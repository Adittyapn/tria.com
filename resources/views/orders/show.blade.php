@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
        <div class="container mx-auto px-4 max-w-6xl">
            <!-- Header Section -->
            <div class="mb-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <nav class="text-sm text-gray-600 mb-2">
                            <a href="{{ route('orders.index') }}" class="hover:text-blue-600 transition-colors">
                                Pesanan Saya
                            </a>
                            <span class="mx-2">></span>
                            <span class="text-gray-800 font-medium">#{{ $order->order_number }}</span>
                        </nav>
                        <h1 class="text-3xl font-bold text-gray-900">
                            Detail Pesanan
                            <span class="text-blue-600">#{{ $order->order_number }}</span>
                        </h1>
                        <p class="text-gray-600 mt-1">Dibuat {{ $order->created_at->format('d M Y H:i') }} WIB</p>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex flex-col items-end gap-2">
                        <!-- Download Invoice Button - Only show when payment is verified -->
                        @if ($order->payment_status === 'verified')
                            <a
                                href="{{ route('orders.download-invoice', $order->order_number) }}"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors shadow-sm hover:shadow-md"
                                target="_blank"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                    />
                                </svg>
                                Download Invoice
                            </a>
                        @endif

                        <div class="flex items-center gap-3">
                            @php
                                $statusColors = [
                                    'pending_payment' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                    'paid' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'processing' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'ready' => 'bg-cyan-100 text-cyan-800 border-cyan-200',
                                    'shipped' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                    'completed' => 'bg-green-100 text-green-800 border-green-200',
                                    'cancelled' => 'bg-red-100 text-red-800 border-red-200',
                                ];
                                $statusIcons = [
                                    'pending_payment' => 'clock',
                                    'paid' => 'check-circle',
                                    'processing' => 'cog',
                                    'ready' => 'package',
                                    'shipped' => 'truck',
                                    'completed' => 'check-double',
                                    'cancelled' => 'x-circle',
                                ];
                            @endphp

                            <div
                                class="px-4 py-2 rounded-full border {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}"
                            >
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if ($order->status === 'pending_payment')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        @elseif ($order->status === 'paid' || $order->status === 'completed')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        @elseif ($order->status === 'processing')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                            />
                                        @elseif ($order->status === 'shipped')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                            />
                                        @elseif ($order->status === 'ready')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                            />
                                        @elseif ($order->status === 'cancelled')
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                            />
                                        @endif
                                    </svg>
                                    <span class="font-semibold">{{ $order->status_label }}</span>
                                </div>
                            </div>

                            @if ($order->payment_status)
                                @php
                                    $paymentColors = [
                                        'pending' => 'bg-orange-100 text-orange-800 border-orange-200',
                                        'verified' => 'bg-green-100 text-green-800 border-green-200',
                                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                    ];
                                @endphp

                                <div
                                    class="px-3 py-1 rounded-full border text-sm {{ $paymentColors[$order->payment_status] ?? 'bg-gray-100 text-gray-800 border-gray-200' }}"
                                >
                                    {{ $order->payment_status_label }}
                                </div>
                            @endif
                        </div>

                        <!-- Tracking Number (if shipped) -->
                        @if ($order->tracking_number)
                            <div class="text-sm text-gray-600">
                                <span class="font-medium">Resi:</span>
                                {{ $order->tracking_number }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="xl:col-span-2 space-y-6">
                    <!-- Order Progress Timeline -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            Status Pesanan
                        </h2>

                        <div class="relative">
                            @php
                                $trackingSteps = [
                                    'pending_payment' => [
                                        'label' => 'Menunggu Pembayaran',
                                        'description' => 'Pesanan dibuat, menunggu konfirmasi pembayaran',
                                        'icon' => 'clock',
                                        'date' => $order->created_at,
                                    ],
                                    'paid' => [
                                        'label' => 'Pembayaran Dikonfirmasi',
                                        'description' => 'Pembayaran telah dikonfirmasi, pesanan masuk antrian produksi',
                                        'icon' => 'check-circle',
                                        'date' => $order->payment_status === 'verified' ? $order->updated_at : null,
                                    ],
                                    'processing' => [
                                        'label' => 'Sedang Produksi',
                                        'description' => 'Pesanan sedang dalam proses produksi',
                                        'icon' => 'cog',
                                        'date' => $order->status === 'processing' ? $order->updated_at : null,
                                    ],
                                    'ready' => [
                                        'label' => 'Siap Kirim',
                                        'description' => 'Produksi selesai, pesanan siap untuk dikirim',
                                        'icon' => 'package',
                                        'date' => $order->status === 'ready' ? $order->updated_at : null,
                                    ],
                                    'shipped' => [
                                        'label' => 'Dalam Pengiriman',
                                        'description' => 'Pesanan sedang dalam perjalanan',
                                        'icon' => 'truck',
                                        'date' => $order->shipped_at,
                                    ],
                                    'completed' => [
                                        'label' => 'Selesai',
                                        'description' => 'Pesanan telah selesai dan diterima',
                                        'icon' => 'check-double',
                                        'date' => $order->delivered_at,
                                    ],
                                ];

                                $statusOrder = ['pending_payment', 'paid', 'processing', 'ready', 'shipped', 'completed'];
                                $currentIndex = array_search($order->status, $statusOrder);

                                if ($order->status === 'cancelled') {
                                    $trackingSteps = [
                                        'pending_payment' => $trackingSteps['pending_payment'],
                                        'cancelled' => [
                                            'label' => 'Pesanan Dibatalkan',
                                            'description' => 'Pesanan telah dibatalkan',
                                            'icon' => 'x-circle',
                                            'date' => $order->updated_at,
                                            'is_cancelled' => true,
                                        ],
                                    ];
                                    $currentIndex = 1;
                                    $statusOrder = ['pending_payment', 'cancelled'];
                                }
                            @endphp

                            @foreach ($trackingSteps as $stepKey => $step)
                                @php
                                    $stepIndex = array_search($stepKey, $statusOrder);
                                    $isCompleted = $stepIndex <= $currentIndex;
                                    $isActive = $stepIndex === $currentIndex;
                                    $isCancelled = isset($step['is_cancelled']);
                                @endphp

                                <div class="flex items-start gap-4 {{ ! $loop->last ? 'pb-8' : '' }}">
                                    <!-- Timeline Line -->
                                    @if (! $loop->last)
                                        <div
                                            class="absolute left-6 top-12 w-0.5 h-16 {{ $isCompleted ? 'bg-blue-500' : 'bg-gray-200' }} -ml-0.5"
                                        ></div>
                                    @endif

                                    <!-- Step Icon -->
                                    <div
                                        class="relative z-10 w-12 h-12 rounded-full flex items-center justify-center border-2 {{
                                            $isCancelled
                                                ? 'bg-red-500 border-red-500'
                                                : ($isCompleted
                                                    ? 'bg-blue-500 border-blue-500'
                                                    : 'bg-white border-gray-200')
                                        }}"
                                    >
                                        <svg
                                            class="w-5 h-5 {{ $isCancelled || $isCompleted ? 'text-white' : 'text-gray-400' }}"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            @if ($step['icon'] === 'clock')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            @elseif ($step['icon'] === 'check-circle')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            @elseif ($step['icon'] === 'cog')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                                                />
                                            @elseif ($step['icon'] === 'package')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"
                                                />
                                            @elseif ($step['icon'] === 'truck')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                                />
                                            @elseif ($step['icon'] === 'check-double')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            @elseif ($step['icon'] === 'x-circle')
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    stroke-width="2"
                                                    d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                                />
                                            @endif
                                        </svg>
                                    </div>

                                    <!-- Step Content -->
                                    <div class="flex-1 pt-1">
                                        <div class="flex items-center justify-between">
                                            <h3
                                                class="font-semibold text-gray-900 {{ $isActive ? 'text-blue-600' : '' }}"
                                            >
                                                {{ $step['label'] }}
                                            </h3>
                                            @if ($step['date'])
                                                <span class="text-sm text-gray-500">
                                                    {{ $step['date']->format('d M Y H:i') }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 text-sm mt-1">{{ $step['description'] }}</p>

                                        @if ($stepKey === 'shipped' && $order->tracking_number)
                                            <div class="mt-3 p-3 bg-blue-50 rounded-lg border border-blue-100">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <p class="text-sm font-medium text-blue-900">Nomor Resi</p>
                                                        <p class="text-lg font-mono text-blue-600">
                                                            {{ $order->tracking_number }}
                                                        </p>
                                                    </div>
                                                    <button
                                                        class="text-blue-600 hover:bg-blue-100 p-2 rounded-lg transition-colors"
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
                                                                d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                                            />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-green-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                    />
                                </svg>
                            </div>
                            Item Pesanan ({{ $order->items->count() }} item)
                        </h2>

                        <div class="space-y-4">
                            @foreach ($order->items as $item)
                                <div class="border border-gray-200 rounded-xl p-4 hover:shadow-md transition-shadow">
                                    <div class="flex gap-4">
                                        <!-- Product Image -->
                                        <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                            @if ($item->product->image_url)
                                                <img
                                                    src="{{ $item->product->image_url }}"
                                                    alt="{{ $item->product->name }}"
                                                    class="w-full h-full object-cover"
                                                />
                                            @else
                                                <div
                                                    class="w-full h-full flex items-center justify-center text-gray-400"
                                                >
                                                    <svg
                                                        class="w-8 h-8"
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
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-semibold text-gray-900">
                                                        {{ $item->product->name }}
                                                    </h3>
                                                    <p class="text-gray-600 text-sm">{{ $item->product->category }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="font-bold text-gray-900">
                                                        Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        {{ $item->quantity }} × Rp
                                                        {{ number_format($item->price, 0, ',', '.') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <!-- Custom Specifications -->
                                            @if ($item->custom_size_width || $item->custom_size_height || $item->selected_material || $item->selected_finishing)
                                                <div class="mt-3 space-y-2">
                                                    @if ($item->custom_size)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <svg
                                                                class="w-4 h-4 text-gray-400"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"
                                                                />
                                                            </svg>
                                                            <span class="text-gray-600">
                                                                Ukuran: {{ $item->custom_size }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($item->selected_material)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <svg
                                                                class="w-4 h-4 text-gray-400"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"
                                                                />
                                                            </svg>
                                                            <span class="text-gray-600">
                                                                Material: {{ $item->selected_material }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($item->selected_finishing)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <svg
                                                                class="w-4 h-4 text-gray-400"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"
                                                                />
                                                            </svg>
                                                            <span class="text-gray-600">
                                                                Finishing: {{ $item->selected_finishing }}
                                                            </span>
                                                        </div>
                                                    @endif

                                                    @if ($item->requires_design_service)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            <svg
                                                                class="w-4 h-4 text-purple-500"
                                                                fill="none"
                                                                stroke="currentColor"
                                                                viewBox="0 0 24 24"
                                                            >
                                                                <path
                                                                    stroke-linecap="round"
                                                                    stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"
                                                                />
                                                            </svg>
                                                            <span class="text-purple-600 font-medium">
                                                                + Jasa Design
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif

                                            <!-- Design Notes -->
                                            @if ($item->design_notes)
                                                <div class="mt-3 p-3 bg-gray-50 rounded-lg">
                                                    <p class="text-sm text-gray-600">
                                                        <span class="font-medium">Catatan Design:</span>
                                                        {{ $item->design_notes }}
                                                    </p>
                                                </div>
                                            @endif

                                            <!-- Design File Download -->
                                            @if ($item->design_file_path)
                                                <div class="mt-3">
                                                    <a
                                                        href="{{ route('orders.download-design', ['orderNumber' => $order->order_number, 'item' => $item->id]) }}"
                                                        class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors"
                                                    >
                                                        <svg
                                                            class="w-4 h-4"
                                                            fill="none"
                                                            stroke="currentColor"
                                                            viewBox="0 0 24 24"
                                                        >
                                                            <path
                                                                stroke-linecap="round"
                                                                stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                                            />
                                                        </svg>
                                                        Download File Design
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Order Notes & History -->
                    @if ($order->notes)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg
                                        class="w-5 h-5 text-gray-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                </div>
                                Catatan & Riwayat
                            </h2>
                            <div class="bg-gray-50 rounded-lg p-4">
                                <pre class="whitespace-pre-wrap text-sm text-gray-700 font-mono">
{{ $order->notes }}</pre
                                >
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Order Summary -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-blue-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                    />
                                </svg>
                            </div>
                            Ringkasan Pembayaran
                        </h2>

                        <div class="space-y-3">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Subtotal Item</span>
                                <span class="font-medium">
                                    Rp {{ number_format($order->subtotal_items, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Ongkos Kirim</span>
                                <span class="font-medium">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">PPN (11%)</span>
                                <span class="font-medium">
                                    Rp {{ number_format($order->tax_amount, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="border-t border-gray-200 pt-3">
                                <div class="flex justify-between">
                                    <span class="font-bold text-gray-900">Total</span>
                                    <span class="font-bold text-xl text-blue-600">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-purple-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"
                                    />
                                </svg>
                            </div>
                            Informasi Customer
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Nama</p>
                                <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium text-gray-900">{{ $order->customer->email }}</p>
                            </div>
                            @if ($order->customer->phone)
                                <div>
                                    <p class="text-sm text-gray-500">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $order->customer->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Information -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center gap-3">
                            <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-orange-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                    />
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                    />
                                </svg>
                            </div>
                            Informasi Pengiriman
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <p class="text-sm text-gray-500">Alamat Lengkap</p>
                                <p class="font-medium text-gray-900 leading-relaxed">
                                    {{ $order->full_shipping_address }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Layanan Pengiriman</p>
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <svg
                                            class="w-4 h-4 text-blue-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
                                            />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $order->shipping_service_display }}</p>
                                        @if ($order->shipping_etd)
                                            <p class="text-xs text-gray-500">Estimasi: {{ $order->shipping_etd }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Biaya Pengiriman</p>
                                <p class="font-bold text-gray-900">
                                    Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if ($order->status === 'pending_payment' && ! $order->payment_proof)
                        <div
                            class="bg-gradient-to-r from-yellow-50 to-orange-50 rounded-2xl border border-yellow-200 p-6"
                        >
                            <h2 class="text-lg font-bold text-yellow-800 mb-4 flex items-center gap-3">
                                <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <svg
                                        class="w-5 h-5 text-yellow-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z"
                                        />
                                    </svg>
                                </div>
                                Menunggu Pembayaran
                            </h2>
                            <p class="text-yellow-700 mb-4 leading-relaxed">
                                Silakan transfer sejumlah
                                <strong>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</strong>
                                ke rekening kami dan upload bukti pembayaran untuk konfirmasi.
                            </p>

                            <!-- Bank Details -->
                            <div class="bg-white rounded-xl p-4 mb-4 border border-yellow-200">
                                <h4 class="font-bold text-gray-900 mb-3">Informasi Transfer:</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Bank</span>
                                        <span class="font-medium">BCA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">No. Rekening</span>
                                        <span class="font-mono font-medium">1234567890</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Atas Nama</span>
                                        <span class="font-medium">PT Digital Print Indonesia</span>
                                    </div>
                                    <div class="flex justify-between border-t border-gray-200 pt-2 mt-3">
                                        <span class="text-gray-900 font-semibold">Jumlah Transfer</span>
                                        <span class="font-bold text-lg text-yellow-800">
                                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Upload Payment Proof Form -->
                            <form id="payment-upload-form" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Bukti Pembayaran
                                    </label>
                                    <input
                                        type="file"
                                        name="payment_proof"
                                        id="payment_proof"
                                        accept=".jpg,.jpeg,.png,.pdf"
                                        class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors"
                                    />
                                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, PDF (Max 5MB)</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Catatan (Opsional)
                                    </label>
                                    <textarea
                                        name="payment_notes"
                                        rows="3"
                                        class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 text-sm"
                                        placeholder="Catatan tambahan untuk pembayaran..."
                                    ></textarea>
                                </div>
                                <button
                                    type="submit"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        />
                                    </svg>
                                    Upload Bukti Pembayaran
                                </button>
                            </form>
                        </div>
                    @elseif ($order->payment_proof)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg
                                        class="w-5 h-5 text-green-600"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                </div>
                                Bukti Pembayaran
                            </h2>
                            <div class="space-y-3">
                                <div
                                    class="flex items-center justify-between p-3 bg-green-50 rounded-lg border border-green-200"
                                >
                                    <div class="flex items-center gap-3">
                                        <svg
                                            class="w-5 h-5 text-green-600"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                            />
                                        </svg>
                                        <span class="text-green-800 font-medium">Bukti pembayaran telah diupload</span>
                                    </div>
                                </div>
                                @if ($order->payment_status === 'verified')
                                    <p class="text-green-600 text-sm">✅ Pembayaran telah diverifikasi</p>
                                @else
                                    <p class="text-orange-600 text-sm">⏱️ Menunggu verifikasi (1-3 jam kerja)</p>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4">Aksi</h2>
                        <div class="space-y-3">
                            <!-- Share Tracking Link -->
                            @if ($order->hasSecureTracking())
                                <button
                                    onclick="copyTrackingLink()"
                                    class="w-full bg-blue-50 hover:bg-blue-100 text-blue-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"
                                        />
                                    </svg>
                                    Copy Link Tracking
                                </button>
                            @endif

                            <!-- Download Invoice - Only show when payment is verified -->
                            @if ($order->payment_status === 'verified')
                                <a
                                    href="{{ route('orders.download-invoice', $order->order_number) }}"
                                    target="_blank"
                                    class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        />
                                    </svg>
                                    Download Invoice
                                </a>
                            @endif

                            <!-- Print Order -->
                            <button
                                onclick="window.print()"
                                class="w-full bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"
                                    />
                                </svg>
                                Print Pesanan
                            </button>

                            <!-- Cancel Order (if applicable) -->
                            @if (in_array($order->status, ['pending_payment', 'paid']))
                                <button
                                    onclick="showCancelModal()"
                                    class="w-full bg-red-50 hover:bg-red-100 text-red-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"
                                        />
                                    </svg>
                                    Batalkan Pesanan
                                </button>
                            @endif

                            <!-- Contact Support -->
                            <a
                                href="https://wa.me/6281234567890?text=Halo,%20saya%20ingin%20menanyakan%20pesanan%20{{ $order->order_number }}"
                                target="_blank"
                                class="w-full bg-green-50 hover:bg-green-100 text-green-700 font-medium py-2.5 px-4 rounded-lg transition-colors flex items-center justify-center gap-2"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"
                                    />
                                </svg>
                                Hubungi Customer Service
                            </a>
                        </div>
                    </div>

                    <!-- Order Timeline (Extended) -->
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h2 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-3">
                            <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <svg
                                    class="w-5 h-5 text-indigo-600"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                            </div>
                            Riwayat Pesanan
                        </h2>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between py-2 border-b border-gray-100">
                                <span class="text-gray-600">Pesanan dibuat</span>
                                <span class="font-medium">{{ $order->created_at->format('d M Y H:i') }}</span>
                            </div>
                            @if ($order->payment_proof)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Bukti pembayaran diupload</span>
                                    <span class="font-medium">{{ $order->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif

                            @if ($order->shipped_at)
                                <div class="flex justify-between py-2 border-b border-gray-100">
                                    <span class="text-gray-600">Pesanan dikirim</span>
                                    <span class="font-medium">{{ $order->shipped_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif

                            @if ($order->delivered_at)
                                <div class="flex justify-between py-2">
                                    <span class="text-gray-600">Pesanan diterima</span>
                                    <span class="font-medium">{{ $order->delivered_at->format('d M Y H:i') }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal -->
    <div
        id="cancelModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 items-center justify-center p-4"
        style="display: none"
    >
        <div class="bg-white rounded-2xl p-6 w-full max-w-md">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Batalkan Pesanan</h3>
            <p class="text-gray-600 mb-6">
                Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.
            </p>

            <form id="cancel-order-form">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan</label>
                    <textarea
                        name="cancellation_reason"
                        required
                        rows="3"
                        class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 text-sm"
                        placeholder="Mohon jelaskan alasan pembatalan..."
                    ></textarea>
                </div>

                <div class="flex gap-3">
                    <button
                        type="button"
                        onclick="hideCancelModal()"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2.5 px-4 rounded-lg transition-colors"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors"
                    >
                        Ya, Batalkan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div
            id="success-message"
            class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"
        >
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div id="error-message" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Payment Upload Form Handler
        document.getElementById('payment-upload-form')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            // Loading state
            button.disabled = true;
            button.innerHTML = `
        <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Mengupload...
    `;

            try {
                const response = await fetch(`{{ route('orders.upload-payment', $order->order_number) }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showMessage(result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                showMessage('Terjadi kesalahan jaringan', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });

        // Cancel Order Form Handler
        document.getElementById('cancel-order-form')?.addEventListener('submit', async function (e) {
            e.preventDefault();

            const formData = new FormData(this);
            const button = this.querySelector('button[type="submit"]');
            const originalText = button.innerHTML;

            button.disabled = true;
            button.innerHTML = 'Membatalkan...';

            try {
                const response = await fetch(`{{ route('orders.cancel', $order->order_number) }}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                });

                const result = await response.json();

                if (result.success) {
                    showMessage(result.message, 'success');
                    hideCancelModal();
                    setTimeout(() => window.location.reload(), 2000);
                } else {
                    showMessage(result.message || 'Terjadi kesalahan', 'error');
                }
            } catch (error) {
                showMessage('Terjadi kesalahan jaringan', 'error');
            } finally {
                button.disabled = false;
                button.innerHTML = originalText;
            }
        });

        // Copy Tracking Link
        function copyTrackingLink() {
            const trackingUrl = `{{ $order->getSecureTrackingUrl() }}`;
            navigator.clipboard
                .writeText(trackingUrl)
                .then(() => {
                    showMessage('Link tracking berhasil disalin!', 'success');
                })
                .catch(() => {
                    showMessage('Gagal menyalin link', 'error');
                });
        }

        // Modal Controls
        function showCancelModal() {
            document.getElementById('cancelModal').classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function hideCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        // Close modal on backdrop click
        document.getElementById('cancelModal')?.addEventListener('click', function (e) {
            if (e.target === this) {
                hideCancelModal();
            }
        });

        // Message Display
        function showMessage(message, type) {
            // Remove existing messages
            const existing = document.querySelectorAll('#success-message, #error-message');
            existing.forEach((el) => el.remove());

            const messageEl = document.createElement('div');
            messageEl.id = type + '-message';
            messageEl.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            messageEl.textContent = message;

            document.body.appendChild(messageEl);

            // Auto remove after 5 seconds
            setTimeout(() => {
                messageEl.remove();
            }, 5000);
        }

        // Auto-hide session messages
        setTimeout(() => {
            const sessionMessages = document.querySelectorAll('#success-message, #error-message');
            sessionMessages.forEach((el) => {
                el.style.opacity = '0';
                el.style.transform = 'translateX(100%)';
                setTimeout(() => el.remove(), 300);
            });
        }, 5000);

        // Print Styles
        const printStyles = `
    @media print {
        body { font-size: 12pt; }
        .no-print { display: none !important; }
        .bg-gradient-to-br { background: white !important; }
        .shadow-sm { box-shadow: none !important; }
        .border { border: 1px solid #ddd !important; }
        .rounded-2xl { border-radius: 8px !important; }
    }
`;

        if (!document.querySelector('#print-styles')) {
            const style = document.createElement('style');
            style.id = 'print-styles';
            style.textContent = printStyles;
            document.head.appendChild(style);
        }
    </script>

    <style>
        /* Custom scrollbar for timeline */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }
        .overflow-y-auto::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 6px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 6px;
        }
        .overflow-y-auto::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Animation for messages */
        #success-message,
        #error-message {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        /* Print specific styles */
        @media print {
            .no-print {
                display: none !important;
            }

            .print-break-before {
                page-break-before: always;
            }

            .print-break-inside-avoid {
                page-break-inside: avoid;
            }

            /* Ensure proper contrast in print */
            .text-gray-600 {
                color: #374151 !important;
            }

            .text-blue-600 {
                color: #2563eb !important;
            }
        }

        /* Enhanced hover effects */
        .hover-lift {
            transition:
                transform 0.2s ease,
                box-shadow 0.2s ease;
        }

        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Modal animation */
        #cancelModal {
            animation: fadeIn 0.2s ease-out;
        }

        #cancelModal > div {
            animation: slideUp 0.2s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
@endsection
