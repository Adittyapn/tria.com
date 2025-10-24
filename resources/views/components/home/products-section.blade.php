@props([
    'productTypes',
    'products',
])

<!-- Products Section -->
<section id="products" class="section-padding bg-gray-50" x-data="productFilter()">
    <div class="container-custom">
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center px-4 py-2 bg-white rounded-full text-navy-900 text-sm font-medium mb-4 shadow-md"
            >
                <span class="w-2 h-2 bg-brand-orange rounded-full mr-2"></span>
                Produk Terlaris
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-navy-900 mb-6">
                Produk
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-orange to-navy-900">
                    Berkualitas Tinggi
                </span>
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Temukan berbagai produk cetak digital berkualitas premium dengan harga yang kompetitif
            </p>
        </div>

        <!-- Product Type Filter -->
        <div class="flex flex-wrap justify-center gap-3 mb-12">
            <button
                @click="filterProductType('all')"
                :class="activeProductType === 'all' ? 'bg-brand-orange text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-100'"
                class="px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105"
            >
                Semua Produk
            </button>
            @foreach ($productTypes as $productType)
                <button
                    @click="filterProductType('{{ $productType['slug'] }}')"
                    :class="activeProductType === '{{ $productType['slug'] }}' ? 'bg-brand-orange text-white shadow-lg' : 'bg-white text-gray-700 hover:bg-gray-100'"
                    class="px-6 py-3 rounded-full font-medium transition-all transform hover:scale-105"
                >
                    {{ $productType['name'] }}
                </button>
            @endforeach
        </div>

        <!-- Products Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <template x-for="product in filteredProducts" :key="product.id">
                <div
                    class="card card-hover cursor-pointer overflow-hidden flex flex-col"
                    @click="window.location.href = `/product/${product.slug}`"
                >
                    <div class="relative">
                        <img :src="product.featured_image" :alt="product.name" class="w-full h-48 object-cover" />
                        <div class="absolute top-4 left-4" x-show="product.promo_price">
                            <span class="bg-brand-orange text-white px-2 py-1 rounded-full text-xs font-bold">
                                SALE
                            </span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1 justify-between">
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <span
                                    class="text-xs font-medium text-brand-orange uppercase tracking-wide"
                                    x-text="product.category_name"
                                ></span>
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path
                                            d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"
                                        />
                                    </svg>
                                    <span class="text-xs text-gray-500">4.9</span>
                                </div>
                            </div>
                            <h3 class="font-bold text-navy-900 text-lg mb-2 line-clamp-2" x-text="product.name"></h3>
                            <p
                                class="text-gray-600 text-sm mb-4 line-clamp-2"
                                x-text="product.short_description"
                            ></p>
                        </div>
                        <div class="flex items-center justify-between mt-auto pt-2">
                            <div>
                                <p class="text-lg font-bold text-orange-600" x-text="product.price_formatted"></p>
                                <p
                                    x-show="product.promo_price"
                                    class="text-sm text-gray-500 line-through ml-2"
                                    x-text="product.original_price_formatted"
                                ></p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Load More Button -->
        <div class="text-center mt-12" x-show="hasMoreProducts">
            <button @click="loadMoreProducts()" class="btn-outline">Lihat Lebih Banyak</button>
        </div>
    </div>

    @php
        $__products_for_js = $products
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'short_description' => $product->short_description,
                    'product_type' => $product->product_type,
                    'category_name' => $product->category->name ?? '',
                    'featured_image' => asset('storage/' . $product->featured_image),
                    'price_formatted' => 'Rp ' . number_format($product->promo_price ?? $product->base_price, 0, ',', '.'),
                    'original_price_formatted' => 'Rp ' . number_format($product->base_price, 0, ',', '.'),
                    'promo_price' => (bool) $product->promo_price,
                    'show' => true,
                ];
            })
            ->toArray();
    @endphp

    <script>
        function productFilter() {
            return {
                activeProductType: 'all',
                products: @json($__products_for_js),
                displayedProducts: 8,
                get filteredProducts() {
                    let filtered = this.products.filter(
                        (product) =>
                            this.activeProductType === 'all' || product.product_type === this.activeProductType,
                    );

                    return filtered.slice(0, this.displayedProducts).map((product) => ({
                        ...product,
                        show: true,
                    }));
                },
                get hasMoreProducts() {
                    let filtered = this.products.filter(
                        (product) =>
                            this.activeProductType === 'all' || product.product_type === this.activeProductType,
                    );
                    return filtered.length > this.displayedProducts;
                },
                filterProductType(productType) {
                    this.activeProductType = productType;
                    this.displayedProducts = 8;
                },
                loadMoreProducts() {
                    this.displayedProducts += 8;
                },
            };
        }
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</section>
