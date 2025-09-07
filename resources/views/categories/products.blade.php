@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50" x-data="categoryPage()">
        <!-- Breadcrumb -->
        <div class="bg-white border-b hidden lg:block">
            <div class="container-custom py-4">
                <nav class="flex items-center space-x-2 text-sm text-gray-600">
                    <a href="{{ route('home') }}" class="hover:text-brand-orange transition-colors">Beranda</a>
                    <span>›</span>
                    <a href="#" class="hover:text-brand-orange transition-colors">Kategori</a>
                    <span>›</span>
                    <span class="text-navy-900 font-medium">{{ $category->name }}</span>
                </nav>
            </div>
        </div>

        <!-- Category Header -->
        <div class="bg-gradient-to-r from-brand-orange to-orange-600 text-white">
            <div class="container-custom py-8 lg:py-12">
                <div class="text-center">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold mb-3">{{ $category->name }}</h1>
                    <p class="text-white/90 text-sm lg:text-base max-w-2xl mx-auto mb-4">
                        {{ $category->description ?? 'Temukan produk ' . $category->name . ' berkualitas tinggi dengan harga terbaik' }}
                    </p>
                    <span class="inline-block px-4 py-2 bg-white/20 rounded-full text-sm font-medium">
                        {{ $products->total() }} Produk Tersedia
                    </span>
                </div>
            </div>
        </div>

        <!-- Mobile Filter Toggle -->
        <div class="lg:hidden bg-white border-b sticky top-0 z-30">
            <div class="container-custom py-3">
                <div class="flex items-center justify-between relative">
                    <button
                        @click="toggleMobileFilter()"
                        class="flex items-center space-x-2 text-gray-700 hover:text-brand-orange transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707v4.586l-4-2v-2.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"
                            />
                        </svg>
                        <span class="font-medium">Filter</span>
                        <span class="text-sm text-gray-500">({{ $products->total() }})</span>
                    </button>

                    <!-- Sort Button Container -->
                    <div class="relative">
                        <button
                            @click="toggleMobileSort()"
                            class="flex items-center space-x-2 text-gray-700 hover:text-brand-orange transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"
                                />
                            </svg>
                            <span class="font-medium">Urutkan</span>
                        </button>

                        <!-- Mobile Sort Dropdown -->
                        <div
                            x-show="showMobileSort"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute top-full right-0 mt-2 w-64 bg-white rounded-lg shadow-xl border border-gray-200 z-50 p-4"
                            @click.away="showMobileSort = false"
                        >
                            <h3 class="font-semibold text-navy-900 mb-4">Urutkan Produk</h3>
                            <div class="space-y-2">
                                <button
                                    @click="showMobileSort = false"
                                    class="w-full text-left p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Terbaru
                                </button>
                                <button
                                    @click="showMobileSort = false"
                                    class="w-full text-left p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Harga: Rendah ke Tinggi
                                </button>
                                <button
                                    @click="showMobileSort = false"
                                    class="w-full text-left p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Harga: Tinggi ke Rendah
                                </button>
                                <button
                                    @click="showMobileSort = false"
                                    class="w-full text-left p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Nama: A-Z
                                </button>
                                <button
                                    @click="showMobileSort = false"
                                    class="w-full text-left p-3 rounded-lg hover:bg-gray-50 transition-colors"
                                >
                                    Popularitas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="container-custom py-6 lg:py-8">
            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Mobile Filter Overlay -->
                <div
                    x-show="showMobileFilter"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden"
                    @click="closeMobileFilter()"
                ></div>

                <!-- Sidebar Filter -->
                <div class="lg:col-span-1">
                    <!-- Mobile Filter Panel -->
                    <div
                        x-show="showMobileFilter"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="-translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-200 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="-translate-x-full"
                        class="fixed left-0 top-0 h-full w-80 bg-white z-50 overflow-y-auto lg:hidden"
                    >
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-navy-900">Filter Produk</h3>
                                <button @click="closeMobileFilter()" class="text-gray-500 hover:text-gray-700">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                            <!-- Mobile Categories -->
                            <div class="space-y-2">
                                <a
                                    href="{{ route('categories.products', $category->slug) }}"
                                    class="flex items-center justify-between p-3 rounded-lg bg-brand-orange text-white"
                                >
                                    <span class="font-medium">{{ $category->name }}</span>
                                    <span class="text-sm bg-white/20 px-2 py-1 rounded-full">
                                        {{ $products->total() }}
                                    </span>
                                </a>
                                @foreach ($categories->where('id', '!=', $category->id) as $cat)
                                    <a
                                        href="{{ route('categories.products', $cat->slug) }}"
                                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors text-gray-700"
                                    >
                                        <span>{{ $cat->name }}</span>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            {{ $cat->products_count ?? 0 }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Desktop Filter -->
                    <div class="hidden lg:block bg-white rounded-lg shadow-sm border border-gray-200 sticky top-6">
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-navy-900 mb-6">Filter Kategori</h3>

                            <!-- Categories -->
                            <div class="space-y-2 mb-8">
                                <a
                                    href="{{ route('categories.products', $category->slug) }}"
                                    class="flex items-center justify-between p-3 rounded-lg bg-brand-orange text-white"
                                >
                                    <span class="font-medium">{{ $category->name }}</span>
                                    <span class="text-sm bg-white/20 px-2 py-1 rounded-full">
                                        {{ $products->total() }}
                                    </span>
                                </a>

                                @foreach ($categories->where('id', '!=', $category->id) as $cat)
                                    <a
                                        href="{{ route('categories.products', $cat->slug) }}"
                                        class="flex items-center justify-between p-3 rounded-lg hover:bg-gray-50 transition-colors text-gray-700 group"
                                    >
                                        <span class="group-hover:text-brand-orange transition-colors">
                                            {{ $cat->name }}
                                        </span>
                                        <span class="text-sm text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            {{ $cat->products_count ?? 0 }}
                                        </span>
                                    </a>
                                @endforeach
                            </div>

                            <!-- Price Range -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="font-semibold text-navy-900 mb-4">Range Harga</h4>
                                <div class="space-y-3">
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange focus:ring-offset-0"
                                        />
                                        <span class="ml-3 text-sm text-gray-700">Rp 10.000 - Rp 50.000</span>
                                    </label>
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange focus:ring-offset-0"
                                        />
                                        <span class="ml-3 text-sm text-gray-700">Rp 50.000 - Rp 100.000</span>
                                    </label>
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange focus:ring-offset-0"
                                        />
                                        <span class="ml-3 text-sm text-gray-700">Rp 100.000 - Rp 500.000</span>
                                    </label>
                                    <label
                                        class="flex items-center p-2 hover:bg-gray-50 rounded-lg transition-colors cursor-pointer"
                                    >
                                        <input
                                            type="checkbox"
                                            class="rounded border-gray-300 text-brand-orange focus:ring-brand-orange focus:ring-offset-0"
                                        />
                                        <span class="ml-3 text-sm text-gray-700">Rp 500.000+</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Grid -->
                <div class="lg:col-span-3 mt-6 lg:mt-0">
                    <!-- Sort Options Desktop -->
                    <div
                        class="hidden lg:flex items-center justify-between mb-6 bg-white rounded-lg p-4 shadow-sm border border-gray-200"
                    >
                        <p class="text-gray-600 text-sm">
                            Menampilkan
                            <span class="font-semibold text-navy-900">
                                {{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}
                            </span>
                            dari
                            <span class="font-semibold text-navy-900">{{ $products->total() }}</span>
                            produk
                        </p>
                        <div class="flex items-center space-x-3">
                            <label class="text-sm text-gray-600">Urutkan:</label>
                            <select
                                class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-brand-orange focus:border-brand-orange bg-white"
                            >
                                <option>Terbaru</option>
                                <option>Harga: Rendah ke Tinggi</option>
                                <option>Harga: Tinggi ke Rendah</option>
                                <option>Nama: A-Z</option>
                                <option>Popularitas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    @if ($products->count() > 0)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach ($products as $product)
                                <div
                                    class="bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 cursor-pointer overflow-hidden group"
                                    onclick="window.location.href = '{{ route('products.show', $product->slug ?? $product->id) }}'"
                                >
                                    <!-- Product Image -->
                                    <div class="relative overflow-hidden">
                                        @if ($product->featured_image)
                                            <img
                                                src="{{ Storage::url($product->featured_image) }}"
                                                alt="{{ $product->name }}"
                                                class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300"
                                            />
                                        @else
                                            <div class="w-full h-64 bg-gray-100 flex items-center justify-center">
                                                <svg
                                                    class="w-20 h-20 text-gray-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                    />
                                                </svg>
                                            </div>
                                        @endif

                                        <!-- Sale Badge -->
                                        @if (isset($product->promo_price) && $product->promo_price && isset($product->base_price) && $product->promo_price < $product->base_price)
                                            <div class="absolute top-4 left-4">
                                                <span
                                                    class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold shadow-lg"
                                                >
                                                    SALE
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Rating Badge -->
                                        <div class="absolute top-4 right-4">
                                            <div
                                                class="bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full flex items-center space-x-1"
                                            >
                                                <svg
                                                    class="w-3 h-3 text-yellow-400"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                                    />
                                                </svg>
                                                <span class="text-xs font-medium text-gray-700">4.9</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Product Content -->
                                    <div class="p-6">
                                        <!-- Category -->
                                        <div class="mb-3">
                                            <span
                                                class="inline-block bg-brand-orange/10 text-brand-orange text-xs font-semibold px-3 py-1 rounded-full"
                                            >
                                                {{ $product->category->name ?? 'Uncategorized' }}
                                            </span>
                                        </div>

                                        <!-- Product Name -->
                                        <h3
                                            class="font-bold text-gray-900 text-xl mb-3 line-clamp-2 leading-tight group-hover:text-brand-orange transition-colors"
                                        >
                                            {{ $product->name }}
                                        </h3>

                                        <!-- Description -->
                                        @if ($product->description || $product->short_description)
                                            <p class="text-gray-600 text-sm mb-4 line-clamp-2 leading-relaxed">
                                                {{ strip_tags($product->description ?? ($product->short_description ?? '')) }}
                                            </p>
                                        @endif

                                        <!-- Price Section -->
                                        <div class="mt-auto">
                                            @if (isset($product->promo_price) && $product->promo_price && isset($product->base_price) && $product->promo_price < $product->base_price)
                                                <div class="flex items-baseline space-x-2 mb-4">
                                                    <span class="text-2xl font-bold text-brand-orange">
                                                        Rp {{ number_format($product->promo_price, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-sm text-gray-500 line-through">
                                                        Rp {{ number_format($product->base_price, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @else
                                                <div class="mb-4">
                                                    <span class="text-2xl font-bold text-gray-900">
                                                        Rp {{ number_format($product->base_price ?? 0, 0, ',', '.') }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Empty State -->
                        <div class="text-center py-16">
                            <div class="max-w-md mx-auto">
                                <div
                                    class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center"
                                >
                                    <svg
                                        class="w-12 h-12 text-gray-400"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                                        />
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-navy-900 mb-3">Belum Ada Produk</h3>
                                <p class="text-gray-600 mb-6">
                                    Kategori {{ $category->name }} belum memiliki produk. Silakan cek kategori lain
                                    atau kembali lagi nanti.
                                </p>
                                <a
                                    href="{{ route('home') }}"
                                    class="inline-flex items-center px-6 py-3 bg-brand-orange text-white rounded-lg hover:bg-orange-600 transition-all duration-300"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                        />
                                    </svg>
                                    Kembali ke Beranda
                                </a>
                            </div>
                        </div>
                    @endif

                    <!-- Pagination -->
                    @if ($products->hasPages())
                        <div class="mt-8 flex justify-center">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                {{ $products->links() }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function categoryPage() {
            return {
                showMobileFilter: false,
                showMobileSort: false,

                toggleMobileFilter() {
                    this.showMobileFilter = !this.showMobileFilter;
                    this.showMobileSort = false;
                    if (this.showMobileFilter) {
                        document.body.style.overflow = 'hidden';
                    } else {
                        document.body.style.overflow = '';
                    }
                },

                toggleMobileSort() {
                    this.showMobileSort = !this.showMobileSort;
                    this.showMobileFilter = false;
                },

                closeMobileFilter() {
                    this.showMobileFilter = false;
                    document.body.style.overflow = '';
                },

                init() {
                    // Handle escape key
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'Escape') {
                            this.closeMobileFilter();
                            this.showMobileSort = false;
                        }
                    });
                },
            };
        }
    </script>
@endsection
