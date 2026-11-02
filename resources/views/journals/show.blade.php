<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('journals.index') }}" class="p-2 rounded-xl text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100">
                        {{ $journal->title }}
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        Ditulis oleh {{ $journal->user->name }} • {{ $journal->entry_date->format('d F Y') }}
                    </p>
                </div>
            </div>

            @can('update', $journal)
                <div class="flex items-center gap-2">
                    <a href="{{ route('journals.edit', $journal) }}" class="px-3.5 py-2 text-xs font-semibold rounded-xl bg-gray-100 hover:bg-gray-200 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 transition">
                        Edit
                    </a>
                    <form action="{{ route('journals.destroy', $journal) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus curhatan ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="px-3.5 py-2 text-xs font-semibold rounded-xl bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 transition">
                            Hapus
                        </button>
                    </form>
                </div>
            @endcan
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white/95 dark:bg-gray-800/95 backdrop-blur-md rounded-3xl p-6 sm:p-10 shadow-xs border border-rose-100 dark:border-gray-700/80 space-y-6 relative overflow-hidden">
                <!-- Watermark Heart -->
                <div class="absolute -right-8 -bottom-8 text-9xl text-pink-500/5 dark:text-pink-500/5 select-none pointer-events-none font-serif">
                    💖
                </div>

                <div class="flex items-center gap-3">
                    @if ($journal->isPrivate())
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-3xs font-semibold bg-violet-50 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 rounded-full border border-violet-200 dark:border-violet-800">
                            🔒 Catatan Rahasia Pribadi
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 text-3xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 rounded-full border border-rose-200 dark:border-rose-800">
                            💌 Dibagikan Bersama Pasangan
                        </span>
                    @endif

                    @if ($journal->mood_tag)
                        <span class="px-3 py-1 text-3xs font-medium text-pink-600 dark:text-pink-300 bg-pink-50 dark:bg-pink-950/50 rounded-full border border-pink-200 dark:border-pink-900">
                            {{ $journal->mood_tag }}
                        </span>
                    @endif
                </div>

                <div class="prose dark:prose-invert max-w-none text-gray-800 dark:text-gray-200 text-base leading-relaxed whitespace-pre-wrap font-serif">
                    {{ $journal->content }}
                </div>

                <div class="pt-6 border-t border-rose-100/70 dark:border-gray-750 flex items-center justify-between text-xs text-gray-400">
                    <span>🗓️ Ditulis pada {{ $journal->entry_date->translatedFormat('d F Y') }}</span>
                    <a href="{{ route('journals.index') }}" class="text-pink-600 hover:text-pink-700 dark:text-pink-400 font-semibold flex items-center gap-1">
                        ← Kembali ke daftar curhatan
                    </a>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
