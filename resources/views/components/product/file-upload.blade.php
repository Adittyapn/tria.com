@props([
    'product',
])

<!-- Design File Upload Section -->
@if ($product->requires_design_file)
    <div class="space-y-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
        <h3 class="font-semibold text-yellow-800">📁 Upload File Desain (Wajib)</h3>

        @if ($product->file_requirements)
            <p class="text-yellow-700 text-sm">{{ $product->file_requirements }}</p>
        @endif

        <!-- File Upload Area -->
        <div
            @dragover.prevent="dragOver = true"
            @dragleave.prevent="dragOver = false"
            @drop.prevent="handleFileDrop($event)"
            :class="dragOver ? 'border-yellow-400 bg-yellow-100' : 'border-yellow-300'"
            class="border-2 border-dashed rounded-lg p-6 text-center transition-colors relative"
        >
            <!-- Loading Overlay -->
            <div
                x-show="isUploading"
                class="absolute inset-0 bg-white bg-opacity-90 rounded-lg flex items-center justify-center z-10"
            >
                <div class="text-center">
                    <svg
                        class="animate-spin h-12 w-12 text-yellow-600 mx-auto mb-3"
                        xmlns="http://www.w3.org/2000/svg"
                        fill="none"
                        viewBox="0 0 24 24"
                    >
                        <circle
                            class="opacity-25"
                            cx="12"
                            cy="12"
                            r="10"
                            stroke="currentColor"
                            stroke-width="4"
                        ></circle>
                        <path
                            class="opacity-75"
                            fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                    </svg>
                    <p class="text-yellow-700 font-medium">Mengupload file...</p>
                    <div class="w-48 bg-gray-200 rounded-full h-2 mt-3">
                        <div
                            class="bg-yellow-600 h-2 rounded-full transition-all duration-300"
                            :style="`width: ${uploadProgress}%`"
                        ></div>
                    </div>
                    <p class="text-sm text-yellow-600 mt-2" x-text="`${uploadProgress}%`"></p>
                </div>
            </div>

            <div x-show="!designFile && !isUploading">
                <svg class="mx-auto h-12 w-12 text-yellow-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path
                        d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    />
                </svg>
                <div class="mt-4">
                    <label for="design-file" class="cursor-pointer">
                        <span class="mt-2 block text-sm font-medium text-yellow-700">
                            Klik untuk upload atau drag & drop file di sini
                        </span>
                        <input
                            id="design-file"
                            type="file"
                            class="sr-only"
                            @change="handleFileSelect($event)"
                            :accept="productData.allowedFormats.map(f => '.' + f).join(',')"
                        />
                    </label>
                    <p class="mt-1 text-xs text-yellow-600">
                        Format: {{ implode(', ', $product->allowed_formats ?? ['JPG', 'PNG', 'PDF']) }}
                        @if ($product->max_file_size_mb)
                            | Max: {{ $product->max_file_size_mb }}MB
                        @endif
                    </p>
                </div>
            </div>

            <!-- File Preview -->
            <div x-show="designFile && !isUploading" class="text-left">
                <div class="flex items-center justify-between bg-white rounded-lg p-3 border">
                    <div class="flex items-center">
                        <div class="relative">
                            <svg class="h-8 w-8 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    fill-rule="evenodd"
                                    d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                            <!-- Success Checkmark -->
                            <div class="absolute -top-1 -right-1 bg-green-500 rounded-full p-0.5">
                                <svg class="h-3 w-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="3"
                                        d="M5 13l4 4L19 7"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-gray-900" x-text="designFile?.name"></p>
                            <p
                                class="text-sm text-gray-500"
                                x-text="designFile ? formatFileSize(designFile.size) : ''"
                            ></p>
                        </div>
                    </div>
                    <button @click="removeFile()" class="text-red-600 hover:text-red-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M6 18L18 6M6 6l12 12"
                            ></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- File Upload Error -->
        <div x-show="errors.designFile" class="text-red-600 text-sm" x-text="errors.designFile"></div>

        <!-- Success Message -->
        <div
            x-show="fileUploadSuccess"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            class="flex items-center bg-green-50 border border-green-200 rounded-lg p-3"
        >
            <svg class="h-5 w-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span class="text-green-700 text-sm font-medium">File berhasil diupload!</span>
        </div>
    </div>
@endif
