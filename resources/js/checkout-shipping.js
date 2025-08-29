// public/js/checkout-shipping.js
class CheckoutShipping {
    constructor() {
        this.initializeElements();
        this.bindEvents();
        this.loadInitialData();
    }

    initializeElements() {
        this.provinceSelect = document.getElementById("shipping_province");
        this.citySelect = document.getElementById("shipping_city");
        this.shippingOptionsContainer =
            document.getElementById("shipping-options");
        this.weightInput = document.querySelector('[name="estimated_weight"]');
        this.calculateShippingBtn = document.getElementById(
            "calculate-shipping-btn",
        );
        this.selectedShippingInput = document.querySelector(
            '[name="selected_shipping"]',
        );
        this.totalAmountElement = document.getElementById("total-amount");

        // Loading states
        this.loadingTemplate = '<option value="">Memuat...</option>';
        this.defaultCityTemplate =
            '<option value="">Pilih Kota/Kabupaten</option>';
        this.defaultProvinceTemplate =
            '<option value="">Pilih Provinsi</option>';
    }

    bindEvents() {
        // Province change event
        if (this.provinceSelect) {
            this.provinceSelect.addEventListener("change", (e) => {
                this.onProvinceChange(e.target.value);
            });
        }

        // City change event
        if (this.citySelect) {
            this.citySelect.addEventListener("change", (e) => {
                this.onCityChange();
            });
        }

        // Calculate shipping button
        if (this.calculateShippingBtn) {
            this.calculateShippingBtn.addEventListener("click", () => {
                this.calculateShipping();
            });
        }

        // Auto-calculate when city is selected
        if (this.citySelect) {
            this.citySelect.addEventListener("change", () => {
                if (this.citySelect.value) {
                    setTimeout(() => this.calculateShipping(), 500);
                }
            });
        }
    }

    loadInitialData() {
        // Load provinces if not already loaded
        if (this.provinceSelect && this.provinceSelect.children.length <= 1) {
            this.loadProvinces();
        }

        // Show popular cities initially
        this.loadPopularCities();
    }

    async loadProvinces() {
        try {
            this.setSelectLoading(this.provinceSelect, this.loadingTemplate);

            const response = await fetch("/shipping/provinces");
            const result = await response.json();

            if (result.success && result.data) {
                this.populateProvinces(result.data);
            } else {
                throw new Error(result.message || "Gagal memuat data provinsi");
            }
        } catch (error) {
            console.error("Error loading provinces:", error);
            this.showError("Gagal memuat data provinsi");
            this.provinceSelect.innerHTML = this.defaultProvinceTemplate;
        }
    }

    async loadPopularCities() {
        try {
            const response = await fetch("/shipping/popular-cities");
            const result = await response.json();

            if (result.success && result.data) {
                this.showPopularCities(result.data);
            }
        } catch (error) {
            console.error("Error loading popular cities:", error);
        }
    }

    async onProvinceChange(provinceId) {
        if (!provinceId) {
            this.citySelect.innerHTML = this.defaultCityTemplate;
            this.hidePopularCities();
            return;
        }

        try {
            this.setSelectLoading(this.citySelect, this.loadingTemplate);
            this.hidePopularCities();

            const response = await fetch(
                `/checkout/cities?province_id=${provinceId}`,
            );
            const result = await response.json();

            if (result.success && result.cities) {
                this.populateCities(result.cities);
            } else {
                throw new Error(result.message || "Gagal memuat data kota");
            }
        } catch (error) {
            console.error("Error loading cities:", error);
            this.showError("Gagal memuat data kota");
            this.citySelect.innerHTML = this.defaultCityTemplate;
        }
    }

    onCityChange() {
        const selectedOption =
            this.citySelect.options[this.citySelect.selectedIndex];

        if (selectedOption.value) {
            // Store city data for later use
            this.selectedCityData = {
                city_id: selectedOption.value,
                city_name: selectedOption.text,
                province_name: selectedOption.dataset.province || "",
            };

            // Hide popular cities when city is selected
            this.hidePopularCities();

            // Show calculate shipping button
            if (this.calculateShippingBtn) {
                this.calculateShippingBtn.style.display = "block";
            }
        }
    }

    async calculateShipping() {
        if (!this.citySelect.value || !this.weightInput.value) {
            this.showError("Pilih kota tujuan terlebih dahulu");
            return;
        }

        try {
            this.showShippingLoading();

            const formData = new FormData();
            formData.append("city_id", this.citySelect.value);
            formData.append("weight", this.weightInput.value);

            const response = await fetch("/checkout/calculate-shipping", {
                method: "POST",
                body: formData,
                headers: {
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]',
                    ).content,
                },
            });

            const result = await response.json();

