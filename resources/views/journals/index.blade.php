<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="text-rose-500">📖</span> Curhatan Hati & Jurnal
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Ruang menulis rasa, baik untuk dibagikan bersama pasangan maupun disimpan privat untuk diri sendiri.
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('create-journal-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Tulis Curhatan
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

            {{-- Tab Filter --}}
            <div class="flex items-center gap-2 border-b border-gray-200 dark:border-gray-700 pb-3">
                <a href="{{ route('journals.index', ['tab' => 'all']) }}" class="px-4 py-2 text-xs font-semibold rounded-xl transition {{ $currentTab === 'all' ? 'bg-rose-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    Semua Jurnal
                </a>
                <a href="{{ route('journals.index', ['tab' => 'shared']) }}" class="px-4 py-2 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 {{ $currentTab === 'shared' ? 'bg-rose-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <span>💌</span> Curhatan Bersama
                </a>
                <a href="{{ route('journals.index', ['tab' => 'private']) }}" class="px-4 py-2 text-xs font-semibold rounded-xl transition flex items-center gap-1.5 {{ $currentTab === 'private' ? 'bg-rose-500 text-white shadow-sm' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                    <span>🔒</span> Ruang Rahasia Saya
                </a>
            </div>

            {{-- List of Journals --}}
            {{-- List of Journals --}}
            @if ($journals->isEmpty())
                <div class="text-center py-16 px-4 bg-white/80 dark:bg-gray-800/80 rounded-3xl border-2 border-dashed border-rose-200 dark:border-gray-700 shadow-2xs">
                    <div class="text-5xl mb-3 animate-float-slow">💌</div>
                    <h3 class="font-serif text-lg font-bold text-gray-800 dark:text-gray-200">Belum Ada Lembaran Curhatan</h3>
                    <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto leading-relaxed">
                        Bagikan isi hatimu hari ini atau tulis cerita manis untuk kenangan berharga kalian berdua.
                    </p>
                    <button type="button" onclick="document.getElementById('create-journal-modal').classList.remove('hidden')" class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl text-xs font-semibold shadow-sm hover:scale-102 transition">
                        <span>✍️</span> Tulis Curhatan Pertama
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($journals as $j)
                        <div class="bg-white/90 dark:bg-gray-800/90 rounded-3xl p-6 shadow-xs border border-rose-100/90 hover:border-pink-300 dark:border-gray-700/80 dark:hover:border-pink-800 flex flex-col justify-between hover:shadow-md hover:-translate-y-0.5 transition duration-200 group">
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    @if ($j->isPrivate())
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-3xs font-semibold bg-violet-50 dark:bg-violet-950/60 text-violet-700 dark:text-violet-300 rounded-full border border-violet-200 dark:border-violet-800">
                                            🔒 Buku Rahasia Pribadi
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-3xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 rounded-full border border-rose-200 dark:border-rose-800">
                                            💌 Surat Bersama
                                        </span>
                                    @endif

                                    @if ($j->mood_tag)
                                        <span class="text-3xs text-pink-600 dark:text-pink-300 bg-pink-50 dark:bg-pink-950/50 px-2.5 py-0.5 rounded-full border border-pink-200 dark:border-pink-900 font-medium">
                                            {{ $j->mood_tag }}
                                        </span>
                                    @endif
                                </div>

                                <a href="{{ route('journals.show', $j) }}" class="block">
                                    <h3 class="font-serif text-lg font-bold text-gray-900 dark:text-gray-100 group-hover:text-rose-600 dark:group-hover:text-rose-400 transition line-clamp-1">
                                        {{ $j->title }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-3 mt-1.5 leading-relaxed font-sans">
                                        {{ $j->content }}
                                    </p>
                                </a>
                            </div>

                            <div class="pt-4 mt-4 border-t border-rose-50 dark:border-gray-750 flex items-center justify-between text-3xs text-gray-400">
                                <div class="flex items-center gap-2">
                                    <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-pink-500 to-rose-400 text-white flex items-center justify-center font-bold text-3xs shadow-2xs">
                                        {{ strtoupper(substr($j->user->name, 0, 1)) }}
                                    </div>
                                    <span class="font-medium text-gray-700 dark:text-gray-300">{{ $j->user->name }}</span>
                                </div>
                                <span>🗓️ {{ $j->entry_date->translatedFormat('d M Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $journals->links() }}
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Tulis Curhatan Baru --}}
    <div id="create-journal-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('create-journal-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span>✍️</span> Tulis Curhatan Hati Baru
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Pilih visibilitas apakah catatan ini ingin kamu bagi bersama pasangan atau disimpan rahasia.
                </p>
            </div>

            <form action="{{ route('journals.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Curhatan</label>
                    <input type="text" name="title" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Apa yang sedang kamu rasakan hari ini?">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Visibilitas Privasi</label>
                        <select name="visibility" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                            <option value="shared">💌 Bersama Pasangan (Shared)</option>
                            <option value="private">🔒 Rahasia Pribadi (Private)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tag Emosi / Mood (Opsional)</label>
                        <input type="text" name="mood_tag" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Misal: Rindu, Bahagia, Reflektif">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Entri</label>
                    <input type="date" name="entry_date" value="{{ date('Y-m-d') }}" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Isi Curhatan</label>
                    <textarea name="content" rows="6" required class="w-full px-4 py-3 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Tuangkan isi pikiranmu dengan bebas dan jujur..."></textarea>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('create-journal-modal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                        Simpan Jurnal
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
