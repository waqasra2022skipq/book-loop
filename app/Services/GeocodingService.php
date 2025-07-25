<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log as FacadesLog;


class GeocodingService
{
    /**
     * Get latitude and longitude for a given address and city using OpenStreetMap Nominatim API.
     *
     * @param string $address
     * @param string $city
     * @return array{lat: float|null, lng: float|null}
     */
    public function geocode(string $address, string $city): array
    {
        $query = trim($address . ', ' . $city);
        $url = 'https://nominatim.openstreetmap.org/search';
        $response = Http::get($url, [
            'q' => $query,
            'format' => 'json',
            'limit' => 1,
        ]);

        if ($response->ok() && isset($response[0]['lat'], $response[0]['lon'])) {
            return [
                'lat' => (float) $response[0]['lat'],
                'lng' => (float) $response[0]['lon'],
            ];
        }

        return ['lat' => null, 'lng' => null];
    }
}
