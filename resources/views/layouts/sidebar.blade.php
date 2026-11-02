<!-- Desktop Sidebar (Fixed) -->
<aside class="hidden lg:fixed lg:inset-y-0 lg:left-0 lg:z-40 lg:flex lg:w-72 lg:flex-col bg-white/95 dark:bg-gray-900/95 backdrop-blur-xl border-r border-rose-100/80 dark:border-gray-800 transition-colors shadow-sm">
    <!-- Brand / Logo Header -->
    <div class="h-20 flex items-center px-6 border-b border-rose-100/70 dark:border-gray-800/80 shrink-0">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
            <x-application-logo class="h-10 w-auto transition transform group-hover:scale-105" />
        </a>
    </div>

    <!-- Scrollable Navigation Items -->
    <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6 scrollbar-thin scrollbar-thumb-rose-200 dark:scrollbar-thumb-gray-700">
        <!-- Quick Nudge Action Button -->
        <div>
            <button 
                type="button" 
                @click="$dispatch('open-nudge-modal')" 
                class="w-full flex items-center justify-center gap-2 py-2.5 px-4 rounded-2xl text-xs font-bold text-white bg-gradient-to-r from-pink-600 via-rose-500 to-purple-600 hover:from-pink-500 hover:to-purple-500 shadow-md shadow-pink-500/20 active:scale-98 transition group"
            >
                <span class="text-sm animate-pulse">✨</span>
                <span>Kirim Sentuhan Manis</span>
                <span class="text-xs opacity-75 group-hover:translate-x-0.5 transition">&rarr;</span>
            </button>
        </div>

        <!-- Section: Menu Utama -->
        <div class="space-y-1">
            <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                Utama
            </p>
            <a 
                href="{{ route('dashboard') }}" 
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
            >
                <span class="text-lg">🏠</span>
                <span class="flex-1">Beranda</span>
                @if (request()->routeIs('dashboard'))
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                @endif
            </a>
        </div>

        @if (Auth::user()->isPartner())
            <!-- Section: Ruang Cinta & Keintiman -->
            <div class="space-y-1">
                <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-pink-500 dark:text-pink-400">
                    Kemesraan & Rasa
                </p>

                <!-- Daily Question / Kuis Cinta -->
                <a 
                    href="{{ route('daily-question.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('daily-question.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">💬</span>
                    <span class="flex-1">Pertanyaan Harian</span>
                    @if (request()->routeIs('daily-question.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>

                <!-- Curhatan Hati / Jurnal -->
                <a 
                    href="{{ route('journals.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('journals.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">📖</span>
                    <span class="flex-1">Curhatan & Jurnal</span>
                    @if (request()->routeIs('journals.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>

                <!-- Kapsul Waktu / Surat Masa Depan -->
                <a 
                    href="{{ route('time-capsules.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('time-capsules.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">💌</span>
                    <span class="flex-1">Kapsul Waktu</span>
                    @if (request()->routeIs('time-capsules.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>
            </div>
        @endif

        <!-- Section: Waktu & Kenangan -->
        <div class="space-y-1">
            <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                Waktu & Kenangan
            </p>

            <!-- Kalender & Acara -->
            <a 
                href="{{ route('events.index') }}" 
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('events.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
            >
                <span class="text-lg">🗓️</span>
                <span class="flex-1">Kalender & Kencan</span>
                @if (request()->routeIs('events.*'))
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                @endif
            </a>

            <!-- Countdown -->
            <a 
                href="{{ route('countdowns.index') }}" 
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('countdowns.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
            >
                <span class="text-lg">⏳</span>
                <span class="flex-1">Countdown</span>
                @if (request()->routeIs('countdowns.*'))
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                @endif
            </a>

            <!-- Galeri Kenangan -->
            <a 
                href="{{ route('memories.index') }}" 
                class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('memories.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
            >
                <span class="text-lg">📸</span>
                <span class="flex-1">Galeri Memory Lane</span>
                @if (request()->routeIs('memories.*'))
                    <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                @endif
            </a>
        </div>

        @if (Auth::user()->isPartner())
            <!-- Section: Impian & Masa Depan -->
            <div class="space-y-1">
                <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-purple-500 dark:text-purple-400">
                    Impian & Masa Depan
                </p>

                <!-- Wishlist -->
                <a 
                    href="{{ route('wishlists.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('wishlists.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">🌟</span>
                    <span class="flex-1">Wishlist Impian</span>
                    @if (request()->routeIs('wishlists.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>

                <!-- Tabungan Bersama -->
                <a 
                    href="{{ route('savings.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('savings.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">💰</span>
                    <span class="flex-1">Tabungan Bersama</span>
                    @if (request()->routeIs('savings.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>

                <!-- Keluarga & Anak -->
                <a 
                    href="{{ route('family.index') }}" 
                    class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('family.*') ? 'bg-gradient-to-r from-pink-500/15 via-rose-500/10 to-purple-500/10 text-pink-700 dark:text-pink-300 border border-pink-200/80 dark:border-pink-800/60 shadow-2xs' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60 hover:text-rose-600 dark:hover:text-rose-300' }}"
                >
                    <span class="text-lg">👨‍👩‍👧‍👦</span>
                    <span class="flex-1">Ruang Keluarga</span>
                    @if (request()->routeIs('family.*'))
                        <span class="w-1.5 h-1.5 rounded-full bg-pink-500"></span>
                    @endif
                </a>
            </div>
        @endif
    </div>

    <!-- Sidebar Footer / User Profile & Dark Mode Toggle -->
    <div class="p-4 border-t border-rose-100/70 dark:border-gray-800 shrink-0 space-y-3 bg-rose-50/30 dark:bg-gray-900/60">
        <!-- Dark Mode Toggle Button in Sidebar -->
        <button 
            type="button" 
            @click="$store.theme.toggle()"
            class="w-full flex items-center justify-between px-3.5 py-2 rounded-2xl text-xs font-semibold text-gray-700 dark:text-gray-300 bg-white/80 dark:bg-gray-800/80 border border-rose-200/60 dark:border-gray-700/70 hover:bg-rose-50/60 dark:hover:bg-gray-750 transition"
        >
            <span class="flex items-center gap-2">
                <span x-show="!$store.theme.dark">🌙 Mode Gelap</span>
                <span x-show="$store.theme.dark" x-cloak>☀️ Mode Terang</span>
            </span>
            <span class="text-3xs text-gray-400 uppercase">Ubah</span>
        </button>

        <!-- User Identity & Quick Links -->
        <div class="flex items-center justify-between gap-2">
            <a href="{{ route('profile.edit') }}" class="flex items-center gap-2.5 flex-1 min-w-0 group">
                <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-pink-500 to-rose-400 text-white flex items-center justify-center text-xs font-bold shadow-xs shrink-0 group-hover:scale-105 transition">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
                <div class="truncate">
                    <p class="text-xs font-bold text-gray-900 dark:text-white truncate group-hover:text-pink-600 dark:group-hover:text-pink-400 transition">
                        {{ Auth::user()->name }}
                    </p>
                    <p class="text-3xs text-gray-400 truncate">
                        {{ Auth::user()->isPartner() ? 'Kekasih Tercinta 💖' : 'Anggota Keluarga 🏡' }}
                    </p>
                </div>
            </a>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button 
                    type="submit" 
                    title="Keluar" 
                    class="p-2 rounded-xl text-gray-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-950/40 transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Off-Canvas Sidebar Drawer -->
<div 
    x-show="sidebarOpen" 
    x-cloak 
    class="relative z-50 lg:hidden" 
    role="dialog" 
    aria-modal="true"
>
    <!-- Backdrop Overlay -->
    <div 
        x-show="sidebarOpen"
        x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click="sidebarOpen = false"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs"
    ></div>

    <!-- Sliding Sidebar Panel -->
    <div class="fixed inset-0 flex">
        <div 
            x-show="sidebarOpen"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="-translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="-translate-x-full"
            class="relative mr-16 flex w-full max-w-xs flex-1 flex-col bg-white dark:bg-gray-900 shadow-2xl"
        >
            <!-- Close Button in Drawer -->
            <div class="absolute top-4 right-4">
                <button 
                    type="button" 
                    @click="sidebarOpen = false" 
                    class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 transition"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <!-- Mobile Logo Header -->
            <div class="h-20 flex items-center px-6 border-b border-rose-100/70 dark:border-gray-800 shrink-0">
                <x-application-logo class="h-9 w-auto" />
            </div>

            <!-- Mobile Scrollable Links -->
            <div class="flex-1 overflow-y-auto px-4 py-5 space-y-6">
                <!-- Section: Utama -->
                <div class="space-y-1">
                    <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                        Utama
                    </p>
                    <a 
                        href="{{ route('dashboard') }}" 
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('dashboard') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                    >
                        <span class="text-lg">🏠</span>
                        <span>Beranda</span>
                    </a>
                </div>

                @if (Auth::user()->isPartner())
                    <!-- Section: Kemesraan -->
                    <div class="space-y-1">
                        <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-pink-500 dark:text-pink-400">
                            Kemesraan & Rasa
                        </p>
                        <a 
                            href="{{ route('daily-question.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('daily-question.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">💬</span>
                            <span>Pertanyaan Harian</span>
                        </a>
                        <a 
                            href="{{ route('journals.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('journals.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">📖</span>
                            <span>Curhatan Hati</span>
                        </a>
                        <a 
                            href="{{ route('time-capsules.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('time-capsules.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">💌</span>
                            <span>Kapsul Waktu</span>
                        </a>
                    </div>
                @endif

                <!-- Section: Waktu & Kenangan -->
                <div class="space-y-1">
                    <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-gray-400 dark:text-gray-500">
                        Waktu & Kenangan
                    </p>
                    <a 
                        href="{{ route('events.index') }}" 
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('events.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                    >
                        <span class="text-lg">🗓️</span>
                        <span>Kalender & Kencan</span>
                    </a>
                    <a 
                        href="{{ route('countdowns.index') }}" 
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('countdowns.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                    >
                        <span class="text-lg">⏳</span>
                        <span>Countdown</span>
                    </a>
                    <a 
                        href="{{ route('memories.index') }}" 
                        @click="sidebarOpen = false"
                        class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('memories.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                    >
                        <span class="text-lg">📸</span>
                        <span>Galeri Foto</span>
                    </a>
                </div>

                @if (Auth::user()->isPartner())
                    <!-- Section: Impian -->
                    <div class="space-y-1">
                        <p class="px-3 text-3xs font-extrabold uppercase tracking-widest text-purple-500 dark:text-purple-400">
                            Impian & Masa Depan
                        </p>
                        <a 
                            href="{{ route('wishlists.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('wishlists.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">🌟</span>
                            <span>Wishlist Impian</span>
                        </a>
                        <a 
                            href="{{ route('savings.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('savings.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">💰</span>
                            <span>Tabungan Bersama</span>
                        </a>
                        <a 
                            href="{{ route('family.index') }}" 
                            @click="sidebarOpen = false"
                            class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-sm font-semibold transition {{ request()->routeIs('family.*') ? 'bg-gradient-to-r from-pink-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 font-bold' : 'text-gray-600 dark:text-gray-400 hover:bg-rose-50/60 dark:hover:bg-gray-800/60' }}"
                        >
                            <span class="text-lg">👨‍👩‍👧‍👦</span>
                            <span>Keluarga & Anak</span>
                        </a>
                    </div>
                @endif
            </div>

            <!-- Mobile Footer Profile -->
            <div class="p-4 border-t border-rose-100/70 dark:border-gray-800 shrink-0 space-y-3 bg-rose-50/30 dark:bg-gray-900/60">
                <button 
                    type="button" 
                    @click="$store.theme.toggle()"
                    class="w-full flex items-center justify-between px-3.5 py-2 rounded-2xl text-xs font-semibold text-gray-700 dark:text-gray-300 bg-white/80 dark:bg-gray-800/80 border border-rose-200/60 dark:border-gray-700/70"
                >
                    <span class="flex items-center gap-2">
                        <span x-show="!$store.theme.dark">🌙 Mode Gelap</span>
                        <span x-show="$store.theme.dark" x-cloak>☀️ Mode Terang</span>
                    </span>
                    <span class="text-3xs text-gray-400 uppercase">Ubah</span>
                </button>

                <div class="flex items-center justify-between gap-2">
                    <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="flex items-center gap-2.5 flex-1 min-w-0">
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-pink-500 to-rose-400 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-bold text-gray-900 dark:text-white truncate">
                                {{ Auth::user()->name }}
                            </p>
                            <p class="text-3xs text-gray-400 truncate">
                                {{ Auth::user()->isPartner() ? 'Kekasih 💖' : 'Keluarga 🏡' }}
                            </p>
                        </div>
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="p-2 rounded-xl text-gray-400 hover:text-red-600 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
