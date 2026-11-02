<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="text-rose-500">⏳</span> Hitung Mundur (Countdown)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Menghitung detik demi detik menuju hari bahagia, kencan romantis, atau liburan bersama.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('create-countdown-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Buat Countdown
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Alert Messages --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 rounded-2xl text-emerald-800 dark:text-emerald-200 text-sm font-medium flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if ($countdowns->isEmpty())
                <div class="text-center py-16 px-4 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                    <div class="text-5xl mb-3">⏳</div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Belum ada countdown yang dibuat</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                        Mulai hitung mundur ke momen spesial seperti liburan pantai, nonton konser, atau anniversary berikutnya!
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($countdowns as $c)
                        @php
                            $isPast = $c->isPast();
                            $daysRemaining = abs($c->daysRemaining());
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 shadow-sm border border-gray-100 dark:border-gray-700 flex flex-col justify-between hover:shadow-md transition relative overflow-hidden">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div class="w-12 h-12 rounded-2xl bg-rose-50 dark:bg-rose-950/60 flex items-center justify-center text-2xl shadow-inner">
                                        {{ $c->icon_emoji }}
                                    </div>

                                    <div class="flex items-center gap-2">
                                        @if ($c->is_pinned)
                                            <span class="px-2.5 py-1 text-[10px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 rounded-full border border-amber-200 dark:border-amber-800 flex items-center gap-1">
                                                📌 Disematkan
                                            </span>
                                        @endif

                                        <form action="{{ route('countdowns.pin', $c) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-amber-500 rounded-lg transition" title="{{ $c->is_pinned ? 'Lepas sematan' : 'Sematkan ke dashboard' }}">
                                                <svg class="w-4 h-4 {{ $c->is_pinned ? 'text-amber-500 fill-amber-500' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                                </svg>
                                            </button>
                                        </form>

                                        <form action="{{ route('countdowns.destroy', $c) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus countdown ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg transition">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 line-clamp-2">
                                    {{ $c->title }}
                                </h3>
                                <p class="text-xs text-gray-400 mt-1">
                                    {{ $c->target_date->format('l, d F Y - H:i') }}
                                </p>

                                {{-- Live Romantic Countdown Indicator --}}
                                <div class="my-5 p-4 rounded-2xl bg-gradient-to-br from-rose-50 to-pink-50/70 dark:from-gray-900 dark:to-gray-800/80 border border-rose-100 dark:border-gray-700 text-center"
                                     x-data="{
                                        target: new Date('{{ $c->target_date->format('Y-m-d\TH:i:s') }}').getTime(),
                                        days: {{ $daysRemaining }},
                                        hours: 0,
                                        minutes: 0,
                                        seconds: 0,
                                        isPast: {{ $isPast ? 'true' : 'false' }},
                                        init() {
                                            this.calc();
                                            setInterval(() => this.calc(), 1000);
                                        },
                                        calc() {
                                            const now = new Date().getTime();
                                            const diff = this.isPast ? (now - this.target) : (this.target - now);
                                            const safeDiff = Math.max(0, diff);
                                            this.days = Math.floor(safeDiff / (1000 * 60 * 60 * 24));
                                            this.hours = Math.floor((safeDiff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                            this.minutes = Math.floor((safeDiff % (1000 * 60 * 60)) / (1000 * 60));
                                            this.seconds = Math.floor((safeDiff % (1000 * 60)) / 1000);
                                        }
                                     }">
                                    <div class="grid grid-cols-4 gap-1.5 py-1">
                                        <div class="bg-white/80 dark:bg-gray-800/80 rounded-xl p-2 shadow-2xs">
                                            <span class="text-xl font-black text-rose-600 dark:text-rose-400" x-text="days">{{ $daysRemaining }}</span>
                                            <p class="text-3xs uppercase font-semibold text-gray-400">Hari</p>
                                        </div>
                                        <div class="bg-white/80 dark:bg-gray-800/80 rounded-xl p-2 shadow-2xs">
                                            <span class="text-xl font-black text-rose-600 dark:text-rose-400" x-text="hours">0</span>
                                            <p class="text-3xs uppercase font-semibold text-gray-400">Jam</p>
                                        </div>
                                        <div class="bg-white/80 dark:bg-gray-800/80 rounded-xl p-2 shadow-2xs">
                                            <span class="text-xl font-black text-rose-600 dark:text-rose-400" x-text="minutes">0</span>
                                            <p class="text-3xs uppercase font-semibold text-gray-400">Mnt</p>
                                        </div>
                                        <div class="bg-white/80 dark:bg-gray-800/80 rounded-xl p-2 shadow-2xs">
                                            <span class="text-xl font-black text-pink-500 animate-pulse" x-text="seconds">0</span>
                                            <p class="text-3xs uppercase font-semibold text-gray-400">Dtk</p>
                                        </div>
                                    </div>
                                    <div class="text-3xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mt-2">
                                        {{ $isPast ? 'Hari Berlalu Sejak Momen Indah' : 'Menuju Hari-H Bahagia' }}
                                    </div>
                                </div>
                            </div>

                            <div class="pt-3 border-t border-gray-100 dark:border-gray-700/60 flex items-center justify-between text-[11px] text-gray-400">
                                <span>Dibuat oleh {{ $c->creator->name }}</span>
                                <span>{{ $c->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Tambah Countdown --}}
    <div id="create-countdown-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('create-countdown-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span>⏳</span> Buat Countdown Baru
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Hitung mundur menuju hari berharga yang dinantikan bersama.
                </p>
            </div>

            <form action="{{ route('countdowns.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Momen / Acara</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Misal: Liburan Honeymoon ke Bali">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target Tanggal & Jam</label>
                        <input type="datetime-local" name="target_date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Emoji Ikon</label>
                        <select name="icon_emoji" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                            <option value="❤️">❤️ Cinta</option>
                            <option value="✈️">✈️ Liburan</option>
                            <option value="💍">💍 Anniversary</option>
                            <option value="🎂">🎂 Ulang Tahun</option>
                            <option value="🏖️">🏖️ Pantai</option>
                            <option value="🎬">🎬 Nonton Bareng</option>
                            <option value="🏠">🏠 Rumah Baru</option>
                        </select>
                    </div>
                </div>

                <div class="pt-2">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="is_pinned" value="1" checked class="rounded border-gray-300 text-rose-500 focus:ring-rose-500">
                        <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">
                            Sematkan di Beranda Utama (Dashboard)
                        </span>
                    </label>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('create-countdown-modal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                        Simpan Countdown
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
