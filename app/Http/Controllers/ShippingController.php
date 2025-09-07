<?php

namespace App\Http\Controllers;

use App\Services\LocationService;
use App\Services\RajaOngkirService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ShippingController extends Controller
{
    protected $rajaOngkirService;
    protected $locationService;

    public function __construct(RajaOngkirService $rajaOngkirService, LocationService $locationService)
    {
        $this->rajaOngkirService = $rajaOngkirService;
        $this->locationService = $locationService;
    }

    /**
     * Get list of provinces from local JSON
     */
    public function getProvinces(): JsonResponse
    {
        try {
            Log::info('ShippingController: getProvinces called');

            $provinces = $this->locationService->getAllProvinces();
            
            // Transform data ke format yang diharapkan oleh frontend
            $transformedProvinces = array_map(function ($province) {
                return [
                    'province_id' => (string) $province['id'],
                    'province' => $province['name']
                ];
            }, $provinces);

            Log::info('ShippingController: provinces retrieved', [
                'count' => count($transformedProvinces)
            ]);

            return response()->json([
                'success' => true,
                'data' => $transformedProvinces
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingController: getProvinces error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data provinsi: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get cities by province ID from local JSON
     */
    public function getCities($provinceId = null): JsonResponse
    {
        try {
            Log::info('ShippingController: getCities called', [
                'province_id' => $provinceId
            ]);

            if (!$provinceId) {
                return response()->json([
                    'success' => false,
                    'message' => 'Province ID is required',
                    'data' => []
                ], 400);
            }

            $cities = $this->locationService->getCitiesByProvinceId($provinceId);
            
            // Transform data ke format yang diharapkan oleh frontend
            $transformedCities = array_map(function ($city) {
                return [
                    'city_id' => (string) $city['id'],
                    'city_name' => $city['name']
                ];
            }, $cities);

            Log::info('ShippingController: cities retrieved', [
                'province_id' => $provinceId,
                'count' => count($transformedCities)
            ]);

            return response()->json([
                'success' => true,
                'data' => $transformedCities
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingController: getCities error', [
                'province_id' => $provinceId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kota: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get districts from RajaOngkir API
     */
    public function getDistricts($cityId = null): JsonResponse
    {
        try {
            Log::info('ShippingController: getDistricts called', [
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

            Log::info('ShippingController: districts retrieved', [
                'city_id' => $cityId,
                'count' => count($districts)
            ]);

            return response()->json([
                'success' => true,
                'data' => $districts
            ]);

        } catch (\Exception $e) {
            Log::error('ShippingController: getDistricts error', [
                'city_id' => $cityId,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat data kecamatan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Calculate shipping cost using RajaOngkir API
     */
    public function calculateCost(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'destination_city_id' => 'required|integer',
                'weight' => 'required|numeric|min:0.1',
                'courier' => 'nullable|string|in:jne,pos,tiki,sicepat,jnt,ninja,lion,anteraja,rex,wahana,all'
            ]);

            Log::info('ShippingController: calculateCost called', $validated);

            $shippingCosts = $this->rajaOngkirService->getShippingCost(
                config('services.rajaongkir.origin_city_id'),
                $validated['destination_city_id'],
                $validated['weight'] * 1000, // Convert to grams
                $validated['courier'] ?? null
            );

            return response()->json([
                'success' => true,
                'data' => $shippingCosts
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak valid',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('ShippingController: calculateCost error', [
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Gagal menghitung ongkos kirim: ' . $e->getMessage()
            ], 500);
        }
    }
}
