<script>
    function cartDropdown() {
        return {
            open: false,
            cartItems: [],
            cartCount: 0,
            loading: false,

            init() {
                this.loadCart();
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.loadCart();
                }
            },

            async loadCart() {
                try {
                    const response = await fetch('/cart', {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.cartItems = data.cartItems || [];
                        // Count unique products, not total quantity
                        this.cartCount = this.cartItems.length;
                        this.renderCartItems();
                        this.updateGlobalCartCount();
                    }
                } catch (error) {
                    console.error('Failed to load cart:', error);
                    this.cartItems = [];
                    this.cartCount = 0;
                }
            },

            renderCartItems() {
                const container = document.getElementById('cart-items-container');
                if (!container) return;

                if (this.cartItems.length === 0) {
                    container.innerHTML = '';
                    return;
                }

                container.innerHTML = this.cartItems
                    .map(
                        (item) => `
                    <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-lg mb-3 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-16 h-16 bg-gray-200 rounded-lg overflow-hidden">
                            <img
                                src="${item.product && item.product.featured_image ? '/storage/' + item.product.featured_image : '/images/no-image.png'}"
                                class="w-full h-full object-cover"
                                alt="${item.product ? item.product.name : 'Product'}"
                                onerror="this.src='/images/no-image.png'"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-gray-900 text-base truncate mb-1">${item.product ? item.product.name : 'Produk tidak ditemukan'}</h4>
                            <div class="flex items-center gap-3 mb-1">
                                <span class="text-sm text-gray-600">Qty: ${item.quantity || 1}</span>
                                <span class="text-sm text-gray-400">•</span>
                                <span class="text-sm font-semibold text-green-600">
                                    ${item.unit_price ? 'Rp ' + parseFloat(item.unit_price).toLocaleString('id-ID') : 'Harga tidak tersedia'}
                                </span>
                            </div>
                            <div class="text-xs text-gray-500">
                                Total: ${item.subtotal ? 'Rp ' + parseFloat(item.subtotal).toLocaleString('id-ID') : 'Total tidak tersedia'}
                            </div>
                        </div>
                        <button
                            class="flex-shrink-0 w-10 h-10 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors remove-item-btn"
                            data-item-id="${item.id}"
                            title="Hapus dari keranjang"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `,
                    )
                    .join('');

                // Add event listeners to remove buttons
                container.querySelectorAll('.remove-item-btn').forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const itemId = btn.getAttribute('data-item-id');
                        this.removeItem(itemId);
                    });
                });
            },

            updateGlobalCartCount() {
                // Update cart count in header
                const headerElement = document.querySelector('[x-data*="cartCount"]');
                if (headerElement && headerElement.__x) {
                    headerElement.__x.$data.cartCount = this.cartCount;
                }
            },

            async removeItem(id) {
                try {
                    const response = await fetch(`/cart/item/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN':
                                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            Accept: 'application/json',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            this.loadCart(); // Reload cart
                        }
                    }
                } catch (error) {
                    console.error('Failed to remove item:', error);
                }
            },
        };
    }

    // Mobile cart dropdown function
    function mobileCartDropdown() {
        return {
            open: false,
            cartItems: [],
            cartCount: 0,
            loading: false,

            init() {
                this.loadCart();
                // Listen for cart updates from other components
                window.addEventListener('cart-updated', (event) => {
                    this.cartCount = event.detail.count;
                    if (this.open) {
                        this.loadCart();
                    }
                });
            },

            toggleDropdown() {
                this.open = !this.open;
                if (this.open) {
                    this.loadCart();
                }
            },

            async loadCart() {
                try {
                    const response = await fetch('/cart', {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.cartItems = data.cartItems || [];
                        this.cartCount = this.cartItems.length;
                        this.renderCartItems();
                        this.updateGlobalCartCount();
                    }
                } catch (error) {
                    console.error('Failed to load mobile cart:', error);
                    this.cartItems = [];
                    this.cartCount = 0;
                }
            },

            renderCartItems() {
                const container = document.getElementById('mobile-cart-items-container');
                if (!container) return;

                if (this.cartItems.length === 0) {
                    container.innerHTML = '';
                    return;
                }

                container.innerHTML = this.cartItems
                    .map(
                        (item) => `
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg mb-2 hover:bg-gray-100 transition-colors">
                        <div class="flex-shrink-0 w-12 h-12 bg-gray-200 rounded-lg overflow-hidden">
                            <img
                                src="${item.product && item.product.featured_image ? '/storage/' + item.product.featured_image : '/images/no-image.png'}"
                                class="w-full h-full object-cover"
                                alt="${item.product ? item.product.name : 'Product'}"
                                onerror="this.src='/images/no-image.png'"
                            />
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm truncate mb-1">${item.product ? item.product.name : 'Produk tidak ditemukan'}</h4>
                            <div class="flex items-center gap-2 text-xs">
                                <span class="text-gray-600">Qty: ${item.quantity || 1}</span>
                                <span class="text-gray-400">•</span>
                                <span class="font-medium text-green-600">
                                    ${item.unit_price ? 'Rp ' + parseFloat(item.unit_price).toLocaleString('id-ID') : 'Harga tidak tersedia'}
                                </span>
                            </div>
                        </div>
                        <button
                            class="flex-shrink-0 w-8 h-8 flex items-center justify-center text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition-colors mobile-remove-item-btn"
                            data-item-id="${item.id}"
                            title="Hapus dari keranjang"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                `,
                    )
                    .join('');

                // Add event listeners to remove buttons
                container.querySelectorAll('.mobile-remove-item-btn').forEach((btn) => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const itemId = btn.getAttribute('data-item-id');
                        this.removeItem(itemId);
                    });
                });
            },

            updateGlobalCartCount() {
                // Update main cart count
                const headerElement = document.querySelector('[x-data*="cartCount"]');
                if (headerElement && headerElement.__x) {
                    headerElement.__x.$data.cartCount = this.cartCount;
                }

                // Update desktop cart count if it exists
                const desktopCartElements = document.querySelectorAll('[x-data*="cartDropdown"]');
                desktopCartElements.forEach((element) => {
                    if (element.__x && element.__x.$data.cartCount !== undefined) {
                        element.__x.$data.cartCount = this.cartCount;
                    }
                });
            },

            async removeItem(id) {
                try {
                    const response = await fetch(`/cart/item/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN':
                                document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                            Accept: 'application/json',
                        },
                    });

                    if (response.ok) {
                        const data = await response.json();
                        if (data.success) {
                            this.loadCart(); // Reload cart
                        }
                    }
                } catch (error) {
                    console.error('Failed to remove item from mobile cart:', error);
                }
            },
        };
    }

    // User dropdown function
    function userDropdown() {
        return {
            open: false,

            toggleDropdown() {
                this.open = !this.open;
            },
        };
    }

    // Search dropdown function
    function searchDropdown(type = 'desktop') {
        return {
            query: '',
            searchResults: [],
            loading: false,
            showDropdown: false,
            highlightedIndex: -1,
            searchController: null,

            init() {
                // Initialize search functionality
            },

            async search() {
                if (this.query.length < 2) {
                    this.searchResults = [];
                    this.showDropdown = false;
                    return;
                }

                // Cancel previous request
                if (this.searchController) {
                    this.searchController.abort();
                }

                this.loading = true;
                this.searchController = new AbortController();

                try {
                    const response = await fetch(`/product/search?q=${encodeURIComponent(this.query)}`, {
                        method: 'GET',
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: this.searchController.signal,
                    });

                    if (response.ok) {
                        const data = await response.json();
                        this.searchResults = data.data || [];
                        this.showDropdown = true;
                        this.highlightedIndex = -1;
                    } else {
                        this.searchResults = [];
                        this.showDropdown = false;
                    }
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        console.error('Search error:', error);
                        this.searchResults = [];
                        this.showDropdown = false;
                    }
                } finally {
                    this.loading = false;
                }
            },

            hideDropdown() {
                this.showDropdown = false;
                this.highlightedIndex = -1;
            },

            highlightNext() {
                if (!this.showDropdown || this.searchResults.length === 0) return;

                this.highlightedIndex = (this.highlightedIndex + 1) % this.searchResults.length;
            },

            highlightPrevious() {
                if (!this.showDropdown || this.searchResults.length === 0) return;

                this.highlightedIndex =
                    this.highlightedIndex <= 0 ? this.searchResults.length - 1 : this.highlightedIndex - 1;
            },

            selectHighlighted() {
                if (this.highlightedIndex >= 0 && this.searchResults[this.highlightedIndex]) {
                    this.selectProduct(this.searchResults[this.highlightedIndex]);
                } else {
                    this.handleSubmit();
                }
            },

            selectProduct(product) {
                // Navigate to product page
                window.location.href = product.url;
            },

            handleSubmit() {
                if (this.query.trim().length >= 2) {
                    // Create a search results page or redirect to products with search parameter
                    const searchUrl = new URL('/product/', window.location.origin);
                    searchUrl.searchParams.set('search', this.query.trim());
                    window.location.href = searchUrl.toString();
                }
            },
        };
    }
</script>
