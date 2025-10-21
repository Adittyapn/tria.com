@props([
    'product',
])

<!-- Product Images Gallery -->
<div class="space-y-4">
    <!-- Main Image -->
    <div class="relative overflow-hidden rounded-lg bg-gray-100">
        <img
            :src="currentImage"
            alt="{{ $product->name }}"
            class="w-full h-96 lg:h-[450px] object-contain transition-transform hover:scale-105"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
        />

        <!-- Product Type Badge -->
        @if ($product->product_type)
            <div class="absolute top-4 left-4 bg-black/70 text-white px-3 py-1 rounded-full text-sm">
                {{ $product->getProductTypeLabel() }}
            </div>
        @endif

        <!-- Zoom Button -->
        <button
            @click="openLightbox()"
            class="absolute top-4 right-4 bg-white/80 p-2 rounded-full hover:bg-white transition-colors"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"
                ></path>
            </svg>
        </button>
    </div>

    <!-- Thumbnail Gallery -->
    @if ($product->gallery_images && count($product->gallery_images) > 0)
        <div class="flex space-x-3 overflow-x-auto pb-2">
            <!-- Main Image Thumbnail -->
            <button
                @click="changeImage('{{ asset('storage/' . $product->featured_image) }}')"
                :class="currentImage === '{{ asset('storage/' . $product->featured_image) }}' ? 'ring-2' : ''"
                style="ring-color: #000334"
                class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 transition-all"
            >
                <img
                    src="{{ asset('storage/' . $product->featured_image) }}"
                    alt="Main"
                    class="w-full h-full object-cover"
                />
            </button>

            <!-- Gallery Images -->
            @foreach ($product->gallery_images as $image)
                <button
                    @click="changeImage('{{ asset('storage/' . $image) }}')"
                    :class="currentImage === '{{ asset('storage/' . $image) }}' ? 'ring-2' : ''"
                    style="ring-color: #000334"
                    class="flex-shrink-0 w-20 h-20 rounded-lg overflow-hidden bg-gray-100 hover:ring-2 transition-all"
                >
                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery" class="w-full h-full object-cover" />
                </button>
            @endforeach
        </div>
    @endif
</div>
