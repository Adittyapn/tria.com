<!-- Top Bar -->
<div class="bg-gradient-to-r from-red-600 to-red-500 text-white text-xs sm:text-sm">
    <div class="container mx-auto px-2 sm:px-4">
        <div class="flex justify-between items-center py-2">
            <div class="flex items-center space-x-2 sm:space-x-4">
                <span class="flex items-center">
                    <svg class="w-3 h-3 sm:w-4 sm:h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                        />
                    </svg>
                    <a href="tel:+628223456789" class="hover:underline text-xs sm:text-sm">(0822) 2536257</a>
                </span>
                <span class="hidden sm:flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                        />
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                        />
                    </svg>
                    Bandung, West Java, ID
                </span>
            </div>
            <div class="flex items-center space-x-2 text-xs">
                <a href="#" class="hover:underline">Lacak</a>
                <span class="hidden sm:inline">|</span>
                <a href="#" class="hidden sm:inline hover:underline">Bantuan</a>
            </div>
        </div>
    </div>
</div>

<!-- Main Header -->
<header
    x-data="{ loading: false }"
    @submit.window="loading = true"
    @load-end.window="loading = false"
    class="bg-gradient-to-b from-red-500 to-red-600 text-white sticky top-0 z-50 shadow-lg"
>
    <div class="container mx-auto px-2 sm:px-4">
        <!-- Mobile Header -->
        <div class="md:hidden py-3">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button x-data @click="$dispatch('toggle-mobile-menu')" class="text-white p-2">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                    </svg>
                </button>

                <!-- Mobile Logo -->
                <a href="/" class="flex items-center space-x-2">
                    <div class="bg-white p-1.5 rounded">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-lg font-bold leading-none">TRIA</h1>
                        <p class="text-xs text-red-100 leading-none">Digital</p>
                    </div>
                </a>

                <!-- Mobile Actions -->
                <div class="flex items-center space-x-2">
                    <!-- User Menu Mobile -->
                    @auth
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="block">
                                <img
                                    class="w-8 h-8 rounded-full object-cover"
                                    src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff' }}"
                                    alt="{{ Auth::user()->name }}"
                                />
                            </button>
                            <!-- Mobile User Dropdown -->
                            <x-user-dropdown />
                        </div>
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="text-white p-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </button>
                            <!-- Mobile Guest Dropdown -->
                            <div
                                x-show="open"
                                @click.away="open = false"
                                x-transition
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden z-50"
                                x-cloak
                            >
                                <a
                                    href="{{ url('dashboard/login') }}"
                                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100 border-b"
                                >
                                    Login
                                </a>
                                <a
                                    href="{{ url('dashboard/register') }}"
                                    class="block px-4 py-3 text-sm text-gray-700 hover:bg-gray-100"
                                >
                                    Daftar
                                </a>
                            </div>
                        </div>
                    @endauth

                    <!-- Mobile Cart -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="relative text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                />
                            </svg>
                            <span
                                class="absolute -top-1 -right-1 bg-yellow-400 text-gray-900 text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center"
                            >
                                0
                            </span>
                        </button>

                        <!-- Mobile Cart Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            class="absolute right-0 mt-2 w-72 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                            x-show="!loading"
                            x-cloak
                        >
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Keranjang Belanja</h3>
                                <div class="text-center py-8 text-gray-500">
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
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                        />
                                    </svg>
                                    <p class="text-gray-600 mb-4">Keranjang masih kosong</p>
                                    <a
                                        href="#"
                                        class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors"
                                    >
                                        Mulai Belanja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Search -->
            <div class="mt-3">
                <form class="relative">
                    <input
                        type="text"
                        placeholder="Cari produk..."
                        class="w-full px-4 py-2.5 pr-12 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                    />
                    <button type="submit" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                            />
                        </svg>
                    </button>
                </form>
            </div>
        </div>

        <!-- Desktop Header (unchanged) -->
        <div class="hidden md:block py-4">
            <!-- Top Header Row -->
            <div class="flex items-center justify-between mb-4">
                <!-- Logo -->
                <a href="/" class="flex items-center space-x-3">
                    <div class="bg-white p-2 rounded-lg">
                        <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">TRIA DIGITAL</h1>
                        <p class="text-xs text-red-100">Digital Printing Solution</p>
                    </div>
                </a>

                <!-- Search Bar -->
                <div class="flex-1 max-w-xl mx-8">
                    <form class="relative">
                        <input
                            type="text"
                            placeholder="Cari produk yang kamu inginkan..."
                            class="w-full px-4 py-3 pr-12 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-yellow-400"
                        />
                        <button
                            type="submit"
                            class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-red-600 text-white p-2 rounded-md hover:bg-red-700 transition-colors"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                />
                            </svg>
                        </button>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Authenticated User Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button
                                @click="open = !open"
                                class="flex items-center space-x-2 bg-white/20 p-2 rounded-lg hover:bg-white/30 transition-colors"
                            >
                                <img
                                    class="w-8 h-8 rounded-full object-cover"
                                    src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff' }}"
                                    alt="{{ Auth::user()->name }}"
                                />
                                <span class="hidden lg:inline font-medium">
                                    {{ Str::words(Auth::user()->name, 1, '') }}
                                </span>
                                <svg
                                    class="w-4 h-4 hidden lg:inline transition-transform"
                                    :class="{'rotate-180': open}"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M19 9l-7 7-7-7"
                                    ></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <x-user-dropdown />
                        </div>
                    @else
                        <!-- Guest Buttons -->
                        <a
                            href="{{ url('dashboard/login') }}"
                            class="bg-white text-red-600 px-[1.2rem] py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-colors"
                        >
                            Login
                        </a>
                        <a
                            href="{{ url('dashboard/register') }}"
                            class="bg-transparent border-2 border-white text-white px-4 py-2 rounded-lg font-medium hover:bg-white hover:text-red-600 transition-colors"
                        >
                            Daftar
                        </a>
                    @endauth

                    <!-- Desktop Cart -->
                    <div class="relative" x-data="{ open: false }">
                        <button
                            @click="open = !open"
                            class="relative bg-white/20 p-2 rounded-lg hover:bg-white/30 transition-colors"
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
                                class="absolute -top-2 -right-2 bg-yellow-400 text-gray-900 text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center"
                            >
                                0
                            </span>
                        </button>

                        <!-- Desktop Cart Dropdown -->
                        <div
                            x-show="open"
                            @click.away="open = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 transform scale-95"
                            x-transition:enter-end="opacity-100 transform scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 transform scale-100"
                            x-transition:leave-end="opacity-0 transform scale-95"
                            x-show="!loading"
                            x-cloak
                            class="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 z-50"
                        >
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Keranjang Belanja</h3>
                                <div class="text-center py-8 text-gray-500">
                                    <svg
                                        class="w-16 h-16 mx-auto mb-3 text-gray-300"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                        />
                                    </svg>
                                    <p class="text-gray-600">Keranjang masih kosong</p>
                                    <a
                                        href="#"
                                        class="mt-4 inline-block bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition-colors"
                                    >
                                        Mulai Belanja
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex items-center space-x-8 overflow-x-auto pb-2">
                <a
                    href="/"
                    class="text-white hover:text-yellow-300 whitespace-nowrap font-medium transition-colors flex items-center"
                >
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                        />
                    </svg>
                    Beranda
                </a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Stiker</a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Banner</a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Akrilk</a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Tumbler</a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Plakat</a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">
                    Kartu Nama
                </a>
                <a href="#" class="text-white/80 hover:text-yellow-300 whitespace-nowrap transition-colors">Buku</a>
                <!-- <div class="ml-auto">
                    <a
                        href="#"
                        class="bg-yellow-400 text-gray-900 px-4 py-2 rounded-lg font-bold hover:bg-yellow-300 transition-colors flex items-center"
                    >
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                                clip-rule="evenodd"
                            />
                        </svg>
                        Custom Design
                    </a>
                </div> -->
            </nav>
        </div>
    </div>
