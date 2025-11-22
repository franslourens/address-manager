<?php

namespace App\Services\Geocoding;

interface GeocodeServiceInterface
{
    /**
     * @param  string  $address   Human-readable address (line, city, etc)
     * @param  array   $options   Extra options like country-code, language-code
     * @return GeocodeResult|null  Null = no result / not found
     *
     * @throws GeocodingException  On network or API-level failure
     */
    public function geocode(string $address, array $options = []): ?GeocodeResult;
}