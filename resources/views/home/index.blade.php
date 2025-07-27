@extends('layouts.app')

@section('title', 'Digital Printing Online - Beranda')
@section('description', 'Cetak produk digital Anda dengan kualitas tinggi dan pengiriman cepat')

@section('content')
<!-- Hero Section -->
<section class="bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 text-white py-20">
    <div class="container mx-auto px-4">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                    Digital Printing 
                    <span class="text-yellow-400">Berkualitas Tinggi</span>
                </h1>
                <p class="text-xl text-blue-100 mb-8 leading-relaxed">
                    Cetak produk digital Anda dengan hasil profesional dan delivery cepat. Dapatkan kualitas terbaik dengan harga terjangkau.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="#produk" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-8 py-4 rounded-xl font-semibold inline-flex items-center justify-center transition-all duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        Lihat Produk
                    </a>
                    <a href="#" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-xl font-semibold inline-flex items-center justify-center transition-all duration-300">
                        Hubungi Kami
                    </a>
                </div>
            </div>
            <div class="relative">
                <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/20">
                    <div class="h-64 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center">
                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Categories Section -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Kategori Produk</h2>
            <p class="text-xl text-gray-600">Pilih kategori sesuai kebutuhan Anda</p>
        </div>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @forelse($categories as $category)
                <div class="card card-hover p-6 text-center border border-gray-100">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg mb-2 text-gray-900">{{ $category->name }}</h3>
                    <p class="text-sm text-gray-500">{{ $category->products_count }} produk</p>
                    <div class="mt-4">
                        <span class="inline-block bg-blue-50 text-blue-600 px-3 py-1 rounded-full text-xs font-medium">Tersedia</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center">
                    <div class="bg-gray-50 rounded-2xl p-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada kategori tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Featured Products Section -->
<section id="produk" class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Produk Unggulan</h2>
            <p class="text-xl text-gray-600">Produk terpopuler dengan kualitas terbaik</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @forelse($featuredProducts as $product)
                <div class="card hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                    <div class="relative h-48 overflow-hidden">
                        @if($product->image)
                            <img src="{{ $product->image }}" alt="{{ $product->name }}" class="w-full h-full object-cover transition-transform duration-300 hover:scale-110">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="bg-blue-500 text-white px-3 py-1 rounded-full text-xs font-semibold">{{ $product->category->name }}</span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="bg-yellow-400 text-gray-900 px-2 py-1 rounded-full text-xs font-bold">Featured</span>
                        </div>
                    </div>
                    <div class="p-6">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">{{ $product->name }}</h3>
                        <p class="text-gray-600 text-sm mb-4 leading-relaxed">{{ Str::limit($product->description, 80) }}</p>
                        <div class="flex justify-between items-center">
                            <div>
                                <span class="text-2xl font-bold text-blue-600">{{ $product->formatted_price }}</span>
                            </div>
                            <button class="btn-primary flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4m-2.4-2h2m0 0L7 13m0 0l-1.5 6M7 13l-1.5 6m0 0h10m-10 0a1 1 0 100 2 1 1 0 000-2zm10 0a1 1 0 100 2 1 1 0 000-2z"/>
                                </svg>
                                Pesan
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center">
                    <div class="bg-white rounded-2xl p-12 shadow-lg">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                        </svg>
                        <p class="text-gray-500 text-lg">Belum ada produk unggulan tersedia</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- Why Choose Us -->
<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-4xl font-bold text-gray-900 mb-4">Mengapa Memilih Kami?</h2>
            <p class="text-xl text-gray-600">Keunggulan yang membuat kami berbeda</p>
        </div>
        <div class="grid md:grid-cols-3 gap-8">
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-green-400 to-blue-500 rounded-2xl mx-auto mb-6 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-4 text-gray-900">Kualitas Terjamin</h3>
                <p class="text-gray-600 leading-relaxed">Menggunakan teknologi printing terbaru dengan hasil yang tajam dan warna yang akurat</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-blue-400 to-purple-500 rounded-2xl mx-auto mb-6 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-4 text-gray-900">Pengiriman Cepat</h3>
                <p class="text-gray-600 leading-relaxed">Proses produksi cepat dengan pengiriman yang dapat diandalkan ke seluruh Indonesia</p>
            </div>
            
            <div class="text-center group">
                <div class="w-20 h-20 bg-gradient-to-r from-orange-400 to-red-500 rounded-2xl mx-auto mb-6 flex items-center justify-center transform group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                    </svg>
                </div>
                <h3 class="font-bold text-2xl mb-4 text-gray-900">Harga Terjangkau</h3>
                <p class="text-gray-600 leading-relaxed">Dapatkan harga terbaik tanpa mengurangi kualitas dengan berbagai pilihan paket</p>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-20 bg-gradient-to-r from-blue-600 to-purple-700">
    <div class="container mx-auto px-4">
        <div class="text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Siap Mulai Proyek Printing Anda?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">Hubungi kami sekarang untuk konsultasi gratis dan dapatkan penawaran terbaik untuk kebutuhan digital printing Anda</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="bg-yellow-500 hover:bg-yellow-600 text-gray-900 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105">
                    Konsultasi Gratis
                </a>
                <a href="#" class="border-2 border-white text-white hover:bg-white hover:text-blue-600 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300">
                    Lihat Portfolio
                </a>
            </div>
        </div>
    </div>
</section>
@endsection