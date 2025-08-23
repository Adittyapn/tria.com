@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Checkout Steps -->
                @include('checkout._steps', ['progress' => 66])

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Payment Information -->
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                            <h2 class="text-xl font-bold mb-4">Informasi Pembayaran</h2>

                            <!-- Payment Method Selection -->
                            <div class="mb-6" x-data="{ paymentMethod: 'bank_transfer' }">
                                <h3 class="text-lg font-semibold mb-4">Pilih Metode Pembayaran</h3>

                                <!-- Payment Method Cards -->
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <!-- Bank Transfer Card -->
                                    <div
                                        @click="paymentMethod = 'bank_transfer'"
                                        :class="paymentMethod === 'bank_transfer' ? 'border-red-500 ring-2 ring-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200 hover:bg-red-50'"
                                        class="relative p-4 border rounded-xl cursor-pointer transition-all duration-200"
                                    >
                                        <div class="flex items-center space-x-4">
                                            <!-- Bank Icon -->
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="w-6 h-6 text-red-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <!-- Method Details -->
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">Transfer Bank</h4>
                                                <p class="text-sm text-gray-500 mt-1">
                                                    Transfer manual melalui Bank BCA
                                                </p>
                                            </div>
                                            <!-- Radio Button -->
                                            <div class="flex-shrink-0">
                                                <div
                                                    :class="paymentMethod === 'bank_transfer' ? 'border-red-500 bg-red-500' : 'border-gray-300 bg-white'"
                                                    class="w-6 h-6 border-2 rounded-full flex items-center justify-center transition-colors"
                                                >
                                                    <div
                                                        :class="paymentMethod === 'bank_transfer' ? 'bg-white' : 'bg-transparent'"
                                                        class="w-2 h-2 rounded-full transition-colors"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QRIS Card -->
                                    <div
                                        @click="paymentMethod = 'qris'"
                                        :class="paymentMethod === 'qris' ? 'border-red-500 ring-2 ring-red-500 bg-red-50' : 'border-gray-200 hover:border-red-200 hover:bg-red-50'"
                                        class="relative p-4 border rounded-xl cursor-pointer transition-all duration-200"
                                    >
                                        <div class="flex items-center space-x-4">
                                            <!-- QRIS Icon -->
                                            <div class="flex-shrink-0">
                                                <div
                                                    class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="w-6 h-6 text-red-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                                                        ></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <!-- Method Details -->
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-gray-900">QRIS</h4>
                                                <p class="text-sm text-gray-500 mt-1">Bayar dengan scan QRIS</p>
                                            </div>
                                            <!-- Radio Button -->
                                            <div class="flex-shrink-0">
                                                <div
                                                    :class="paymentMethod === 'qris' ? 'border-red-500 bg-red-500' : 'border-gray-300 bg-white'"
                                                    class="w-6 h-6 border-2 rounded-full flex items-center justify-center transition-colors"
                                                >
                                                    <div
                                                        :class="paymentMethod === 'qris' ? 'bg-white' : 'bg-transparent'"
                                                        class="w-2 h-2 rounded-full transition-colors"
                                                    ></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Details -->
                                <div class="mt-6">
                                    <!-- Bank Transfer Details -->
                                    <div
                                        x-show="paymentMethod === 'bank_transfer'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        class="bg-white rounded-xl border border-gray-200 overflow-hidden"
                                    >
                                        <!-- Bank Header -->
                                        <div class="p-5 border-b border-gray-100">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center space-x-3">
                                                    <div
                                                        class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center"
                                                    >
                                                        <span class="text-blue-600 font-bold">BCA</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-medium text-gray-900">Bank BCA</p>
                                                        <p class="text-sm text-gray-500">Transfer Bank</p>
                                                    </div>
                                                </div>
                                                <span
                                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full"
                                                >
                                                    Rekening Bank
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Bank Details -->
                                        <div class="p-5 space-y-4">
                                            <div>
                                                <label class="text-sm text-gray-500">Nomor Rekening</label>
                                                <div
                                                    class="mt-1 flex items-center justify-between bg-gray-50 px-4 py-2 rounded-lg"
                                                >
                                                    <span class="font-mono text-lg font-medium text-gray-900">
                                                        1234567890
                                                    </span>
                                                    <button
                                                        onclick="copyToClipboard('1234567890')"
                                                        class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors"
                                                    >
                                                        <svg
                                                            class="w-5 h-5"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="text-sm text-gray-500">Atas Nama</label>
                                                <div class="mt-1 font-medium text-gray-900">Aditya</div>
                                            </div>

                                            <div>
                                                <label class="text-sm text-gray-500">Total Transfer</label>
                                                <div class="mt-1 text-lg font-bold text-gray-900">Rp 145.000</div>
                                            </div>

                                            <div class="pt-4 mt-4 border-t border-gray-100">
                                                <div class="flex items-start">
                                                    <div class="flex-shrink-0">
                                                        <svg
                                                            class="w-5 h-5 text-yellow-400"
                                                            fill="currentColor"
                                                            viewBox="0 0 20 20"
                                                        >
                                                            <path
                                                                fill-rule="evenodd"
                                                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                                                clip-rule="evenodd"
                                                            />
                                                        </svg>
                                                    </div>
                                                    <p class="ml-2 text-sm text-gray-500">
                                                        Pastikan nominal transfer sesuai sampai 3 digit terakhir
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- QRIS Payment -->
                                    <div
                                        x-show="paymentMethod === 'qris'"
                                        x-transition:enter="transition ease-out duration-200"
                                        x-transition:enter-start="opacity-0 transform -translate-y-2"
                                        x-transition:enter-end="opacity-100 transform translate-y-0"
                                        class="bg-white rounded-xl border border-gray-200 p-6 text-center"
                                    >
                                        <div class="inline-block">
                                            <div
                                                class="w-14 h-14 mx-auto mb-4 bg-red-100 rounded-xl flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-8 h-8 text-red-600"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>
                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">Scan untuk Membayar</h3>
                                        <p class="text-gray-600 mb-6">
                                            Scan kode QR menggunakan aplikasi e-wallet atau m-banking Anda
                                        </p>

                                        <div
                                            class="bg-white p-4 rounded-xl inline-block shadow-sm border border-gray-200"
                                        >
                                            <img
                                                src="https://via.placeholder.com/200"
                                                alt="QRIS Code"
                                                class="w-48 h-48"
                                            />
                                        </div>

                                        <div class="mt-6 flex items-center justify-center space-x-4">
                                            <button
                                                class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"
                                                    />
                                                </svg>
                                                Unduh QR Code
                                            </button>
                                            <span class="text-gray-300">|</span>
                                            <button
                                                class="text-red-600 hover:text-red-700 font-medium text-sm flex items-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 mr-1"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                    />
                                                </svg>
                                                Salin Kode QRIS
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Bukti Transfer -->
                            <form
                                action="{{ route('checkout.process-payment') }}"
                                method="POST"
                                enctype="multipart/form-data"
                                class="space-y-4"
                            >
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Bukti Transfer
                                    </label>
                                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
                                        <div class="text-center" x-data="{ fileSelected: false, fileName: '' }">
                                            <div x-show="!fileSelected">
                                                <svg
                                                    class="mx-auto h-12 w-12 text-gray-400"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 48 48"
                                                >
                                                    <path
                                                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                                        stroke-width="2"
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                    />
                                                </svg>
                                                <div class="mt-4 flex text-sm text-gray-600 justify-center">
                                                    <label
                                                        for="payment_proof"
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-red-600 hover:text-red-500"
                                                    >
                                                        <span>Upload file</span>
                                                        <input
                                                            id="payment_proof"
                                                            name="payment_proof"
                                                            type="file"
                                                            accept="image/*"
                                                            class="sr-only"
                                                            required
                                                            @change="fileSelected = true; fileName = $event.target.files[0].name"
                                                        />
                                                    </label>
                                                    <p class="pl-1">atau drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 10MB</p>
                                            </div>
                                            <div x-show="fileSelected" class="text-left">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-500" x-text="fileName"></span>
                                                    <button
                                                        type="button"
                                                        @click="fileSelected = false; document.getElementById('payment_proof').value = ''"
                                                        class="text-sm text-red-600 hover:text-red-500"
                                                    >
                                                        Hapus
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    class="w-full bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition-colors"
                                >
                                    Konfirmasi Pembayaran
                                </button>
                            </form>
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
                                    Bayar Sekarang
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
