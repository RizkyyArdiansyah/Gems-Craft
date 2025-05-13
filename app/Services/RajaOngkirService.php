<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.rajaongkir.api_key');
        $this->baseUrl = config('services.rajaongkir.base_url');
    }

    public function getProvinces()
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get("{$this->baseUrl}/province");

        return $response->json()['rajaongkir']['results'] ?? [];
    }

    public function getCities($provinceId)
    {
        $response = Http::withHeaders([
            'key' => $this->apiKey
        ])->get("{$this->baseUrl}/city", ['province' => $provinceId]);

        return $response->json()['rajaongkir']['results'] ?? [];
    }

    public function getShippingCost($origin, $destination, $weight, $courier)
{
    $response = Http::withHeaders([
        'key' => $this->apiKey
    ])->asForm()->post("{$this->baseUrl}/cost", [ // Pakai `asForm()`
        'origin' => $origin,
        'destination' => $destination,
        'weight' => $weight,
        'courier' => $courier
    ]);

    return $response->json()['rajaongkir']['results'] ?? [];
}

}
