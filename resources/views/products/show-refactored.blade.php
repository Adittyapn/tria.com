@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('description', $product->meta_description ?? $product->short_description)

@push('head')
    <meta name="keywords" content="{{ is_array($product->keywords) ? implode(', ', $product->keywords) : '' }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .truncate-enhanced {
            overflow: hidden;
            white-space: nowrap;
            text-overflow: ellipsis;
        }
    </style>
@endpush

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm">
            <a href="{{ route('home') }}" class="text-gray-500 hover:text-blue-600" style="color: #000334">Home</a>
            <span class="mx-2 text-gray-400">/</span>
            <a href="#" class="text-gray-500 hover:text-blue-600" style="color: #000334">
                {{ $product->category->name ?? 'Produk' }}
            </a>
            <span class="mx-2 text-gray-400">/</span>
            <span class="text-gray-900">{{ $product->name }}</span>
        </nav>

        <!-- Product Detail Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16" x-data="productDetail()" x-init="init()">
            <div class="space-y-4">
                <!-- Product Images Gallery Component -->
                <x-product.gallery :product="$product" />

                <!-- Product Notes -->
                <div class="bg-white border border-gray-200 rounded-lg p-6 space-y-4">
                    <!-- Important Notice -->
                    <div
                        class="text-white px-3 py-1 rounded inline-block text-sm font-semibold"
                        style="background-color: #000334"
                    >
                        PENTING DI BACA :
                    </div>
                    <div class="text-gray-700 text-sm leading-relaxed">
                        <p>
                            Hasil print tidak bisa 100% sama dengan warna layar monitor customer, karena warna yang
                            dihasilkan mesin cetak menggunakan CMYK (Cyan Magenta Yellow Black) sedangkan tampilan layar
                            monitor adalah RGB (Red Green Blue). Hal ini yang menyebabkan warna desain pada layar
                            monitor customer akan sedikit berbeda dengan hasil cetak.
                        </p>

                        <p class="mt-3">
                            Untuk Cara Order di Website kami
                            <a href="#" class="text-blue-500 hover:text-blue-700 underline">KLIK DISINI</a>
                        </p>
                    </div>

                    <!-- Product Specifications -->
                    <div
                        class="text-white px-3 py-1 rounded inline-block text-sm font-semibold"
                        style="background-color: #000334"
                    >
                        SPESIFIKASI PRODUK DAN AREA CETAK
                    </div>
                    <div class="space-y-2 text-sm text-gray-700">
                        <div class="flex">
                            <span class="font-medium w-32">JENIS BAHAN</span>
                            <span class="mr-2">:</span>
                            <span>CROMO</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium w-32">FINISHING</span>
                            <span class="mr-2">:</span>
                            <span>KISSCUT</span>
                        </div>
                        <div class="flex">
                            <span class="font-medium w-32">HASIL CETAK</span>
                            <span class="mr-2">:</span>
                            <span>CMYK</span>
                        </div>
                    </div>

                    <!-- Material Explanation -->
                    <div
                        class="text-white px-3 py-1 rounded inline-block text-sm font-semibold"
                        style="background-color: #000334"
                    >
                        PENJELASAN JENIS BAHAN :
                    </div>
                    <div class="space-y-3 text-sm text-gray-700">
                        <h4 class="font-semibold text-gray-900">Sticker CROMO</h4>
                        <p>
                            *Yaitu stiker yang berbahan dasar kertas dan lebih murah. Stiker cromo cocok untuk label
                            kemasan makanan kering dan tidak disarankan untuk makan basah atau disimpan dalam kulkas.
                        </p>
                        <p>
                            *Kelebihan stiker ini adalah dapat dicetak full color karena stiker ini cara pembuatannya
                            hanya didesain dikomputer lalu diprint, harganya pun sangat murah dan terjangkau.
                        </p>
                        <p>
                            *Kelemahan stiker ini jika sudah tertempel pada kertas atau plastik dalam waktu lama susah
                            sekali untuk mengelupas atau memindahkannya ke permukaan benda lain. Stiker Cromo jika
                            terkena air juga akan mudah rusak dan tidak tahan gores.
                        </p>
                    </div>

                    <!-- Notes -->
                    <div
                        class="text-white px-3 py-1 rounded inline-block text-sm font-semibold"
                        style="background-color: #000334"
                    >
                        CATATAN :
                    </div>
                    <div class="space-y-2 text-sm text-gray-700">
                        <p class="font-medium">PENGERJAAN 1 HARI BERES BISA DI TUNGGU</p>
                        <p class="font-medium">FILE DI WAJIBKAN MENGGUNAKAN CDR/AI/PNG/VEKTOR</p>
                    </div>
                </div>
            </div>

            <!-- Product Info & Form -->
            <div class="space-y-6">
                <!-- Category & SKU -->
                <div class="flex items-center justify-between">
                    <span
                        class="inline-block px-3 py-1 text-sm text-white rounded-full"
                        style="background-color: #ff7900"
                    >
                        {{ $product->category->name ?? 'Produk' }}
                    </span>
                    @if ($product->sku)
                        <span class="text-sm text-gray-500">SKU: {{ $product->sku }}</span>
                    @endif
                </div>

                <!-- Product Name -->
                <h1 class="text-3xl lg:text-4xl font-bold text-gray-900">{{ $product->name }}</h1>

                <!-- Short Description -->
                @if ($product->short_description)
                    <p class="text-lg text-gray-600">{{ $product->short_description }}</p>
                @endif

                <!-- Pricing System Info -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <div class="flex items-center space-x-2 mb-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <span class="font-semibold text-blue-800">{{ $product->getPricingTypeLabel() }}</span>
                    </div>
                    <p class="text-blue-700 text-sm">{{ $product->pricing_description }}</p>
                </div>

                <!-- Custom Size Component -->
                <x-product.custom-size :product="$product" />

                <!-- Size Presets -->
                @if ($product->size_presets && count($product->size_presets) > 0)
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">Pilih Ukuran Standar:</h3>
                        <div class="grid grid-cols-2 lg:grid-cols-3 gap-3">
                            @foreach ($product->size_presets as $preset)
                                <button
                                    @click="selectPresetSize('{{ $preset['name'] }}', '{{ $preset['dimensions'] ?? '' }}', {{ $preset['price_multiplier'] ?? 1 }})"
                                    :class="selectedPreset === '{{ $preset['name'] }}' ? 'bg-red-600 text-white' : 'bg-white border-2 border-gray-200 hover:border-red-300'"
                                    class="p-3 rounded-lg text-left transition-all hover:shadow-md"
                                >
                                    <div class="font-semibold">{{ $preset['name'] }}</div>
                                    @if (isset($preset['dimensions']))
                                        <div class="text-sm opacity-75">{{ $preset['dimensions'] }}</div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- File Upload Component -->
                <x-product.file-upload :product="$product" />

                <!-- Design Notes -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900">Catatan Desain:</h3>
                    <textarea
                        x-model="designNotes"
                        rows="4"
                        placeholder="Tambahkan catatan khusus untuk desain Anda..."
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                    ></textarea>
                    <p class="text-sm text-gray-500">
                        💡 Jelaskan apakah file yang diupload adalah desain final atau hanya referensi
                    </p>
                </div>

                <!-- Quantity Selector -->
                <div class="flex items-center space-x-4">
                    <label class="font-medium text-gray-700">Jumlah:</label>
                    <div class="flex items-center border rounded-lg overflow-hidden">
                        <button
                            type="button"
                            @click="decreaseQuantity()"
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200"
                        >
                            -
                        </button>
                        <input
                            type="number"
                            x-model="quantity"
                            @input="validateQuantity()"
                            :min="productData.minimumQuantity"
                            :step="productData.stepQuantity"
                            class="w-16 text-center border-0 focus:ring-0"
                        />
                        <button
                            type="button"
                            @click="increaseQuantity()"
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200"
                        >
                            +
                        </button>
                    </div>
                </div>

                <!-- Price Summary -->
                <div class="space-y-3 bg-gray-50 border rounded-lg p-4">
                    <div class="flex justify-between text-gray-700">
                        <span>Subtotal</span>
                        <span x-text="formatPrice(calculateSubtotal())"></span>
                    </div>
                    <div class="border-t pt-2 flex justify-between font-bold text-lg text-gray-900">
                        <span>Total</span>
                        <span x-text="formatPrice(calculateFinalPrice())"></span>
                    </div>
                </div>

                <!-- Action Buttons Component -->
                <x-product.action-buttons />

                <!-- Validation Message -->
                <div x-show="! isOrderValid()" class="bg-red-50 border border-red-200 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <span class="text-red-700 text-sm" x-text="getValidationMessage()"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('js/product/detail.js') }}"></script>
    <script>
        // Initialize product data from backend
        document.addEventListener('alpine:init', () => {
            Alpine.data('productDetail', () => ({
                ...productDetail(), // Import base functionality
                productData: {
                    id: {{ $product->id }},
                    slug: '{{ $product->slug }}',
                    featuredImage: '{{ asset('storage/' . $product->featured_image) }}',
                    pricingType: '{{ $product->pricing_type }}',
                    allowsCustomSize: {{ $product->allows_custom_size ? 'true' : 'false' }},
                    requiresDesignFile: {{ $product->requires_design_file ? 'true' : 'false' }},
                    basePrice: {{ $product->promo_price ?? $product->base_price }},
                    minimumQuantity: {{ $product->minimum_quantity }},
                    stepQuantity: {{ $product->step_quantity }},
                    unitLabel: '{{ $product->unit_label }}',
                    maxFileSize: {{ $product->max_file_size_mb ?? 10 }},
                    allowedFormats: {!! json_encode($product->allowed_formats ?? ['jpg', 'jpeg', 'png', 'pdf']) !!},
                    materialOptions: {!! json_encode($product->material_options ?? []) !!},
                    finishingOptions: {!! json_encode($product->finishing_options ?? []) !!},
                    volumePricing: {!! json_encode($product->volume_pricing ?? []) !!},
                },
            }));
        });
    </script>
@endpush
