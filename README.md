# 📚 Sistem Perpustakaan Digital Kampus

Aplikasi web manajemen perpustakaan kampus modern berbasis **Laravel 12**, dilengkapi dengan autentikasi multi-role berbasis session, modul CRUD lengkap untuk Buku, Kategori, Anggota, dan Peminjaman, REST API terstandarisasi, serta konsumsi API internal untuk Dashboard dan Laporan.

---

## 🚀 Fitur Utama

1. **Autentikasi & Otorisasi Multi-Role:**
   * Login, logout, dan manajemen profil berbasis session.
   * Dua peran pengguna: **Admin** (akses penuh termasuk kategori) dan **Petugas** (akses sirkulasi buku, anggota, peminjaman).
   * Proteksi middleware `auth` dan middleware khusus `CheckAdminRole`.

2. **Manajemen Data (CRUD):**
   * **Buku:** Judul, penulis, penerbit, tahun, ISBN, stok, kategori, dan sampul.
   * **Kategori:** Pengelompokan buku (Novel, Komik, Sains, Teknologi, Sejarah).
   * **Anggota:** Mahasiswa dengan NIM unik dan email kampus `@pens.ac.id`.
   * **Peminjaman:** Pencatatan peminjaman multi-buku, status peminjaman, serta alur pengembalian.

3. **REST API (Pertemuan 9):**
   * Endpoint publik terpisah di `routes/api.php` (`/api/books`, `/api/members`, `/api/loans`, `/api/stats`).
   * Menggunakan **API Resource** untuk format JSON response yang konsisten dan rapi.
   * Dokumentasi lengkap pengujian Postman tersedia di [**`API.md`**](./API.md).

4. **Konsumsi API Internal (Pertemuan 10):**
   * **Dashboard (`/`):** Mengonsumsi `GET /api/stats` lewat Laravel HTTP Client untuk menampilkan ringkasan kartu statistik.
   * **Laporan Peminjaman (`/loans/report`):** Mengonsumsi `GET /api/loans` untuk menyajikan rekap data peminjaman beserta paginasi dinamis.
   * Penanganan error jaringan koneksi menggunakan `try/catch (ConnectionException $e)` untuk mencegah error 500 saat API server offline.

5. **Data Dummy Realistis (Seeder & Factory):**
   * Faker bahasa Indonesia (`APP_FAKER_LOCALE=id_ID`).
   * Judul buku dan penerbit terkurasi sesuai industri literatur Indonesia.

---

## 🛠️ Persyaratan Sistem

* **PHP:** >= 8.2
* **Composer:** >= 2.0
* **SQLite / MySQL:** (Secara default project menggunakan SQLite)
* **Postman:** (Untuk pengujian endpoint API)

---

## 📦 Panduan Instalasi & Menjalankan Proyek

### 1. Clone Repository
```bash
git clone https://github.com/<username>/app-perpustakaan.git
cd app-perpustakaan
```

### 2. Install Dependency
```bash
composer install
```

### 3. Konfigurasi Environment (`.env`)
Salin file `.env.example` ke `.env`:
```bash
cp .env.example .env
```
Pastikan pengaturan berikut telah terisi di `.env`:
```env
APP_NAME=Perpustakaan
APP_URL=http://localhost:8000

# Base URL instance kedua `php artisan serve` khusus panggilan API internal
INTERNAL_API_URL=http://127.0.0.1:8011

APP_FAKER_LOCALE=id_ID

DB_CONNECTION=sqlite
```

Generate application key:
```bash
php artisan key:generate
```

### 4. Migrasi & Seeding Database
Jalankan migrasi seluruh tabel sekaligus mengisi data dummy realistis:
```bash
php artisan migrate:fresh --seed
```

---

## 🌐 Menjalankan Aplikasi (Dua Server)

Karena `php artisan serve` memproses request secara *single-threaded*, konsumsi API internal membutuhkan **dua server berjalan bersamaan** di port berbeda agar tidak terjadi *deadlock*:

### Terminal 1: Server Utama (Aplikasi Web & Browser)
```bash
php artisan serve --port=8000
```
Buka di browser: `http://127.0.0.1:8000`

### Terminal 2: Server API Internal (Penyedia Data JSON)
```bash
php artisan serve --port=8011
```
Server ini khusus menerima panggilan HTTP internal dari `DashboardController` dan `LoanController@report`.

---

## 🔑 Kredensial Akun Default

Semua akun menggunakan password bawaan: `password`

| Role | Nama | Email | Password | Hak Akses |
| :--- | :--- | :--- | :--- | :--- |
| **Admin** | Bambang Sutrisno | `admin@pens.ac.id` | `password` | Akses penuh termasuk menu Kategori |
| **Petugas** | Siti Rahmawati | `petugas1@pens.ac.id` | `password` | Operasional Buku, Anggota, & Peminjaman |
| **Petugas** | Ahmad Fauzi | `petugas2@pens.ac.id` | `password` | Operasional Buku, Anggota, & Peminjaman |

---

## 📖 Dokumentasi Lanjutan

* **Dokumentasi REST API:** Lihat [**`API.md`**](./API.md) untuk tabel 11 endpoint API, contoh request body, status code, dan screenshot Postman.
* **Skenario Demo UAS:** Lihat [**`DEMO.md`**](./DEMO.md) untuk panduan runtut demonstrasi fitur aplikasi kepada dosen penguji.
