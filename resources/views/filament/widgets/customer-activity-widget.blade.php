<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            <div class="flex items-center gap-2">
                <x-heroicon-m-clock class="w-5 h-5 text-primary-500" />
                <span>Aktivitas Terakhir</span>
            </div>
        </x-slot>

        <div class="space-y-4">
            {{-- User Info Card --}}
            <div
                class="bg-gradient-to-r from-primary-50 to-primary-100 dark:from-primary-900 dark:to-primary-800 rounded-lg p-4"
            >
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-white dark:bg-gray-700 rounded-full flex items-center justify-center">
                            <x-heroicon-s-user class="w-6 h-6 text-primary-500" />
                        </div>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ $this->getViewData()['userName'] }}
                        </h3>
                        <p class="text-sm text-gray-600 dark:text-gray-300">
                            {{ $this->getViewData()['userEmail'] }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Login Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <x-heroicon-m-arrow-right-on-rectangle class="w-5 h-5 text-success-500" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Login Saat Ini</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                                {{ $this->getViewData()['currentLogin']->diffForHumans() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ $this->getViewData()['currentLogin']->format('d M Y, H:i') }} WIB
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0 mt-1">
                            <x-heroicon-m-clock class="w-5 h-5 text-warning-500" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Login Terakhir</p>
                            <p class="text-base font-semibold text-gray-900 dark:text-white mt-1">
                                {{ \Carbon\Carbon::parse($this->getViewData()['lastLogin'])->diffForHumans() }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ \Carbon\Carbon::parse($this->getViewData()['lastLogin'])->format('d M Y, H:i') }}
                                WIB
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Activities --}}
            @if ($this->getViewData()['recentActivities']->isNotEmpty())
                <div class="mt-4">
                    <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Aktivitas Terbaru</h4>
                    <div class="space-y-2">
                        @foreach ($this->getViewData()['recentActivities'] as $activity)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg">
                                <div class="flex-shrink-0 mt-1">
                                    @php
                                        $iconComponent = str_replace('heroicon-m-', '', $activity['icon']);
                                        $colorClass = match ($activity['color']) {
                                            'success' => 'text-success-500',
                                            'info' => 'text-info-500',
                                            'warning' => 'text-warning-500',
                                            'primary' => 'text-primary-500',
                                            default => 'text-gray-500',
                                        };
                                    @endphp

                                    <x-dynamic-component
                                        :component="'heroicon-m-' . $iconComponent"
                                        class="w-5 h-5 {{ $colorClass }}"
                                    />
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm text-gray-900 dark:text-white">
                                        {{ $activity['description'] }}
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                        {{ $activity['created_at']->diffForHumans() }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-6">
                    <x-heroicon-o-information-circle class="w-12 h-12 text-gray-400 mx-auto mb-3" />
                    <p class="text-sm text-gray-500 dark:text-gray-400">Belum ada aktivitas tercatat</p>
                </div>
            @endif
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
