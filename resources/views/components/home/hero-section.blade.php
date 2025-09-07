@props([
    'carouselSlides',
])

<!-- Hero Carousel Section -->
<section class="relative h-screen overflow-hidden bg-navy-900">
    <x-carousel
        :slides="$carouselSlides"
        :auto-slide="true"
        :auto-slide-interval="5000"
        :show-controls="true"
        :show-dots="true"
        height="h-screen"
        id="hero-carousel"
    />
</section>
