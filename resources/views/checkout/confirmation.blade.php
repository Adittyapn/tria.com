@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Checkout Steps -->
                @include('checkout._steps', ['progress' => 100])

                <div class="max-w-2xl mx-auto">
                    <div class="bg-white rounded-lg shadow-sm p-6">
                        <div class="text-center mb-8">
                            <div
                                class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4"
                            >
                                <svg
                                    class="w-8 h-8 text-green-500"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-2">Pesanan Berhasil Dibuat</h2>
                            <p class="text-gray-600">Nomor Pesanan: #ORD123456</p>
                        </div>

                        <!-- Order Status -->
                        <div class="border rounded-lg p-4 mb-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Status Pesanan</span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800"
                                    >
                                        Menunggu Verifikasi
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Total Pembayaran</span>
                                    <span class="font-bold text-xl">Rp 145.000</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Metode Pembayaran</span>
                                    <span class="font-medium">Transfer Bank BCA</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Bukti Pembayaran</span>
                                    <span class="text-green-600 font-medium">Telah Diunggah</span>
                                </div>
                            </div>
                        </div>

                        <!-- Order Information -->
                        <div class="border rounded-lg p-4 mb-6">
                            <h3 class="font-bold text-lg mb-4">Informasi Pesanan</h3>
                            <div class="space-y-4">
                                <div class="flex items-start space-x-4">
                                    <img
                                        src="https://via.placeholder.com/80"
                                        alt="Product"
                                        class="w-20 h-20 rounded-lg object-cover"
                                    />
                                    <div>
                                        <p class="font-medium">Spanduk Vinyl 1x2m</p>
                                        <p class="text-sm text-gray-600">2 pcs</p>
                                        <p class="text-sm font-medium">Rp 120.000</p>
                                    </div>
                                </div>
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
                            </div>
                        </div>

                        <!-- Next Steps -->
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path
                                            fill-rule="evenodd"
                                            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                            clip-rule="evenodd"
                                        />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Langkah Selanjutnya</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <li>Bukti pembayaran Anda sedang diverifikasi oleh tim kami</li>
                                            <li>Anda akan menerima notifikasi ketika pembayaran telah diverifikasi</li>
                                            <li>Pesanan akan diproses setelah pembayaran terverifikasi</li>
                                            <li>Anda dapat memantau status pesanan di halaman "Pesanan Saya"</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex space-x-4">
                            <a
                                href="/orders"
                                class="flex-1 bg-red-600 text-white py-3 rounded-lg font-bold hover:bg-red-700 transition-colors text-center"
                            >
                                Lihat Pesanan
                            </a>
                            <a
                                href="/"
                                class="flex-1 bg-gray-200 text-gray-800 py-3 rounded-lg font-bold hover:bg-gray-300 transition-colors text-center"
                            >
                                Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
