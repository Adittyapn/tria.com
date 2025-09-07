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

        /* Enhanced truncate utility for better text handling */
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
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image -->
                <div class="relative overflow-hidden rounded-lg bg-gray-100">
                    <img
                        :src="currentImage"
                        alt="{{ $product->name }}"
                        class="w-full h-96 lg:h-[450px] object-contain transition-transform hover:scale-105"
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
                            :class="currentImage === '{{ asset('storage/' . $product->featured_image) }}' ? 'ring-2' : ''"
                            style="ring-color: #000334"
                            class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 transition-all"
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
                                :class="currentImage === '{{ asset('storage/' . $image) }}' ? 'ring-2' : ''"
                                style="ring-color: #000334"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 transition-all"
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                        style="focus:ring-color: #000334;"
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
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:border-transparent"
                                        style="focus:ring-color: #000334;"
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

                <div class="flex justify-end space-x-4">
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
                </div>

                <!-- Debug Button - Remove in production -->
                <div class="mt-4 space-y-2">
                    <button
                        @click="showSuccessModal = true; loadRecommendedProducts();"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg text-sm"
                    >
                        🧪 Test Modal (Debug)
                    </button>
                    <button
                        @click="updateCartCount(5); console.log('Cart count set to 5');"
                        class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-lg text-sm"
                    >
                        🔢 Test Cart Count (Debug)
                    </button>
                </div>
                <!-- Action Buttons -->

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

            <!-- Success Modal -->
            <div
                x-show="showSuccessModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0"
                x-transition:enter-end="opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4"
                style="display: none"
                @click.self="showSuccessModal = false"
            >
                <div
                    x-show="showSuccessModal"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95"
                    class="bg-white rounded-lg w-full max-w-4xl mx-2 sm:mx-4 max-h-[95vh] sm:max-h-[90vh] overflow-y-auto"
                >
                    <!-- Modal Header -->
                    <div class="flex items-center justify-between p-4 sm:p-6 border-b">
                        <h3 class="text-lg sm:text-xl font-semibold text-gray-900">Berhasil Ditambahkan</h3>
                        <button
                            @click="showSuccessModal = false"
                            class="text-gray-400 hover:text-gray-600 transition-colors p-2 -m-2 touch-manipulation"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                ></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Added Product -->
                    <div class="p-4 sm:p-6 border-b">
                        <div class="flex items-center space-x-4">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                <img
                                    src="{{ asset('storage/' . $product->featured_image) }}"
                                    alt="{{ $product->name }}"
                                    class="w-full h-full object-contain"
                                />
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm sm:text-base font-medium text-gray-900 truncate">
                                    {{ $product->name }}
                                </h4>
                                <div class="flex items-center justify-between mt-2">
                                    <button
                                        class="bg-green-600 hover:bg-green-700 text-white px-3 sm:px-4 py-2 rounded-lg text-xs sm:text-sm font-medium transition-colors touch-manipulation"
                                        onclick="window.location.href='/cart'"
                                    >
                                        Lihat Keranjang
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recommended Products -->
                    <div class="p-4 sm:p-6">
                        <div class="flex items-center mb-4 sm:mb-6">
                            <h4 class="text-lg sm:text-xl font-semibold text-gray-900">Kamu Mungkin Juga Suka</h4>
                        </div>

                        <!-- Loading Skeleton -->
                        <div x-show="isLoadingRecommended" class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                            <template x-for="i in 6" :key="i">
                                <div class="border border-gray-200 rounded-lg overflow-hidden animate-pulse">
                                    <div class="aspect-square bg-gray-200"></div>
                                    <div class="p-2 sm:p-3 space-y-2">
                                        <div class="h-4 bg-gray-200 rounded w-full"></div>
                                        <div class="h-3 bg-gray-200 rounded w-2/3"></div>
                                        <div class="flex items-center gap-1">
                                            <div class="h-3 w-3 bg-gray-200 rounded"></div>
                                            <div class="h-3 bg-gray-200 rounded w-8"></div>
                                            <div class="h-3 bg-gray-200 rounded w-16"></div>
                                        </div>
                                        <div class="h-3 bg-gray-200 rounded w-3/4"></div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Products Grid -->
                        <div
                            x-show="! isLoadingRecommended && recommendedProducts.length > 0"
                            class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4"
                        >
                            <template x-for="product in recommendedProducts" :key="product.id">
                                <div
                                    class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                                    @click="window.location.href = `/product/${product.slug}`"
                                >
                                    <div class="aspect-square bg-gray-100 relative">
                                        <img
                                            :src="product.image"
                                            :alt="product.name"
                                            class="w-full h-full object-contain p-1 sm:p-2"
                                        />
                                        <!-- Discount Badge -->
                                        <div
                                            x-show="product.discount"
                                            class="absolute top-1 sm:top-2 left-1 sm:left-2 bg-red-500 text-white text-xs px-1.5 sm:px-2 py-0.5 sm:py-1 rounded"
                                            x-text="product.discount + '%'"
                                        ></div>
                                    </div>
                                    <div class="p-2 sm:p-3 space-y-1 sm:space-y-2">
                                        <h5
                                            class="text-xs sm:text-sm font-medium text-gray-900 line-clamp-2 leading-tight"
                                            x-text="product.name"
                                        ></h5>
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-1 sm:gap-2 flex-wrap">
                                                <div
                                                    class="text-xs sm:text-sm font-bold text-gray-900"
                                                    x-text="product.price"
                                                ></div>
                                                <div
                                                    x-show="product.original_price"
                                                    class="text-xs text-gray-500 line-through"
                                                    x-text="product.original_price"
                                                ></div>
                                            </div>
                                            <div class="flex items-center gap-1 text-xs text-gray-500">
                                                <svg
                                                    class="w-2.5 h-2.5 sm:w-3 sm:h-3 text-yellow-400 flex-shrink-0"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                                <span x-text="product.rating" class="flex-shrink-0"></span>
                                                <span class="hidden sm:inline">•</span>
                                                <span
                                                    x-text="product.sold + ' terjual'"
                                                    class="truncate hidden sm:inline"
                                                ></span>
                                            </div>
                                            <div
                                                class="text-xs text-gray-400 truncate hidden sm:block"
                                                x-text="product.location"
                                            ></div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>

                        <!-- Empty State -->
                        <div
                            x-show="!isLoadingRecommended && recommendedProducts.length === 0"
                            class="text-center py-8 sm:py-12"
                        >
                            <div
                                class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center"
                            >
                                <svg
                                    class="w-6 h-6 sm:w-8 sm:h-8 text-gray-400"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10"
                                    ></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Tidak ada produk rekomendasi tersedia</p>
                        </div>
                    </div>
                </div>
            </div>
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
                showSuccessModal: false,
                recommendedProducts: [],
                isLoadingRecommended: false,

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
                    console.log('addToCart function called');

                    if (!this.isOrderValid()) {
                        this.showError('Harap lengkapi semua data yang diperlukan');
                        return;
                    }

                    this.isLoading = true;
                    console.log('Starting add to cart request...');

                    try {
                        const formData = this.buildFormData();
                        console.log('FormData built:', formData);

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

                        console.log('Response status:', response.status);
                        const result = await response.json();
                        console.log('Response result:', result);

                        if (result.success) {
                            console.log('Success! Showing modal...');
                            this.updateCartCount(result.cart_count);
                            this.showSuccessModal = true;
                            this.loadRecommendedProducts(); // Load recommended products when modal opens
                            console.log('Modal should be visible now. showSuccessModal:', this.showSuccessModal);
                        } else {
                            console.log('Request failed:', result);
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
                    console.log('updateCartCount called with count:', count);

                    // Update cart count in header using Alpine.js global store or direct variable update
                    // Method 1: Dispatch custom event for Alpine.js header component
                    window.dispatchEvent(
                        new CustomEvent('cart-updated', {
                            detail: { count: count },
                        }),
                    );

                    // Method 2: Try to update Alpine.js component directly if available
                    if (window.Alpine && window.Alpine.store) {
                        try {
                            window.Alpine.store('cart', { count: count });
                        } catch (e) {
                            console.log('Alpine store not available:', e);
                        }
                    }

                    // Method 3: Update any elements with data-cart-count attribute
                    const cartCountElements = document.querySelectorAll('[data-cart-count]');
                    cartCountElements.forEach((element) => {
                        element.textContent = count;
                        element.style.display = count > 0 ? 'inline' : 'none';
                    });

                    // Method 4: Update cart badge directly by class/text content
                    const cartBadges = document.querySelectorAll('.bg-yellow-400');
                    cartBadges.forEach((badge) => {
                        if (badge.textContent.trim() === '0' || /^\d+$/.test(badge.textContent.trim())) {
                            badge.textContent = count;
                            badge.style.display = count > 0 ? 'flex' : 'none';
                        }
                    });

                    console.log('Cart count updated to:', count);
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

                // Load recommended products for modal
                async loadRecommendedProducts() {
                    this.isLoadingRecommended = true;
                    this.recommendedProducts = []; // Clear existing products

                    try {
                        const categoryId = {{ $product->category_id ?? 'null' }};
                        const currentProductId = {{ $product->id }};

                        const url = `/api/recommended-products/${categoryId}?current_product_id=${currentProductId}`;

                        const response = await fetch(url, {
                            headers: {
                                Accept: 'application/json',
                                'Content-Type': 'application/json',
                            },
                        });

                        if (response.ok) {
                            const result = await response.json();
                            if (result.success) {
                                this.recommendedProducts = result.data || [];
                            } else {
                                console.warn('API returned success:false', result.message);
                                this.recommendedProducts = [];
                            }
                        } else {
                            console.error('Failed to load recommended products, HTTP status:', response.status);
                            this.recommendedProducts = [];
                        }
                    } catch (error) {
                        console.error('Failed to load recommended products:', error);
                        this.recommendedProducts = [];
                    } finally {
                        this.isLoadingRecommended = false;
                    }
                },
            };
        }
    </script>
@endpush
