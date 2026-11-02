<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|playfair-display:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Initialization (Prevent Flash) -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body x-data="{ sidebarOpen: false }" class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-[#fdf8f7] dark:bg-gray-950 transition-colors duration-200 min-h-screen selection:bg-pink-500 selection:text-white">
        <div class="min-h-screen bg-[#fdf8f7] dark:bg-gray-950 flex flex-col">
            <!-- Sidebar (Fixed on Desktop, Drawer on Mobile) -->
            @include('layouts.sidebar')

            <!-- Main Content Wrapper (Padded left for sidebar on Desktop lg:pl-72) -->
            <div class="lg:pl-72 flex flex-col flex-1 min-h-screen transition-all duration-300">
                @include('layouts.navigation')

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white/70 dark:bg-gray-900/70 backdrop-blur-md border-b border-rose-100/70 dark:border-gray-800 shadow-2xs">
                        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content (Extra bottom padding on mobile/tablet for Bottom Navigation) -->
                <main class="flex-1 pb-24 lg:pb-8">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @auth
            <x-bottom-navigation />
            <x-nudge-receiver />
            <x-nudge-sender-modal />
        @endauth
    </body>
</html>
