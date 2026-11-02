<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('savings.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-pink-600 dark:text-pink-400 hover:underline">
                &larr; Kembali ke Daftar Tabungan
            </a>
            @can('delete', $goal)
                <form action="{{ route('savings.goals.destroy', $goal) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus target tabungan ini beserta seluruh riwayat setorannya?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 font-semibold">
                        Hapus Target Tabungan
                    </button>
                </form>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="text-xl">✨</span>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @php
                $pct = $goal->progress_percentage;
                $colorGradient = \App\Models\SavingsGoal::COLORS[$goal->color] ?? 'from-pink-500 to-rose-500';
                $estMonths = $goal->estimated_months_left;
            @endphp

            <!-- Goal Big Overview Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm space-y-6">
                <div class="flex items-start justify-between flex-wrap gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br {{ $colorGradient }} text-white flex items-center justify-center text-3xl shadow-sm">
                            {{ $goal->icon }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white">
                                    {{ $goal->name }}
                                </h1>
                                @if ($goal->status === 'achieved')
                                    <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                        🎉 Tercapai
                                    </span>
                                @endif
                            </div>
                            @if ($goal->target_date)
                                <p class="text-xs text-gray-400 mt-1">
                                    🗓️ Target Selesai: {{ \Carbon\Carbon::parse($goal->target_date)->translatedFormat('l, d F Y') }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Projection Estimate Badge -->
                    @if ($goal->status === 'achieved')
                        <div class="px-4 py-2 rounded-2xl bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 text-xs font-semibold">
                            🏆 Target 100% Tercapai pada {{ $goal->achieved_at?->translatedFormat('d M Y') }}
                        </div>
                    @elseif ($estMonths !== null)
                        <div class="px-4 py-2 rounded-2xl bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 text-blue-700 dark:text-blue-300 text-xs font-semibold">
                            ⏱️ Estimasi Waktu: ~{{ $estMonths }} bulan lagi (berdasarkan tren setoran bulanan)
                        </div>
                    @endif
                </div>

                <!-- Financial Stats -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-4 border-t border-gray-100 dark:border-gray-750">
                    <div class="bg-gray-50 dark:bg-gray-750/50 rounded-2xl p-4">
                        <span class="text-3xs font-semibold text-gray-400 uppercase tracking-wider">Terkumpul Saat Ini</span>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $goal->formatted_current_amount }}
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-750/50 rounded-2xl p-4">
                        <span class="text-3xs font-semibold text-gray-400 uppercase tracking-wider">Target Dana</span>
                        <div class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $goal->formatted_target_amount }}
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-750/50 rounded-2xl p-4">
                        <span class="text-3xs font-semibold text-gray-400 uppercase tracking-wider">Sisa Kekurangan</span>
                        <div class="text-xl sm:text-2xl font-bold text-rose-500 mt-1">
                            {{ $goal->formatted_remaining_amount }}
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div>
                    <div class="flex items-center justify-between text-xs font-semibold text-gray-600 dark:text-gray-300 mb-1.5">
                        <span>Pencapaian Target</span>
                        <span class="text-pink-600 dark:text-pink-400 font-bold text-sm">{{ $pct }}%</span>
                    </div>
                    <div class="w-full bg-gray-100 dark:bg-gray-700 h-3 rounded-full overflow-hidden">
                        <div 
                            class="h-3 rounded-full bg-gradient-to-r {{ $colorGradient }} transition-all duration-500" 
                            style="width: {{ $pct }}%"
                        ></div>
                    </div>
                </div>

                @if ($goal->description)
                    <p class="text-xs text-gray-600 dark:text-gray-300 leading-relaxed border-t border-gray-100 dark:border-gray-750 pt-3">
                        {{ $goal->description }}
                    </p>
                @endif
            </div>

            <!-- Form Catat Setoran Baru -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">💵</span>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Catat Setoran Baru</h3>
                </div>

                <form action="{{ route('savings.transactions.store', $goal) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nominal Setoran (Rupiah) *</label>
                        <input 
                            type="number" 
                            name="amount" 
                            required 
                            min="1000" 
                            placeholder="Contoh: 1500000" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Setoran *</label>
                        <input 
                            type="date" 
                            name="transaction_date" 
                            required 
                            value="{{ date('Y-m-d') }}"
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan (Opsional)</label>
                        <input 
                            type="text" 
                            name="notes" 
                            placeholder="Contoh: Gaji bulanan, Bonus proyek" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div class="sm:col-span-3 flex justify-end pt-2">
                        <button 
                            type="submit" 
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 hover:from-pink-700 hover:to-rose-700 text-white rounded-xl text-xs font-semibold shadow-md transition"
                        >
                            <span>💰</span> Simpan Setoran
                        </button>
                    </div>
                </form>
            </div>

            <!-- Ledger Riwayat Setoran -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                        <span>📜</span> Riwayat Setoran Tabungan
                    </h3>
                    <span class="text-xs text-gray-400">Total {{ $transactions->total() }} transaksi</span>
                </div>

                @if ($transactions->count() === 0)
                    <div class="py-8 text-center text-xs text-gray-400">
                        Belum ada setoran dana yang dicatat untuk target ini.
                    </div>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="border-b border-gray-100 dark:border-gray-750 text-3xs font-semibold text-gray-400 uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-2">Tanggal</th>
                                    <th class="py-3 px-2">Dicatat Oleh</th>
                                    <th class="py-3 px-2">Nominal</th>
                                    <th class="py-3 px-2">Catatan</th>
                                    <th class="py-3 px-2 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 dark:divide-gray-750">
                                @foreach ($transactions as $trx)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750/30 transition">
                                        <td class="py-3 px-2 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ \Carbon\Carbon::parse($trx->transaction_date)->translatedFormat('d M Y') }}
                                        </td>
                                        <td class="py-3 px-2 text-gray-600 dark:text-gray-300 whitespace-nowrap">
                                            {{ $trx->user->name }}
                                        </td>
                                        <td class="py-3 px-2 font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                                            +{{ $trx->formatted_amount }}
                                        </td>
                                        <td class="py-3 px-2 text-gray-500 dark:text-gray-400">
                                            {{ $trx->notes ?? '-' }}
                                        </td>
                                        <td class="py-3 px-2 text-right">
                                            <form action="{{ route('savings.transactions.destroy', $trx) }}" method="POST" onsubmit="return confirm('Hapus catatan setoran ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-3xs text-red-500 hover:text-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-3">
                        {{ $transactions->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
