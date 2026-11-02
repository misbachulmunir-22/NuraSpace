<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-serif font-bold text-xl sm:text-2xl text-gray-900 dark:text-white leading-tight flex items-center gap-2">
                    <span class="text-rose-500">👤</span> {{ __('Pengaturan Akun & Profil') }}
                </h2>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Kelola informasi akun cinta dan kata sandi keamanan ruang privat kalian.
                </p>
            </div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/50 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                <span>🔐</span> Ruang Privat
            </span>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            @if (session('warning'))
                <div class="p-4 bg-amber-50 dark:bg-amber-950/40 border border-amber-300 dark:border-amber-700/60 rounded-2xl text-amber-800 dark:text-amber-200 text-sm font-medium flex items-center gap-3 shadow-xs">
                    <span class="text-xl">⚠️</span>
                    <span>{{ session('warning') }}</span>
                </div>
            @endif

            <!-- Card Informasi Profil -->
            <div class="p-6 sm:p-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl border border-rose-100 dark:border-gray-700/80 shadow-xs">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <!-- Card Perbarui Kata Sandi -->
            <div class="p-6 sm:p-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl border border-rose-100 dark:border-gray-700/80 shadow-xs">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            @if (!auth()->user()->isPartner())
                <div class="p-6 sm:p-8 bg-white/90 dark:bg-gray-800/90 backdrop-blur-md rounded-3xl border border-red-100 dark:border-red-900/40 shadow-xs">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

