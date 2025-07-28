@extends("layouts.app")

@section("content")
    <div class="bg-gray-50 py-6">
        <div class="container mx-auto px-4">
            <!-- Breadcrumb -->
            <nav class="flex text-sm text-gray-600 mb-6">
                <a href="{{ url("/") }}" class="hover:text-red-600 transition-colors">Beranda</a>
                <span class="mx-2">></span>
                <a href="#" class="hover:text-red-600 transition-colors">Media Promosi</a>
                <span class="mx-2">></span>
                <span class="text-gray-900">Spanduk / Banner Flexi China 280 gsm Glossy</span>
            </nav>

            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Product Images -->
                <div
                    class="space-y-4"
                    x-data="{
                        mainImage:
                            'https://via.placeholder.com/600x400/ff6b6b/ffffff?text=Spanduk+Banner+Main',
                        images: [
                            'https://via.placeholder.com/600x400/ff6b6b/ffffff?text=Spanduk+Banner+Main',
                            'https://via.placeholder.com/150x150/4ecdc4/ffffff?text=Detail+1',
                            'https://via.placeholder.com/150x150/45b7d1/ffffff?text=Detail+2',
                            'https://via.placeholder.com/150x150/96ceb4/ffffff?text=Detail+3',
                        ],
                    }"
                >
                    <!-- Main Image -->
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                        <img :src="mainImage" alt="Spanduk Banner" class="w-full h-96 object-cover" />
                    </div>

                    <!-- Thumbnail Images -->
                    <div class="flex space-x-3">
                        <template x-for="(image, index) in images" :key="index">
                            <button
                                @click="mainImage = image"
                                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden border-2 transition-all"
                                :class="mainImage === image ? 'border-red-500' : 'border-gray-200 hover:border-gray-300'"
                            >
                                <img
                                    :src="image"
                                    :alt="'Thumbnail ' + (index + 1)"
                                    class="w-full h-full object-cover"
                                />
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Product Details -->
                <div
                    class="space-y-6"
                    x-data="{
                        selectedMaterial: 'Spanduk / Banner Flexi China 280 gsm Glossy',
                        selectedFinishing: 'Mata Ayam Pojok Pojok',
                        priceRange: '1 - 250 m',
                        price: 20000,
                        panjang: 'ex:1.2',
                        lebar: 'ex:3',
                        quantity: 90,
                        notes: '',

                        materials: [
                            'Spanduk / Banner Flexi China 280 gsm Glossy',
                            'Banner Korea 440 gsm',
                            'Banner Jerman 510 gsm Premium',
                        ],

                        finishings: [
                            'Mata Ayam Pojok Pojok',
                            'Mata Ayam Keliling',
                            'Tanpa Mata Ayam',
                        ],

                        priceRanges: [
                            { range: '1 - 250 m', price: 20000 },
                            { range: '250 - 1000 m', price: 17500 },
                            { range: '≥ 1001 m', price: 15000 },
                        ],

                        updatePrice() {
                            const selected = this.priceRanges.find(
                                (p) => p.range === this.priceRange,
                            )
                            this.price = selected ? selected.price : 20000
                        },
                    }"
                >
                    <!-- Product Title & Rating -->
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">
                            Spanduk / Banner Flexi China 280 gsm Glossy
                        </h1>

                        <div class="flex items-center space-x-4 mb-4">
                            <!-- Social Share -->
                            <div class="flex items-center space-x-2 text-sm text-gray-600">
                                <span>Bagikan:</span>
                                <a
                                    href="#"
                                    class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="p-2 bg-sky-400 text-white rounded hover:bg-sky-500 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="p-2 bg-red-600 text-white rounded hover:bg-red-700 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M21.8,8.001c0,0-0.195-1.378-0.795-1.985c-0.76-0.797-1.613-0.801-2.004-0.847c-2.799-0.202-6.997-0.202-6.997-0.202h-0.009c0,0-4.198,0-6.997,0.202C4.608,5.216,3.756,5.22,2.995,6.016C2.395,6.623,2.2,8.001,2.2,8.001S2,9.62,2,11.238v1.517c0,1.618,0.2,3.237,0.2,3.237s0.195,1.378,0.795,1.985c0.761,0.797,1.76,0.771,2.205,0.855c1.6,0.153,6.8,0.201,6.8,0.201s4.203-0.006,7.001-0.209c0.391-0.047,1.243-0.051,2.004-0.847c0.6-0.607,0.795-1.985,0.795-1.985s0.2-1.618,0.2-3.237v-1.517C22,9.62,21.8,8.001,21.8,8.001z M9.935,14.594l-0.001-5.62l5.404,2.82L9.935,14.594z"
                                        />
                                    </svg>
                                </a>
                                <a
                                    href="#"
                                    class="p-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898 1.866 1.869 2.893 4.352 2.892 6.993-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                                        />
                                    </svg>
                                </a>
                            </div>

                            <!-- Rating -->
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    <template x-for="i in 5" :key="i">
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                    </template>
                                </div>
                                <span class="text-sm text-gray-600">(5 Ulasan)</span>
                            </div>
                        </div>

                        <!-- Important Notice -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-blue-700">
                                        <strong>PENTING DI BACA :</strong>
                                        Hasil print tidak bisa 100% sama dengan warna layar monitor customer, karena
                                        warna yang dihasilkan mesin cetak menggunakan sistem CMYK, sementara layar
                                        monitor menggunakan sistem RGB.
                                        <a href="#" class="underline hover:no-underline">Selengkapnya</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Options -->
                    <div class="space-y-6 bg-white p-6 rounded-lg shadow-sm mb-28 relative z-10">
                        <!-- Material Selection -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Pilih Produk dan Bahan Spanduk Banner Baliho Backdrop
                            </label>
                            <select
                                x-model="selectedMaterial"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            >
                                <template x-for="material in materials" :key="material">
                                    <option :value="material" x-text="material"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">Harga per m</label>
                            <div class="space-y-2">
                                <template x-for="range in priceRanges" :key="range.range">
                                    <label
                                        class="flex items-center justify-between p-3 border rounded-lg cursor-pointer hover:bg-gray-50"
                                        :class="priceRange === range.range ? 'border-red-500 bg-red-50' : 'border-gray-200'"
                                    >
                                        <div class="flex items-center">
                                            <input
                                                type="radio"
                                                :value="range.range"
                                                x-model="priceRange"
                                                @change="updatePrice()"
                                                class="text-red-600 focus:ring-red-500"
                                            />
                                            <span class="ml-2 text-sm" x-text="range.range"></span>
                                        </div>
                                        <span
                                            class="font-semibold text-red-600"
                                            x-text="'Rp ' + range.price.toLocaleString('id-ID')"
                                        ></span>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Dimensions -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Panjang</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="panjang"
                                        class="w-full p-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="ex:1.2"
                                    />
                                    <span class="absolute right-3 top-3 text-gray-500 text-sm">m</span>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-2">Lebar</label>
                                <div class="relative">
                                    <input
                                        type="text"
                                        x-model="lebar"
                                        class="w-full p-3 pr-8 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        placeholder="ex:3"
                                    />
                                    <span class="absolute right-3 top-3 text-gray-500 text-sm">m</span>
                                </div>
                            </div>
                        </div>

                        <!-- Finishing -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-3">
                                Finishing Lebihan, Lipat, Mata Ayam
                            </label>
                            <select
                                x-model="selectedFinishing"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            >
                                <template x-for="finishing in finishings" :key="finishing">
                                    <option :value="finishing" x-text="finishing"></option>
                                </template>
                            </select>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Keterangan</label>
                            <textarea
                                x-model="notes"
                                rows="4"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                placeholder="Tambahkan catatan khusus untuk pesanan Anda..."
                            ></textarea>
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Upload File
                                <span class="text-gray-500">(PDF, JPG, ZIP, RAR max 50Mb)</span>
                            </label>
                            <div
                                class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-red-400 transition-colors"
                            >
                                <div class="space-y-1 text-center">
                                    <svg
                                        class="mx-auto h-12 w-12 text-gray-400"
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
                                    <div class="flex text-sm text-gray-600">
                                        <label
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-red-500"
                                        >
                                            <span>Upload File</span>
                                            <input
                                                type="file"
                                                class="sr-only"
                                                multiple
                                                accept=".pdf,.jpg,.jpeg,.png,.zip,.rar"
                                            />
                                        </label>
                                        <p class="pl-1">atau drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, PNG, ZIP, RAR up to 50MB</p>
                                </div>
                            </div>
                        </div>

                        <!-- Quantity -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">
                                Jumlah:
                                <span class="text-gray-500">(Min Order: 90 Lembar)</span>
                            </label>
                            <div class="flex items-center space-x-3">
                                <button
                                    @click="quantity = Math.max(90, quantity - 1)"
                                    class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 12H4"
                                        />
                                    </svg>
                                </button>
                                <input
                                    type="number"
                                    x-model="quantity"
                                    min="90"
                                    class="w-20 px-3 py-2 text-center border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                />
                                <button
                                    @click="quantity++"
                                    class="w-10 h-10 rounded-lg border border-gray-300 flex items-center justify-center hover:bg-gray-50 transition-colors"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 4v16m8-8H4"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Promo Code -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 mb-2">Promo</label>
                            <div class="flex space-x-2">
                                <input
                                    type="text"
                                    class="flex-1 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                    placeholder="Masukkan Kode Promo"
                                />
                                <button
                                    class="px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold"
                                >
                                    Apply
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Add to Cart Button -->
                    <div class="sticky bottom-4 bg-white p-4 rounded-lg shadow-lg border z-20">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <div
                                    class="text-2xl font-bold text-red-600"
                                    x-text="
                                        'Rp ' +
                                            (
                                                price *
                                                parseFloat(panjang || 1) *
                                                parseFloat(lebar || 1) *
                                                quantity
                                            ).toLocaleString('id-ID')
                                    "
                                ></div>
                                <div class="text-sm text-gray-600">
                                    <span x-text="panjang + ' x ' + lebar + ' m • ' + quantity + ' pcs'"></span>
                                </div>
                            </div>
                            <button
                                class="bg-red-600 hover:bg-red-700 text-white px-8 py-4 rounded-lg font-semibold transition-colors shadow-lg"
                            >
                                Tambah ke Keranjang
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Description & Reviews -->
            <div class="mt-12 grid lg:grid-cols-3 gap-8">
                <!-- Description -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-4">Deskripsi Produk</h3>
                        <div class="prose prose-sm max-w-none text-gray-600">
                            <p>
                                Banner Spanduk Flexi China 280 gsm Glossy adalah pilihan terbaik untuk kebutuhan promosi
                                outdoor dan indoor Anda. Dibuat dengan material berkualitas tinggi yang tahan cuaca dan
                                menghasilkan warna yang tajam dan cerah.
                            </p>

                            @php
                                $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
                                $bgPattern = "data:image/svg+xml," . rawurlencode($svg);
                            @endphp

                            <div
                                class="bg-gradient-to-r from-red-50 to-red-100 p-6 rounded-lg mt-6 relative overflow-hidden"
                            >
                                <div
                                    class="absolute inset-0 opacity-10"
                                    style="background-image: url('{{ $bgPattern }}')"
                                ></div>
                                <div class="relative">
                                    <h4 class="font-semibold text-red-800 mb-3">Keunggulan Produk:</h4>
                                    <ul class="space-y-2 text-red-700">
                                        <li class="flex items-start">
                                            <svg
                                                class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Material Flexi China 280 gsm dengan finishing glossy
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Tahan air dan cuaca ekstrem
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Warna cetak tajam dan tidak mudah pudar
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Dilengkapi mata ayam untuk pemasangan mudah
                                        </li>
                                        <li class="flex items-start">
                                            <svg
                                                class="w-5 h-5 text-red-600 mr-2 mt-0.5 flex-shrink-0"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                            Cocok untuk outdoor dan indoor
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <h4 class="font-semibold text-gray-900 mt-6 mb-3">Spesifikasi:</h4>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-medium text-gray-700">Material:</span>
                                        <span class="text-gray-600">Flexi China 280 gsm</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Finishing:</span>
                                        <span class="text-gray-600">Glossy</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Ketebalan:</span>
                                        <span class="text-gray-600">280 gsm</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Minimum Order:</span>
                                        <span class="text-gray-600">90 lembar</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Waktu Produksi:</span>
                                        <span class="text-gray-600">1-3 hari kerja</span>
                                    </div>
                                    <div>
                                        <span class="font-medium text-gray-700">Ukuran:</span>
                                        <span class="text-gray-600">Custom sesuai kebutuhan</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Reviews Section -->
                    <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6">Ulasan Pelanggan</h3>

                        <!-- Review Summary -->
                        <div class="flex items-center space-x-6 mb-6 pb-6 border-b border-gray-200">
                            <div class="text-center">
                                <div class="text-4xl font-bold text-gray-900">4.8</div>
                                <div class="flex items-center justify-center mt-1">
                                    <template x-data="{}" x-for="i in 5" :key="i">
                                        <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                    </template>
                                </div>
                                <div class="text-sm text-gray-600 mt-1">5 ulasan</div>
                            </div>
                            <div class="flex-1">
                                <div class="space-y-2">
                                    <template
                                        x-data="{
                                            ratings: [
                                                { stars: 5, count: 4, percentage: 80 },
                                                { stars: 4, count: 1, percentage: 20 },
                                                { stars: 3, count: 0, percentage: 0 },
                                                { stars: 2, count: 0, percentage: 0 },
                                                { stars: 1, count: 0, percentage: 0 },
                                            ],
                                        }"
                                        x-for="rating in ratings"
                                        :key="rating.stars"
                                    >
                                        <div class="flex items-center space-x-2 text-sm">
                                            <span x-text="rating.stars" class="w-2"></span>
                                            <svg
                                                class="w-4 h-4 text-yellow-400"
                                                fill="currentColor"
                                                viewBox="0 0 20 20"
                                            >
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                />
                                            </svg>
                                            <div class="flex-1 bg-gray-200 rounded-full h-2">
                                                <div
                                                    class="bg-yellow-400 h-2 rounded-full"
                                                    :style="`width: ${rating.percentage}%`"
                                                ></div>
                                            </div>
                                            <span x-text="rating.count" class="w-4 text-gray-600"></span>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>

                        <!-- Individual Reviews -->
                        <div class="space-y-6">
                            <!-- Review 1 -->
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center flex-shrink-0"
                                    >
                                        <span class="text-red-600 font-semibold text-sm">BK</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-semibold text-gray-900">Budi Kurniawan</h4>
                                            <div class="flex items-center">
                                                <template x-data="{}" x-for="i in 5" :key="i">
                                                    <svg
                                                        class="w-4 h-4 text-yellow-400"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                        />
                                                    </svg>
                                                </template>
                                            </div>
                                            <span class="text-sm text-gray-500">2 minggu yang lalu</span>
                                        </div>
                                        <p class="text-gray-700 text-sm leading-relaxed">
                                            Kualitas banner sangat baik, warna cetak tajam dan material tidak mudah
                                            robek. Sudah dipasang di depan toko selama 2 minggu dan masih terlihat
                                            seperti baru. Pelayanan cepat dan hasil sesuai ekspektasi.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Review 2 -->
                            <div class="border-b border-gray-200 pb-6">
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0"
                                    >
                                        <span class="text-blue-600 font-semibold text-sm">SR</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-semibold text-gray-900">Sari Rahayu</h4>
                                            <div class="flex items-center">
                                                <template x-data="{}" x-for="i in 5" :key="i">
                                                    <svg
                                                        class="w-4 h-4 text-yellow-400"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                        />
                                                    </svg>
                                                </template>
                                            </div>
                                            <span class="text-sm text-gray-500">1 bulan yang lalu</span>
                                        </div>
                                        <p class="text-gray-700 text-sm leading-relaxed">
                                            Puas dengan hasilnya! Banner untuk event pernikahan anak saya hasilnya bagus
                                            sekali. Mata ayamnya kuat dan mudah dipasang. Terima kasih Tria Digital!
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Review 3 -->
                            <div>
                                <div class="flex items-start space-x-4">
                                    <div
                                        class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0"
                                    >
                                        <span class="text-green-600 font-semibold text-sm">AP</span>
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-2">
                                            <h4 class="font-semibold text-gray-900">Ahmad Permana</h4>
                                            <div class="flex items-center">
                                                <template x-data="{}" x-for="i in 4" :key="i">
                                                    <svg
                                                        class="w-4 h-4 text-yellow-400"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                        />
                                                    </svg>
                                                </template>
                                                <svg
                                                    class="w-4 h-4 text-gray-300"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                            </div>
                                            <span class="text-sm text-gray-500">2 bulan yang lalu</span>
                                        </div>
                                        <p class="text-gray-700 text-sm leading-relaxed">
                                            Material cukup bagus untuk harganya. Proses pengerjaan cepat, hanya saja
                                            warna sedikit berbeda dari yang di layar. Tapi overall puas dengan hasilnya.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Related Products -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Produk Terkait</h3>
                        <div class="space-y-4">
                            <!-- Related Product 1 -->
                            <div class="flex space-x-3">
                                <img
                                    src="https://via.placeholder.com/80x80/ff6b6b/ffffff?text=X-Banner"
                                    alt="X-Banner"
                                    class="w-20 h-20 object-cover rounded-lg"
                                />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-gray-900 mb-1">X-Banner 60x160cm</h4>
                                    <p class="text-xs text-gray-600 mb-2">Banner portable untuk event</p>
                                    <div class="text-red-600 font-semibold text-sm">Rp 125.000</div>
                                </div>
                            </div>

                            <!-- Related Product 2 -->
                            <div class="flex space-x-3">
                                <img
                                    src="https://via.placeholder.com/80x80/4ecdc4/ffffff?text=Roll+Up"
                                    alt="Roll Up Banner"
                                    class="w-20 h-20 object-cover rounded-lg"
                                />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-gray-900 mb-1">Roll Up Banner</h4>
                                    <p class="text-xs text-gray-600 mb-2">Banner premium dengan stand</p>
                                    <div class="text-red-600 font-semibold text-sm">Rp 275.000</div>
                                </div>
                            </div>

                            <!-- Related Product 3 -->
                            <div class="flex space-x-3">
                                <img
                                    src="https://via.placeholder.com/80x80/45b7d1/ffffff?text=Backdrop"
                                    alt="Backdrop"
                                    class="w-20 h-20 object-cover rounded-lg"
                                />
                                <div class="flex-1">
                                    <h4 class="font-semibold text-sm text-gray-900 mb-1">Backdrop Premium</h4>
                                    <p class="text-xs text-gray-600 mb-2">Background foto premium</p>
                                    <div class="text-red-600 font-semibold text-sm">Rp 180.000</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Support -->
                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-6 text-white">
                        @php
                            $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
                            $bgPattern = "data:image/svg+xml," . rawurlencode($svg);
                        @endphp

                        <div
                            class="absolute inset-0 opacity-10"
                            style="background-image: url('{{ $bgPattern }}')"
                        ></div>

                        <div class="relative">
                            <h3 class="text-lg font-bold mb-4">Butuh Bantuan?</h3>
                            <p class="text-sm mb-4 text-white/90">Tim customer service kami siap membantu Anda 24/7</p>
                            <div class="space-y-3">
                                <a
                                    href="#"
                                    class="flex items-center space-x-3 text-sm hover:text-yellow-300 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898 1.866 1.869 2.893 4.352 2.892 6.993-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                                        />
                                    </svg>
                                    <span>WhatsApp: +62 822-2536-257</span>
                                </a>
                                <a
                                    href="#"
                                    class="flex items-center space-x-3 text-sm hover:text-yellow-300 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                        />
                                    </svg>
                                    <span>info@triadigital.co.id</span>
                                </a>
                                <a
                                    href="#"
                                    class="flex items-center space-x-3 text-sm hover:text-yellow-300 transition-colors"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                                        />
                                    </svg>
                                    <span>Telepon: (0822) 2536-257</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Info Pengiriman</h3>
                        <div class="space-y-4 text-sm">
                            <div class="flex items-start space-x-3">
                                <svg
                                    class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <div>
                                    <div class="font-semibold text-gray-900">Pengiriman Gratis</div>
                                    <div class="text-gray-600">Untuk pembelian minimal Rp 500.000</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg
                                    class="w-5 h-5 text-blue-500 mt-0.5 flex-shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                        clip-rule="evenodd"
                                    />
                                </svg>
                                <div>
                                    <div class="font-semibold text-gray-900">Estimasi Produksi</div>
                                    <div class="text-gray-600">1-3 hari kerja</div>
                                </div>
                            </div>
                            <div class="flex items-start space-x-3">
                                <svg
                                    class="w-5 h-5 text-yellow-500 mt-0.5 flex-shrink-0"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div>
                                    <div class="font-semibold text-gray-900">Garansi Kualitas</div>
                                    <div class="text-gray-600">Uang kembali jika tidak puas</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
