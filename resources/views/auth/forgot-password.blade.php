@extends('layouts.auth')

@section('title', 'Lupa Password - Tria Digital')

@section('content')
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

            <!-- Forgot Password Form -->
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden backdrop-blur-sm border border-white/20">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Lupa Password?</h2>
                    <p class="text-gray-600 mb-6">Masukkan email Anda untuk reset password</p>

                    <form class="space-y-6" method="POST" action="{{ route('password.request') }}">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <div class="relative group">
                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    value="{{ old('email') }}"
                                    class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200 group-hover:border-red-300"
                                    placeholder="Masukkan email Anda"
                                    required
                                />
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg
                                        class="w-5 h-5 text-gray-400 group-hover:text-red-400 transition-colors"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"
                                        />
                                    </svg>
                                </div>
                            </div>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <button
                            type="submit"
                            class="w-full bg-gradient-to-r from-red-600 to-red-500 text-white py-3 px-4 rounded-lg font-semibold hover:from-red-700 hover:to-red-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-[1.02] active:scale-[0.98] shadow-lg hover:shadow-xl"
                        >
                            <span class="flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                </svg>
                                Kirim Link Reset
                            </span>
                        </button>

                        <!-- Back to Login -->
                        <div class="text-center mt-6">
                            <p class="text-sm text-gray-600">
                                Ingat password Anda?
                                <a
                                    href="{{ route('login') }}"
                                    class="text-red-600 hover:text-red-500 font-medium transition-colors"
                                >
                                    Kembali ke Login
                                </a>
                            </p>
                        </div>
                    </form>
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

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => (show = false), 5000)"
                class="fixed top-4 right-4 bg-green-500 text-white p-4 rounded-lg shadow-lg z-50"
            >
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{ session('success') }}</span>
                    <button @click="show = false" class="ml-4 text-white hover:text-gray-200">&times;</button>
                </div>
            </div>
        @endif

        @if (session('error') || $errors->any())
            <div
                x-data="{ show: true }"
                x-show="show"
                x-init="setTimeout(() => (show = false), 5000)"
                class="fixed top-4 right-4 bg-red-500 text-white p-4 rounded-lg shadow-lg z-50"
            >
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            fill-rule="evenodd"
                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                            clip-rule="evenodd"
                        />
                    </svg>
                    <span>{{ session('error') ?? 'Terjadi kesalahan. Silakan periksa input Anda.' }}</span>
                    <button @click="show = false" class="ml-4 text-white hover:text-gray-200">&times;</button>
                </div>
            </div>
        @endif
    </div>
@endsection
