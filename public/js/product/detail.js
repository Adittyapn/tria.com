/**
 * Product Detail Alpine.js Component
 * Handles product customization, cart operations, and validation
 */
function productDetail() {
    return {
        // ===============================
        // DATA PROPERTIES
        // ===============================
        currentImage: "",
        customLength: 0,
        customWidth: 0,
        totalMeters: 0,
        quantity: 1,
        selectedPreset: null,
        selectedMaterial: "",
        selectedFinishing: [],
        needDesignService: false,

        // Price calculations
        basePrice: 0,
        originalBasePrice: 0,
        materialAdjustment: 0,
        finishingAdjustment: 0,
        volumeDiscount: 0,
        volumeDiscountPercent: 0,
        designServiceCost: 0,
        presetMultiplier: 1,

        // File upload
        designFile: null,
        designNotes: "",
        dragOver: false,
        uploadProgress: 0,
        isUploading: false,
        fileUploadSuccess: false,

        // UI states
        isLoading: false,
        isAddingToCart: false,
        isBuyingNow: false,
        errors: {},
        showSuccessModal: false,
        recommendedProducts: [],
        isLoadingRecommended: false,

        // Product constants from backend
        productData: {},

        // ===============================
        // INITIALIZATION
        // ===============================
        init() {
            this.initProductData();
            this.setDefaults();
            this.calculateVolumeDiscount();
            this.updateDesignServiceCost();
        },

        initProductData() {
            // Will be populated from backend data
            this.currentImage = this.productData.featuredImage || "";
            this.quantity = this.productData.minimumQuantity || 1;
            this.basePrice = this.productData.basePrice || 0;
        },

        setDefaults() {
            if (
                this.productData.materialOptions?.length > 0 &&
                !this.selectedMaterial
            ) {
                this.selectedMaterial =
                    this.productData.materialOptions[0].name;
                this.updateMaterialPrice(
                    this.productData.materialOptions[0].price_adjustment || 0,
                );
            }
        },

        // ===============================
        // IMAGE GALLERY METHODS
        // ===============================
        changeImage(img) {
            this.currentImage = img;
        },

        openLightbox() {
            const lightbox = document.createElement("div");
            lightbox.className =
                "fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50";
            lightbox.innerHTML = `
                <div class="relative max-w-4xl max-h-full p-4">
                    <img src="${this.currentImage}" class="max-w-full max-h-full object-contain">
                    <button class="absolute top-4 right-4 text-white text-3xl hover:text-gray-300" onclick="this.parentElement.parentElement.remove()">×</button>
                </div>
            `;
            document.body.appendChild(lightbox);
        },

        // ===============================
        // VALIDATION METHODS
        // ===============================
        isOrderValid() {
            if (this.quantity < this.productData.minimumQuantity) {
                return false;
            }

            if (this.productData.allowsCustomSize && !this.selectedPreset) {
                if (this.productData.pricingType === "per_meter_square") {
                    if (
                        !this.customLength ||
                        !this.customWidth ||
                        this.customLength <= 0 ||
                        this.customWidth <= 0
                    ) {
                        return false;
                    }
                } else if (
                    this.productData.pricingType === "per_meter_linear"
                ) {
                    if (!this.customLength || this.customLength <= 0) {
                        return false;
                    }
                }
            }

            if (this.errors.customSize) {
                return false;
            }

            if (this.productData.requiresDesignFile && !this.designFile) {
                return false;
            }

            if (this.errors.designFile) {
                return false;
            }

            return true;
        },

        getValidationMessage() {
            if (this.quantity < this.productData.minimumQuantity) {
                return `Minimal pemesanan ${this.productData.minimumQuantity} ${this.productData.unitLabel}`;
            }

            if (this.productData.allowsCustomSize && !this.selectedPreset) {
                if (this.productData.pricingType === "per_meter_square") {
                    if (
                        !this.customLength ||
                        !this.customWidth ||
                        this.customLength <= 0 ||
                        this.customWidth <= 0
                    ) {
                        return "Harap masukkan ukuran panjang dan lebar untuk produk ini";
                    }
                } else if (
                    this.productData.pricingType === "per_meter_linear"
                ) {
                    if (!this.customLength || this.customLength <= 0) {
                        return "Harap masukkan ukuran panjang untuk produk ini";
                    }
                }
            }

            if (this.errors.customSize) {
                return this.errors.customSize;
            }

            if (this.productData.requiresDesignFile && !this.designFile) {
                return "File desain wajib diupload";
            }

            if (this.errors.designFile) {
                return this.errors.designFile;
            }

            return "";
        },

        // ===============================
        // CART & CHECKOUT METHODS
        // ===============================
        async addToCart() {
            if (!this.isOrderValid()) {
                this.showError("Harap lengkapi semua data yang diperlukan");
                return;
            }

            this.isAddingToCart = true;

            try {
                const formData = this.buildFormData();
                const response = await fetch(
                    `/cart/add/${this.productData.id}`,
                    {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            Accept: "application/json",
                        },
                    },
                );

                const result = await response.json();

                if (result.success) {
                    this.updateCartCount(result.cart_count);
                    this.showSuccessModal = true;
                    this.loadRecommendedProducts();
                } else {
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                    this.showError(result.message || "Terjadi kesalahan");
                }
            } catch (error) {
                console.error("Add to cart error:", error);
                this.showError("Terjadi kesalahan jaringan");
            } finally {
                this.isAddingToCart = false;
            }
        },

        async buyNow() {
            if (!this.isOrderValid()) {
                this.showError("Harap lengkapi semua data yang diperlukan");
                return;
            }

            this.isBuyingNow = true;

            try {
                const formData = this.buildFormData();
                const response = await fetch(
                    `/checkout/buy-now/${this.productData.id}`,
                    {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": document
                                .querySelector('meta[name="csrf-token"]')
                                .getAttribute("content"),
                            Accept: "application/json",
                        },
                    },
                );

                const result = await response.json();

                if (result.success) {
                    if (result.redirect_url) {
                        window.location.href = result.redirect_url;
                    } else {
                        this.showSuccess(
                            result.message || "Pesanan berhasil dibuat",
                        );
                    }
                } else {
                    if (result.errors) {
                        this.errors = result.errors;
                    }
                    this.showError(result.message || "Terjadi kesalahan");
                }
            } catch (error) {
                console.error("Buy now error:", error);
                this.showError("Terjadi kesalahan jaringan");
            } finally {
                this.isBuyingNow = false;
            }
        },

        // ===============================
        // UTILITY METHODS
        // ===============================
        buildFormData() {
            const formData = new FormData();
            formData.append("quantity", this.quantity);
            formData.append("selected_material", this.selectedMaterial || "");

            if (this.selectedFinishing.length > 0) {
                this.selectedFinishing.forEach((finishing, index) => {
                    formData.append(`selected_finishing[${index}]`, finishing);
                });
            }

            formData.append("design_notes", this.designNotes || "");
            formData.append(
                "requires_design_service",
                this.needDesignService ? "1" : "0",
            );

            if (
                this.productData.allowsCustomSize &&
                (this.customLength > 0 || this.customWidth > 0)
            ) {
                formData.append("custom_size_width", this.customWidth * 100);
                formData.append("custom_size_height", this.customLength * 100);
            }

            if (this.selectedPreset) {
                formData.append("selected_preset", this.selectedPreset);
            }

            if (this.designFile) {
                formData.append("design_file", this.designFile);
            }

            return formData;
        },

        showSuccess(message) {
            // Toast notification implementation
            this.createToast(message, "success");
        },

        showError(message) {
            // Toast notification implementation
            this.createToast(message, "error");
        },

        createToast(message, type) {
            const toast = document.createElement("div");
            const bgColor = type === "success" ? "bg-green-500" : "bg-red-500";
            const icon =
                type === "success"
                    ? '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>'
                    : '<svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

            toast.className = `toast-notification fixed top-4 right-4 ${bgColor} text-white px-6 py-4 rounded-lg shadow-2xl z-50 flex items-center space-x-3 min-w-[300px] max-w-md`;
            toast.innerHTML = `
                <div class="flex-shrink-0">
                    ${icon}
                </div>
                <div class="flex-1">
                    <p class="font-medium">${type === "success" ? "Berhasil!" : "Error!"}</p>
                    <p class="text-sm opacity-90">${message}</p>
                </div>
                <button onclick="this.parentElement.remove()" class="flex-shrink-0 text-white hover:text-gray-200 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            `;

            document.body.appendChild(toast);

            setTimeout(
                () => {
                    toast.style.opacity = "0";
                    toast.style.transform = "translateX(100%)";
                    toast.style.transition = "all 0.3s ease-in";
                    setTimeout(() => toast.remove(), 300);
                },
                type === "success" ? 3000 : 5000,
            );
        },

        formatPrice(price) {
            return new Intl.NumberFormat("id-ID", {
                style: "currency",
                currency: "IDR",
                minimumFractionDigits: 0,
            }).format(price);
        },

        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (
                parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
            );
        },

        // ===============================
        // FILE UPLOAD METHODS
        // ===============================
        handleFileSelect(event) {
            const file = event.target.files[0];
            if (file) {
                this.validateAndUploadFile(file);
            }
        },

        handleFileDrop(event) {
            this.dragOver = false;
            const file = event.dataTransfer.files[0];
            if (file) {
                this.validateAndUploadFile(file);
            }
        },

        validateAndUploadFile(file) {
            // Reset previous errors
            this.errors.designFile = "";
            this.fileUploadSuccess = false;

            // Validate file size
            const maxSize = this.productData.maxFileSize * 1024 * 1024; // Convert MB to bytes
            if (file.size > maxSize) {
                this.errors.designFile = `Ukuran file terlalu besar. Maksimal ${this.productData.maxFileSize}MB`;
                return;
            }

            // Validate file format
            const fileExtension = file.name.split(".").pop().toLowerCase();
            if (!this.productData.allowedFormats.includes(fileExtension)) {
                this.errors.designFile = `Format file tidak didukung. Gunakan: ${this.productData.allowedFormats.join(", ")}`;
                return;
            }

            // Simulate upload with progress
            this.isUploading = true;
            this.uploadProgress = 0;

            // Simulate upload progress
            const progressInterval = setInterval(() => {
                this.uploadProgress += 10;
                if (this.uploadProgress >= 90) {
                    clearInterval(progressInterval);
                }
            }, 100);

            // Simulate upload completion after 1.5 seconds
            setTimeout(() => {
                clearInterval(progressInterval);
                this.uploadProgress = 100;

                setTimeout(() => {
                    this.designFile = file;
                    this.isUploading = false;
                    this.uploadProgress = 0;
                    this.fileUploadSuccess = true;

                    // Show success notification
                    this.showSuccess(`File "${file.name}" berhasil diupload!`);

                    // Hide success message after 3 seconds
                    setTimeout(() => {
                        this.fileUploadSuccess = false;
                    }, 3000);
                }, 300);
            }, 1500);
        },

        removeFile() {
            this.designFile = null;
            this.fileUploadSuccess = false;
            this.errors.designFile = "";

            // Reset file input
            const fileInput = document.getElementById("design-file");
            if (fileInput) {
                fileInput.value = "";
            }
        },

        // ===============================
        // QUANTITY METHODS
        // ===============================
        increaseQuantity() {
            this.quantity += this.productData.stepQuantity || 1;
            this.calculateVolumeDiscount();
        },

        decreaseQuantity() {
            const newQuantity =
                this.quantity - (this.productData.stepQuantity || 1);
            if (newQuantity >= this.productData.minimumQuantity) {
                this.quantity = newQuantity;
                this.calculateVolumeDiscount();
            }
        },

        validateQuantity() {
            if (this.quantity < this.productData.minimumQuantity) {
                this.quantity = this.productData.minimumQuantity;
            }
            this.calculateVolumeDiscount();
        },

        // ===============================
        // PRICING CALCULATION METHODS
        // ===============================
        calculateSubtotal() {
            let subtotal = this.basePrice;

            // Apply preset multiplier
            subtotal *= this.presetMultiplier;

            // Calculate based on pricing type
            if (this.productData.pricingType === "per_meter_square") {
                subtotal *= this.totalMeters;
            } else if (this.productData.pricingType === "per_meter_linear") {
                subtotal *= this.customLength;
            }

            // Add material adjustment
            subtotal += this.materialAdjustment;

            // Add finishing adjustment
            subtotal += this.finishingAdjustment;

            return subtotal * this.quantity;
        },

        calculateFinalPrice() {
            let finalPrice = this.calculateSubtotal();

            // Apply volume discount
            finalPrice -= this.volumeDiscount;

            // Add design service cost
            finalPrice += this.designServiceCost;

            return Math.max(0, finalPrice);
        },

        calculateVolumeDiscount() {
            if (
                !this.productData.volumePricing ||
                this.productData.volumePricing.length === 0
            ) {
                this.volumeDiscount = 0;
                this.volumeDiscountPercent = 0;
                return;
            }

            const subtotal = this.calculateSubtotal();
            let bestDiscount = 0;
            let bestPercent = 0;

            this.productData.volumePricing.forEach((tier) => {
                if (this.quantity >= tier.min_quantity) {
                    const discount = (subtotal * tier.discount_percent) / 100;
                    if (discount > bestDiscount) {
                        bestDiscount = discount;
                        bestPercent = tier.discount_percent;
                    }
                }
            });

            this.volumeDiscount = bestDiscount;
            this.volumeDiscountPercent = bestPercent;
        },

        updateMaterialPrice(adjustment) {
            this.materialAdjustment = adjustment;
        },

        updateFinishingPrice(finishings) {
            this.finishingAdjustment = finishings.reduce(
                (sum, f) => sum + (f.price || 0),
                0,
            );
        },

        updateDesignServiceCost() {
            this.designServiceCost = this.needDesignService ? 50000 : 0;
        },

        selectPresetSize(name, dimensions, multiplier) {
            this.selectedPreset = name;
            this.presetMultiplier = multiplier || 1;
            this.customLength = 0;
            this.customWidth = 0;
            this.totalMeters = 0;
            this.errors.customSize = "";
        },

        // ===============================
        // RECOMMENDED PRODUCTS
        // ===============================
        async loadRecommendedProducts() {
            this.isLoadingRecommended = true;
            try {
                const response = await fetch(
                    `/api/products/${this.productData.slug}/recommended`,
                );
                const result = await response.json();
                if (result.success) {
                    this.recommendedProducts = result.products;
                }
            } catch (error) {
                console.error("Failed to load recommended products:", error);
            } finally {
                this.isLoadingRecommended = false;
            }
        },

        updateCartCount(count) {
            const cartBadge = document.querySelector(".cart-count");
            if (cartBadge) {
                cartBadge.textContent = count;
                cartBadge.classList.remove("hidden");
            }
        },

        closeSuccessModal() {
            this.showSuccessModal = false;
        },
    };
}
