<!-- Mobile Navigation Menu -->
<div
    x-show="mobileMenuOpen"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0 -translate-y-2"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    class="mt-4 pb-4 border-t border-gray-200"
    x-cloak
>
    <nav class="grid grid-cols-2 gap-2 mt-4">
        <a
            href="/"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20" style="color: #000334">
                <path
                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"
                />
            </svg>
            <span class="text-sm text-gray-700">Beranda</span>
        </a>
        <a
            href="/product/banner-draptek"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <span class="text-sm">Banner Outdoor</span>
        </a>
        <a
            href="/product/frame-poster-akrilik-a2"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <span class="text-sm">Frame Poster Akrilik</span>
        </a>
        <a
            href="/product/tumbler"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <span class="text-sm">Tumbler</span>
        </a>
        <a
            href="/product/plakat-custom"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <span class="text-sm">Plakat Custom</span>
        </a>
        <a
            href="/product/kartu-nama"
            class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-gray-700"
            @click="mobileMenuOpen = false"
        >
            <span class="text-sm">Kartu Nama</span>
        </a>
    </nav>

    <!-- Mobile Auth Buttons -->
    @guest
        <div class="flex gap-2 mt-4 pt-4 border-t border-gray-200">
            <a
                href="{{ url('dashboard/login') }}"
                class="flex-1 text-white px-4 py-2.5 rounded-lg font-medium hover:opacity-90 transition-colors text-center text-sm"
                style="background-color: #000334"
                @click="mobileMenuOpen = false"
            >
                Login
            </a>
            <a
                href="{{ url('dashboard/register') }}"
                class="flex-1 bg-transparent border px-4 py-2.5 rounded-lg font-medium hover:bg-gray-100 transition-colors text-center text-sm"
                style="border-color: #000334; color: #000334"
                @click="mobileMenuOpen = false"
            >
                Daftar
            </a>
        </div>
    @endguest

    @auth
        <div class="mt-4 pt-4 border-t border-gray-200">
            <div class="flex items-center space-x-3 p-3 bg-gray-100 rounded-lg mb-3">
                <img
                    class="w-10 h-10 rounded-full object-cover"
                    src="{{ Auth::user()->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&color=fff' }}"
                    alt="{{ Auth::user()->name }}"
                />
                <div>
                    <div class="font-medium text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="text-xs text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <!-- Mobile Menu Items based on Role -->
            <div class="space-y-1">
                @role('super_admin')
                    <!-- Super Admin Menu -->
                    <a
                        href="/admin/dashboard"
                        class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-sm text-gray-700"
                        @click="mobileMenuOpen = false"
                    >
                        <svg
                            class="w-4 h-4 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="color: #000334"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                            ></path>
                        </svg>
                        Dashboard
                    </a>
                @endrole

                @role('customer')
                    <!-- Customer Menu -->
                    <a
                        href="/dashboard/my-orders"
                        class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-sm text-gray-700"
                        @click="mobileMenuOpen = false"
                    >
                        <svg
                            class="w-4 h-4 mr-3"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                            style="color: #000334"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                            ></path>
                        </svg>
                        Pesanan Saya
                    </a>
                @endrole

                <!-- Lacak Pesanan -->
                <a
                    href="/track-order"
                    class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-sm text-gray-700"
                    @click="mobileMenuOpen = false"
                >
                    <svg
                        class="w-4 h-4 mr-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        style="color: #000334"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                    Lacak Pesanan
                </a>

                <!-- Common Menu Items -->
                <a
                    href="/dashboard/my-profile"
                    class="flex items-center p-3 rounded-lg hover:bg-gray-100 transition-colors text-sm text-gray-700"
                    @click="mobileMenuOpen = false"
                >
                    <svg
                        class="w-4 h-4 mr-3"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                        style="color: #000334"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                        ></path>
                    </svg>
                    Akun Saya
                </a>

                <!-- Logout -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="flex items-center w-full p-3 rounded-lg hover:bg-red-100 transition-colors text-sm text-red-600"
                        @click="mobileMenuOpen = false"
                    >
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                            ></path>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    @endauth
</div>
