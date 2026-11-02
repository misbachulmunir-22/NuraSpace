<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="text-rose-500">💌</span> Surat Masa Depan (Time-Capsule)
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Tulis surat rahasia berharga yang disegel dan baru dapat dibuka pada tanggal & jam istimewa di masa depan.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('create-capsule-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    Segel Surat Baru
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

            @if ($capsules->isEmpty())
                <div class="text-center py-16 px-4 bg-white dark:bg-gray-800 rounded-3xl border border-dashed border-gray-200 dark:border-gray-700">
                    <div class="text-5xl mb-3">📬</div>
                    <h3 class="text-base font-bold text-gray-800 dark:text-gray-200">Belum ada surat masa depan</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                        Tuliskan pesan cinta atau harapan masa depan yang dikunci hingga hari anniversary, ulang tahun, atau momen spesial berikutnya!
                    </p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($capsules as $c)
                        @php
                            $unlocked = $c->isUnlocked();
                        @endphp
                        <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl p-6 shadow-xs border border-rose-100/90 hover:border-pink-300 dark:border-gray-700/80 dark:hover:border-pink-800 flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-200 relative overflow-hidden group">
                            {{-- Top decorative seal --}}
                            <div class="flex items-start justify-between gap-2 mb-4">
                                <div>
                                    @if ($unlocked)
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-3xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 rounded-full border border-emerald-200 dark:border-emerald-800">
                                            🔓 Terbuka untuk Dibaca
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-3 py-1 text-3xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 rounded-full border border-amber-200 dark:border-amber-800">
                                            🔒 Tersegel Rapi
                                        </span>
                                    @endif
                                </div>
                                <div class="w-10 h-10 rounded-full {{ $unlocked ? 'bg-pink-100 dark:bg-pink-900/40 text-pink-600' : 'bg-amber-100 dark:bg-amber-900/40 text-amber-600' }} flex items-center justify-center text-lg shadow-inner">
                                    {{ $unlocked ? '💌' : '🔐' }}
                                </div>
                            </div>

                            <div class="space-y-2.5">
                                <h3 class="font-serif text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition line-clamp-1">
                                    {{ $c->title }}
                                </h3>

                                <div class="text-xs text-gray-500 dark:text-gray-400 space-y-1">
                                    <p class="flex items-center gap-1.5">
                                        <span>Dari:</span>
                                        <strong class="text-gray-800 dark:text-gray-200 font-semibold">{{ $c->sender->name }}</strong>
                                    </p>
                                    @if ($c->recipient)
                                        <p class="flex items-center gap-1.5">
                                            <span>Untuk:</span>
                                            <strong class="text-gray-800 dark:text-gray-200 font-semibold">{{ $c->recipient->name }}</strong>
                                        </p>
                                    @endif
                                    <p class="pt-1 text-pink-600 dark:text-pink-400 font-semibold flex items-center gap-1 text-3xs">
                                        <span>🗓️ Buka:</span>
                                        <span>{{ $c->unlock_at->translatedFormat('d M Y, H:i') }} WIB</span>
                                    </p>
                                </div>

                                @if (!$unlocked)
                                    <div class="p-3 bg-amber-50/70 dark:bg-amber-950/30 rounded-2xl border border-amber-200/60 dark:border-amber-800/40 text-3xs text-amber-800 dark:text-amber-300 flex items-center gap-2">
                                        <span class="animate-pulse">⏳</span>
                                        <span class="font-medium">Dibuka {{ $c->unlock_at->diffForHumans() }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="pt-4 mt-4 border-t border-rose-50 dark:border-gray-750 flex items-center justify-between text-xs">
                                <a href="{{ route('time-capsules.show', $c) }}" class="inline-flex items-center gap-1 font-semibold {{ $unlocked ? 'text-pink-600 hover:text-pink-700 dark:text-pink-400' : 'text-gray-500 hover:text-gray-700 dark:text-gray-400' }}">
                                    {{ $unlocked ? 'Buka & Baca Surat →' : 'Lihat Status Segel →' }}
                                </a>

                                @if ($c->sender_id === $currentUser->id && !$unlocked)
                                    <form action="{{ route('time-capsules.destroy', $c) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan dan menghapus surat ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-rose-400 hover:text-rose-600 text-3xs font-medium">
                                            Batalkan
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $capsules->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Tulis & Segel Surat Baru --}}
    <div id="create-capsule-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('create-capsule-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span>💌</span> Segel Surat Masa Depan
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Isi surat tidak akan dapat dibaca oleh siapapun (termasuk pasangan) sebelum tanggal dan waktu buka tiba.
                </p>
            </div>

            <form action="{{ route('time-capsules.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Surat</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Misal: Untuk Syahra di Hari Ulang Tahun ke-25">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Penerima Surat</label>
                        <select name="recipient_id" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                            @foreach ($partners as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} (Pasangan)</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Waktu Buka (Unlock At)</label>
                        <input type="datetime-local" name="unlock_at" required min="{{ now()->addMinute()->format('Y-m-d\TH:i') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Isi Pesan Surat</label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Tuliskan kata-kata yang ingin kamu sampaikan saat waktu itu tiba..."></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('create-capsule-modal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-gradient-to-r from-rose-500 to-pink-500 hover:from-rose-600 hover:to-pink-600 text-white shadow-sm transition">
                        Kunci & Segel Surat
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
