@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
    <style>
        /* Custom animations for checkout */
        @keyframes pulse-glow {
            0%,
            100% {
                box-shadow: 0 0 5px rgba(59, 130, 246, 0.3);
            }
            50% {
                box-shadow:
                    0 0 15px rgba(59, 130, 246, 0.6),
                    0 0 25px rgba(59, 130, 246, 0.4);
            }
        }

        @keyframes float {
            0%,
            100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-3px);
            }
        }

        .step-glow {
            animation: pulse-glow 2s ease-in-out infinite;
        }

        .step-float:hover {
            animation: float 0.6s ease-in-out infinite;
        }

        .shipping-option-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .shipping-option-card:hover {
            transform: translateY(-2px);
        }

        .progress-line-animate {
            background: linear-gradient(
                90deg,
                rgba(59, 130, 246, 0.8) 0%,
                rgba(59, 130, 246, 1) 50%,
                rgba(59, 130, 246, 0.8) 100%
            );
            background-size: 200% 100%;
            animation: shimmer 2s infinite;
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }
            100% {
                background-position: 200% 0;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-6 md:py-8" x-data="checkoutManager()" x-init="init()">
        <!-- Progress Steps -->
        <div class="mb-8">
            <!-- Desktop Progress Steps -->
            <div class="hidden md:block">
                <div class="flex items-center justify-center mb-6">
                    <div class="flex items-center space-x-4 relative">
                        <!-- Step 1: Cart -->
                        <div class="flex flex-col items-center relative z-10">
                            <div class="relative step-float">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full flex items-center justify-center font-semibold shadow-lg transform transition-all duration-300 hover:scale-110"
                                >
                                    <i class="fas fa-shopping-cart text-lg"></i>
                                </div>
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full blur opacity-25 animate-pulse"
                                ></div>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-blue-600 transition-colors">Keranjang</span>
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-1 animate-pulse"></div>
                        </div>

                        <!-- Connection Line 1 -->
                        <div
                            class="flex-1 h-1 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full relative overflow-hidden min-w-[80px] lg:min-w-[120px] progress-line-animate"
                        ></div>

                        <!-- Step 2: Checkout (Current) -->
                        <div class="flex flex-col items-center relative z-10">
                            <div class="relative step-float">
                                <div
                                    class="w-12 h-12 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full flex items-center justify-center font-semibold shadow-lg transform transition-all duration-300 hover:scale-110 ring-4 ring-blue-200 step-glow"
                                >
                                    <i class="fas fa-credit-card text-lg"></i>
                                </div>
                                <div
                                    class="absolute -inset-1 bg-gradient-to-r from-blue-600 to-blue-700 rounded-full blur opacity-30"
                                ></div>
                            </div>
                            <span class="mt-3 text-sm font-semibold text-blue-600 transition-colors">Checkout</span>
                            <div class="w-2 h-2 bg-blue-600 rounded-full mt-1 animate-bounce"></div>
                        </div>

                        <!-- Connection Line 2 -->
                        <div
                            class="flex-1 h-1 bg-gray-200 rounded-full relative overflow-hidden min-w-[80px] lg:min-w-[120px]"
                        >
                            <div
                                class="w-0 h-full bg-gradient-to-r from-blue-600 to-blue-700 rounded-full transition-all duration-1000 ease-out"
                            ></div>
                        </div>

                        <!-- Step 3: Complete -->
                        <div class="flex flex-col items-center relative z-10">
                            <div class="relative">
                                <div
                                    class="w-12 h-12 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center font-semibold shadow-sm transition-all duration-300 hover:bg-gray-300"
                                >
                                    <i class="fas fa-check text-lg"></i>
                                </div>
                            </div>
                            <span class="mt-3 text-sm font-medium text-gray-400">Selesai</span>
                            <div class="w-2 h-2 bg-gray-300 rounded-full mt-1"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Progress Steps -->
            <div class="md:hidden mb-6">
                <div class="relative">
                    <!-- Progress Bar Background -->
                    <div class="w-full bg-gray-200 rounded-full h-2 mb-4">
                        <div
                            class="bg-gradient-to-r from-blue-600 to-blue-700 h-2 rounded-full transition-all duration-300"
                            style="width: 66.66%"
                        ></div>
                    </div>

                    <!-- Steps -->
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full flex items-center justify-center text-sm"
                            >
                                <i class="fas fa-check"></i>
                            </div>
                            <span class="text-xs font-medium text-blue-600 mt-1">Keranjang</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-full flex items-center justify-center text-sm ring-2 ring-blue-200"
                            >
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <span class="text-xs font-semibold text-blue-600 mt-1">Checkout</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <div
                                class="w-8 h-8 bg-gray-200 text-gray-400 rounded-full flex items-center justify-center text-sm"
                            >
                                <i class="fas fa-flag-checkered"></i>
                            </div>
                            <span class="text-xs font-medium text-gray-400 mt-1">Selesai</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <h1
                    class="text-2xl md:text-3xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-2"
                >
                    Checkout Pesanan
                </h1>
                <p class="text-gray-600 text-sm md:text-base">Lengkapi informasi untuk menyelesaikan pesanan Anda</p>
            </div>
        </div>

        <form @submit.prevent="processCheckout()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-2 md:mr-3 text-blue-600"></i>
                        Informasi Pelanggan
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
                            <input
                                type="text"
                                x-model="form.customer_name"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="errors.customer_name ? 'border-red-500' : ''"
                                required
                            />
                            <div
                                x-show="errors.customer_name"
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.customer_name?.[0]"
                            ></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                            <input
                                type="email"
                                x-model="form.customer_email"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="errors.customer_email ? 'border-red-500' : ''"
                                required
                            />
                            <div
                                x-show="errors.customer_email"
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.customer_email?.[0]"
                            ></div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                            <input
                                type="tel"
                                x-model="form.customer_phone"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                placeholder="08xxxxxxxxxx"
                            />
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap *</label>
                        <textarea
                            x-model="form.customer_address"
                            @input="if (!form.shipping_address) form.shipping_address = form.customer_address; checkSingaparnaAddress()"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="errors.customer_address ? 'border-red-500' : ''"
                            placeholder="Jalan, No. Rumah, RT/RW, Kelurahan, Kecamatan"
                            required
                        ></textarea>
                        <div
                            x-show="errors.customer_address"
                            class="text-red-500 text-sm mt-1"
                            x-text="errors.customer_address?.[0]"
                        ></div>
                        <div
                            x-show="isSingaparnaAddress"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform -translate-y-4 scale-95"
                            x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                            x-transition:leave="transition ease-in duration-300"
                            x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                            x-transition:leave-end="opacity-0 transform -translate-y-4 scale-95"
                            class="mt-2 p-3 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg shadow-sm"
                        >
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                        <i class="fas fa-map-marker-alt text-green-600 text-sm"></i>
                                    </div>
                                </div>
                                <div class="ml-3 flex-1">
                                    <div class="flex items-center">
                                        <p class="text-sm font-semibold text-green-800">
                                            Alamat Singaparna Terdeteksi!
                                        </p>
                                        <div class="ml-2 w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    </div>
                                    <p class="text-xs text-green-700 mt-1 flex items-center">
                                        <i class="fas fa-store mr-1"></i>
                                        Opsi kurir toko tersedia dengan tarif khusus
                                    </p>
                                </div>
                                <div class="flex-shrink-0 ml-2">
                                    <span
                                        class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 animate-pulse"
                                    >
                                        <i class="fas fa-star mr-1"></i>
                                        Hemat
                                    </span>
                                </div>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            Alamat ini akan digunakan sebagai alamat pengiriman juga
                        </p>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-2 md:mr-3 text-blue-600"></i>
                        Alamat Pengiriman
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Province Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                            <select
                                x-model="form.shipping_province_id"
                                @change="resetShippingSelection(); shippingOptions = []; loadCities($event.target.value); form.shipping_city_id = ''; form.shipping_district_id = ''; cities = []; districts = [];"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none cursor-pointer"
                                :class="errors.shipping_province_id ? 'border-red-500' : ''"
                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"%236B7280\"><path fill-rule=\"evenodd\" d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" clip-rule=\"evenodd\"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;"
                                required
                            >
                                <option value="" x-text="loadingProvinces ? 'Memuat provinsi...' : 'Pilih Provinsi'"></option>
                                <template x-for="province in provinces" :key="province.province_id">
                                    <option :value="province.province_id" x-text="province.province"></option>
                                </template>
                            </select>
                            <div x-show="loadingProvinces" class="text-sm text-blue-600 mt-1 flex items-center">
                                <svg
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Memuat provinsi...
                            </div>
                            <div x-show="!loadingProvinces && provinces.length > 0" class="text-xs text-gray-500 mt-1">
                                <span x-text="provinces.length"></span> provinsi tersedia (diurutkan A-Z)
                            </div>
                            <div
                                x-show="errors.shipping_province_id"
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.shipping_province_id?.[0]"
                            ></div>
                        </div>

                        <!-- City Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kota *</label>
                            <select
                                x-model="form.shipping_city_id"
                                @change="resetShippingSelection(); shippingOptions = []; updateCityName(); form.shipping_district_id = ''; districts = [];"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none cursor-pointer disabled:bg-gray-50 disabled:cursor-not-allowed"
                                :class="errors.shipping_city_id ? 'border-red-500' : ''"
                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"%236B7280\"><path fill-rule=\"evenodd\" d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" clip-rule=\"evenodd\"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;"
                                required
                                :disabled="!form.shipping_province_id"
                            >
                                <option value="" x-text="loadingCities ? 'Memuat kota...' : (!form.shipping_province_id ? 'Pilih provinsi dulu' : 'Pilih Kota')"></option>
                                <template x-for="city in cities" :key="city.city_id">
                                    <option :value="city.city_id" x-text="city.city_name"></option>
                                </template>
                            </select>
                            <div x-show="loadingCities" class="text-sm text-blue-600 mt-1 flex items-center">
                                <svg
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Memuat kota...
                            </div>
                            <div x-show="!form.shipping_province_id && !loadingCities" class="text-sm text-gray-400 mt-1">
                                Pilih provinsi terlebih dahulu
                            </div>
                            <div x-show="!loadingCities && form.shipping_province_id && cities.length > 0" class="text-xs text-gray-500 mt-1">
                                <span x-text="cities.length"></span> kota tersedia (diurutkan A-Z)
                            </div>
                            <div
                                x-show="errors.shipping_city_id"
                                class="text-red-500 text-sm mt-1"
                                x-text="errors.shipping_city_id?.[0]"
                            ></div>
                        </div>

                        <!-- District Selection -->
                        <div
                            x-show="form.shipping_city_id"
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                        >
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                            <select
                                x-model="form.shipping_district_id"
                                @change="resetShippingSelection(); shippingOptions = []; updateDistrictName()"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white appearance-none cursor-pointer disabled:bg-gray-50 disabled:cursor-not-allowed"
                                style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 20 20\" fill=\"%236B7280\"><path fill-rule=\"evenodd\" d=\"M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z\" clip-rule=\"evenodd\"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 16px;"
                                :disabled="!form.shipping_city_id || loadingDistricts"
                            >
                                <option value="" x-text="loadingDistricts ? 'Memuat kecamatan...' : (!form.shipping_city_id ? 'Pilih kota dulu' : 'Pilih Kecamatan')"></option>
                                <template x-for="district in districts" :key="district.district_id">
                                    <option :value="district.district_id" x-text="district.district_name"></option>
                                </template>
                            </select>
                            <div x-show="loadingDistricts" class="text-sm text-blue-600 mt-1 flex items-center">
                                <svg
                                    class="animate-spin -ml-1 mr-2 h-4 w-4 text-blue-600"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                >
                                    <circle
                                        class="opacity-25"
                                        cx="12"
                                        cy="12"
                                        r="10"
                                        stroke="currentColor"
                                        stroke-width="4"
                                    ></circle>
                                    <path
                                        class="opacity-75"
                                        fill="currentColor"
                                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                                    ></path>
                                </svg>
                                Memuat kecamatan...
                            </div>
                            <div x-show="!loadingDistricts && form.shipping_city_id && hasDistricts()" class="text-xs text-gray-500 mt-1">
                                <span x-text="districts.length"></span> kecamatan tersedia (diurutkan A-Z)
                            </div>
                            <div
                                x-show="form.shipping_city_id && !loadingDistricts && !hasDistricts()"
                                class="text-sm text-gray-500 mt-1"
                            >
                                <i class="fas fa-info-circle mr-1"></i>
                                Data kecamatan tidak tersedia, menggunakan tingkat kota
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Options -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-truck mr-2 md:mr-3 text-blue-600"></i>
                        Pilihan Pengiriman
                    </h2>

                    <!-- Loading State -->
                    <div
                        x-show="loadingShipping"
                        class="text-center py-8"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                    >
                        <div class="relative mx-auto mb-4 w-12 h-12">
                            <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-200"></div>
                            <div
                                class="animate-spin rounded-full h-12 w-12 border-4 border-blue-600 border-t-transparent absolute top-0 left-0"
                            ></div>
                            <div class="absolute inset-0 rounded-full bg-blue-100 animate-pulse opacity-25"></div>
                        </div>
                        <p class="text-gray-700 font-medium">Menghitung ongkos kirim...</p>
                        <p class="text-sm text-gray-500 mt-2 flex items-center justify-center">
                            <i class="fas fa-map-marker-alt mr-2 text-blue-500"></i>
                            <span x-show="form.shipping_district_id">Menggunakan tingkat kecamatan</span>
                            <span x-show="!form.shipping_district_id && form.shipping_city_id">
                                Menggunakan tingkat kota
                            </span>
                        </p>
                        <div class="mt-4 flex justify-center space-x-1">
                            <div
                                class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                                style="animation-delay: 0s"
                            ></div>
                            <div
                                class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                                style="animation-delay: 0.1s"
                            ></div>
                            <div
                                class="w-2 h-2 bg-blue-500 rounded-full animate-bounce"
                                style="animation-delay: 0.2s"
                            ></div>
                        </div>
                    </div>

                    <!-- No Shipping Options Message -->
                    <div
                        x-show="!loadingShipping && !shippingOptions.length && (form.shipping_district_id || form.shipping_city_id) && !loadingCities && !loadingDistricts"
                        class="text-center py-8"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        <i class="fas fa-info-circle text-4xl text-gray-300 mb-3"></i>
                        <p class="text-gray-600">Pilih lokasi pengiriman untuk melihat opsi</p>
                    </div>

                    <!-- Shipping Options List -->
                    <div
                        x-show="shippingOptions.length"
                        class="space-y-2 md:space-y-3"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        <template x-for="option in shippingOptions" :key="option.service + '_' + option.courier">
                            <label class="block cursor-pointer">
                                <div
                                    class="border rounded-lg p-3 md:p-4 transition-all duration-300 shipping-option-card"
                                    :class="{
                                        'border-blue-500 bg-gradient-to-r from-blue-50 to-indigo-50 shadow-lg ring-2 ring-blue-200': form.shipping_service === option.service && form.shipping_courier === option.courier,
                                        'border-green-300 bg-gradient-to-r from-green-50 to-emerald-50 hover:border-green-400 hover:shadow-md': option.is_store_courier && !(form.shipping_service === option.service && form.shipping_courier === option.courier),
                                        'border-gray-200 bg-white hover:border-blue-300 hover:bg-gray-50 hover:shadow-md': !option.is_store_courier && !(form.shipping_service === option.service && form.shipping_courier === option.courier)
                                    }"
                                >
                                    <div class="flex items-start md:items-center">
                                        <input
                                            type="radio"
                                            name="shipping_option"
                                            :value="option.service"
                                            @change="updateShippingCost(option)"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2 mt-1 md:mt-0 flex-shrink-0"
                                        />
                                        <div class="ml-3 flex-1">
                                            <div class="flex flex-col md:flex-row md:justify-between md:items-center">
                                                <div class="flex-1 mb-2 md:mb-0">
                                                    <!-- Service Name with Icon for Store Courier -->
                                                    <div class="flex flex-col sm:flex-row sm:items-center mb-1">
                                                        <div
                                                            class="font-medium text-sm md:text-base"
                                                            :class="option.is_store_courier ? 'text-green-800' : 'text-gray-900'"
                                                            x-text="option.service_name || option.courier.toUpperCase() + ' ' + option.service"
                                                        ></div>
                                                        <div
                                                            x-show="option.is_store_courier"
                                                            class="mt-1 sm:mt-0 sm:ml-2"
                                                        >
                                                            <span
                                                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                                            >
                                                                <i class="fas fa-store mr-1"></i>
                                                                <span class="hidden sm:inline">Rekomendasi</span>
                                                                <span class="sm:hidden">Toko</span>
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Description -->
                                                    <div
                                                        class="text-xs md:text-sm mb-2"
                                                        :class="option.is_store_courier ? 'text-green-700' : 'text-gray-600'"
                                                        x-text="option.description || 'Layanan pengiriman'"
                                                    ></div>

                                                    <!-- Estimation and Notes -->
                                                    <div
                                                        class="flex flex-col sm:flex-row sm:items-center text-xs space-y-1 sm:space-y-0 sm:space-x-3"
                                                    >
                                                        <div class="flex items-center">
                                                            <i class="fas fa-clock mr-1 text-gray-400"></i>
                                                            <span class="text-gray-600">Estimasi:</span>
                                                            <span
                                                                class="font-medium ml-1"
                                                                :class="option.is_store_courier ? 'text-green-700' : 'text-gray-700'"
                                                                x-text="option.etd"
                                                            ></span>
                                                        </div>
                                                        <div x-show="option.note" class="flex items-center">
                                                            <i class="fas fa-info-circle mr-1 text-blue-400"></i>
                                                            <span
                                                                class="text-xs"
                                                                :class="option.is_store_courier ? 'text-green-600' : 'text-blue-600'"
                                                                x-text="option.note"
                                                            ></span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Price -->
                                                <div class="text-right md:ml-4">
                                                    <div
                                                        class="font-bold text-base md:text-lg"
                                                        :class="option.is_store_courier ? 'text-green-700' : 'text-gray-900'"
                                                        x-text="formatPrice(option.cost)"
                                                    ></div>
                                                    <div
                                                        x-show="option.is_store_courier"
                                                        class="text-xs text-green-600"
                                                    >
                                                        Hemat!
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                    <!-- Info about service availability -->
                    <div
                        x-show="shippingOptions.length"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-y-2"
                        x-transition:enter-end="opacity-100 transform translate-y-0"
                        class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg"
                    >
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-600 mt-0.5 mr-2 flex-shrink-0"></i>
                            <div class="text-xs text-blue-800">
                                <p class="font-medium mb-1">Tentang Ketersediaan Layanan:</p>
                                <ul class="list-disc list-inside space-y-0.5 text-blue-700">
                                    <li>Layanan yang ditampilkan adalah yang <strong>tersedia untuk daerah Anda</strong></li>
                                    <li>Layanan premium seperti JNE YES/SPS hanya tersedia di kota-kota tertentu</li>
                                    <li>Jika tidak melihat layanan express, gunakan layanan reguler yang pasti tersedia</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
                    <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-2 md:mr-3 text-blue-600"></i>
                        Catatan Tambahan
                    </h2>
                    <textarea
                        x-model="form.notes"
                        rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        placeholder="Berikan catatan khusus untuk pesanan Anda (opsional)"
                    ></textarea>
                    <p class="text-xs text-gray-500 mt-2">
                        Contoh: Warna yang diinginkan, ukuran khusus, atau instruksi pengiriman
                    </p>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-8">
                    <div class="p-4 md:p-6">
                        <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-receipt mr-2 md:mr-3 text-blue-600"></i>
                            Ringkasan Pesanan
                        </h2>

                        <!-- Order Items -->
                        <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                            <template x-for="item in items" :key="item.id || item.product_id">
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        <img
                                            :src="item.product.image_url || '/images/default-product.svg'"
                                            :alt="item.product.name"
                                            class="w-full h-full object-cover"
                                            loading="lazy"
                                            onerror="this.src='/images/default-product.svg'"
                                        />
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-medium text-gray-900 truncate" x-text="item.product.name"></p>
                                        <p class="text-sm text-gray-600">
                                            Qty:
                                            <span x-text="item.quantity"></span>
                                        </p>
                                        <div
                                            x-show="item.custom_size_width && item.custom_size_height"
                                            class="text-xs text-gray-500"
                                        >
                                            <span
                                                x-text="`${item.custom_size_width} x ${item.custom_size_height} cm`"
                                            ></span>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p
                                            class="font-semibold text-gray-900"
                                            x-text="formatPrice(item.subtotal)"
                                        ></p>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Price Breakdown -->
                        <div class="border-t pt-4 space-y-3">
                            <div class="flex justify-between text-gray-600">
                                <span>
                                    Subtotal (
                                    <span x-text="getTotalItems()"></span>
                                    item)
                                </span>
                                <span x-text="formatPrice(subtotal)"></span>
                            </div>

                            <div class="flex justify-between text-gray-600">
                                <span>Estimasi Berat</span>
                                <span x-text="estimatedWeight + ' kg'"></span>
                            </div>

                            <div class="flex justify-between text-gray-600" x-show="form.shipping_cost > 0">
                                <span>Ongkos Kirim</span>
                                <span x-text="formatPrice(form.shipping_cost)"></span>
                            </div>

                            <div class="flex justify-between text-gray-600">
                                <span>PPN (11%)</span>
                                <span x-text="formatPrice(getTaxAmount())"></span>
                            </div>

                            <div class="border-t pt-3">
                                <div class="flex justify-between font-bold text-xl text-gray-900">
                                    <span>Total</span>
                                    <span x-text="formatPrice(getTotalAmount())"></span>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method Notice -->
                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <div class="flex items-start">
                                <i class="fas fa-info-circle text-blue-600 mt-1 mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-blue-900 mb-1">Metode Pembayaran</h4>
                                    <p class="text-sm text-blue-700">
                                        Transfer bank manual. Detail rekening akan diberikan setelah checkout.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Button -->
                        <button
                            @click="processCheckout($event)"
                            type="submit"
                            :disabled="!canCheckout() || processing"
                            class="w-full bg-tria-navy text-white py-4 px-4 rounded-lg font-bold text-lg hover:bg-tria-navy-light transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-6"
                        >
                            <span x-show="!processing">
                                <i class="fas fa-lock mr-2"></i>
                                Buat Pesanan
                            </span>
                            <span x-show="processing" class="flex items-center justify-center">
                                <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-white mr-3"></div>
                                Memproses...
                            </span>
                        </button>

                        <!-- Terms & Conditions -->
                        <div class="mt-4 text-xs text-gray-500 text-center">
                            Dengan melanjutkan, Anda menyetujui
                            <a href="#" class="text-blue-600 hover:underline">Syarat & Ketentuan</a>
                            dan
                            <a href="#" class="text-blue-600 hover:underline">Kebijakan Privasi</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <!-- Loading Overlay -->
        <div x-show="processing" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-8 flex items-center space-x-4">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                <div>
                    <h3 class="font-semibold text-gray-900">Memproses Pesanan</h3>
                    <p class="text-sm text-gray-600">Mohon tunggu sebentar...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function checkoutManager() {
            return {
                items: @json($items ?? []),
                subtotal: @json($subtotal ?? 0),
                estimatedWeight: @json($estimatedWeight ?? 1),
                userData: @json($userData ?? null),
                isBuyNow: @json($isBuyNow ?? false),

                form: {
                    customer_name: '',
                    customer_email: '',
                    customer_phone: '',
                    customer_address: '',
                    shipping_province_id: '',
                    shipping_city_id: '',
                    shipping_district_id: '', // ← NEW: District support
                    shipping_province_name: '',
                    shipping_city_name: '',
                    shipping_district_name: '', // ← NEW: District name
                    shipping_address: '',
                    shipping_courier: '',
                    shipping_service: '',
                    shipping_etd: '',
                    shipping_cost: 0,
                    notes: '',
                },

                provinces: [],
                cities: [],
                districts: [], // ← NEW: Districts array
                loadingProvinces: false,
                loadingCities: false,
                loadingDistricts: false, // ← NEW: Districts loading state
                loadingShipping: false, // ← NEW: Shipping calculation loading state
                shippingOptions: [],
                processing: false,
                errors: {},
                isSingaparnaAddress: false, // ← NEW: Track if address contains Singaparna

                // ← NEW: Feature flags
                useDistricts: true, // Enable district-level shipping
                allowCityFallback: true, // Allow fallback to city-level if districts fail

                init() {
                    // Setup CSRF token
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');

                    // Pre-fill user data if logged in
                    if (this.userData) {
                        this.form.customer_name = this.userData.name || '';
                        this.form.customer_email = this.userData.email || '';
                        this.form.customer_phone = this.userData.phone || '';
                        this.form.customer_address = this.userData.address || '';
                    }

                    this.loadProvinces();
                },

                async loadProvinces() {
                    console.log('Loading provinces...');
                    this.loadingProvinces = true;
                    try {
                        const response = await axios.get('/shipping/provinces');
                        console.log('Provinces response:', response);

                        if (response.data.success) {
                            // Sort provinces alphabetically by province name
                            this.provinces = response.data.data.sort((a, b) => 
                                a.province.localeCompare(b.province, 'id', { numeric: true })
                            );
                            console.log('Provinces loaded and sorted:', this.provinces.length);
                        } else {
                            throw new Error(response.data.message || 'Failed to load provinces');
                        }
                    } catch (error) {
                        console.error('Failed to load provinces:', error);
                        this.showToast(
                            'Gagal memuat data provinsi: ' + (error.response?.data?.message || error.message),
                            'error',
                        );

                        // Fallback provinces (already sorted alphabetically)
                        this.provinces = [
                            { province_id: '6', province: 'DKI Jakarta' },
                            { province_id: '11', province: 'Jawa Timur' },
                            { province_id: '9', province: 'Jawa Barat' },
                            { province_id: '10', province: 'Jawa Tengah' },
                        ].sort((a, b) => a.province.localeCompare(b.province, 'id', { numeric: true }));
                    }
                    this.loadingProvinces = false;
                },

                async loadCities(provinceId) {
                    if (!provinceId) {
                        this.cities = [];
                        this.districts = [];
                        return;
                    }

                    console.log('Loading cities for province:', provinceId);
                    this.loadingCities = true;
                    this.cities = [];
                    this.districts = [];
                    this.form.shipping_city_id = '';
                    this.form.shipping_district_id = '';

                    try {
                        const response = await axios.get(`/shipping/cities/${provinceId}`);
                        console.log('Cities response:', response);

                        if (response.data.success) {
                            // Sort cities alphabetically by city name
                            this.cities = response.data.data.sort((a, b) => 
                                a.city_name.localeCompare(b.city_name, 'id', { numeric: true })
                            );
                            console.log('Cities loaded and sorted:', this.cities.length);
                        } else {
                            throw new Error(response.data.message || 'Failed to load cities');
                        }
                    } catch (error) {
                        console.error('Failed to load cities:', error);
                        this.showToast(
                            'Gagal memuat data kota: ' + (error.response?.data?.message || error.message),
                            'error',
                        );

                        // Fallback cities for major provinces
                        const fallbackCities = {
                            6: [
                                // DKI Jakarta
                                { city_id: '151', city_name: 'Jakarta Pusat' },
                                { city_id: '152', city_name: 'Jakarta Selatan' },
                                { city_id: '154', city_name: 'Jakarta Utara' },
                            ],
                            9: [
                                // Jawa Barat
                                { city_id: '39', city_name: 'Bandung' },
                                { city_id: '78', city_name: 'Bogor' },
                                { city_id: '151', city_name: 'Depok' },
                            ],
                        };
                        // Sort fallback cities alphabetically
                        this.cities = (fallbackCities[provinceId] || []).sort((a, b) => 
                            a.city_name.localeCompare(b.city_name, 'id', { numeric: true })
                        );
                    }
                    this.loadingCities = false;
                },

                // ← NEW: Load districts method
                async loadDistricts(cityId) {
                    if (!cityId || !this.useDistricts) {
                        this.districts = [];
                        return;
                    }

                    console.log('Loading districts for city:', cityId);
                    this.loadingDistricts = true;
                    this.districts = [];
                    this.form.shipping_district_id = '';

                    try {
                        const response = await axios.get(`/shipping/districts/${cityId}`);
                        console.log('Districts response:', response);

                        if (response.data.success) {
                            // Sort districts alphabetically by district name
                            this.districts = response.data.data.sort((a, b) => 
                                a.district_name.localeCompare(b.district_name, 'id', { numeric: true })
                            );
                            console.log('Districts loaded and sorted:', this.districts.length);

                            // Auto-select first district if only one available (after sorting)
                            if (this.districts.length === 1) {
                                this.form.shipping_district_id = this.districts[0].district_id;
                                this.updateDistrictName();
                            }
                        } else {
                            throw new Error(response.data.message || 'Failed to load districts');
                        }
                    } catch (error) {
                        console.error('Failed to load districts:', error);
                        console.log('District loading failed, will fallback to city-level shipping');

                        // Don't show error toast for districts, just log and continue with city-level
                        this.districts = [];

                        if (this.allowCityFallback) {
                            // Trigger shipping calculation with city ID
                            this.calculateShipping();
                        }
                    }
                    this.loadingDistricts = false;
                },

                // ← NEW: Check if address contains Singaparna
                checkSingaparnaAddress() {
                    const address = this.form.customer_address.toLowerCase();
                    const singaparnaKeywords = ['singaparna', 'sing parna', 'kec singaparna', 'kecamatan singaparna'];

                    this.isSingaparnaAddress = singaparnaKeywords.some((keyword) =>
                        address.includes(keyword.toLowerCase()),
                    );

                    console.log('Singaparna address check:', {
                        address: this.form.customer_address,
                        isSingaparna: this.isSingaparnaAddress,
                    });

                    // Trigger shipping recalculation if location data is available
                    if (this.form.shipping_city_id) {
                        this.calculateShipping();
                    }
                },

                // ← NEW: Add store courier option for Singaparna addresses
                addStoreCourierOption() {
                    const storeCourierOption = {
                        courier: 'store_courier',
                        service: 'ANTAR_TOKO',
                        service_name: 'Kurir Toko',
                        description: 'Pengantaran langsung dari toko (Khusus Singaparna)',
                        cost: 20000, // Cheaper than regular shipping
                        etd: 'Hari ini - 1 hari',
                        note: 'Khusus wilayah Singaparna',
                        is_store_courier: true,
                    };

                    // Add at the beginning of the array (prioritize store courier)
                    this.shippingOptions.unshift(storeCourierOption);

                    console.log('Added store courier option for Singaparna');
                },

                // ← UPDATED: Enhanced shipping calculation with district support
                async calculateShipping() {
                    // Require either district or city to be selected
                    if (!this.form.shipping_district_id && !this.form.shipping_city_id) {
                        this.shippingOptions = [];
                        return;
                    }

                    const destinationId = this.form.shipping_district_id || this.form.shipping_city_id;
                    const useDistrict = !!this.form.shipping_district_id;

                    // Set loading state
                    this.loadingShipping = true;
                    this.shippingOptions = [];

                    try {
                        console.log('Calculating shipping:', {
                            destination_district_id: this.form.shipping_district_id,
                            destination_city_id: this.form.shipping_city_id,
                            weight: this.estimatedWeight,
                            use_district: useDistrict,
                        });

                        const requestData = {
                            weight: this.estimatedWeight,
                            courier: 'all',
                        };

                        // Send both district and city IDs, backend will decide which to use
                        if (this.form.shipping_district_id) {
                            requestData.destination_district_id = parseInt(this.form.shipping_district_id);
                        }
                        if (this.form.shipping_city_id) {
                            requestData.destination_city_id = parseInt(this.form.shipping_city_id);
                        }

                        const response = await axios.post('/checkout/calculate-shipping', requestData);
                        console.log('Shipping response:', response);

                        if (response.data.success) {
                            this.shippingOptions = response.data.shipping_options || [];

                            // Add store courier option for Singaparna addresses
                            if (this.isSingaparnaAddress) {
                                this.addStoreCourierOption();
                            }

                            console.log('Shipping options loaded:', this.shippingOptions.length);

                            // Reset shipping selection when options change
                            this.resetShippingSelection();

                            // Show calculation info for debugging
                            if (response.data.calculation_info) {
                                console.log('Shipping calculation info:', response.data.calculation_info);
                            }
                        } else {
                            throw new Error(response.data.message || 'Failed to calculate shipping');
                        }
                    } catch (error) {
                        console.error('Shipping calculation error:', error);
                        this.showToast(
                            'Gagal menghitung ongkos kirim: ' + (error.response?.data?.message || error.message),
                            'error',
                        );

                        // Enhanced fallback shipping options
                        this.shippingOptions = [
                            {
                                courier: 'jne',
                                service: 'REG',
                                service_name: 'JNE REG',
                                description: 'Layanan Reguler',
                                cost: 15000,
                                etd: '2-3 hari',
                                note: 'Estimasi (fallback)',
                            },
                            {
                                courier: 'pos',
                                service: 'Paket Kilat Khusus',
                                service_name: 'POS Reguler',
                                description: 'Pos Indonesia Reguler',
                                cost: 12000,
                                etd: '3-4 hari',
                                note: 'Estimasi (fallback)',
                            },
                        ];

                        // Add store courier for Singaparna even in fallback
                        if (this.isSingaparnaAddress) {
                            this.addStoreCourierOption();
                        }
                    } finally {
                        // Always turn off loading state
                        this.loadingShipping = false;
                    }
                },

                // ← NEW: Reset shipping selection
                resetShippingSelection() {
                    this.form.shipping_service = '';
                    this.form.shipping_cost = 0;
                    this.form.shipping_courier = '';
                    this.form.shipping_etd = '';
                },

                updateShippingCost(option) {
                    this.form.shipping_cost = option.cost;
                    this.form.shipping_courier = option.courier;
                    this.form.shipping_etd = option.etd;
                    this.form.shipping_service = option.service;
                    console.log('Selected shipping:', option);
                },

                updateCityName() {
                    if (this.form.shipping_city_id) {
                        const selectedCity = this.cities.find((city) => city.city_id == this.form.shipping_city_id);
                        const selectedProvince = this.provinces.find(
                            (province) => province.province_id == this.form.shipping_province_id,
                        );

                        if (selectedCity) this.form.shipping_city_name = selectedCity.city_name;
                        if (selectedProvince) this.form.shipping_province_name = selectedProvince.province;

                        // Set shipping address same as customer address if empty
                        if (!this.form.shipping_address && this.form.customer_address) {
                            this.form.shipping_address = this.form.customer_address;
                        }

                        // Load districts for the selected city
                        this.loadDistricts(this.form.shipping_city_id);
                    }
                },

                // ← NEW: Update district name
                updateDistrictName() {
                    if (this.form.shipping_district_id) {
                        const selectedDistrict = this.districts.find(
                            (district) => district.district_id == this.form.shipping_district_id,
                        );
                        if (selectedDistrict) {
                            this.form.shipping_district_name = selectedDistrict.district_name;
                        }

                        // Trigger shipping calculation when district is selected
                        this.calculateShipping();
                    }
                },

                async processCheckout() {
                    if (!this.canCheckout()) {
                        this.showToast('Mohon lengkapi semua data yang diperlukan', 'warning');
                        return;
                    }

                    this.processing = true;
                    this.errors = {};

                    try {

                        const response = await axios.post('/checkout/process', this.form);

                        if (response.data.success) {
                            this.showToast('Pesanan berhasil dibuat!', 'success');

                            // Redirect to order page
                            setTimeout(() => {
                                window.location.href = response.data.redirect_url;
                            }, 1500);
                        } else {
                            throw new Error(response.data.message || 'Checkout failed');
                        }
                    } catch (error) {
                        this.processing = false;

                            if (error.response?.status === 422) {
                                // Populate field errors for the UI
                                this.errors = error.response.data.errors || {};

                                // Show the first available validation message in toast if present
                                const firstError = this.errors && Object.values(this.errors)[0] ? Object.values(this.errors)[0][0] : null;
                                this.showToast(firstError || error.response.data.message || 'Mohon periksa data yang diisi', 'error');
                            } else {
                                this.showToast(
                                    'Terjadi kesalahan: ' + (error.response?.data?.message || error.message),
                                    'error',
                                );
                            }
                    }
                },

                canCheckout() {
                    const required = [
                        this.form.customer_name,
                        this.form.customer_email,
                        this.form.customer_address,
                        this.form.shipping_city_id,
                        this.form.shipping_service,
                        this.items.length > 0,
                    ];

                    console.log('Checkout validation:', {
                        customer_name: !!this.form.customer_name,
                        customer_email: !!this.form.customer_email,
                        customer_address: !!this.form.customer_address,
                        shipping_city_id: !!this.form.shipping_city_id,
                        shipping_district_id: !!this.form.shipping_district_id,
                        shipping_service: !!this.form.shipping_service,
                        items_count: this.items.length,
                        can_checkout: required.every((field) => field),
                    });

                    return required.every((field) => field);
                },

                getTotalItems() {
                    return this.items.reduce((total, item) => total + parseInt(item.quantity), 0);
                },

                getTaxAmount() {
                    return this.subtotal * 0.11; // 11% PPN
                },

                getTotalAmount() {
                    return this.subtotal + this.form.shipping_cost + this.getTaxAmount();
                },

                formatPrice(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(amount || 0);
                },

                // ← NEW: Get shipping destination display
                getShippingDestination() {
                    const parts = [];
                    if (this.form.shipping_district_name) parts.push(this.form.shipping_district_name);
                    if (this.form.shipping_city_name) parts.push(this.form.shipping_city_name);
                    if (this.form.shipping_province_name) parts.push(this.form.shipping_province_name);
                    return parts.join(', ');
                },

                // ← NEW: Check if districts are available
                hasDistricts() {
                    return this.districts && this.districts.length > 0;
                },

                showToast(message, type = 'success') {
                    // Use SweetAlert2 if available, otherwise console.log
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            icon: type,
                            title: message,
                        });
                    } else {
                        console.log(`${type.toUpperCase()}: ${message}`);
                        alert(`${type.toUpperCase()}: ${message}`);
                    }
                },
            };
        }
    </script>
@endsection
