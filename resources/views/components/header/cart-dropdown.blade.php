@props(['type' => 'mobile'])

@if ($type === 'mobile')
    <!-- Mobile Cart -->
    <div class="relative" x-data="mobileCartDropdown()">
        <button
            @click="toggleDropdown()"
            class="relative text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors"
        >
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
            <span
                class="absolute -top-1 -right-1 text-white text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                style="background-color: #ff7900"
                x-text="cartCount"
            >
                0
            </span>
        </button>

        <!-- Mobile Cart Dropdown -->
        <div
            x-show="open"
            @click.away="open = false"
            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-cloak
        >
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Keranjang
                        <span class="text-sm text-gray-500">
                            (
                            <span x-text="cartCount"></span>
                            )
                        </span>
                    </h3>
                    <a href="/cart" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat Semua</a>
                </div>

                <!-- Empty Cart -->
                <div x-show="cartItems.length === 0" class="text-center py-8 text-gray-500">
                    <svg
                        class="w-12 h-12 mx-auto mb-3 text-gray-300"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        ></path>
                    </svg>
                    <p class="text-gray-600 mb-4">Keranjang masih kosong</p>
                    <a
                        href="/"
                        class="inline-block text-white px-4 py-2 rounded-lg hover:opacity-90 transition-colors font-medium text-sm"
                        style="background-color: #000334"
                    >
                        Mulai Belanja
                    </a>
                </div>

                <!-- Cart Items -->
                <div x-show="cartItems.length > 0">
                    <div id="mobile-cart-items-container" class="max-h-60 overflow-y-auto">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                    <div class="mt-4 pt-4 border-t">
                        <a
                            href="/cart"
                            class="w-full text-white px-4 py-2 rounded-lg hover:opacity-90 transition-colors font-medium text-center block"
                            style="background-color: #000334"
                        >
                            Lihat Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <!-- Desktop Cart -->
    <div class="relative" x-data="cartDropdown()">
        <button
            @click="toggleDropdown()"
            class="relative bg-gray-100 p-2 rounded-lg hover:bg-gray-200 transition-colors"
        >
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                />
            </svg>
            <span
                class="absolute -top-2 -right-2 text-white text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center"
                style="background-color: #ff7900"
                x-text="cartCount"
            >
                0
            </span>
        </button>

        <!-- Cart Dropdown with Simple Logic -->
        <div
            x-show="open"
            @click.away="open = false"
            class="absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
            x-cloak
        >
            <div class="p-5">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-xl font-semibold text-gray-900">
                        Keranjang
                        <span class="text-sm text-gray-500">
                            (
                            <span x-text="cartCount"></span>
                            )
                        </span>
                    </h3>

                    <a href="/cart" class="text-sm text-blue-600 hover:text-blue-800 font-medium">Lihat</a>
                </div>

                <!-- Empty Cart -->
                <div x-show="cartItems.length === 0" class="text-center py-10 text-gray-500">
                    <p class="text-gray-600 mb-4">Keranjang masih kosong</p>
                    <a
                        href="#"
                        class="inline-block text-white px-6 py-3 rounded-lg hover:opacity-90 transition-colors font-medium"
                        style="background-color: #000334"
                    >
                        Mulai Belanja
                    </a>
                </div>

                <!-- Cart Items -->
                <div x-show="cartItems.length > 0">
                    <div id="cart-items-container" class="max-h-80 overflow-y-auto">
                        <!-- Items will be populated by JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
