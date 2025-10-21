@props([
    'features' => [
        [
            'title' => 'Cepat & Tepat',
            'description' => 'Proses produksi dalam 24 jam',
            'icon' => 'clock',
        ],
        [
            'title' => 'Kualitas Premium',
            'description' => 'Menggunakan bahan terbaik',
            'icon' => 'check-circle',
        ],
        [
            'title' => 'Harga Kompetitif',
            'description' => 'Terjangkau untuk semua kalangan',
            'icon' => 'currency-dollar',
        ],
        [
            'title' => 'Layanan 24/7',
            'description' => 'Customer support siap membantu',
            'icon' => 'sparkles',
        ],
    ],
    'bgClass' => 'bg-brand-cream',
    'containerClass' => 'section-padding',
])

@php
    $iconPaths = [
        'clock' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
        'check-circle' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
        'currency-dollar' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1',
        'sparkles' => 'M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 12h.01M12 12l4.24-4.24M12 12L7.76 16.24',
    ];
@endphp

<!-- Features Section -->
<section class="{{ $containerClass }} {{ $bgClass }}">
    <div class="container-custom">
        <!-- Grid: 1 col mobile, 2 cols tablet, 4 cols desktop -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 lg:gap-8">
            @foreach ($features as $feature)
                <x-feature-card
                    :title="$feature['title']"
                    :description="$feature['description']"
                    :iconPath="$iconPaths[$feature['icon']] ?? $iconPaths['clock']"
                />
            @endforeach
        </div>
    </div>
</section>
