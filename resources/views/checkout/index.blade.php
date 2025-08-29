@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="checkoutManager()" x-init="init()">
        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex items-center justify-center space-x-4 mb-6">
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold"
                    >
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Keranjang</span>
                </div>
                <div class="w-20 h-1 bg-blue-600 rounded"></div>
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center font-semibold"
                    >
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Checkout</span>
                </div>
                <div class="w-20 h-1 bg-gray-300 rounded"></div>
                <div class="flex items-center">
                    <div
                        class="w-10 h-10 bg-gray-300 text-gray-500 rounded-full flex items-center justify-center font-semibold"
                    >
                        <i class="fas fa-check"></i>
                    </div>
                    <span class="ml-2 text-sm font-medium text-gray-500">Selesai</span>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-center text-gray-900">Checkout</h1>
        </div>

        <form @submit.prevent="processCheckout()" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-user mr-3 text-blue-600"></i>
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
                            @input="if (!form.shipping_address) form.shipping_address = form.customer_address"
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
                        <p class="text-xs text-gray-500 mt-1">
                            Alamat ini akan digunakan sebagai alamat pengiriman juga
                        </p>
                    </div>
                </div>

                <!-- Shipping Information -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-map-marker-alt mr-3 text-blue-600"></i>
                        Alamat Pengiriman
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Province Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi *</label>
                            <select
                                x-model="form.shipping_province_id"
                                @change="loadCities($event.target.value); form.shipping_city_id = ''; form.shipping_district_id = ''; cities = []; districts = [];"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="errors.shipping_province_id ? 'border-red-500' : ''"
                                required
                            >
                                <option value="">Pilih Provinsi</option>
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
                                @change="updateCityName(); form.shipping_district_id = ''; districts = [];"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :class="errors.shipping_city_id ? 'border-red-500' : ''"
                                required
                                :disabled="!form.shipping_province_id"
                            >
                                <option value="">Pilih Kota</option>
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
                            <div x-show="!form.shipping_province_id" class="text-sm text-gray-400 mt-1">
                                Pilih provinsi terlebih dahulu
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
                                @change="updateDistrictName()"
                                class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                :disabled="!form.shipping_city_id || loadingDistricts"
                            >
                                <option value="">Pilih Kecamatan</option>
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
                            <div
                                x-show="form.shipping_city_id && ! loadingDistricts && ! hasDistricts()"
                                class="text-sm text-gray-500 mt-1"
                            >
                                <i class="fas fa-info-circle mr-1"></i>
                                Data kecamatan tidak tersedia, menggunakan tingkat kota
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Address -->
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Pengiriman Spesifik</label>
                        <textarea
                            x-model="form.shipping_address"
                            rows="3"
                            class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            :class="errors.shipping_address ? 'border-red-500' : ''"
                            placeholder="Jalan, No. Rumah, RT/RW, Kelurahan (jika berbeda dari alamat utama)"
                        ></textarea>
                        <div
                            x-show="errors.shipping_address"
                            class="text-red-500 text-sm mt-1"
                            x-text="errors.shipping_address?.[0]"
                        ></div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span x-show="getShippingDestination()" class="text-blue-600 font-medium">
                                Tujuan:
                                <span x-text="getShippingDestination()"></span>
                            </span>
                        </p>
                    </div>
                </div>

                <!-- Shipping Options -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-truck mr-3 text-blue-600"></i>
                        Pilihan Pengiriman
                    </h2>

                    <!-- Loading State -->
                    <div
                        x-show="
                            (loadingDistricts && form.shipping_district_id) ||
                                (! shippingOptions.length &&
                                    (form.shipping_district_id || form.shipping_city_id) &&
                                    ! loadingCities)
                        "
                        class="text-center py-8"
                    >
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mx-auto mb-4"></div>
                        <p class="text-gray-600">Menghitung ongkos kirim...</p>
                        <p class="text-sm text-gray-500 mt-2">
                            <span x-show="form.shipping_district_id">Menggunakan tingkat kecamatan</span>
                            <span x-show="!form.shipping_district_id && form.shipping_city_id">
                                Menggunakan tingkat kota
                            </span>
                        </p>
                    </div>

                    <!-- Shipping Options List -->
                    <div
                        x-show="shippingOptions.length"
                        class="space-y-3"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                    >
                        <template x-for="option in shippingOptions" :key="option.service + '_' + option.courier">
                            <label class="block cursor-pointer">
                                <div
                                    class="border border-gray-200 rounded-lg p-4 hover:border-blue-500 transition-colors"
                                    :class="form.shipping_service === option.service && form.shipping_courier === option.courier ? 'border-blue-500 bg-blue-50' : ''"
                                >
                                    <div class="flex items-center">
                                        <input
                                            type="radio"
                                            name="shipping_option"
                                            :value="option.service"
                                            @change="updateShippingCost(option)"
                                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 focus:ring-2"
                                        />
                                        <div class="ml-3 flex-1 flex justify-between items-center">
                                            <div>
                                                <div
                                                    class="font-medium text-gray-900"
                                                    x-text="option.service_name || option.courier.toUpperCase() + ' ' + option.service"
                                                ></div>
                                                <div
                                                    class="text-sm text-gray-600"
                                                    x-text="option.description || 'Layanan pengiriman'"
                                                ></div>
                                                <div class="text-xs text-gray-500">
                                                    Estimasi:
                                                    <span x-text="option.etd"></span>
                                                    <span
                                                        x-show="option.note"
                                                        class="ml-2 text-orange-600"
                                                        x-text="'(' + option.note + ')'"
                                                    ></span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <div
                                                    class="font-semibold text-gray-900"
                                                    x-text="formatPrice(option.cost)"
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                    <!-- No Options State -->
                    <div
                        x-show="
                            ! loadingCities &&
                                ! loadingDistricts &&
                                ! shippingOptions.length &&
                                (form.shipping_city_id || form.shipping_district_id)
                        "
                        class="text-center py-8"
                    >
                        <i class="fas fa-exclamation-triangle text-yellow-500 text-3xl mb-4"></i>
                        <p class="text-gray-600">Tidak dapat menghitung ongkos kirim</p>
                        <p class="text-sm text-gray-500 mt-2">Silakan pilih lokasi pengiriman yang berbeda</p>
                    </div>
                </div>

                <!-- Additional Notes -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                        <i class="fas fa-sticky-note mr-3 text-blue-600"></i>
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
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-receipt mr-3 text-blue-600"></i>
                            Ringkasan Pesanan
                        </h2>

                        <!-- Order Items -->
                        <div class="space-y-4 mb-6 max-h-60 overflow-y-auto">
                            <template x-for="item in items" :key="item.id || item.product_id">
                                <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                                    <div class="w-12 h-12 bg-gray-200 rounded-lg overflow-hidden flex-shrink-0">
                                        <img
                                            :src="item.product.image_url || '/images/default-product.png'"
                                            :alt="item.product.name"
                                            class="w-full h-full object-cover"
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
                            class="w-full bg-blue-600 text-white py-4 px-4 rounded-lg font-bold text-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed mt-6"
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
                subtotal: {{ $subtotal ?? 0 }},
                estimatedWeight: {{ $estimatedWeight ?? 1 }},
                userData: @json($userData ?? null),
                isBuyNow: {{ $isBuyNow ? 'true' : 'false' }},

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
                shippingOptions: [],
                processing: false,
                errors: {},

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
                            this.provinces = response.data.data;
                            console.log('Provinces loaded:', this.provinces.length);
                        } else {
                            throw new Error(response.data.message || 'Failed to load provinces');
                        }
                    } catch (error) {
                        console.error('Failed to load provinces:', error);
                        this.showToast(
                            'Gagal memuat data provinsi: ' + (error.response?.data?.message || error.message),
                            'error',
                        );

                        // Fallback provinces
                        this.provinces = [
                            { province_id: '6', province: 'DKI Jakarta' },
                            { province_id: '9', province: 'Jawa Barat' },
                            { province_id: '10', province: 'Jawa Tengah' },
                            { province_id: '11', province: 'Jawa Timur' },
                        ];
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
                            this.cities = response.data.data;
                            console.log('Cities loaded:', this.cities.length);
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
                        this.cities = fallbackCities[provinceId] || [];
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
                            this.districts = response.data.data;
                            console.log('Districts loaded:', this.districts.length);

                            // Auto-select first district if only one available
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

                // ← UPDATED: Enhanced shipping calculation with district support
                async calculateShipping() {
                    // Require either district or city to be selected
                    if (!this.form.shipping_district_id && !this.form.shipping_city_id) {
                        this.shippingOptions = [];
                        return;
                    }

                    const destinationId = this.form.shipping_district_id || this.form.shipping_city_id;
                    const useDistrict = !!this.form.shipping_district_id;

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
                        console.log('Processing checkout with data:', this.form);

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
                            this.errors = error.response.data.errors || {};
                            this.showToast('Mohon periksa data yang diisi', 'error');
                            console.log('Validation errors:', this.errors);
                        } else {
                            this.showToast(
                                'Terjadi kesalahan: ' + (error.response?.data?.message || error.message),
                                'error',
                            );
                        }
                        console.error('Checkout error:', error);
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
