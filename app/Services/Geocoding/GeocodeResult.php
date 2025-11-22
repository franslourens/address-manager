<?php

namespace App\Services\Geocoding;

class GeocodeResult
{
    public function __construct(
        public readonly string $formattedAddress,
        public readonly ?float $latitude,
        public readonly ?float $longitude,
        public readonly ?string $postalCode,
        public readonly ?string $city,
        public readonly ?string $state,
        public readonly ?string $countryCode,
        public readonly ?string $locationType,
        public readonly ?string $buildingType,
        public readonly array $raw = [],
    ) {
    }

    public static function fromNeutrinoLocation(array $location): self
    {
        return new self(
            formattedAddress: $location['address'] ?? '',
            latitude: isset($location['latitude']) ? (float) $location['latitude'] : null,
            longitude: isset($location['longitude']) ? (float) $location['longitude'] : null,
            postalCode: $location['postal-code'] ?? null,
            city: $location['city'] ?? null,
            state: $location['state'] ?? null,
            countryCode: $location['country-code'] ?? null,
            locationType: $location['location-type'] ?? null,
            buildingType: $location['building'] ?? null,
            raw: $location,
        );
    }
}