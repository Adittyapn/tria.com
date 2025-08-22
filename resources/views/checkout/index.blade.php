@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                @include('checkout._steps', ['progress' => 33])

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Shipping Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-xl font-bold mb-4">Informasi Pengiriman</h2>
                            <form x-data="{ selectedAddress: 'existing' }">
                                <!-- Address Selection -->
                                <div class="mb-6">
                                    <div class="flex items-center space-x-4 mb-4">
                                        <button
                                            type="button"
                                            @click="selectedAddress = 'existing'"
                                            :class="{'bg-red-50 border-red-500': selectedAddress === 'existing', 'border-gray-300': selectedAddress !== 'existing'}"
                                            class="flex-1 p-4 border-2 rounded-lg"
                                        >
                                            <div class="font-medium">Alamat Tersimpan</div>
                                        </button>
                                        <button
                                            type="button"
                                            @click="selectedAddress = 'new'"
                                            :class="{'bg-red-50 border-red-500': selectedAddress === 'new', 'border-gray-300': selectedAddress !== 'new'}"
                                            class="flex-1 p-4 border-2 rounded-lg"
                                        >
                                            <div class="font-medium">Alamat Baru</div>
                                        </button>
                                    </div>
                                </div>

                                <!-- Existing Address -->
                                <div x-show="selectedAddress === 'existing'">
                                    <div class="border border-gray-200 rounded-lg p-4 mb-4">
                                        <div class="flex items-start">
                                            <input type="radio" name="address" class="mt-1" checked />
                                            <div class="ml-3">
                                                <p class="font-medium">Rumah</p>
                                                <p class="text-gray-600">John Doe</p>
                                                <p class="text-gray-600">Jl. Contoh No. 123</p>
                                                <p class="text-gray-600">Jakarta Selatan, 12345</p>
                                                <p class="text-gray-600">081234567890</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- New Address Form -->
                                <div x-show="selectedAddress === 'new'" class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Nama Lengkap
                                            </label>
                                            <input
                                                type="text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            />
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Nomor Telepon
                                            </label>
                                            <input
                                                type="tel"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            />
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                                        <textarea
                                            rows="3"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                        ></textarea>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi</label>
                                            <select
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            >
                                                <option>Pilih Provinsi</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Kota/Kabupaten
                                            </label>
                                            <select
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            >
                                                <option>Pilih Kota/Kabupaten</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                Kecamatan
                                            </label>
                                            <select
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            >
                                                <option>Pilih Kecamatan</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">Kode Pos</label>
                                            <input
                                                type="text"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                        <!-- Shipping Method -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold mb-4">Metode Pengiriman</h2>
                            <div class="space-y-3">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="shipping" class="mr-3" checked />
                                        <div>
                                            <p class="font-medium">JNE Regular</p>
                                            <p class="text-sm text-gray-600">Estimasi 2-3 hari</p>
                                            <p class="text-sm font-medium">Rp 25.000</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="shipping" class="mr-3" />
                                        <div>
                                            <p class="font-medium">J&T Express</p>
                                            <p class="text-sm text-gray-600">Estimasi 2-3 hari</p>
                                            <p class="text-sm font-medium">Rp 27.000</p>
                                        </div>
                                    </label>
                                </div>
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <label class="flex items-center">
                                        <input type="radio" name="shipping" class="mr-3" />
                                        <div>
                                            <p class="font-medium">SiCepat Regular</p>
                                            <p class="text-sm text-gray-600">Estimasi 2-3 hari</p>
                                            <p class="text-sm font-medium">Rp 26.000</p>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div>
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h2 class="text-xl font-bold mb-4">Ringkasan Pesanan</h2>
                            <div class="space-y-4">
                                <!-- Product List -->
                                <div class="space-y-3">
                                    <div class="flex items-start">
                                        <img
                                            src="https://via.placeholder.com/80"
                                            alt="Product"
                                            class="w-20 h-20 rounded-lg object-cover"
                                        />
                                        <div class="ml-3 flex-1">
                                            <p class="font-medium">Spanduk Vinyl 1x2m</p>
                                            <p class="text-sm text-gray-600">2 pcs</p>
                                            <p class="text-sm font-medium">Rp 120.000</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Details -->
                                <div class="border-t pt-4 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal</span>
                                        <span>Rp 120.000</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Biaya Pengiriman</span>
                                        <span>Rp 25.000</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-lg pt-2 border-t">
                                        <span>Total</span>
                                        <span>Rp 145.000</span>
                                    </div>
                                </div>

                                <button
                                    class="w-full bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition-colors"
                                >
                                    Lanjut ke Pembayaran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
