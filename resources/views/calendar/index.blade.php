<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="text-rose-500">🗓️</span> Kalender & Agenda Bersama
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Jadwal kencan, acara keluarga, dan pengingat momen berharga kalian.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('create-event-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Agenda
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

            {{-- Category Filter Pills --}}
            <div class="flex items-center gap-2 overflow-x-auto pb-2 scrollbar-none">
                <a href="{{ route('events.index') }}" class="px-4 py-2 text-xs font-semibold rounded-xl shrink-0 transition {{ !$selectedCategory ? 'bg-rose-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-100 dark:border-gray-700' }}">
                    Semua Kategori
                </a>
                @foreach ($categories as $catKey => $cat)
                    <a href="{{ route('events.index', ['category' => $catKey]) }}" class="px-4 py-2 text-xs font-semibold rounded-xl shrink-0 transition flex items-center gap-1.5 {{ $selectedCategory === $catKey ? 'bg-rose-500 text-white shadow-sm' : 'bg-white dark:bg-gray-800 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 border border-gray-100 dark:border-gray-700' }}">
                        <span>{{ $cat['emoji'] }}</span>
                        <span>{{ $cat['label'] }}</span>
                    </a>
                @endforeach
            </div>

            {{-- Upcoming Agenda List & Calendar Container --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Left 2 cols: Main Events List / Calendar --}}
                <div class="lg:col-span-2 space-y-4">
                    <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                    <span>📅</span> Semua Agenda Terjadwal
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Total {{ $allEvents->count() }} agenda dalam ruang kalian</p>
                            </div>
                        </div>

                        @if ($allEvents->isEmpty())
                            <div class="text-center py-16 px-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                                <div class="text-4xl mb-2">🗓️</div>
                                <h4 class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum ada agenda yang dijadwalkan</h4>
                                <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                                    Mulai rencanakan kencan, liburan, atau acara keluarga bersama pasanganmu sekarang!
                                </p>
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($allEvents as $e)
                                    <div class="p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 flex items-start justify-between gap-4 hover:border-rose-200 dark:hover:border-rose-900 transition">
                                        <div class="flex items-start gap-3.5">
                                            <div class="w-11 h-11 rounded-2xl shrink-0 flex items-center justify-center text-xl shadow-sm text-white" style="background-color: {{ $e->color }};">
                                                {{ $e->category_emoji }}
                                            </div>
                                            <div class="space-y-1">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    <h4 class="font-bold text-gray-900 dark:text-gray-100 text-base">
                                                        {{ $e->title }}
                                                    </h4>
                                                    <span class="px-2.5 py-0.5 text-[10px] font-semibold rounded-full" style="background-color: {{ $e->color }}15; color: {{ $e->color }};">
                                                        {{ $e->category_label }}
                                                    </span>
                                                    @if ($e->is_family_event)
                                                        <span class="px-2 py-0.5 text-[10px] bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-300 font-medium rounded-full">
                                                            Keluarga
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-0.5 text-[10px] bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-300 font-medium rounded-full">
                                                            Privat Pasangan
                                                        </span>
                                                    @endif
                                                </div>

                                                @if ($e->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 leading-relaxed">
                                                        {{ $e->description }}
                                                    </p>
                                                @endif

                                                <div class="flex items-center gap-3 text-xs text-gray-400 pt-1">
                                                    <span>⏰ {{ $e->start_at->format('d M Y, H:i') }}</span>
                                                    @if ($e->end_at)
                                                        <span>s/d {{ $e->end_at->format('d M Y, H:i') }}</span>
                                                    @endif
                                                    <span>• Dibuat oleh {{ $e->creator->name }}</span>
                                                </div>
                                            </div>
                                        </div>

                                        @can('delete', $e)
                                            <form action="{{ route('events.destroy', $e) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus agenda ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 text-gray-400 hover:text-red-500 rounded-lg transition" title="Hapus Agenda">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right 1 col: Upcoming Highlights --}}
                <div class="space-y-6">
                    <div class="bg-gradient-to-br from-rose-500/10 to-pink-500/10 dark:from-rose-950/30 dark:to-pink-950/30 rounded-3xl p-6 border border-rose-100 dark:border-rose-900/40">
                        <h3 class="font-bold text-gray-900 dark:text-gray-100 text-base flex items-center gap-2 mb-4">
                            <span>✨</span> Agenda Mendatang
                        </h3>

                        @if ($upcomingEvents->isEmpty())
                            <p class="text-xs text-gray-500 dark:text-gray-400">Tidak ada agenda dalam waktu dekat.</p>
                        @else
                            <div class="space-y-3">
                                @foreach ($upcomingEvents as $up)
                                    <div class="bg-white dark:bg-gray-800 rounded-2xl p-4 shadow-sm border border-gray-100 dark:border-gray-700">
                                        <div class="flex items-center justify-between mb-1">
                                            <span class="text-xs font-semibold text-rose-500">
                                                {{ $up->start_at->diffForHumans() }}
                                            </span>
                                            <span class="text-xs">{{ $up->category_emoji }}</span>
                                        </div>
                                        <h4 class="font-bold text-gray-800 dark:text-gray-200 text-sm">
                                            {{ $up->title }}
                                        </h4>
                                        <p class="text-[11px] text-gray-400 mt-0.5">
                                            {{ $up->start_at->format('l, d F Y - H:i') }}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>

        </div>
    </div>

    {{-- Modal Tambah Agenda Baru --}}
    <div id="create-event-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('create-event-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span>🗓️</span> Tambah Agenda Baru
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Atur jadwal kegiatan, kencan romantis, atau acara keluarga bersama.
                </p>
            </div>

            <form action="{{ route('events.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Agenda</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Misal: Date Night & Candle Light Dinner">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori Agenda</label>
                        <select name="category" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                            @foreach ($categories as $catKey => $cat)
                                <option value="{{ $catKey }}">{{ $cat['emoji'] }} {{ $cat['label'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Warna Penanda</label>
                        <input type="color" name="color" value="#f43f5e" class="w-full h-10 p-1 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 cursor-pointer">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Mulai Pada</label>
                        <input type="datetime-local" name="start_at" required value="{{ now()->addHour()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Selesai Pada (Opsional)</label>
                        <input type="datetime-local" name="end_at" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Deskripsi & Catatan (Opsional)</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Detail lokasi, dress code, atau catatan penting..."></textarea>
                </div>

                @if ($isPartner)
                    <div class="pt-2">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_family_event" value="1" checked class="rounded border-gray-300 text-rose-500 focus:ring-rose-500">
                            <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">
                                Bagikan ke Kalender Keluarga (Anak dapat melihat agenda ini)
                            </span>
                        </label>
                    </div>
                @endif

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('create-event-modal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                        Simpan Agenda
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
