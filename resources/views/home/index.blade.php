@extends('layouts.app')

@section('content')
    {{-- Hero Section --}}
    <x-home.hero-section :carousel-slides="$carouselSlides" />

    {{-- Features Section --}}
    <x-home.features-section />

    {{-- Categories Section --}}
    <x-home.categories-section :categories="$categories" />

    {{-- Products Section --}}
    <x-home.products-section :product-types="$productTypes" :products="$products" />

    {{-- CTA Section --}}
    <x-home.cta-section />

    {{-- Scripts --}}
    <x-home.scripts />
@endsection
