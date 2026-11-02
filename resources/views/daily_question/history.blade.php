<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <span>📜</span> {{ __('Riwayat Pertanyaan Harian') }}
            </h2>
            <a href="{{ route('daily-question.index') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-pink-600 dark:text-pink-400 hover:underline">
                &larr; Kembali ke Pertanyaan Hari Ini
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if ($history->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-pink-100 dark:bg-pink-900/30 text-pink-600 dark:text-pink-400 flex items-center justify-center text-3xl">
                        💌
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Riwayat Pertanyaan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Mulai jawab pertanyaan harian pertamamu bersama pasangan hari ini!
                    </p>
                    <a href="{{ route('daily-question.index') }}" class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-sm font-semibold shadow-sm transition">
                        Buka Pertanyaan Hari Ini &rarr;
                    </a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($history as $item)
                        @php
                            $bothAnswered = $item->areBothAnswersSubmitted();
                            $myAns = $item->answers->firstWhere('user_id', $currentUserId);
                            $partnerAns = $item->answers->firstWhere('user_id', '!=', $currentUserId);
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 p-6 shadow-sm space-y-4">
                            <!-- Header Pertanyaan -->
                            <div class="flex items-center justify-between flex-wrap gap-2 pb-3 border-b border-gray-100 dark:border-gray-700/60">
                                <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">
                                    🗓️ {{ \Carbon\Carbon::parse($item->served_date)->translatedFormat('l, d F Y') }}
                                </span>
                                <div>
                                    @if ($bothAnswered)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                            ✅ Terbuka Lengkap
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800">
                                            🔒 Belum Dijawab Lengkap
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Teks Pertanyaan -->
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white leading-relaxed">
                                “{{ $item->question->question_text }}”
                            </h3>

                            <!-- Jawaban -->
                            @if ($bothAnswered)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-2">
                                    <!-- Jawaban Pengguna -->
                                    <div class="bg-pink-50/40 dark:bg-gray-900/60 rounded-2xl p-4 border border-pink-100 dark:border-pink-900/30">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="w-6 h-6 rounded-full bg-pink-100 text-pink-600 dark:bg-pink-900/50 dark:text-pink-300 flex items-center justify-center text-xs font-bold">
                                                {{ substr($myAns?->user->name ?? 'K', 0, 1) }}
                                            </span>
                                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $myAns?->user->name ?? 'Kamu' }}</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
                                            {{ $myAns?->answer_text }}
                                        </p>
                                    </div>

                                    <!-- Jawaban Pasangan -->
                                    <div class="bg-purple-50/40 dark:bg-gray-900/60 rounded-2xl p-4 border border-purple-100 dark:border-purple-900/30">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span class="w-6 h-6 rounded-full bg-purple-100 text-purple-600 dark:bg-purple-900/50 dark:text-purple-300 flex items-center justify-center text-xs font-bold">
                                                {{ substr($partnerAns?->user->name ?? 'P', 0, 1) }}
                                            </span>
                                            <span class="text-xs font-semibold text-gray-800 dark:text-gray-200">{{ $partnerAns?->user->name ?? 'Pasanganmu' }}</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap leading-relaxed">
                                            {{ $partnerAns?->answer_text }}
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-4 border border-gray-200 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span>🔒</span>
                                        <span>Jawaban disembunyikan sampai kedua pasangan sama-sama mengirim jawaban.</span>
                                    </div>
                                    <a href="{{ route('daily-question.index') }}" class="font-semibold text-pink-600 dark:text-pink-400 hover:underline ml-4 whitespace-nowrap">
                                        Jawab Sekarang &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div>
                        {{ $history->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
