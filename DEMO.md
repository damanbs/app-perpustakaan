# Skenario Demo Aplikasi Perpustakaan Digital Kampus (Persiapan UAS)

Dokumen ini memuat skenario panduan langkah demi langkah untuk mendemokan aplikasi Perpustakaan Digital Kampus secara runtut dan profesional.

---

## 0. Persiapan Lingkungan Demo

Pastikan database sudah dalam kondisi bersih dan terisi data dummy realistis:
```bash
php artisan migrate:fresh --seed
```

Nyalakan **dua instance server** di dua terminal terpisah:
* **Terminal 1 (Web UI):**
  ```bash
  php artisan serve --port=8000
  ```
* **Terminal 2 (API Internal):**
  ```bash
  php artisan serve --port=8011
  ```

Buka browser di `http://127.0.0.1:8000`.

---

## 1. Skenario Demo Langkah Demi Langkah

### Langkah 1: Autentikasi (Login & Proteksi Multi-Role)
1. Buka halaman `http://127.0.0.1:8000`. Sistem akan otomatis mengarahkan ke `/login` karena seluruh rute terproteksi middleware `auth`.
2. Masukkan kredensial akun **Admin**:
   * **Email:** `admin@pens.ac.id`
   * **Password:** `password`
3. Klik tombol **Login**.
4. Verifikasi bahwa sistem berhasil redirect ke **Dashboard** (`/`) dengan pesan flash *"Login berhasil, selamat datang Bambang Sutrisno."* dan menu **Kategori** muncul di navbar karena login sebagai admin.

---

### Langkah 2: Menampilkan Dashboard (Konsumsi `GET /api/stats`)
1. Di halaman Dashboard (`/`), tunjukkan 3 kartu statistik ringkasan:
   * **Total Buku:** 20
   * **Total Anggota:** 15
   * **Peminjaman Aktif:** (sesuai data seeder)
2. Jelaskan kepada penguji bahwa data di kartu dashboard ini diambil secara decoupled menggunakan **Laravel HTTP Client** dari endpoint `GET /api/stats`.

---

### Langkah 3: Tambah Kategori Buku (Akses Khusus Admin)
1. Klik menu **Kategori** di navbar (`/categories`).
2. Klik tombol **Tambah Kategori**.
3. Masukkan data:
   * **Nama Kategori:** `Ensiklopedia`
   * **Deskripsi:** `Buku referensi lengkap seputar ilmu pengetahuan umum.`
4. Klik **Simpan**. Verifikasi kategori baru muncul di daftar kategori.

---

### Langkah 4: Tambah Buku Baru
1. Klik menu **Buku** di navbar (`/books`).
2. Tunjukkan pagination rapi di bagian bawah tabel buku (`« Sebelumnya`, `1`, `2`, `Berikutnya »`).
3. Klik tombol **Tambah Buku**.
4. Isi form buku:
   * **Judul:** `Ensiklopedia Sains dan Alam Semesta`
   * **Penulis:** `Prof. Hendra Kusuma`
   * **Penerbit:** `Gramedia Pustaka Utama`
   * **Tahun Terbit:** `2024`
   * **ISBN:** `978-602-9988-77-1`
   * **Stok:** `5`
   * **Kategori:** Pilih `Ensiklopedia` yang baru dibuat.
5. Klik **Simpan**. Verifikasi buku baru berhasil ditambahkan.

---

### Langkah 5: Tambah Anggota Perpustakaan
1. Klik menu **Anggota** di navbar (`/members`).
2. Tunjukkan daftar anggota mahasiswa dengan nama dan email domain kampus `@pens.ac.id`.
3. Klik tombol **Tambah Anggota**.
4. Isi form:
   * **Nama:** `Farhan Pratama`
   * **NIM:** `3120600099`
   * **Email:** `farhan.pratama@pens.ac.id`
   * **Nomor Telepon:** `081234567899`
   * **Alamat:** `Jl. Raya ITS Kampus Sukolilo, Surabaya`
   * **Status:** `aktif`
5. Klik **Simpan**.

---

### Langkah 6: Buat Transaksi Peminjaman Baru
1. Klik menu **Peminjaman** di navbar (`/loans`).
2. Klik tombol **Catat Peminjaman**.
3. Masukkan data peminjaman:
   * **Anggota:** Pilih `Farhan Pratama`
   * **Tanggal Pinjam:** (Hari ini)
   * **Tanggal Kembali:** (7 hari ke depan)
   * **Pilih Buku:** Centang buku `Ensiklopedia Sains dan Alam Semesta`.
4. Klik **Simpan Transaksi**.
5. Verifikasi transaksi peminjaman baru berstatus **Dipinjam**.

---

### Langkah 7: Lihat Daftar Peminjaman Aktif & Detail Transaksi
1. Pada tabel peminjaman, filter atau cari transaksi yang baru dibuat.
2. Klik tombol **Detail** pada transaksi untuk melihat informasi lengkap peminjam, petugas pencatat, dan daftar buku yang dipinjam.

---

### Langkah 8: Proses Pengembalian Buku
1. Kembali ke daftar peminjaman (`/loans`).
2. Temukan transaksi peminjaman atas nama `Farhan Pratama`.
3. Klik tombol **Kembalikan Buku**.
4. Verifikasi status peminjaman berubah menjadi **Dikembalikan** dengan label tanggal pengembalian terisi hari ini.

---

### Langkah 9: Tampilkan Laporan Peminjaman (Konsumsi `GET /api/loans`)
1. Klik menu **Laporan** di navbar (`/loans/report`).
2. Tunjukkan tabel rekap transaksi peminjaman.
3. Jelaskan bahwa halaman ini mengonsumsi data langsung dari endpoint internal `GET /api/loans` menggunakan HTTP Client dan membangun paginasi custom dari meta response API.
4. Uji ketahanan aplikasi: coba matikan server API kedua di port 8011, lalu refresh halaman `/loans/report`. Tunjukkan bahwa halaman tidak crash (error 500), melainkan menampilkan fallback graceful berkat penanganan `ConnectionException`.
5. Nyalakan kembali server port 8011.

---

### Langkah 10: Demo REST API di Postman
Buka Postman dan jalankan collection `app-perpustakaan`:
1. **`GET /api/stats`** (Status `200 OK`) -> Menampilkan angka statistik realtime.
2. **`GET /api/books`** (Status `200 OK`) -> Menampilkan daftar buku berpaginasi beserta nested data `category`.
3. **`GET /api/books?judul=Laskar`** (Status `200 OK`) -> Menampilkan buku terfilter.
4. **`POST /api/books`** dengan body kosong (Status `422 Unprocessable Entity`) -> Memperlihatkan pesan validasi JSON.
5. **`GET /api/loans`** (Status `200 OK`) -> Menampilkan transaksi peminjaman dengan relasi `member`, `petugas`, dan `books`.
6. **`PUT /api/loans/{id}/kembalikan`** (Status `200 OK`) -> Memperbarui status transaksi menjadi dikembalikan via API.

---

## 2. Tabel Akun Pengujian

| Role | Email | Password | Keterangan |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@pens.ac.id` | `password` | Nama: Bambang Sutrisno (Akses Menu Kategori) |
| **Petugas** | `petugas1@pens.ac.id` | `password` | Nama: Siti Rahmawati |
| **Petugas** | `petugas2@pens.ac.id` | `password` | Nama: Ahmad Fauzi |
