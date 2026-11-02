<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-bold text-xl sm:text-2xl text-gray-900 dark:text-white leading-tight">
                    {{ $greeting }}, {{ $user->name }}! ✨
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                    🗓️ {{ \Carbon\Carbon::now('Asia/Jakarta')->translatedFormat('l, d F Y') }} &bull; NuraSpace Couple & Family
                </p>
            </div>
            <button 
                type="button" 
                @click="$dispatch('open-nudge-modal')" 
                class="hidden sm:inline-flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-gradient-to-r from-pink-600 to-rose-600 text-white text-xs font-semibold shadow-md hover:scale-102 transition"
            >
                <span>✨</span> Kirim Sentuhan
            </button>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="text-xl">✨</span>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @if ($isPartner)
                <!-- ========================================== -->
                <!-- TAMPILAN KHUSUS PASANGAN (PARTNER DASHBOARD) -->
                <!-- ========================================== -->

                <!-- Romantic Couple Hero Banner -->
                <div class="relative overflow-hidden bg-gradient-to-br from-pink-500/10 via-rose-500/10 to-purple-500/15 dark:from-pink-950/40 dark:via-gray-900 dark:to-purple-950/40 border border-pink-200/80 dark:border-pink-900/40 rounded-3xl p-6 sm:p-8 shadow-sm">
                    <!-- Subtle Glow Blob -->
                    <div class="absolute -right-10 -bottom-10 w-48 h-48 bg-pink-400/20 dark:bg-pink-600/10 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between gap-6">
                        <!-- Couple Avatar & Identity -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 text-center sm:text-left">
                            <!-- Intertwined Avatars -->
                            <div class="relative flex items-center justify-center">
                                <div class="w-16 h-16 rounded-full bg-gradient-to-tr from-pink-600 to-rose-500 text-white font-serif font-bold text-2xl flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800 z-10">
                                    M
                                </div>
                                <div class="w-16 h-16 -ml-5 rounded-full bg-gradient-to-tr from-purple-600 to-pink-500 text-white font-serif font-bold text-2xl flex items-center justify-center shadow-lg ring-4 ring-white dark:ring-gray-800">
                                    S
                                </div>
                                <span class="absolute -bottom-1 left-1/2 -translate-x-1/2 bg-white dark:bg-gray-800 px-2 py-0.5 rounded-full text-xs shadow-xs border border-pink-200 dark:border-pink-800 z-20">
                                    💖
                                </span>
                            </div>

                            <div>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-3xs font-bold tracking-widest uppercase bg-pink-100/80 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300 border border-pink-200/60 dark:border-pink-800/60 mb-1.5">
                                    <span>✨</span> Ruang Privat Kekasih
                                </div>
                                <h3 class="font-serif font-bold text-2xl text-gray-900 dark:text-white leading-snug">
                                    Misbachul Munir <span class="text-pink-500">&hearts;</span> Syahra Alfi
                                </h3>
                                <p class="text-xs text-gray-600 dark:text-gray-300 mt-1 italic max-w-md">
                                    “Setiap detik yang kita lewati berdua adalah bab terindah dalam kisah cinta kita.”
                                </p>
                            </div>
                        </div>

                        <!-- Romantic Quick Shortcuts -->
                        <div class="flex items-center gap-2 flex-wrap justify-center">
                            <a href="{{ route('daily-question.index') }}" class="px-3.5 py-2 rounded-2xl bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold border border-pink-100 dark:border-gray-700 shadow-2xs hover:shadow-xs transition flex items-center gap-1.5 active:scale-95">
                                <span>💬</span> Kuis Cinta
                            </a>
                            <a href="{{ route('journals.index') }}" class="px-3.5 py-2 rounded-2xl bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold border border-pink-100 dark:border-gray-700 shadow-2xs hover:shadow-xs transition flex items-center gap-1.5 active:scale-95">
                                <span>📖</span> Curhatan
                            </a>
                            <a href="{{ route('time-capsules.index') }}" class="px-3.5 py-2 rounded-2xl bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold border border-pink-100 dark:border-gray-700 shadow-2xs hover:shadow-xs transition flex items-center gap-1.5 active:scale-95">
                                <span>💌</span> Kapsul Waktu
                            </a>
                            <a href="{{ route('memories.index') }}" class="px-3.5 py-2 rounded-2xl bg-white/80 dark:bg-gray-800/80 hover:bg-white dark:hover:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-semibold border border-pink-100 dark:border-gray-700 shadow-2xs hover:shadow-xs transition flex items-center gap-1.5 active:scale-95">
                                <span>📸</span> Galeri
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Quick Nudge Floating Bar (Mobile & Desktop) -->
                <div class="bg-gradient-to-r from-pink-500/10 via-purple-500/10 to-rose-500/10 border border-pink-200/80 dark:border-pink-900/40 rounded-3xl p-4 sm:p-5 flex flex-col sm:flex-row items-center justify-between gap-4 shadow-xs">
                    <div class="flex items-center gap-3 text-center sm:text-left">
                        <span class="text-3xl">💌</span>
                        <div>
                            <h4 class="font-bold text-gray-900 dark:text-white text-sm">
                                Kirim Sentuhan Manis ke {{ $partner?->name ?? 'Pasangan' }}
                            </h4>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Berikan getaran rasa sayang langsung di layarnya saat ini juga.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-2.5 w-full sm:w-auto justify-center">
                        <!-- Quick Hug -->
                        <form action="{{ route('nudges.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nudge_type" value="hug">
                            @if ($partner) <input type="hidden" name="recipient_id" value="{{ $partner->id }}"> @endif
                            <button type="submit" class="px-3.5 py-2 rounded-2xl bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 dark:hover:bg-amber-900/50 text-amber-800 dark:text-amber-200 text-xs font-bold border border-amber-200 dark:border-amber-800 shadow-2xs flex items-center gap-1.5 transition active:scale-95">
                                <span>🤗</span> Peluk
                            </button>
                        </form>

                        <!-- Quick Kiss -->
                        <form action="{{ route('nudges.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nudge_type" value="kiss">
                            @if ($partner) <input type="hidden" name="recipient_id" value="{{ $partner->id }}"> @endif
                            <button type="submit" class="px-3.5 py-2 rounded-2xl bg-pink-50 hover:bg-pink-100 dark:bg-pink-900/30 dark:hover:bg-pink-900/50 text-pink-800 dark:text-pink-200 text-xs font-bold border border-pink-200 dark:border-pink-800 shadow-2xs flex items-center gap-1.5 transition active:scale-95">
                                <span>😘</span> Kecup
                            </button>
                        </form>

                        <!-- Quick Miss You -->
                        <form action="{{ route('nudges.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nudge_type" value="miss_you">
                            @if ($partner) <input type="hidden" name="recipient_id" value="{{ $partner->id }}"> @endif
                            <button type="submit" class="px-3.5 py-2 rounded-2xl bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/30 dark:hover:bg-purple-900/50 text-purple-800 dark:text-purple-200 text-xs font-bold border border-purple-200 dark:border-purple-800 shadow-2xs flex items-center gap-1.5 transition active:scale-95">
                                <span>🥺❤️</span> Kangen
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Grid Baris 1: Mood Tracker & Countdown Terdekat -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Widget 1: Mood Tracker Pasangan Hari Ini -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>💖</span> Mood Pasangan Hari Ini
                                </h3>
                                <span class="text-3xs text-gray-400">Diperbarui setiap hari</span>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-4">
                                <!-- Status Pasangan -->
                                <div class="bg-purple-50/50 dark:bg-gray-750/50 rounded-2xl p-4 border border-purple-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/40 text-purple-600 dark:text-purple-300 font-bold text-xs flex items-center justify-center">
                                            {{ substr($partner?->name ?? 'P', 0, 1) }}
                                        </div>
                                        <div class="truncate">
                                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">{{ $partner?->name ?? 'Pasangan' }}</h4>
                                            <span class="text-3xs text-gray-400">Kekasih Tercinta</span>
                                        </div>
                                    </div>
                                    @if ($partnerMood)
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-3xl">{{ $partnerMood->emoji }}</span>
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $partnerMood->label }}</span>
                                                @if ($partnerMood->note)
                                                    <p class="text-3xs text-gray-500 italic mt-0.5 line-clamp-1">"{{ $partnerMood->note }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-gray-400 italic flex items-center gap-1.5">
                                            <span>⏳</span> Belum update mood hari ini
                                        </div>
                                    @endif
                                </div>

                                <!-- Status Pengguna Sendiri -->
                                <div class="bg-pink-50/50 dark:bg-gray-750/50 rounded-2xl p-4 border border-pink-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 font-bold text-xs flex items-center justify-center">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h4 class="text-xs font-bold text-gray-900 dark:text-white">{{ $user->name }} (Kamu)</h4>
                                            <span class="text-3xs text-gray-400">Perasaanmu</span>
                                        </div>
                                    </div>
                                    @if ($myMood)
                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-3xl">{{ $myMood->emoji }}</span>
                                            <div>
                                                <span class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $myMood->label }}</span>
                                                @if ($myMood->note)
                                                    <p class="text-3xs text-gray-500 italic mt-0.5 line-clamp-1">"{{ $myMood->note }}"</p>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <div class="mt-2">
                                            <p class="text-3xs text-pink-600 dark:text-pink-400 font-semibold mb-2">Bagaimana perasaanmu hari ini?</p>
                                            <div class="flex items-center gap-2">
                                                @foreach (['happy' => '🥰', 'romantic' => '💖', 'grateful' => '😇', 'tired' => '😴'] as $key => $emo)
                                                    <form action="{{ route('moods.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="mood_type" value="{{ $key }}">
                                                        <button type="submit" class="text-xl p-1.5 rounded-xl hover:bg-white dark:hover:bg-gray-700 transition">
                                                            {{ $emo }}
                                                        </button>
                                                    </form>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-750 flex justify-end">
                            <a href="{{ route('journals.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                Buka Ruang Curhatan &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Widget 2: Countdown Terdekat / Pinned -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>⏳</span> Countdown Hari Penting
                                </h3>
                                <a href="{{ route('countdowns.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                    Lihat Semua &rarr;
                                </a>
                            </div>

                            @if ($countdown)
                                <div class="mt-4 text-center space-y-3" x-data="{
                                    target: new Date('{{ $countdown->target_date->format('Y-m-d\TH:i:s') }}').getTime(),
                                    days: 0, hours: 0, minutes: 0, seconds: 0,
                                    init() {
                                        this.update();
                                        setInterval(() => this.update(), 1000);
                                    },
                                    update() {
                                        const now = new Date().getTime();
                                        const diff = Math.max(0, this.target - now);
                                        this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                                        this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                        this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                                        this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                                    }
                                }">
                                    <div>
                                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-pink-50 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300">
                                            🎯 {{ $countdown->title }}
                                        </span>
                                        <p class="text-3xs text-gray-400 mt-1">
                                            🗓️ {{ \Carbon\Carbon::parse($countdown->target_date)->translatedFormat('l, d F Y') }}
                                        </p>
                                    </div>

                                    <!-- Live Timer Blocks -->
                                    <div class="grid grid-cols-4 gap-2 max-w-xs mx-auto">
                                        <div class="bg-gray-50 dark:bg-gray-750/70 rounded-2xl p-2.5">
                                            <span class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white" x-text="days">0</span>
                                            <p class="text-3xs text-gray-400 uppercase font-semibold">Hari</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-750/70 rounded-2xl p-2.5">
                                            <span class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white" x-text="hours">0</span>
                                            <p class="text-3xs text-gray-400 uppercase font-semibold">Jam</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-750/70 rounded-2xl p-2.5">
                                            <span class="text-xl sm:text-2xl font-black text-gray-900 dark:text-white" x-text="minutes">0</span>
                                            <p class="text-3xs text-gray-400 uppercase font-semibold">Mnt</p>
                                        </div>
                                        <div class="bg-gray-50 dark:bg-gray-750/70 rounded-2xl p-2.5">
                                            <span class="text-xl sm:text-2xl font-black text-pink-600 dark:text-pink-400" x-text="seconds">0</span>
                                            <p class="text-3xs text-gray-400 uppercase font-semibold">Dtk</p>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="py-8 text-center text-xs text-gray-400 space-y-2">
                                    <p>Belum ada countdown yang aktif.</p>
                                    <a href="{{ route('countdowns.index') }}" class="inline-block px-3 py-1.5 rounded-xl bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300 font-semibold text-3xs">
                                        Tambah Countdown Baru &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-750 text-3xs text-gray-400 text-center">
                            Setiap detik menuju momen bahagia bersama
                        </div>
                    </div>
                </div>

                <!-- Grid Baris 2: Kuis Cinta & Tabungan Bersama -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Widget 3: Pertanyaan Harian (Kuis Cinta) -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>💬</span> Pertanyaan Harian Pasangan
                                </h3>
                                <a href="{{ route('daily-question.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                    Buka Kuis &rarr;
                                </a>
                            </div>

                            @if ($dailyQuestionRecord)
                                <div class="mt-4 space-y-3">
                                    <div class="bg-pink-50/40 dark:bg-gray-750/40 rounded-2xl p-4 border border-pink-100/60 dark:border-gray-700">
                                        <span class="text-3xs font-bold text-pink-600 uppercase tracking-widest">Pertanyaan Hari Ini</span>
                                        <h4 class="text-sm font-bold text-gray-900 dark:text-white mt-1 leading-snug">
                                            “{{ $dailyQuestionRecord->question->question_text }}”
                                        </h4>
                                    </div>

                                    @if ($bothAnswered)
                                        <div class="p-3 bg-emerald-50 dark:bg-emerald-900/30 rounded-2xl border border-emerald-200 dark:border-emerald-800 text-xs text-emerald-800 dark:text-emerald-200 flex items-center justify-between">
                                            <span>🎉 Keduanya telah menjawab! Rahasia jawaban terbuka.</span>
                                            <a href="{{ route('daily-question.index') }}" class="font-bold underline ml-2">Lihat Jawaban &rarr;</a>
                                        </div>
                                    @elseif ($myAnswer)
                                        <div class="p-3 bg-amber-50 dark:bg-amber-900/30 rounded-2xl border border-amber-200 dark:border-amber-800 text-xs text-amber-800 dark:text-amber-200 flex items-center gap-2">
                                            <span>🔒</span>
                                            <span>Jawabanmu tersimpan! Menunggu jawaban {{ $partner?->name ?? 'pasanganmu' }}.</span>
                                        </div>
                                    @else
                                        <form action="{{ route('daily-question.answer') }}" method="POST" class="space-y-2">
                                            @csrf
                                            <input type="hidden" name="daily_question_couple_id" value="{{ $dailyQuestionRecord->id }}">
                                            <input 
                                                type="text" 
                                                name="answer_text" 
                                                required 
                                                placeholder="Tuliskan jawaban rahasiamu hari ini..."
                                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-xs focus:border-pink-500 focus:ring-pink-500"
                                            >
                                            <div class="flex justify-end">
                                                <button type="submit" class="px-4 py-1.5 bg-pink-600 hover:bg-pink-700 text-white rounded-lg text-xs font-semibold shadow-xs">
                                                    Kirim Jawaban 💖
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            @else
                                <div class="py-6 text-center text-xs text-gray-400">
                                    <a href="{{ route('daily-question.index') }}" class="text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                        Buka kuis harian untuk memuat pertanyaan hari ini &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-750 flex items-center justify-between text-3xs text-gray-400">
                            <span>Kerahasiaan jawaban terjamin sampai berdua menjawab</span>
                            <a href="{{ route('daily-question.history') }}" class="hover:underline">Riwayat</a>
                        </div>
                    </div>

                    <!-- Widget 4: Target Tabungan Berjalan -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>💰</span> Tabungan Bersama
                                </h3>
                                <a href="{{ route('savings.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                    Kelola &rarr;
                                </a>
                            </div>

                            @if ($activeGoal)
                                @php
                                    $pct = $activeGoal->progress_percentage;
                                    $colorGradient = \App\Models\SavingsGoal::COLORS[$activeGoal->color] ?? 'from-pink-500 to-rose-500';
                                @endphp
                                <div class="mt-4 space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-2xl">{{ $activeGoal->icon }}</span>
                                            <div>
                                                <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $activeGoal->name }}</h4>
                                                <span class="text-3xs text-gray-400">Target: {{ $activeGoal->formatted_target_amount }}</span>
                                            </div>
                                        </div>
                                        <span class="text-xs font-bold text-pink-600 dark:text-pink-400">{{ $pct }}%</span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden">
                                        <div class="h-2.5 rounded-full bg-gradient-to-r {{ $colorGradient }} transition-all duration-500" style="width: {{ $pct }}%"></div>
                                    </div>

                                    <div class="flex items-center justify-between text-3xs text-gray-500 pt-1">
                                        <span>Terkumpul: <strong class="text-gray-900 dark:text-white">{{ $activeGoal->formatted_current_amount }}</strong></span>
                                        <span>Sisa: <strong class="text-rose-500">{{ $activeGoal->formatted_remaining_amount }}</strong></span>
                                    </div>
                                </div>
                            @else
                                <div class="py-6 text-center text-xs text-gray-400 space-y-2">
                                    <p>Belum ada target tabungan aktif.</p>
                                    <a href="{{ route('savings.index') }}" class="inline-block px-3 py-1.5 rounded-xl bg-pink-100 dark:bg-pink-900/40 text-pink-700 dark:text-pink-300 font-semibold text-3xs">
                                        Buat Target Baru &rarr;
                                    </a>
                                </div>
                            @endif
                        </div>

                        <div class="mt-4 pt-3 border-t border-gray-50 dark:border-gray-750 flex items-center justify-between text-3xs text-gray-400">
                            <span>Wujudkan impian keluarga bersama</span>
                            <a href="{{ route('wishlists.index') }}" class="hover:underline">Buka Wishlist &rarr;</a>
                        </div>
                    </div>
                </div>

                <!-- Grid Baris 3: Kenangan Terbaru & Acara Terdekat -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    <!-- Widget 5: Kenangan Terbaru -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>📸</span> Momen Terakhir di Memory Lane
                                </h3>
                                <a href="{{ route('memories.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                    Buka Galeri &rarr;
                                </a>
                            </div>

                            @if ($recentMemory && $recentMemory->primaryMedia())
                                <div class="mt-4 flex items-center gap-4">
                                    <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-100 shrink-0">
                                        @if ($recentMemory->primaryMedia()->isImage())
                                            <img src="{{ $recentMemory->primaryMedia()->signed_url }}" alt="{{ $recentMemory->title }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-900 text-white text-xl">🎥</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <span class="text-3xs text-gray-400">🗓️ {{ \Carbon\Carbon::parse($recentMemory->memory_date)->translatedFormat('d F Y') }}</span>
                                        <h4 class="font-bold text-sm text-gray-900 dark:text-white truncate mt-0.5">{{ $recentMemory->title }}</h4>
                                        @if ($recentMemory->location)
                                            <p class="text-3xs text-gray-500 mt-0.5">📍 {{ $recentMemory->location }}</p>
                                        @endif
                                        <a href="{{ route('memories.show', $recentMemory) }}" class="inline-block mt-2 text-3xs font-semibold text-pink-600 dark:text-pink-400 hover:underline">
                                            Buka Cerita Kenangan &rarr;
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="py-6 text-center text-xs text-gray-400">
                                    Belum ada foto yang diabadikan ke galeri.
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Widget 6: Acara Kalender Terdekat -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs flex flex-col justify-between">
                        <div>
                            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                                <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                    <span>🗓️</span> Agenda Mendatang
                                </h3>
                                <a href="{{ route('events.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                    Buka Kalender &rarr;
                                </a>
                            </div>

                            @if ($nextEvent)
                                <div class="mt-4 p-4 rounded-2xl bg-gray-50 dark:bg-gray-750/50 border border-gray-100 dark:border-gray-700 space-y-1">
                                    <span class="text-3xs font-semibold text-pink-600 uppercase tracking-wider">
                                        🗓️ {{ \Carbon\Carbon::parse($nextEvent->start_at)->translatedFormat('l, d F Y - H:i') }}
                                    </span>
                                    <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $nextEvent->title }}</h4>
                                    @if ($nextEvent->description)
                                        <p class="text-xs text-gray-500 line-clamp-1">{{ $nextEvent->description }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="py-6 text-center text-xs text-gray-400">
                                    Tidak ada agenda terdekat.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            @else
                <!-- ========================================== -->
                <!-- TAMPILAN KHUSUS ANAK (CHILD DASHBOARD)    -->
                <!-- ========================================== -->

                <div class="bg-gradient-to-r from-blue-500/10 via-purple-500/10 to-pink-500/10 border border-blue-200/80 dark:border-blue-900/40 rounded-3xl p-6 shadow-xs text-center sm:text-left flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <span class="text-4xl">🏡</span>
                        <div>
                            <h3 class="font-extrabold text-lg text-gray-900 dark:text-white">
                                Ruang Keluarga NuraSpace
                            </h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Tempat berbagi momen ceria, foto keluarga, dan hari-hari spesial kita.
                            </p>
                        </div>
                    </div>

                    <!-- Quick Nudge to Parents -->
                    <div class="flex items-center gap-2">
                        <form action="{{ route('nudges.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nudge_type" value="hug">
                            <button type="submit" class="px-4 py-2 rounded-2xl bg-gradient-to-r from-pink-500 to-rose-500 text-white text-xs font-bold shadow-sm hover:scale-102 transition flex items-center gap-1.5">
                                <span>🤗</span> Peluk Orang Tua
                            </button>
                        </form>
                        <form action="{{ route('nudges.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="nudge_type" value="miss_you">
                            <button type="submit" class="px-4 py-2 rounded-2xl bg-gradient-to-r from-purple-500 to-indigo-500 text-white text-xs font-bold shadow-sm hover:scale-102 transition flex items-center gap-1.5">
                                <span>🥺❤️</span> Kangen
                            </button>
                        </form>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Family Event -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                <span>🗓️</span> Acara Keluarga Mendatang
                            </h3>
                            <a href="{{ route('events.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                Kalender &rarr;
                            </a>
                        </div>

                        @if ($nextFamilyEvent)
                            <div class="p-4 rounded-2xl bg-pink-50/50 dark:bg-gray-750/50 border border-pink-100 dark:border-gray-700">
                                <span class="text-3xs font-semibold text-pink-600 uppercase">
                                    🗓️ {{ \Carbon\Carbon::parse($nextFamilyEvent->start_at)->translatedFormat('l, d F Y') }}
                                </span>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white mt-1">{{ $nextFamilyEvent->title }}</h4>
                                @if ($nextFamilyEvent->description)
                                    <p class="text-xs text-gray-500 mt-1">{{ $nextFamilyEvent->description }}</p>
                                @endif
                            </div>
                        @else
                            <div class="py-6 text-center text-xs text-gray-400">
                                Belum ada acara keluarga dalam waktu dekat.
                            </div>
                        @endif
                    </div>

                    <!-- Family Countdown -->
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                <span>⏳</span> Menuju Hari Spesial
                            </h3>
                            <a href="{{ route('countdowns.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                Countdown &rarr;
                            </a>
                        </div>

                        @if ($countdown)
                            <div class="p-4 rounded-2xl bg-purple-50/50 dark:bg-gray-750/50 border border-purple-100 dark:border-gray-700 text-center space-y-2">
                                <span class="text-xs font-bold text-purple-700 dark:text-purple-300">
                                    {{ $countdown->title }}
                                </span>
                                <p class="text-xl font-black text-gray-900 dark:text-white">
                                    {{ (int) max(0, now()->diffInDays($countdown->target_date)) }} Hari Lagi!
                                </p>
                                <p class="text-3xs text-gray-400">
                                    🗓️ {{ \Carbon\Carbon::parse($countdown->target_date)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        @else
                            <div class="py-6 text-center text-xs text-gray-400">
                                Belum ada hari spesial yang dihitung mundur.
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Family Gallery Preview -->
                @if ($recentMemory && $recentMemory->primaryMedia())
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 border border-gray-100 dark:border-gray-700 shadow-2xs space-y-4">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                                <span>📸</span> Foto Keluarga Terbaru
                            </h3>
                            <a href="{{ route('memories.index') }}" class="text-3xs text-pink-600 dark:text-pink-400 font-semibold hover:underline">
                                Lihat Semua Foto &rarr;
                            </a>
                        </div>

                        <div class="flex items-center gap-4">
                            <div class="w-24 h-24 rounded-2xl overflow-hidden bg-gray-100 shrink-0">
                                <img src="{{ $recentMemory->primaryMedia()->signed_url }}" alt="{{ $recentMemory->title }}" class="w-full h-full object-cover">
                            </div>
                            <div>
                                <h4 class="font-bold text-sm text-gray-900 dark:text-white">{{ $recentMemory->title }}</h4>
                                <p class="text-3xs text-gray-400">🗓️ {{ \Carbon\Carbon::parse($recentMemory->memory_date)->translatedFormat('d F Y') }}</p>
                                <a href="{{ route('memories.show', $recentMemory) }}" class="inline-block mt-2 text-3xs font-semibold text-pink-600 dark:text-pink-400 hover:underline">
                                    Buka Foto & Cerita &rarr;
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            @endif

        </div>
    </div>
</x-app-layout>
