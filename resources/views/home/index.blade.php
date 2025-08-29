@extends('layouts.app')

@section('content')
    <!-- Hero Carousel Section -->
    <section
        class="relative h-screen overflow-hidden"
        x-data="heroCarousel()"
        @mousemove="showControls = true; resetControlsTimer()"
        @mouseleave="showControls = false"
    >
        <!-- Slides Container -->
        <div class="relative h-full">
            <template x-for="(slide, index) in slides" :key="index">
                <div
                    x-show="currentSlide === index"
                    x-transition:enter="transition transform ease-out duration-500"
                    x-transition:enter-start="translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave="transition transform ease-in duration-500"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="-translate-x-full"
                    :class="[
                        'absolute inset-0 h-full w-full transition-transform duration-500',
                        direction === 'prev' ? 'slide-prev' : 'slide-next',
                        `bg-gradient-to-br ${slide.bg}`
                    ]"
                >
                    <div
                        class="absolute inset-0 opacity-10"
                        style="
                            background-image: url('data:image/svg+xml,%3Csvg width%3D%2760%27 height%3D%2760%27 viewBox%3D%270 0 60 60%27 xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%3E%3Cg fill%3D%27none%27 fill-rule%3D%27evenodd%27%3E%3Cg fill%3D%27%23ffffff%27 fill-opacity%3D%270.4%27%3E%3Cpath d%3D%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');
                        "
                    ></div>

                    <div class="container mx-auto px-4 h-full">
                        <div class="flex flex-col lg:flex-row items-center justify-between h-full py-16 lg:py-0">
                            <div class="text-white z-10 space-y-8 max-w-2xl lg:w-1/2 text-center lg:text-left">
                                <div class="space-y-4">
                                    <p
                                        x-text="slide.subtitle"
                                        class="text-sm md:text-lg font-medium text-white/90 tracking-wider uppercase"
                                    ></p>
                                    <h1
                                        x-text="slide.title"
                                        class="text-3xl md:text-5xl lg:text-6xl font-bold leading-tight"
                                    ></h1>
                                </div>
                                <p
                                    x-text="slide.description"
                                    class="text-base md:text-xl text-white/90 leading-relaxed"
                                ></p>
                                <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                                    <button
                                        x-text="slide.cta"
                                        class="bg-white text-gray-900 px-6 py-3 md:px-8 md:py-4 rounded-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg text-sm md:text-base"
                                    ></button>
                                    <button
                                        class="border-2 border-white text-white px-6 py-3 md:px-8 md:py-4 rounded-lg font-semibold hover:bg-white hover:text-gray-900 transition-all text-sm md:text-base"
                                    >
                                        Lihat Portfolio
                                    </button>
                                </div>
                            </div>
                            <div class="relative z-10 lg:w-1/2 mt-8 lg:mt-0">
                                <div class="relative">
                                    <img
                                        :src="slide.image"
                                        :alt="slide.title"
                                        class="rounded-2xl shadow-2xl transform hover:scale-105 transition-transform duration-300 w-full max-w-lg mx-auto object-cover object-center h-[300px] md:h-[400px] lg:h-[500px]"
                                    />

                                    <!-- Badge -->
                                    <div
                                        class="absolute -top-4 -right-4 bg-white/20 backdrop-blur-sm rounded-xl p-3 md:p-4"
                                    >
                                        <div class="flex items-center space-x-2 text-white">
                                            <svg class="w-4 h-4 md:w-5 md:h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                />
                                            </svg>
                                            <span class="font-semibold text-sm md:text-base">4.9</span>
                                        </div>
                                    </div>

                                    <!-- Image Overlay -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent rounded-2xl"
                                    ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Navigation -->
        <button
            @click="prevSlide()"
            x-show="showControls"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-all z-20 group"
        >
            <svg
                class="w-6 h-6 transform group-hover:scale-110 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
        </button>
        <button
            @click="nextSlide()"
            x-show="showControls"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-white/20 backdrop-blur-sm text-white p-3 rounded-full hover:bg-white/30 transition-all z-20 group"
        >
            <svg
                class="w-6 h-6 transform group-hover:scale-110 transition-transform"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        </button>

        <!-- Dots Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-3 z-20">
            <template x-for="(slide, index) in slides" :key="index">
                <button
                    @click="goToSlide(index)"
                    class="w-3 h-3 rounded-full transition-all duration-300"
                    :class="currentSlide === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/75'"
                ></button>
            </template>
        </div>

        <!-- Progress Bar -->
        <div class="absolute bottom-0 left-0 w-full h-1 bg-white/20 z-20">
            <div
                class="h-full bg-white transition-all duration-500 ease-linear"
                :style="`width: ${((currentSlide + 1) / slides.length) * 100}%`"
            ></div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="py-8 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                @php
                    $features = [
                        ['icon' => 'M5 13l4 4L19 7', 'title' => 'High Quality', 'desc' => 'Cetak berkualitas tinggi dengan hasil memuaskan', 'color' => 'red'],
                        ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Harga Terjangkau', 'desc' => 'Harga bersaing dengan kualitas terbaik', 'color' => 'yellow'],
                        ['icon' => 'M13 10V3L4 14h7v7l9-11h-7z', 'title' => 'Proses Cepat', 'desc' => 'Pengerjaan cepat tanpa mengurangi kualitas', 'color' => 'green'],
                        ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Pengiriman Aman', 'desc' => 'Dikemas dengan aman sampai tujuan', 'color' => 'blue'],
                    ];
                @endphp

                @foreach ($features as $feature)
                    <div
                        class="flex items-center space-x-4 bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow"
                    >
                        <div
                            class="w-12 h-12 bg-{{ $feature['color'] }}-100 rounded-full flex items-center justify-center flex-shrink-0"
                        >
                            <svg
                                class="w-6 h-6 text-{{ $feature['color'] }}-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="{{ $feature['icon'] }}"
                                />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">{{ $feature['title'] }}</h3>
                            <p class="text-sm text-gray-600">{{ $feature['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Kategori Produk</h2>
                <p class="text-gray-600 text-lg">Pilih kategori yang sesuai dengan kebutuhan Anda</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                <!-- Flash Sale -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-orange-400 to-orange-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-orange-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Flash Sale</h3>
                    </div>
                </div>

                <!-- Label -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-blue-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path d="M3 7V3h4l12 12-4 4L3 7z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Label</h3>
                    </div>
                </div>

                <!-- Merchandise -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-green-400 to-green-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-green-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path d="M20 6H4v12h16V6zM4 10h16" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Merchandise</h3>
                    </div>
                </div>

                <!-- Cetak Stiker -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-purple-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path d="M4 5a2 2 0 0 1 2-2h7l7 7v9a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Cetak Stiker</h3>
                    </div>
                </div>

                <!-- Stationery -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-pink-400 to-pink-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-pink-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5l4 4-12 12H4v-4l12-12z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Stationery</h3>
                    </div>
                </div>

                <!-- Media Promosi -->
                <div class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-red-400 to-red-500 rounded-xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-xl h-full"
                    >
                        <div class="w-16 h-16 mx-auto mb-4 bg-white rounded-full flex items-center justify-center">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                class="w-8 h-8 text-red-500"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M21 15a2 2 0 0 1-2 2h-4l-4 4v-4H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v10z"
                                />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold">Media Promosi</h3>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="py-16 bg-white" x-data="productFilter()">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">Produk Terlaris Kami</h2>
                <p class="text-gray-600 text-lg">Temukan produk berkualitas dengan harga terjangkau</p>
            </div>

            <!-- Category Filter -->
            <div class="flex flex-wrap justify-center gap-3 mb-12">
                <button
                    @click="filterCategory('all')"
                    :class="activeCategory === 'all' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                    class="px-6 py-3 rounded-full font-medium transition-all"
                >
                    Semua Produk
                </button>
                @foreach ($categories as $category)
                    <button
                        @click="filterCategory('{{ $category->id }}')"
                        :class="activeCategory === '{{ $category->id }}' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                        class="px-6 py-3 rounded-full font-medium transition-all"
                    >
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>

            <!-- Products Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <template x-for="product in filteredProducts" :key="product.id">
                    <div
                        class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 overflow-hidden"
                        x-show="product.show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-90"
                        x-transition:enter-end="opacity-100 transform scale-100"
                    >
                        <div class="relative">
                            <img :src="product.image_url" :alt="product.name" class="w-full h-48 object-cover" />

                            <button
                                class="absolute bottom-2 right-2 bg-white/80 p-2 rounded-full shadow-md hover:bg-white transition-colors"
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
                                x-text="product.category_name"
                            ></span>
                            <h3 class="text-lg font-semibold text-gray-900 mt-1 mb-2" x-text="product.name"></h3>
                            <p class="text-sm text-gray-600 mb-3" x-text="product.short_description"></p>

                            <div class="flex items-center justify-between mb-3">
                                <div>
                                    <span
                                        x-show="product.sale_price"
                                        class="text-sm text-gray-500 line-through"
                                        x-text="'Rp ' + parseInt(product.price).toLocaleString('id-ID')"
                                    ></span>
                                    <span
                                        class="text-lg font-bold text-red-600"
                                        x-text="'Rp ' + parseInt(product.price).toLocaleString('id-ID')"
                                    ></span>
                                </div>
                                <div class="flex items-center">
                                    <span
                                        x-show="product.stock_quantity > 0"
                                        class="text-sm px-2 py-1 bg-green-100 text-green-800 rounded"
                                    >
                                        Stok:
                                        <span x-text="product.stock_quantity"></span>
                                    </span>
                                    <span
                                        x-show="product.stock_quantity <= 0"
                                        class="text-sm px-2 py-1 bg-red-100 text-red-800 rounded"
                                    >
                                        Habis
                                    </span>
                                </div>
                            </div>

                            <div class="flex gap-2">
                                <button
                                    class="flex-1 bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition-colors font-medium disabled:bg-gray-400"
                                    :disabled="product.stock_quantity <= 0"
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
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17"
                                        />
                                    </svg>
                                    <span x-text="product.stock_quantity > 0 ? 'Beli' : 'Habis'"></span>
                                </button>
                                <button
                                    class="bg-gray-200 text-gray-700 p-2 rounded-lg hover:bg-gray-300 transition-colors"
                                    @click="window.open(`/product/${product.slug}`, '_blank')"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <div class="text-center mt-12" x-show="hasMoreProducts">
                <button
                    @click="loadMoreProducts()"
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
            <h2 class="text-3xl lg:text-4xl font-bold text-white mb-6">Siap untuk Membuat Produk Custom Anda?</h2>
            <p class="text-xl text-red-100 mb-8 max-w-2xl mx-auto">
                Wujudkan ide kreatif Anda menjadi produk berkualitas tinggi dengan harga terjangkau
            </p>
            <button
                class="bg-transparent border-2 border-white text-white px-8 py-4 rounded-lg font-bold hover:bg-white hover:text-red-600 transition-all"
            >
                Konsultasi Gratis
            </button>
        </div>
    </section>

    <style>
        .slide-prev.translate-x-full {
            transform: translateX(-100%);
        }
        .slide-next.translate-x-full {
            transform: translateX(100%);
        }
        .slide-prev.-translate-x-full {
            transform: translateX(100%);
        }
        .slide-next.-translate-x-full {
            transform: translateX(-100%);
        }
    </style>

    <script>
        function heroCarousel() {
                return {
                    currentSlide: 0,
                    direction: 'next',
                    showControls: false,
                    controlsTimer: null,
                    resetControlsTimer() {
                        if (this.controlsTimer) clearTimeout(this.controlsTimer);
                        this.controlsTimer = setTimeout(() => {
                            this.showControls = false;
                        }, 2000); // akan hide setelah 2 detik tidak ada gerakan mouse
                    },
                    slides: [
                        {
                            title: 'Solusi Digital Printing Premium',
                            subtitle: 'Kualitas Profesional',
                            description:
                                'Wujudkan visi kreatif Anda dengan teknologi printing terkini. Kami menghadirkan hasil cetak berkualitas tinggi dengan harga yang terjangkau untuk setiap proyek Anda.',
                            image: 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=1200&q=80',
                            bg: 'from-red-600 to-red-800',
                            cta: 'Mulai Proyek',
                        },
                        {
                            title: 'Merchandise Eksklusif',
                            subtitle: 'Desain Kustom',
                            description:
                                'Ciptakan merchandise yang unik dan berkesan. Dari gantungan kunci hingga produk premium, kami siap mewujudkan ide kreatif Anda dengan kualitas terbaik.',
                            image: 'https://images.unsplash.com/photo-1607082349566-187342175e2f?auto=format&fit=crop&w=1200&q=80',
                            bg: 'from-blue-600 to-blue-800',
                            cta: 'Eksplorasi Koleksi',
                        },
                        {
                            title: 'Label & Stiker Premium',
                            subtitle: 'Branding Profesional',
                            description:
                                'Tingkatkan identitas brand Anda dengan label dan stiker berkualitas tinggi. Dibuat dengan material terbaik untuk hasil yang tahan lama dan mengesankan.',
                            image: 'https://images.unsplash.com/photo-1606676539940-12768ce0e762?auto=format&fit=crop&w=1200&q=80',
                            bg: 'from-purple-600 to-purple-800',
                            cta: 'Konsultasi Gratis',
                        },
                    ],
                    autoSlide: null,
                    init() {
                        this.startAutoSlide();
                    },
                    nextSlide() {
                        this.direction = 'next';
                        this.currentSlide = (this.currentSlide + 1) % this.slides.length;
                        this.resetAutoSlide();
                    },
                    prevSlide() {
                        this.direction = 'prev';
                        this.currentSlide = this.currentSlide === 0 ? this.slides.length - 1 : this.currentSlide - 1;
                        this.resetAutoSlide();
                    },
                    goToSlide(index) {
                        this.currentSlide = index;
                        this.resetAutoSlide();
                    },
                    startAutoSlide() {
                        this.autoSlide = setInterval(() => this.nextSlide(), 5000);
                    },
                    resetAutoSlide() {
                        clearInterval(this.autoSlide);
                        this.startAutoSlide();
                    },
                };
            }

        function productFilter() {
            return {
                activeCategory: 'all',
                products: [
                    @foreach($products as $product)
                    {
                        id: {{ $product->id }},
                        name: '{{ addslashes($product->name) }}',
                        slug: '{{ $product->slug }}',
                        category_id: {{ $product->category_id ?? 0 }},
                        category_name: '{{ addslashes($product->category->name ?? "Uncategorized") }}',
                        price: {{ $product->price }},
                        sale_price: {{ $product->sale_price ?? 'null' }},
                        final_price: {{ $product->final_price }},
                        discount_percentage: {{ $product->sale_price ? round((($product->price - $product->sale_price) / $product->price) * 100) : 0 }},
                        image_url: '{{ $product->image_url }}',
                        short_description: '{{ addslashes($product->short_description ?? "") }}',
                        stock_quantity: {{ $product->stock_quantity ?? 0 }},
                        is_featured: {{ $product->is_featured ? 'true' : 'false' }},
                        show: true
                    }@if(!$loop->last),@endif
                    @endforeach
                ],
                displayedProducts: 8,
                get filteredProducts() {
                    let filtered = this.products.filter(product =>
                        this.activeCategory === 'all' || product.category_id == this.activeCategory
                    );

                    return filtered.slice(0, this.displayedProducts).map(product => ({
                        ...product,
                        show: true
                    }));
                },
                get hasMoreProducts() {
                    let filtered = this.products.filter(product =>
                        this.activeCategory === 'all' || product.category_id == this.activeCategory
                    );
                    return filtered.length > this.displayedProducts;
                },
                filterCategory(category) {
                    this.activeCategory = category;
                    this.displayedProducts = 8;
                },
                loadMoreProducts() {
                    this.displayedProducts += 8;
                }
            };
        }
    </script>
@endsection
