@extends("layouts.app")

@section("title", "Semua Produk - Tria Digital")
@section("description", "Jelajahi koleksi lengkap produk digital printing kami. Stiker, banner, merchandise, dan layanan cetak custom berkualitas tinggi.")

@section("content")
    <div class="bg-gray-50">
        <!-- Breadcrumb -->
        <!-- <div class="bg-white border-b">
            <div class="container mx-auto px-4 py-4">
                <nav class="flex text-sm text-gray-600">
                    <a href="{{ route("home") }}" class="hover:text-red-600 transition-colors">Home</a>
                    <span class="mx-2">></span>
                    <span class="text-gray-900">Semua Produk</span>
                </nav>
            </div>
        </div> -->

        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-red-600 to-red-700 text-white py-12">
            @php
                $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
                $bgPattern = "data:image/svg+xml," . rawurlencode($svg);
            @endphp

            <div class="absolute inset-0 opacity-10" style="background-image: url('{{ $bgPattern }}')"></div>

            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">Koleksi Produk Kami</h1>
                    <p class="text-xl text-red-100 max-w-2xl mx-auto">
                        Temukan berbagai produk digital printing berkualitas tinggi untuk semua kebutuhan bisnis dan
                        personal Anda
                    </p>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section
            class="py-12"
            x-data="{
                searchQuery: '',
                sortBy: 'newest',
                activeCategory: 'all',
                priceRange: 'all',
                showFilters: false,
                products: [
                    {
                        id: 1,
                        name: 'Banner Spanduk',
                        slug: 'spanduk-banner-flexi-china',
                        category: 'sticker',
                        categoryDisplay: 'Stiker',
                        price: 25000,
                        originalPrice: 35000,
                        discount: 29,
                        image: 'https://via.placeholder.com/300x300/ff6b6b/ffffff?text=Stiker+Vinyl',
                        description: 'Stiker vinyl berkualitas tinggi, tahan air dan cuaca',
                        rating: 4.8,
                        reviews: 45,
                        isNew: true,
                        isFeatured: true,
                    },
                    {
                        id: 2,
                        name: 'Spanduk Banner Flexi',
                        slug: 'spanduk-banner-flexi',
                        category: 'banner',
                        categoryDisplay: 'Banner',
                        price: 90000,
                        originalPrice: 120000,
                        discount: 25,
                        image: 'https://via.placeholder.com/300x300/4ecdc4/ffffff?text=Banner+Flexi',
                        description: 'Banner flexi premium 280gsm dengan mata ayam',
                        rating: 4.9,
                        reviews: 32,
                        isNew: false,
                        isFeatured: true,
                    },
                    {
                        id: 3,
                        name: 'X-Banner 60x160cm',
                        slug: 'x-banner-60x160',
                        category: 'banner',
                        categoryDisplay: 'Banner',
                        price: 125000,
                        originalPrice: 150000,
                        discount: 17,
                        image: 'https://via.placeholder.com/300x300/45b7d1/ffffff?text=X-Banner',
                        description: 'Banner portable untuk event dan promosi',
                        rating: 4.6,
                        reviews: 28,
                        isNew: false,
                        isFeatured: false,
                    },
                    {
                        id: 4,
                        name: 'Gantungan Kunci Akrilik',
                        slug: 'gantungan-kunci-akrilik',
                        category: 'merchandise',
                        categoryDisplay: 'Merchandise',
                        price: 15000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/96ceb4/ffffff?text=Keychain',
                        description: 'Gantungan kunci akrilik custom desain',
                        rating: 4.9,
                        reviews: 67,
                        isNew: true,
                        isFeatured: true,
                    },
                    {
                        id: 5,
                        name: 'Kartu Nama Premium',
                        slug: 'kartu-nama-premium',
                        category: 'card',
                        categoryDisplay: 'Kartu Nama',
                        price: 75000,
                        originalPrice: 100000,
                        discount: 25,
                        image: 'https://via.placeholder.com/300x300/f39c12/ffffff?text=Business+Card',
                        description: 'Kartu nama art carton dengan laminasi glossy',
                        rating: 4.7,
                        reviews: 23,
                        isNew: false,
                        isFeatured: false,
                    },
                    {
                        id: 6,
                        name: 'Stiker Transparan',
                        slug: 'stiker-transparan',
                        category: 'sticker',
                        categoryDisplay: 'Stiker',
                        price: 18000,
                        originalPrice: null,
                        discount: 0,
                        image: 'https://via.placeholder.com/300x300/e74c3c/ffffff?text=Clear+Sticker',
                        description: 'Stiker bening berkualitas premium',
                        rating: 4.5,
                        reviews: 19,
                        isNew: false,
                        isFeatured: false,
                    },
                ],

                get filteredProducts() {
                    let filtered = this.products

                    // Filter by search
                    if (this.searchQuery) {
                        filtered = filtered.filter(
                            (product) =>
                                product.name
                                    .toLowerCase()
                                    .includes(this.searchQuery.toLowerCase()) ||
                                product.description
                                    .toLowerCase()
                                    .includes(this.searchQuery.toLowerCase()),
                        )
                    }

                    // Filter by category
                    if (this.activeCategory !== 'all') {
                        filtered = filtered.filter(
                            (product) => product.category === this.activeCategory,
                        )
                    }

                    // Filter by price range
                    if (this.priceRange !== 'all') {
                        filtered = filtered.filter((product) => {
                            switch (this.priceRange) {
                                case 'under-50k':
                                    return product.price < 50000
                                case '50k-100k':
                                    return product.price >= 50000 && product.price <= 100000
                                case 'over-100k':
                                    return product.price > 100000
                                default:
                                    return true
                            }
                        })
                    }

                    // Sort products
                    switch (this.sortBy) {
                        case 'name':
                            return filtered.sort((a, b) => a.name.localeCompare(b.name))
                        case 'price-low':
                            return filtered.sort((a, b) => a.price - b.price)
                        case 'price-high':
                            return filtered.sort((a, b) => b.price - a.price)
                        case 'rating':
                            return filtered.sort((a, b) => b.rating - a.rating)
                        case 'newest':
                        default:
                            return filtered.sort((a, b) => b.id - a.id)
                    }
                },
            }"
        >
            <div class="container mx-auto px-4">
                <!-- Search and Filter Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <div class="flex flex-col lg:flex-row gap-4 items-center justify-between">
                        <!-- Search Bar -->
                        <div class="relative flex-1 max-w-md">
                            <input
                                type="text"
                                x-model="searchQuery"
                                placeholder="Cari produk..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            />
                            <svg
                                class="absolute left-3 top-3.5 w-5 h-5 text-gray-400"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </div>

                        <!-- Sort and Filter Controls -->
                        <div class="flex items-center space-x-4">
                            <!-- Sort Dropdown -->
                            <select
                                x-model="sortBy"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                            >
                                <option value="newest">Terbaru</option>
                                <option value="name">Nama A-Z</option>
                                <option value="price-low">Harga Terendah</option>
                                <option value="price-high">Harga Tertinggi</option>
                                <option value="rating">Rating Tertinggi</option>
                            </select>

                            <!-- Filter Toggle -->
                            <button
                                @click="showFilters = !showFilters"
                                class="flex items-center space-x-2 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"
                                    />
                                </svg>
                                <span>Filter</span>
                            </button>
                        </div>
                    </div>

                    <!-- Filter Panel -->
                    <div x-show="showFilters" x-collapse class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Category Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Kategori</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="activeCategory"
                                            value="all"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Semua Kategori</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="activeCategory"
                                            value="sticker"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Stiker</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="activeCategory"
                                            value="banner"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Banner</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="activeCategory"
                                            value="merchandise"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Merchandise</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="activeCategory"
                                            value="card"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Kartu Nama</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Price Range Filter -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Rentang Harga</label>
                                <div class="space-y-2">
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="priceRange"
                                            value="all"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Semua Harga</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="priceRange"
                                            value="under-50k"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Di bawah Rp 50.000</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="priceRange"
                                            value="50k-100k"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Rp 50.000 - Rp 100.000</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input
                                            type="radio"
                                            x-model="priceRange"
                                            value="over-100k"
                                            class="text-red-600 focus:ring-red-500"
                                        />
                                        <span class="ml-2 text-sm text-gray-700">Di atas Rp 100.000</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Quick Filters -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Filter Cepat</label>
                                <div class="flex flex-wrap gap-2">
                                    <button
                                        @click="activeCategory = 'all'; priceRange = 'all'"
                                        class="px-3 py-1 text-xs bg-gray-100 text-gray-700 rounded-full hover:bg-gray-200 transition-colors"
                                    >
                                        Reset Filter
                                    </button>
                                    <button
                                        @click="sortBy = 'rating'"
                                        class="px-3 py-1 text-xs bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition-colors"
                                    >
                                        Rating Tinggi
                                    </button>
                                    <button
                                        @click="priceRange = 'under-50k'"
                                        class="px-3 py-1 text-xs bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition-colors"
                                    >
                                        Budget Friendly
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Results Count -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">
                        Menampilkan
                        <span x-text="filteredProducts.length" class="font-semibold"></span>
                        produk
                    </p>

                    <!-- View Toggle -->
                    <div class="flex items-center space-x-2" x-data="{ viewMode: 'grid' }">
                        <button
                            @click="viewMode = 'grid'"
                            :class="viewMode === 'grid' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="p-2 rounded-lg transition-colors"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                                />
                            </svg>
                        </button>
                        <button
                            @click="viewMode = 'list'"
                            :class="viewMode === 'list' ? 'bg-red-600 text-white' : 'bg-gray-200 text-gray-700'"
                            class="p-2 rounded-lg transition-colors"
                        >
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"
                                />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div
                            class="bg-white rounded-lg shadow-md hover:shadow-xl transition-all transform hover:-translate-y-1 overflow-hidden group"
                        >
                            <div class="relative">
                                <img
                                    :src="product.image"
                                    :alt="product.name"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                />

                                <!-- Badges -->
                                <div class="absolute top-2 left-2 space-y-1">
                                    <span
                                        x-show="product.discount > 0"
                                        class="bg-red-600 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                    >
                                        <span x-text="product.discount + '%'"></span>
                                        OFF
                                    </span>
                                    <span
                                        x-show="product.isNew"
                                        class="bg-green-600 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                    >
                                        NEW
                                    </span>
                                    <span
                                        x-show="product.isFeatured"
                                        class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold"
                                    >
                                        ⭐ FEATURED
                                    </span>
                                </div>

                                <!-- Wishlist Button -->
                                <button
                                    class="absolute top-2 right-2 bg-white p-2 rounded-full shadow-md hover:bg-gray-100 transition-colors opacity-0 group-hover:opacity-100"
                                >
                                    <svg
                                        class="w-4 h-4 text-gray-600"
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
                                <div class="flex items-center justify-between mb-2">
                                    <span
                                        class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded"
                                        x-text="product.categoryDisplay"
                                    ></span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <span class="text-sm text-gray-600" x-text="product.rating"></span>
                                        <span class="text-xs text-gray-500">
                                            (
                                            <span x-text="product.reviews"></span>
                                            )
                                        </span>
                                    </div>
                                </div>

                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2" x-text="product.name"></h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2" x-text="product.description"></p>

                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-lg font-bold text-red-600"
                                            x-text="'Rp ' + product.price.toLocaleString('id-ID')"
                                        ></span>
                                        <span
                                            x-show="product.originalPrice"
                                            class="text-sm text-gray-500 line-through"
                                            x-text="
                                                product.originalPrice
                                                    ? 'Rp ' + product.originalPrice.toLocaleString('id-ID')
                                                    : ''
                                            "
                                        ></span>
                                    </div>
                                    <a
                                        :href="'/products/' + product.slug"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                                    >
                                        Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- No Results -->
                <div x-show="filteredProducts.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                        />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk ditemukan</h3>
                    <p class="mt-2 text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <button
                        @click="searchQuery = ''; activeCategory = 'all'; priceRange = 'all'"
                        class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>

                <!-- Pagination (Static for demo) -->
                <div class="flex justify-center mt-12">
                    <nav class="flex items-center space-x-2">
                        <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            Previous
                        </button>
                        <button class="px-3 py-2 text-sm bg-red-600 text-white rounded">1</button>
                        <button class="px-3 py-2 text-sm text-gray-700 hover:text-gray-900 transition-colors">2</button>
                        <button class="px-3 py-2 text-sm text-gray-700 hover:text-gray-900 transition-colors">3</button>
                        <button class="px-3 py-2 text-sm text-gray-500 hover:text-gray-700 transition-colors">
                            Next
                        </button>
                    </nav>
                </div>
            </div>
        </section>
    </div>
@endsection
