<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>@yield('title', 'Tria Digital - Jasa Cetak Digital Custom')</title>
        <meta
            name="description"
            content="@yield('description', 'Jasa cetak digital custom terpercaya. Stiker, banner, merchandise, dan berbagai produk printing berkualitas tinggi')"
        />

        <!-- SweetAlert2 CSS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" />

        <!-- SweetAlert2 JS -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com" />
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
            rel="stylesheet"
        />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles

        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
        </style>
    </head>

    <body class="bg-gray-50">
        <!-- Main Header -->
        <x-header />

        <!-- Breadcrumbs (optional) -->
        @hasSection('breadcrumbs')
            <div class="bg-gray-100 py-3">
                <div class="container mx-auto px-4">
                    <nav class="text-sm">
                        @yield('breadcrumbs')
                    </nav>
                </div>
            </div>
        @endif

        <!-- Main Content -->
        <main class="min-h-screen">
            @yield('content')
        </main>

        <!-- WhatsApp Floating Button -->
        <x-whatsapp-floating-button />

        <!-- Footer -->
        <x-footer />

        @livewireScripts

        <!-- Custom Scripts -->
        @stack('scripts')
    </body>
</html>
