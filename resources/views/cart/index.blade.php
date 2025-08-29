@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@section('content')
    <div class="container mx-auto px-4 py-8" x-data="cartManager()" x-init="init()">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Keranjang Belanja</h1>
            <p class="text-gray-600">Kelola produk sebelum checkout</p>
        </div>

        <!-- Empty Cart State -->
        <div x-show="cartItems.length === 0" class="text-center py-16">
            <div class="w-24 h-24 mx-auto mb-6 bg-gray-100 rounded-full flex items-center justify-center">
                <i class="fas fa-shopping-cart text-4xl text-gray-400"></i>
            </div>
            <h2 class="text-2xl font-semibold text-gray-900 mb-4">Keranjang Kosong</h2>
            <p class="text-gray-600 mb-8">Belum ada produk di keranjang Anda</p>
            <a
                href="{{ route('products.index') }}"
                class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
                <i class="fas fa-search mr-2"></i>
                Jelajahi Produk
            </a>
        </div>

        <!-- Cart Content -->
        <div x-show="cartItems.length > 0" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-100">
                    <div class="p-6 border-b border-gray-100">
                        <div class="flex items-center justify-between">
                            <h2 class="text-xl font-semibold text-gray-900">
                                Item (
                                <span x-text="cartItems.length"></span>
                                )
                            </h2>
                            <button @click="clearCart()" class="text-red-600 hover:text-red-700 text-sm font-medium">
                                <i class="fas fa-trash mr-1"></i>
                                Kosongkan Keranjang
                            </button>
                        </div>
                    </div>

                    <!-- Cart Items List -->
                    <div class="divide-y divide-gray-100">
                        <template x-for="item in cartItems" :key="item.id">
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start space-x-4">
                                    <!-- Product Image -->
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden flex-shrink-0">
                                        <img
                                            :src="item.product.image_url || '/images/default-product.png'"
                                            :alt="item.product.name"
                                            class="w-full h-full object-cover"
                                        />
                                    </div>

                                    <!-- Product Details -->
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 mb-1" x-text="item.product.name"></h3>
                                        <p
                                            class="text-sm text-gray-600 mb-2"
                                            x-text="item.product.category?.name"
                                        ></p>

                                        <!-- Specifications -->
                                        <div class="space-y-1 text-sm text-gray-600">
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
                                            <div x-show="item.design_file_path" class="flex items-center space-x-2">
                                                <i class="fas fa-paperclip text-green-600"></i>
                                                <span class="text-green-600">File desain terupload</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Quantity & Price -->
                                    <div class="text-right space-y-3">
                                        <div class="flex items-center space-x-2">
                                            <button
                                                @click="updateQuantity(item.id, item.quantity - 1)"
                                                :disabled="item.quantity <= item.product.minimum_quantity"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50 disabled:opacity-50"
                                            >
                                                <i class="fas fa-minus text-xs"></i>
                                            </button>
                                            <input
                                                type="number"
                                                x-model="item.quantity"
                                                @change="updateQuantity(item.id, item.quantity)"
                                                :min="item.product.minimum_quantity"
                                                class="w-16 text-center border border-gray-300 rounded-lg py-1"
                                            />
                                            <button
                                                @click="updateQuantity(item.id, item.quantity + 1)"
                                                class="w-8 h-8 rounded-full border border-gray-300 flex items-center justify-center hover:bg-gray-50"
                                            >
                                                <i class="fas fa-plus text-xs"></i>
                                            </button>
                                        </div>

                                        <!-- Price -->
                                        <div class="space-y-1">
                                            <div
                                                class="text-lg font-semibold text-gray-900"
                                                x-text="formatPrice(item.subtotal)"
                                            ></div>
                                            <div
                                                class="text-sm text-gray-500"
                                                x-text="`${formatPrice(item.unit_price)} / ${item.product.unit_label}`"
                                            ></div>
                                        </div>

                                        <!-- Remove Button -->
                                        <button
                                            @click="removeItem(item.id)"
                                            class="text-red-600 hover:text-red-700 text-sm"
                                        >
                                            <i class="fas fa-trash"></i>
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
                    <div class="p-6">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Ringkasan Pesanan</h2>

                        <!-- Summary Items -->
                        <div class="space-y-3 mb-6">
                            <div class="flex justify-between text-gray-600">
                                <span>
                                    Subtotal (
                                    <span x-text="getTotalItems()"></span>
                                    item)
                                </span>
                                <span x-text="formatPrice(totalAmount)"></span>
                            </div>
                            <div class="flex justify-between text-gray-600">
                                <span>Estimasi Berat</span>
                                <span x-text="getEstimatedWeight() + ' kg'"></span>
                            </div>
                            <div class="border-t pt-3">
                                <div class="flex justify-between font-semibold text-lg text-gray-900">
                                    <span>Total</span>
                                    <span x-text="formatPrice(totalAmount)"></span>
                                </div>
                                <p class="text-sm text-gray-500 mt-1">*Belum termasuk ongkir & pajak</p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="space-y-3">
                            <button
                                @click="proceedToCheckout()"
                                :disabled="cartItems.length === 0"
                                class="w-full bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                <i class="fas fa-credit-card mr-2"></i>
                                Lanjut ke Checkout
                            </button>

                            <a
                                href="{{ route('products.index') }}"
                                class="w-full border border-gray-300 text-gray-700 py-3 px-4 rounded-lg font-semibold hover:bg-gray-50 transition-colors text-center inline-block"
                            >
                                <i class="fas fa-arrow-left mr-2"></i>
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
                                    <i class="fas fa-tag mr-1"></i>
                                    Punya kode promo?
                                </button>
                                <div x-show="showPromo" x-collapse>
                                    <div class="mt-3 flex space-x-2">
                                        <input
                                            type="text"
                                            placeholder="Kode promo"
                                            class="flex-1 border border-gray-300 rounded-lg px-3 py-2 text-sm"
                                        />
                                        <button
                                            class="bg-gray-900 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-gray-800"
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
                    if (newQuantity < 1) return;

                    this.loading = true;

                    try {
                        const response = await axios.patch(`/cart/${itemId}`, {
                            quantity: newQuantity,
                        });

                        if (response.data.success) {
                            // Update local data
                            const item = this.cartItems.find((i) => i.id === itemId);
                            if (item) {
                                item.quantity = newQuantity;
                                item.subtotal = response.data.cart_item.subtotal;
                            }

                            this.totalAmount = response.data.total_amount;
                            this.showToast('Keranjang berhasil diperbarui', 'success');
                        }
                    } catch (error) {
                        this.showToast('Gagal memperbarui keranjang', 'error');
                        console.error(error);
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
