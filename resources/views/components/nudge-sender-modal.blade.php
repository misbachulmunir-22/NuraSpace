<div 
    x-data="{ 
        open: false, 
        nudgeType: 'hug', 
        sending: false,
        sentSuccess: false,
        successMsg: '',
        recipientId: '{{ auth()->user()->isPartner() ? (auth()->user()->couple->partners()->where('id', '!=', auth()->id())->first()?->id ?? '') : (auth()->user()->couple->partners()->first()?->id ?? '') }}',

        async submitNudge() {
            this.sending = true;
            try {
                const res = await fetch('{{ route('nudges.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nudge_type: this.nudgeType,
                        recipient_id: this.recipientId ? parseInt(this.recipientId) : null
                    })
                });

                const data = await res.json();
                if (res.ok) {
                    this.sentSuccess = true;
                    this.successMsg = data.message || 'Sentuhan terkirim!';
                    setTimeout(() => {
                        this.open = false;
                        this.sentSuccess = false;
                    }, 2000);
                } else {
                    alert(data.message || 'Gagal mengirim sentuhan.');
                }
            } catch (err) {
                alert('Terjadi kesalahan jaringan.');
            } finally {
                this.sending = false;
            }
        }
    }"
    @open-nudge-modal.window="open = true; sentSuccess = false;"
    class="relative z-40"
>
    <!-- Modal Backdrop -->
    <div 
        x-show="open" 
        x-cloak
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/60 backdrop-blur-xs flex items-center justify-center p-4 z-40"
    >
        <!-- Modal Card -->
        <div 
            @click.away="open = false" 
            class="bg-white dark:bg-gray-800 rounded-3xl p-6 max-w-sm w-full shadow-2xl border border-pink-100 dark:border-gray-700 space-y-5"
        >
            <div class="flex items-center justify-between pb-3 border-b border-gray-100 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <span class="text-2xl">✨</span>
                    <h3 class="font-bold text-gray-900 dark:text-white text-base">Kirim Sentuhan Virtual</h3>
                </div>
                <button @click="open = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-lg">&times;</button>
            </div>

            <template x-if="sentSuccess">
                <div class="py-6 text-center space-y-2">
                    <div class="text-5xl animate-bounce">💖</div>
                    <h4 class="font-bold text-gray-900 dark:text-white text-base" x-text="successMsg"></h4>
                    <p class="text-xs text-gray-400">Sentuhan hangatmu sudah sampai di layarnya!</p>
                </div>
            </template>

            <template x-if="!sentSuccess">
                <div class="space-y-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Kirimkan getaran cinta dan kasih sayang langsung ke layar pasanganmu saat ini juga.
                    </p>

                    <!-- Pilihan Sentuhan -->
                    <div class="grid grid-cols-3 gap-2.5">
                        <button 
                            type="button" 
                            @click="nudgeType = 'hug'"
                            :class="nudgeType === 'hug' ? 'border-amber-400 bg-amber-50 dark:bg-amber-900/30 text-amber-800 dark:text-amber-200 shadow-sm' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300'"
                            class="p-3 border-2 rounded-2xl flex flex-col items-center text-center transition"
                        >
                            <span class="text-3xl mb-1">🤗</span>
                            <span class="text-xs font-bold">Pelukan</span>
                            <span class="text-3xs text-gray-400">Hangat</span>
                        </button>

                        <button 
                            type="button" 
                            @click="nudgeType = 'kiss'"
                            :class="nudgeType === 'kiss' ? 'border-pink-400 bg-pink-50 dark:bg-pink-900/30 text-pink-800 dark:text-pink-200 shadow-sm' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300'"
                            class="p-3 border-2 rounded-2xl flex flex-col items-center text-center transition"
                        >
                            <span class="text-3xl mb-1">😘</span>
                            <span class="text-xs font-bold">Kecupan</span>
                            <span class="text-3xs text-gray-400">Manis</span>
                        </button>

                        <button 
                            type="button" 
                            @click="nudgeType = 'miss_you'"
                            :class="nudgeType === 'miss_you' ? 'border-purple-400 bg-purple-50 dark:bg-purple-900/30 text-purple-800 dark:text-purple-200 shadow-sm' : 'border-gray-200 dark:border-gray-700 hover:border-gray-300'"
                            class="p-3 border-2 rounded-2xl flex flex-col items-center text-center transition"
                        >
                            <span class="text-3xl mb-1">🥺❤️</span>
                            <span class="text-xs font-bold">Kangen</span>
                            <span class="text-3xs text-gray-400">Banget</span>
                        </button>
                    </div>

                    <!-- Tombol Kirim -->
                    <button 
                        type="button" 
                        @click="submitNudge()"
                        :disabled="sending"
                        class="w-full py-3 bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white rounded-2xl text-sm font-semibold shadow-md transition disabled:opacity-50 flex items-center justify-center gap-2"
                    >
                        <span x-show="!sending">Kirim Sekarang ✨</span>
                        <span x-show="sending">Mengirim...</span>
                    </button>
                </div>
            </template>
        </div>
    </div>
</div>
