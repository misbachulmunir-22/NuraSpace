<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                <span>💬</span> {{ __('Pertanyaan Harian Pasangan') }}
            </h2>
            <a href="{{ route('daily-question.history') }}" class="inline-flex items-center text-xs sm:text-sm font-medium text-pink-600 dark:text-pink-400 hover:underline">
                📜 Riwayat Pertanyaan &rarr;
            </a>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-gradient-to-r from-pink-500/10 via-purple-500/10 to-indigo-500/10 border border-pink-200 dark:border-pink-800 text-pink-800 dark:text-pink-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-sm animate-fade-in">
                    <span class="text-2xl">✨</span>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @php
                $categoryMap = [
                    'romance' => ['label' => 'Kenangan & Romantisme', 'badge' => 'bg-pink-100 text-pink-700 dark:bg-pink-900/40 dark:text-pink-300 border-pink-200 dark:border-pink-800', 'icon' => '🌹'],
                    'deep' => ['label' => 'Refleksi & Kedekatan Hati', 'badge' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/40 dark:text-purple-300 border-purple-200 dark:border-purple-800', 'icon' => '💜'],
                    'future' => ['label' => 'Impian & Masa Depan', 'badge' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300 border-blue-200 dark:border-blue-800', 'icon' => '🌟'],
                    'fun' => ['label' => 'Keseruan & Tawa Manis', 'badge' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300 border-amber-200 dark:border-amber-800', 'icon' => '🎈'],
                    'gratitude' => ['label' => 'Apresiasi & Rasa Syukur', 'badge' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800', 'icon' => '💖'],
                ];
                $cat = $categoryMap[$question->category] ?? ['label' => 'Spesial Berdua', 'badge' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border-gray-200', 'icon' => '💌'];
            @endphp

            <!-- Card Utama Pertanyaan Hari Ini -->
            <div class="relative overflow-hidden bg-gradient-to-br from-white via-pink-50/20 to-purple-50/30 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900 border border-pink-100 dark:border-pink-900/30 rounded-3xl p-6 sm:p-8 shadow-sm backdrop-blur-sm">
                <!-- Background Glow -->
                <div class="absolute -top-16 -right-16 w-48 h-48 bg-pink-400/10 dark:bg-pink-500/5 rounded-full blur-2xl pointer-events-none"></div>

                <div class="flex items-center justify-between flex-wrap gap-2 mb-4">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold border {{ $cat['badge'] }}">
                        <span>{{ $cat['icon'] }}</span> {{ $cat['label'] }}
                    </span>
                    <span class="text-xs text-gray-400 dark:text-gray-500 font-medium">
                        🗓️ {{ \Carbon\Carbon::parse($todayRecord->served_date)->translatedFormat('l, d F Y') }}
                    </span>
                </div>

                <div class="my-4">
                    <p class="text-xs font-medium text-pink-500 dark:text-pink-400 uppercase tracking-widest mb-1">Pertanyaan Hari Ini</p>
                    <h3 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white leading-relaxed">
                        “{{ $question->question_text }}”
                    </h3>
                </div>

                <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Pertanyaan #{{ $question->order_index ?? $todayRecord->id }}</span>
                    <span>
                        @if ($bothAnswered)
                            <span class="inline-flex items-center gap-1 text-emerald-600 dark:text-emerald-400 font-medium">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                Lengkap terjawab oleh keduanya
                            </span>
                        @elseif ($myAnswer)
                            <span class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 font-medium">
                                ⏳ Menunggu {{ $partner ? $partner->name : 'pasangan' }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-pink-600 dark:text-pink-400 font-medium">
                                ✍️ Belum kamu jawab
                            </span>
                        @endif
                    </span>
                </div>
            </div>

            <!-- Bagian Jawaban -->
            @if ($bothAnswered)
                <!-- KEDUANYA SUDAH MENJAWAB: Tampilkan kedua kartu jawaban berdampingan -->
                <div class="space-y-4">
                    <div class="text-center py-2">
                        <span class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-sm font-semibold shadow-xs">
                            🎉 Keduanya Telah Menjawab! Rahasia Terbuka! 💖
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Kartu Jawaban Pengguna -->
                        <div class="bg-white dark:bg-gray-800 border-2 border-pink-200 dark:border-pink-800/60 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center font-bold text-sm">
                                            {{ substr(auth()->user()->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ auth()->user()->name }} (Kamu)</h4>
                                            <p class="text-xs text-gray-400">{{ $myAnswer->created_at->translatedFormat('H:i, d M') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-lg">💌</span>
                                </div>
                                <div class="mt-4 text-gray-700 dark:text-gray-200 text-sm whitespace-pre-wrap leading-relaxed">
                                    {{ $myAnswer->answer_text }}
                                </div>
                            </div>
                        </div>

                        <!-- Kartu Jawaban Pasangan -->
                        <div class="bg-white dark:bg-gray-800 border-2 border-purple-200 dark:border-purple-800/60 rounded-3xl p-6 shadow-sm flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300 flex items-center justify-center font-bold text-sm">
                                            {{ substr($partner?->name ?? 'P', 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900 dark:text-white text-sm">{{ $partner?->name ?? 'Pasanganmu' }}</h4>
                                            <p class="text-xs text-gray-400">{{ $partnerAnswer?->created_at->translatedFormat('H:i, d M') }}</p>
                                        </div>
                                    </div>
                                    <span class="text-lg">💖</span>
                                </div>
                                <div class="mt-4 text-gray-700 dark:text-gray-200 text-sm whitespace-pre-wrap leading-relaxed">
                                    {{ $partnerAnswer?->answer_text }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tombol Edit Jawaban Sendiri -->
                    <div x-data="{ editing: false }" class="mt-4 text-center">
                        <button @click="editing = !editing" class="text-xs text-gray-500 hover:text-pink-600 dark:hover:text-pink-400 underline">
                            Ingin memperbarui jawabanmu?
                        </button>

                        <div x-show="editing" x-cloak class="mt-4 text-left max-w-xl mx-auto bg-gray-50 dark:bg-gray-800/80 p-5 rounded-2xl border border-gray-200 dark:border-gray-700">
                            <form action="{{ route('daily-question.answer') }}" method="POST">
                                @csrf
                                <input type="hidden" name="daily_question_couple_id" value="{{ $todayRecord->id }}">
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-2">Edit Jawaban:</label>
                                <textarea name="answer_text" rows="3" required class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500">{{ $myAnswer->answer_text }}</textarea>
                                <div class="flex justify-end gap-2 mt-2">
                                    <button type="button" @click="editing = false" class="px-3 py-1.5 text-xs text-gray-600 dark:text-gray-400">Batal</button>
                                    <button type="submit" class="px-4 py-1.5 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-xs font-semibold">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @elseif ($myAnswer)
                <!-- PENGGUNA SUDAH MENJAWAB, PASANGAN BELUM: Tampilkan jawaban sendiri + Status Terkunci Pasangan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Kartu Jawaban Pengguna -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 shadow-sm">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center font-bold text-sm">
                                    {{ substr(auth()->user()->name, 0, 1) }}
                                </div>
                                <div>
                                    <h4 class="font-semibold text-gray-900 dark:text-white text-sm">Jawabanmu Telah Tersimpan</h4>
                                    <p class="text-xs text-emerald-600 dark:text-emerald-400 font-medium">✓ Menunggu pasangan</p>
                                </div>
                            </div>
                            <span class="text-lg">💌</span>
                        </div>
                        <div class="mt-4 text-gray-700 dark:text-gray-200 text-sm whitespace-pre-wrap leading-relaxed">
                            {{ $myAnswer->answer_text }}
                        </div>
                    </div>

                    <!-- Kartu Misteri Terkunci Pasangan -->
                    <div class="relative bg-gradient-to-br from-amber-50/60 to-purple-50/60 dark:from-gray-800/90 dark:to-gray-900/90 border-2 border-dashed border-amber-300 dark:border-amber-700/60 rounded-3xl p-6 flex flex-col items-center justify-center text-center">
                        <div class="w-14 h-14 rounded-2xl bg-amber-100 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl shadow-inner mb-3 animate-pulse">
                            🔒
                        </div>
                        <h4 class="font-bold text-gray-900 dark:text-white text-base">
                            Jawaban {{ $partner ? $partner->name : 'Pasanganmu' }} Masih Terkunci
                        </h4>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2 max-w-xs leading-relaxed">
                            Jawaban akan otomatis terbuka berdampingan begitu {{ $partner ? $partner->name : 'pasanganmu' }} selesai mengirim jawabannya.
                        </p>

                        <!-- Quick Nudge Button -->
                        @if ($partner)
                            <form action="{{ route('nudges.store') }}" method="POST" class="mt-5">
                                @csrf
                                <input type="hidden" name="nudge_type" value="miss_you">
                                <input type="hidden" name="recipient_id" value="{{ $partner->id }}">
                                <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-500 to-rose-500 hover:from-pink-600 hover:to-rose-600 text-white rounded-xl text-xs font-semibold shadow-sm transition hover:scale-102">
                                    <span>🥺❤️</span> Ingatkan {{ $partner->name }} ("Aku Kangen Kamu")
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

            @else
                <!-- PENGGUNA BELUM MENJAWAB: Form Input Jawaban -->
                <div class="bg-white dark:bg-gray-800 border border-pink-100 dark:border-gray-700 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center text-lg">
                            ✍️
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-base">Tulis Jawabanmu Hari Ini</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Jawab dengan tulus dari lubuk hatimu yang terdalam.</p>
                        </div>
                    </div>

                    <form action="{{ route('daily-question.answer') }}" method="POST" class="space-y-4">
                        @csrf
                        <input type="hidden" name="daily_question_couple_id" value="{{ $todayRecord->id }}">

                        <div>
                            <textarea 
                                name="answer_text" 
                                rows="4" 
                                required 
                                placeholder="Tuliskan jawabanmu di sini untuk dibaca oleh {{ $partner ? $partner->name : 'pasanganmu' }}..."
                                class="w-full rounded-2xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-200 text-sm sm:text-base focus:border-pink-500 focus:ring-pink-500 p-4 leading-relaxed"
                            >{{ old('answer_text') }}</textarea>
                            <x-input-error :messages="$errors->get('answer_text')" class="mt-2" />
                        </div>

                        <!-- Info Privasi Kunci -->
                        <div class="bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-xl p-3 flex items-start gap-2.5 text-xs text-purple-700 dark:text-purple-300">
                            <span class="text-base leading-none">🔐</span>
                            <p class="leading-relaxed">
                                <strong>Privasi Terjamin:</strong> Pasanganmu tidak akan bisa melihat jawabanmu sebelum dia juga mengirim jawabannya sendiri. Keduanya akan terbuka serentak!
                            </p>
                        </div>

                        <div class="flex justify-end pt-2">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white rounded-2xl font-semibold text-sm shadow-md transition hover:scale-102">
                                <span>💖</span> Kirim Jawaban Rahasia
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <!-- Riwayat Singkat Sebelumnya -->
            @if ($recentCompleted->count() > 0)
                <div class="pt-6">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-gray-900 dark:text-white text-sm flex items-center gap-2">
                            <span>📜</span> Pertanyaan Hari-Hari Sebelumnya
                        </h4>
                        <a href="{{ route('daily-question.history') }}" class="text-xs text-pink-600 dark:text-pink-400 hover:underline">
                            Lihat Semua &rarr;
                        </a>
                    </div>

                    <div class="space-y-3">
                        @foreach ($recentCompleted as $past)
                            <div class="bg-white dark:bg-gray-800 border border-gray-100 dark:border-gray-700 rounded-2xl p-4 shadow-2xs hover:border-pink-200 dark:hover:border-pink-800 transition">
                                <div class="flex items-center justify-between text-xs text-gray-400 mb-1.5">
                                    <span>🗓️ {{ \Carbon\Carbon::parse($past->served_date)->translatedFormat('d M Y') }}</span>
                                    <span>{{ $past->areBothAnswersSubmitted() ? '✅ Terjawab Berdua' : '⏳ Belum Lengkap' }}</span>
                                </div>
                                <p class="text-sm font-medium text-gray-800 dark:text-gray-200 mb-2">
                                    “{{ $past->question->question_text }}”
                                </p>
                                @if ($past->areBothAnswersSubmitted())
                                    <div class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-4">
                                        <span>💬 {{ $past->answers->count() }} jawaban terkumpul</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
