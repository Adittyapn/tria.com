@extends('layouts.app')

@section('content')
    <div class="bg-gray-50 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-5xl mx-auto">
                <h1 class="text-2xl font-bold mb-6">Pesanan Saya</h1>

                <!-- Order List -->
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                            <!-- Order Header -->
                            <div class="p-4 sm:p-6 border-b">
                                <div class="flex flex-wrap justify-between items-center gap-4">
                                    <div>
                                        <p class="text-sm text-gray-600">Nomor Pesanan</p>
                                        <p class="font-medium">{{ $order->order_number }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Tanggal Pesanan</p>
                                        <p class="font-medium">
                                            {{ $order->created_at->format('d M Y') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Total Pembayaran</p>
                                        <p class="font-medium">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Status Pembayaran</p>
                                        @if ($order->payment_status === 'pending')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                                            >
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif ($order->payment_status === 'verified')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                            >
                                                Terverifikasi
                                            </span>
                                        @elseif ($order->payment_status === 'rejected')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800"
                                            >
                                                Ditolak
                                            </span>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Status Pesanan</p>
                                        @if ($order->status === 'pending_payment')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                                            >
                                                Menunggu Pembayaran
                                            </span>
                                        @elseif ($order->status === 'processing')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                            >
                                                Sedang Diproses
                                            </span>
                                        @elseif ($order->status === 'shipped')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                                            >
                                                Dalam Pengiriman
                                            </span>
                                        @elseif ($order->status === 'completed')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                                            >
                                                Selesai
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Order Items -->
                            <div class="p-4 sm:p-6">
                                <div class="space-y-4">
                                    @foreach ($order->items as $item)
                                        <div class="flex items-start space-x-4">
                                            @if ($item->product->image)
                                                <img
                                                    src="{{ asset('storage/' . $item->product->image) }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-20 h-20 rounded-lg object-cover flex-shrink-0"
                                                />
                                            @else
                                                <div
                                                    class="w-20 h-20 rounded-lg bg-gray-200 flex items-center justify-center flex-shrink-0"
                                                >
                                                    <svg
                                                        class="w-10 h-10 text-gray-400"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                        />
                                                    </svg>
                                                </div>
                                            @endif
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-gray-900">{{ $item->product->name }}</p>
                                                <p class="text-sm text-gray-600">{{ $item->quantity }} pcs</p>
                                                <p class="text-sm font-medium">
                                                    Rp {{ number_format($item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Actions -->
                                <div class="mt-6 flex items-center justify-end gap-3">
                                    @if ($order->payment_status === 'verified')
                                        <a
                                            href="{{ route('orders.download-invoice', $order->order_number) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 border border-blue-600 text-sm font-medium rounded-md text-blue-600 hover:bg-blue-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                            target="_blank"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                />
                                            </svg>
                                            Invoice
                                        </a>
                                    @endif

                                    <a
                                        href="{{ route('orders.show', $order->order_number) }}"
                                        class="inline-flex items-center px-4 py-2 border border-red-600 text-sm font-medium rounded-md text-red-600 hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                    >
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if ($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif

                <!-- Empty State -->
                @if ($orders->count() === 0)
                    <div class="text-center py-12">
                        <svg
                            class="mx-auto h-12 w-12 text-gray-400"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 48 48"
                        >
                            <path
                                d="M9.42857 19.7143V37.7143C9.42857 39.5343 10.9086 41.0143 12.7286 41.0143H35.2714C37.0914 41.0143 38.5714 39.5343 38.5714 37.7143V19.7143M9.42857 19.7143L14.1429 7.41429C14.6571 6.02857 16.0286 5.14286 17.5143 5.14286H30.4857C31.9714 5.14286 33.3429 6.02857 33.8571 7.41429L38.5714 19.7143M9.42857 19.7143H38.5714"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada pesanan</h3>
                        <p class="mt-1 text-sm text-gray-500">Mulai belanja untuk membuat pesanan pertama Anda.</p>
                        <div class="mt-6">
                            <a
                                href="{{ route('products.index') }}"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                            >
                                Mulai Belanja
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
