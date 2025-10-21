@props([
    'title',
    'description',
    'iconPath',
    'iconColor' => 'bg-brand-orange',
    'cardClass' => 'bg-white',
    'titleClass' => 'text-navy-900',
    'descriptionClass' => 'text-gray-600',
])

<div
    class="feature-card group {{ $cardClass }} p-4 sm:p-6 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1 border border-gray-100"
>
    <div class="flex items-center space-x-3 sm:space-x-4">
        <!-- Icon Container -->
        <div
            class="w-10 h-10 sm:w-12 sm:h-12 {{ $iconColor }} rounded-xl sm:rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-300"
        >
            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
            </svg>
        </div>

        <!-- Content -->
        <div class="flex-1 min-w-0">
            <h3
                class="font-bold {{ $titleClass }} text-base sm:text-lg mb-1 group-hover:text-brand-orange transition-colors duration-300"
            >
                {{ $title }}
            </h3>
            <p class="{{ $descriptionClass }} text-xs sm:text-sm leading-relaxed">
                {{ $description }}
            </p>
        </div>
    </div>
</div>
