<!-- Action Buttons -->
<div class="flex flex-col sm:flex-row gap-4 mt-8">
    <button
        @click="addToCart"
        :disabled="isAddingToCart || isBuyingNow || !isOrderValid()"
        class="flex-1 bg-tria-orange hover:to-tria-orange-light text-white font-bold py-4 px-8 rounded-xl flex items-center justify-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl border-0 focus:outline-none focus:ring-4 focus:ring-orange-300"
    >
        <svg
            x-show="isAddingToCart"
            class="animate-spin h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
        </svg>
        <svg x-show="!isAddingToCart" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M3 3h2l.4 2M7 13h10l4-8H5.4m0 0L7 13m0 0l-2.5 5M7 13l2.5 5m0 0L17 18m-7.5 0h7.5"
            ></path>
        </svg>
        <span x-show="isAddingToCart" class="text-sm">Menambahkan...</span>
        <span x-show="!isAddingToCart" class="text-sm font-semibold">Tambah ke Keranjang</span>
    </button>

    <button
        @click="buyNow"
        :disabled="isAddingToCart || isBuyingNow || !isOrderValid()"
        class="flex-1 bg-tria-navy hover:to-tria-navy-light text-white font-bold py-4 px-8 rounded-xl flex items-center justify-center space-x-3 disabled:opacity-50 disabled:cursor-not-allowed transform hover:scale-105 active:scale-95 transition-all duration-200 shadow-lg hover:shadow-xl border-0 focus:outline-none focus:ring-4 focus:ring-blue-300"
    >
        <svg
            x-show="isBuyingNow"
            class="animate-spin h-5 w-5"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
        >
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path
                class="opacity-75"
                fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
            ></path>
        </svg>
        <svg x-show="!isBuyingNow" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <span x-show="isBuyingNow" class="text-sm">Memproses...</span>
        <span x-show="!isBuyingNow" class="text-sm font-semibold">Beli Sekarang</span>
    </button>
</div>
