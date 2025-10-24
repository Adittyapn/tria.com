@props([
    'categories' => []
])

<!-- Categories Section -->
<section class="section-padding bg-white">
    <div class="container-custom">
        <div class="text-center mb-16">
            <div
                class="inline-flex items-center px-4 py-2 bg-brand-cream rounded-full text-navy-900 text-sm font-medium mb-4"
            >
                <span class="w-2 h-2 bg-brand-orange rounded-full mr-2"></span>
                Kategori Produk
            </div>
            <h2 class="text-4xl lg:text-5xl font-black text-navy-900 mb-6">
                Pilih Kategori Yang
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-brand-orange to-navy-900">
                    Anda Butuhkan
                </span>
            </h2>
            <p class="text-gray-600 text-lg max-w-2xl mx-auto">
                Beragam kategori produk berkualitas tinggi untuk memenuhi semua kebutuhan bisnis dan personal Anda
            </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
            <!-- Flash Sale - Special category (tidak ada di database) -->
            @php
                // Map kategori statis dengan warna
                $staticCategories = [
                    'label' => [
                        'name' => 'Label',
                        'description' => 'Stiker & label produk',
                        'gradient' => 'from-navy-900 to-navy-800',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>'
                    ],
                    'merchandise' => [
                        'name' => 'Merchandise',
                        'description' => 'Produk promosi',
                        'gradient' => 'from-green-500 to-green-600',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>'
                    ],
                    'sticker' => [
                        'name' => 'Sticker',
                        'description' => 'Stiker custom',
                        'gradient' => 'from-purple-500 to-purple-600',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'
                    ],
                    'atk' => [
                        'name' => 'ATK',
                        'description' => 'Alat tulis kantor',
                        'gradient' => 'from-pink-500 to-pink-600',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>'
                    ],
                        'media-promosi' => [
                        'name' => 'Media Promosi',
                        'description' => 'Banner & spanduk',
                        'gradient' => 'from-brand-orange to-orange-600',
                        'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>'
                    ],
                ];
                
                // Cari kategori dari database
                $dbCategories = $categories->keyBy('slug');
            @endphp

            @foreach($staticCategories as $slug => $staticCat)
                @php
                    $dbCategory = $dbCategories->get($slug);
                    $hasProducts = $dbCategory && $dbCategory->products()->where('is_active', true)->count() > 0;
                @endphp
                
                @if($dbCategory)
                    <!-- Kategori ada di database -->
                    <a href="{{ route('categories.products', $dbCategory->slug) }}" class="group cursor-pointer">
                @else
                    <!-- Kategori belum ada di database -->
                    <div class="group cursor-pointer" onclick="showComingSoonModal('{{ $staticCat['name'] }}')">
                @endif
                    <div class="bg-gradient-to-br {{ $staticCat['gradient'] }} rounded-2xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-2xl h-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $staticCat['icon'] !!}
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">{{ $staticCat['name'] }}</h3>
                            <p class="text-white/80 text-sm">{{ $staticCat['description'] }}</p>
                        </div>
                    </div>
                @if($dbCategory)
                    </a>
                @else
                    </div>
                @endif
            @endforeach

            <!-- Kategori Media Promosi dan kategori dinamis lainnya dari database -->
            @foreach($categories->whereNotIn('slug', array_keys($staticCategories)) as $category)
                <a href="{{ route('categories.products', $category->slug) }}" class="group cursor-pointer">
                    <div
                        class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl p-6 text-center transform transition-all hover:scale-105 hover:shadow-2xl h-full relative overflow-hidden"
                    >
                        <div
                            class="absolute inset-0 bg-white/10 opacity-0 group-hover:opacity-100 transition-opacity"
                        ></div>
                        <div class="relative z-10">
                            <div class="w-12 h-12 bg-white/20 rounded-2xl mx-auto mb-4 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"
                                    />
                                </svg>
                            </div>
                            <h3 class="text-white font-bold text-lg mb-2">{{ $category->name }}</h3>
                            <p class="text-white/80 text-sm">
                                {{ $category->description ?? 'Produk ' . strtolower($category->name) }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
</section>

<!-- Coming Soon Modal -->
