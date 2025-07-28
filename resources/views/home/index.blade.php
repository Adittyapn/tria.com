@extends("layouts.app")

@section("content")
    <!-- Hero Section -->
    <section
        class="relative bg-gradient-to-br from-red-500 to-red-600 overflow-hidden"
    >
        <!-- Background Pattern -->
        @php
            $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
            $bgPattern = "data:image/svg+xml," . rawurlencode($svg);
        @endphp

        <div
            class="absolute inset-0 opacity-20"
            style="background-image: url('{{ $bgPattern }}')"
        ></div>

        <div class="container mx-auto px-4 py-16 lg:py-24">
            <div class="grid lg:grid-cols-2 gap-8 items-center">
                <!-- Text Content -->
                <div class="text-white space-y-6 z-10 relative">
                    <div
                        class="inline-flex items-center bg-yellow-400 text-gray-900 px-4 py-2 rounded-full text-sm font-semibold transform -rotate-2"
                    >
                        <svg
                            class="w-4 h-4 mr-2"
                            fill="currentColor"
                            viewBox="0 0 20 20"
                        >
                            <path
                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                            />
                        </svg>
                        NOW SHOP - NEW ARRIVAL
                    </div>

                    <h1 class="text-4xl lg:text-6xl font-bold">
                        GANTUNGAN KUNCI
                        <br />
                        <span class="text-yellow-400">AKRILIK CUSTOM</span>
                    </h1>

                    <p class="text-xl lg:text-2xl">Custom Desain Sesukamu!</p>

                    <div class="flex flex-wrap gap-4">
                        <button
                            class="bg-white text-red-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg"
                        >
                            Pesan Sekarang
                        </button>
                        <button
                            class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-red-600 transition-all"
                        >
                            Lihat Katalog
                        </button>
                    </div>
                </div>

                <!-- Product Images -->
                <div class="relative">
                    <div class="grid grid-cols-2 gap-4 transform lg:rotate-3">
                        <div class="space-y-4">
                            <img
                                src="https://via.placeholder.com/200x200/FFB6C1/000000?text=Keychain+1"
                                alt="Keychain"
                                class="rounded-lg shadow-xl transform hover:scale-105 transition-transform"
                            />
                            <img
                                src="https://via.placeholder.com/200x200/FFE4B5/000000?text=Keychain+2"
                                alt="Keychain"
                                class="rounded-lg shadow-xl transform hover:scale-105 transition-transform"
                            />
                        </div>
                        <div class="space-y-4 mt-8">
                            <img
                                src="https://via.placeholder.com/200x200/E0E0E0/000000?text=Keychain+3"
                                alt="Keychain"
                                class="rounded-lg shadow-xl transform hover:scale-105 transition-transform"
                            />
                            <img
                                src="https://via.placeholder.com/200x200/B0E0E6/000000?text=Keychain+4"
                                alt="Keychain"
                                class="rounded-lg shadow-xl transform hover:scale-105 transition-transform"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <!-- Feature 1 -->
                <div
                    class="flex items-center space-x-4 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-red-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5 13l4 4L19 7"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            High Quality
                        </h3>
                        <p class="text-sm text-gray-600">
                            Cetak berkualitas tinggi dengan hasil memuaskan
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div
                    class="flex items-center space-x-4 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-yellow-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-yellow-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Harga Terjangkau
                        </h3>
                        <p class="text-sm text-gray-600">
                            Harga bersaing dengan kualitas terbaik
                        </p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div
                    class="flex items-center space-x-4 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-green-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Proses Cepat
                        </h3>
                        <p class="text-sm text-gray-600">
                            Pengerjaan cepat tanpa mengurangi kualitas
                        </p>
                    </div>
                </div>

                <!-- Feature 4 -->
                <div
                    class="flex items-center space-x-4 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow"
                >
                    <div class="flex-shrink-0">
                        <div
                            class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-6 h-6 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                ></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900">
                            Pengiriman Aman
                        </h3>
                        <p class="text-sm text-gray-600">
                            Dikemas dengan aman sampai tujuan
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Kategori Produk
                </h2>
                <p class="text-gray-600 text-lg">
                    Pilih kategori yang sesuai dengan kebutuhan Anda
                </p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- Category 1 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-orange-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Flash Sale</h3>
                    </div>
                </div>

                <!-- Category 2 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-blue-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Label</h3>
                    </div>
                </div>

                <!-- Category 3 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-green-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm3 5a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1zm0 3a1 1 0 011-1h4a1 1 0 110 2H8a1 1 0 01-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Merchandise</h3>
                    </div>
                </div>

                <!-- Category 4 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-purple-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M11 17a1 1 0 001.447.894l4-2A1 1 0 0017 15V9.236a1 1 0 00-1.447-.894l-4 2a1 1 0 00-.553.894V17zM15.211 6.276a1 1 0 000-1.788l-4.764-2.382a1 1 0 00-.894 0L4.789 4.488a1 1 0 000 1.788l4.764 2.382a1 1 0 00.894 0l4.764-2.382zM4.447 8.342A1 1 0 003 9.236V15a1 1 0 00.553.894l4 2A1 1 0 009 17v-5.764a1 1 0 00-.553-.894l-4-2z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Cetak Stiker</h3>
                    </div>
                </div>

                <!-- Category 5 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-pink-400 to-pink-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-pink-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M10 2a4 4 0 00-4 4v1H5a1 1 0 00-.994.89l-1 9A1 1 0 004 18h12a1 1 0 00.994-1.11l-1-9A1 1 0 0015 7h-1V6a4 4 0 00-4-4zm2 5V6a2 2 0 10-4 0v1h4zm-6 3a1 1 0 112 0 1 1 0 01-2 0zm7-1a1 1 0 100 2 1 1 0 000-2z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Stationery</h3>
                    </div>
                </div>

                <!-- Category 6 -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl"
                    >
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center"
                        >
                            <svg
                                class="w-8 h-8 text-red-500"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 3.5a1.5 1.5 0 013 0V4a1 1 0 001 1h3a1 1 0 011 1v3a1 1 0 01-1 1h-.5a1.5 1.5 0 000 3h.5a1 1 0 011 1v3a1 1 0 01-1 1h-3a1 1 0 01-1-1v-.5a1.5 1.5 0 00-3 0v.5a1 1 0 01-1 1H6a1 1 0 01-1-1v-3a1 1 0 00-1-1h-.5a1.5 1.5 0 010-3H4a1 1 0 001-1V6a1 1 0 011-1h3a1 1 0 001-1v-.5z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Media Promosi</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section with Filter -->
    <section class="py-16 bg-white" x-data="productFilter()">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">
                    Produk Terlaris Kami
                </h2>
                <p class="text-gray-600 text-lg">
                    Temukan produk berkualitas dengan harga terjangkau
                </p>
            </div>

            <!-- Category Filter Tabs -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button
                    @click="filterCategory('all')"
                    :class="activeCategory === 'all' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Semua Produk
                </button>
                <button
                    @click="filterCategory('sticker')"
                    :class="activeCategory === 'sticker' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Stiker
                </button>
                <button
                    @click="filterCategory('banner')"
                    :class="activeCategory === 'banner' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Banner
                </button>
                <button
                    @click="filterCategory('merchandise')"
                    :class="activeCategory === 'merchandise' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Merchandise
                </button>
                <button
                    @click="filterCategory('card')"
                    :class="activeCategory === 'card' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Kartu Nama
                </button>
            </div>

            <!-- Products Grid -->
            <div
                class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
            >
                <!-- Product Card Template -->
                <template
                    x-for="product in filteredProducts"
                    :key="product.id"
                >
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 overflow-hidden"
                        x-show="product.show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                    >
                        <div class="relative">
                            <img
                                :src="product.image"
                                :alt="product.name"
                                class="w-full h-48 object-cover"
                            />
                            <span
                                x-show="product.discount"
                                class="absolute top-2 left-2 bg-red-600 text-white px-3 py-1 rounded-full text-sm font-semibold"
                            >
                                <span x-text="product.discount + '%'"></span>
                                OFF
                            </span>
                            <button
                                class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition-colors"
                            >
                                <svg
                                    class="w-5 h-5 text-gray-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                                    />
                                </svg>
                            </button>
                        </div>

                        <div class="p-4">
                            <span
                                class="text-xs text-gray-500 uppercase tracking-wide"
                                x-text="product.category"
                            ></span>
                            <h3
                                class="text-lg font-semibold text-gray-900 mt-1 mb-2"
                                x-text="product.name"
                            ></h3>
                            <p
                                class="text-sm text-gray-600 mb-3"
                                x-text="product.description"
                            ></p>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span
                                        x-show="product.originalPrice"
                                        class="text-sm text-gray-500 line-through"
                                        x-text="'Rp ' + product.originalPrice.toLocaleString('id-ID')"
                                    ></span>
                                    <span
                                        class="text-lg font-bold text-red-600"
                                        x-text="'Rp ' + product.price.toLocaleString('id-ID')"
                                    ></span>
                                </div>
                                <div class="flex items-center text-yellow-400">
                                    <svg
                                        class="w-4 h-4 fill-current"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"
                                        />
                                    </svg>
                                    <span
                                        class="text-sm text-gray-600 ml-1"
                                        x-text="product.rating"
                                    ></span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button
                                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium"
                                >
                                    <svg
                                        class="w-4 h-4 inline mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                        />
                                    </svg>
                                    Beli
                                </button>
                                <button
                                    class="bg-gray-200 text-gray-700 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                                >
                                    <svg
                                        class="w-5 h-5"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                        />
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>

            <!-- Load More Button -->
            <div class="text-center mt-12">
                <button
                    class="bg-gray-200 text-gray-700 px-8 py-3 rounded-lg hover:bg-gray-300 transition-colors font-medium"
                >
                    Lihat Lebih Banyak
                </button>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16 bg-gradient-to-r from-red-600 to-red-700">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">
                Siap untuk Membuat Produk Custom Anda?
            </h2>
            <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
                Wujudkan ide kreatif Anda menjadi produk berkualitas tinggi
                dengan harga terjangkau
            </p>
            <div class="flex flex-wrap gap-4 justify-center">
                <button
                    class="bg-white text-red-600 px-8 py-4 rounded-lg font-bold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg"
                >
                    Mulai Custom Sekarang
                </button>
                <button
                    class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-red-600 transition-all"
                >
                    Konsultasi Gratis
                </button>
            </div>
        </div>
    </section>

    <script>
        function productFilter() {
            return {
                activeCategory: 'all',
                products: [
                    // Sticker Products
                    {
                        id: 1,
                        name: 'Stiker Vinyl Glossy',
                        category: 'sticker',
                        categoryDisplay: 'Stiker',
                        price: 15000,
                        originalPrice: 20000,
                        discount: 25,
                        image: 'https://via.placeholder.com/300x300/FF6B6B/ffffff?text=Stiker+Vinyl',
                        description: 'Stiker tahan air berkualitas tinggi',
                        rating: 4.8,
                        show: true,
                    },
                    {
                        id: 2,
                        name: 'Stiker Die Cut Custom',
                        category: 'sticker',
                        categoryDisplay: 'Stiker',
                        price: 25000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/4ECDC4/ffffff?text=Die+Cut',
                        description: 'Potong sesuai bentuk desain',
                        rating: 4.9,
                        show: true,
                    },
                    {
                        id: 3,
                        name: 'Stiker Transparant',
                        category: 'sticker',
                        categoryDisplay: 'Stiker',
                        price: 18000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/45B7D1/ffffff?text=Transparant',
                        description: 'Stiker bening berkualitas',
                        rating: 4.7,
                        show: true,
                    },

                    // Banner Products
                    {
                        id: 4,
                        name: 'X-Banner 60x160cm',
                        category: 'banner',
                        categoryDisplay: 'Banner',
                        price: 125000,
                        originalPrice: 150000,
                        discount: 17,
                        image: 'https://via.placeholder.com/300x300/F7DC6F/000000?text=X-Banner',
                        description: 'Banner portable untuk event',
                        rating: 4.6,
                        show: true,
                    },
                    {
                        id: 5,
                        name: 'Roll Up Banner',
                        category: 'banner',
                        categoryDisplay: 'Banner',
                        price: 275000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/BB8FCE/ffffff?text=Roll+Up',
                        description: 'Banner premium dengan stand',
                        rating: 4.8,
                        show: true,
                    },
                    {
                        id: 6,
                        name: 'Spanduk Flexi 3x1m',
                        category: 'banner',
                        categoryDisplay: 'Banner',
                        price: 90000,
                        originalPrice: 120000,
                        discount: 25,
                        image: 'https://via.placeholder.com/300x300/85C1E2/ffffff?text=Spanduk',
                        description: 'Spanduk outdoor tahan cuaca',
                        rating: 4.5,
                        show: true,
                    },

                    // Merchandise Products
                    {
                        id: 7,
                        name: 'Gantungan Kunci Akrilik',
                        category: 'merchandise',
                        categoryDisplay: 'Merchandise',
                        price: 12000,
                        originalPrice: 15000,
                        discount: 20,
                        image: 'https://via.placeholder.com/300x300/F8B739/000000?text=Keychain',
                        description: 'Custom design double side',
                        rating: 4.9,
                        show: true,
                    },
                    {
                        id: 8,
                        name: 'Tumbler Custom 500ml',
                        category: 'merchandise',
                        categoryDisplay: 'Merchandise',
                        price: 65000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/58D68D/ffffff?text=Tumbler',
                        description: 'Tumbler stainless berkualitas',
                        rating: 4.7,
                        show: true,
                    },
                    {
                        id: 9,
                        name: 'Pin Button 5.8cm',
                        category: 'merchandise',
                        categoryDisplay: 'Merchandise',
                        price: 5000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/EC7063/ffffff?text=Pin',
                        description: 'Pin custom design unik',
                        rating: 4.8,
                        show: true,
                    },

                    // Card Products
                    {
                        id: 10,
                        name: 'Kartu Nama Premium',
                        category: 'card',
                        categoryDisplay: 'Kartu Nama',
                        price: 75000,
                        originalPrice: 100000,
                        discount: 25,
                        image: 'https://via.placeholder.com/300x300/AF7AC5/ffffff?text=Business+Card',
                        description: 'Art carton 260gsm laminasi',
                        rating: 4.9,
                        show: true,
                    },
                    {
                        id: 11,
                        name: 'ID Card PVC',
                        category: 'card',
                        categoryDisplay: 'Kartu Nama',
                        price: 15000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/5DADE2/ffffff?text=ID+Card',
                        description: 'Kartu identitas tahan lama',
                        rating: 4.6,
                        show: true,
                    },
                    {
                        id: 12,
                        name: 'Member Card',
                        category: 'card',
                        categoryDisplay: 'Kartu Nama',
                        price: 8000,
                        originalPrice: 10000,
                        discount: 20,
                        image: 'https://via.placeholder.com/300x300/48C9B0/ffffff?text=Member+Card',
                        description: 'Kartu member dengan barcode',
                        rating: 4.7,
                        show: true,
                    },
                ],
                get filteredProducts() {
                    return this.products;
                },
                filterCategory(category) {
                    this.activeCategory = category;
                    this.products.forEach((product) => {
                        if (category === 'all') {
                            product.show = true;
                        } else {
                            product.show = product.category === category;
                        }
                    });
                },
            };
        }
    </script>
@endsection
