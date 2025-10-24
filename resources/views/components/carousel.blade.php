@props([
    'slides' => [],
    'autoSlide' => true,
    'autoSlideInterval' => 5000,
    'showControls' => true,
    'showDots' => true,
    'height' => 'h-screen',
    'id' => 'carousel-' . uniqid(),
])

<div
    x-data="{
        currentSlide: 0,
        slides: @js($slides),
        autoSlide: @js($autoSlide),
        autoSlideInterval: @js($autoSlideInterval),
        showControls: @js($showControls),
        showDots: @js($showDots),
        autoSlideTimer: null,

        init() {
            if (this.autoSlide && this.slides.length > 1) {
                this.startAutoSlide()
            }
        },

        startAutoSlide() {
            this.autoSlideTimer = setInterval(() => {
                this.nextSlide()
            }, this.autoSlideInterval)
        },

        stopAutoSlide() {
            if (this.autoSlideTimer) {
                clearInterval(this.autoSlideTimer)
                this.autoSlideTimer = null
            }
        },

        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.slides.length
        },

        prevSlide() {
            this.currentSlide =
                this.currentSlide === 0
                    ? this.slides.length - 1
                    : this.currentSlide - 1
        },

        goToSlide(index) {
            this.currentSlide = index
        },
    }"
    @mouseenter="stopAutoSlide()"
    @mouseleave="autoSlide && startAutoSlide()"
    class="carousel relative {{ $height }}"
    id="{{ $id }}"
    style="min-height: 100vh"
