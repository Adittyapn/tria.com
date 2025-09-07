@extends('layouts.app')

@section('title', 'Semua Produk - Tria Digital')
@section('description', 'Jelajahi koleksi lengkap produk digital printing kami. Stiker, banner, merchandise, dan layanan cetak custom berkualitas tinggi.')

@section('content')
    <div class="bg-gray-50">
        <!-- Hero Section -->
        <section class="relative bg-gradient-to-r from-blue-600 to-blue-700 text-white py-12">
            @php
                $svg = "<svg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'><g fill='none' fill-rule='evenodd'><g fill='#ffffff' fill-opacity='0.4'><path d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/></g></g></svg>";
                $bgPattern = 'data:image/svg+xml,' . rawurlencode($svg);
            @endphp

            <div class="absolute inset-0 opacity-10" style="background-image: url('{{ $bgPattern }}')"></div>

            <div class="container mx-auto px-4">
                <div class="text-center">
                    <h1 class="text-4xl lg:text-5xl font-bold mb-4">Koleksi Produk Kami</h1>
                    <p class="text-xl text-blue-100 max-w-2xl mx-auto">
                        Temukan berbagai produk digital printing berkualitas tinggi untuk semua kebutuhan bisnis dan
                        personal Anda
                    </p>
                    @if (request('search'))
                        <p class="text-lg text-blue-200 mt-4">
                            Hasil pencarian untuk: "
                            <span class="font-semibold">{{ request('search') }}</span>
                            "
                        </p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Products Section -->
        <section class="py-12">
            <div class="container mx-auto px-4">
                <!-- Search and Filter Header -->
                <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                    <form
                        method="GET"
                        action="{{ route('products.index') }}"
                        class="flex flex-col lg:flex-row gap-4 items-center justify-between"
                    >
                        <!-- Search Bar -->
                        <div class="relative flex-1 max-w-md">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Cari produk..."
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
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
                            <!-- Category Filter -->
                            <select
                                name="category"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="">Semua Kategori</option>
                                @foreach ($categories as $category)
                                    <option
                                        value="{{ $category->slug }}"
                                        {{ request('category') === $category->slug ? 'selected' : '' }}
                                    >
                                        {{ $category->name }} ({{ $category->products_count }})
                                    </option>
                                @endforeach
                            </select>

                            <!-- Sort Dropdown -->
                            <select
                                name="sort"
                                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            >
                                <option value="newest" {{ request('sort') === 'newest' ? 'selected' : '' }}>
                                    Terbaru
                                </option>
                                <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>
                                    Nama A-Z
                                </option>
                                <option value="price_low" {{ request('sort') === 'price_low' ? 'selected' : '' }}>
                                    Harga Terendah
                                </option>
                                <option value="price_high" {{ request('sort') === 'price_high' ? 'selected' : '' }}>
                                    Harga Tertinggi
                                </option>
                                <option value="featured" {{ request('sort') === 'featured' ? 'selected' : '' }}>
                                    Produk Unggulan
                                </option>
                            </select>

                            <button
                                type="submit"
                                class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
                            >
                                Filter
                            </button>
                        </div>
                    </form>

                    @if (request('search') || request('category') || request('sort'))
                        <div class="mt-4 pt-4 border-t border-gray-200">
                            <div class="flex items-center gap-2">
                                <span class="text-sm text-gray-600">Filter aktif:</span>
                                @if (request('search'))
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                        Pencarian: {{ request('search') }}
                                    </span>
                                @endif

                                @if (request('category'))
                                    @php
                                        $activeCategory = $categories->where('slug', request('category'))->first();
                                    @endphp

                                    @if ($activeCategory)
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                            Kategori: {{ $activeCategory->name }}
                                        </span>
                                    @endif
                                @endif

                                @if (request('sort') && request('sort') !== 'newest')
                                    <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs">
                                        Urutan: {{ ucfirst(str_replace('_', ' ', request('sort'))) }}
                                    </span>
                                @endif

                                <a
                                    href="{{ route('products.index') }}"
                                    class="text-blue-600 hover:text-blue-800 text-sm ml-2"
                                >
                                    Hapus semua filter
                                </a>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Results Count -->
                <div class="flex justify-between items-center mb-6">
                    <p class="text-gray-600">
                        Menampilkan
                        <span class="font-semibold">{{ $products->count() }}</span>
                        dari
                        <span class="font-semibold">{{ $products->total() }}</span>
                        produk
                    </p>
                </div>

                @if ($products->count() > 0)
                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        @foreach ($products as $product)
                            <a href="{{ route('products.show', $product->slug) }}" class="block">
                                <div
                                    class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden border border-gray-100 cursor-pointer"
                                >
                                    <div class="relative">
                                        <img
                                            src="{{ $product->featured_image ? asset('storage/' . $product->featured_image) : asset('images/default-product.png') }}"
                                            alt="{{ $product->name }}"
                                            class="w-full h-48 object-cover"
                                            loading="lazy"
                                            onerror="this.src='{{ asset('images/default-product.png') }}'"
                                        />

                                        <!-- Sale Badge -->
                                        @if ($product->discount_percentage)
                                            <div class="absolute top-3 left-3">
                                                <span
                                                    class="bg-orange-500 text-white px-2 py-1 rounded-md text-xs font-medium"
                                                >
                                                    SALE
                                                </span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="p-4">
                                        <!-- Category Badge -->
                                        <div class="mb-2">
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800"
                                            >
                                                {{ strtoupper($product->category->name ?? 'MEDIA PROMOSI') }}
                                            </span>
                                        </div>

                                        <!-- Product Title -->
                                        <h3 class="font-semibold text-gray-900 text-lg mb-2 line-clamp-1">
                                            {{ $product->name }}
                                        </h3>

                                        <!-- Product Description -->
                                        <p class="text-sm text-gray-600 mb-3 line-clamp-1">
                                            {{ $product->short_description ?? 'Premium Quality' }}
                                        </p>

                                        <!-- Rating -->
                                        <div class="flex items-center mb-3">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                                <span class="ml-1 text-sm font-medium text-gray-900">4.9</span>
                                            </div>
                                        </div>

                                        <!-- Price -->
                                        <div class="flex items-center justify-between">
                                            <div class="flex flex-col">
                                                <div class="flex items-center space-x-2">
                                                    <span class="text-xl font-bold text-gray-900">
                                                        Rp {{ number_format($product->final_price, 0, ',', '.') }}
                                                    </span>
                                                    @if ($product->promo_price)
                                                        <span class="text-sm text-gray-500 line-through">
                                                            Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="flex justify-center mt-12">
                        {{ $products->appends(request()->query())->links() }}
                    </div>
                @else
                    <!-- No Results -->
                    <div class="text-center py-12">
                        <svg
                            class="mx-auto h-24 w-24 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                            />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900">Tidak ada produk ditemukan</h3>

                        @if (request('search'))
                            <p class="mt-2 text-gray-500">
                                Tidak ada produk yang cocok dengan pencarian "{{ request('search') }}".
                            </p>
                        @else
                            <p class="mt-2 text-gray-500">Coba ubah filter atau kata kunci pencarian Anda.</p>
                        @endif
                        <a
                            href="{{ route('products.index') }}"
                            class="mt-4 inline-block bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold transition-colors"
                        >
                            Lihat Semua Produk
                        </a>
                    </div>
                @endif
            </div>
        </section>
    </div>
@endsection
