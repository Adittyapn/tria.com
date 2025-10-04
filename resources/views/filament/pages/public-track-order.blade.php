<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Search Form --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-2">
                    <x-heroicon-m-magnifying-glass class="w-5 h-5" />
                    <span>Cari Pesanan</span>
                </div>
            </x-slot>

            <form wire:submit="trackOrder">
                {{ $this->form }}

                <div class="mt-4">
                    <x-filament::button type="submit" size="lg" class="w-full md:w-auto">
                        <x-heroicon-m-magnifying-glass class="w-5 h-5 mr-2" />
                        Lacak Pesanan
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Tracking Result --}}
        @if ($showTracking && $order)
            {{-- Order Info Card --}}
            <x-filament::section>
                <x-slot name="heading">Detail Pesanan</x-slot>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Nomor Pesanan</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">{{ $order->order_number }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Tanggal Pesanan</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mt-1">
                            {{ $order->created_at->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Pembayaran</p>
                        <p class="text-lg font-semibold text-success-600 dark:text-success-400 mt-1">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</p>
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
                            class="mt-1"
                        >
                            {{ $order->status_label }}
                        </x-filament::badge>
                    </div>
                </div>

                {{-- Customer Info --}}
                <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Informasi Pemesan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Nama</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $order->customer->name }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Email</p>
                            <p class="font-semibold text-gray-900 dark:text-white">
                                {{ $order->customer->email }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Shipping Info --}}
                @if ($order->tracking_number)
                    <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Informasi Pengiriman
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Kurir</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ strtoupper($order->shipping_courier ?? '-') }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">No. Resi</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->tracking_number }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Estimasi Pengiriman</p>
                                <p class="font-semibold text-gray-900 dark:text-white">
                                    {{ $order->shipping_etd ?? '-' }} hari
                                </p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Alamat Pengiriman</p>
                            <p class="text-gray-900 dark:text-white">
                                {{ $order->full_shipping_address }}
                            </p>
                        </div>
                    </div>
                @endif
            </x-filament::section>

            {{-- Tracking Timeline --}}
            <x-filament::section>
                <x-slot name="heading">Timeline Pesanan</x-slot>

                <div class="relative">
                    @foreach ($this->getTrackingSteps() as $index => $step)
                        <div class="flex gap-4 {{ $index < count($this->getTrackingSteps()) - 1 ? 'pb-8' : '' }}">
                            {{-- Timeline Line --}}
                            @if ($index < count($this->getTrackingSteps()) - 1)
                                <div
                                    class="absolute left-6 top-12 bottom-0 w-0.5 {{ $step['completed'] ? 'bg-success-500' : 'bg-gray-300 dark:bg-gray-600' }}"
                                ></div>
                            @endif

                            {{-- Icon --}}
                            <div class="relative z-10 flex-shrink-0">
                                <div
                                    class="flex items-center justify-center w-12 h-12 rounded-full {{ $step['completed'] ? 'bg-success-500 text-white' : ($step['active'] ? 'bg-primary-500 text-white' : 'bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400') }} ring-4 ring-white dark:ring-gray-900"
                                >
                                    <x-dynamic-component :component="$step['icon']" class="w-6 h-6" />
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="flex-1 pt-1">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3
                                            class="text-base font-semibold {{ $step['active'] ? 'text-primary-600 dark:text-primary-400' : 'text-gray-900 dark:text-white' }}"
                                        >
                                            {{ $step['label'] }}
                                        </h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $step['description'] }}
                                        </p>

                                        @if ($step['date'])
                                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-2">
                                                <x-heroicon-m-calendar class="w-4 h-4 inline mr-1" />
                                                {{ $step['date']->format('d M Y, H:i') }} WIB
                                            </p>
                                        @endif
                                    </div>

                                    @if ($step['completed'])
                                        <x-filament::badge color="success" size="sm">Selesai</x-filament::badge>
                                    @elseif ($step['active'])
                                        <x-filament::badge color="primary" size="sm">
                                            Sedang Berlangsung
                                        </x-filament::badge>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>

            {{-- Order Items --}}
            <x-filament::section>
                <x-slot name="heading">Item Pesanan ({{ $order->items->count() }} item)</x-slot>

                <div class="space-y-4">
                    @foreach ($order->items as $item)
                        <div class="flex gap-4 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-900 dark:text-white">
                                    {{ $item->product->name }}
                                </h4>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    Jumlah: {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
                                </p>
                                @if ($item->selected_material || $item->selected_finishing)
                                    <p class="text-xs text-gray-400 mt-1">
                                        @if ($item->selected_material)
                                                Material: {{ $item->selected_material }}
                                        @endif

                                        @if ($item->selected_finishing)
                                                | Finishing: {{ $item->selected_finishing }}
                                        @endif
                                    </p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-gray-900 dark:text-white">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>
