<x-filament-widgets::widget>
    <x-filament::section>
        <!-- Header Section -->
        <div class="mb-6">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white">📊 Export Laporan Data</h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                Unduh data laporan dalam format CSV untuk analisis lebih lanjut
            </p>
        </div>

        <!-- Report Cards Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            @foreach ($reports as $report)
                @php
                    $colorClasses = match ($report['type']) {
                        'orders' => [
                            'bg' => 'bg-blue-50 dark:bg-blue-900/20',
                            'icon' => 'text-blue-600 dark:text-blue-400',
                            'iconBg' => 'bg-blue-100 dark:bg-blue-800/30',
                            'border' => 'border-blue-200 dark:border-blue-800',
                            'hover' => 'hover:border-blue-300 dark:hover:border-blue-700',
                            'button' => 'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500',
                            'focus' => 'focus:ring-blue-500 focus:border-blue-500',
                        ],
                        'products' => [
                            'bg' => 'bg-green-50 dark:bg-green-900/20',
                            'icon' => 'text-green-600 dark:text-green-400',
                            'iconBg' => 'bg-green-100 dark:bg-green-800/30',
                            'border' => 'border-green-200 dark:border-green-800',
                            'hover' => 'hover:border-green-300 dark:hover:border-green-700',
                            'button' => 'bg-green-600 hover:bg-green-700 focus:ring-green-500',
                            'focus' => 'focus:ring-green-500 focus:border-green-500',
                        ],
                        'customers' => [
                            'bg' => 'bg-purple-50 dark:bg-purple-900/20',
                            'icon' => 'text-purple-600 dark:text-purple-400',
                            'iconBg' => 'bg-purple-100 dark:bg-purple-800/30',
                            'border' => 'border-purple-200 dark:border-purple-800',
                            'hover' => 'hover:border-purple-300 dark:hover:border-purple-700',
                            'button' => 'bg-purple-600 hover:bg-purple-700 focus:ring-purple-500',
                            'focus' => 'focus:ring-purple-500 focus:border-purple-500',
                        ],
                        default => [
                            'bg' => 'bg-gray-50',
                            'icon' => 'text-gray-600',
                            'iconBg' => 'bg-gray-100',
                            'border' => 'border-gray-200',
                            'hover' => 'hover:border-gray-300',
                            'button' => 'bg-gray-600 hover:bg-gray-700 focus:ring-gray-500',
                            'focus' => 'focus:ring-gray-500 focus:border-gray-500',
                        ],
                    };
                @endphp

                <div
                    class="group bg-white dark:bg-gray-800 rounded-xl shadow-sm border-2 {{ $colorClasses['border'] }} {{ $colorClasses['hover'] }} transition-all duration-300 overflow-hidden hover:shadow-lg"
                >
                    <!-- Card Header with Gradient -->
                    <div class="{{ $colorClasses['bg'] }} px-6 py-5 border-b {{ $colorClasses['border'] }}">
                        <div class="flex items-start gap-4">
                            <div
                                class="flex-shrink-0 w-12 h-12 {{ $colorClasses['iconBg'] }} rounded-xl flex items-center justify-center shadow-sm group-hover:scale-110 transition-transform duration-300"
                            >
                                @if ($report['type'] === 'orders')
                                    <svg
                                        class="w-6 h-6 {{ $colorClasses['icon'] }}"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                        ></path>
                                    </svg>
                                @elseif ($report['type'] === 'products')
                                    <svg
                                        class="w-6 h-6 {{ $colorClasses['icon'] }}"
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
                                @else
                                    <svg
                                        class="w-6 h-6 {{ $colorClasses['icon'] }}"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
                                        ></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                                    {{ $report['title'] }}
                                </h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                                    {{ $report['description'] }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body - Form -->
                    <form action="{{ route('admin.reports.download') }}" method="GET" class="p-6 space-y-5">
                        <input type="hidden" name="type" value="{{ $report['type'] }}" />

                        <!-- Date Range -->
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Mulai
                                </label>
                                <input
                                    type="date"
                                    name="start_date"
                                    value="{{ now()->subMonth()->format('Y-m-d') }}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg {{ $colorClasses['focus'] }} dark:bg-gray-700 dark:text-white transition-colors"
                                    required
                                />
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Tanggal Akhir
                                </label>
                                <input
                                    type="date"
                                    name="end_date"
                                    value="{{ now()->format('Y-m-d') }}"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg {{ $colorClasses['focus'] }} dark:bg-gray-700 dark:text-white transition-colors"
                                    required
                                />
                            </div>
                        </div>

                        <!-- Status Filter (Orders Only) -->
                        @if ($report['type'] === 'orders')
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Status Pesanan
                                </label>
                                <select
                                    name="status"
                                    class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-lg {{ $colorClasses['focus'] }} dark:bg-gray-700 dark:text-white transition-colors"
                                >
                                    <option value="all">Semua Status</option>
                                    <option value="paid">✅ Sudah Dibayar</option>
                                    <option value="pending">⏳ Menunggu Pembayaran</option>
                                    <option value="cancelled">❌ Dibatalkan</option>
                                </select>
                            </div>
                        @endif

                        <!-- Download Button -->
                        <button
                            type="submit"
                            class="w-full {{ $colorClasses['button'] }} text-white font-semibold py-3 px-4 rounded-lg transition-all duration-200 flex items-center justify-center gap-2 shadow-md hover:shadow-lg hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"
                                ></path>
                            </svg>
                            <span>Download CSV</span>
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Export Tips -->
        <div
            class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-2 border-blue-200 dark:border-blue-800 rounded-xl p-6 shadow-sm"
        >
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg
                        class="w-6 h-6 text-blue-600 dark:text-blue-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"
                        ></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-blue-900 dark:text-blue-300 text-base mb-3">💡 Tips Export Data</h4>
                    <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-300">
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">📊</span>
                            <span>
                                File CSV dapat dibuka di
                                <strong>Microsoft Excel</strong>
                                atau
                                <strong>Google Sheets</strong>
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">🔤</span>
                            <span>
                                Gunakan encoding
                                <strong>UTF-8</strong>
                                untuk karakter Indonesia yang benar
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">📅</span>
                            <span>
                                Data mencakup
                                <strong>periode yang dipilih</strong>
                                dengan detail lengkap
                            </span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-blue-600 dark:text-blue-400 font-bold">⏱️</span>
                            <span>
                                Untuk periode besar, proses download mungkin memerlukan
                                <strong>waktu lebih lama</strong>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
