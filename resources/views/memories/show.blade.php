<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <a href="{{ route('memories.index') }}" class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-semibold text-pink-600 dark:text-pink-400 hover:underline">
                &larr; Kembali ke Galeri
            </a>
            @can('delete', $memory)
                <form action="{{ route('memories.destroy', $memory) }}" method="POST" onsubmit="return confirm('Apakah kamu yakin ingin menghapus kenangan ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs text-red-500 hover:text-red-700 dark:hover:text-red-400 font-semibold">
                        Hapus Kenangan
                    </button>
                </form>
            @endcan
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            <!-- Memory Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 border border-gray-100 dark:border-gray-700 shadow-sm space-y-4">
                <div class="flex items-center justify-between flex-wrap gap-2">
                    <div class="flex items-center gap-2">
                        @if ($memory->is_milestone)
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 border border-amber-300">
                                <span>🏆</span> Tonggak Sejarah (Milestone)
                            </span>
                        @endif
                        <span class="text-xs text-gray-500 font-medium">
                            🗓️ {{ \Carbon\Carbon::parse($memory->memory_date)->translatedFormat('l, d F Y') }}
                        </span>
                    </div>

                    @if ($memory->location)
                        <span class="text-xs text-gray-500 flex items-center gap-1">
                            📍 {{ $memory->location }}
                        </span>
                    @endif
                </div>

                <h1 class="text-2xl sm:text-3xl font-extrabold text-gray-900 dark:text-white leading-tight">
                    {{ $memory->title }}
                </h1>

                @if ($memory->description)
                    <div class="pt-2 text-gray-700 dark:text-gray-200 text-sm sm:text-base whitespace-pre-wrap leading-relaxed border-t border-gray-100 dark:border-gray-750">
                        {{ $memory->description }}
                    </div>
                @endif

                <div class="pt-3 border-t border-gray-100 dark:border-gray-750 text-xs text-gray-400">
                    Diabadikan oleh <span class="font-semibold text-gray-700 dark:text-gray-300">{{ $memory->user->name }}</span> pada {{ $memory->created_at->translatedFormat('d M Y, H:i') }}
                </div>
            </div>

            <!-- Media Gallery (Photos and Videos) -->
            @if ($memory->media->count() > 0)
                <div class="space-y-4">
                    <h3 class="font-bold text-gray-900 dark:text-white text-base flex items-center gap-2">
                        <span>🖼️</span> Galeri Media ({{ $memory->media->count() }})
                    </h3>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach ($memory->media as $media)
                            <div class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700 shadow-xs">
                                @if ($media->isImage())
                                    <a href="{{ $media->signed_url }}" target="_blank" class="block aspect-4/3 overflow-hidden bg-gray-100 dark:bg-gray-900 group">
                                        <img 
                                            src="{{ $media->signed_url }}" 
                                            alt="{{ $memory->title }}" 
                                            class="w-full h-full object-cover group-hover:scale-102 transition duration-300"
                                        >
                                    </a>
                                @else
                                    <div class="aspect-16/9 bg-black flex items-center justify-center">
                                        <video controls class="w-full h-full">
                                            <source src="{{ $media->signed_url }}">
                                            Browser tidak mendukung pemutar video.
                                        </video>
                                    </div>
                                @endif

                                @if ($media->caption)
                                    <div class="p-3 text-xs text-gray-600 dark:text-gray-300 italic">
                                        {{ $media->caption }}
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
