@props([
    'carouselSlides',
])

<!-- Hero Carousel Section -->
<section class="relative overflow-hidden bg-gradient-to-r from-blue-900 to-purple-900">
    <x-carousel
        :slides="$carouselSlides"
        :auto-slide="true"
        :auto-slide-interval="5000"
        :show-controls="true"
        :show-dots="true"
        height="min-h-screen"
        id="hero-carousel"
    />
</section>
