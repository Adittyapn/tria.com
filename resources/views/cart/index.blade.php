@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <div class="container mx-auto px-4 py-8" x-data="cartManager()" x-init="init()">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
                <p class="text-gray-600">Kelola produk sebelum checkout</p>
            </div>

            <!-- Empty Cart State -->
            <div x-show="cartItems.length === 0" class="text-center py-16">
                <div
                    class="w-20 h-20 md:w-24 md:h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center"
                >
                    <svg
                        class="w-8 h-8 md:w-12 md:h-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                        ></path>
                    </svg>
                </div>
                <h2 class="text-xl md:text-2xl font-semibold text-gray-900 mb-4">Keranjang Kosong</h2>
                <p class="text-gray-600 mb-8">Belum ada produk di keranjang Anda</p>
                <a
                    href="{{ route('products.index') }}"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        ></path>
                    </svg>
                    Jelajahi Produk
                </a>
            </div>

            <!-- Cart Content -->
            <div x-show="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
                <!-- Cart Items -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                        <div class="p-4 md:p-6 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg md:text-xl font-semibold text-gray-900">
                                    Item (
                                    <span x-text="cartItems.length"></span>
                                    )
                                </h2>
                                <button
                                    @click="clearCart()"
                                    class="text-red-600 hover:text-red-700 text-sm font-medium"
                                >
                                    <svg
                                        class="w-4 h-4 inline mr-1"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                        ></path>
                                    </svg>
                                    Kosongkan Keranjang
                                </button>
                            </div>
                        </div>

                        <!-- Cart Items List -->
                        <div class="divide-y divide-gray-100">
                            <template x-for="item in cartItems" :key="item.id">
                                <div class="p-4 md:p-6 hover:bg-gray-50 transition-colors">
                                    <div
                                        class="flex flex-col md:flex-row md:items-start space-y-4 md:space-y-0 md:space-x-4"
                                    >
                                        <!-- Product Image -->
                                        <div
                                            class="w-full md:w-24 h-24 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0"
                                        >
                                            <img
                                                :src="item.product.featured_image ? `/storage/${item.product.featured_image}` : '/images/default-product.png'"
                                                :alt="item.product.name"
                                                class="w-full h-full object-cover"
                                                onerror="this.src='/images/default-product.png'"
                                            />
                                        </div>

                                        <!-- Product Details -->
                                        <div class="flex-1 min-w-0">
                                            <h3
                                                class="font-semibold text-gray-900 mb-1 text-sm md:text-base"
                                                x-text="item.product.name"
                                            ></h3>
                                            <p
                                                class="text-sm text-gray-600 mb-2"
                                                x-text="item.product.category?.name"
                                            ></p>

                                            <!-- Specifications -->
                                            <div class="space-y-1 text-xs md:text-sm text-gray-600">
                                                <div x-show="item.custom_size_width && item.custom_size_height">
                                                    <span class="font-medium">Ukuran:</span>
                                                    <span
                                                        x-text="`${item.custom_size_width} x ${item.custom_size_height} cm`"
                                                    ></span>
                                                </div>
                                                <div x-show="item.selected_material">
                                                    <span class="font-medium">Bahan:</span>
                                                    <span x-text="item.selected_material"></span>
                                                </div>
                                                <div x-show="item.selected_finishing">
                                                    <span class="font-medium">Finishing:</span>
                                                    <span x-text="item.selected_finishing"></span>
                                                </div>
                                                <div x-show="item.design_notes">
                                                    <span class="font-medium">Catatan:</span>
                                                    <span x-text="item.design_notes"></span>
                                                </div>
                                                <div
                                                    x-show="item.design_file_path"
                                                    class="flex items-center space-x-2"
                                                >
                                                    <svg
                                                        class="w-3 h-3 text-green-600"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.586-6.586a2 2 0 000-2.828z"
                                                        ></path>
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4"
                                                        ></path>
                                                    </svg>
                                                    <span class="text-green-600">File desain terupload</span>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Quantity & Price - Mobile Layout -->
                                        <div
                                            class="flex md:block justify-between items-end md:items-start md:text-right space-y-0 md:space-y-3"
                                        >
                                            <!-- Quantity Controls -->
                                            <div class="flex items-center space-x-2 md:justify-end">
                                                <button
                                                    @click="updateQuantity(item.id, item.quantity - 1)"
                                                    :disabled="item.quantity <= (item.product.minimum_quantity || 1)"
                                                    class="w-7 h-7 md:w-8 md:h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                                >
                                                    <svg
                                                        class="w-3 h-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M20 12H4"
                                                        ></path>
                                                    </svg>
                                                </button>
                                                <input
                                                    type="number"
                                                    x-model="item.quantity"
                                                    @change="updateQuantity(item.id, item.quantity)"
                                                    :min="item.product.minimum_quantity || 1"
                                                    :data-item-id="item.id"
                                                    class="w-12 md:w-16 text-center border border-gray-300 rounded-lg py-1 text-sm"
                                                />
                                                <button
                                                    @click="updateQuantity(item.id, item.quantity + 1)"
                                                    class="w-7 h-7 md:w-8 md:h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50"
                                                >
                                                    <svg
                                                        class="w-3 h-3"
                                                        fill="none"
                                                        stroke="currentColor"
                                                        viewBox="0 0 24 24"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"
                                                        ></path>
                                                    </svg>
                                                </button>
                                            </div>

                                            <!-- Price -->
                                            <div class="space-y-1 text-right">
                                                <div
                                                    class="text-base md:text-lg font-semibold text-gray-900"
                                                    x-text="formatPrice(item.subtotal)"
                                                ></div>
                                                <div
                                                    class="text-xs md:text-sm text-gray-500"
                                                    x-text="`${formatPrice(item.unit_price)} / ${item.product.unit_label || 'pcs'}`"
                                                ></div>
                                            </div>

                                            <!-- Remove Button -->
                                            <button
                                                @click="removeItem(item.id)"
                                                class="text-red-600 hover:text-red-700 text-sm md:mt-2"
                                            >
                                                <svg
                                                    class="w-4 h-4"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                                    ></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 sticky top-8">
                        <div class="p-4 md:p-6">
                            <h2 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                            <!-- Summary Items -->
                            <div class="space-y-3 mb-6">
                                <div class="flex justify-between text-gray-600">
                                    <span class="text-sm">
                                        Subtotal (
                                        <span x-text="getTotalItems()"></span>
                                        item)
                                    </span>
                                    <span class="font-medium" x-text="formatPrice(totalAmount)"></span>
                                </div>
                                <div class="flex justify-between text-gray-600">
                                    <span class="text-sm">Estimasi Berat</span>
                                    <span class="font-medium" x-text="getEstimatedWeight() + ' kg'"></span>
                                </div>
                                <div class="border-t pt-3">
                                    <div class="flex justify-between font-semibold text-lg text-gray-900">
                                        <span>Total</span>
                                        <span x-text="formatPrice(totalAmount)"></span>
                                    </div>
                                    <p class="text-xs md:text-sm text-gray-500 mt-1">*Belum termasuk ongkir & pajak</p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="space-y-3">
                                <button
                                    @click="proceedToCheckout()"
                                    :disabled="cartItems.length === 0"
                                    class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg
                                        class="w-4 h-4 inline mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"
                                        ></path>
                                    </svg>
                                    Lanjut ke Checkout
                                </button>

                                <a
                                    href="{{ route('products.index') }}"
                                    class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center inline-block"
                                >
                                    <svg
                                        class="w-4 h-4 inline mr-2"
                                        fill="none"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                        ></path>
                                    </svg>
                                    Lanjut Belanja
                                </a>
                            </div>

                            <!-- Promo Code -->
                            <div class="mt-6 pt-6 border-t">
                                <div x-data="{ showPromo: false }">
                                    <button
                                        @click="showPromo = !showPromo"
                                        class="text-blue-600 hover:text-blue-700 font-medium text-sm"
                                    >
                                        <svg
                                            class="w-4 h-4 inline mr-1"
                                            fill="none"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"
                                            ></path>
                                        </svg>
                                        Punya kode promo?
                                    </button>
                                    <div x-show="showPromo" x-collapse>
                                        <div class="mt-3 flex space-x-2">
                                            <input
                                                type="text"
                                                placeholder="Kode promo"
                                                class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                            />
                                            <button
                                                class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800 transition-colors"
                                            >
                                                Terapkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Loading State -->
            <div x-show="loading" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 flex items-center space-x-3">
                    <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                    <span>Memproses...</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        function cartManager() {
            return {
                cartItems: @json($cartItems ?? []),
                totalAmount: {{ $totalAmount ?? 0 }},
                itemCount: {{ $itemCount ?? 0 }},
                loading: false,

                init() {
                    // Setup CSRF token for axios
                    axios.defaults.headers.common['X-CSRF-TOKEN'] = document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content');
                },

                async updateQuantity(itemId, newQuantity) {
                    console.log('updateQuantity called:', { itemId, newQuantity });

                    if (newQuantity < 1) {
                        console.log('Quantity less than 1, returning');
                        return;
                    }

                    this.loading = true;

                    try {
                        console.log('Making request to update cart item');

                        // Try simple approach first - direct manual update
                        const response = await fetch('/cart/update/' + itemId, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document
                                    .querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content'),
                            },
                            body: JSON.stringify({
                                quantity: newQuantity,
                            }),
                        });

                        const data = await response.json();
                        console.log('Response received:', data);

                        if (data.success) {
                            // Update local data
                            const item = this.cartItems.find((i) => i.id === itemId);
                            if (item) {
                                console.log('Updating item quantity from', item.quantity, 'to', newQuantity);
                                item.quantity = newQuantity;
                                item.subtotal = data.cart_item.subtotal;
                            }

                            this.totalAmount = data.total_amount;
                            this.showToast('Keranjang berhasil diperbarui', 'success');
                        } else {
                            console.error('Response success was false:', data);
                            this.showToast(data.message || 'Gagal memperbarui keranjang', 'error');
                        }
                    } catch (error) {
                        console.error('Update quantity error:', error);

                        const errorMessage = 'Gagal memperbarui keranjang';
                        this.showToast(errorMessage, 'error');

                        // Reset quantity if failed
                        const item = this.cartItems.find((i) => i.id === itemId);
                        if (item) {
                            // Force re-render by updating the input
                            this.$nextTick(() => {
                                const input = document.querySelector(`input[data-item-id="${itemId}"]`);
                                if (input) input.value = item.quantity;
                            });
                        }
                    } finally {
                        this.loading = false;
                    }
                },

                async removeItem(itemId) {
                    const result = await Swal.fire({
                        title: 'Hapus Item?',
                        text: 'Item akan dihapus dari keranjang',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Hapus',
                        cancelButtonText: 'Batal',
                    });

                    if (!result.isConfirmed) return;

                    this.loading = true;

                    try {
                        const response = await axios.delete(`/cart/${itemId}`);

                        if (response.data.success) {
                            this.cartItems = this.cartItems.filter((item) => item.id !== itemId);
                            this.totalAmount = response.data.total_amount;
                            this.itemCount = response.data.cart_count;
                            this.showToast('Item berhasil dihapus', 'success');
                        }
                    } catch (error) {
                        this.showToast('Gagal menghapus item', 'error');
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },

                async clearCart() {
                    const result = await Swal.fire({
                        title: 'Kosongkan Keranjang?',
                        text: 'Semua item akan dihapus dari keranjang',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#ef4444',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, Kosongkan',
                        cancelButtonText: 'Batal',
                    });

                    if (!result.isConfirmed) return;

                    this.loading = true;

                    try {
                        const response = await axios.delete('/cart');

                        if (response.data.success) {
                            this.cartItems = [];
                            this.totalAmount = 0;
                            this.itemCount = 0;
                            this.showToast('Keranjang berhasil dikosongkan', 'success');
                        }
                    } catch (error) {
                        this.showToast('Gagal mengosongkan keranjang', 'error');
                        console.error(error);
                    } finally {
                        this.loading = false;
                    }
                },

                proceedToCheckout() {
                    if (this.cartItems.length === 0) {
                        this.showToast('Keranjang kosong', 'error');
                        return;
                    }

                    window.location.href = '/checkout';
                },

                getTotalItems() {
                    return this.cartItems.reduce((total, item) => total + parseInt(item.quantity), 0);
                },

                getEstimatedWeight() {
                    return this.cartItems
                        .reduce((total, item) => {
                            const weight = item.product.estimated_weight_per_unit || 0.5;
                            const sizeMultiplier =
                                item.custom_size_width && item.custom_size_height
                                    ? (item.custom_size_width * item.custom_size_height) / 10000
                                    : 1;
                            return total + weight * sizeMultiplier * item.quantity;
                        }, 0)
                        .toFixed(1);
                },

                formatPrice(amount) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0,
                    }).format(amount);
                },

                showToast(message, type = 'success') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        icon: type,
                        title: message,
                    });
                },
            };
        }
    </script>
@endsection
