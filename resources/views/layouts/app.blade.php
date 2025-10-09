<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        
        <!-- Bootstrap Icons (keeping CDN for icons) -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" />
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Inline override: ensure hero uses the public/images file so the
             banner shows immediately even when build assets point elsewhere -->
        <style>
            /* Highest priority override to use the public/images path */
            .hero {
                background-image: url('/images/istockphoto-1428709516-612x612.jpg') ;
            }

           
        </style>
    </head>
    <body>
        <div class="min-vh-100 bg-light">
            @include('layouts.navigation')

            <main class="container-fluid py-4">
                @yield('content')
            </main>
        </div>
        @include('layouts.footer')
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

            {{-- Render view-pushed scripts (e.g. AJAX search script pushed from components) --}}
            @stack('scripts')
    </body>
</html>
