@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <!-- Back Button -->
                <div class="mb-6">
                    <a
                        href="{{ route('orders.index') }}"
                        class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900"
                    >
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"
                            />
                        </svg>
                        Kembali ke Daftar Pesanan
                    </a>
                </div>

                <!-- Order Header -->
                <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-6">
                    <div class="p-6 border-b">
                        <div class="flex flex-wrap justify-between items-start gap-4">
                            <div>
                                <h1 class="text-2xl font-bold">Pesanan #{{ $order['id'] }}</h1>
                                <p class="text-gray-600">
                                    {{ \Carbon\Carbon::parse($order['date'])->format('d M Y H:i') }}
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <div class="inline-flex flex-col items-end">
                                    <span class="text-sm text-gray-600">Status Pembayaran</span>
                                    @if ($order['payment_status'] === 'waiting_verification')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                        >
                                            Menunggu Verifikasi
                                        </span>
                                    @elseif ($order['payment_status'] === 'verified')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                        >
                                            Terverifikasi
                                        </span>
                                    @endif
                                </div>
                                <div class="inline-flex flex-col items-end">
                                    <span class="text-sm text-gray-600">Status Pesanan</span>
                                    @if ($order['status'] === 'pending')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                        >
                                            Menunggu Diproses
                                        </span>
                                    @elseif ($order['status'] === 'processing')
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                        >
                                            Sedang Diproses
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Order Details -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Items -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-bold mb-4">Detail Produk</h2>
                                <div class="space-y-4">
                                    @foreach ($order['items'] as $item)
                                        <div class="flex items-start space-x-4">
                                            <img
                                                src="{{ $item['image'] }}"
                                                alt="{{ $item['name'] }}"
                                                class="w-20 h-20 rounded-lg object-cover"
                                            />
                                            <div class="flex-1">
                                                <p class="font-medium text-gray-900">{{ $item['name'] }}</p>
                                                <p class="text-sm text-gray-600">{{ $item['quantity'] }} pcs</p>
                                                <p class="text-sm font-medium">
                                                    Rp {{ number_format($item['price'], 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Information -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-bold mb-4">Informasi Pengiriman</h2>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Kurir</p>
                                        <p class="font-medium">{{ $order['shipping_method'] }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Status</p>
                                        <p class="font-medium">
                                            @if ($order['shipping_status'] === 'pending')
                                                Menunggu Dikirim
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-600 mb-1">Alamat Pengiriman</p>
                                    <p class="font-medium">{{ $order['shipping_address']['name'] }}</p>
                                    <p class="text-gray-600">{{ $order['shipping_address']['phone'] }}</p>
                                    <p class="text-gray-600">{{ $order['shipping_address']['address'] }}</p>
                                    <p class="text-gray-600">
                                        {{ $order['shipping_address']['city'] }},
                                        {{ $order['shipping_address']['province'] }}
                                        {{ $order['shipping_address']['postal_code'] }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <h2 class="text-lg font-bold mb-4">Informasi Pembayaran</h2>
                                <div class="space-y-4">
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600">Metode Pembayaran</p>
                                            <p class="font-medium">{{ $order['payment']['method'] }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm text-gray-600">Status</p>
                                            <p class="font-medium">
                                                @if ($order['payment']['status'] === 'waiting_verification')
                                                    Menunggu Verifikasi
                                                @endif
                                            </p>
                                        </div>
                                    </div>
                                    @if ($order['payment']['proof'])
                                        <div>
                                            <p class="text-sm text-gray-600 mb-2">Bukti Pembayaran</p>
                                            <img
                                                src="{{ asset('storage/' . $order['payment']['proof']) }}"
                                                alt="Bukti Pembayaran"
                                                class="w-full max-w-md rounded-lg"
                                            />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="lg:col-span-1">
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden sticky top-6">
                            <div class="p-6">
                                <h2 class="text-lg font-bold mb-4">Ringkasan Pembayaran</h2>
                                <div class="space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal Produk</span>
                                        <span>
                                            Rp
                                            {{ number_format($order['total'] - $order['shipping_cost'], 0, ',', '.') }}
                                        </span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Biaya Pengiriman</span>
                                        <span>Rp {{ number_format($order['shipping_cost'], 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between font-bold text-lg pt-2 border-t mt-2">
                                        <span>Total</span>
                                        <span>Rp {{ number_format($order['total'], 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
