<nav class="bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-rose-100/70 dark:border-gray-800 sticky top-0 z-30 transition-colors">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <!-- Left Side: Mobile Hamburger & Desktop Breadcrumb/Greeting -->
            <div class="flex items-center gap-3">
                <!-- Mobile Hamburger Button (opens sidebar drawer) -->
                <button 
                    type="button" 
                    @click="sidebarOpen = true" 
                    class="lg:hidden p-2 rounded-2xl text-gray-600 dark:text-gray-300 hover:text-rose-600 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-gray-800 border border-rose-200/50 dark:border-gray-700 transition"
                    title="Buka Menu Fitur"
                >
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <!-- Mobile Logo -->
                <div class="lg:hidden flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="h-8 w-auto" />
                    </a>
                </div>

                <!-- Desktop Left Info / Status Pill -->
                <div class="hidden lg:flex items-center gap-2">
                    <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50/80 dark:bg-gray-800/80 text-rose-700 dark:text-rose-300 border border-rose-200/60 dark:border-gray-700/80 shadow-2xs">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>Ruang Privat: <strong>Misbachul Munir &hearts; Syahra Alfi</strong></span>
                    </span>
                </div>
            </div>

            <!-- Right Side: Actions (Nudge, Dark Mode, Profile) -->
            <div class="flex items-center gap-2.5 sm:gap-3">
                <!-- Kirim Sentuhan Virtual Button -->
                <button 
                    type="button" 
                    @click="$dispatch('open-nudge-modal')" 
                    class="inline-flex items-center gap-1.5 px-3 sm:px-3.5 py-1.5 rounded-full text-xs font-semibold bg-gradient-to-r from-pink-500/15 via-rose-500/15 to-purple-500/15 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 hover:scale-102 hover:shadow-xs transition"
                >
                    <span class="animate-pulse">✨</span>
                    <span class="hidden sm:inline">Kirim Sentuhan</span>
                    <span class="sm:hidden">Sentuh</span>
                </button>

                <!-- Dark Mode Toggle Button -->
                <button 
                    type="button" 
                    @click="$store.theme.toggle()"
                    class="p-2 rounded-xl text-gray-500 hover:text-rose-600 dark:text-gray-400 dark:hover:text-rose-300 bg-rose-50/70 dark:bg-gray-800 hover:bg-rose-100 dark:hover:bg-gray-700 transition shadow-2xs border border-rose-200/50 dark:border-gray-700"
                    title="Ganti Tema Gelap / Terang"
                >
                    <span x-show="!$store.theme.dark" class="text-base">🌙</span>
                    <span x-show="$store.theme.dark" x-cloak class="text-base">☀️</span>
                </button>

                <!-- User Dropdown (Desktop & Mobile) -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-rose-200/60 dark:border-gray-700 text-sm leading-4 font-medium rounded-full text-gray-700 dark:text-gray-300 bg-white/70 dark:bg-gray-800/70 hover:bg-rose-50/60 dark:hover:bg-gray-700/60 focus:outline-none transition ease-in-out duration-150">
                            <span class="w-6 h-6 rounded-full bg-gradient-to-tr from-pink-500 to-rose-400 text-white flex items-center justify-center text-3xs font-bold">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            <span class="hidden sm:inline max-w-[120px] truncate">{{ Auth::user()->name }}</span>

                            <div class="ms-0.5">
                                <svg class="fill-current h-3.5 w-3.5 opacity-70" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-2 border-b border-gray-100 dark:border-gray-700">
                            <p class="text-xs font-bold text-gray-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-3xs text-gray-400 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')">
                            👤 {{ __('Profil & Sandi') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                🚪 {{ __('Keluar') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

        </div>
    </div>
</nav>

