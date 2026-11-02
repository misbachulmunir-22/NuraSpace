<x-guest-layout>
    <div 
        x-data="{
            email: '{{ old('email') }}',
            password: '',
            showPass: false,
            fillMunir() {
                this.email = 'munir@nuraspace.local';
                this.password = 'SecretPassword123!';
            },
            fillSyahra() {
                this.email = 'syahra@nuraspace.local';
                this.password = 'SecretPassword123!';
            }
        }"
        class="w-full max-w-md"
    >
        <!-- Glassmorphism Romantic Login Card -->
        <div class="glass-romantic shadow-2xl rounded-3xl p-6 sm:p-8 space-y-6 transition-all duration-300">

            <!-- Card Header: Romantic Greeting & Monogram -->
            <div class="text-center space-y-3">
                <!-- Dual Avatar Avatars with Intertwined Heart Badge -->
                <div class="flex items-center justify-center -space-x-2">
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-blue-500 to-indigo-600 text-white font-bold text-sm flex items-center justify-center ring-4 ring-white dark:ring-gray-800 shadow-md">
                        M
                    </div>
                    <div class="w-8 h-8 rounded-full bg-pink-500 text-white text-xs flex items-center justify-center z-10 shadow-lg ring-2 ring-white dark:ring-gray-800 animate-pulse">
                        ❤️
                    </div>
                    <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-pink-500 to-rose-600 text-white font-bold text-sm flex items-center justify-center ring-4 ring-white dark:ring-gray-800 shadow-md">
                        S
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-extrabold text-gray-900 dark:text-white font-serif tracking-tight">
                        Ruang Cinta Kita
                    </h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 max-w-xs mx-auto leading-relaxed">
                        Selamat datang kembali di pelukan hangat rumah digital kita berdua.
                    </p>
                </div>

                <!-- Quick Account Selector Pill for Seamless Testing -->
                <div class="pt-1 flex items-center justify-center gap-2">
                    <button 
                        type="button" 
                        @click="fillMunir()" 
                        class="px-3 py-1.5 rounded-full text-3xs font-semibold bg-blue-50 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800 hover:bg-blue-100 transition active:scale-95 flex items-center gap-1"
                    >
                        <span>👨‍💼</span> Akun Munir
                    </button>
                    <button 
                        type="button" 
                        @click="fillSyahra()" 
                        class="px-3 py-1.5 rounded-full text-3xs font-semibold bg-pink-50 dark:bg-pink-900/30 text-pink-700 dark:text-pink-300 border border-pink-200 dark:border-pink-800 hover:bg-pink-100 transition active:scale-95 flex items-center gap-1"
                    >
                        <span>👩‍💼</span> Akun Syahra
                    </button>
                </div>
            </div>

            <!-- Session Status Alert -->
            <x-auth-session-status class="text-xs text-center" :status="session('status')" />

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">
                        Email Akun Cinta
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            📧
                        </div>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            x-model="email"
                            required 
                            autofocus 
                            placeholder="nama@nuraspace.local"
                            class="w-full pl-10 pr-4 py-2.5 rounded-2xl text-sm border-gray-200 dark:border-gray-700 dark:bg-gray-800/80 dark:text-white focus:border-pink-500 focus:ring-pink-500 transition shadow-2xs"
                        />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-xs text-rose-500" />
                </div>

                <!-- Password Input -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-xs font-semibold text-gray-700 dark:text-gray-300">
                            Kata Sandi
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-3xs text-pink-600 dark:text-pink-400 hover:underline">
                                Lupa sandi?
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                            🔒
                        </div>
                        <input 
                            id="password" 
                            :type="showPass ? 'text' : 'password'" 
                            name="password" 
                            x-model="password"
                            required 
                            autocomplete="current-password"
                            placeholder="Masukkan kata sandi..."
                            class="w-full pl-10 pr-10 py-2.5 rounded-2xl text-sm border-gray-200 dark:border-gray-700 dark:bg-gray-800/80 dark:text-white focus:border-pink-500 focus:ring-pink-500 transition shadow-2xs"
                        />
                        <button 
                            type="button" 
                            @click="showPass = !showPass" 
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200"
                        >
                            <span x-text="showPass ? '🙈' : '👁️'"></span>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-xs text-rose-500" />
                </div>

                <!-- Remember Me Checkbox -->
                <div class="flex items-center justify-between pt-1">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                        <input 
                            id="remember_me" 
                            type="checkbox" 
                            name="remember" 
                            class="rounded-md border-gray-300 dark:border-gray-700 text-pink-600 focus:ring-pink-500 dark:bg-gray-800"
                        >
                        <span class="ms-2 text-xs text-gray-600 dark:text-gray-400 font-medium">Ingat saya di perangkat ini</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button 
                        type="submit" 
                        class="w-full py-3 px-4 rounded-2xl bg-gradient-to-r from-pink-600 via-rose-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-semibold text-sm shadow-lg shadow-pink-500/25 active:scale-98 transition flex items-center justify-center gap-2"
                    >
                        <span>Masuk ke Ruang Cinta</span>
                        <span>💖</span>
                    </button>
                </div>
            </form>

            <!-- Security & Privacy Footer Pill -->
            <div class="pt-4 border-t border-pink-100 dark:border-pink-900/30 text-center">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-3xs font-medium text-gray-500 dark:text-gray-400 bg-pink-50/60 dark:bg-pink-950/40">
                    <span>🔐</span> Ruang Privat Tertutup &bull; Akses Terbatas Pasangan & Keluarga
                </span>
            </div>

        </div>
    </div>
</x-guest-layout>