</header>

<!-- Mobile Menu Overlay -->
<div
    x-data="{ open: false }"
    @toggle-mobile-menu.window="open = !open"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="md:hidden fixed inset-0 z-50 bg-black bg-opacity-50"
    x-show="!loading"
    x-cloak
>
    <!-- Mobile Menu Panel -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-300 transform"
        x-transition:enter-start="-translate-x-full"
        x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-300 transform"
        x-transition:leave-start="translate-x-0"
        x-transition:leave-end="-translate-x-full"
        class="fixed left-0 top-0 h-full w-80 bg-white shadow-xl"
    >
        <div class="p-4 bg-red-600 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="bg-white p-1.5 rounded">
                        <svg class="w-6 h-6 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                            />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold">TRIA DIGITAL</h2>
                        <p class="text-xs text-red-100">Digital Printing Solution</p>
                    </div>
                </div>
                <button @click="open = false" class="text-white p-2">
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
        </div>

        <nav class="p-4">
            <a href="/" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg border-b">
                <svg class="w-5 h-5 mr-3 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                    />
                </svg>
                Beranda
            </a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Stiker</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Banner</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Akrilk</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Tumbler</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Plakat</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Kartu Nama</a>
            <a href="#" class="flex items-center py-3 px-2 text-gray-700 hover:bg-gray-100 rounded-lg">Buku</a>

            <!-- <div class="mt-4 pt-4 border-t">
                <a
                    href="#"
                    class="flex items-center justify-center bg-yellow-400 text-gray-900 px-4 py-3 rounded-lg font-bold hover:bg-yellow-300 transition-colors"
                >
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    Custom Design
                </a>
            </div> -->
        </nav>
    </div>
</div>
