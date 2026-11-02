<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between flex-wrap gap-3">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-2">
                    <span>🌟</span> {{ __('Couple Wishlist (Daftar Impian)') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Rencana liburan, barang impian, dan target masa depan kita berdua.</p>
            </div>
            <button 
                type="button" 
                @click="$dispatch('open-add-wishlist-modal')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white rounded-xl text-xs sm:text-sm font-semibold shadow-md transition hover:scale-102"
            >
                <span>➕</span> Tambah Impian
            </button>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{ addModalOpen: false }" @open-add-wishlist-modal.window="addModalOpen = true">
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
                        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Total Impian</span>
                        <span class="text-lg">🌟</span>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-gray-900 dark:text-white">{{ $totalItems }}</div>
                    <p class="text-3xs text-gray-400 mt-1">Daftar cita-cita bersama</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-emerald-600 dark:text-emerald-400">Telah Tercapai</span>
                        <span class="text-lg">🎉</span>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ $achievedCount }}</div>
                    <p class="text-3xs text-gray-400 mt-1">Impian yang sudah terwujud</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-blue-600 dark:text-blue-400">Sedang Berjalan</span>
                        <span class="text-lg">🚀</span>
                    </div>
                    <div class="mt-2 text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $inProgressCount }}</div>
                    <p class="text-3xs text-gray-400 mt-1">Dalam proses diperjuangkan</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-3xl p-5 border border-gray-100 dark:border-gray-700 shadow-2xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-medium text-purple-600 dark:text-purple-400">Estimasi Total Biaya</span>
                        <span class="text-lg">💰</span>
                    </div>
                    <div class="mt-2 text-lg sm:text-xl font-bold text-purple-600 dark:text-purple-400 truncate">
                        Rp {{ number_format($totalEstimatedCost, 0, ',', '.') }}
                    </div>
                    <p class="text-3xs text-gray-400 mt-1">Perkiraan dana impian</p>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-4 sm:p-5 border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                <!-- Category Pills -->
                <div class="flex items-center gap-2 overflow-x-auto w-full md:w-auto pb-1 md:pb-0">
                    <a 
                        href="{{ route('wishlists.index', array_filter(['status' => $selectedStatus])) }}" 
                        class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition {{ empty($selectedCategory) ? 'bg-pink-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                    >
                        Semua Kategori
                    </a>

                    @foreach (\App\Models\Wishlist::CATEGORIES as $key => $cat)
                        <a 
                            href="{{ route('wishlists.index', array_filter(['category' => $key, 'status' => $selectedStatus])) }}" 
                            class="px-3.5 py-1.5 rounded-full text-xs font-semibold whitespace-nowrap transition flex items-center gap-1.5 {{ $selectedCategory === $key ? 'bg-pink-600 text-white shadow-xs' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200' }}"
                        >
                            <span>{{ $cat['icon'] }}</span> {{ $cat['label'] }}
                        </a>
                    @endforeach
                </div>

                <!-- Status Filter Dropdown -->
                <div class="flex items-center gap-2 text-xs w-full md:w-auto justify-end">
                    <span class="text-gray-400">Status:</span>
                    <select 
                        onchange="location = this.value;"
                        class="rounded-xl border-gray-200 dark:border-gray-700 dark:bg-gray-900 text-xs py-1.5 pl-3 pr-8 focus:border-pink-500 focus:ring-pink-500"
                    >
                        <option value="{{ route('wishlists.index', array_filter(['category' => $selectedCategory])) }}">Semua Status</option>
                        <option value="{{ route('wishlists.index', array_filter(['category' => $selectedCategory, 'status' => 'pending'])) }}" {{ $selectedStatus === 'pending' ? 'selected' : '' }}>Belum Dimulai</option>
                        <option value="{{ route('wishlists.index', array_filter(['category' => $selectedCategory, 'status' => 'in_progress'])) }}" {{ $selectedStatus === 'in_progress' ? 'selected' : '' }}>Sedang Berjalan</option>
                        <option value="{{ route('wishlists.index', array_filter(['category' => $selectedCategory, 'status' => 'achieved'])) }}" {{ $selectedStatus === 'achieved' ? 'selected' : '' }}>Tercapai 🎉</option>
                    </select>
                </div>
            </div>

            <!-- Wishlist Grid -->
            @if ($wishlists->count() === 0)
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-12 text-center border border-gray-100 dark:border-gray-700 shadow-sm">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-pink-100 dark:bg-pink-900/40 text-pink-600 dark:text-pink-300 flex items-center justify-center text-3xl">
                        🌟
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Belum Ada Impian di Daftar Ini</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 max-w-sm mx-auto">
                        Tuliskan rencana liburan impian, kencan romantis, atau barang yang ingin dibeli bersama pasangan!
                    </p>
                    <button 
                        @click="addModalOpen = true" 
                        class="mt-5 inline-flex items-center gap-2 px-5 py-2.5 bg-pink-600 hover:bg-pink-700 text-white rounded-xl text-sm font-semibold shadow-sm transition"
                    >
                        <span>➕</span> Tambah Impian Pertama
                    </button>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach ($wishlists as $item)
                        @php
                            $catMeta = $item->category_meta;
                        @endphp
                        <div class="bg-white dark:bg-gray-800 rounded-3xl border border-gray-100 dark:border-gray-700 overflow-hidden shadow-2xs hover:shadow-md transition flex flex-col justify-between group {{ $item->status === 'achieved' ? 'ring-2 ring-emerald-400/40' : '' }}">
                            <div>
                                <!-- Image Preview if uploaded -->
                                @if ($item->image_path)
                                    <div class="aspect-16/9 bg-gray-100 dark:bg-gray-900 overflow-hidden relative">
                                        <img 
                                            src="{{ $item->signed_image_url }}" 
                                            alt="{{ $item->title }}" 
                                            class="w-full h-full object-cover group-hover:scale-103 transition duration-500"
                                            loading="lazy"
                                        >
                                        <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-3xs font-bold bg-black/60 backdrop-blur-xs text-white flex items-center gap-1">
                                            <span>{{ $catMeta['icon'] }}</span> {{ $catMeta['label'] }}
                                        </span>
                                    </div>
                                @endif

                                <div class="p-5 space-y-3">
                                    @if (!$item->image_path)
                                        <div class="flex items-center justify-between">
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-3xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <span>{{ $catMeta['icon'] }}</span> {{ $catMeta['label'] }}
                                            </span>

                                            <!-- Status Badge -->
                                            @if ($item->status === 'achieved')
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300">
                                                    🎉 Tercapai
                                                </span>
                                            @elseif ($item->status === 'in_progress')
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-bold bg-blue-100 text-blue-800 dark:bg-blue-900/40 dark:text-blue-300">
                                                    🚀 Proses
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded-full text-3xs font-semibold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                                    Belum Dimulai
                                                </span>
                                            @endif
                                        </div>
                                    @endif

                                    <h3 class="text-base font-bold text-gray-900 dark:text-white leading-snug">
                                        {{ $item->title }}
                                    </h3>

                                    @if ($item->description)
                                        <p class="text-xs text-gray-600 dark:text-gray-300 line-clamp-3 leading-relaxed">
                                            {{ $item->description }}
                                        </p>
                                    @endif

                                    <!-- Price & Target Date -->
                                    <div class="pt-2 border-t border-gray-100 dark:border-gray-750 flex items-center justify-between text-xs">
                                        @if ($item->estimated_cost)
                                            <span class="font-bold text-pink-600 dark:text-pink-400">
                                                {{ $item->formatted_cost }}
                                            </span>
                                        @else
                                            <span class="text-3xs text-gray-400">Biaya: Fleksibel</span>
                                        @endif

                                        @if ($item->target_date)
                                            <span class="text-3xs text-gray-400">
                                                🎯 {{ \Carbon\Carbon::parse($item->target_date)->translatedFormat('d M Y') }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Footer Actions & Quick Status Changer -->
                            <div class="px-5 pb-5 pt-2 border-t border-gray-50 dark:border-gray-750 flex items-center justify-between text-xs">
                                <div>
                                    @if ($item->status === 'pending')
                                        <form action="{{ route('wishlists.status', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="in_progress">
                                            <button type="submit" class="text-xs font-semibold text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                                🚀 Mulai Wujudkan
                                            </button>
                                        </form>
                                    @elseif ($item->status === 'in_progress')
                                        <form action="{{ route('wishlists.status', $item) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="achieved">
                                            <button type="submit" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 flex items-center gap-1">
                                                <span>🎉</span> Tandai Tercapai
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-3xs text-emerald-600 dark:text-emerald-400 font-medium">
                                            ✓ Terwujud {{ $item->achieved_at?->translatedFormat('d M Y') }}
                                        </span>
                                    @endif
                                </div>

                                <form action="{{ route('wishlists.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus impian ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-3xs text-gray-400 hover:text-red-500">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="pt-4">
                    {{ $wishlists->links() }}
                </div>
            @endif

        </div>

        <!-- Modal Tambah Impian -->
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
                        <span class="text-2xl">🌟</span>
                        <h3 class="font-bold text-gray-900 dark:text-white text-base">Tambah Impian Baru</h3>
                    </div>
                    <button @click="addModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl leading-none">&times;</button>
                </div>

                <form action="{{ route('wishlists.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 mt-5">
                    @csrf

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Judul Impian *</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            placeholder="Contoh: Liburan ke Labuan Bajo, Beli Sofa Cozy, Dinner di Rooftop" 
                            class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                        >
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                            <select 
                                name="category" 
                                required 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                                @foreach (\App\Models\Wishlist::CATEGORIES as $key => $c)
                                    <option value="{{ $key }}">{{ $c['icon'] }} {{ $c['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Estimasi Biaya (Rupiah)</label>
                            <input 
                                type="number" 
                                name="estimated_cost" 
                                min="0" 
                                placeholder="Contoh: 15000000" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Target Tanggal (Opsional)</label>
                            <input 
                                type="date" 
                                name="target_date" 
                                class="w-full rounded-xl border-gray-300 dark:border-gray-700 dark:bg-gray-900 text-sm focus:border-pink-500 focus:ring-pink-500"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Foto Referensi (Opsional)</label>
                            <input 
                                type="file" 
                                name="image" 
                                accept="image/*"
                                class="w-full text-xs text-gray-500 file:mr-2 file:py-1.5 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-pink-50 file:text-pink-700"
                            >
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Catatan / Detail Rencana</label>
                        <textarea 
                            name="description" 
                            rows="3" 
                            placeholder="Tuliskan ide tempat, spesifikasi barang, atau kenapa impian ini penting untuk kita..." 
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
                            Simpan Impian 🌟
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
