<section>
    <header>
        <h2 class="text-base font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
            <span>💌</span> {{ __('Informasi Akun Pasangan') }}
        </h2>

        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            {{ __('Perbarui nama panggilan sayang atau alamat email yang digunakan.') }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-5 space-y-4">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap / Panggilan')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Alamat Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-xs mt-2 text-gray-800 dark:text-gray-200">
                        {{ __('Alamat email kamu belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-xs text-pink-600 dark:text-pink-400 hover:text-pink-800 focus:outline-none">
                            {{ __('Kirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-xs text-emerald-600 dark:text-emerald-400">
                            {{ __('Tautan verifikasi baru telah dikirim ke email kamu.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <x-primary-button>{{ __('Simpan Profil') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2500)"
                    class="text-xs font-semibold text-emerald-600 dark:text-emerald-400 flex items-center gap-1"
                >✨ {{ __('Profil berhasil diperbarui!') }}</p>
            @endif
        </div>
    </form>
</section>

