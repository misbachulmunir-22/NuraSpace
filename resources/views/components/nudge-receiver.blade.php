<div x-data="nudgeReceiver()" x-init="initReceiver()" class="relative z-50">
    <!-- Overlay Visual Micro-Animation -->
    <template x-if="activeNudge">
        <div class="fixed inset-0 pointer-events-none flex items-center justify-center z-50 overflow-hidden">
            <!-- Pelukan Hangat Animation: Golden Glow Halo -->
            <template x-if="activeNudge.nudge_type === 'hug'">
                <div class="absolute inset-0 bg-amber-400/20 backdrop-blur-2xs animate-pulse transition-opacity duration-1000 flex items-center justify-center">
                    <div class="text-8xl sm:text-9xl transform scale-125 animate-bounce">
                        🤗
                    </div>
                </div>
            </template>

            <!-- Kecupan Manis Animation: Floating Hearts Shower -->
            <template x-if="activeNudge.nudge_type === 'kiss'">
                <div class="absolute inset-0 bg-pink-400/20 backdrop-blur-2xs flex items-center justify-center">
                    <div class="relative w-full h-full flex items-center justify-center">
                        <div class="text-8xl sm:text-9xl animate-ping opacity-80">
                            😘
                        </div>
                        <div class="absolute text-5xl animate-bounce -top-10 left-1/4">💖</div>
                        <div class="absolute text-4xl animate-bounce -bottom-10 right-1/4">💕</div>
                        <div class="absolute text-5xl animate-bounce -top-20 right-1/3">💋</div>
                    </div>
                </div>
            </template>

            <!-- Aku Kangen Kamu Animation: Sweet Sparkles -->
            <template x-if="activeNudge.nudge_type === 'miss_you'">
                <div class="absolute inset-0 bg-purple-500/20 backdrop-blur-2xs flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-8xl sm:text-9xl animate-pulse">
                            🥺❤️
                        </div>
                        <p class="text-white text-lg font-bold tracking-wide mt-3 drop-shadow-md bg-purple-900/60 px-4 py-1.5 rounded-full inline-block">
                            Ada yang lagi kangen banget sama kamu...
                        </p>
                    </div>
                </div>
            </template>
        </div>
    </template>

    <!-- Nudge Toast Notification Box -->
    <div class="fixed top-5 left-1/2 transform -translate-x-1/2 z-50 w-full max-w-sm px-4">
        <template x-for="item in notifications" :key="item.id">
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 -translate-y-4 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 -translate-y-4 scale-95"
                class="mb-3 bg-white/95 dark:bg-gray-800/95 backdrop-blur-md border-2 border-pink-300 dark:border-pink-600 rounded-3xl p-4 shadow-xl flex items-center gap-3.5"
            >
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-100 to-rose-200 dark:from-pink-900/50 dark:to-purple-900/50 flex items-center justify-center text-2xl shrink-0 shadow-inner">
                    <span x-text="item.emoji"></span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                        <h4 class="font-bold text-sm text-gray-900 dark:text-white" x-text="item.label"></h4>
                        <span class="text-3xs text-gray-400" x-text="item.created_at"></span>
                    </div>
                    <p class="text-xs text-gray-600 dark:text-gray-300 mt-0.5 truncate">
                        <span class="font-semibold text-pink-600 dark:text-pink-400" x-text="item.sender_name"></span> <span x-text="item.verb"></span>
                    </p>
                    
                    <!-- Quick Reciprocal Action Button -->
                    <div class="mt-2 flex items-center gap-2">
                        <button 
                            @click="respondBack(item.sender_id, item.nudge_type); dismiss(item.id)" 
                            class="text-3xs font-semibold px-2.5 py-1 rounded-full bg-pink-100 dark:bg-pink-900/50 text-pink-700 dark:text-pink-300 hover:bg-pink-200 transition"
                        >
                            Balas Sentuhan ✨
                        </button>
                        <button 
                            @click="dismiss(item.id)" 
                            class="text-3xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </div>
</div>

<script>
function nudgeReceiver() {
    return {
        activeNudge: null,
        notifications: [],
        pollInterval: null,
        userId: {{ auth()->id() ?? 'null' }},

        initReceiver() {
            if (!this.userId) return;

            // 1. WebSocket Listener with Laravel Echo if loaded
            if (window.Echo) {
                window.Echo.private('user.' + this.userId)
                    .listen('.nudge.received', (e) => {
                        this.showNudge(e);
                    });
            }

            // 2. Polling Fallback (Every 12s)
            this.fetchRecentNudges();
            this.pollInterval = setInterval(() => {
                this.fetchRecentNudges();
            }, 12000);
        },

        async fetchRecentNudges() {
            try {
                const res = await fetch('{{ route('nudges.recent') }}', {
                    headers: { 'Accept': 'application/json' }
                });
                if (!res.ok) return;
                const data = await res.json();
                if (data.nudges && data.nudges.length > 0) {
                    data.nudges.forEach(nudge => {
                        if (!this.notifications.some(existing => existing.id === nudge.id)) {
                            this.showNudge(nudge);
                        }
                    });
                }
            } catch (err) {
                // Ignore network errors on background poll
            }
        },

        showNudge(nudge) {
            this.activeNudge = nudge;
            this.notifications.unshift(nudge);

            // Hide full screen animation after 3.5 seconds
            setTimeout(() => {
                if (this.activeNudge && this.activeNudge.id === nudge.id) {
                    this.activeNudge = null;
                }
            }, 3500);

            // Auto dismiss toast after 9 seconds
            setTimeout(() => {
                this.dismiss(nudge.id);
            }, 9000);
        },

        async dismiss(nudgeId) {
            this.notifications = this.notifications.filter(n => n.id !== nudgeId);
            try {
                await fetch(`/nudges/${nudgeId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                });
            } catch (e) {}
        },

        async respondBack(recipientId, type) {
            // Reciprocal nudge: hug -> hug, kiss -> kiss, miss_you -> hug
            const replyType = type === 'miss_you' ? 'hug' : type;
            try {
                await fetch('{{ route('nudges.store') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        nudge_type: replyType,
                        recipient_id: recipientId
                    })
                });
            } catch (e) {}
        }
    }
}
</script>
