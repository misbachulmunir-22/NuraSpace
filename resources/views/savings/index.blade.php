<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span>💰</span> {{ __('Tabungan Bersama Pasangan') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rencanakan dan wujudkan masa depan finansial keluarga berdua.</p>
            </div>
            <button 
                type="button" 
                @click="$dispatch('open-add-goal-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-md transition hover:scale-102"
            >
                <span>➕</span> Buat Target Tabungan
            </button>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{ addModalOpen: false }" @open-add-goal-modal.window="addModalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="text-xl">✨</span>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Summary KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Target Aktif</span>
                        <span class="text-lg">🎯</span>
                    </div>
                    <div class="mt-2 text-lg sm:text-xl font-bold text-gray-900 dark:text-white truncate">
                        Rp {{ number_format($totalTarget, 0, ',', '.') }}
                    </div>
                    <p class="text-3xs text-gray-400 mt-1">Cita-cita finansial bersama</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Total Terkumpul</span>
                        <span class="text-lg">💰</span>
                    </div>
                    <div class="mt-2 text-lg sm:text-xl font-bold text-emerald-600 dark:text-emerald-400 truncate">
                        Rp {{ number_format($totalCollected, 0, ',', '.') }}
                    </div>
                    <p class="text-3xs text-gray-400 mt-1">Dana yang sudah tersimpan</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Progress Rata-rata</span>
                        <span class="text-lg">📊</span>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $overallProgress }}%</div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-1.5 rounded-full mt-2 overflow-hidden">
                        <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $overallProgress }}%"></div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-purple-600 dark:text-purple-400">Target Tercapai</span>
                        <span class="text-lg">🎉</span>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $achievedCount }} Target</div>
                    <p class="text-3xs text-gray-400 mt-1">Sudah terealisasi 100%</p>
                </div>
            </div>

            <!-- Savings Goals Grid -->
            @if ($goals->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center text-3xl">
                        💰
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Target Tabungan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Mulai rencanakan tabungan rumah, liburan, atau dana darurat bersama pasanganmu.
                    </p>
                    <button 
                        @click="addModalOpen = true" 
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"
                    >
                        <span>➕</span> Buat Target Tabungan Pertama
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($goals as $goal)
                        @php
                            $pct = $goal->progress_percentage;
                            $colorGradient = \App\Models\SavingsGoal::COLORS[$goal->color] ?? 'from-pink-500 to-rose-500';
                            $estMonths = $goal->estimated_months_left;
                        @endphp

                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-2xs hover:shadow-md transition flex flex-col justify-between {{ $goal->status === 'achieved' ? 'ring-2 ring-emerald-400/50' : '' }}">
                            <div class="p-6 space-y-4">
                                <!-- Top Bar -->
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br {{ $colorGradient }} text-white flex items-center justify-center text-2xl shadow-sm">
                                            {{ $goal->icon }}
                                        </div>
                                        <div>
                                            <h3 class="font-bold text-gray-900 dark:text-white text-base leading-snug">
                                                {{ $goal->name }}
                                            </h3>
                                            @if ($goal->target_date)
                                                <p class="text-3xs text-gray-400">
                                                    Target: {{ \Carbon\Carbon::parse($goal->target_date)->translatedFormat('d M Y') }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    @if ($goal->status === 'achieved')
                                        <span class="px-2.5 py-1 rounded-full text-3xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 shrink-0">
                                            🎉 Tercapai
                                        </span>
                                    @endif
                                </div>

                                <!-- Amounts -->
                                <div>
                                    <div class="flex items-baseline justify-between">
                                        <span class="text-xl sm:text-2xl font-extrabold text-gray-900 dark:text-white">
                                            {{ $goal->formatted_current_amount }}
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            dari {{ $goal->formatted_target_amount }}
                                        </span>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="mt-2.5">
                                        <div class="flex items-center justify-between text-3xs font-semibold text-gray-500 mb-1">
                                            <span>Progress</span>
                                            <span class="text-pink-600 dark:text-pink-400 font-bold">{{ $pct }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-100 dark:bg-gray-700 h-2.5 rounded-full overflow-hidden">
                                            <div 
                                                class="h-2.5 rounded-full bg-gradient-to-r {{ $colorGradient }} transition-all duration-500" 
                                                style="width: {{ $pct }}%"
                                            ></div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status / Estimate Info -->
                                <div class="bg-gray-50 dark:bg-gray-750/50 rounded-2xl p-3 text-xs text-gray-600 dark:text-gray-300 space-y-1">
                                    <div class="flex items-center justify-between text-3xs">
                                        <span>Kekurangan:</span>
                                        <span class="font-bold text-rose-500">{{ $goal->formatted_remaining_amount }}</span>
                                    </div>

                                    @if ($goal->status === 'achieved')
                                        <div class="text-3xs text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1 pt-1">
                                            <span>✨</span> Target penuh telah tercapai pada {{ $goal->achieved_at?->translatedFormat('d M Y') }}!
                                        </div>
                                    @elseif ($estMonths !== null)
                                        <div class="text-3xs text-blue-600 dark:text-blue-400 font-semibold flex items-center gap-1 pt-1">
                                            <span>⏱️</span> Estimasi tercapai: ~{{ $estMonths }} bulan lagi (berdasarkan rata-rata setoran)
                                        </div>
                                    @else
                                        <div class="text-3xs text-gray-400 flex items-center gap-1 pt-1">
                                            <span>💡</span> Tambahkan setoran pertama untuk memproyeksikan estimasi waktu.
                                        </div>
                                    @endif
                                </div>

                                @if ($goal->description)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 line-clamp-2">
                                        {{ $goal->description }}
                                    </p>
                                @endif
                            </div>

                            <!-- Footer Actions -->
                            <div class="px-6 pb-6 pt-2 border-t border-gray-50 dark:border-gray-750 flex items-center justify-between text-xs">
                                <span class="text-3xs text-gray-400">
                                    {{ $goal->transactions->count() }} setoran tercatat
                                </span>
                                <a 
                                    href="{{ route('savings.show', $goal) }}" 
                                    class="inline-flex items-center gap-1 text-xs font-semibold text-pink-600 dark:text-pink-400 hover:underline"
                                >
                                    Catat Setoran & Riwayat &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

        </div>

        <!-- Modal Tambah Target Tabungan -->
        <div 
            x-show="addModalOpen" 
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-50 overflow-y-auto"
        >
            <div 
                @click.away="addModalOpen = false" 
                class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-pink-100 dark:border-gray-700 my-8"
            >
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">🎯</span>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Buat Target Tabungan Baru</h3>
                    </div>
                    <button @click="addModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                </div>

                <form action="{{ route('savings.goals.store') }}" method="POST" class="space-y-4 mt-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Target Tabungan *</label>
                        <input 
                            type="text" 
                            name="name" 
                            required 
                            placeholder="Contoh: DP Rumah Impian, Dana Liburan Jepang, Tabungan Melahirkan" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target Dana (Rupiah) *</label>
                            <input 
                                type="number" 
                                name="target_amount" 
                                required 
                                min="1000" 
                                placeholder="Contoh: 50000000" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target Tanggal Selesai (Opsional)</label>
                            <input 
                                type="date" 
                                name="target_date" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Ikon Pilihan</label>
                            <select 
                                name="icon" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                                @foreach (\App\Models\SavingsGoal::ICONS as $ic)
                                    <option value="{{ $ic }}">{{ $ic }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tema Warna Kartu</label>
                            <select 
                                name="color" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                                <option value="pink">Pink & Rose 💖</option>
                                <option value="purple">Purple & Indigo 💜</option>
                                <option value="blue">Blue & Cyan 💙</option>
                                <option value="emerald">Emerald & Teal 💚</option>
                                <option value="amber">Amber & Orange 🧡</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan / Rencana Pendanaan</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Tuliskan komitmen setoran bulanan atau tujuan khusus target ini..." 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        ></textarea>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button 
                            type="button" 
                            @click="addModalOpen = false" 
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl text-xs font-semibold shadow-md hover:from-pink-700 hover:to-rose-700 transition"
                        >
                            Simpan Target Tabungan 🎯
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
