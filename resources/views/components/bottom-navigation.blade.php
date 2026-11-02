<nav class="lg:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 dark:bg-gray-900/95 backdrop-blur-lg border-t border-rose-100/80 dark:border-gray-800 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] px-3 py-2 transition-colors">
    <div class="flex items-center justify-around max-w-md mx-auto">
        <!-- Beranda -->
        <a 
            href="{{ route('dashboard') }}" 
            class="flex flex-col items-center gap-0.5 text-3xs font-medium transition {{ request()->routeIs('dashboard') ? 'text-pink-600 dark:text-pink-400 font-bold' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
        >
            <span class="text-xl">🏠</span>
            <span>Beranda</span>
        </a>

        <!-- Kuis Cinta / Kalender -->
        @if (Auth::user()->isPartner())
            <a 
                href="{{ route('daily-question.index') }}" 
                class="flex flex-col items-center gap-0.5 text-3xs font-medium transition {{ request()->routeIs('daily-question.*') ? 'text-pink-600 dark:text-pink-400 font-bold' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
            >
                <span class="text-xl">💬</span>
                <span>Kuis Cinta</span>
            </a>
        @else
            <a 
                href="{{ route('events.index') }}" 
                class="flex flex-col items-center gap-0.5 text-3xs font-medium transition {{ request()->routeIs('events.*') ? 'text-pink-600 dark:text-pink-400 font-bold' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
            >
                <span class="text-xl">🗓️</span>
                <span>Kalender</span>
            </a>
        @endif

        <!-- Floating Center Action: Kirim Sentuhan -->
        <button 
            type="button" 
            @click="$dispatch('open-nudge-modal')" 
            class="relative -top-4 w-12 h-12 rounded-full bg-gradient-to-tr from-pink-600 via-rose-500 to-purple-600 text-white flex items-center justify-center text-xl shadow-lg shadow-pink-500/30 active:scale-95 transition"
            title="Kirim Sentuhan"
        >
            <span>✨</span>
        </button>

        <!-- Galeri -->
        <a 
            href="{{ route('memories.index') }}" 
            class="flex flex-col items-center gap-0.5 text-3xs font-medium transition {{ request()->routeIs('memories.*') ? 'text-pink-600 dark:text-pink-400 font-bold' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
        >
            <span class="text-xl">📸</span>
            <span>Galeri</span>
        </a>

        <!-- Profil / Akun -->
        <a 
            href="{{ route('profile.edit') }}" 
            class="flex flex-col items-center gap-0.5 text-3xs font-medium transition {{ request()->routeIs('profile.*') ? 'text-pink-600 dark:text-pink-400 font-bold' : 'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300' }}"
        >
            <span class="text-xl">👤</span>
            <span>Profil</span>
        </a>
    </div>
</nav>
