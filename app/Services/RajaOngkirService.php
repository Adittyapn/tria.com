<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class RajaOngkirService
{
    private $baseUrl;
    private $shippingCostApiKey;
    private $shippingDeliveryApiKey;

    public function __construct()
    {
        $this->baseUrl = 'https://rajaongkir.komerce.id/api/v1/';
        $this->shippingCostApiKey = config('services.rajaongkir.shipping_cost_key');
        $this->shippingDeliveryApiKey = config('services.rajaongkir.shipping_delivery_key');
    }

    /**
     * Get list of provinces
     */
    public function getProvinces()
    {
        $cacheKey = 'rajaongkir_provinces';

        return Cache::remember($cacheKey, 3600, function () {
            try {
                $url = $this->baseUrl . 'destination/province';

                Log::info('RajaOngkir: Getting provinces', [
                    'url' => $url,
                    'api_key_exists' => !empty($this->shippingCostApiKey)
                ]);

                $response = Http::timeout(30)->withHeaders([
                    'Key' => $this->shippingCostApiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->get($url);

                Log::info('RajaOngkir: Province response', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                        $provinces = collect($data['data'])->map(function ($province) {
                            return [
                                'province_id' => (string) $province['id'],
                                'province' => $province['name']
                            ];
                        })->toArray();

                        Log::info('RajaOngkir: Provinces formatted', [
                            'count' => count($provinces),
                            'sample' => array_slice($provinces, 0, 2)
                        ]);

                        return $provinces;
                    }
                }

                Log::error('RajaOngkir API Error - Get Provinces', [
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return $this->getFallbackProvinces();

            } catch (\Exception $e) {
                Log::error('RajaOngkir Service Error - Get Provinces', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return $this->getFallbackProvinces();
            }
        });
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId = null)
    {
        if (!$provinceId) {
            Log::warning('RajaOngkir: No province ID provided for getCities');
            return [];
        }

        $cacheKey = "rajaongkir_cities_{$provinceId}";

        return Cache::remember($cacheKey, 3600, function () use ($provinceId) {
            try {
                $url = $this->baseUrl . "destination/city/{$provinceId}";

                Log::info('RajaOngkir: Getting cities', [
                    'province_id' => $provinceId,
                    'url' => $url
                ]);

                $response = Http::timeout(30)->withHeaders([
                    'Key' => $this->shippingCostApiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->get($url);

                Log::info('RajaOngkir: Cities response', [
                    'status' => $response->status(),
                    'body_length' => strlen($response->body())
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                        $cities = collect($data['data'])->map(function ($city) {
                            return [
                                'city_id' => (string) $city['id'],
                                'city_name' => $city['name'],
                                'zip_code' => $city['zip_code'] ?? null
                            ];
                        })->toArray();

                        Log::info('RajaOngkir: Cities formatted', [
                            'province_id' => $provinceId,
                            'count' => count($cities),
                            'sample' => array_slice($cities, 0, 2)
                        ]);

                        return $cities;
                    }

                    Log::warning('RajaOngkir: Unexpected response format for cities', [
                        'province_id' => $provinceId,
                        'data' => $data
                    ]);
                    return $this->getFallbackCities($provinceId);
                }

                Log::error('RajaOngkir API Error - Get Cities', [
                    'province_id' => $provinceId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return $this->getFallbackCities($provinceId);

            } catch (\Exception $e) {
                Log::error('RajaOngkir Service Error - Get Cities', [
                    'province_id' => $provinceId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return $this->getFallbackCities($provinceId);
            }
        });
    }

    /**
     * ✅ NEW: Get districts by city ID
     */
    public function getDistricts($cityId = null)
    {
        if (!$cityId) {
            Log::warning('RajaOngkir: No city ID provided for getDistricts');
            return [];
        }

        $cacheKey = "rajaongkir_districts_{$cityId}";

        return Cache::remember($cacheKey, 3600, function () use ($cityId) {
            try {
                $url = $this->baseUrl . "destination/district/{$cityId}";

                Log::info('RajaOngkir: Getting districts', [
                    'city_id' => $cityId,
                    'url' => $url
                ]);

                $response = Http::timeout(30)->withHeaders([
                    'Key' => $this->shippingCostApiKey,
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json'
                ])->get($url);

                Log::info('RajaOngkir: Districts response', [
                    'status' => $response->status(),
                    'body_length' => strlen($response->body())
                ]);

                if ($response->successful()) {
                    $data = $response->json();

                    if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                        $districts = collect($data['data'])->map(function ($district) {
                            return [
                                'district_id' => (string) $district['id'],
                                'district_name' => $district['name'],
                                'zip_code' => $district['zip_code'] ?? null
                            ];
                        })->toArray();

                        Log::info('RajaOngkir: Districts formatted', [
                            'city_id' => $cityId,
                            'count' => count($districts),
                            'sample' => array_slice($districts, 0, 2)
                        ]);

                        return $districts;
                    }

                    Log::warning('RajaOngkir: Unexpected response format for districts', [
                        'city_id' => $cityId,
                        'data' => $data
                    ]);
                    return $this->getFallbackDistricts($cityId);
                }

                Log::error('RajaOngkir API Error - Get Districts', [
                    'city_id' => $cityId,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);

                return $this->getFallbackDistricts($cityId);

            } catch (\Exception $e) {
                Log::error('RajaOngkir Service Error - Get Districts', [
                    'city_id' => $cityId,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return $this->getFallbackDistricts($cityId);
            }
        });
    }

    /**
     * ✅ UPDATED: Get shipping cost calculation (now using DISTRICT endpoint)
     */
    public function getShippingCost($originDistrictId, $destinationDistrictId, $weight, $courier = null)
    {
        try {
            Log::info('RajaOngkir: Calculating shipping cost', [
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'weight' => $weight,
                'courier' => $courier
            ]);

            // ✅ FIXED: Use district endpoint instead of city
            $url = $this->baseUrl . 'calculate/district/domestic-cost';

            $response = Http::timeout(60)->withHeaders([
                'Key' => $this->shippingCostApiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($url, [
                'origin' => $originDistrictId,
                'destination' => $destinationDistrictId,
                'weight' => max($weight, 1000), // Minimum 1kg
                'courier' => $courier ?: 'jne:pos:tiki:sicepat:jnt:ninja:lion:anteraja:rex:wahana',
                'price' => 'lowest'
            ]);

            Log::info('RajaOngkir: Shipping cost raw response', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['meta']['code']) && $data['meta']['code'] == 200 && isset($data['data'])) {
                    Log::info('RajaOngkir: Shipping cost response success', [
                        'data_count' => count($data['data'])
                    ]);
                    return $this->formatShippingResults($data['data']);
                }

                Log::warning('RajaOngkir: Unexpected shipping cost response format', [
                    'data' => $data
                ]);
                return $this->getFallbackShippingOptions();
            }

            Log::error('RajaOngkir API Error - Get Shipping Cost', [
                'status' => $response->status(),
                'response' => $response->body()
            ]);

            return $this->getFallbackShippingOptions();

        } catch (\Exception $e) {
            Log::error('RajaOngkir Service Error - Get Shipping Cost', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return $this->getFallbackShippingOptions();
        }
    }

    /**
     * ✅ UPDATED: Format shipping results for consistent output
     */
    private function formatShippingResults($results)
    {
        $formattedResults = [];

        foreach ($results as $result) {
            // Handle new response format from district endpoint
            $courierCode = $result['code'] ?? '';
            $courierName = strtoupper($result['name'] ?? $courierCode);
            $service = $result['service'] ?? 'REG';
            $description = $result['description'] ?? 'Layanan Regular';
            $cost = (int) ($result['cost'] ?? 0);
            $etd = $result['etd'] ?? '2-3 day';

            $formattedResults[] = [
                'courier' => $courierCode,
                'courier_name' => $courierName,
                'service' => $service,
                'service_name' => $courierName . ' ' . $service,
                'description' => $description,
                'cost' => $cost,
                'etd' => $etd,
                'note' => ''
            ];
        }

        // Sort by cost (cheapest first)
        usort($formattedResults, function($a, $b) {
            return $a['cost'] <=> $b['cost'];
        });

        Log::info('RajaOngkir: Formatted shipping results', [
            'count' => count($formattedResults),
            'sample' => array_slice($formattedResults, 0, 2)
        ]);

        return $formattedResults;
    }

    /**
     * Track shipment (using delivery API key)
     */
    public function trackShipment($receiptNumber, $courier)
    {
        try {
            $url = $this->baseUrl . 'waybill';

            Log::info('RajaOngkir: Tracking shipment', [
                'receipt' => $receiptNumber,
                'courier' => $courier,
                'url' => $url
            ]);

            $response = Http::timeout(30)->withHeaders([
                'Key' => $this->shippingDeliveryApiKey,
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->asForm()->post($url, [
                'waybill' => $receiptNumber,
                'courier' => $courier
            ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['meta']['code']) && $data['meta']['code'] == 200) {
                    return $data['data'] ?? null;
                }
            }

            Log::error('RajaOngkir API Error - Track Shipment', [
                'receipt' => $receiptNumber,
                'courier' => $courier,
                'response' => $response->json()
            ]);

            return null;

        } catch (\Exception $e) {
            Log::error('RajaOngkir Service Error - Track Shipment', [
                'receipt' => $receiptNumber,
                'courier' => $courier,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * ✅ NEW: Get popular districts for quick selection
     */
    public function getPopularDistricts()
    {
        return Cache::remember('rajaongkir_popular_districts', 86400, function () {
            return [
                ['district_id' => '1360', 'district_name' => 'JAKARTA SELATAN', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
                ['district_id' => '1361', 'district_name' => 'JAGAKARSA', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
                ['district_id' => '1362', 'district_name' => 'KEBAYORAN BARU', 'city' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
                ['district_id' => '574', 'district_name' => 'BANDUNG WETAN', 'city' => 'Bandung', 'province' => 'Jawa Barat'],
                ['district_id' => '575', 'district_name' => 'BANDUNG KULON', 'city' => 'Bandung', 'province' => 'Jawa Barat'],
                ['district_id' => '576', 'district_name' => 'BOJONGLOA KALER', 'city' => 'Bandung', 'province' => 'Jawa Barat'],
            ];
        });
    }

    /**
     * ✅ HELPER: Auto-select first district from city (for backward compatibility)
     */
    public function getFirstDistrictFromCity($cityId)
    {
        $districts = $this->getDistricts($cityId);
        return !empty($districts) ? $districts[0] : null;
    }

    /**
     * Find district by name (helper method)
     */
    public function findDistrictByName($districtName, $cityId = null)
    {
        $districts = $this->getDistricts($cityId);

        return collect($districts)->filter(function ($district) use ($districtName) {
            return stripos($district['district_name'], $districtName) !== false;
        })->first();
    }

    // ========================================
    // FALLBACK METHODS
    // ========================================

    private function getFallbackProvinces()
    {
        return [
            ['province_id' => '1', 'province' => 'DKI Jakarta'],
            ['province_id' => '2', 'province' => 'Jawa Barat'],
            ['province_id' => '3', 'province' => 'Jawa Tengah'],
            ['province_id' => '4', 'province' => 'Jawa Timur'],
            ['province_id' => '5', 'province' => 'DI Yogyakarta'],
        ];
    }

    private function getFallbackCities($provinceId)
    {
        $fallbackData = [
            '1' => [['city_id' => '575', 'city_name' => 'Jakarta Selatan', 'zip_code' => '12000']],
            '2' => [['city_id' => '39', 'city_name' => 'Bandung', 'zip_code' => '40000']],
            '3' => [['city_id' => '399', 'city_name' => 'Semarang', 'zip_code' => '50000']],
            '4' => [['city_id' => '444', 'city_name' => 'Surabaya', 'zip_code' => '60000']],
            '5' => [['city_id' => '501', 'city_name' => 'Yogyakarta', 'zip_code' => '55000']],
        ];

        return $fallbackData[$provinceId] ?? [];
    }

    /**
     * ✅ NEW: Fallback districts
     */
    private function getFallbackDistricts($cityId)
    {
        $fallbackData = [
            '575' => [ // Jakarta Selatan
                ['district_id' => '1360', 'district_name' => 'JAKARTA SELATAN', 'zip_code' => '12000'],
                ['district_id' => '1361', 'district_name' => 'JAGAKARSA', 'zip_code' => '12630'],
                ['district_id' => '1362', 'district_name' => 'KEBAYORAN BARU', 'zip_code' => '12150'],
            ],
            '39' => [ // Bandung
                ['district_id' => '574', 'district_name' => 'BANDUNG WETAN', 'zip_code' => '40117'],
                ['district_id' => '575', 'district_name' => 'BANDUNG KULON', 'zip_code' => '40212'],
                ['district_id' => '576', 'district_name' => 'BOJONGLOA KALER', 'zip_code' => '40231'],
            ]
        ];

        return $fallbackData[$cityId] ?? [
            ['district_id' => $cityId . '01', 'district_name' => 'Pusat Kota', 'zip_code' => '00000']
        ];
    }

    /**
     * Get fallback shipping options when API fails
     */
    private function getFallbackShippingOptions()
    {
        return [
            [
                'courier' => 'jne',
                'courier_name' => 'JNE',
                'service' => 'REG',
                'service_name' => 'JNE REG',
                'description' => 'Layanan Reguler',
                'cost' => 15000,
                'etd' => '2-3 hari',
                'note' => 'Estimasi'
            ],
            [
                'courier' => 'jne',
                'courier_name' => 'JNE',
                'service' => 'YES',
                'service_name' => 'JNE YES',
                'description' => 'Yakin Esok Sampai',
                'cost' => 25000,
                'etd' => '1-1 hari',
                'note' => 'Estimasi'
            ],
            [
                'courier' => 'pos',
                'courier_name' => 'POS',
                'service' => 'Paket Kilat Khusus',
                'service_name' => 'POS Reguler',
                'description' => 'Pos Indonesia Reguler',
                'cost' => 12000,
                'etd' => '3-4 hari',
                'note' => 'Estimasi'
            ]
        ];
    }

    /**
     * Find city by name (helper method)
     */
    public function findCityByName($cityName, $provinceId = null)
    {
        $cities = $this->getCities($provinceId);

        return collect($cities)->filter(function ($city) use ($cityName) {
            return stripos($city['city_name'], $cityName) !== false;
        })->first();
    }

    /**
     * Get popular cities for quick selection
     */
    public function getPopularCities()
    {
        return Cache::remember('rajaongkir_popular_cities', 86400, function () {
            return [
                ['city_id' => '575', 'city_name' => 'Jakarta Selatan', 'province' => 'DKI Jakarta'],
                ['city_id' => '39', 'city_name' => 'Bandung', 'province' => 'Jawa Barat'],
                ['city_id' => '444', 'city_name' => 'Surabaya', 'province' => 'Jawa Timur'],
                ['city_id' => '501', 'city_name' => 'Yogyakarta', 'province' => 'DI Yogyakarta'],
            ];
        });
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            $url = $this->baseUrl . 'destination/province';

            $response = Http::timeout(10)->withHeaders([
                'Key' => $this->shippingCostApiKey,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ])->get($url);

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful() ? 'Connection OK' : 'Connection Failed',
                'data' => $response->json()
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 0,
                'message' => 'Exception: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
