<div
    id="cancelModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50 overflow-y-auto"
>
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto my-8">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-red-900">Batalkan Pesanan</h3>
                <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600 p-1">
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

            <div class="bg-red-50 border border-red-200 rounded-lg p-3 sm:p-4 mb-4">
                <p class="text-red-800 text-xs sm:text-sm">
                    ⚠️ Pesanan yang sudah dibatalkan tidak dapat dikembalikan. Pastikan keputusan Anda.
                </p>
            </div>

            <form id="cancelForm" method="POST" action="{{ route('orders.cancel', $order->order_number) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Pembatalan
                            <span class="text-red-500">*</span>
                        </label>
                        <textarea
                            id="cancellation_reason"
                            name="cancellation_reason"
                            rows="4"
                            required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm sm:text-base"
                            placeholder="Jelaskan alasan Anda membatalkan pesanan ini..."
                        ></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0 pt-4">
                        <button
                            type="button"
                            onclick="closeCancelModal()"
                            class="w-full sm:flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full sm:flex-1 bg-red-600 hover:bg-red-700 text-white font-semibold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base"
                        >
                            Ya, Batalkan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
