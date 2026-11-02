# NuraSpace 💖
> **A Private, Mobile-First, Real-Time Couple & Family Space**  
> Didedikasikan khusus untuk **Misbachul Munir** & **Syahra Alfi Nur Arintia** beserta anak-anak mereka tercinta.

[![Laravel](https://img.shields.io/badge/Laravel-11.x-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3%2B-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-3.x-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white)](https://tailwindcss.com)
[![Tests](https://img.shields.io/badge/Tests-87%20Passed-10B981?style=for-the-badge&logo=pest&logoColor=white)](#pengujian)
[![License](https://img.shields.io/badge/License-Private-amber?style=for-the-badge)](#)

---

## 📖 Tentang NuraSpace

**NuraSpace** adalah aplikasi web privat yang dirancang sebagai ruang digital eksklusif untuk sepasang kekasih hidup (**Misbachul Munir** & **Syahra Alfi Nur Arintia**) dan anak-anak mereka. Aplikasi ini **TIDAK TERBUKA UNTUK PUBLIK**, tidak menyediakan pendaftaran bebas, dan menerapkan standar privasi serta proteksi data keluarga yang sangat ketat.

---

## 🛡️ Prinsip Keamanan & Aturan Privasi Utama

1. **Registrasi Publik Dinonaktifkan**:
   - Rute `/register` mengembalikan respon `404 Not Found`. Tidak ada siapa pun dari luar yang dapat membuat akun secara bebas.
2. **Akun Pasangan Abadi (Anti-Delete Protection)**:
   - Kedua akun pasangan telah di-seed di awal dan diikat ke pasangan `couple_id = 1`.
   - Akun pasangan **TIDAK BISA DIHAPUS** melalui rute, controller, policy, maupun Eloquent model event (`User::deleting` hook yang memblokir penghapusan pada level database).
3. **Persetujuan Ganda untuk Akun Anak (Dual-Approval Workflow)**:
   - Pembuatan akun anak (`role: child`) harus diajukan oleh salah satu pasangan dan **wajib disetujui oleh pasangan lainnya** sebelum akun aktif.
   - Penghapusan akun anak juga memerlukan persetujuan ganda kedua pasangan.
4. **Wajib Ganti Password Awal (`must_change_password`)**:
   - Password awal diset melalui konfigurasi `.env` (`NURASPACE_INIT_PASSWORD`).
   - Pada saat login pertama kali, pengguna dipaksa dialihkan ke halaman profil untuk mengganti password pribadi sebelum dapat mengakses fitur apa pun.
5. **Akses Bertingkat Ramah Anak (Child-Safe Permissions)**:
   - **Boleh diakses anak**: Beranda keluarga, Kalender acara keluarga (`is_family_event = true`), Galeri foto keluarga, Countdown hari spesial, dan Pengiriman sentuhan virtual (*Nudge*) kepada orang tua.
   - **Terlarang untuk anak (403 Forbidden)**: Ruang curhatan/jurnal privat, Mood tracker pribadi pasangan, Kapsul waktu, Kuis cinta pasangan, Tabungan bersama/finansial, dan Manajemen keluarga.
6. **Penyimpanan Media Privat & Temporary Signed URLs**:
   - Semua foto memori, video, dan foto impian disimpan di disk lokal privat (`storage/app/private/`), bukan di folder publik.
   - Akses file media hanya dapat dilakukan melalui **Temporary Signed URL** bertanda tangan kriptografis dan berbatas waktu (60 menit) atau sesi terautentikasi dalam keluarga yang sama (`MediaController`).

---

## 🌟 Fitur-Fitur Unggulan

### 1. Autentikasi & Manajemen Keluarga (FR-01)
- Pembatasan akses keluarga berbasis `CoupleScope` (Global Eloquent Scope).
- Dasbor manajemen keluarga untuk partner: kelola akun anak, aktif/nonaktifkan akun, reset password anak sementara, dan pengajuan persetujuan ganda.

### 2. Hubungan Emosional & Keintiman (FR-02)
- **Mood Tracker Harian**: Catat perasaan hari ini (*Bahagia, Romantis, Bersyukur, Tenang, Lelah, Sedih, Kesal*) lengkap dengan catatan singkat. Tampilan status mood pasangan berdampingan secara real-time.
- **Curhatan Bersama & Jurnal Privat**: Ruang menulis cerita hari ini dengan opsi visibilitas *Bersama* (dapat dibaca pasangan) atau *Privat* (hanya dibaca oleh diri sendiri, dilindungi oleh Policy dan query masking).
- **Kapsul Waktu & Surat Tersegel**: Tulis surat masa depan untuk pasangan yang terkunci secara kriptografis hingga tanggal pembukaan (`unlock_at`). Isi surat disembunyikan (*masked safeContent*) dan tanggal buka tidak dapat diubah setelah disimpan.

### 3. Kalender Bersama & Pelacak Momen (FR-03)
- **Kalender Bersama**: Manajemen agenda kencan (*date night*), acara keluarga, kerjaan, ulang tahun, dan anniversary. Mendukung FullCalendar feed endpoint.
- **Pengingat Otomatis Anniversary & Ulang Tahun**: Command konsol `nuraspace:send-reminders` untuk mendeteksi H-7, H-3, H-1, dan hari H, mencatat ke log, dan mengirim notifikasi.
- **Hitung Mundur (Countdown)**: Penghitung mundur hari penting dengan live timer detik berjalan (*Hari, Jam, Menit, Detik*) dan fitur sematkan (*pin*) ke dasbor utama.

### 4. Komunikasi Real-Time & Kuis Pasangan (FR-04)
- **Sentuhan Virtual (Real-Time Nudge)**:
  - Kirimkan getaran kasih sayang instan ke layar pasangan: **Pelukan Hangat** 🤗, **Kecupan Manis** 😘, atau **Aku Kangen Kamu** 🥺❤️.
  - Dilengkapi micro-animation visual di layar penerima: halo glow keemasan, hujan hati cinta, dan kilau romantis.
  - Didukung siaran real-time WebSocket (Laravel Reverb / Echo) dengan *polling fallback* otomatis setiap 12 detik untuk keandalan 100%.
- **Pertanyaan Harian Pasangan (Kuis Cinta 105 Hari)**:
  - Disajikan 1 pertanyaan kurasi per hari dari bank 105 pertanyaan Bahasa Indonesia (kategori: *Romantisme Awal, Refleksi Mendalam, Impian Masa Depan, Keseruan Sehari-hari, Apresiasi & Rasa Syukur*).
  - **Fitur Kunci Privasi Ganda**: Jawaban masing-masing pasangan disembunyikan dan terkunci total sampai **KEDUA pasangan sama-sama telah mengirim jawaban**. Setelah keduanya menjawab, kedua jawaban terbuka berdampingan.
  - Arsip riwayat kuis harian lengkap dengan pagination.

### 5. Galeri Bersama & Linimasa (Memory Lane) (FR-05)
- Linimasa kronologis perjalanan cinta dari masa ke masa.
- Upload multi-file foto dan video ke disk privat.
- Lencana khusus **Tonggak Sejarah (Milestone)** 🏆 untuk momen monumental (kencan pertama, lamaran, pernikahan, kelahiran anak).
- Filter memori berdasarkan Tonggak Sejarah dan Tahun.
- Pemutar video terintegrasi dan pratinjau media resolusi tinggi via Signed URLs.

### 6. Rencana & Finansial Bersama (FR-06)
- **Couple Wishlist (Daftar Impian)**:
  - Rencanakan liburan impian, ide kencan romantis, barang impian, dan target masa depan.
  - Estimasi biaya dalam format Rupiah (`Rp 25.000.000`).
  - Pengubah status cepat (*Belum Dimulai* ➔ *Sedang Berjalan* 🚀 ➔ *Tercapai* 🎉 lengkap dengan tanggal terwujud).
- **Tabungan Bersama (Financial Tracker)**:
  - Target tabungan (rumah, liburan, pendidikan anak, dana darurat) dengan tema warna dan ikon pilihan.
  - Progress bar dinamis dan persentase ketercapaian target.
  - Buku kas setoran (ledger transaksi) dengan auto-recalculate saldo dan auto-complete jika target 100% terpenuhi.
  - **Proyeksi Estimasi Waktu**: Menghitung rata-rata setoran bulanan dan memproyeksikan sisa bulan hingga target tercapai.

### 7. Dasbor Terpadu & Mobile-First UX (FR-07)
- **Bottom Navigation Bar Mobile**: Bilah navigasi bawah modern dengan tombol aksi melingkar mengambang di tengah untuk mengirim sentuhan instan dari halaman mana pun.
- **Dark Mode Persisten**: Pengganti mode gelap/terang (🌙 / ☀️) yang tersimpan di `localStorage` dan mendeteksi preferensi sistem pengguna tanpa kedipan (zero flash).
- **Dasbor Adaptif**:
  - Tampilan Pasangan: Widget Mood terkini, Widget Quick Nudge, Widget Live Countdown, Widget Kuis Cinta hari ini, Widget Progres Tabungan, dan Widget Foto Kenangan terbaru.
  - Tampilan Anak: Ruang keluarga ceria dengan agenda keluarga terdekat, countdown hari liburan, tombol peluk orang tua, dan foto keluarga.

---

## 🛠️ Tech Stack

- **Backend**: Laravel 11 (PHP 8.3+)
- **Frontend**: Blade + Tailwind CSS v3 + Alpine.js + Vite
- **Real-Time**: Laravel Reverb + Laravel Echo (dengan Polling Fallback)
- **Database**: MySQL / PostgreSQL / SQLite
- **Testing**: Pest PHP / PHPUnit (87 Automated Feature Tests)
- **Storage**: Private Local Disk Storage / AWS S3 Compatible

---

## 🚀 Panduan Instalasi Lokal

### 1. Prasyarat Sistem
- PHP 8.3 atau lebih baru (dengan ekstensi `pdo`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `json`, `curl`)
- Composer 2.x
- Node.js 18+ & NPM
- SQLite / MySQL / MariaDB

### 2. Kloning & Instal Dependensi
```bash
git clone <repository-url> NuraSpace
cd NuraSpace

# Instal dependensi PHP
composer install

# Instal dependensi Node
npm install
```

### 3. Konfigurasi Lingkungan (`.env`)
Salin file konfigurasi contoh:
```bash
cp .env.example .env
php artisan key:generate
```

Pastikan variabel konfigurasi keluarga terisi pada `.env`:
```env
APP_NAME=NuraSpace
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id

NURASPACE_COUPLE_NAME="Misbachul Munir & Syahra Alfi Nur Arintia"
NURASPACE_ANNIVERSARY="2024-05-12"
NURASPACE_INIT_PASSWORD="SecretPassword123!"

PARTNER_1_NAME="Misbachul Munir"
PARTNER_1_EMAIL="munir@nuraspace.local"
PARTNER_1_GENDER="male"
PARTNER_1_BIRTH="1998-08-17"

PARTNER_2_NAME="Syahra Alfi Nur Arintia"
PARTNER_2_EMAIL="syahra@nuraspace.local"
PARTNER_2_GENDER="female"
PARTNER_2_BIRTH="2000-04-21"
```

### 4. Migrasi & Seeder Database
Jalankan migrasi database beserta data awal (kedua pasangan dan 105 bank pertanyaan cinta):
```bash
php artisan migrate --seed
```

### 5. Build Aset Frontend
```bash
npm run build
# Atau untuk pengembangan aktif:
# npm run dev
```

### 6. Jalankan Server Lokal
```bash
php artisan serve
```
Akses aplikasi melalui peramban di: `http://127.0.0.1:8000`

---

## 👥 Kredensial Akun Bawaan (Default Seeder)

| Peran | Nama | Email | Password Awal |
|---|---|---|---|
| **Partner 1 (Laki-laki)** | Misbachul Munir | `munir@nuraspace.local` | `SecretPassword123!` |
| **Partner 2 (Perempuan)** | Syahra Alfi Nur Arintia | `syahra@nuraspace.local` | `SecretPassword123!` |

> [!NOTE]  
> Pada saat pertama kali login, sistem akan secara otomatis mewajibkan kedua pengguna untuk memperbarui password awal demi keamanan.

---

## ⏰ Cron Scheduler & Background Tasks

Jalankan perintah scheduler Laravel untuk mengeksekusi otomatis:
- Pengingat Anniversary & Ulang Tahun (H-7, H-3, H-1, H-0)
- Pembukaan Kapsul Waktu yang telah jatuh tempo

```bash
php artisan schedule:work
```
Atau jalankan secara manual:
```bash
# Cek pengingat hari penting
php artisan nuraspace:send-reminders

# Cek pembukaan surat kapsul waktu
php artisan nuraspace:check-time-capsules
```

---

## 🧪 Pengujian Otomatis (Testing)

Aplikasi dilengkapi dengan rangkaian pengujian fitur otomatis (Pest / PHPUnit) dengan **100% tingkat kelulusan**:

```bash
php artisan test
```

### Rincian Rangkaian Pengujian:
- `ChildAccountApprovalFlowTest` (7 tes) — Alur persetujuan ganda pembuatan dan penghapusan akun anak.
- `MoodTrackerTest` (4 tes) — Pencatatan mood harian dan validasi pembaruan data harian.
- `JournalPrivacyTest` (4 tes) — Keamanan jurnal privat dan penyembunyian dari pasangan.
- `TimeCapsuleSecurityTest` (4 tes) — Penyegelan surat masa depan dan pemblokiran intip sebelum tanggal buka.
- `CalendarAndEventTest` (3 tes) — Manajemen acara kalender dan filter visibilitas keluarga.
- `CountdownTest` (3 tes) — Hitung mundur hari penting dan fungsi sematkan (pin).
- `ReminderCommandTest` (2 tes) — Deteksi otomatis hari anniversary dan ulang tahun.
- `NudgeTest` (7 tes) — Pengiriman sentuhan virtual, broadcast WebSocket, dan polling fallback.
- `DailyQuestionTest` (5 tes) — Bank 105 pertanyaan harian dan proteksi rahasia jawaban terkunci ganda.
- `MemoryGalleryTest` (4 tes) — Penyimpanan media di disk privat, signed URL, dan pembersihan file otomatis.
- `WishlistTest` (4 tes) — Papan impian bersama, estimasi biaya Rupiah, dan status tercapai.
- `SavingsTrackerTest` (5 tes) — Target tabungan bersama, ledger setoran, dan rekalkulasi saldo otomatis.
- `DashboardTest` (4 tes) — Pengujian tampilan dasbor terpadu adaptif untuk pasangan dan anak.
- **Total**: **87 tests passed, 312 assertions**.

---

## 📁 Struktur Direktori Utama

```
app/
├── Console/Commands/       # Command pengingat anniversary & kapsul waktu
├── Events/                 # Event WebSocket (NudgeSentEvent, DailyQuestionAnsweredEvent, MoodUpdatedEvent)
├── Http/Controllers/       # Controller fitur (Dashboard, Mood, Journal, Calendar, Nudge, Memory, Savings, dll)
├── Http/Middleware/        # CheckRole, EnsureCoupleScope, EnsurePasswordChanged
├── Models/                 # Eloquent Models dengan CoupleScope
├── Notifications/          # Notifikasi persetujuan akun anak & pengingat hari penting
└── Policies/               # Otorisasi hak akses pasangan vs anak
database/
├── migrations/             # 8 file migrasi database
└── seeders/                # DatabaseSeeder & DailyQuestionSeeder (105 pertanyaan)
resources/
├── views/
│   ├── components/         # Bottom navigation, Nudge receiver overlay, Nudge sender modal
│   ├── daily_question/     # Tampilan kuis cinta harian & riwayat
│   ├── memories/           # Galeri Memory Lane & linimasa foto/video
│   ├── savings/            # Dasbor tabungan, form setoran & riwayat transaksi
│   ├── wishlists/          # Papan impian berdua & KPI finansial
│   ├── journals/           # Ruang curhatan bersama & privat
│   ├── time_capsules/      # Kapsul waktu & surat tersegel
│   ├── calendar/           # Kalender bersama & feed agenda
│   └── dashboard.blade.php # Dasbor terpadu mobile-first pasangan & anak
routes/
├── web.php                 # Rute utama aplikasi dengan proteksi middleware bertingkat
└── auth.php                # Rute autentikasi Breeze (registrasi publik dimatikan)
```

---

<p align="center">
  Dibuat dengan segenap cinta dan kehangatan untuk <strong>Misbachul Munir</strong> & <strong>Syahra Alfi Nur Arintia</strong> 💑✨
</p>
