<?php

namespace App\Jobs;

use App\Models\Address;
use App\Models\GeocodeResult;
use App\Services\Geocoding\GeocodeServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GeocodeAddressJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;

    public function __construct(
        public Address $address,
    ) {}

    public function handle(GeocodeServiceInterface $geocoder): void
    {
        $address = $this->address;

        Log::info('GeocodeAddressJob handle() called', [
            'address_id' => $address->id,
        ]);

        Log::info('Loaded address in job', [
            'address_id'     => $address->id,
            'address_exists' => (bool) $address,
        ]);

        if (! $address) {
            return;
        }

        $address->update([
            'status'     => Address::STATUS_PROCESSING,
            'last_error' => null,
        ]);

        try {

            $options = [
                'house_number'  => $address->house_number ?? null,
                'street'        => $address->line1 ?? null,
                'city'          => $address->city ?? null,
                'county'        => null,
                'state'         => $address->province ?? null,
                'postal_code'   => $address->postal ?? null,
                'country_code'  => $address->country_code ?? null,
                'language_code' => 'en',
            ];

            $result = $geocoder->geocode($address->line1, $options);

            if (! $result) {
                Log::warning('No geocode results found', [
                    'address_id' => $address->id,
                    'line1'      => $address->line1,
                ]);

                $address->update([
                    'status'     => Address::STATUS_FAILED,
                    'last_error' => 'No geocode results found',
                ]);

                return;
            }

            GeocodeResult::create([
                'address_id'        => $address->id,
                'latitude'          => $result->latitude,
                'longitude'         => $result->longitude,
                'country'           => $result->countryCode,
                'city'              => $result->city,
                'postal_code'       => $result->postalCode,
                'location_type'     => $result->locationType,
                'raw'               => $result->raw,
            ]);

            Log::info('Neutrino raw location', [
                'address_id' => $address->id,
                'raw'        => $result->raw,
            ]);

            $address->update([
                'latitude'  => $result->latitude,
                'longitude' => $result->longitude,
                'status'    => Address::STATUS_SUCCESS,
            ]);
        } catch (Throwable $e) {
            Log::error('Geocode job failed', [
                'address_id' => $address->id ?? null,
                'error'      => $e->getMessage(),
            ]);

            $address?->update([
                'status'     => Address::STATUS_FAILED,
                'last_error' => $e->getMessage(),
            ]);
        }
    }
}