@extends('layouts.app')

@section('title', $product->meta_title ?? $product->name)
@section('description', $product->meta_description ?? $product->short_description)

@push('head')
    <meta name="keywords" content="{{ is_array($product->keywords) ? implode(', ', $product->keywords) : '' }}" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endpush

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
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16" x-data="productDetail()" x-init="init()">
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

                    <!-- Product Type Badge -->
                    @if ($product->product_type)
                        <div class="absolute top-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm">
                            {{ $product->getProductTypeLabel() }}
                        </div>
                    @endif

                    <!-- Zoom Button -->
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
                    <div class="flex space-x-3 overflow-x-auto pb-2">
                        <!-- Main Image Thumbnail -->
                        <button
                            @click="changeImage('{{ asset('storage/' . $product->featured_image) }}')"
                            :class="currentImage === '{{ asset('storage/' . $product->featured_image) }}' ? 'ring-2 ring-red-500' : ''"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 hover:ring-red-300 transition-all"
                        >
                            <img
                                src="{{ asset('storage/' . $product->featured_image) }}"
                                alt="Main"
                                class="w-full h-full object-cover"
                            />
                        </button>

                        <!-- Gallery Images -->
                        @foreach ($product->gallery_images as $image)
                            <button
                                @click="changeImage('{{ asset('storage/' . $image) }}')"
                                :class="currentImage === '{{ asset('storage/' . $image) }}' ? 'ring-2 ring-red-500' : ''"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 hover:ring-red-300 transition-all"
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

            <!-- Product Info & Form -->
            <div class="space-y-6">
                <!-- Category & SKU -->
                <div class="flex items-center justify-between">
                    <span class="inline-block px-3 py-1 text-sm bg-red-100 text-red-600 rounded-full">
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

                <!-- Volume Pricing Info -->
                @if ($product->volume_pricing && count($product->volume_pricing) > 0)
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h3 class="font-semibold text-green-800 mb-2">💰 Diskon Kuantitas</h3>
                        <div class="space-y-1 text-sm text-green-700">
                            @foreach ($product->volume_pricing as $tier)
                                <div class="flex justify-between">
                                    <span>
                                        {{ $tier['min_qty'] }}{{ isset($tier['max_qty']) ? ' - ' . $tier['max_qty'] : '+' }}
                                        {{ $product->unit_label }}
                                    </span>
                                    <span class="font-semibold">{{ $tier['discount_percent'] }}% OFF</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Custom Size Input -->
                @if ($product->allows_custom_size && in_array($product->pricing_type, ['per_meter_square', 'per_meter_linear']))
                    <div class="space-y-4 bg-gray-50 p-4 rounded-lg">
                        <h3 class="font-semibold text-gray-900">Ukuran Custom:</h3>

                        @if ($product->pricing_type === 'per_meter_square')
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Panjang (meter)</label>
                                    <input
                                        type="number"
                                        x-model="customLength"
                                        @input="calculateCustomSize()"
                                        step="0.1"
                                        min="0.1"
                                        max="50"
                                        placeholder="3.0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    />
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Lebar (meter)</label>
                                    <input
                                        type="number"
                                        x-model="customWidth"
                                        @input="calculateCustomSize()"
                                        step="0.1"
                                        min="0.1"
                                        max="50"
                                        placeholder="1.0"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                    />
                                </div>
                            </div>

                            <!-- Size Calculator Result -->
                            <div
                                x-show="totalMeters > 0"
                                class="text-center p-3 bg-white rounded border-2 border-dashed border-gray-300"
                            >
                                <span class="text-lg font-semibold text-gray-900">
                                    Total Area:
                                    <span class="text-red-600" x-text="totalMeters.toFixed(2)"></span>
                                    m²
                                </span>
                            </div>
                        @else
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Panjang (meter)</label>
                                <input
                                    type="number"
                                    x-model="customLength"
                                    @input="calculateCustomSize()"
                                    step="0.1"
                                    min="0.1"
                                    max="100"
                                    placeholder="5.0"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                />
                                <div
                                    x-show="totalMeters > 0"
                                    class="mt-2 text-center text-lg font-semibold text-red-600"
                                >
                                    <span x-text="totalMeters.toFixed(1)"></span>
                                    meter
                                </div>
                            </div>
                        @endif

                        <!-- Custom Size Error -->
                        <div
                            x-show="errors.customSize"
                            class="text-red-600 text-sm"
                            x-text="errors.customSize"
                        ></div>
                    </div>
                @endif

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

                <!-- Material Options -->
                @if ($product->material_options && count($product->material_options) > 0)
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">Pilihan Bahan:</h3>
                        <div class="space-y-2">
                            @foreach ($product->material_options as $material)
                                <label
                                    class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                >
                                    <div class="flex items-center">
                                        <input
                                            type="radio"
                                            name="material"
                                            value="{{ $material['name'] }}"
                                            x-model="selectedMaterial"
                                            @change="updateMaterialPrice({{ $material['price_adjustment'] ?? 0 }})"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <div class="ml-3">
                                            <div class="font-medium">{{ $material['name'] }}</div>
                                            @if (isset($material['description']))
                                                <div class="text-sm text-gray-500">{{ $material['description'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (($material['price_adjustment'] ?? 0) != 0)
                                        <span
                                            class="text-sm {{ ($material['price_adjustment'] ?? 0) > 0 ? 'text-red-600' : 'text-green-600' }}"
                                        >
                                            {{ ($material['price_adjustment'] ?? 0) > 0 ? '+' : '' }}Rp
                                            {{ number_format($material['price_adjustment'] ?? 0, 0, ',', '.') }}
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Finishing Options -->
                @if ($product->finishing_options && count($product->finishing_options) > 0)
                    <div class="space-y-3">
                        <h3 class="font-semibold text-gray-900">Pilihan Finishing:</h3>
                        <div class="space-y-2">
                            @foreach ($product->finishing_options as $finishing)
                                <label
                                    class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                >
                                    <div class="flex items-center">
                                        <input
                                            type="checkbox"
                                            name="finishing[]"
                                            value="{{ $finishing['name'] }}"
                                            x-model="selectedFinishing"
                                            @change="updateFinishingPrice()"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <div class="ml-3">
                                            <div class="font-medium">{{ $finishing['name'] }}</div>
                                            @if (isset($finishing['description']))
                                                <div class="text-sm text-gray-500">
                                                    {{ $finishing['description'] }}
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    @if (($finishing['price_adjustment'] ?? 0) > 0)
                                        <span class="text-sm text-red-600">
                                            +Rp {{ number_format($finishing['price_adjustment'], 0, ',', '.') }}
                                        </span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Design File Upload Section -->
                @if ($product->requires_design_file)
                    <div class="space-y-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <h3 class="font-semibold text-yellow-800">📁 Upload File Desain (Wajib)</h3>

                        @if ($product->file_requirements)
                            <p class="text-yellow-700 text-sm">{{ $product->file_requirements }}</p>
                        @endif

                        <!-- File Upload Area -->
                        <div
                            @dragover.prevent="dragOver = true"
                            @dragleave.prevent="dragOver = false"
                            @drop.prevent="handleFileDrop($event)"
                            :class="dragOver ? 'border-yellow-400 bg-yellow-100' : 'border-yellow-300'"
                            class="border-2 border-dashed rounded-lg p-6 text-center transition-colors"
                        >
                            <div x-show="!designFile">
                                <svg
                                    class="mx-auto h-12 w-12 text-yellow-400"
                                    stroke="currentColor"
                                    fill="none"
                                    viewBox="0 0 48 48"
                                >
                                    <path
                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                        stroke-width="2"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                    />
                                </svg>
                                <div class="mt-4">
                                    <label for="design-file" class="cursor-pointer">
                                        <span class="mt-2 block text-sm font-medium text-yellow-700">
                                            Klik untuk upload atau drag & drop file di sini
                                        </span>
                                        <input
                                            id="design-file"
                                            type="file"
                                            class="sr-only"
                                            @change="handleFileSelect($event)"
                                            :accept="productData.allowedFormats.map(f => '.' + f).join(',')"
                                        />
                                    </label>
                                    <p class="mt-1 text-xs text-yellow-600">
                                        Format: {{ implode(', ', $product->allowed_formats ?? ['JPG', 'PNG', 'PDF']) }}
                                        @if ($product->max_file_size_mb)
                                                | Max: {{ $product->max_file_size_mb }}MB
                                        @endif
                                    </p>
                                </div>
                            </div>

                            <!-- File Preview -->
                            <div x-show="designFile" class="text-left">
                                <div class="flex items-center justify-between bg-white rounded-lg p-3 border">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                fill-rule="evenodd"
                                                d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                                clip-rule="evenodd"
                                            ></path>
                                        </svg>
                                        <div class="ml-3">
                                            <p
                                                class="text-sm font-medium text-gray-900"
                                                x-text="designFile?.name"
                                            ></p>
                                            <p
                                                class="text-sm text-gray-500"
                                                x-text="designFile ? formatFileSize(designFile.size) : ''"
                                            ></p>
                                        </div>
                                    </div>
                                    <button @click="removeFile()" class="text-red-600 hover:text-red-800">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            ></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- File Upload Error -->
                        <div
                            x-show="errors.designFile"
                            class="text-red-600 text-sm"
                            x-text="errors.designFile"
                        ></div>
                    </div>
                @endif

                <!-- Design Notes -->
                <div class="space-y-3">
                    <h3 class="font-semibold text-gray-900">Catatan Desain:</h3>
                    <textarea
                        x-model="designNotes"
                        rows="4"
                        placeholder="Tambahkan catatan khusus untuk desain Anda... contoh: 'File ini adalah referensi, mohon disesuaikan dengan ukuran yang dipilih' atau 'File sudah final, langsung cetak'"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent resize-none"
                    ></textarea>
                    <p class="text-sm text-gray-500">
                        💡 Jelaskan apakah file yang diupload adalah desain final atau hanya referensi
                    </p>
                </div>

                <!-- Design Service -->
                @if ($product->offers_design_service && $product->design_service_price)
                    <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <h3 class="font-semibold text-purple-800">🎨 Jasa Desain Tersedia</h3>
                                <p class="text-purple-700 text-sm">Butuh bantuan menyempurnakan desain?</p>
                            </div>
                            <div class="text-right">
                                <div class="text-lg font-bold text-purple-600">
                                    +Rp {{ number_format($product->design_service_price, 0, ',', '.') }}
                                </div>
                                <label class="flex items-center mt-1">
                                    <input
                                        type="checkbox"
                                        x-model="needDesignService"
                                        @change="updateDesignServiceCost()"
                                        class="text-purple-600 focus:ring-purple-500"
                                    />
                                    <span class="ml-2 text-sm text-purple-700">Tambahkan jasa desain</span>
                                </label>
                            </div>
                        </div>
                    </div>
                @endif

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
                    <template x-if="volumeDiscount > 0">
                        <div class="flex justify-between text-green-700">
                            <span>
                                Diskon Volume (
                                <span x-text="volumeDiscountPercent"></span>
                                %)
                            </span>
                            <span>
                                -
                                <span x-text="formatPrice(volumeDiscount)"></span>
                            </span>
                        </div>
                    </template>
                    <template x-if="designServiceCost > 0">
                        <div class="flex justify-between text-purple-700">
                            <span>Jasa Desain</span>
                            <span x-text="formatPrice(designServiceCost)"></span>
                        </div>
                    </template>
                    <div class="border-t pt-2 flex justify-between font-bold text-lg text-gray-900">
                        <span>Total</span>
                        <span x-text="formatPrice(calculateFinalPrice())"></span>
                    </div>
                </div>

                <!-- Action Buttons -->
                <button
                    @click="addToCart"
                    :disabled="isLoading || !isOrderValid()"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="isLoading">⏳ Menambahkan...</span>
                    <span x-show="!isLoading">🛒 Tambah ke Keranjang</span>
                </button>

                <button
                    @click="buyNow"
                    :disabled="isLoading || !isOrderValid()"
                    class="bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    <span x-show="isLoading">⏳ Memproses...</span>
                    <span x-show="!isLoading">⚡ Beli Sekarang</span>
                </button>

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
            <!-- end product info & form -->
        </div>
        <!-- end product detail section -->
    </div>
@endsection

@push('scripts')
    <script>
        function productDetail() {
            return {
                // ===============================
                // DATA PROPERTIES
                // ===============================
                currentImage: '{{ asset('storage/' . $product->featured_image) }}',
                customLength: 0,
                customWidth: 0,
                totalMeters: 0,
                quantity: {{ $product->minimum_quantity }},
                selectedPreset: null,
                selectedMaterial: '{{ $product->default_material ?? '' }}',
                selectedFinishing: [], // FIXED: Keep as array consistently
                needDesignService: false,

                // Price calculations
                basePrice: {{ $product->promo_price ?? $product->base_price }},
                originalBasePrice: {{ $product->base_price }},
                materialAdjustment: 0,
                finishingAdjustment: 0,
                volumeDiscount: 0,
                volumeDiscountPercent: 0,
                designServiceCost: 0,
                presetMultiplier: 1,

                // File upload
                designFile: null,
                designNotes: '',
                dragOver: false,
                uploadProgress: 0,

                // UI states
                isLoading: false,
                errors: {},

                // Product constants from backend
                productData: {
                    id: {{ $product->id }},
                    slug: '{{ $product->slug }}',
                    pricingType: '{{ $product->pricing_type }}',
                    allowsCustomSize: {{ $product->allows_custom_size ? 'true' : 'false' }},
                    requiresDesignFile: {{ $product->requires_design_file ? 'true' : 'false' }},
                    offersDesignService: {{ $product->offers_design_service ? 'true' : 'false' }},
                    designServicePrice: {{ $product->design_service_price ?? 0 }},
                    minimumQuantity: {{ $product->minimum_quantity }},
                    stepQuantity: {{ $product->step_quantity }},
                    unitLabel: '{{ $product->unit_label }}',
                    maxFileSize: {{ $product->max_file_size_mb ?? 10 }},

                    allowedFormats:
                        {!! json_encode($product->allowed_formats ? $product->allowed_formats : ['jpg', 'jpeg', 'png', 'pdf', 'ai', 'psd']) !!},
                    materialOptions: {!! json_encode($product->material_options ?? []) !!},
                    finishingOptions: {!! json_encode($product->finishing_options ?? []) !!},
                    volumePricing: {!! json_encode($product->volume_pricing ?? []) !!},
                    sizePresets: {!! json_encode($product->size_presets ?? []) !!},
                },

                // ===============================
                // INITIALIZATION
                // ===============================
                init() {
                    // Set default material if available
                    if (this.productData.materialOptions.length > 0 && !this.selectedMaterial) {
                        this.selectedMaterial = this.productData.materialOptions[0].name;
                        this.updateMaterialPrice(this.productData.materialOptions[0].price_adjustment || 0);
                    }

                    // Initialize calculations
                    this.calculateVolumeDiscount();
                    this.updateDesignServiceCost();
                },

                // ===============================
                // IMAGE GALLERY METHODS
                // ===============================
                changeImage(img) {
                    this.currentImage = img;
                },

                openLightbox() {
                    const lightbox = document.createElement('div');
                    lightbox.className = 'fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50';
                    lightbox.innerHTML = `
                <div class="relative max-w-4xl max-h-full p-4">
                    <img src="${this.currentImage}" class="max-w-full max-h-full object-contain">
                    <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
                    document.body.appendChild(lightbox);
                },

                // ===============================
                // SIZE CALCULATION METHODS
                // ===============================
                calculateCustomSize() {
                    const length = parseFloat(this.customLength) || 0;
                    const width = parseFloat(this.customWidth) || 0;

                    this.totalMeters = this.productData.pricingType === 'per_meter_square' ? length * width : length;

                    this.validateCustomSize();
                },

                validateCustomSize() {
                    delete this.errors.customSize;

                    if (!this.productData.allowsCustomSize) return;

                    const length = parseFloat(this.customLength) || 0;
                    const width = parseFloat(this.customWidth) || 0;

                    if (length < 0.1) {
                        this.errors.customSize = 'Panjang minimal 0.1 meter';
                        return;
                    }

                    if (this.productData.pricingType === 'per_meter_square' && width < 0.1) {
                        this.errors.customSize = 'Lebar minimal 0.1 meter';
                        return;
                    }

                    if (length > 50) {
                        this.errors.customSize = 'Panjang maksimal 50 meter';
                        return;
                    }

                    if (this.productData.pricingType === 'per_meter_square' && width > 50) {
                        this.errors.customSize = 'Lebar maksimal 50 meter';
                        return;
                    }
                },

                selectPresetSize(name, dimensions, multiplier) {
                    this.selectedPreset = name;
                    this.presetMultiplier = multiplier || 1;

                    // Clear custom size when preset is selected
                    this.customLength = 0;
                    this.customWidth = 0;
                    this.totalMeters = 0;
                },

                // ===============================
                // MATERIAL & FINISHING METHODS
                // ===============================
                updateMaterialPrice(adjustment) {
                    this.materialAdjustment = adjustment || 0;
                },

                updateFinishingPrice() {
                    this.finishingAdjustment = 0;

                    if (Array.isArray(this.selectedFinishing)) {
                        this.selectedFinishing.forEach((finishingName) => {
                            const finishing = this.productData.finishingOptions.find((f) => f.name === finishingName);
                            if (finishing && finishing.price_adjustment) {
                                this.finishingAdjustment += finishing.price_adjustment;
                            }
                        });
                    }
                },

                updateDesignServiceCost() {
                    this.designServiceCost = this.needDesignService ? this.productData.designServicePrice : 0;
                },

                // ===============================
                // QUANTITY METHODS
                // ===============================
                increaseQuantity() {
                    this.quantity += this.productData.stepQuantity;
                    this.calculateVolumeDiscount();
                },

                decreaseQuantity() {
                    if (this.quantity > this.productData.minimumQuantity) {
                        this.quantity = Math.max(
                            this.productData.minimumQuantity,
                            this.quantity - this.productData.stepQuantity,
                        );
                        this.calculateVolumeDiscount();
                    }
                },

                validateQuantity() {
                    if (this.quantity < this.productData.minimumQuantity) {
                        this.quantity = this.productData.minimumQuantity;
                    }
                    this.calculateVolumeDiscount();
                },

                // ===============================
                // PRICE CALCULATION METHODS
                // ===============================
                calculateVolumeDiscount() {
                    this.volumeDiscount = 0;
                    this.volumeDiscountPercent = 0;

                    if (!this.productData.volumePricing.length) return;

                    // Find applicable volume tier
                    for (let tier of this.productData.volumePricing) {
                        const minQty = tier.min_qty || 0;
                        const maxQty = tier.max_qty || null;

                        if (this.quantity >= minQty && (maxQty === null || this.quantity <= maxQty)) {
                            this.volumeDiscountPercent = tier.discount_percent || 0;

                            // Calculate discount based on subtotal
                            const subtotal = this.calculateSubtotal();
                            this.volumeDiscount = (subtotal * this.volumeDiscountPercent) / 100;
                            break;
                        }
                    }
                },

                calculateSubtotal() {
                    // Base price with material and finishing adjustments
                    let unitPrice = this.basePrice + this.materialAdjustment + this.finishingAdjustment;

                    // Apply preset multiplier
                    unitPrice *= this.presetMultiplier;

                    // Apply size multiplier for custom sizes
                    let sizeMultiplier = 1;
                    if (this.totalMeters > 0) {
                        sizeMultiplier = this.totalMeters;
                    }

                    return unitPrice * sizeMultiplier * this.quantity;
                },

                calculateFinalPrice() {
                    const subtotal = this.calculateSubtotal();
                    return Math.max(0, subtotal - this.volumeDiscount + this.designServiceCost);
                },

                // ===============================
                // FILE UPLOAD METHODS
                // ===============================
                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.validateAndSetFile(file);
                    }
                },

                handleFileDrop(event) {
                    event.preventDefault();
                    this.dragOver = false;

                    const file = event.dataTransfer.files[0];
                    if (file) {
                        this.validateAndSetFile(file);
                    }
                },

                validateAndSetFile(file) {
                    delete this.errors.designFile;

                    // Check file size
                    const maxSizeBytes = this.productData.maxFileSize * 1024 * 1024;
                    if (file.size > maxSizeBytes) {
                        this.errors.designFile = `Ukuran file maksimal ${this.productData.maxFileSize}MB`;
                        return;
                    }

                    // Check file format
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!this.productData.allowedFormats.includes(extension)) {
                        this.errors.designFile = `Format file harus: ${this.productData.allowedFormats.join(', ')}`;
                        return;
                    }

                    this.designFile = file;
                },

                removeFile() {
                    this.designFile = null;
                    delete this.errors.designFile;
                    // Reset file input
                    const fileInput = document.querySelector('input[type="file"]');
                    if (fileInput) fileInput.value = '';
                },

                // ===============================
                // VALIDATION METHODS
                // ===============================
                isOrderValid() {
                    // Check minimum quantity
                    if (this.quantity < this.productData.minimumQuantity) {
                        return false;
                    }

                    // Check custom size validation
                    if (this.errors.customSize) {
                        return false;
                    }

                    // Check design file requirement
                    if (this.productData.requiresDesignFile && !this.designFile) {
                        return false;
                    }

                    // Check file validation errors
                    if (this.errors.designFile) {
                        return false;
                    }

                    return true;
                },

                getValidationMessage() {
                    if (this.quantity < this.productData.minimumQuantity) {
                        return `Minimal pemesanan ${this.productData.minimumQuantity} ${this.productData.unitLabel}`;
                    }

                    if (this.errors.customSize) {
                        return this.errors.customSize;
                    }

                    if (this.productData.requiresDesignFile && !this.designFile) {
                        return 'File desain wajib diupload';
                    }

                    if (this.errors.designFile) {
                        return this.errors.designFile;
                    }

                    return '';
                },

                // ===============================
                // CART & CHECKOUT METHODS - FIXED
                // ===============================
                async addToCart() {
                    if (!this.isOrderValid()) {
                        this.showError('Harap lengkapi semua data yang diperlukan');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const formData = this.buildFormData();

                        const response = await fetch(`/cart/add/${this.productData.id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                Accept: 'application/json',
                            },
                        });

                        const result = await response.json();

                        if (result.success) {
                            this.showSuccess(result.message || 'Produk berhasil ditambahkan ke keranjang');
                            this.updateCartCount(result.cart_count);
                        } else {
                            if (result.errors) {
                                this.errors = result.errors;
                            }
                            this.showError(result.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        console.error('Add to cart error:', error);
                        this.showError('Terjadi kesalahan jaringan');
                    } finally {
                        this.isLoading = false;
                    }
                },

                async buyNow() {
                    if (!this.isOrderValid()) {
                        this.showError('Harap lengkapi semua data yang diperlukan');
                        return;
                    }

                    this.isLoading = true;

                    try {
                        const formData = this.buildFormData();

                        const response = await fetch(`/checkout/buy-now/${this.productData.id}`, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                                Accept: 'application/json',
                            },
                        });

                        const result = await response.json();

                        if (result.success) {
                            if (result.redirect_url) {
                                window.location.href = result.redirect_url;
                            } else {
                                this.showSuccess(result.message || 'Pesanan berhasil dibuat');
                            }
                        } else {
                            if (result.errors) {
                                this.errors = result.errors;
                            }
                            this.showError(result.message || 'Terjadi kesalahan');
                        }
                    } catch (error) {
                        console.error('Buy now error:', error);
                        this.showError('Terjadi kesalahan jaringan');
                    } finally {
                        this.isLoading = false;
                    }
                },

                // FIXED: Consistent field mapping
                buildFormData() {
                    const formData = new FormData();

                    // Basic product data
                    formData.append('quantity', this.quantity);
                    formData.append('selected_material', this.selectedMaterial || '');

                    // Send finishing as array, backend will handle conversion
                    if (this.selectedFinishing.length > 0) {
                        this.selectedFinishing.forEach((finishing, index) => {
                            formData.append(`selected_finishing[${index}]`, finishing);
                        });
                    }

                    formData.append('design_notes', this.designNotes || '');
                    formData.append('requires_design_service', this.needDesignService ? '1' : '0');

                    // ✅ PERBAIKAN: Convert meter to cm dan mapping yang benar
                    if (this.productData.allowsCustomSize && (this.customLength > 0 || this.customWidth > 0)) {
                        // Convert from meters to centimeters untuk backend
                        formData.append('custom_size_width', this.customWidth * 100); // lebar dalam cm
                        formData.append('custom_size_height', this.customLength * 100); // panjang dalam cm
                    }

                    // Preset size
                    if (this.selectedPreset) {
                        formData.append('selected_preset', this.selectedPreset);
                    }

                    // Design file
                    if (this.designFile) {
                        formData.append('design_file', this.designFile);
                    }
                    return formData;
                },

                // ===============================
                // UTILITY METHODS
                // ===============================
                resetForm() {
                    this.quantity = this.productData.minimumQuantity;
                    this.selectedMaterial = this.productData.materialOptions[0]?.name || '';
                    this.selectedFinishing = [];
                    this.customLength = 0;
                    this.customWidth = 0;
                    this.totalMeters = 0;
                    this.selectedPreset = null;
                    this.designFile = null;
                    this.designNotes = '';
                    this.needDesignService = false;
                    this.errors = {};

                    // Reset file input
                    const fileInput = document.querySelector('input[type="file"]');
                    if (fileInput) fileInput.value = '';

                    this.calculateVolumeDiscount();
                    this.updateDesignServiceCost();
                },

                showSuccess(message) {
                    const toast = document.createElement('div');
                    toast.className =
                        'fixed top-4 right-4 bg-green-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => toast.classList.remove('translate-x-full'), 100);

                    setTimeout(() => {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => toast.remove(), 300);
                    }, 3000);
                },

                showError(message) {
                    const toast = document.createElement('div');
                    toast.className =
                        'fixed top-4 right-4 bg-red-500 text-white px-6 py-4 rounded-lg shadow-lg z-50 transform transition-transform duration-300 translate-x-full';
                    toast.textContent = message;
                    document.body.appendChild(toast);

                    setTimeout(() => toast.classList.remove('translate-x-full'), 100);

                    setTimeout(() => {
                        toast.classList.add('translate-x-full');
                        setTimeout(() => toast.remove(), 300);
                    }, 5000);
                },

                updateCartCount(count) {
                    const cartCountElements = document.querySelectorAll('[data-cart-count]');
                    cartCountElements.forEach((element) => {
                        element.textContent = count;
                        element.style.display = count > 0 ? 'inline' : 'none';
                    });
                },

                contactWhatsApp() {
                    const productName = encodeURIComponent('{{ $product->name }}');
                    const productUrl = encodeURIComponent(window.location.href);
                    const message = `Halo, saya tertarik dengan produk ${productName}. ${productUrl}`;
                    const whatsappUrl = `https://wa.me/6281234567890?text=${message}`;
                    window.open(whatsappUrl, '_blank');
                },

                // ===============================
                // FORMAT HELPERS
                // ===============================
                formatPrice(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(price);
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
                },
            };
        }
    </script>
@endpush
