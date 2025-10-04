<x-filament-panels::page>
    <div class="space-y-6">
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-shopping-bag class="w-5 h-5" />
                    <span>Daftar Pesanan</span>
                </div>
            </x-slot>

            @php
                $orders = $this->getOrders();
            @endphp

            @if ($orders->count() > 0)
                <div class="space-y-4">
                    @foreach ($orders as $order)
                        <div
                            class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6"
                        >
                            <div class="flex items-start justify-between mb-4">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                        {{ $order->order_number }}
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $order->created_at->format('d M Y, H:i') }} WIB
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <x-filament::badge
                                        :color="match($order->status) {
                                            'pending_payment' => 'warning',
                                            'paid' => 'info',
                                            'processing' => 'primary',
                                            'ready' => 'success',
                                            'shipped' => 'warning',
                                            'completed' => 'success',
                                            'cancelled' => 'danger',
                                            default => 'gray'
                                        }"
                                    >
                                        {{ $order->status_label }}
                                    </x-filament::badge>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Jumlah Item</p>
                                    <p class="text-base font-semibold text-gray-900 dark:text-white">
                                        {{ $order->items->count() }} item
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                                    <p class="text-base font-semibold text-success-600 dark:text-success-400">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                        Status Pembayaran
                                    </p>
                                    <x-filament::badge
                                        :color="match($order->payment_status) {
                                            'pending' => 'warning',
                                            'verified' => 'success',
                                            'rejected' => 'danger',
                                            default => 'gray'
                                        }"
                                        size="sm"
                                    >
                                        {{
                                            match ($order->payment_status) {
                                                'pending' => 'Menunggu',
                                                'verified' => 'Terverifikasi',
                                                'rejected' => 'Ditolak',
                                                default => $order->payment_status,
                                            }
                                        }}
                                    </x-filament::badge>
                                </div>
                            </div>

                            <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <x-filament::button
                                    tag="a"
                                    href="{{ route('public.track.index') }}?order={{ $order->order_number }}"
                                    color="success"
                                    size="sm"
                                >
                                    <x-heroicon-m-map-pin class="w-4 h-4 mr-1" />
                                    Lacak Pesanan
                                </x-filament::button>

                                @if ($order->status === 'pending_payment')
                                    {{-- Render Filament action button --}}
                                    {{ $this->uploadPaymentProofAction()->record($order)->arguments(['orderId' => $order->id]) }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if ($orders->hasPages())
                    <div class="mt-6">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="text-center py-12">
                    <x-heroicon-o-shopping-bag class="w-16 h-16 text-gray-400 mx-auto mb-4" />
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">Belum Ada Pesanan</h3>
                    <p class="text-gray-500 dark:text-gray-400 mb-6">
                        Anda belum memiliki pesanan. Mulai berbelanja sekarang!
                    </p>
                    <x-filament::button tag="a" href="{{ url('/') }}" color="primary">
                        <x-heroicon-m-home class="w-4 h-4 mr-2" />
                        Mulai Belanja
                    </x-filament::button>
                </div>
            @endif
        </x-filament::section>
    </div>
</x-filament-panels::page>
