<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('time-capsules.index') }}" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">
                    {{ $capsule->title }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    Dari {{ $capsule->sender->name }} • Disegel pada {{ $capsule->created_at->format('d M Y') }}
                </p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            @if (!$isUnlocked)
                {{-- STATUS TERKUNCI (LOCKED VIEW) --}}
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-8 sm:p-12 shadow-sm border border-amber-200 dark:border-amber-800/60 text-center relative overflow-hidden">
                    <div class="w-20 h-20 rounded-full bg-amber-100 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-4xl mx-auto mb-5 shadow-inner animate-pulse">
                        🔒
                    </div>

                    <span class="inline-flex items-center px-3 py-1 text-xs font-bold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 rounded-full border border-amber-300 dark:border-amber-700 mb-4">
                        Surat Ini Sedang Tersegel Rapi
                    </span>

                    <h3 class="text-2xl font-black text-gray-900 dark:text-gray-100 mb-2">
                        Belum Waktunya Membuka Pesan Ini
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 max-w-md mx-auto leading-relaxed">
                        Surat ini telah dikunci rapat oleh <strong>{{ $capsule->sender->name }}</strong>. Isi surat dilindungi secara ketat oleh sistem dan baru akan terbuka pada waktu yang ditentukan.
                    </p>

                    <div class="mt-8 p-6 bg-gradient-to-br from-amber-50 to-orange-50 dark:from-gray-900 dark:to-gray-800/80 rounded-2xl border border-amber-200/60 dark:border-amber-900/40 inline-block max-w-sm w-full">
                        <div class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">
                            Waktu Buka Kapsul
                        </div>
                        <div class="text-lg font-bold text-gray-900 dark:text-gray-100">
                            {{ $capsule->unlock_at->format('d F Y, H:i') }} WIB
                        </div>
                        <div class="text-xs text-rose-500 dark:text-rose-400 font-semibold mt-2">
                            ⏳ Akan terbuka {{ $capsule->unlock_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="mt-8">
                        <a href="{{ route('time-capsules.index') }}" class="text-xs font-semibold text-gray-500 hover:text-gray-700 dark:hover:text-gray-300">
                            ← Kembali ke daftar kapsul waktu
                        </a>
                    </div>
                </div>
            @else
                {{-- STATUS TERBUKA (UNLOCKED LETTER VIEW) --}}
                <div class="bg-gradient-to-b from-amber-50/50 to-rose-50/30 dark:from-gray-800 dark:to-gray-900 rounded-3xl p-8 sm:p-12 shadow-md border border-rose-100 dark:border-gray-700 relative">
                    <div class="flex items-center justify-between border-b border-rose-200/60 dark:border-gray-700 pb-5 mb-8">
                        <div class="flex items-center gap-2">
                            <span class="text-2xl">💌</span>
                            <div>
                                <span class="text-xs font-bold text-rose-600 dark:text-rose-400 uppercase tracking-wider">Surat Telah Terbuka</span>
                                <p class="text-[11px] text-gray-400">Jadwal buka: {{ $capsule->unlock_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        <div class="w-12 h-12 rounded-full border-2 border-dashed border-rose-300 dark:border-rose-700 flex items-center justify-center text-xs font-bold text-rose-500 rotate-12">
                            SEALED
                        </div>
                    </div>

                    <div class="prose dark:prose-invert max-w-none text-gray-900 dark:text-gray-100 text-base leading-relaxed whitespace-pre-wrap font-serif">
                        {{ $safeContent }}
                    </div>

                    <div class="mt-12 pt-6 border-t border-rose-200/60 dark:border-gray-700 flex items-center justify-between text-xs text-gray-400">
                        <div>
                            <span>Dengan cinta,</span>
                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm mt-0.5">{{ $capsule->sender->name }}</h4>
                        </div>
                        <div>
                            @if ($capsule->opened_at)
                                <p class="text-[11px]">Pertama kali dibuka: {{ $capsule->opened_at->format('d M Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
