<?php

namespace App\Http\Controllers;

use App\Services\RajaOngkirService;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ShippingController extends Controller
{
    protected $rajaOngkirService;

    public function __construct(RajaOngkirService $rajaOngkirService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
    }

    /**
     * Test API connection
     */
    public function testConnection()
    {
        try {
            $result = $this->rajaOngkirService->testConnection();

            \Log::info('RajaOngkir Connection Test', $result);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'details' => [
                    'status' => $result['status'],
                    'api_key_configured' => !empty(config('services.rajaongkir.shipping_cost_key')),
                    'base_url' => 'https://rajaongkir.komerce.id/api/v1',
                    'response_sample' => $result['data'] ? array_slice($result['data'], 0, 2) : null
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Test connection failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get list of provinces
     */
    public function getProvinces()
    {
        try {
            \Log::info('ShippingController: getProvinces called');

            $provinces = $this->rajaOngkirService->getProvinces();

            \Log::info('ShippingController: provinces retrieved', [
                'count' => count($provinces),
                'sample' => array_slice($provinces, 0, 3)
            ]);

            if (empty($provinces)) {
                \Log::warning('ShippingController: No provinces returned, using fallback');
            }

            return response()->json([
                'success' => true,
                'message' => count($provinces) > 0 ? 'Provinces loaded successfully' : 'Using fallback provinces',
                'data' => $provinces,
                'count' => count($provinces)
            ]);

        } catch (\Exception $e) {
            \Log::error('ShippingController: getProvinces error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data provinsi: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get cities by province ID
     */
    public function getCities($provinceId = null)
    {
        try {
            \Log::info('ShippingController: getCities called', [
                'province_id' => $provinceId
            ]);

            if (!$provinceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Province ID is required',
                    'data' => []
                ], 400);
            }

            $cities = $this->rajaOngkirService->getCities($provinceId);

            \Log::info('ShippingController: cities retrieved', [
                'province_id' => $provinceId,
                'count' => count($cities),
                'sample' => array_slice($cities, 0, 3)
            ]);

            return response()->json([
                'success' => true,
                'message' => count($cities) > 0 ? 'Cities loaded successfully' : 'No cities found for this province',
                'data' => $cities,
                'count' => count($cities)
            ]);

        } catch (\Exception $e) {
            \Log::error('ShippingController: getCities error', [
                'province_id' => $provinceId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * ✅ NEW: Get districts by city ID
     */
    public function getDistricts($cityId = null)
    {
        try {
            \Log::info('ShippingController: getDistricts called', [
                'city_id' => $cityId
            ]);

            if (!$cityId) {
                return response()->json([
                    'success' => false,
                    'message' => 'City ID is required',
                    'data' => []
                ], 400);
            }

            $districts = $this->rajaOngkirService->getDistricts($cityId);

            \Log::info('ShippingController: districts retrieved', [
                'city_id' => $cityId,
                'count' => count($districts),
                'sample' => array_slice($districts, 0, 3)
            ]);

            return response()->json([
                'success' => true,
                'message' => count($districts) > 0 ? 'Districts loaded successfully' : 'No districts found for this city',
                'data' => $districts,
                'count' => count($districts)
            ]);

        } catch (\Exception $e) {
            \Log::error('ShippingController: getDistricts error', [
                'city_id' => $cityId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kecamatan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get popular cities for quick selection
     */
    public function getPopularCities()
    {
        try {
            $cities = $this->rajaOngkirService->getPopularCities();

            return response()->json([
                'success' => true,
                'message' => 'Popular cities loaded successfully',
                'data' => $cities
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kota populer: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * ✅ NEW: Get popular districts for quick selection
     */
    public function getPopularDistricts()
    {
        try {
            $districts = $this->rajaOngkirService->getPopularDistricts();

            return response()->json([
                'success' => true,
                'message' => 'Popular districts loaded successfully',
                'data' => $districts
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengambil data kecamatan populer: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * ✅ UPDATED: Calculate shipping cost (now using districts)
     */
    public function calculateCost(Request $request)
    {
        try {
            $validated = $request->validate([
                'origin_district_id' => 'required|integer',
                'destination_district_id' => 'required|integer',
                'weight' => 'required|numeric|min:0.1',
                'courier' => 'nullable|string|in:jne,pos,tiki,sicepat,jnt,ninja,lion,anteraja,rex,wahana,all',

                // ✅ BACKWARD COMPATIBILITY: Accept city IDs as fallback
                'origin_city_id' => 'nullable|integer',
                'destination_city_id' => 'nullable|integer',
            ]);

            \Log::info('ShippingController: calculateCost called', $validated);

            // ✅ SMART FALLBACK: Use district if available, otherwise auto-get first district from city
            $originDistrictId = $validated['origin_district_id'];
            $destinationDistrictId = $validated['destination_district_id'];

            // Fallback logic for backward compatibility
            if (!$originDistrictId && !empty($validated['origin_city_id'])) {
                $firstDistrict = $this->rajaOngkirService->getFirstDistrictFromCity($validated['origin_city_id']);
                $originDistrictId = $firstDistrict['district_id'] ?? $validated['origin_city_id'];
            }

            if (!$destinationDistrictId && !empty($validated['destination_city_id'])) {
                $firstDistrict = $this->rajaOngkirService->getFirstDistrictFromCity($validated['destination_city_id']);
                $destinationDistrictId = $firstDistrict['district_id'] ?? $validated['destination_city_id'];
            }

            $weight = max($validated['weight'], 1); // Minimum 1kg
            $courier = ($validated['courier'] === 'all') ? null : $validated['courier'];

            $shippingCosts = $this->rajaOngkirService->getShippingCost(
                $originDistrictId,
                $destinationDistrictId,
                $weight * 1000, // Convert to grams
                $courier
            );

            \Log::info('ShippingController: shipping costs calculated', [
                'count' => count($shippingCosts),
                'sample' => array_slice($shippingCosts, 0, 2)
            ]);

            return response()->json([
                'success' => true,
                'message' => count($shippingCosts) > 0 ? 'Shipping costs calculated' : 'Using fallback shipping costs',
                'data' => [
                    'shipping_options' => $shippingCosts,
                    'origin_district_id' => $originDistrictId,
                    'destination_district_id' => $destinationDistrictId,
                    'weight' => $weight,
                    'courier' => $courier
                ]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('ShippingController: calculateCost error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Track shipment
     */
    public function trackShipment(Request $request)
    {
        try {
            $validated = $request->validate([
                'receipt_number' => 'required|string',
                'courier' => 'required|string|in:jne,pos,tiki'
            ]);

            \Log::info('ShippingController: trackShipment called', $validated);

            $trackingInfo = $this->rajaOngkirService->trackShipment(
                $validated['receipt_number'],
                $validated['courier']
            );

            if (!$trackingInfo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data pengiriman tidak ditemukan atau nomor resi tidak valid'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Tracking information retrieved successfully',
                'data' => $trackingInfo
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('ShippingController: trackShipment error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal melacak pengiriman: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get shipping info for debugging
     */
    public function getShippingInfo()
    {
        try {
            $config = [
                'base_url' => 'https://rajaongkir.komerce.id/api/v1/',
                'shipping_cost_key_configured' => !empty(config('services.rajaongkir.shipping_cost_key')),
                'shipping_delivery_key_configured' => !empty(config('services.rajaongkir.shipping_delivery_key')),
                'origin_district_id' => config('services.rajaongkir.origin_district_id', 'Not configured'), // ✅ NEW
                'origin_city_id' => config('services.rajaongkir.origin_city_id', 'Not configured'), // Keep for fallback
                'cache_enabled' => config('cache.default') !== null,
            ];

            // Test basic connection
            $connectionTest = $this->rajaOngkirService->testConnection();

            return response()->json([
                'success' => true,
                'config' => $config,
                'connection_test' => $connectionTest,
                'suggestions' => [
                    'Pastikan API key sudah dikonfigurasi di config/services.php',
                    '✅ NEW: Pastikan origin_district_id sudah dikonfigurasi untuk akurasi shipping',
                    'Cek koneksi internet server',
                    'Pastikan API key masih aktif dan valid',
                    'Cek log Laravel untuk error detail: storage/logs/laravel.log'
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error getting shipping info: ' . $e->getMessage()
            ], 500);
        }
    }
}
