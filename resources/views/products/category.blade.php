@extends("layouts.app")

@section("title", "{{ $category->name }} - Tria Digital")
@section("description", "Jelajahi koleksi {{ $category->name }} berkualitas tinggi dari Tria Digital. {{ $category->description }}")

@section("content")
    <div class="bg-gray-50">
        <!-- Breadcrumb -->
        <div class="bg-white border-b">
            <div class="container mx-auto px-4 py-4">
                <nav class="flex text-sm text-gray-600">
                    <a href="{{ route("home") }}" class="hover:text-red-600 transition-colors">Home</a>
                    <span class="mx-2">></span>
                    <a href="{{ route("products.index") }}" class="hover:text-red-600 transition-colors">Produk</a>
                    <span class="mx-2">></span>
                    <span class="text-gray-900">{{ $category->name }}</span>
                </nav>
            </div>
        </div>

        <!-- Category Hero Section -->
        <section class="bg-gradient-to-r from-red-600 to-red-700 text-white py-16">
            @php
                $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
                $bgPattern = "data:image/svg+xml," . rawurlencode($svg);
            @endphp

            <div class="absolute inset-0 opacity-10" style="background-image: url('{{ $bgPattern }}')"></div>

            <div class="container mx-auto px-4 relative">
                <div class="grid lg:grid-cols-2 gap-8 items-center">
                    <div>
                        <div
                            class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-full text-sm font-semibold mb-4"
                        >
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
                                />
                            </svg>
                            Kategori Produk
                        </div>
                        <h1 class="text-4xl lg:text-5xl font-bold mb-4">{{ $category->name }}</h1>
                        <p class="text-xl text-red-100 mb-6">{{ $category->description }}</p>

                        <div class="flex items-center space-x-6 text-red-100">
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
                                    />
                                </svg>
                                <span>{{ $products->count() }} Produk</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                    />
                                </svg>
                                <span>Rating 4.8</span>
                            </div>
                        </div>
                    </div>

                    <div class="relative">
                        <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8">
                            <h3 class="text-xl font-bold mb-4">Keunggulan {{ $category->name }}</h3>
                            <ul class="space-y-3">
                                <li class="flex items-start space-x-3">
                                    <svg
                                        class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>Kualitas material premium</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg
                                        class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>Desain custom sesuai kebutuhan</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg
                                        class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>Proses cepat dan hasil memuaskan</span>
                                </li>
                                <li class="flex items-start space-x-3">
                                    <svg
                                        class="w-5 h-5 text-green-400 mt-0.5 flex-shrink-0"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            fill-rule="evenodd"
                                            d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                    <span>Harga kompetitif</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section
            class="py-12"
            x-data="{
                searchQuery: '',
                sortBy: 'newest',
                priceRange: 'all',
                showFilters: false,

                get filteredProducts() {
                    let filtered = @json($products)

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
                            return filtered.sort(
                                (a, b) => (b.rating || 4.5) - (a.rating || 4.5),
                            )
                        case 'newest':
                        default:
                            return filtered.sort(
                                (a, b) => new Date(b.created_at) - new Date(a.created_at),
                            )
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
                                placeholder="Cari dalam {{ $category->name }}..."
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

                        <!-- Controls -->
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
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                            <!-- Quick Actions -->
                            <div>
                                <label class="block text-sm font-semibold text-gray-900 mb-3">Aksi Cepat</label>
                                <div class="space-y-3">
                                    <button
                                        @click="searchQuery = ''; priceRange = 'all'"
                                        class="w-full px-4 py-2 text-left text-sm bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors"
                                    >
                                        🔄 Reset Semua Filter
                                    </button>
                                    <button
                                        @click="sortBy = 'price-low'"
                                        class="w-full px-4 py-2 text-left text-sm bg-green-100 text-green-700 rounded-lg hover:bg-green-200 transition-colors"
                                    >
                                        💰 Harga Termurah
                                    </button>
                                    <button
                                        @click="sortBy = 'rating'"
                                        class="w-full px-4 py-2 text-left text-sm bg-yellow-100 text-yellow-700 rounded-lg hover:bg-yellow-200 transition-colors"
                                    >
                                        ⭐ Rating Terbaik
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Stats -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                        <div class="text-2xl font-bold text-red-600" x-text="filteredProducts.length"></div>
                        <div class="text-sm text-gray-600">Total Produk</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                        <div class="text-2xl font-bold text-green-600">4.8</div>
                        <div class="text-sm text-gray-600">Rating Rata-rata</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                        <div class="text-2xl font-bold text-blue-600">1-3</div>
                        <div class="text-sm text-gray-600">Hari Produksi</div>
                    </div>
                    <div class="bg-white p-4 rounded-lg shadow-sm text-center">
                        <div class="text-2xl font-bold text-purple-600">500+</div>
                        <div class="text-sm text-gray-600">Customer Puas</div>
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
                                    :src="product.image || 'https://via.placeholder.com/300x300/e2e8f0/64748b?text=' + encodeURIComponent(product.name)"
                                    :alt="product.name"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300"
                                />

                                <!-- Featured Badge -->
                                <div class="absolute top-2 left-2" x-show="product.is_featured">
                                    <span class="bg-yellow-500 text-white px-2 py-1 rounded-full text-xs font-semibold">
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
                                    <span class="text-xs font-medium text-red-600 bg-red-50 px-2 py-1 rounded">
                                        {{ $category->name }}
                                    </span>
                                    <div class="flex items-center space-x-1">
                                        <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                            />
                                        </svg>
                                        <span class="text-sm text-gray-600">4.5</span>
                                    </div>
                                </div>

                                <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2" x-text="product.name"></h3>
                                <p
                                    class="text-sm text-gray-600 mb-3 line-clamp-2"
                                    x-text="product.description || 'Produk berkualitas tinggi dengan hasil memuaskan'"
                                ></p>

                                <div class="flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span
                                            class="text-lg font-bold text-red-600"
                                            x-text="'Rp ' + (product.price || 0).toLocaleString('id-ID')"
                                        ></span>
                                        <span class="text-xs text-gray-500">per item</span>
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
                    <svg class="mx-auto h-24 w-24 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="1"
                            d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A9.971 9.971 0 0124 24c4.21 0 7.813 2.602 9.288 6.286"
                        />
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk ditemukan</h3>
                    <p class="mt-2 text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                    <button
                        @click="searchQuery = ''; priceRange = 'all'"
                        class="mt-4 bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                    >
                        Reset Filter
                    </button>
                </div>

                <!-- Call to Action -->
                <div
                    class="mt-16 bg-gradient-to-r from-red-600 to-red-700 rounded-2xl p-8 text-white text-center relative overflow-hidden"
                >
                    <div class="absolute inset-0 opacity-10" style="background-image: url('{{ $bgPattern }}')"></div>
                    <div class="relative">
                        <h3 class="text-2xl font-bold mb-4">Tidak Menemukan yang Dicari?</h3>
                        <p class="text-red-100 mb-6">
                            Konsultasikan kebutuhan custom {{ $category->name }} Anda dengan tim ahli kami
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 justify-center">
                            <a
                                href="https://wa.me/6282225362457"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-lg font-semibold transition-colors inline-flex items-center justify-center"
                            >
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                    <path
                                        d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898 1.866 1.869 2.893 4.352 2.892 6.993-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"
                                    />
                                </svg>
                                Konsultasi WhatsApp
                            </a>
                            <a
                                href="{{ route("products.index") }}"
                                class="bg-white text-red-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors"
                            >
                                Lihat Semua Produk
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
