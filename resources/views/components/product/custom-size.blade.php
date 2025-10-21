@props([
    'product',
])

<!-- Custom Size Input -->
@if ($product->allows_custom_size && in_array($product->pricing_type, ['per_meter_square', 'per_meter_linear']))
    <div class="space-y-4 bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
        <h3 class="font-semibold text-yellow-800">📏 Ukuran Custom (Wajib diisi):</h3>

        @if ($product->pricing_type === 'per_meter_square')
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-red-700 mb-1">
                        Panjang (meter)
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        x-model="customLength"
                        @input="calculateCustomSize()"
                        step="0.1"
                        min="0.1"
                        max="50"
                        placeholder="Contoh: 3.0"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    />
                </div>
                <div>
                    <label class="block text-sm font-medium text-red-700 mb-1">
                        Lebar (meter)
                        <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        x-model="customWidth"
                        @input="calculateCustomSize()"
                        step="0.1"
                        min="0.1"
                        max="50"
                        placeholder="Contoh: 1.0"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                    />
                </div>
            </div>

            <!-- Size Calculator Result -->
            <div
                x-show="totalMeters > 0"
                class="text-center p-3 bg-white rounded border-2 border-dashed border-gray-300"
            >
                <span class="text-lg font-semibold text-gray-900">
                    Total Area:
                    <span class="text-red-600" x-text="totalMeters.toFixed(2)"></span>
                    m²
                </span>
            </div>
        @else
            <div>
                <label class="block text-sm font-medium text-red-700 mb-1">
                    Panjang (meter)
                    <span class="text-red-500">*</span>
                </label>
                <input
                    type="number"
                    x-model="customLength"
                    @input="calculateCustomSize()"
                    step="0.1"
                    min="0.1"
                    max="100"
                    placeholder="Contoh: 5.0"
                    required
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-yellow-500 focus:border-transparent"
                />
                <div x-show="totalMeters > 0" class="mt-2 text-center text-lg font-semibold text-red-600">
                    <span x-text="totalMeters.toFixed(1)"></span>
                    meter
                </div>
            </div>
        @endif

        <!-- Custom Size Error -->
        <div x-show="errors.customSize" class="text-red-600 text-sm" x-text="errors.customSize"></div>
    </div>
@endif
