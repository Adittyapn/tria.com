@props(['type' => 'desktop'])

@if ($type === 'mobile')
    <!-- Mobile Search -->
    <div class="mt-3">
        <div class="relative" x-data="searchDropdown('mobile')">
            <form @submit.prevent="handleSubmit()">
                <input
                    type="text"
                    placeholder="Cari produk..."
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @focus="showDropdown = true"
                    @keydown.escape="hideDropdown()"
                    @keydown.arrow-down.prevent="highlightNext()"
                    @keydown.arrow-up.prevent="highlightPrevious()"
                    @keydown.enter.prevent="selectHighlighted()"
                    class="w-full px-4 py-2.5 pr-12 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 border border-gray-300"
                    style="focus:ring-color: #000334;"
                />
                <button
                    type="submit"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-600"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </button>
            </form>

            <x-header.search-dropdown type="mobile" />
        </div>
    </div>
@else
    <!-- Desktop Search Bar -->
    <div class="flex-1 max-w-xl mx-8">
        <div class="relative" x-data="searchDropdown('desktop')">
            <form @submit.prevent="handleSubmit()">
                <input
                    type="text"
                    placeholder="Cari produk yang kamu inginkan..."
                    x-model="query"
                    @input.debounce.300ms="search()"
                    @focus="showDropdown = true"
                    @keydown.escape="hideDropdown()"
                    @keydown.arrow-down.prevent="highlightNext()"
                    @keydown.arrow-up.prevent="highlightPrevious()"
                    @keydown.enter.prevent="selectHighlighted()"
                    class="w-full px-4 py-3 pr-12 rounded-lg text-gray-800 placeholder-gray-500 focus:outline-none focus:ring-2 border border-gray-300"
                    style="focus:ring-color: #000334;"
                />
                <button
                    type="submit"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-md hover:opacity-90 transition-colors"
                    style="background-color: #000334"
                >
                    <svg
                        class="w-5 h-5"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                        />
                    </svg>
                </button>
            </form>

            <x-header.search-dropdown type="desktop" />
        </div>
    </div>
@endif
