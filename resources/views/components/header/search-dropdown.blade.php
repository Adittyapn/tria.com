@props(['type' => 'desktop'])

@if ($type === 'mobile')
    <!-- Mobile Search Dropdown -->
    <div
        x-show="showDropdown && (searchResults.length > 0 || loading || query.length >= 2)"
        @click.away="hideDropdown()"
        class="absolute top-full left-0 right-0 mt-1 bg-white rounded-lg shadow-xl border border-gray-200 z-[60] max-h-80 overflow-y-auto"
        x-cloak
    >
        <!-- Loading State -->
        <div x-show="loading" class="p-4 text-center text-gray-500">
            <div
                class="animate-spin inline-block w-5 h-5 border-2 border-gray-300 border-t-blue-600 rounded-full"
            ></div>
            <span class="ml-2">Mencari...</span>
        </div>

        <!-- No Results -->
        <div
            x-show="! loading && query.length >= 2 && searchResults.length === 0"
            class="p-4 text-center text-gray-500"
        >
            <svg
                class="w-8 h-8 mx-auto mb-2 text-gray-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                ></path>
            </svg>
            <p>
                Tidak ada produk ditemukan untuk "
                <span x-text="query"></span>
                "
            </p>
        </div>

        <!-- Search Results -->
        <div x-show="! loading && searchResults.length > 0">
            <template
                x-for="(product, index) in searchResults"
                :key="product.id"
            >
                <a
                    :href="product.url"
                    @click="selectProduct(product)"
                    class="flex items-center p-3 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0"
                    :class="{ 'bg-blue-50': highlightedIndex === index }"
                    @mouseenter="highlightedIndex = index"
                >
                    <div
                        class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-lg overflow-hidden mr-3"
                    >
                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            onerror="this.src='/images/default-product.png'"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4
                            class="font-medium text-gray-900 text-sm truncate"
                            x-text="product.name"
                        ></h4>
                        <p
                            class="text-xs text-gray-500 truncate"
                            x-text="product.category"
                        ></p>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="text-sm font-semibold text-green-600"
                                x-text="product.price"
                            ></span>
                            <span
                                x-show="product.original_price"
                                class="text-xs text-gray-400 line-through"
                                x-text="product.original_price"
                            ></span>
                        </div>
                    </div>
                </a>
            </template>
        </div>
    </div>
@else
    <!-- Desktop Search Dropdown -->
    <div
        x-show="showDropdown && (searchResults.length > 0 || loading || query.length >= 2)"
        @click.away="hideDropdown()"
        class="absolute top-full left-0 right-0 mt-2 bg-white rounded-lg shadow-xl border border-gray-200 z-[60] max-h-96 overflow-y-auto"
        x-cloak
    >
        <!-- Loading State -->
        <div x-show="loading" class="p-6 text-center text-gray-500">
            <div
                class="animate-spin inline-block w-6 h-6 border-2 border-gray-300 border-t-blue-600 rounded-full"
            ></div>
            <span class="ml-2">Mencari produk...</span>
        </div>

        <!-- No Results -->
        <div
            x-show="! loading && query.length >= 2 && searchResults.length === 0"
            class="p-6 text-center text-gray-500"
        >
            <svg
                class="w-12 h-12 mx-auto mb-4 text-gray-300"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                ></path>
            </svg>
            <p class="text-gray-600 mb-2">Tidak ada produk ditemukan</p>
            <p class="text-sm text-gray-500">
                untuk pencarian "
                <span class="font-medium" x-text="query"></span>
                "
            </p>
        </div>

        <!-- Search Results -->
        <div x-show="! loading && searchResults.length > 0">
            <div class="p-3 border-b border-gray-100">
                <p class="text-sm text-gray-600">
                    Menampilkan
                    <span
                        class="font-medium"
                        x-text="searchResults.length"
                    ></span>
                    produk untuk "
                    <span class="font-medium" x-text="query"></span>
                    "
                </p>
            </div>
            <template
                x-for="(product, index) in searchResults"
                :key="product.id"
            >
                <a
                    :href="product.url"
                    @click="selectProduct(product)"
                    class="flex items-center p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-0"
                    :class="{ 'bg-blue-50': highlightedIndex === index }"
                    @mouseenter="highlightedIndex = index"
                >
                    <div
                        class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg overflow-hidden mr-4"
                    >
                        <img
                            :src="product.image"
                            :alt="product.name"
                            class="w-full h-full object-cover"
                            loading="lazy"
                            onerror="this.src='/images/default-product.png'"
                        />
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4
                            class="font-semibold text-gray-900 text-base truncate mb-1"
                            x-text="product.name"
                        ></h4>
                        <p
                            class="text-sm text-gray-500 truncate mb-2"
                            x-text="product.category"
                        ></p>
                        <div class="flex items-center gap-3">
                            <span
                                class="text-base font-bold text-green-600"
                                x-text="product.price"
                            ></span>
                            <span
                                x-show="product.original_price"
                                class="text-sm text-gray-400 line-through"
                                x-text="product.original_price"
                            ></span>
                            <span
                                x-show="product.discount"
                                class="text-xs bg-red-100 text-red-600 px-2 py-1 rounded-full font-medium"
                            >
                                -
                                <span x-text="product.discount"></span>
                                %
                            </span>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <svg
                            class="w-5 h-5 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 5l7 7-7 7"
                            ></path>
                        </svg>
                    </div>
                </a>
            </template>
        </div>
    </div>
@endif
