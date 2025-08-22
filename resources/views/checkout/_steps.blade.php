<div class="mb-8">
    <!-- Desktop Steps (md and up) -->
    <div class="hidden md:block relative">
        <div class="max-w-xl mx-auto">
            <div>
                <!-- Progress Bar Background -->
                <div class="absolute top-5 left-0 transform -translate-y-1/2 h-1 bg-gray-200 w-full rounded-full"></div>

                <!-- Active Progress Bar -->
                <div
                    class="absolute top-5 left-0 transform -translate-y-1/2 h-1 bg-red-500 rounded-full transition-all duration-500"
                    style="width: {{ $progress === 100 ? '100%' : $progress . '%' }}"
                ></div>

                <!-- Steps Container -->
                <div class="relative flex justify-between">
                    <!-- Step 1: Shipping -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-300',
                                'bg-red-500 shadow-lg shadow-red-100' => $progress >= 33,
                                'border-2 border-gray-200 bg-white' => $progress < 33,
                            ])
                        >
                            <span
                                @class([
                                    'font-bold',
                                    'text-white' => $progress >= 33,
                                    'text-gray-400' => $progress < 33,
                                ])
                            >
                                1
                            </span>
                        </div>
                        <span
                            @class([
                                'text-sm font-medium',
                                'text-red-600' => $progress >= 33,
                                'text-gray-400' => $progress < 33,
                            ])
                        >
                            Pengiriman
                        </span>
                    </div>

                    <!-- Step 2: Payment -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-300',
                                'bg-red-500 shadow-lg shadow-red-100' => $progress >= 66,
                                'border-2 border-gray-200 bg-white' => $progress < 66,
                            ])
                        >
                            <span
                                @class([
                                    'font-bold',
                                    'text-white' => $progress >= 66,
                                    'text-gray-400' => $progress < 66,
                                ])
                            >
                                2
                            </span>
                        </div>
                        <span
                            @class([
                                'text-sm font-medium',
                                'text-red-600' => $progress >= 66,
                                'text-gray-400' => $progress < 66,
                            ])
                        >
                            Pembayaran
                        </span>
                    </div>

                    <!-- Step 3: Confirmation -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-10 h-10 rounded-full flex items-center justify-center mb-2 transition-all duration-300',
                                'bg-red-500 shadow-lg shadow-red-100' => $progress === 100,
                                'border-2 border-gray-200 bg-white' => $progress < 100,
                            ])
                        >
                            <span
                                @class([
                                    'font-bold',
                                    'text-white' => $progress === 100,
                                    'text-gray-400' => $progress < 100,
                                ])
                            >
                                3
                            </span>
                        </div>
                        <span
                            @class([
                                'text-sm font-medium',
                                'text-red-600' => $progress === 100,
                                'text-gray-400' => $progress < 100,
                            ])
                        >
                            Konfirmasi
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Steps (sm and down) -->
        <div class="block md:hidden">
            <div class="px-4 max-w-xs mx-auto">
                <!-- Progress Bar -->
                <div class="relative h-2 bg-gray-200 rounded-full mb-6">
                    <div
                        class="absolute left-0 top-0 h-full bg-red-500 rounded-full transition-all duration-500"
                        style="width: {{ $progress === 100 ? '100%' : $progress . '%' }}"
                    ></div>
                </div>

                <!-- Steps -->
                <div class="flex justify-between -mt-2">
                    <!-- Step 1: Shipping -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-300',
                                'bg-red-500 shadow-md' => $progress >= 33,
                                'border-2 border-gray-200 bg-white' => $progress < 33,
                            ])
                        >
                            @if ($progress >= 33)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            @else
                                <span class="text-gray-400 text-sm font-bold">1</span>
                            @endif
                        </div>
                        <span
                            @class([
                                'text-xs font-medium',
                                'text-red-600' => $progress >= 33,
                                'text-gray-400' => $progress < 33,
                            ])
                        >
                            Pengiriman
                        </span>
                    </div>

                    <!-- Step 2: Payment -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-300',
                                'bg-red-500 shadow-md' => $progress >= 66,
                                'border-2 border-gray-200 bg-white' => $progress < 66,
                            ])
                        >
                            @if ($progress >= 66 && $progress < 100)
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M5 13l4 4L19 7"
                                    />
                                </svg>
                            @else
                                <span
                                    @class([
                                        'text-sm font-bold',
                                        'text-white' => $progress >= 66,
                                        'text-gray-400' => $progress < 66,
                                    ])
                                >
                                    2
                                </span>
                            @endif
                        </div>
                        <span
                            @class([
                                'text-xs font-medium',
                                'text-red-600' => $progress >= 66,
                                'text-gray-400' => $progress < 66,
                            ])
                        >
                            Pembayaran
                        </span>
                    </div>

                    <!-- Step 3: Confirmation -->
                    <div class="flex flex-col items-center">
                        <div
                            @class([
                                'w-8 h-8 rounded-full flex items-center justify-center mb-1 transition-all duration-300',
                                'bg-red-500 shadow-md' => $progress === 100,
                                'border-2 border-gray-200 bg-white' => $progress < 100,
                            ])
                        >
                            <span
                                @class([
                                    'text-sm font-bold',
                                    'text-white' => $progress === 100,
                                    'text-gray-400' => $progress < 100,
                                ])
                            >
                                3
                            </span>
                        </div>
                        <span
                            @class([
                                'text-xs font-medium',
                                'text-red-600' => $progress === 100,
                                'text-gray-400' => $progress < 100,
                            ])
                        >
                            Konfirmasi
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
