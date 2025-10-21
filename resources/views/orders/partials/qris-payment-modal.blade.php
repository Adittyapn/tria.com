<div
    id="qrisModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50 overflow-y-auto"
>
    <div class="bg-white rounded-2xl shadow-2xl max-w-sm w-full mx-auto my-8">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Bayar via QRIS</h3>
                <button onclick="closeQrisModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        ></path>
                    </svg>
                </button>
            </div>

            <div class="text-center">
                @php
                    // Always prefer a default QRIS image from public/images/qris
                    $qrisDefaultPng = public_path('images/qris/qris.png');
                    $qrisDefaultSvg = public_path('images/qris/qris.jpeg');
                    $qrisImageUrl = null;

                    if (file_exists($qrisDefaultPng)) {
                        $qrisImageUrl = asset('images/qris/qris.png');
                    } elseif (file_exists($qrisDefaultSvg)) {
                        $qrisImageUrl = asset('images/qris/qris.jpeg');
                    }
                @endphp

                @if ($qrisImageUrl)
                    <p class="text-sm text-gray-600 mb-3">Scan QR code berikut untuk membayar menggunakan QRIS</p>
                    <div class="inline-block bg-white p-4 rounded-lg border">
                        <img
                            src="{{ $qrisImageUrl }}"
                            alt="QRIS - {{ $order->order_number }}"
                            class="mx-auto w-48 h-48 object-contain"
                        />
                    </div>

                    <div class="mt-3 flex items-center justify-center gap-3">
                        <a
                            href="{{ $qrisImageUrl }}"
                            download="qris-{{ $order->order_number }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm rounded-md"
                        >
                            Download QRIS
                        </a>

                        @if (! empty($order->qris_payload))
                            <button
                                type="button"
                                onclick="navigator.clipboard && navigator.clipboard.writeText('{{ $order->qris_payload }}')"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-sm rounded-md"
                            >
                                Salin Instruksi Pembayaran
                            </button>
                        @endif
                    </div>
                @else
                    {{-- No default asset found — render small inline placeholder so modal still shows something --}}
                    <p class="text-sm text-gray-600 mb-3">Scan QR code berikut untuk membayar menggunakan QRIS</p>
                    <div class="inline-block bg-white p-4 rounded-lg border">
                        <svg
                            class="mx-auto w-48 h-48 text-gray-300"
                            viewBox="0 0 200 200"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <rect width="100%" height="100%" rx="8" fill="#f8fafc" stroke="#e5e7eb" />
                            <text
                                x="50%"
                                y="50%"
                                dominant-baseline="middle"
                                text-anchor="middle"
                                fill="#9ca3af"
                                font-size="14"
                            >
                                QRIS (default)
                            </text>
                        </svg>
                    </div>

                    <div class="mt-3">
                        <p class="text-sm text-gray-600">
                            QRIS default belum tersedia. Tambahkan file
                            <code>public/images/qris/default.png</code>
                            atau
                            <code>public/images/qris/default.svg</code>
                            .
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
