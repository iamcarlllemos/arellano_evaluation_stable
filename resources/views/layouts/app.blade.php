<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ 'Arellano Evaluation' }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <main>
                <div id="sidebar" class="sidebar fixed top-0 left-0 z-40 md:w-80 h-screen transition-transform -translate-x-full sm:translate-x-0" style="background-color: #192231">
                    @livewire('admin.sidebar')
                </div>
                <div class="content md:ml-80">
                    @livewire('admin.navbar')
                    @if(!isset($slot))
                        @yield('content')
                    @else
                        {{ $slot }}
                    @endif
                </div>

            </main>
        </div>

        @stack('modals')

        @livewireScripts

    </body>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</html>
