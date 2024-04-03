<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

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
s
        <div class="min-h-screen bg-gray-100">
            <!-- Page Content -->
            <main>
                <div class="">
                    <div class="">
                        @livewire('student.sidebar')
                        <div class="content sm:ml-[320px]">
                            @livewire('student.navbar')
                            @if(!isset($slot))
                                @yield('content')
                            @else
                                {{ $slot }}
                            @endif
                        </div>
                    </div>
                </div>
            </main>
        </div>

        @stack('modals')

        @livewireScripts

    </body>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
</html>