>
    @if (count($slides) > 0)
        <!-- Slides Container -->
        <div class="slides-container w-full relative">
            @foreach ($slides as $index => $slide)
                <div
                    x-show="currentSlide === {{ $index }}"
                    x-transition:enter="transition ease-out duration-500"
                    x-transition:enter-start="opacity-0 transform translate-x-full"
                    x-transition:enter-end="opacity-100 transform translate-x-0"
                    x-transition:leave="transition ease-in duration-500"
                    x-transition:leave-start="opacity-100 transform translate-x-0"
                    x-transition:leave-end="opacity-0 transform -translate-x-full"
                    class="slide w-full min-h-screen bg-gradient-to-r from-blue-900 to-purple-900"
                    :class="currentSlide === {{ $index }} ? 'block' : 'hidden'"
                >
                    <div
                        class="absolute inset-0 opacity-10"
                        style="
                            background-image: url('data:image/svg+xml,%3Csvg width%3D%2760%27 height%3D%2760%27 viewBox%3D%270 0 60 60%27 xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%3E%3Cg fill%3D%27none%27 fill-rule%3D%27evenodd%27%3E%3Cg fill%3D%27%23ffffff%27 fill-opacity%3D%270.4%27%3E%3Cpath d%3D%27M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%27%2F%3E%3C%2Fg%3E%3C%2Fg%3E%3C%2Fsvg%3E');
                        "
                    ></div>
                    <div class="container mx-auto px-4 lg:px-8 relative z-10">
                        <div class="flex flex-col lg:flex-row items-center justify-between min-h-screen py-8 lg:py-16">
                            <!-- Content Left -->
                            <div class="text-white space-y-6 lg:space-y-8 max-w-2xl lg:w-1/2 text-center lg:text-left">
                                <div class="space-y-4 lg:space-y-6">
                                    <!-- Badge -->
                                    @if (isset($slide['badge']))
                                        <div
                                            class="inline-flex items-center px-4 py-2 bg-brand-orange bg-opacity-20 rounded-full text-brand-orange border border-brand-orange border-opacity-30"
                                        >
                                            <span
                                                class="w-2 h-2 bg-brand-orange rounded-full mr-2 animate-pulse"
                                            ></span>
                                            <span class="font-medium text-sm lg:text-base">{{ $slide['badge'] }}</span>
                                        </div>
                                    @endif

                                    <!-- Title -->
                                    @if (isset($slide['title']))
                                        <h1
                                            class="text-3xl md:text-4xl lg:text-6xl xl:text-7xl font-black leading-tight"
                                        >
                                            {!! $slide['title'] !!}
                                        </h1>
                                    @endif

                                    <!-- Description -->
                                    @if (isset($slide['description']))
                                        <p class="text-base md:text-lg lg:text-xl text-gray-300 leading-relaxed">
                                            {{ $slide['description'] }}
                                        </p>
                                    @endif
                                </div>

                                <!-- Buttons -->
                                @if (isset($slide['buttons']) && count($slide['buttons']) > 0)
                                    <div
                                        class="flex flex-col sm:flex-row gap-3 lg:gap-4 items-center justify-center lg:justify-start"
                                    >
                                        @foreach ($slide['buttons'] as $button)
                                            <button class="{{ $button['class'] }}">
                                                {{ $button['text'] }}
                                            </button>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Stats -->
                                @if (isset($slide['stats']) && count($slide['stats']) > 0)
                                    <div
                                        class="flex items-center justify-center lg:justify-start space-x-4 lg:space-x-8 pt-6 lg:pt-8"
                                    >
                                        @foreach ($slide['stats'] as $stat)
                                            <div class="text-center">
                                                <div class="text-2xl lg:text-3xl font-bold text-brand-orange">
                                                    {{ $stat['value'] }}
                                                </div>
                                                <div class="text-xs lg:text-sm text-gray-400">
                                                    {{ $stat['label'] }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <!-- Right Content -->
                            @if (isset($slide['rightContent']))
                                <div class="relative lg:w-1/2 mt-8 lg:mt-0">
                                    <div
                                        class="bg-white bg-opacity-10 backdrop-blur-lg rounded-2xl lg:rounded-3xl p-4 lg:p-6 border border-white border-opacity-20 w-full"
                                    >
                                        <!-- Header -->
                                        <div class="md:flex items-center justify-between mb-4 lg:mb-6 hidden">
                                            <div>
                                                <h3 class="text-white font-bold text-lg lg:text-xl">
                                                    {{ $slide['rightContent']['title'] ?? 'Produk Unggulan' }}
                                                </h3>
                                                <p class="text-gray-300 text-sm">
                                                    {{ $slide['rightContent']['subtitle'] ?? 'Kualitas terbaik, harga terjangkau' }}
                                                </p>
                                            </div>
                                            <div
                                                class="w-10 h-10 lg:w-12 lg:h-12 bg-brand-orange rounded-xl lg:rounded-2xl flex items-center justify-center"
                                            >
                                                <svg
                                                    class="w-5 h-5 lg:w-6 lg:h-6 text-white"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"
                                                    />
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Products Grid -->
                                        <div class="grid grid-cols-2 gap-3 lg:gap-2 pb-4">
                                            @if (isset($slide['rightContent']['products']))
                                                @foreach ($slide['rightContent']['products'] as $productIndex => $product)
                                                    <div
                                                        class="bg-white bg-opacity-10 rounded-xl lg:rounded-2xl overflow-hidden hover:bg-white hover:bg-opacity-20 transition-all cursor-pointer group"
                                                    >
                                                        <!-- Product Image -->
                                                        <div class="relative w-full h-32 bg-gray-200 overflow-hidden">
                                                            <img
                                                                src="{{ $product['image'] }}"
                                                                alt="{{ $product['name'] }}"
                                                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                                                loading="lazy"
                                                            />

                                                            <!-- Discount Badge -->
                                                            @if (isset($product['promo_price']) && $product['promo_price'] && isset($product['discount']))
                                                                <div class="absolute top-2 left-2">
                                                                    <span
                                                                        class="bg-red-500 text-white px-2 py-1 rounded-full text-xs font-bold shadow-lg"
                                                                    >
                                                                        {{ $product['discount'] }}
                                                                    </span>
                                                                </div>
                                                            @endif

                                                            <!-- Category Badge -->
                                                            @if (isset($product['category']))
                                                                <div class="absolute top-2 right-2">
                                                                    <span
                                                                        class="bg-black bg-opacity-70 text-white px-2 py-1 rounded-full text-xs font-medium"
                                                                    >
                                                                        {{ $product['category'] }}
                                                                    </span>
                                                                </div>
                                                            @endif
                                                        </div>

                                                        <!-- Product Info -->
                                                        <div class="p-3 lg:p-4">
                                                            <h4
                                                                class="text-white font-semibold text-sm lg:text-base mb-1"
                                                            >
                                                                {{ $product['name'] }}
                                                            </h4>
                                                            <div class="flex items-center justify-between">
                                                                <span
                                                                    class="text-brand-orange font-bold text-sm lg:text-base"
                                                                >
                                                                    Rp
                                                                    {{ number_format((float) preg_replace('/[^0-9.]/', '', $product['price']), 0, ',', '.') }}
                                                                </span>
                                                                <a
                                                                    href="/product/{{ $product['slug'] ?? '' }}"
                                                                    class="bg-brand-orange bg-opacity-20 text-brand-orange p-1.5 rounded-lg hover:bg-brand-orange hover:text-white transition-colors"
                                                                >
                                                                    <svg
                                                                        class="w-3 h-3 lg:w-4 lg:h-4"
                                                                        fill="none"
                                                                        stroke="currentColor"
                                                                        viewBox="0 0 24 24"
                                                                    >
                                                                        <path
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round"
                                                                            stroke-width="2"
                                                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
                                                                        />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        <!-- View All Products Link -->
                                        <div class="mt-4 lg:mt-6 text-center">
                                            <a
                                                href="#products"
                                                class="inline-flex items-center text-white hover:text-brand-orange transition-colors text-sm lg:text-base font-medium"
                                            >
                                                <span>Lihat Semua Produk</span>
                                                <svg
                                                    class="w-4 h-4 ml-2"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    viewBox="0 0 24 24"
                                                >
                                                    <path
                                                        stroke-linecap="round"
                                                        stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M17 8l4 4m0 0l-4 4m4-4H3"
                                                    />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Scroll Indicator -->
                    <div class="absolute bottom-6 lg:bottom-8 right-6 lg:right-8 text-white animate-bounce">
                        <svg class="w-5 h-5 lg:w-6 lg:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M19 14l-7 7m0 0l-7-7m7 7V3"
                            />
                        </svg>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Navigation Controls -->
        @if ($showControls && count($slides) > 1)
            <div class="absolute left-4 top-1/2 transform -translate-y-1/2 z-20">
                <button
                    @click="prevSlide()"
                    class="bg-black bg-opacity-20 hover:bg-opacity-40 text-white p-2 rounded-full transition-all duration-200 backdrop-blur-sm border border-white border-opacity-20"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
            </div>

            <div class="absolute right-4 top-1/2 transform -translate-y-1/2 z-20">
                <button
                    @click="nextSlide()"
                    class="bg-black bg-opacity-20 hover:bg-opacity-40 text-white p-2 rounded-full transition-all duration-200 backdrop-blur-sm border border-white border-opacity-20"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        @endif

        <!-- Dots Navigation -->
        @if ($showDots && count($slides) > 1)
            <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-20">
                <div class="flex space-x-2">
                    @foreach ($slides as $index => $slide)
                        <button
                            @click="goToSlide({{ $index }})"
                            :class="currentSlide === {{ $index }} ? 'bg-brand-orange' : 'bg-white bg-opacity-50'"
                            class="w-3 h-3 rounded-full transition-all duration-200 hover:bg-tria-orange"
                        ></button>
                    @endforeach
                </div>
            </div>
        @endif
    @else
        <!-- Fallback when no slides -->
        <div class="flex items-center justify-center h-full bg-gray-200">
            <p class="text-gray-500">No slides available</p>
        </div>
    @endif
</div>