            if (result.success && result.shipping_options) {
                this.displayShippingOptions(result.shipping_options);
            } else {
                throw new Error(
                    result.message || "Gagal menghitung ongkos kirim",
                );
            }
        } catch (error) {
            console.error("Error calculating shipping:", error);
            this.showError("Gagal menghitung ongkos kirim");
            this.hideShippingOptions();
        }
    }

    populateProvinces(provinces) {
        let html = this.defaultProvinceTemplate;

        provinces.forEach((province) => {
            html += `<option value="${province.province_id}">${province.province}</option>`;
        });

        this.provinceSelect.innerHTML = html;
    }

    populateCities(cities) {
        let html = this.defaultCityTemplate;

        cities.forEach((city) => {
            const cityType = city.type === "Kabupaten" ? "Kab." : city.type;
            html += `<option value="${city.city_id}" data-province="${city.province}">${cityType} ${city.city_name}</option>`;
        });

        this.citySelect.innerHTML = html;
    }

    showPopularCities(cities) {
        const popularCitiesContainer =
            document.getElementById("popular-cities");
        if (!popularCitiesContainer) return;

        let html =
            '<div class="popular-cities"><h4>Kota Populer:</h4><div class="city-buttons">';

        cities.slice(0, 8).forEach((city) => {
            const cityType = city.type === "Kabupaten" ? "Kab." : city.type;
            html += `<button type="button" class="btn btn-outline-secondary btn-sm city-btn"
                     data-city-id="${city.city_id}"
                     data-city-name="${city.city_name}"
                     data-city-type="${city.type}"
                     data-province="${city.province}">
                     ${cityType} ${city.city_name}
                   </button>`;
        });

        html += "</div></div>";
        popularCitiesContainer.innerHTML = html;

        // Bind click events to city buttons
        popularCitiesContainer.querySelectorAll(".city-btn").forEach((btn) => {
            btn.addEventListener("click", (e) => {
                this.selectPopularCity(e.target);
            });
        });

        popularCitiesContainer.style.display = "block";
    }

    hidePopularCities() {
        const popularCitiesContainer =
            document.getElementById("popular-cities");
        if (popularCitiesContainer) {
            popularCitiesContainer.style.display = "none";
        }
    }

    selectPopularCity(button) {
        const cityId = button.dataset.cityId;
        const cityName = button.dataset.cityName;
        const cityType = button.dataset.cityType;
        const provinceName = button.dataset.province;

        // Add option to city select
        const displayName = `${cityType === "Kabupaten" ? "Kab." : cityType} ${cityName}`;
        this.citySelect.innerHTML = `
            ${this.defaultCityTemplate}
            <option value="${cityId}" selected data-province="${provinceName}">${displayName}</option>
        `;

        // Store city data
        this.selectedCityData = {
            city_id: cityId,
            city_name: cityName,
            province_name: provinceName,
        };

        // Hide popular cities
        this.hidePopularCities();

        // Auto-calculate shipping
        setTimeout(() => this.calculateShipping(), 500);
    }

    displayShippingOptions(options) {
        if (!this.shippingOptionsContainer || options.length === 0) {
            this.showError("Tidak ada layanan pengiriman yang tersedia");
            return;
        }

        let html =
            '<div class="shipping-options"><h5>Pilih Layanan Pengiriman:</h5>';

        options.forEach((option, index) => {
            const isChecked = index === 0 ? "checked" : "";
            const etdDisplay = option.etd
                ? `${option.etd} hari`
                : "Sesuai estimasi";

            html += `
                <div class="shipping-option">
                    <input type="radio" id="shipping_${index}" name="shipping_service"
                           value="${option.service}" ${isChecked}
                           data-cost="${option.cost}"
                           data-courier="${option.courier}"
                           data-service="${option.service}"
                           data-etd="${option.etd}"
                           data-service-name="${option.service_name}">
                    <label for="shipping_${index}" class="shipping-label">
                        <div class="shipping-info">
                            <strong>${option.service_name}</strong>
                            <div class="shipping-desc">${option.description}</div>
                            <div class="shipping-etd">Estimasi: ${etdDisplay}</div>
                        </div>
                        <div class="shipping-cost">
                            Rp ${this.formatNumber(option.cost)}
                        </div>
                    </label>
                </div>
            `;
        });

        html += "</div>";
        this.shippingOptionsContainer.innerHTML = html;

        // Bind change events to shipping options
        this.shippingOptionsContainer
            .querySelectorAll('input[name="shipping_service"]')
            .forEach((radio) => {
                radio.addEventListener("change", (e) => {
                    this.onShippingOptionChange(e.target);
                });
            });

        // Auto-select first option and update total
        const firstOption = this.shippingOptionsContainer.querySelector(
            'input[name="shipping_service"]:checked',
        );
        if (firstOption) {
            this.onShippingOptionChange(firstOption);
        }

        this.shippingOptionsContainer.style.display = "block";
    }

    onShippingOptionChange(selectedOption) {
        // Update hidden form fields
        const form = document.querySelector("form[data-checkout-form]");
        if (form) {
            // Create or update hidden fields
            this.updateHiddenField(
                form,
                "shipping_cost",
                selectedOption.dataset.cost,
            );
            this.updateHiddenField(
                form,
                "shipping_courier",
                selectedOption.dataset.courier,
            );
            this.updateHiddenField(
                form,
                "shipping_service",
                selectedOption.dataset.service,
            );
            this.updateHiddenField(
                form,
                "shipping_etd",
                selectedOption.dataset.etd,
            );

            // Update city info
            if (this.selectedCityData) {
                this.updateHiddenField(
                    form,
                    "shipping_province_name",
                    this.selectedCityData.province_name,
                );
                this.updateHiddenField(
                    form,
                    "shipping_city_name",
                    this.selectedCityData.city_name,
                );
                this.updateHiddenField(
                    form,
                    "shipping_province_id",
                    this.provinceSelect.value,
                );
                this.updateHiddenField(
                    form,
                    "shipping_city_id",
                    this.citySelect.value,
                );
            }
        }

        // Update total amount display
        this.updateTotalAmount(parseFloat(selectedOption.dataset.cost));
    }

    updateHiddenField(form, name, value) {
        let field = form.querySelector(`input[name="${name}"]`);
        if (!field) {
            field = document.createElement("input");
            field.type = "hidden";
            field.name = name;
            form.appendChild(field);
        }
        field.value = value;
    }

    updateTotalAmount(shippingCost) {
        if (!this.totalAmountElement) return;

        const subtotal = parseFloat(
            this.totalAmountElement.dataset.subtotal || 0,
        );
        const tax = parseFloat(this.totalAmountElement.dataset.tax || 0);
        const total = subtotal + shippingCost + tax;

        this.totalAmountElement.textContent = `Rp ${this.formatNumber(total)}`;

        // Update shipping cost display
        const shippingCostElement = document.getElementById(
            "shipping-cost-display",
        );
        if (shippingCostElement) {
            shippingCostElement.textContent = `Rp ${this.formatNumber(shippingCost)}`;
        }
    }

    showShippingLoading() {
        if (this.shippingOptionsContainer) {
            this.shippingOptionsContainer.innerHTML =
                '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Memuat...</span></div><p>Menghitung ongkos kirim...</p></div>';
            this.shippingOptionsContainer.style.display = "block";
        }
    }

    hideShippingOptions() {
        if (this.shippingOptionsContainer) {
            this.shippingOptionsContainer.style.display = "none";
        }
    }

    setSelectLoading(selectElement, loadingTemplate) {
        selectElement.innerHTML = loadingTemplate;
        selectElement.disabled = true;
        setTimeout(() => {
            selectElement.disabled = false;
        }, 1000);
    }

    showError(message) {
        // You can customize this to show errors in your preferred way
        console.error(message);

        const errorContainer = document.getElementById("shipping-error");
        if (errorContainer) {
            errorContainer.innerHTML = `<div class="alert alert-danger">${message}</div>`;
            errorContainer.style.display = "block";

            setTimeout(() => {
                errorContainer.style.display = "none";
            }, 5000);
        } else {
            alert(message);
        }
    }

    formatNumber(num) {
        return new Intl.NumberFormat("id-ID").format(num);
    }
}

