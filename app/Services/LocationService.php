<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class LocationService
{
    protected $provincesPath;
    protected $citiesPath;

    public function __construct()
    {
        $this->provincesPath = storage_path('app/json/provinces.json');
        $this->citiesPath = storage_path('app/json/cities.json');
    }

    /**
     * Get all provinces
     *
     * @return array
     */
    public function getAllProvinces()
    {
        if (!File::exists($this->provincesPath)) {
            return [];
        }

        return json_decode(File::get($this->provincesPath), true) ?? [];
    }

    /**
     * Get province by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getProvinceById($id)
    {
        $provinces = $this->getAllProvinces();
        return collect($provinces)->firstWhere('id', $id);
    }

    /**
     * Get all cities
     *
     * @return array
     */
    public function getAllCities()
    {
        if (!File::exists($this->citiesPath)) {
            return [];
        }

        return json_decode(File::get($this->citiesPath), true) ?? [];
    }

    /**
     * Get cities by province ID
     *
     * @param int $provinceId
     * @return array
     */
    public function getCitiesByProvinceId($provinceId)
    {
        $cities = $this->getAllCities();
        return collect($cities)->where('province_id', $provinceId)->values()->all();
    }

    /**
     * Get city by ID
     *
     * @param int $id
     * @return array|null
     */
    public function getCityById($id)
    {
        $cities = $this->getAllCities();
        return collect($cities)->firstWhere('id', $id);
    }
}
