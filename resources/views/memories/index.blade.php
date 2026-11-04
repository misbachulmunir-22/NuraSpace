<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span>📸</span> {{ __('Galeri & Memory Lane') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Jejak perjalanan cinta dan momen berharga keluarga.</p>
            </div>
            <button 
                type="button" 
                @click="$dispatch('open-add-memory-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-md transition hover:scale-102"
            >
                <span>➕</span> Abadikan Kenangan
            </button>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{ modalOpen: {{ $errors->any() ? 'true' : 'false' }} }" @open-add-memory-modal.window="modalOpen = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-800 dark:text-emerald-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="text-xl">✨</span>
                    <span class="text-sm font-medium">{{ session('status') }}</span>
                </div>
            @endif

            @if ($errors->any() && !old('title'))
                <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 px-4 py-3 rounded-2xl flex items-center gap-3 shadow-xs">
                    <span class="text-xl">⚠️</span>
                    <div class="text-sm">
                        <span class="font-medium">Terjadi kesalahan validasi.</span>
                        <ul class="list-disc list-inside mt-1 text-xs text-rose-700 dark:text-rose-300">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Filter & Stats Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 sm:p-5 border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <!-- Filter Pills -->
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                    <a 
                        href="{{ route('memories.index') }}" 
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition {{ !$isMilestoneFilter && empty($selectedYear) ? 'bg-pink-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                    >
                        Semua Momen ({{ $memories->total() }})
                    </a>

                    <a 
                        href="{{ route('memories.index', ['milestone' => 1]) }}" 
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition flex items-center gap-1.5 {{ $isMilestoneFilter ? 'bg-amber-500 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                    >
                        <span>🏆</span> Tonggak Sejarah ({{ $totalMilestones }})
                    </a>

                    @foreach ($availableYears as $year)
                        <a 
                            href="{{ route('memories.index', ['year' => $year]) }}" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition {{ $selectedYear == $year ? 'bg-purple-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                        >
                            {{ $year }}
                        </a>
                    @endforeach
                </div>

                <div class="text-xs text-gray-400 shrink-0">
                    Tersimpan aman di private storage 🔐
                </div>
            </div>

            <!-- Empty State -->
            @if ($memories->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center text-3xl">
                        📸
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Kenangan yang Diabadikan</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Simpan foto dan momen terindah kebersamaanmu bersama pasangan dan anak-anak di sini.
                    </p>
                    <button 
                        @click="modalOpen = true" 
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"
                    >
                        <span>➕</span> Tambah Kenangan Pertama
                    </button>
                </div>
            @else
                <!-- Timeline Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($memories as $memory)
                        @php
                            $primary = $memory->primaryMedia();
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-xs hover:shadow-md hover:border-pink-200 dark:hover:border-pink-800 transition flex flex-col justify-between group">
                            <div>
                                <!-- Media Thumbnail -->
                                <div class="relative aspect-4/3 bg-gray-100 dark:bg-gray-900 overflow-hidden">
                                    @if ($primary)
                                        @if ($primary->isImage())
                                            <img 
                                                src="{{ $primary->signed_url }}" 
                                                alt="{{ $memory->title }}" 
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-500"
                                                loading="lazy"
                                            >
                                        @else
                                            <div class="w-full h-full flex items-center justify-center bg-gray-900 text-white">
                                                <div class="text-center">
                                                    <span class="text-4xl">🎥</span>
                                                    <p class="text-xs text-gray-300 mt-1">Video Memori</p>
                                                </div>
                                            </div>
                                        @endif
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-pink-50 to-purple-50 dark:from-gray-900 dark:to-gray-800 text-pink-300 dark:text-gray-600">
                                            <span class="text-5xl">💖</span>
                                        </div>
                                    @endif

                                    <!-- Media Count Badge -->
                                    @if ($memory->media->count() > 1)
                                        <span class="absolute bottom-3 right-3 px-2 py-1 rounded-full bg-black/60 backdrop-blur-xs text-white text-3xs font-semibold flex items-center gap-1">
                                            <span>📷</span> +{{ $memory->media->count() - 1 }}
                                        </span>
                                    @endif

                                    <!-- Milestone Badge -->
                                    @if ($memory->is_milestone)
                                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full bg-amber-400 text-amber-950 text-3xs font-bold shadow-md flex items-center gap-1">
                                            <span>🏆</span> Milestone
                                        </span>
                                    @endif
                                </div>

                                <!-- Card Content -->
                                <div class="p-5">
                                    <div class="flex items-center justify-between text-xs text-gray-400 dark:text-gray-500 mb-1.5">
                                        <span>🗓️ {{ \Carbon\Carbon::parse($memory->memory_date)->translatedFormat('d F Y') }}</span>
                                        @if ($memory->location)
                                            <span class="truncate max-w-[120px]">📍 {{ $memory->location }}</span>
                                        @endif
                                    </div>

                                    <h3 class="text-base font-bold text-gray-900 dark:text-white line-clamp-1 group-hover:text-pink-600 dark:group-hover:text-pink-400 transition">
                                        {{ $memory->title }}
                                    </h3>

                                    @if ($memory->description)
                                        <p class="text-xs text-gray-600 dark:text-gray-300 mt-1.5 line-clamp-2 leading-relaxed">
                                            {{ $memory->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>

                            <!-- Card Footer -->
                            <div class="px-5 pb-5 pt-2 border-t border-gray-50 dark:border-gray-750 flex items-center justify-between text-xs">
                                <span class="text-3xs text-gray-400">Oleh: {{ $memory->user->name }}</span>
                                <a 
                                    href="{{ route('memories.show', $memory) }}" 
                                    class="text-xs font-semibold text-pink-600 dark:text-pink-400 hover:underline flex items-center gap-1"
                                >
                                    Buka Cerita &rarr;
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $memories->links() }}
                </div>
            @endif

        </div>

        <!-- Modal Tambah Kenangan -->
        <div 
            x-show="modalOpen" 
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
                @click.away="modalOpen = false" 
                class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-pink-100 dark:border-gray-700 my-8"
            >
                <div class="flex items-center justify-between pb-4 border-b border-gray-100 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <span class="text-2xl">📸</span>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Abadikan Kenangan Baru</h3>
                    </div>
                    <button @click="modalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                </div>

                <form action="{{ route('memories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-5">
                    @csrf

                    @if ($errors->any())
                        <div class="bg-rose-50 dark:bg-rose-950/40 border border-rose-200 dark:border-rose-800 text-rose-800 dark:text-rose-200 p-3 rounded-xl text-xs space-y-1">
                            <div class="font-bold flex items-center gap-1.5">
                                <span>⚠️</span> Mohon periksa kembali:
                            </div>
                            <ul class="list-disc list-inside space-y-0.5 text-rose-700 dark:text-rose-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Momen / Kenangan *</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            value="{{ old('title') }}"
                            placeholder="Contoh: Kencan Pertama di Pantai, Kelahiran Buah Hati" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Momen *</label>
                            <input 
                                type="date" 
                                name="memory_date" 
                                required 
                                value="{{ old('memory_date', date('Y-m-d')) }}"
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Lokasi (Opsional)</label>
                            <input 
                                type="text" 
                                name="location" 
                                value="{{ old('location') }}"
                                placeholder="Contoh: Yogyakarta, Rumah Kita" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Foto / Video (Bisa pilih beberapa) *</label>
                        <input 
                            type="file" 
                            name="media[]" 
                            multiple 
                            accept="image/*,video/*"
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100 dark:file:bg-pink-900/40 dark:file:text-pink-300"
                        >
                        <p class="text-3xs text-gray-400 mt-1">Format gambar (JPG, PNG, WEBP) maks 10MB, video (MP4, MOV) maks 50MB.</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Cerita di Balik Kenangan Ini</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Tuliskan rasa bahagia, percakapan lucu, atau momen manis yang terjadi..." 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >{{ old('description') }}</textarea>
                    </div>

                    <div class="flex items-center gap-2 pt-1">
                        <input 
                            type="checkbox" 
                            name="is_milestone" 
                            id="is_milestone" 
                            value="1" 
                            {{ old('is_milestone') ? 'checked' : '' }}
                            class="rounded border-gray-300 text-amber-500 focus:ring-amber-400"
                        >
                        <label for="is_milestone" class="text-xs font-medium text-gray-700 dark:text-gray-300 cursor-pointer">
                            Tandai sebagai <strong>Milestone / Tonggak Sejarah Cinta</strong> 🏆
                        </label>
                    </div>

                    <div class="flex justify-end gap-2 pt-3 border-t border-gray-100 dark:border-gray-700">
                        <button 
                            type="button" 
                            @click="modalOpen = false" 
                            class="px-4 py-2 text-xs font-semibold text-gray-600 dark:text-gray-400"
                        >
                            Batal
                        </button>
                        <button 
                            type="submit" 
                            class="px-5 py-2.5 bg-gradient-to-r from-pink-600 to-rose-600 text-white rounded-xl text-xs font-semibold shadow-md hover:from-pink-700 hover:to-rose-700 transition"
                        >
                            Simpan Kenangan 💖
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
