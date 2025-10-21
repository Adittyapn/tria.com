@extends('layouts.app')

@section('title', 'Tracking Order - ' . $order->order_number)

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-emerald-50 via-blue-50 to-indigo-100 py-8 px-4">
        <div class="max-w-5xl mx-auto">
            <!-- Success Verification Banner -->
            <div
                class="mb-6 bg-gradient-to-r from-emerald-500 to-green-600 rounded-2xl shadow-lg p-4 sm:p-6 text-white"
            >
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"
                            ></path>
                        </svg>
                    </div>
                    <div class="ml-3 sm:ml-4">
                        <h2 class="text-base sm:text-lg font-bold">Email Terverifikasi</h2>
                        <p class="text-emerald-100 text-xs sm:text-sm">
                            Anda memiliki akses penuh ke order ini selama 1 jam
                        </p>
                    </div>
                </div>
            </div>

            <!-- Header Section -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full mb-4 shadow-lg"
                >
                    <svg
                        class="w-8 h-8 sm:w-10 sm:h-10 text-white"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2-2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                        ></path>
                    </svg>
                </div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">Tracking Order</h1>
                <p class="text-lg sm:text-xl font-mono font-semibold text-blue-600 mb-1 break-all px-4">
                    {{ $order->order_number }}
                </p>
                <p class="text-sm sm:text-base text-gray-600 px-4">
                    Dibuat {{ $order->created_at->format('d M Y, H:i') }} • Customer: {{ $order->customer->name }}
                </p>
            </div>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border border-green-200 rounded-xl p-3 sm:p-4">
                    <div class="flex items-start sm:items-center">
                        <svg
                            class="w-5 h-5 text-green-600 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0"
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
                        <span class="text-green-800 font-medium text-sm sm:text-base">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-3 sm:p-4">
                    <div class="flex items-start sm:items-center">
                        <svg
                            class="w-5 h-5 text-red-600 mr-2 sm:mr-3 flex-shrink-0 mt-0.5 sm:mt-0"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                            ></path>
                        </svg>
                        <span class="text-red-800 font-medium text-sm sm:text-base">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

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

                <div class="{{ $currentStatus['bg'] }} border {{ $currentStatus['text'] }} rounded-xl p-4 sm:p-6">
                    <div class="flex flex-col md:flex-row md:items-start md:gap-6 lg:gap-8">
                        <div class="flex items-center md:w-1/2 lg:w-2/5">
                            <!-- Icon -->

                            @switch($currentStatus['icon'])
                                @case('clock')
                                    <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                        ></path>
                                    </svg>

                                    @break
                                @case('check-circle')
                                    <svg class="w-8 h-8 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                        class="w-8 h-8 mr-4 animate-spin"
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
                                    <!-- Tambahkan icon lain sesuai sebelumnya -->
                            @endswitch

                            <div class="w-full">
                                <h2 class="text-xl sm:text-2xl font-bold">{{ $order->status_label }}</h2>
                                <p class="text-sm sm:text-base opacity-90 mt-1">
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

                                <!-- Info Bank Jika Pending Payment -->
                                @if ($order->status === 'pending_payment' && ! $order->payment_proof)
                                    <div
                                        class="mt-4 md:mt-0 md:mb-0 md:mr-6 lg:mr-8 p-3 sm:p-4 bg-gray-50 border border-gray-200 rounded-lg w-full md:w-auto"
                                    >
                                        <h3 class="font-semibold text-base sm:text-lg mb-2">Informasi Transfer</h3>
                                        <div class="text-sm sm:text-base opacity-90 space-y-1">
                                            <p>
                                                <span class="text-gray-600">Bank:</span>
                                                <span class="font-medium">{{ $order->bank_name ?? 'BCA' }}</span>
                                            </p>
                                            <p>
                                                <span class="text-gray-600">Nomor Rekening:</span>
                                                <span class="font-medium font-mono">
                                                    {{ $order->bank_account ?? '123-456-7890' }}
                                                </span>
                                            </p>
                                            <p>
                                                <span class="text-gray-600">Atas Nama:</span>
                                                <span class="font-medium">
                                                    {{ $order->account_holder ?? 'PT Contoh Nama' }}
                                                </span>
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div
                            class="flex flex-col gap-3 w-full md:w-1/2 lg:w-3/5 md:justify-center md:items-stretch md:pl-4"
                        >
                            <!-- Download Invoice Button - Only show when payment is verified -->
                            @if ($order->payment_status === 'verified')
                                <a
                                    href="{{ route('orders.download-invoice', $order->order_number) }}"
                                    target="_blank"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center text-sm sm:text-base"
                                >
                                    <svg
                                        class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                        ></path>
                                    </svg>
                                    <span class="whitespace-nowrap">Download Invoice</span>
                                </a>
                            @endif

                            @if ($order->status === 'pending_payment' && ! $order->payment_proof)
                                <button
                                    onclick="openUploadModal()"
                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center text-sm sm:text-base"
                                >
                                    <svg
                                        class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"
                                        ></path>
                                    </svg>
                                    <span class="whitespace-nowrap">Upload Bukti Bayar</span>
                                </button>

                                <!-- QRIS payment button -->
                                <button
                                    onclick="openQrisModal()"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center text-sm sm:text-base"
                                >
                                    <svg
                                        class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 7h4v10H3zM17 7h4v10h-4zM9 7h6v10H9z"
                                        ></path>
                                    </svg>
                                    <span class="whitespace-nowrap">Bayar via QRIS</span>
                                </button>
                            @endif

                            @if ($order->status === 'pending_payment' && ! $order->payment_proof)
                                <button
                                    onclick="openCancelModal()"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 sm:py-3 px-4 sm:px-6 rounded-xl transition-colors shadow-lg hover:shadow-xl transform hover:-translate-y-1 flex items-center justify-center text-sm sm:text-base"
                                >
                                    <svg
                                        class="w-4 h-4 sm:w-5 sm:h-5 mr-2 flex-shrink-0"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        ></path>
                                    </svg>
                                    <span class="whitespace-nowrap">Batalkan Order</span>
                                </button>
                            @endif
                        </div>
                    </div>
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
                                <x-orders.item :item="$item" />
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

                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">Kurir & Layanan</p>
                                    <p class="font-semibold text-gray-900 text-lg">
                                        {{ $order->shipping_service_display }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 mb-1">No. Resi</p>
                                    <p class="font-mono font-semibold text-green-600 text-lg">
                                        {{ $order->tracking_number }}
                                    </p>
                                </div>
                            </div>

                            @if ($order->shipping_etd)
                                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                                    <p class="text-sm text-green-800">
                                        <strong>Estimasi Tiba:</strong>
                                        {{ $order->shipping_etd }} hari kerja
                                    </p>
                                </div>
                            @endif

                            @if ($order->shipped_at)
                                <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                    <p class="text-sm text-blue-800">
                                        <strong>Tanggal Kirim:</strong>
                                        {{ $order->shipped_at->format('d M Y, H:i') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Customer Info -->
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
                            Info Customer
                        </h3>

                        <div class="space-y-3">
                            <div>
                                <p class="text-sm text-gray-600">Nama Lengkap</p>
                                <p class="font-medium text-gray-900">{{ $order->customer->name }}</p>
                            </div>

                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-medium text-gray-900">{{ $order->customer->email }}</p>
                            </div>

                            @if ($order->customer->phone)
                                <div>
                                    <p class="text-sm text-gray-600">Telepon</p>
                                    <p class="font-medium text-gray-900">{{ $order->customer->phone }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Shipping Address -->
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
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"
                                ></path>
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"
                                ></path>
                            </svg>
                            Alamat Pengiriman
                        </h3>

                        <div class="text-gray-900">
                            <p class="leading-relaxed">{{ $order->full_shipping_address }}</p>
                        </div>
                    </div>

                    <!-- Order Summary -->
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
                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"
                                ></path>
                            </svg>
                            Ringkasan Order
                        </h3>

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

                            <hr class="border-gray-200" />

                            <div class="flex justify-between text-lg font-bold">
                                <span class="text-gray-900">Total</span>
                                <span class="text-blue-600">
                                    Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                </span>
                            </div>
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
                        </div>

                        @if ($order->payment_proof)
                            <div class="mt-4 p-3 bg-gray-50 rounded-lg border">
                                <p class="text-sm text-gray-700 flex items-center">
                                    <svg
                                        class="w-4 h-4 mr-2 text-green-600"
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
                </div>
            </div>
        </div>
    </div>

    @include('orders.partials.upload-payment-modal')
    @include('orders.partials.cancel-order-modal')
    @include('orders.partials.qris-payment-modal')

    <style>
        /* Modal animations */
        .modal-enter {
            animation: modalFadeIn 0.3s ease-out;
        }

        .modal-leave {
            animation: modalFadeOut 0.3s ease-out;
        }

        @keyframes modalFadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes modalFadeOut {
            from {
                opacity: 1;
                transform: scale(1);
            }
            to {
                opacity: 0;
                transform: scale(0.9);
            }
        }

        /* File upload styles */
        .file-drag-over {
            border-color: #3b82f6;
            background-color: #eff6ff;
        }

        /* Loading states */
        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .btn-loading::after {
            content: '';
            width: 16px;
            height: 16px;
            margin-left: 8px;
            border: 2px solid transparent;
            border-top-color: #ffffff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(360deg);
            }
        }

        /* Success animations */
        @keyframes checkmark {
            0% {
                transform: scale(0);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }

        .animate-checkmark {
            animation: checkmark 0.6s ease-out;
        }
    </style>

    <script>
        // Modal Management
        function openUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'modal-enter');
            document.body.classList.add('overflow-hidden');
        }

        function closeUploadModal() {
            const modal = document.getElementById('uploadModal');
            modal.classList.add('modal-leave');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'modal-enter', 'modal-leave');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        function openCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'modal-enter');
            document.body.classList.add('overflow-hidden');
        }

        function closeCancelModal() {
            const modal = document.getElementById('cancelModal');
            modal.classList.add('modal-leave');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'modal-enter', 'modal-leave');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        function openQrisModal() {
            const modal = document.getElementById('qrisModal');
            if (!modal) return;
            modal.classList.remove('hidden');
            modal.classList.add('flex', 'modal-enter');
            document.body.classList.add('overflow-hidden');
        }

        function closeQrisModal() {
            const modal = document.getElementById('qrisModal');
            if (!modal) return;
            modal.classList.add('modal-leave');
            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'modal-enter', 'modal-leave');
                document.body.classList.remove('overflow-hidden');
            }, 300);
        }

        // File Upload Enhancement
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('payment_proof');
            const fileNameDiv = document.getElementById('fileName');
            const dropArea = fileInput.closest('.border-dashed');

            // File selection handler
            fileInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    fileNameDiv.textContent = `📄 ${file.name} (${formatFileSize(file.size)})`;
                    fileNameDiv.classList.remove('hidden');
                    dropArea.classList.add('border-blue-300', 'bg-blue-50');
                }
            });

            // Drag and drop
            dropArea.addEventListener('dragover', function(e) {
                e.preventDefault();
                dropArea.classList.add('file-drag-over');
            });

            dropArea.addEventListener('dragleave', function(e) {
                e.preventDefault();
                dropArea.classList.remove('file-drag-over');
            });

            dropArea.addEventListener('drop', function(e) {
                e.preventDefault();
                dropArea.classList.remove('file-drag-over');

                const files = e.dataTransfer.files;
                if (files.length > 0) {
                    fileInput.files = files;
                    fileInput.dispatchEvent(new Event('change'));
                }
            });

            // Form submissions with loading states
            const uploadForm = document.getElementById('uploadForm');
            const cancelForm = document.getElementById('cancelForm');

            uploadForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.classList.add('btn-loading');
                submitBtn.textContent = 'Mengupload...';
            });

            cancelForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.classList.add('btn-loading');
                submitBtn.textContent = 'Membatalkan...';
            });

            // Close modal on outside click
            document.getElementById('uploadModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeUploadModal();
                }
            });

            document.getElementById('cancelModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeCancelModal();
                }
            });

            // QRIS modal outside click
            const qrisModalEl = document.getElementById('qrisModal');
            if (qrisModalEl) {
                qrisModalEl.addEventListener('click', function(e) {
                    if (e.target === this) {
                        closeQrisModal();
                    }
                });
            }

            // Keyboard shortcuts
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeUploadModal();
                    closeCancelModal();
                    closeQrisModal();
                }
            });

            // Auto-refresh for active orders (every 2 minutes)
            @if(in_array($order->status, ['paid', 'processing', 'ready', 'shipped']))
                setInterval(function() {
                    if (!document.hidden) {
                        // Don't refresh if modal is open
                        const modalsOpen = !document.getElementById('uploadModal').classList.contains('hidden') ||
                                          !document.getElementById('cancelModal').classList.contains('hidden') ||
                                          (document.getElementById('qrisModal') && !document.getElementById('qrisModal').classList.contains('hidden'));
                        if (!modalsOpen) {
                            window.location.reload();
                        }
                    }
                }, 120000); // 2 minutes
            @endif
        });

        // Utility function
        function formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
        }

        // Success animation trigger
        @if(session('success'))
            setTimeout(function() {
                const successIcon = document.querySelector('.text-green-600');
                if (successIcon) {
                    successIcon.classList.add('animate-checkmark');
                }
            }, 100);
        @endif
    </script>
@endsection
