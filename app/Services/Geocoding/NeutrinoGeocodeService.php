<?php

namespace App\Services\Geocoding;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NeutrinoGeocodeService implements GeocodeServiceInterface
{
    private string $userId;
    private string $apiKey;

    public function __construct(?string $userId = null, ?string $apiKey = null)
    {
        $this->userId = $userId ?? config('services.neutrino.user_id');
        $this->apiKey = $apiKey ?? config('services.neutrino.api_key');
    }

    public function geocode(string $address, array $options = []): ?GeocodeResult
    {
        if (trim($address) === '') {
            return null;
        }

        $payload = [
            'address'       => $address,
            'house-number'  => $options['house_number'] ?? '',
            'street'        => $options['street'] ?? '',
            'city'          => $options['city'] ?? '',
            'county'        => $options['county'] ?? '',
            'state'         => $options['state'] ?? '',
            'postal-code'   => $options['postal_code'] ?? '',
            'country-code'  => $options['country_code'] ?? 'ZA',
            'language-code' => $options['language_code'] ?? 'en',
            'fuzzy-search'  => $options['fuzzy_search'] ?? 'false',
        ];

        $url = config('services.neutrino.geocode_url');

        $response = Http::asForm()
            ->withHeaders([
                'User-ID' => $this->userId,
                'API-Key' => $this->apiKey,
            ])
            ->timeout(10)
            ->post($url, $payload);

        Log::info('Calling Neutrino API request: ', [
            'url'     => $url,
            'payload' => $payload,
            'status'  => $response->status(),
            'body'    => $response->json(),
        ]);

        if (!$response->successful()) {
            $body = $response->json();

            $apiMessage = $body['api-error-msg'] ?? null;

            Log::error('Neutrino API request failed', [
                'url'     => $url,
                'payload' => $payload,
                'status'  => $response->status(),
                'body'    => $response->body(),
            ]);

            throw new GeocodingException(
                $apiMessage
                    ? "Neutrino API error: {$apiMessage}"
                    : 'Neutrino API error: HTTP ' . $response->status()
            );
        }

        $body = $response->json();
        $location = $body['locations'][0] ?? null;

        if (! $location) {
            return null;
        }

        return GeocodeResult::neutrino($location);
    }
}