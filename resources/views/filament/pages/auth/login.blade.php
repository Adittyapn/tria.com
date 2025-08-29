{{-- resources/views/filament/pages/auth/login.blade.php --}}
<x-filament-panels::layout.base :livewire="$livewire">
    @push('styles')
        <style>
            body {
                background: linear-gradient(135deg, #f9fafb 0%, #e5e7eb 100%) !important;
            }
        </style>
    @endpush

    <div class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center p-4">
        <!-- Container -->
        <div class="w-full max-w-md">
            <!-- Logo & Brand -->
            <div class="text-center mb-8">
                <div
                    class="bg-gradient-to-br from-red-500 to-red-600 p-4 rounded-2xl inline-block mb-4 shadow-xl transform hover:scale-105 transition-transform duration-300"
                >
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"
                        />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">TRIA DIGITAL</h1>
                <p class="text-gray-600">Digital Printing Solution</p>
            </div>

            <!-- Login Form -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm border border-white/20">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang!</h2>
                    <p class="text-gray-600 mb-6">Masuk untuk melanjutkan ke admin panel</p>

                    <!-- Filament Form Component -->
                    <x-filament-panels::form wire:submit="authenticate">
                        <div class="space-y-6">
                            {{ $this->form }}
                        </div>

                        <div class="mt-6">
                            <x-filament-panels::form.actions
                                :actions="$this->getCachedFormActions()"
                                :full-width="$this->hasFullWidthFormActions()"
                                alignment="center"
                                class="w-full"
                            />
                        </div>
                    </x-filament-panels::form>

                    {{-- Optional: Add your social login buttons here --}}
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">Atau masuk dengan</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <button
                            type="button"
                            class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-red-300 transition-all duration-200 transform hover:scale-[1.02]"
                        >
                            <svg class="w-5 h-5 text-red-500 mr-2" viewBox="0 0 24 24">
                                <path
                                    fill="currentColor"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                                />
                                <path
                                    fill="currentColor"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                                />
                                <path
                                    fill="currentColor"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                                />
                                <path
                                    fill="currentColor"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                                />
                            </svg>
                            Google
                        </button>
                        <button
                            type="button"
                            class="flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-[1.02]"
                        >
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"
                                />
                            </svg>
                            Facebook
                        </button>
                    </div>
                </div>
            </div>

            <!-- Back to Homepage -->
            <div class="text-center mt-8">
                <a href="/" class="inline-flex items-center text-gray-600 hover:text-red-600 transition-colors group">
                    <svg
                        class="w-4 h-4 mr-2 group-hover:transform group-hover:-translate-x-1 transition-transform"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
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
    </div>

    @push('scripts')
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endpush
</x-filament-panels::layout.base>
