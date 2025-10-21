@props([
    'item',
])

<div
    class="flex items-start space-x-4 p-5 bg-gray-50 rounded-xl border border-gray-100 hover:bg-gray-100 transition-colors"
>
    <!-- Product Image Placeholder -->
    <div
        class="flex-shrink-0 w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 rounded-lg flex items-center justify-center overflow-hidden"
    >
        @if ($item->product->image_url)
            <img
                src="{{ $item->product->image_url }}"
                alt="{{ $item->product->name }}"
                class="w-full h-full object-cover rounded-lg"
            />
        @else
            <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <h4 class="font-semibold text-gray-900 text-lg">{{ $item->product->name }}</h4>
        <div class="mt-2 grid grid-cols-2 gap-4">
            <div>
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
            </div>
            <div>
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
            </div>
        </div>

        @if ($item->design_notes)
            <div class="mt-3 p-3 bg-white rounded-lg border">
                <p class="text-sm text-gray-700">
                    <strong>Catatan Design:</strong>
                    {{ $item->design_notes }}
                </p>
            </div>
        @endif

        <div class="mt-3 flex flex-wrap gap-2">
            @if ($item->requires_design_service)
                <span
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800"
                >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v1m0 0h6m-6 0V3m6 0a2 2 0 012 2v1M9 7h6m0 0v2M9 7V5a2 2 0 012-2h2a2 2 0 012 2v2M9 7v10a2 2 0 002 2h2a2 2 0 002-2V7m-6 0h6"
                        ></path>
                    </svg>
                    Jasa Design
                </span>
            @endif

            @if ($item->hasDesignFile())
                <a
                    href="{{ route('orders.download-design', ['orderNumber' => $item->order->order_number ?? request()->route('orderNumber'), 'item' => $item->id]) }}"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 hover:bg-blue-200 transition-colors"
                >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707v6.586a2 2 0 01-2 2z"
                        ></path>
                    </svg>
                    Download File
                </a>
            @endif
        </div>
    </div>
</div>
