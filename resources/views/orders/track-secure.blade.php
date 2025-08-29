@extends('layouts.app')

@section('title', 'Tracking Order - ' . $order->order_number)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-4xl mx-auto">
            <!-- Header Section -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-4 shadow-lg"
                >
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2 2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                        ></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">Tracking Order</h1>
                <p class="text-xl font-mono font-semibold text-blue-600 mb-1">{{ $order->order_number }}</p>
                <p class="text-gray-600">Dibuat {{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>

            <!-- Status Banner -->
            <div class="mb-8">
                @php
                    $statusConfig = [
                        'pending_payment' => ['bg' => 'bg-yellow-50 border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'clock'],
                        'paid' => ['bg' => 'bg-blue-50 border-blue-200', 'text' => 'text-blue-800', 'icon' => 'check-circle'],
                        'processing' => ['bg' => 'bg-purple-50 border-purple-200', 'text' => 'text-purple-800', 'icon' => 'cog'],
                        'ready' => ['bg' => 'bg-indigo-50 border-indigo-200', 'text' => 'text-indigo-800', 'icon' => 'package'],
                        'shipped' => ['bg' => 'bg-green-50 border-green-200', 'text' => 'text-green-800', 'icon' => 'truck'],
                        'completed' => ['bg' => 'bg-emerald-50 border-emerald-200', 'text' => 'text-emerald-800', 'icon' => 'check-double'],
                        'cancelled' => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-800', 'icon' => 'x-circle'],
                    ];
                    $currentStatus = $statusConfig[$order->status] ?? $statusConfig['pending_payment'];
                @endphp

                <div
                    class="{{ $currentStatus['bg'] }} border {{ $currentStatus['text'] }} rounded-xl p-6 text-center"
                >
                    <div class="flex items-center justify-center mb-3">
                        @switch($currentStatus['icon'])
                            @case('clock')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>

                                @break
                            @case('check-circle')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>

                                @break
                            @case('cog')
                                <svg class="w-8 h-8 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                    ></path>
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                    ></path>
                                </svg>

                                @break
                            @case('package')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                    ></path>
                                </svg>

                                @break
                            @case('truck')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                                    ></path>
                                </svg>

                                @break
                            @case('check-double')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>

                                @break
                            @case('x-circle')
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    ></path>
                                </svg>

                                @break
                        @endswitch
                    </div>
                    <h2 class="text-2xl font-bold mb-2">{{ $order->status_label }}</h2>
                    <p class="text-base opacity-90">
                        @switch($order->status)
                            @case('pending_payment')
                                Menunggu konfirmasi pembayaran dari Anda

                                @break
                            @case('paid')
                                Pembayaran dikonfirmasi, pesanan masuk antrian produksi

                                @break
                            @case('processing')
                                Tim kami sedang memproses pesanan Anda

                                @break
                            @case('ready')
                                Pesanan sudah siap dan akan segera dikirim

                                @break
                            @case('shipped')
                                Pesanan sedang dalam perjalanan ke alamat tujuan

                                @break
                            @case('completed')
                                Pesanan telah selesai dan diterima dengan baik

                                @break
                            @case('cancelled')
                                Pesanan dibatalkan

                                @break
                        @endswitch
                    </p>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Progress Timeline -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg
                                class="w-6 h-6 mr-3 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"
                                ></path>
                            </svg>
                            Progress Pesanan
                        </h3>

                        <div class="space-y-4">
                            @foreach ($trackingSteps as $stepKey => $step)
                                @if (! isset($step['is_cancelled']))
                                    <div class="flex items-start space-x-4 relative">
                                        <!-- Timeline Line -->
                                        @if (! $loop->last)
                                            <div
                                                class="absolute left-6 top-12 w-0.5 h-16 {{ $step['completed'] ? 'bg-blue-300' : 'bg-gray-200' }}"
                                            ></div>
                                        @endif

                                        <!-- Step Icon -->
                                        <div
                                            class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center {{ $step['completed'] ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-500' }} transition-all duration-300"
                                        >
                                            @switch($step['icon'])
                                                @case('clock')
                                                    <svg
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        ></path>
                                                    </svg>

                                                    @break
                                                @case('check-circle')
                                                    <svg
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                        ></path>
                                                    </svg>

                                                    @break
                                                @case('cog')
                                                    <svg
                                                        class="w-6 h-6 {{ $step['completed'] ? 'animate-spin-slow' : '' }}"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                        ></path>
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        ></path>
                                                    </svg>

                                                    @break
                                                @case('package')
                                                    <svg
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                        ></path>
                                                    </svg>

                                                    @break
                                                @case('truck')
                                                    <svg
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                                                        ></path>
                                                    </svg>

                                                    @break
                                                @case('check-double')
                                                    <svg
                                                        class="w-6 h-6"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M5 13l4 4L19 7"
                                                        ></path>
                                                    </svg>

                                                    @break
                                            @endswitch
                                        </div>

                                        <!-- Step Content -->
                                        <div class="flex-1 pb-8">
                                            <h4 class="font-semibold text-gray-900 text-lg">{{ $step['label'] }}</h4>
                                            <p class="text-gray-600 text-sm mt-1">{{ $step['description'] }}</p>

                                            @if ($step['completed'] && $stepKey === $order->status)
                                                <div class="mt-2 flex items-center text-xs text-blue-600">
                                                    <div
                                                        class="w-2 h-2 bg-blue-600 rounded-full mr-2 animate-pulse"
                                                    ></div>
                                                    Status saat ini
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                            <!-- Cancelled Status -->
                            @if ($order->status === 'cancelled')
                                <div class="flex items-start space-x-4 relative">
                                    <div
                                        class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center bg-red-600 text-white"
                                    >
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-red-900 text-lg">Pesanan Dibatalkan</h4>
                                        <p class="text-red-700 text-sm mt-1">Pesanan telah dibatalkan</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                            <svg
                                class="w-6 h-6 mr-3 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"
                                ></path>
                            </svg>
                            Item Pesanan
                        </h3>

                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div
                                    class="flex items-start space-x-4 p-4 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors"
                                >
                                    <!-- Product Image Placeholder -->
                                    <div
                                        class="flex-shrink-0 w-16 h-16 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center"
                                    >
                                        @if ($item->product->image_url)
                                            <img
                                                src="{{ $item->product->image_url }}"
                                                alt="{{ $item->product->name }}"
                                                class="w-full h-full object-cover rounded-lg"
                                            />
                                        @else
                                            <svg
                                                class="w-8 h-8 text-gray-500"
                                                fill="none"
                                                stroke="currentColor"
                                                viewBox="0 0 24 24"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"
                                                ></path>
                                            </svg>
                                        @endif
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-semibold text-gray-900 text-base">
                                            {{ $item->product->name }}
                                        </h4>
                                        <div class="mt-1 space-y-1">
                                            <p class="text-sm text-gray-600">
                                                Jumlah:
                                                <span class="font-medium">{{ $item->quantity }} pcs</span>
                                            </p>

                                            @if ($item->custom_size)
                                                <p class="text-sm text-gray-600">
                                                    Ukuran:
                                                    <span class="font-medium">{{ $item->custom_size }}</span>
                                                </p>
                                            @endif

                                            @if ($item->selected_material)
                                                <p class="text-sm text-gray-600">
                                                    Material:
                                                    <span class="font-medium">{{ $item->selected_material }}</span>
                                                </p>
                                            @endif

                                            @if ($item->selected_finishing)
                                                <p class="text-sm text-gray-600">
                                                    Finishing:
                                                    <span class="font-medium">{{ $item->selected_finishing }}</span>
                                                </p>
                                            @endif

                                            @if ($item->requires_design_service)
                                                <div class="flex items-center mt-2">
                                                    <svg
                                                        class="w-4 h-4 text-purple-600 mr-1"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v1m0 0h6m-6 0V3m6 0a2 2 0 012 2v1M9 7h6m0 0v2M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7v10a2 2 0 002 2h2a2 2 0 002-2V7m-6 0h6"
                                                        ></path>
                                                    </svg>
                                                    <span class="text-xs text-purple-600 font-medium">
                                                        Memerlukan Jasa Design
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Shipping Info -->
                    @if ($order->tracking_number)
                        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                                <svg
                                    class="w-6 h-6 mr-3 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"
                                    ></path>
                                </svg>
                                Info Pengiriman
                            </h3>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Kurir & Layanan</p>
                                    <p class="font-semibold text-gray-900">{{ $order->shipping_service_display }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">No. Resi</p>
                                    <p class="font-mono font-semibold text-green-600 text-lg">
                                        {{ $order->tracking_number }}
                                    </p>
                                </div>
                            </div>

                            @if ($order->shipping_etd)
                                <div class="mt-4 p-3 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800">
                                        <strong>Estimasi Tiba:</strong>
                                        {{ $order->shipping_etd }} hari kerja
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info (Limited) -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                ></path>
                            </svg>
                            Info Penerima
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Kota Tujuan</p>
                                <p class="font-medium text-gray-900">
                                    {{ $order->shipping_city_name }}, {{ $order->shipping_province_name }}
                                </p>
                            </div>

                            @if ($order->shipping_district_name)
                                <div>
                                    <p class="text-sm text-gray-600">Kecamatan</p>
                                    <p class="font-medium text-gray-900">{{ $order->shipping_district_name }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                            <svg
                                class="w-5 h-5 mr-2 text-blue-600"
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
                            Status Pembayaran
                        </h3>

                        @php
                            $paymentConfig = [
                                'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800', 'border' => 'border-yellow-200'],
                                'verified' => ['bg' => 'bg-green-100', 'text' => 'text-green-800', 'border' => 'border-green-200'],
                                'rejected' => ['bg' => 'bg-red-100', 'text' => 'text-red-800', 'border' => 'border-red-200'],
                            ];
                            $paymentStyle = $paymentConfig[$order->payment_status] ?? $paymentConfig['pending'];
                        @endphp

                        <div
                            class="{{ $paymentStyle['bg'] }} {{ $paymentStyle['border'] }} {{ $paymentStyle['text'] }} border rounded-lg p-4 text-center"
                        >
                            <p class="font-semibold text-lg">{{ $order->payment_status_label }}</p>
                            @if ($order->payment_status === 'pending' && $order->status === 'pending_payment')
                                <p class="text-xs mt-1 opacity-75">Upload bukti pembayaran untuk melanjutkan</p>
                            @endif
                        </div>

                        @if ($order->payment_proof)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg border">
                                <p class="text-sm text-gray-700">
                                    <svg
                                        class="w-4 h-4 inline mr-1 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>
                                    Bukti pembayaran sudah diupload
                                </p>
                            </div>
                        @endif
                    </div>

                    <!-- Need Full Access Banner -->
                    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-6 text-white">
                        <div class="flex items-center mb-3">
                            <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2-2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"
                                ></path>
                            </svg>
                            <h3 class="font-bold text-lg">Butuh Akses Lebih?</h3>
                        </div>
                        <p class="text-blue-100 text-sm mb-4">
                            Untuk upload bukti pembayaran atau cancel order, verifikasi dengan email Anda.
                        </p>
                        <a
                            href="{{ route('orders.track.verify', $order->order_number) }}"
                            class="inline-flex items-center bg-white text-blue-600 hover:bg-blue-50 font-semibold py-2 px-4 rounded-lg transition-colors"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"
                                ></path>
                            </svg>
                            Verifikasi Email
                        </a>
                    </div>
                </div>
            </div>

            <!-- Security Notice -->
            <div class="mt-8 text-center">
                <div
                    class="inline-flex items-center text-xs text-gray-500 bg-white px-4 py-2 rounded-full border border-gray-200"
                >
                    <svg class="w-4 h-4 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                        ></path>
                    </svg>
                    Akses tracking aman dengan token terenkripsi
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom animations for tracking page */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse-slow {
            0%,
            100% {
                opacity: 1;
            }
            50% {
                opacity: 0.5;
            }
        }

        @keyframes spin-slow {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }

        .animate-spin-slow {
            animation: spin-slow 3s linear infinite;
        }

        .animate-pulse-slow {
            animation: pulse-slow 2s ease-in-out infinite;
        }

        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Hover effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            transition: transform 0.2s ease;
        }

        /* Progress line animation */
        .progress-line {
            position: relative;
            overflow: hidden;
        }

        .progress-line::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.6), transparent);
            animation: progress-shine 2s infinite;
        }

        @keyframes progress-shine {
            0% {
                left: -100%;
            }
            50%,
            100% {
                left: 100%;
            }
        }

        /* Mobile optimizations */
        @media (max-width: 640px) {
            .grid {
                gap: 1rem;
            }

            .lg\:col-span-2 {
                grid-column: span 1;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add fade-in animation to main content
            const mainContent = document.querySelector('.max-w-4xl');
            if (mainContent) {
                mainContent.classList.add('fade-in-up');
            }

            // Auto-refresh for active orders (every 2 minutes)
            @if(in_array($order->status, ['paid', 'processing', 'ready', 'shipped']))
                setInterval(function() {
                    // Only refresh if page is visible
                    if (!document.hidden) {
                        window.location.reload();
                    }
                }, 120000); // 2 minutes
            @endif

            // Add hover effects to interactive elements
            const interactiveElements = document.querySelectorAll('.hover\\:bg-gray-100, .hover\\:bg-blue-50');
            interactiveElements.forEach(el => {
                el.classList.add('hover-lift');
            });
        });
    </script>
@endsection
