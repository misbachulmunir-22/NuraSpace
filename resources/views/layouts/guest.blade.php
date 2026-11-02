<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'NuraSpace') }} — Ruang Privat Kita</title>

        <!-- Google Fonts: Outfit & Playfair Display -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|playfair-display:400,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Theme Initialization (Immediate execution before render) -->
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        </script>
    </head>
    <body class="font-sans antialiased text-gray-900 dark:text-gray-100 bg-rose-50/50 dark:bg-gray-950 transition-colors duration-300 min-h-screen relative overflow-x-hidden selection:bg-pink-500 selection:text-white">

        <!-- Ambient Romantic Glowing Background Blobs -->
        <div class="fixed inset-0 pointer-events-none overflow-hidden z-0 bg-romantic-glow">
            <div class="absolute -top-32 -left-32 w-96 h-96 bg-gradient-to-br from-pink-400/25 to-rose-300/10 dark:from-pink-900/20 dark:to-purple-900/10 rounded-full blur-3xl animate-float-slow"></div>
            <div class="absolute top-1/3 -right-32 w-96 h-96 bg-gradient-to-br from-purple-400/20 to-pink-300/10 dark:from-purple-900/20 dark:to-rose-900/10 rounded-full blur-3xl animate-float-slow" style="animation-delay: -3s;"></div>
            <div class="absolute -bottom-32 left-1/4 w-96 h-96 bg-gradient-to-br from-rose-300/20 to-amber-200/10 dark:from-rose-950/30 dark:to-purple-950/10 rounded-full blur-3xl animate-pulse-glow"></div>
        </div>

        <!-- Top Header Navigation (Theme Toggle & Logo) -->
        <header class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center justify-between">
            <a href="/" class="hover:opacity-90 transition">
                <x-application-logo />
            </a>

            <!-- Dark Mode Toggle Button for Guest / Login Page -->
            <button 
                type="button" 
                @click="$store.theme.toggle()"
                class="flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-white/80 dark:bg-gray-800/80 backdrop-blur-md border border-pink-200 dark:border-pink-900/50 text-gray-700 dark:text-gray-300 shadow-xs hover:border-pink-300 dark:hover:border-pink-700 transition active:scale-95"
                title="Ganti Tema Gelap / Terang"
            >
                <span x-show="!$store.theme.dark" class="text-sm">🌙</span>
                <span x-show="$store.theme.dark" x-cloak class="text-sm">☀️</span>
                <span x-text="$store.theme.dark ? 'Mode Terang' : 'Mode Gelap'" class="text-3xs"></span>
            </button>
        </header>

        <!-- Main Content Slot -->
        <main class="relative z-10 flex flex-col justify-center items-center px-4 py-8 sm:py-12">
            {{ $slot }}
        </main>

        <!-- Romantic Footer Note -->
        <footer class="relative z-10 text-center py-6 text-3xs text-gray-400 dark:text-gray-500 font-medium tracking-wide">
            Dibuat penuh cinta untuk <span class="text-pink-600 dark:text-pink-400 font-semibold">Misbachul Munir</span> & <span class="text-purple-600 dark:text-purple-400 font-semibold">Syahra Alfi Nur Arintia</span> 💑✨
        </footer>

    </body>
</html>
