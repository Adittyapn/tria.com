<!-- Top Bar Component -->
<x-header.top-bar />

<!-- Main Header -->
<header
    x-data="{ cartCount: 0, mobileMenuOpen: false }"
    class="bg-white text-gray-800 sticky top-0 z-50 shadow-lg border-b border-gray-200"
>
    <div class="container mx-auto px-2 sm:px-4">
        <!-- Mobile Header -->
        <div class="md:hidden py-3">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button
                    @click="mobileMenuOpen = !mobileMenuOpen"
                    class="text-gray-700 p-2 rounded-lg hover:bg-gray-100 transition-colors"
                >
                    <svg
                        x-show="!mobileMenuOpen"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        ></path>
                    </svg>
                    <svg
                        x-show="mobileMenuOpen"
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>

                <!-- Mobile Logo Component -->
                <x-header.mobile-logo />

                <!-- Mobile Cart Component -->
                <x-header.cart-dropdown type="mobile" />
            </div>

            <!-- Mobile Search Component -->
            <x-header.search type="mobile" />

            <!-- Mobile Navigation Menu Component -->
            <x-header.mobile-menu />
        </div>

        <!-- Desktop Header -->
        <div class="hidden md:block py-4">
            <!-- Top Header Row -->
            <div class="flex items-center justify-between mb-4">
                <!-- Logo Component -->
                <x-header.logo />

                <!-- Search Bar Component -->
                <x-header.search type="desktop" />

                <!-- Header Actions -->
                <div class="flex items-center space-x-4">
                    @auth
                        <!-- Authenticated User Dropdown -->
                        <div class="relative" x-data="userDropdown()">
                            <button
                                @click="toggleDropdown()"
                                class="flex items-center space-x-2 bg-gray-100 p-2 rounded-lg hover:bg-gray-200 transition-colors"
                            >
                                <img
                                    class="w-8 h-8 rounded-full object-cover"
                                    src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff' }}"
                                    alt="{{ Auth::user()->name }}"
                                />
                                <span
                                    class="hidden lg:inline font-medium text-gray-800"
                                >
                                    {{ Str::words(Auth::user()->name, 1, '') }}
                                </span>
                                <svg
                                    class="w-4 h-4 transition-transform text-gray-600"
                                    :class="open ? 'rotate-180' : ''"
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

                            <!-- User Dropdown Menu Component -->
                            <x-header.user-dropdown />
                        </div>
                    @endauth

                    @guest
                        <!-- Guest Buttons -->
                        <a
                            href="{{ url('dashboard/login') }}"
                            class="text-white px-4 py-2.5 rounded-lg font-medium hover:opacity-90 transition-colors"
                            style="background-color: #000334"
                        >
                            Login
                        </a>
                        <a
                            href="{{ url('dashboard/register') }}"
                            class="bg-transparent border-2 px-4 py-2 rounded-lg font-medium hover:bg-gray-100 transition-colors"
                            style="border-color: #000334; color: #000334"
                        >
                            Daftar
                        </a>
                    @endguest

                    <!-- Desktop Cart Component -->
                    <x-header.cart-dropdown type="desktop" />
                </div>
            </div>

            <!-- Navigation Menu Component -->
            <x-header.desktop-nav />
        </div>
    </div>
</header>

<!-- Header Scripts Component -->
<x-header.scripts />
