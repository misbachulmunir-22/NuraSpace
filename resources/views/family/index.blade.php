<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-100 flex items-center gap-2">
                    <span class="text-rose-500">👨‍👩‍👧‍👦</span> Ruang Keluarga & Akun Anak
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Kelola anggota keluarga dan persetujuan akun anak untuk pasangan {{ $couple->name }}
                </p>
            </div>
            <div>
                <button type="button" onclick="document.getElementById('add-child-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2.5 bg-rose-500 hover:bg-rose-600 active:bg-rose-700 text-white font-medium text-sm rounded-xl shadow-sm hover:shadow transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Ajukan Akun Anak
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">

            {{-- Flash Alert Messages --}}
            @if (session('success'))
                <div class="p-4 bg-emerald-50 dark:bg-emerald-950/40 border border-emerald-300 dark:border-emerald-700/60 rounded-2xl text-emerald-800 dark:text-emerald-200 text-sm font-medium flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('info'))
                <div class="p-4 bg-blue-50 dark:bg-blue-950/40 border border-blue-300 dark:border-blue-700/60 rounded-2xl text-blue-800 dark:text-blue-200 text-sm font-medium flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('info') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 bg-red-50 dark:bg-red-950/40 border border-red-300 dark:border-red-700/60 rounded-2xl text-red-800 dark:text-red-200 text-sm font-medium flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Section 1: Pasangan Utama --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span>💑</span> Pasangan Utama
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Akun pengelola utama dengan akses penuh & perlindungan permanen</p>
                    </div>
                    <span class="px-3 py-1 bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 text-xs font-semibold rounded-full border border-rose-200 dark:border-rose-800">
                        Couple ID #{{ $couple->id }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($partners as $p)
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-rose-400 to-pink-500 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                    {{ strtoupper(substr($p->name, 0, 1)) }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-800 dark:text-gray-200 text-base">
                                        {{ $p->name }}
                                        @if ($p->id === $currentPartner->id)
                                            <span class="text-xs font-medium text-rose-500 ml-1">(Anda)</span>
                                        @endif
                                    </h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $p->email }}</p>
                                    @if ($p->birth_date)
                                        <p class="text-[11px] text-gray-400 mt-0.5">🎂 Ulang tahun: {{ $p->birth_date->format('d M Y') }}</p>
                                    @endif
                                </div>
                            </div>
                            <span class="px-2.5 py-1 text-xs font-semibold bg-emerald-100 dark:bg-emerald-950/70 text-emerald-700 dark:text-emerald-300 rounded-full">
                                Partner
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Section 2: Permintaan Akun Anak Menunggu Persetujuan --}}
            @if ($pendingRequests->isNotEmpty())
                <div class="bg-amber-50/60 dark:bg-amber-950/20 rounded-3xl p-6 sm:p-8 border border-amber-200 dark:border-amber-800/60 shadow-sm">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-amber-950 dark:text-amber-200 flex items-center gap-2">
                                <span>⏳</span> Permintaan Akun Anak Menunggu Persetujuan (Dual-Approval)
                            </h3>
                            <p class="text-xs text-amber-800 dark:text-amber-400">Akun baru hanya akan dibuat jika KEDUA partner memberikan persetujuan</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        @foreach ($pendingRequests as $req)
                            @php
                                $myApproval = $req->approvals->firstWhere('partner_user_id', $currentPartner->id);
                            @endphp
                            <div class="bg-white dark:bg-gray-800 rounded-2xl p-5 border border-amber-200/80 dark:border-amber-800/40 shadow-sm">
                                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                                    <div class="space-y-1">
                                        <div class="flex items-center gap-2">
                                            <h4 class="font-bold text-gray-900 dark:text-gray-100 text-lg">{{ $req->name }}</h4>
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ $req->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">Email: <span class="font-mono text-xs">{{ $req->email }}</span> • Lahir: {{ $req->birth_date->format('d M Y') }}</p>
                                        <p class="text-xs text-gray-400">Diajukan oleh: <strong class="text-gray-700 dark:text-gray-300">{{ $req->requester->name }}</strong> ({{ $req->created_at->diffForHumans() }})</p>
                                    </div>

                                    {{-- Status persetujuan kedua partner --}}
                                    <div class="flex flex-wrap items-center gap-3">
                                        @foreach ($req->approvals as $appr)
                                            <div class="px-3 py-1.5 rounded-xl border text-xs flex items-center gap-1.5 {{ $appr->status === 'approved' ? 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300' : ($appr->status === 'rejected' ? 'bg-red-50 dark:bg-red-950/40 border-red-200 dark:border-red-800 text-red-700 dark:text-red-300' : 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800 text-amber-700 dark:text-amber-300') }}">
                                                <span class="font-medium">{{ $appr->partner->name }}:</span>
                                                <span class="font-bold">
                                                    @if ($appr->status === 'approved')
                                                        ✓ Disetujui
                                                    @elseif ($appr->status === 'rejected')
                                                        ✕ Ditolak
                                                    @else
                                                        ⏳ Menunggu
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                                {{-- Aksi Persetujuan jika Partner belum memutuskan --}}
                                @if ($myApproval && $myApproval->status === 'pending')
                                    <div class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700 flex flex-col sm:flex-row items-center justify-between gap-3">
                                        <span class="text-xs text-amber-700 dark:text-amber-400 font-medium">
                                            ⚠️ Keputusan Anda dibutuhkan untuk menyelesaikan permintaan ini.
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <form action="{{ route('family.child-requests.reject', $req) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menolak permintaan akun anak ini?');">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-gray-100 hover:bg-red-50 hover:text-red-600 dark:bg-gray-700 dark:hover:bg-red-950 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-xl transition">
                                                    Tolak Permintaan
                                                </button>
                                            </form>
                                            <form action="{{ route('family.child-requests.approve', $req) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-sm transition">
                                                    Setujui & Buat Akun
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Section 3: Daftar Akun Anak Aktif / Terdaftar --}}
            <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span>👶</span> Akun Anak Terdaftar ({{ $children->count() }})
                        </h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Hak akses terbatas ke fitur keluarga sesuai kebijakan privasi</p>
                    </div>
                </div>

                @if ($children->isEmpty())
                    <div class="text-center py-12 px-4 border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-2xl">
                        <div class="text-4xl mb-2">🌱</div>
                        <h4 class="font-bold text-gray-700 dark:text-gray-300 text-sm">Belum ada akun anak yang terdaftar</h4>
                        <p class="text-xs text-gray-400 mt-1 max-w-sm mx-auto">
                            Klik tombol "Ajukan Akun Anak" di atas untuk menambahkan anak. Akun akan dibuat setelah kedua partner menyetujui.
                        </p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($children as $c)
                            <div class="p-5 rounded-2xl bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 flex flex-col justify-between gap-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-gradient-to-tr from-amber-400 to-orange-400 flex items-center justify-center text-white font-bold text-lg shadow-sm">
                                            {{ strtoupper(substr($c->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-800 dark:text-gray-200 text-base flex items-center gap-2">
                                                {{ $c->name }}
                                                @if (!$c->is_active)
                                                    <span class="px-2 py-0.5 text-[10px] bg-red-100 dark:bg-red-950 text-red-600 dark:text-red-400 font-semibold rounded-full">
                                                        Nonaktif
                                                    </span>
                                                @else
                                                    <span class="px-2 py-0.5 text-[10px] bg-emerald-100 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 font-semibold rounded-full">
                                                        Aktif
                                                    </span>
                                                @endif
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $c->email }}</p>
                                            @if ($c->birth_date)
                                                <p class="text-[11px] text-gray-400 mt-0.5">🎂 {{ $c->birth_date->format('d M Y') }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Aksi Pengelolaan Akun Anak --}}
                                <div class="pt-3 border-t border-gray-200/60 dark:border-gray-700/60 flex flex-wrap items-center gap-2">
                                    {{-- Toggle Aktif/Nonaktif --}}
                                    <form action="{{ route('family.children.toggle-active', $c) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-xl border transition {{ $c->is_active ? 'bg-white hover:bg-amber-50 text-amber-700 border-amber-300 dark:bg-gray-800 dark:text-amber-400 dark:border-amber-800' : 'bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border-emerald-300 dark:bg-emerald-950 dark:text-emerald-300' }}">
                                            {{ $c->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>

                                    {{-- Reset Password Button --}}
                                    <button type="button" onclick="openResetPasswordModal('{{ $c->id }}', '{{ $c->name }}')" class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-white dark:bg-gray-800 hover:bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700 transition">
                                        Reset Password
                                    </button>

                                    {{-- Request Deletion (Dual Approval) --}}
                                    <form action="{{ route('family.children.request-deletion', $c) }}" method="POST" onsubmit="return confirm('Pengajuan penghapusan akun anak membutuhkan persetujuan kedua partner. Lanjutkan?');">
                                        @csrf
                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-xl bg-red-50 hover:bg-red-100 text-red-600 dark:bg-red-950/40 dark:text-red-400 border border-red-200 dark:border-red-900 transition">
                                            Hapus Akun
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Section 4: Riwayat Pengajuan --}}
            @if ($historyRequests->isNotEmpty())
                <div class="bg-white dark:bg-gray-800 rounded-3xl p-6 sm:p-8 shadow-sm border border-gray-100 dark:border-gray-700">
                    <h3 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-4">
                        Riwayat Permintaan Akun Sebelumnya
                    </h3>
                    <div class="divide-y divide-gray-100 dark:divide-gray-700 text-xs">
                        @foreach ($historyRequests as $hist)
                            <div class="py-3 flex items-center justify-between">
                                <div>
                                    <span class="font-bold text-gray-800 dark:text-gray-200">{{ $hist->name }}</span>
                                    <span class="text-gray-400">({{ $hist->email }})</span>
                                    <p class="text-[11px] text-gray-400">Diajukan {{ $hist->created_at->format('d M Y') }} oleh {{ $hist->requester->name }}</p>
                                </div>
                                <span class="px-2.5 py-1 rounded-full font-semibold {{ $hist->status === 'approved' ? 'bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300' : 'bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300' }}">
                                    {{ $hist->status === 'approved' ? 'Disetujui' : 'Ditolak' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Tambah Akun Anak --}}
    <div id="add-child-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('add-child-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-6">
                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                    <span>👶</span> Ajukan Akun Anak Baru
                </h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    Permintaan ini membutuhkan persetujuan dari kedua partner sebelum akun dibuat.
                </p>
            </div>

            <form action="{{ route('family.child-requests.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Nama Lengkap Anak</label>
                    <input type="text" name="name" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="Contoh: Muhammad Rayyan Munir">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Alamat Email (Unik)</label>
                    <input type="email" name="email" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="rayyan@nuraspace.local">
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Tanggal Lahir</label>
                        <input type="date" name="birth_date" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Jenis Kelamin</label>
                        <select name="gender" required class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                            <option value="male">Laki-laki</option>
                            <option value="female">Perempuan</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password Sementara (Min. 8 karakter)</label>
                    <input type="password" name="initial_password" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500" placeholder="••••••••">
                    <p class="text-[11px] text-gray-400 mt-1">Anak wajib mengganti password ini pada login pertama.</p>
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('add-child-modal').classList.add('hidden')" class="px-4 py-2.5 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-xs font-semibold rounded-xl bg-rose-500 hover:bg-rose-600 text-white shadow-sm transition">
                        Kirim Permintaan ke Pasangan
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Reset Password Anak --}}
    <div id="reset-password-modal" class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 hidden">
        <div class="bg-white dark:bg-gray-800 rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl border border-gray-100 dark:border-gray-700 relative">
            <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <div class="mb-4">
                <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Reset Password Anak</h3>
                <p id="reset-modal-child-name" class="text-xs text-gray-500 dark:text-gray-400 mt-1"></p>
            </div>

            <form id="reset-password-form" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Password Baru</label>
                    <input type="password" name="password" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" required minlength="8" class="w-full px-4 py-2.5 rounded-xl border border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 text-sm focus:ring-2 focus:ring-rose-500">
                </div>

                <div class="pt-4 flex items-center justify-end gap-3">
                    <button type="button" onclick="document.getElementById('reset-password-modal').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold rounded-xl text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-xs font-semibold rounded-xl bg-rose-500 hover:bg-rose-600 text-white shadow-sm">
                        Simpan Password Baru
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openResetPasswordModal(childId, childName) {
            const modal = document.getElementById('reset-password-modal');
            const form = document.getElementById('reset-password-form');
            const nameEl = document.getElementById('reset-modal-child-name');
            form.action = `/family/children/${childId}/reset-password`;
            nameEl.innerText = `Mengubah password untuk ${childName}. Anak wajib mengganti password saat login.`;
            modal.classList.remove('hidden');
        }
    </script>
</x-app-layout>
