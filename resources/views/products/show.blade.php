@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('description', $product->meta_description ?? $product->short_description)

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-red-600">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="#" class="text-gray-500 hover:text-red-600">{{ $product->category->name ?? 'Produk' }}</a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <!-- Product Detail Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16" x-data="productDetail()">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative overflow-hidden rounded-lg bg-gray-100">
                    <img
                        :src="currentImage"
                        alt="{{ $product->name }}"
                        class="w-full h-96 lg:h-[500px] object-cover transition-transform hover:scale-105"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100"
                    />

                    <!-- Zoom functionality -->
                    <button
                        @click="openLightbox()"
                        class="absolute top-4 right-4 bg-white/80 p-2 rounded-full hover:bg-white transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                            ></path>
                        </svg>
                    </button>
                </div>

                <!-- Thumbnail Gallery -->
                @if ($product->gallery_images && count($product->gallery_images) > 0)
                    <div class="flex space-x-3 overflow-x-auto">
                        <!-- Main Image Thumbnail -->
                        <button
                            @click="changeImage('{{ $product->image_url }}')"
                            :class="currentImage === '{{ $product->image_url }}' ? 'ring-2 ring-red-600' : ''"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100"
                        >
                            <img src="{{ $product->image_url }}" alt="Main" class="w-full h-full object-cover" />
                        </button>

                        <!-- Gallery Images -->
                        @foreach ($product->gallery_images as $image)
                            <button
                                @click="changeImage('{{ asset('storage/' . $image) }}')"
                                :class="currentImage === '{{ asset('storage/' . $image) }}' ? 'ring-2 ring-red-600' : ''"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100"
                            >
                                <img
                                    src="{{ asset('storage/' . $image) }}"
                                    alt="Gallery"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <!-- Category -->
                <span class="inline-block px-3 py-1 text-sm bg-red-100 text-red-600 rounded-full">
                    {{ $product->category->name ?? 'Produk' }}
                </span>

                <!-- Product Name -->
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->name }}</h1>

                <!-- Short Description -->
                @if ($product->short_description)
                    <p class="text-lg text-gray-600">{{ $product->short_description }}</p>
                @endif

                <!-- Price -->
                <div class="space-y-2">
                    @if ($product->sale_price)
                        <div class="flex items-center space-x-3">
                            <span
                                class="text-3xl font-bold text-red-600"
                                x-text="'Rp ' + calculatePrice().toLocaleString('id-ID')"
                            ></span>
                            <span
                                class="text-xl text-gray-500 line-through"
                                x-text="'Rp ' + calculateOriginalPrice().toLocaleString('id-ID')"
                            ></span>
                            <span class="bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold">
                                {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                            </span>
                        </div>
                    @else
                        <span
                            class="text-3xl font-bold text-gray-900"
                            x-text="'Rp ' + calculatePrice().toLocaleString('id-ID')"
                        ></span>
                    @endif

                    <!-- Price Breakdown -->
                    <div class="text-sm text-gray-600 space-y-1" x-show="quantity > 1 || totalMeters > 0">
                        <div class="flex justify-between">
                            <span>Harga satuan:</span>
                            <span x-text="'Rp ' + basePrice.toLocaleString('id-ID')"></span>
                        </div>
                        <div x-show="totalMeters > 0" class="flex justify-between">
                            <span>Harga per m²:</span>
                            <span x-text="'Rp ' + getPricePerMeter().toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between">
                            <span>Jumlah:</span>
                            <span x-text="quantity + ' pcs'"></span>
                        </div>
                        <div class="border-t pt-1 flex justify-between font-semibold">
                            <span>Total estimasi:</span>
                            <span
                                class="text-red-600"
                                x-text="'Rp ' + calculatePrice().toLocaleString('id-ID')"
                            ></span>
                        </div>
                    </div>
                </div>

                <!-- Stock Status -->
                <div class="flex items-center space-x-2">
                    @if ($product->stock_quantity > 0)
                        <div class="flex items-center text-green-600">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <span class="font-medium">Stok tersedia ({{ $product->stock_quantity }} pcs)</span>
                        </div>
                    @else
                        <div class="flex items-center text-red-600">
                            <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <span class="font-medium">Stok habis</span>
                        </div>
                    @endif
                </div>

                <!-- Size Variants dengan Custom Input -->
                @if ($product->size_variants && count($product->size_variants) > 0)
                    <div class="space-y-4">
                        <h3 class="font-semibold text-gray-900">Pilih Ukuran & Hitung Harga:</h3>

                        <!-- Custom Size Input -->
                        <div class="bg-gray-50 rounded-lg p-4 space-y-4">
                            <h4 class="font-medium text-gray-900">Input Ukuran Custom:</h4>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Panjang (meter)</label>
                                    <input
                                        type="number"
                                        x-model="customLength"
                                        @input="calculateCustomSize()"
                                        step="0.1"
                                        min="0.1"
                                        placeholder="Contoh: 2.5"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Lebar (meter)</label>
                                    <input
                                        type="number"
                                        x-model="customWidth"
                                        @input="calculateCustomSize()"
                                        step="0.1"
                                        min="0.1"
                                        placeholder="Contoh: 1.2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    />
                                </div>
                            </div>

                            <!-- Hasil Kalkulasi -->
                            <div x-show="totalMeters > 0" class="bg-white rounded border p-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-700">
                                        <span x-text="customLength"></span>
                                        m ×
                                        <span x-text="customWidth"></span>
                                        m =
                                        <span class="font-semibold" x-text="totalMeters.toFixed(2)"></span>
                                        m²
                                    </span>
                                    <span
                                        class="text-lg font-bold text-red-600"
                                        x-text="'Rp ' + getPricePerMeter().toLocaleString('id-ID') + '/m²'"
                                    ></span>
                                </div>
                            </div>
                        </div>

                        <!-- Price Tiers -->
                        <div class="space-y-3">
                            <h4 class="font-medium text-gray-900">Harga Berdasarkan Total Area:</h4>
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-3">
                                @foreach ($product->size_variants as $index => $tier)
                                    @if ($tier['is_available'] ?? true)
                                        <div
                                            :class="isInPriceTier('{{ $tier['size'] }}') ? 'border-green-600 bg-green-50' : 'border-gray-300'"
                                            class="border-2 rounded-lg p-4"
                                        >
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <h5 class="font-semibold text-gray-900">{{ $tier['size'] }}²</h5>
                                                    <div class="mt-1">
                                                        <span class="text-lg font-bold text-red-600">
                                                            Rp {{ number_format($tier['price'] ?? 0, 0, ',', '.') }}
                                                        </span>
                                                        <span class="text-sm text-gray-500">/m²</span>
                                                    </div>
                                                </div>
                                                <div
                                                    x-show="isInPriceTier('{{ $tier['size'] }}')"
                                                    class="text-green-600"
                                                >
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                            clip-rule="evenodd"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <!-- Selected calculation info -->
                        <div x-show="totalMeters > 0" class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                            <div class="space-y-2">
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-700 font-medium">Ukuran yang dipesan:</span>
                                    <span class="text-blue-900 font-semibold">
                                        <span x-text="customLength"></span>
                                        m ×
                                        <span x-text="customWidth"></span>
                                        m
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-700 font-medium">Total area:</span>
                                    <span
                                        class="text-blue-900 font-semibold"
                                        x-text="totalMeters.toFixed(2) + ' m²'"
                                    ></span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-blue-700 font-medium">Harga per m²:</span>
                                    <span
                                        class="text-blue-900 font-semibold"
                                        x-text="'Rp ' + getPricePerMeter().toLocaleString('id-ID')"
                                    ></span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Quantity untuk Custom Size -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900">Jumlah Pesanan:</h3>

                    <div class="flex items-center space-x-4">
                        <div class="flex items-center space-x-3 bg-gray-100 rounded-lg p-1">
                            <button
                                @click="decreaseQuantity()"
                                :disabled="quantity <= 1"
                                class="w-10 h-10 rounded-lg bg-white text-gray-700 hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                -
                            </button>
                            <input
                                type="number"
                                x-model="quantity"
                                @input="validateQuantity()"
                                min="1"
                                max="100"
                                class="w-20 text-center font-semibold text-lg bg-transparent border-none outline-none"
                            />
                            <button
                                @click="increaseQuantity()"
                                :disabled="quantity >= 100"
                                class="w-10 h-10 rounded-lg bg-white text-gray-700 hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                +
                            </button>
                        </div>

                        <!-- Quick quantity buttons -->
                        <div class="flex space-x-2">
                            <template x-for="qty in [1, 2, 5, 10]" :key="qty">
                                <button
                                    @click="quantity = qty"
                                    :class="quantity === qty ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-red-600 hover:text-white'"
                                    class="px-3 py-1 text-sm rounded transition-colors"
                                    x-text="qty + ' pcs'"
                                ></button>
                            </template>
                        </div>
                    </div>

                    <!-- <div class="text-sm text-gray-500">
                        <p>Jumlah banner/spanduk yang akan dicetak</p>
                        <p x-show="quantity > 1" class="text-blue-600 font-medium">
                            Total area keseluruhan:
                            <span x-text="(totalMeters * quantity).toFixed(2)"></span>
                            m²
                        </p>
                    </div> -->
                </div>

                <!-- Production Time -->
                @if ($product->production_time)
                    <div class="flex items-center text-blue-600 bg-blue-50 p-3 rounded-lg">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <span class="text-sm font-medium">Estimasi pengerjaan: {{ $product->production_time }}</span>
                    </div>
                @endif

                <!-- Action Buttons -->
                <div class="space-y-4">
                    <div class="flex gap-4">
                        <button
                            @click="addToCart()"
                            :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                            class="flex-1 bg-red-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-red-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                ></path>
                            </svg>
                            {{ $product->stock_quantity > 0 ? 'Tambah ke Keranjang' : 'Stok Habis' }}
                        </button>

                        <button
                            @click="buyNow()"
                            :disabled="{{ $product->stock_quantity <= 0 ? 'true' : 'false' }}"
                            class="bg-green-600 text-white py-4 px-6 rounded-lg font-semibold hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed"
                        >
                            Beli Sekarang
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description & Specifications -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
            <!-- Description -->
            <div class="lg:col-span-2 space-y-6">
                <h2 class="text-2xl font-bold text-gray-900">Deskripsi Produk</h2>
                <div class="prose max-w-none text-gray-600">
                    {!! nl2br(e($product->description ?? $product->short_description)) !!}
                </div>

                @if ($product->file_upload_notes)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-semibold text-yellow-800 mb-2">Catatan Upload File:</h3>
                        <p class="text-yellow-700 text-sm">{{ $product->file_upload_notes }}</p>
                        @if ($product->allowed_file_types)
                            <p class="text-yellow-600 text-xs mt-2">
                                Format yang diizinkan: {{ implode(', ', $product->allowed_file_types) }}
                                @if ($product->max_file_size)
                                    (Max: {{ $product->max_file_size }}MB)
                                @endif
                            </p>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Specifications -->
            @if ($product->specifications && count($product->specifications) > 0)
                <div class="space-y-4">
                    <h3 class="text-xl font-bold text-gray-900">Spesifikasi</h3>
                    <div class="bg-gray-50 rounded-lg p-4 space-y-3">
                        @foreach ($product->specifications as $spec)
                            <div class="flex justify-between border-b border-gray-200 pb-2 last:border-b-0">
                                <span class="font-medium text-gray-700">{{ $spec['name'] ?? 'N/A' }}:</span>
                                <span class="text-gray-600">{{ $spec['value'] ?? 'N/A' }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="mb-16">
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">Produk Terkait</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($relatedProducts as $related)
                        <div
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 overflow-hidden"
                        >
                            <div class="relative">
                                <a href="{{ route('product.show', $related->slug) }}">
                                    <img
                                        src="{{ $related->image_url }}"
                                        alt="{{ $related->name }}"
                                        class="w-full h-48 object-cover"
                                    />
                                </a>
                                @if ($related->sale_price)
                                    <span
                                        class="absolute top-2 left-2 bg-red-600 text-white px-2 py-1 rounded text-xs font-semibold"
                                    >
                                        {{ round((($related->price - $related->sale_price) / $related->price) * 100) }}%
                                        OFF
                                    </span>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-semibold text-gray-900 mb-2">
                                    <a href="{{ route('product.show', $related->slug) }}" class="hover:text-red-600">
                                        {{ $related->name }}
                                    </a>
                                </h3>
                                <div class="flex items-center justify-between">
                                    @if ($related->sale_price)
                                        <div>
                                            <span class="text-sm text-gray-500 line-through">
                                                Rp {{ number_format($related->price, 0, ',', '.') }}
                                            </span>
                                            <span class="text-lg font-bold text-red-600">
                                                Rp {{ number_format($related->sale_price, 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @else
                                        <span class="text-lg font-bold text-gray-900">
                                            Rp {{ number_format($related->price, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        function productDetail() {
            return {
                currentImage: '{{ $product->image_url }}',
                customLength: '',
                customWidth: '',
                totalMeters: 0,
                quantity: 1,
                currentPricePerMeter: 0,
                basePrice: {{ $product->sale_price ?? $product->price }},

                // Size variants data - tier harga
                priceTiers: @json($product->size_variants ?? []),

                init() {
                    // Initialize any default values or event listeners
                },

                calculateCustomSize() {
                    const length = parseFloat(this.customLength) || 0;
                    const width = parseFloat(this.customWidth) || 0;
                    this.totalMeters = length * width;
                    this.currentPricePerMeter = this.getPricePerMeter();
                },

                getPricePerMeter() {
                    if (this.totalMeters === 0) return 0;

                    // Cari tier harga yang sesuai
                    for (let tier of this.priceTiers) {
                        if (!(tier.is_available ?? true)) continue;

                        const range = tier.size.replace(/\s/g, '').toLowerCase();

                        if (range.includes('-')) {
                            const [min, max] = range.split('-').map((s) => parseFloat(s.replace('m', '')));
                            if (this.totalMeters >= min && this.totalMeters <= max) {
                                return parseInt(tier.price) || 0;
                            }
                        } else {
                            // Single value atau format lain
                            const value = parseFloat(range.replace('m', ''));
                            if (this.totalMeters <= value) {
                                return parseInt(tier.price) || 0;
                            }
                        }
                    }

                    // Default ke tier tertinggi jika tidak ada yang cocok
                    if (this.priceTiers.length > 0) {
                        const lastTier = this.priceTiers[this.priceTiers.length - 1];
                        return parseInt(lastTier.price) || 0;
                    }

                    return this.basePrice;
                },

                isInPriceTier(sizeRange) {
                    if (this.totalMeters === 0) return false;

                    const range = sizeRange.replace(/\s/g, '').toLowerCase();

                    if (range.includes('-')) {
                        const [min, max] = range.split('-').map((s) => parseFloat(s.replace('m', '')));
                        return this.totalMeters >= min && this.totalMeters <= max;
                    } else {
                        const value = parseFloat(range.replace('m', ''));
                        return this.totalMeters <= value;
                    }
                },

                getSingleBannerPrice() {
                    if (this.totalMeters > 0) {
                        return Math.round(this.totalMeters * this.getPricePerMeter());
                    }
                    return this.basePrice;
                },

                getSubtotal() {
                    return this.getSingleBannerPrice() * this.quantity;
                },

                getDiscountPercentage() {
                    if (this.quantity >= 10) return 10;
                    if (this.quantity >= 5) return 5;
                    return 0;
                },

                getDiscountAmount() {
                    const subtotal = this.getSubtotal();
                    const discountPercent = this.getDiscountPercentage();
                    return Math.round(subtotal * (discountPercent / 100));
                },

                calculatePrice() {
                    const subtotal = this.getSubtotal();
                    const discount = this.getDiscountAmount();
                    return subtotal - discount;
                },

                calculateOriginalPrice() {
                    if (this.totalMeters > 0) {
                        return Math.round(this.totalMeters * {{ $product->price }}) * this.quantity;
                    }
                    return {{ $product->price }} * this.quantity;
                },

                increaseQuantity() {
                    if (this.quantity < 100) {
                        this.quantity++;
                    }
                },

                decreaseQuantity() {
                    if (this.quantity > 1) {
                        this.quantity--;
                    }
                },

                validateQuantity() {
                    if (this.quantity < 1) {
                        this.quantity = 1;
                    } else if (this.quantity > 100) {
                        this.quantity = 100;
                    }
                },

                changeImage(imageUrl) {
                    this.currentImage = imageUrl;
                },

                openLightbox() {
                    // Implementasi lightbox untuk zoom gambar
                    console.log('Open lightbox for image:', this.currentImage);
                },

                addToCart() {
                    if (this.totalMeters === 0) {
                        alert('Silakan masukkan ukuran panjang dan lebar terlebih dahulu!');
                        return;
                    }

                    const data = {
                        product_id: {{ $product->id }},
                        quantity: this.quantity,
                        custom_length: this.customLength,
                        custom_width: this.customWidth,
                        total_meters: this.totalMeters,
                        price_per_meter: this.getPricePerMeter(),
                        unit_price: this.getSingleBannerPrice(),
                        total_price: this.calculatePrice(),
                    };

                    console.log('Add to cart:', data);

                    // Implementasi AJAX untuk menambah ke keranjang
                    // fetch('/cart/add', {
                    //     method: 'POST',
                    //     headers: {
                    //         'Content-Type': 'application/json',
                    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    //     },
                    //     body: JSON.stringify(data)
                    // })
                    // .then(response => response.json())
                    // .then(result => {
                    //     if (result.success) {
                    //         alert(`Produk berhasil ditambahkan ke keranjang!\nTotal: Rp ${this.calculatePrice().toLocaleString('id-ID')}`);
                    //     }
                    // });

                    alert(
                        `Produk ditambahkan ke keranjang!\nTotal: Rp ${this.calculatePrice().toLocaleString('id-ID')}`,
                    );
                },

                buyNow() {
                    if (this.totalMeters === 0) {
                        alert('Silakan masukkan ukuran panjang dan lebar terlebih dahulu!');
                        return;
                    }

                    // Redirect ke halaman checkout dengan data produk
                    const data = {
                        product_id: {{ $product->id }},
                        quantity: this.quantity,
                        custom_length: this.customLength,
                        custom_width: this.customWidth,
                        total_meters: this.totalMeters,
                        price_per_meter: this.getPricePerMeter(),
                        unit_price: this.getSingleBannerPrice(),
                        total_price: this.calculatePrice(),
                    };

                    // Simpan data ke session storage untuk checkout
                    sessionStorage.setItem('checkout_data', JSON.stringify(data));

                    // Redirect ke halaman checkout
                    // window.location.href = '/checkout';

                    console.log('Buy now:', data);
                    alert(`Redirecting to checkout...\nTotal: Rp ${this.calculatePrice().toLocaleString('id-ID')}`);
                },

                contactWhatsApp() {
                    if (this.totalMeters === 0) {
                        alert('Silakan masukkan ukuran panjang dan lebar terlebih dahulu!');
                        return;
                    }

                    const totalPrice = this.calculatePrice();
                    const pricePerMeter = this.getPricePerMeter();
                    const singlePrice = this.getSingleBannerPrice();
                    const discount = this.getDiscountPercentage();

                    let message = `Halo, saya tertarik dengan produk: *{{ $product->name }}*

📦 Detail Pesanan:
- Ukuran: ${this.customLength}m × ${this.customWidth}m (${this.totalMeters.toFixed(2)} m²)
- Harga per m²: Rp ${pricePerMeter.toLocaleString('id-ID')}
- Harga per banner: Rp ${singlePrice.toLocaleString('id-ID')}
- Jumlah: ${this.quantity} banner
- Total area: ${(this.totalMeters * this.quantity).toFixed(2)} m²`;

                    if (discount > 0) {
                        message += `
- Diskon kuantitas: ${discount}%`;
                    }

                    message += `

💰 *Total Estimasi: Rp ${totalPrice.toLocaleString('id-ID')}*

Link produk: {{ url()->current() }}

Mohon konfirmasi ketersediaan dan detail pemesanan. Terima kasih!`;

                    const whatsappNumber = '6281234567890'; // Ganti dengan nomor WhatsApp yang sesuai
                    const whatsappUrl = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(message)}`;
                    window.open(whatsappUrl, '_blank');
                },
            };
        }
    </script>
@endsection