// Initialize when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
    if (document.querySelector("[data-checkout-form]")) {
        new CheckoutShipping();
    }
});

// CSS styles (add to your CSS file)
const shippingStyles = `
.popular-cities {
    margin: 1rem 0;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 0.375rem;
}

.popular-cities h4 {
    font-size: 1rem;
    margin-bottom: 0.75rem;
    color: #495057;
}

.city-buttons {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.city-btn {
    font-size: 0.875rem;
    padding: 0.375rem 0.75rem;
    border-radius: 0.25rem;
    border: 1px solid #dee2e6;
    background: white;
    color: #495057;
    cursor: pointer;
    transition: all 0.2s;
}

.city-btn:hover {
    background: #e9ecef;
    border-color: #adb5bd;
}

.shipping-options {
    margin: 1rem 0;
}

.shipping-option {
    margin-bottom: 0.75rem;
}

.shipping-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s;
}

.shipping-label:hover {
    border-color: #0d6efd;
    background: #f8f9fa;
}

.shipping-option input[type="radio"]:checked + .shipping-label {
    border-color: #0d6efd;
    background: #e7f3ff;
}

.shipping-info {
    flex: 1;
}

.shipping-desc {
    font-size: 0.875rem;
    color: #6c757d;
    margin: 0.25rem 0;
}

.shipping-etd {
    font-size: 0.75rem;
    color: #6c757d;
}

.shipping-cost {
    font-weight: 600;
    color: #0d6efd;
    font-size: 1.1rem;
}

.shipping-option input[type="radio"] {
    position: absolute;
    opacity: 0;
}

#shipping-error {
    margin: 1rem 0;
}
`;

// Inject styles
const styleSheet = document.createElement("style");
styleSheet.textContent = shippingStyles;
document.head.appendChild(styleSheet);
