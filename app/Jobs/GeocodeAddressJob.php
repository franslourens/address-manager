<?php

namespace App\Jobs;

use App\Models\Address;
use App\Services\Geocoding\GeocodeServiceInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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

        if (! $address) {
            return;
        }

        $address->update([
            'status'     => Address::STATUS_PROCESSING,
            'last_error' => null,
        ]);

        try {
            $coords = $geocoder->geocode($address);

            $address->update([
                'latitude'  => $coords['lat'],
                'longitude' => $coords['lng'],
                'status'    => Address::STATUS_SUCCESS,
                'last_error'=> null,
            ]);
        } catch (Throwable $e) {
            $address->update([
                'status'     => Address::STATUS_FAILED,
                'last_error' => $e->getMessage(),
            ]);
        }
    }
}