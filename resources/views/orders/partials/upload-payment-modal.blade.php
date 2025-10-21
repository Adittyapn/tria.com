<div
    id="uploadModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4 z-50 overflow-y-auto"
>
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-auto my-8">
        <div class="p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Upload Bukti Pembayaran</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600 p-1">
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

            <form
                id="uploadForm"
                method="POST"
                action="{{ route('orders.upload-payment', $order->order_number) }}"
                enctype="multipart/form-data"
            >
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">File Bukti Pembayaran</label>
                        <div
                            class="border-2 border-dashed border-gray-300 rounded-lg p-3 sm:p-4 text-center hover:border-gray-400 transition-colors"
                        >
                            <input
                                type="file"
                                id="payment_proof"
                                name="payment_proof"
                                accept=".jpg,.jpeg,.png,.pdf"
                                required
                                class="hidden"
                            />
                            <label for="payment_proof" class="cursor-pointer block">
                                <svg
                                    class="w-10 h-10 sm:w-12 sm:h-12 text-gray-400 mx-auto mb-2"
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
                                <p class="text-sm text-gray-600">Klik untuk pilih file</p>
                                <p class="text-xs text-gray-500 mt-1">JPG, PNG, PDF (max 5MB)</p>
                            </label>
                        </div>
                        <div id="fileName" class="text-sm text-gray-600 mt-2 hidden"></div>
                    </div>

                    <div>
                        <label for="payment_notes" class="block text-sm font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea
                            id="payment_notes"
                            name="payment_notes"
                            rows="3"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm sm:text-base"
                            placeholder="Tambahkan catatan jika diperlukan..."
                        ></textarea>
                    </div>

                    <div class="flex flex-col sm:flex-row sm:space-x-3 space-y-2 sm:space-y-0 pt-4">
                        <button
                            type="button"
                            onclick="closeUploadModal()"
                            class="w-full sm:flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="w-full sm:flex-1 bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 sm:py-3 px-4 rounded-lg transition-colors text-sm sm:text-base"
                        >
                            Upload
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
