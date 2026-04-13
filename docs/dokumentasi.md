# DOKUMENTASI PROGRAM
# Aplikasi Peminjaman Alat Sekolah
**Versi:** 1.0  
**Framework:** Laravel 11  
**Database:** MySQL  
**Tanggal:** April 2026
---
## DAFTAR ISI
- [A. ERD (Entity Relationship Diagram)](#a-erd-entity-relationship-diagram)
- [B. Deskripsi Program](#b-deskripsi-program)
- [C. Dokumentasi Fungsi/Prosedur](#c-dokumentasi-fungsiprosedur)
- [D. Debugging](#d-debugging)
- [E. Pengujian dan Tangkapan Layar Hasil Uji](#e-pengujian-dan-tangkapan-layar-hasil-uji)
---
## A. ERD (Entity Relationship Diagram)
### A.1 Diagram Relasi Tabel
```
┌─────────────────────┐         ┌─────────────────────┐
│      pengguna        │         │      kategori        │
├─────────────────────┤         ├─────────────────────┤
│ PK  id               │         │ PK  id               │
│     nama             │         │     nama_kategori    │
│     email (UNIQUE)   │         │     created_at       │
│     email_verified_at│         │     updated_at       │
│     kata_sandi       │         └──────────┬──────────┘
│     peran (ENUM)     │                    │
│     remember_token   │                    │ 1:N
│     created_at       │                    │
│     updated_at       │         ┌──────────▼──────────┐
└──────┬────────┬──────┘         │        alat          │
       │        │                ├─────────────────────┤
       │        │                │ PK  id               │
       │ 1:N    │ 1:N            │ FK  kategori_id  ────┘
       │        │                │     nama_alat        │
       │        │                │     deskripsi        │
       │        │                │     stok             │
       │        │                │     gambar           │
       │        │                │     created_at       │
       │        │                │     updated_at       │
       │        │                └──────────┬──────────┘
       │        │                           │
       │        │                           │ 1:N
       │        │                           │
       │        │     ┌─────────────────────▼──┐
       │        │     │      peminjaman         │
       │        │     ├────────────────────────┤
       │        └────►│ PK  id                  │
       │              │ FK  pengguna_id      ◄──┘ (ke pengguna)
       │              │ FK  alat_id          ◄──  (ke alat)
       │              │     jumlah              │
       │              │     tanggal_pinjam      │
       │              │     tanggal_wajib_kembali│
       │              │     tanggal_kembali     │
       │              │     status (ENUM)       │
       │              │     denda               │
       │              │     created_at          │
       │              │     updated_at          │
       │              └────────────────────────┘
       │
       │ 1:N
       │
┌──────▼──────────────┐
│   log_aktivitas      │
├─────────────────────┤
│ PK  id               │
│ FK  pengguna_id  ────┘ (CASCADE DELETE)
│     aksi             │
│     deskripsi        │
│     created_at       │
│     updated_at       │
└─────────────────────┘
```
### A.2 Relasi Antar Tabel
| Tabel Asal     | Tabel Tujuan   | Tipe Relasi | Keterangan                                    |
|----------------|----------------|-------------|-----------------------------------------------|
| `pengguna`     | `peminjaman`   | One-to-Many | Satu pengguna bisa punya banyak peminjaman    |
| `pengguna`     | `log_aktivitas`| One-to-Many | Satu pengguna bisa punya banyak log (CASCADE) |
| `kategori`     | `alat`         | One-to-Many | Satu kategori bisa punya banyak alat          |
| `alat`         | `peminjaman`   | One-to-Many | Satu alat bisa dipinjam berkali-kali          |
### A.3 Struktur Tabel Detail
#### Tabel `pengguna`
| Kolom              | Tipe Data                           | Constraint      | Keterangan                             |
|--------------------|-------------------------------------|-----------------|----------------------------------------|
| `id`               | BIGINT UNSIGNED                     | PRIMARY KEY, AI | ID unik pengguna                       |
| `nama`             | VARCHAR(255)                        | NOT NULL        | Nama lengkap pengguna                  |
| `email`            | VARCHAR(255)                        | UNIQUE, NOT NULL| Email untuk login                      |
| `email_verified_at`| TIMESTAMP                           | NULLABLE        | Waktu verifikasi email                 |
| `kata_sandi`       | VARCHAR(255)                        | NOT NULL        | Password terenkripsi (bcrypt)          |
| `peran`            | ENUM('admin','petugas','peminjam')  | DEFAULT 'peminjam'| Peran pengguna dalam sistem          |
| `remember_token`   | VARCHAR(100)                        | NULLABLE        | Token "remember me"                    |
| `created_at`       | TIMESTAMP                           | NULLABLE        | Waktu data dibuat                      |
| `updated_at`       | TIMESTAMP                           | NULLABLE        | Waktu data diperbarui                  |
#### Tabel `kategori`
| Kolom           | Tipe Data       | Constraint      | Keterangan                     |
|-----------------|-----------------|-----------------|--------------------------------|
| `id`            | BIGINT UNSIGNED | PRIMARY KEY, AI | ID unik kategori               |
| `nama_kategori` | VARCHAR(255)    | NOT NULL        | Nama kategori alat             |
| `created_at`    | TIMESTAMP       | NULLABLE        | Waktu data dibuat              |
| `updated_at`    | TIMESTAMP       | NULLABLE        | Waktu data diperbarui          |
#### Tabel `alat`
| Kolom         | Tipe Data       | Constraint           | Keterangan                     |
|---------------|-----------------|----------------------|--------------------------------|
| `id`          | BIGINT UNSIGNED | PRIMARY KEY, AI      | ID unik alat                   |
| `kategori_id` | BIGINT UNSIGNED | FOREIGN KEY, NOT NULL| FK ke tabel `kategori`         |
| `nama_alat`   | VARCHAR(255)    | NOT NULL             | Nama alat                      |
| `deskripsi`   | TEXT            | NULLABLE             | Deskripsi/keterangan alat      |
| `stok`        | INT             | NOT NULL             | Jumlah stok (dikelola trigger) |
| `gambar`      | VARCHAR(255)    | NULLABLE             | Path file gambar               |
| `created_at`  | TIMESTAMP       | NULLABLE             | Waktu data dibuat              |
| `updated_at`  | TIMESTAMP       | NULLABLE             | Waktu data diperbarui          |
#### Tabel `peminjaman`
| Kolom                   | Tipe Data       | Constraint           | Keterangan                      |
|-------------------------|-----------------|----------------------|---------------------------------|
| `id`                    | BIGINT UNSIGNED | PRIMARY KEY, AI      | ID unik transaksi               |
| `pengguna_id`           | BIGINT UNSIGNED | FOREIGN KEY, NOT NULL| FK ke tabel `pengguna`          |
| `alat_id`               | BIGINT UNSIGNED | FOREIGN KEY, NOT NULL| FK ke tabel `alat`              |
| `jumlah`                | INT             | NOT NULL, DEFAULT 1  | Jumlah alat yang dipinjam       |
| `tanggal_pinjam`        | DATE            | NOT NULL             | Tanggal mulai pinjam            |
| `tanggal_wajib_kembali` | DATE            | NOT NULL             | Batas waktu pengembalian        |
| `tanggal_kembali`       | DATE            | NULLABLE             | Tanggal dikembalikan            |
| `status`                | ENUM(5 nilai)   | DEFAULT 'diajukan'   | Status peminjaman               |
| `denda`                 | DECIMAL(10,2)   | DEFAULT 0            | Denda keterlambatan             |
| `created_at`            | TIMESTAMP       | NULLABLE             | Waktu data dibuat               |
| `updated_at`            | TIMESTAMP       | NULLABLE             | Waktu data diperbarui           |
**Nilai ENUM `status`:** `diajukan`, `disetujui`, `sedang_dikembalikan`, `dikembalikan`, `ditolak`
#### Tabel `log_aktivitas`
| Kolom         | Tipe Data       | Constraint             | Keterangan                    |
|---------------|-----------------|------------------------|-------------------------------|
| `id`          | BIGINT UNSIGNED | PRIMARY KEY, AI        | ID unik log                   |
| `pengguna_id` | BIGINT UNSIGNED | FK, NOT NULL, CASCADE  | FK ke `pengguna` (ON DELETE CASCADE) |
| `aksi`        | VARCHAR(255)    | NOT NULL               | Jenis aksi yang dilakukan     |
| `deskripsi`   | TEXT            | NULLABLE               | Detail deskripsi aksi         |
| `created_at`  | TIMESTAMP       | NULLABLE               | Waktu data dibuat             |
| `updated_at`  | TIMESTAMP       | NULLABLE               | Waktu data diperbarui         |
### A.4 Kode ERD (dbdiagram.io)
File ERD dalam format DBML tersedia di: `docs/erd_dbdiagram.dbml`  
Tempel kode tersebut ke [https://dbdiagram.io/d](https://dbdiagram.io/d) untuk melihat diagram visual.
---
## B. Deskripsi Program
### B.1 Gambaran Umum
**Aplikasi Peminjaman Alat Sekolah** adalah sistem informasi berbasis web yang dibangun menggunakan framework **Laravel 11** dengan database **MySQL**. Aplikasi ini dirancang untuk mengelola seluruh proses peminjaman dan pengembalian alat/peralatan di lingkungan sekolah secara digital, menggantikan pencatatan manual yang rentan terhadap kesalahan dan kehilangan data.
### B.2 Tujuan Program
1. Mempermudah proses pengajuan dan persetujuan peminjaman alat sekolah
2. Mengelola stok alat secara otomatis melalui trigger database
3. Menghitung denda keterlambatan pengembalian secara otomatis
4. Menyediakan audit trail untuk seluruh aktivitas dalam sistem
5. Memberikan akses berbasis peran (role-based) sesuai kebutuhan pengguna
### B.3 Fitur Utama
| No | Fitur                        | Keterangan                                                             |
|----|------------------------------|------------------------------------------------------------------------|
| 1  | Autentikasi                  | Login/logout dengan redirect otomatis sesuai peran                     |
| 2  | Manajemen Pengguna           | CRUD pengguna (admin, petugas, peminjam) oleh Admin                    |
| 3  | Manajemen Kategori           | CRUD kategori alat oleh Admin                                          |
| 4  | Manajemen Alat               | CRUD alat dengan upload gambar oleh Admin                              |
| 5  | Katalog Alat                 | Daftar alat tersedia dengan filter kategori untuk Peminjam             |
| 6  | Pengajuan Peminjaman         | Peminjam mengajukan peminjaman dengan durasi maks 3 hari               |
| 7  | Persetujuan/Penolakan        | Petugas/Admin menyetujui atau menolak pengajuan                        |
| 8  | Pengajuan Pengembalian       | Peminjam mengajukan pengembalian alat                                  |
| 9  | Konfirmasi Pengembalian      | Petugas/Admin mengkonfirmasi pengembalian + hitung denda otomatis      |
| 10 | Manajemen Stok Otomatis      | Stok berkurang saat disetujui, bertambah saat dikembalikan (trigger)   |
| 11 | Perhitungan Denda Otomatis   | Rp 5.000/hari keterlambatan (fungsi MySQL)                             |
| 12 | Laporan Peminjaman           | Rekapitulasi seluruh data peminjaman untuk Petugas                     |
| 13 | Log Aktivitas                | Riwayat semua aktivitas penting (audit trail) untuk Admin              |
### B.4 Arsitektur Sistem
```
┌─────────────────────────────────────────────────────────────┐
│                        BROWSER                               │
│                  (Chrome/Firefox/Edge)                        │
└───────────────────────┬─────────────────────────────────────┘
                        │ HTTP Request/Response
                        ▼
┌─────────────────────────────────────────────────────────────┐
│                     LARAVEL 11                               │
│  ┌──────────┐  ┌──────────────┐  ┌─────────────────────┐   │
│  │  Routes   │→│  Middleware   │→│    Controllers       │   │
│  │ (web.php) │  │ (RoleMiddle- │  │  - AuthController    │   │
│  │           │  │  ware.php)   │  │  - AlatController    │   │
│  └──────────┘  └──────────────┘  │  - KategoriController│   │
│                                   │  - PenggunaController│   │
│                                   │  - PeminjamanController│ │
│                                   │  - LogAktivitasCtrl  │   │
│                                   └──────────┬──────────┘   │
│                                              │               │
│  ┌───────────────┐              ┌────────────▼──────────┐   │
│  │    Views       │←─────────── │      Models           │   │
│  │ (Blade)        │   Data      │  - Pengguna           │   │
│  │ - admin/*      │              │  - Alat               │   │
│  │ - petugas/*    │              │  - Kategori           │   │
│  │ - peminjam/*   │              │  - Peminjaman         │   │
│  │ - auth/*       │              │  - LogAktivitas       │   │
│  └───────────────┘              └────────────┬──────────┘   │
│                                              │               │
└──────────────────────────────────────────────┼───────────────┘
                                               │ Eloquent ORM
                                               ▼
┌─────────────────────────────────────────────────────────────┐
│                        MySQL                                 │
│  ┌─────────┐ ┌─────────┐ ┌───────────────┐ ┌────────────┐  │
│  │pengguna │ │kategori │ │  peminjaman   │ │log_aktivitas│ │
│  └─────────┘ └─────────┘ └───────────────┘ └────────────┘  │
│  ┌─────────┐                                                │
│  │  alat   │  TRIGGER + FUNCTION + STORED PROCEDURE         │
│  └─────────┘                                                │
└─────────────────────────────────────────────────────────────┘
```
### B.5 Peran Pengguna (Role)
| Peran      | Hak Akses                                                                  |
|------------|----------------------------------------------------------------------------|
| **Admin**  | CRUD pengguna, kategori, alat. Kelola peminjaman. Lihat log aktivitas.     |
| **Petugas**| Kelola peminjaman (setujui/tolak/konfirmasi pengembalian). Lihat laporan.  |
| **Peminjam**| Lihat katalog alat. Ajukan peminjaman. Ajukan pengembalian.               |
### B.6 Alur Peminjaman
```
  ┌──────────┐     ┌──────────┐     ┌───────────────────┐     ┌──────────────┐
  │ Peminjam │     │ Petugas/ │     │     Peminjam      │     │   Petugas/   │
  │ mengajukan│────►│  Admin   │────►│ mengajukan        │────►│    Admin     │
  │ peminjaman│     │ setujui  │     │ pengembalian      │     │ konfirmasi   │
  └──────────┘     └──────────┘     └───────────────────┘     │ pengembalian │
                         │                                     └──────────────┘
                         │ (atau)                                     │
                         ▼                                            ▼
                   ┌──────────┐                              ┌──────────────┐
                   │  Ditolak  │                              │  Selesai +   │
                   └──────────┘                              │  Hitung Denda│
                                                              └──────────────┘
Status: diajukan → disetujui → sedang_dikembalikan → dikembalikan
                 → ditolak
```
### B.7 Teknologi yang Digunakan
| Komponen   | Teknologi              | Keterangan                           |
|------------|------------------------|--------------------------------------|
| Backend    | Laravel 11 (PHP 8.2+)  | Framework utama                      |
| Database   | MySQL 8.x              | RDBMS dengan trigger & stored proc   |
| Frontend   | Blade Template         | Template engine bawaan Laravel       |
| CSS        | Tailwind CSS           | Framework CSS utility-first          |
| Build Tool | Vite                   | Build tool untuk asset frontend      |
| Auth       | Laravel Auth (custom)  | Autentikasi dengan model `Pengguna`  |
| Storage    | Laravel Storage        | Upload & manajemen file gambar alat  |
---
## C. Dokumentasi Fungsi/Prosedur
### C.1 Trigger MySQL
#### Trigger 1: `kurangi_stok_setelah_disetujui`
| Properti      | Nilai                                                                |
|---------------|----------------------------------------------------------------------|
| **Event**     | AFTER UPDATE pada tabel `peminjaman`                                 |
| **Kondisi**   | `NEW.status = 'disetujui'` DAN `OLD.status != 'disetujui'`          |
| **Aksi**      | Kurangi stok alat sesuai jumlah peminjaman                           |
```sql
CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
        UPDATE alat SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END
```
**Penjelasan:**  
Saat petugas menyetujui peminjaman (status berubah ke `disetujui`), trigger ini otomatis mengurangi stok alat di tabel `alat` sebanyak jumlah yang dipinjam. Ini memastikan stok selalu akurat tanpa perlu update manual dari aplikasi.
---
#### Trigger 2: `tambah_stok_setelah_dikembalikan`
| Properti      | Nilai                                                                |
|---------------|----------------------------------------------------------------------|
| **Event**     | AFTER UPDATE pada tabel `peminjaman`                                 |
| **Kondisi**   | `NEW.status = 'dikembalikan'` DAN `OLD.status != 'dikembalikan'`     |
| **Aksi**      | Tambah stok alat sesuai jumlah peminjaman                            |
```sql
CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
        UPDATE alat SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END
```
**Penjelasan:**  
Saat pengembalian dikonfirmasi (status berubah ke `dikembalikan`), trigger ini otomatis menambahkan kembali stok alat. Ini menjamin konsistensi data stok di level database.
---
### C.2 Fungsi MySQL
#### Fungsi: `hitung_denda`
| Properti       | Nilai                                                  |
|----------------|--------------------------------------------------------|
| **Parameter**  | `tanggal_wajib` (DATE), `tanggal_kembali` (DATE)      |
| **Return**     | DECIMAL(10,2) — nominal denda dalam Rupiah             |
| **Tarif**      | Rp 5.000 per hari keterlambatan                        |
```sql
CREATE FUNCTION hitung_denda(tanggal_wajib DATE, tanggal_kembali DATE) RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE denda DECIMAL(10,2);
    DECLARE hari_terlambat INT;
    SET denda = 0;
    IF tanggal_kembali > tanggal_wajib THEN
        SET hari_terlambat = DATEDIFF(tanggal_kembali, tanggal_wajib);
        SET denda = hari_terlambat * 5000;
    END IF;
    RETURN denda;
END
```
**Penjelasan:**  
Fungsi ini menghitung denda berdasarkan selisih hari antara tanggal pengembalian aktual dan tanggal wajib kembali. Jika dikembalikan tepat waktu atau lebih awal, denda = 0. Fungsi ini dipanggil oleh stored procedure `proses_pengembalian`.
**Contoh:**
| Tanggal Wajib | Tanggal Kembali | Hari Terlambat | Denda        |
|---------------|-----------------|----------------|--------------|
| 2026-01-10    | 2026-01-10      | 0              | Rp 0         |
| 2026-01-10    | 2026-01-08      | 0 (lebih awal) | Rp 0         |
| 2026-01-10    | 2026-01-13      | 3              | Rp 15.000    |
| 2026-01-10    | 2026-01-20      | 10             | Rp 50.000    |
---
### C.3 Stored Procedure MySQL
#### Prosedur 1: `proses_persetujuan_peminjaman`
| Properti        | Nilai                                                  |
|-----------------|--------------------------------------------------------|
| **Parameter**   | `id_peminjaman` (INT), `id_petugas` (INT)              |
| **Aksi**        | Update status → catat log → commit                     |
| **Error Handle**| ROLLBACK otomatis jika terjadi error                   |
```sql
CREATE PROCEDURE proses_persetujuan_peminjaman(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
    START TRANSACTION;
    
    UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;
    
    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
    VALUES (id_petugas, "Setujui Peminjaman", 
            CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());
    
    COMMIT;
END
```
**Penjelasan:**  
Prosedur ini dijalankan saat petugas menyetujui peminjaman. Dalam satu transaksi, prosedur mengubah status peminjaman menjadi `disetujui` dan mencatat aktivitas ke tabel `log_aktivitas`. Jika salah satu operasi gagal, seluruh transaksi dibatalkan (ROLLBACK).
**Dipanggil dari:** `PeminjamanController@approve`  
```php
DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
```
---
#### Prosedur 2: `proses_pengembalian`
| Properti        | Nilai                                                           |
|-----------------|-----------------------------------------------------------------|
| **Parameter**   | `id_peminjaman` (INT), `id_petugas` (INT)                       |
| **Aksi**        | Hitung denda → update status & tanggal kembali → catat log      |
| **Error Handle**| ROLLBACK otomatis jika terjadi error                             |
```sql
CREATE PROCEDURE proses_pengembalian(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE tanggal_wajib DATE;
    DECLARE nominal_denda DECIMAL(10,2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
    START TRANSACTION;
    
    SELECT tanggal_wajib_kembali INTO tanggal_wajib 
    FROM peminjaman WHERE id = id_peminjaman;
    
    SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());
    
    UPDATE peminjaman 
    SET status = "dikembalikan", 
        tanggal_kembali = CURDATE(), 
        denda = nominal_denda 
    WHERE id = id_peminjaman;
    
    IF id_petugas IS NOT NULL THEN
        INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
        VALUES (id_petugas, "Konfirmasi Pengembalian", 
                CONCAT("Peminjaman ", id_peminjaman, " dikembalikan. Denda: ", nominal_denda), 
                NOW(), NOW());
    END IF;
    
    COMMIT;
END
```
**Penjelasan:**  
Prosedur ini menangani proses pengembalian alat secara lengkap:
1. Ambil tanggal wajib kembali dari data peminjaman
2. Hitung denda menggunakan fungsi `hitung_denda()`
3. Update status menjadi `dikembalikan`, isi tanggal kembali, dan simpan denda
4. Catat log aktivitas (jika ada petugas yang memproses)
5. Semua dalam satu transaksi untuk menjaga konsistensi data
**Dipanggil dari:** `PeminjamanController@returnTool`  
```php
DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);
```
---
### C.4 Controller - Fungsi PHP
#### AuthController
| Method           | Route                    | Deskripsi                                         |
|------------------|--------------------------|---------------------------------------------------|
| `showLoginForm()`| GET `/login`             | Menampilkan halaman login                         |
| `login()`        | POST `/login`            | Memproses autentikasi dan redirect sesuai peran   |
| `logout()`       | POST `/logout`           | Logout dan invalidasi session                     |
#### PenggunaController
| Method      | Route                        | Deskripsi                                  |
|-------------|------------------------------|--------------------------------------------|
| `index()`   | GET `/admin/pengguna`        | Daftar semua pengguna (paginate 10)        |
| `create()`  | GET `/admin/pengguna/create` | Form tambah pengguna baru                  |
| `store()`   | POST `/admin/pengguna`       | Simpan pengguna baru (validasi + hash)     |
| `edit()`    | GET `/admin/pengguna/{id}/edit` | Form edit pengguna                      |
| `update()`  | PUT `/admin/pengguna/{id}`   | Update data pengguna (password opsional)   |
| `destroy()` | DELETE `/admin/pengguna/{id}`| Hapus pengguna                             |
#### KategoriController
| Method      | Route                         | Deskripsi                                  |
|-------------|-------------------------------|--------------------------------------------|
| `index()`   | GET `/admin/kategori`         | Daftar semua kategori (paginate 10)        |
| `create()`  | GET `/admin/kategori/create`  | Form tambah kategori baru                  |
| `store()`   | POST `/admin/kategori`        | Simpan kategori baru                       |
| `edit()`    | GET `/admin/kategori/{id}/edit`| Form edit kategori                        |
| `update()`  | PUT `/admin/kategori/{id}`    | Update data kategori                       |
| `destroy()` | DELETE `/admin/kategori/{id}` | Hapus kategori (cek relasi alat dulu)      |
#### AlatController
| Method      | Route                      | Deskripsi                                     |
|-------------|----------------------------|-----------------------------------------------|
| `index()`   | GET `/admin/alat`          | Daftar semua alat + kategori (paginate 10)    |
| `create()`  | GET `/admin/alat/create`   | Form tambah alat baru                         |
| `store()`   | POST `/admin/alat`         | Simpan alat baru + upload gambar              |
| `edit()`    | GET `/admin/alat/{id}/edit`| Form edit alat                                |
| `update()`  | PUT `/admin/alat/{id}`     | Update alat + ganti gambar (hapus lama)       |
| `destroy()` | DELETE `/admin/alat/{id}`  | Hapus alat                                    |
#### PeminjamanController
| Method            | Route                                           | Peran     | Deskripsi                              |
|-------------------|------------------------------------------------|-----------|----------------------------------------|
| `katalog()`       | GET `/peminjam/katalog`                         | Peminjam  | Katalog alat tersedia (filter kategori)|
| `store()`         | POST `/peminjam/peminjaman`                     | Peminjam  | Ajukan peminjaman baru                 |
| `peminjamanSaya()`| GET `/peminjam/peminjaman-saya`                 | Peminjam  | Daftar peminjaman sendiri              |
| `requestReturn()` | POST `/peminjam/peminjaman/{id}/ajukan-pengembalian` | Peminjam | Ajukan pengembalian                    |
| `index()`         | GET `/petugas/peminjaman`                       | Petugas   | Daftar semua peminjaman                |
| `adminIndex()`    | GET `/admin/peminjaman`                         | Admin     | Daftar semua peminjaman                |
| `approve()`       | POST `/{role}/peminjaman/{id}/setujui`          | Keduanya  | Setujui peminjaman (stored procedure)  |
| `reject()`        | POST `/{role}/peminjaman/{id}/tolak`            | Keduanya  | Tolak peminjaman                       |
| `returnTool()`    | POST `/{role}/peminjaman/{id}/kembali`          | Keduanya  | Konfirmasi pengembalian (stored proc)  |
| `laporan()`       | GET `/petugas/laporan`                          | Petugas   | Laporan seluruh peminjaman             |
#### LogAktivitasController
| Method    | Route                       | Deskripsi                                 |
|-----------|-----------------------------|--------------------------------------------|
| `index()` | GET `/admin/log-aktivitas`  | Daftar semua log aktivitas (paginate 20)  |
---
### C.5 Middleware
#### RoleMiddleware
| Properti    | Nilai                                                        |
|-------------|--------------------------------------------------------------|
| **File**    | `app/Http/Middleware/RoleMiddleware.php`                      |
| **Alias**   | `peran`                                                      |
| **Fungsi**  | Mengontrol akses berdasarkan peran pengguna                  |
**Logika:**
1. Cek apakah user sudah login → jika belum, redirect ke `/login`
2. Cek apakah peran user sesuai parameter → jika cocok, lanjutkan request
3. Jika tidak cocok → redirect ke dashboard sesuai peran user
**Contoh penggunaan di route:**
```php
Route::middleware(['auth', 'peran:admin'])->group(function () { ... });
Route::middleware(['auth', 'peran:petugas'])->group(function () { ... });
Route::middleware(['auth', 'peran:peminjam'])->group(function () { ... });
```
---
### C.6 Model Eloquent
#### Pengguna
| Properti     | Nilai                                                |
|-------------|------------------------------------------------------|
| **Tabel**    | `pengguna`                                           |
| **Extends**  | `Authenticatable` (bukan `Model` biasa)              |
| **Override** | `getAuthPassword()` → return `kata_sandi`            |
| **Cast**     | `kata_sandi` → `hashed` (auto bcrypt saat set)       |
#### Alat
| Relasi            | Tipe       | Model Tujuan |
|-------------------|------------|--------------|
| `kategori()`      | belongsTo  | Kategori     |
| `peminjaman()`    | hasMany    | Peminjaman   |
#### Kategori
| Relasi    | Tipe    | Model Tujuan |
|-----------|---------|--------------|
| `alat()`  | hasMany | Alat         |
#### Peminjaman
| Relasi        | Tipe      | Model Tujuan |
|---------------|-----------|--------------|
| `pengguna()`  | belongsTo | Pengguna     |
| `alat()`      | belongsTo | Alat         |
#### LogAktivitas
| Relasi        | Tipe      | Model Tujuan |
|---------------|-----------|--------------|
| `pengguna()`  | belongsTo | Pengguna     |
---
## D. Debugging
### D.1 Daftar Bug yang Ditemukan dan Diperbaiki
#### Bug #1: Gambar Alat Tidak Tampil
| Properti       | Detail                                                       |
|----------------|--------------------------------------------------------------|
| **Gejala**     | Gambar alat tidak muncul di halaman katalog dan daftar alat  |
| **Penyebab**   | Symbolic link antara `storage/app/public` dan `public/storage` belum dibuat |
| **Solusi**     | Jalankan perintah `php artisan storage:link`                 |
| **Status**     | ✅ Selesai                                                   |
**Langkah debugging:**
1. Inspeksi elemen → path gambar menunjuk ke `/storage/alat/xxx.jpg`
2. Cek folder `public/storage` → tidak ada (symlink belum dibuat)
3. Jalankan `php artisan storage:link`
4. Gambar tampil normal setelah symlink dibuat
---
#### Bug #2: Stok Tidak Sinkron Setelah Seeding
| Properti       | Detail                                                       |
|----------------|--------------------------------------------------------------|
| **Gejala**     | Stok alat tidak berkurang untuk peminjaman aktif setelah seeding |
| **Penyebab**   | Seeder menggunakan `DB::table()->insert()` yang bypass trigger |
| **Solusi**     | Menambahkan logika manual di seeder untuk mengurangi stok peminjaman aktif |
| **Status**     | ✅ Selesai                                                   |
**Langkah debugging:**
1. Setelah `php artisan db:seed`, cek stok alat → masih utuh
2. Analisis: `DB::table()->insert()` tidak memicu trigger karena trigger hanya aktif saat `UPDATE`
3. Solusi: Tambahkan loop di seeder untuk `decrement` stok peminjaman berstatus `disetujui` dan `sedang_dikembalikan`
```php
// Kurangi stok untuk peminjaman aktif
$peminjamanAktif = DB::table('peminjaman')
    ->whereIn('status', ['disetujui', 'sedang_dikembalikan'])
    ->get();
foreach ($peminjamanAktif as $p) {
    DB::table('alat')
        ->where('id', $p->alat_id)
        ->decrement('stok', $p->jumlah);
}
```
---
#### Bug #3: Login Gagal Karena Kolom Password Custom
| Properti       | Detail                                                       |
|----------------|--------------------------------------------------------------|
| **Gejala**     | Login selalu gagal meskipun email dan password benar         |
| **Penyebab**   | Laravel secara default mencari kolom `password`, sedangkan tabel menggunakan `kata_sandi` |
| **Solusi**     | Override method `getAuthPassword()` di model `Pengguna`      |
| **Status**     | ✅ Selesai                                                   |
```php
// Override di model Pengguna
public function getAuthPassword()
{
    return $this->kata_sandi;
}
```
---
#### Bug #4: Kategori Tidak Bisa Dihapus
| Properti       | Detail                                                       |
|----------------|--------------------------------------------------------------|
| **Gejala**     | Error foreign key constraint saat menghapus kategori yang masih punya alat |
| **Penyebab**   | Tidak ada pengecekan relasi sebelum delete                   |
| **Solusi**     | Tambahkan validasi cek jumlah alat terkait sebelum hapus     |
| **Status**     | ✅ Selesai                                                   |
```php
public function destroy(Kategori $kategori)
{
    if ($kategori->alat()->count() > 0) {
        return redirect()->route('admin.kategori.index')
            ->with('error', 'Kategori tidak bisa dihapus karena masih ada ' 
            . $kategori->alat()->count() . ' alat yang terdaftar.');
    }
    $kategori->delete();
    return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
}
```
---
#### Bug #5: Stok Tidak Dicek Saat Persetujuan
| Properti       | Detail                                                       |
|----------------|--------------------------------------------------------------|
| **Gejala**     | Stok bisa menjadi negatif jika petugas menyetujui peminjaman saat stok habis |
| **Penyebab**   | Tidak ada validasi stok sebelum memanggil stored procedure   |
| **Solusi**     | Tambahkan pengecekan stok di controller sebelum approval     |
| **Status**     | ✅ Selesai                                                   |
```php
public function approve(Peminjaman $peminjaman)
{
    if ($peminjaman->alat->stok < $peminjaman->jumlah) {
        return back()->with('error', 'Stok alat tidak mencukupi. Stok tersedia: ' 
            . $peminjaman->alat->stok . ', dibutuhkan: ' . $peminjaman->jumlah);
    }
    DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
    return back()->with('success', 'Peminjaman disetujui.');
}
```
---
## E. Pengujian dan Tangkapan Layar Hasil Uji
### E.1 Skenario Pengujian
#### Modul 1: Autentikasi
| No | Test Case                       | Input                                   | Output yang Diharapkan                          | Status |
|----|-------------------------------- |-----------------------------------------|--------------------------------------------------|--------|
| 1  | Login dengan kredensial valid   | Email: admin@admin.com, Password: password | Redirect ke `/admin/dashboard`                | ✅ PASS |
| 2  | Login dengan password salah     | Email: admin@admin.com, Password: salah    | Pesan error "Kredensial tidak cocok"          | ✅ PASS |
| 3  | Login sebagai petugas           | Email: budi.petugas@sekolah.com            | Redirect ke `/petugas/dashboard`              | ✅ PASS |
| 4  | Login sebagai peminjam          | Email: andi.pratama@siswa.com              | Redirect ke `/peminjam/dashboard`             | ✅ PASS |
| 5  | Akses halaman tanpa login       | Buka `/admin/dashboard` tanpa login        | Redirect ke halaman login                     | ✅ PASS |
| 6  | Logout                          | Klik tombol logout                         | Redirect ke halaman login, session dihapus    | ✅ PASS |
#### Modul 2: Manajemen Pengguna (Admin)
| No | Test Case                       | Input                                    | Output yang Diharapkan                         | Status |
|----|-------------------------------- |------------------------------------------|------------------------------------------------|--------|
| 1  | Tambah pengguna baru            | Nama, email, password, peran             | Data tersimpan, redirect ke daftar pengguna    | ✅ PASS |
| 2  | Tambah pengguna email duplikat  | Email yang sudah terdaftar               | Pesan error "Email sudah terdaftar"            | ✅ PASS |
| 3  | Edit pengguna                   | Ubah nama dan peran                      | Data terperbarui                               | ✅ PASS |
| 4  | Edit pengguna ganti password    | Isi field password baru                  | Password terupdate (bisa login dengan yg baru) | ✅ PASS |
| 5  | Hapus pengguna                  | Klik tombol hapus                        | Data terhapus dari database                    | ✅ PASS |
| 6  | Validasi form kosong            | Submit form tanpa isi                    | Pesan validasi muncul                          | ✅ PASS |
#### Modul 3: Manajemen Kategori (Admin)
| No | Test Case                       | Input                                    | Output yang Diharapkan                         | Status |
|----|-------------------------------- |------------------------------------------|------------------------------------------------|--------|
| 1  | Tambah kategori baru            | Nama kategori: "Alat Baru"              | Data tersimpan, redirect ke daftar             | ✅ PASS |
| 2  | Edit kategori                   | Ubah nama kategori                       | Data terperbarui                               | ✅ PASS |
| 3  | Hapus kategori tanpa alat       | Hapus kategori yang kosong               | Kategori terhapus                              | ✅ PASS |
| 4  | Hapus kategori dengan alat      | Hapus kategori yang masih punya alat     | Pesan error relasi                             | ✅ PASS |
#### Modul 4: Manajemen Alat (Admin)
| No | Test Case                       | Input                                    | Output yang Diharapkan                         | Status |
|----|-------------------------------- |------------------------------------------|------------------------------------------------|--------|
| 1  | Tambah alat baru                | Nama, kategori, stok, deskripsi, gambar  | Data tersimpan + gambar terupload              | ✅ PASS |
| 2  | Tambah alat tanpa gambar        | Nama, kategori, stok (tanpa gambar)      | Data tersimpan, gambar null                    | ✅ PASS |
| 3  | Edit alat ganti gambar          | Upload gambar baru                       | Gambar lama dihapus, gambar baru tersimpan     | ✅ PASS |
| 4  | Validasi format gambar          | Upload file .txt                         | Pesan error "File harus berupa gambar"         | ✅ PASS |
| 5  | Hapus alat                      | Klik tombol hapus                        | Data alat terhapus                             | ✅ PASS |
#### Modul 5: Peminjaman
| No | Test Case                       | Input                                    | Output yang Diharapkan                         | Status |
|----|-------------------------------- |------------------------------------------|------------------------------------------------|--------|
| 1  | Ajukan peminjaman               | Pilih alat, jumlah, tanggal, durasi      | Status "diajukan", stok belum berubah          | ✅ PASS |
| 2  | Ajukan peminjaman stok habis    | Isi jumlah melebihi stok                 | Pesan error "Stok tidak mencukupi"             | ✅ PASS |
| 3  | Persetujuan peminjaman          | Petugas klik "Setujui"                   | Status → "disetujui", stok berkurang (trigger) | ✅ PASS |
| 4  | Penolakan peminjaman            | Petugas klik "Tolak"                     | Status → "ditolak", stok tidak berubah         | ✅ PASS |
| 5  | Ajukan pengembalian             | Peminjam klik "Ajukan Pengembalian"      | Status → "sedang_dikembalikan"                 | ✅ PASS |
| 6  | Konfirmasi pengembalian tepat waktu  | Petugas konfirmasi, tidak terlambat | Status → "dikembalikan", denda = 0, stok + | ✅ PASS |
| 7  | Konfirmasi pengembalian terlambat    | Petugas konfirmasi, terlambat 3 hari| Status → "dikembalikan", denda = Rp 15.000  | ✅ PASS |
| 8  | Validasi durasi > 3 hari       | Isi durasi = 5                           | Pesan error "Durasi maksimal 3 hari"           | ✅ PASS |
| 9  | Validasi tanggal lampau         | Isi tanggal kemarin                      | Pesan error "Tanggal tidak boleh kurang dari hari ini" | ✅ PASS |
#### Modul 6: Trigger & Stored Procedure
| No | Test Case                              | Kondisi                          | Output yang Diharapkan                       | Status |
|----|----------------------------------------|----------------------------------|----------------------------------------------|--------|
| 1  | Trigger kurangi stok                   | Status → disetujui, jumlah = 2  | Stok alat berkurang 2                        | ✅ PASS |
| 2  | Trigger tambah stok                    | Status → dikembalikan, jumlah = 2| Stok alat bertambah 2                       | ✅ PASS |
| 3  | Fungsi hitung_denda (tepat waktu)      | Kembali sebelum deadline         | Denda = 0                                   | ✅ PASS |
| 4  | Fungsi hitung_denda (terlambat)        | Kembali 5 hari setelah deadline  | Denda = 25.000                              | ✅ PASS |
| 5  | Prosedur persetujuan + logging         | Setujui peminjaman               | Status berubah + log tercatat               | ✅ PASS |
| 6  | Prosedur pengembalian + denda + log    | Konfirmasi pengembalian          | Status + denda + tanggal kembali + log      | ✅ PASS |
#### Modul 7: Akses Kontrol (Middleware)
| No | Test Case                              | Kondisi                          | Output yang Diharapkan                       | Status |
|----|----------------------------------------|----------------------------------|----------------------------------------------|--------|
| 1  | Admin akses halaman admin              | Login sebagai admin              | Halaman tampil normal                        | ✅ PASS |
| 2  | Peminjam akses halaman admin           | Login sebagai peminjam           | Redirect ke dashboard peminjam               | ✅ PASS |
| 3  | Petugas akses halaman admin            | Login sebagai petugas            | Redirect ke dashboard petugas                | ✅ PASS |
| 4  | Admin akses halaman petugas            | Login sebagai admin              | Redirect ke dashboard admin                  | ✅ PASS |
### E.2 Petunjuk Pengambilan Tangkapan Layar
Untuk melengkapi dokumentasi pengujian, ambil tangkapan layar pada skenario berikut:
#### Halaman Autentikasi
1. **Login** — Halaman login dengan form email & password
2. **Login gagal** — Pesan error setelah input kredensial salah
#### Dashboard
3. **Dashboard Admin** — Tampilan dashboard admin setelah login
4. **Dashboard Petugas** — Tampilan dashboard petugas
5. **Dashboard Peminjam** — Tampilan dashboard peminjam
#### CRUD Pengguna
6. **Daftar Pengguna** — Tabel daftar pengguna dengan pagination
7. **Form Tambah Pengguna** — Form input pengguna baru
8. **Form Edit Pengguna** — Form edit pengguna yang sudah ada
#### CRUD Kategori
9. **Daftar Kategori** — Tabel daftar kategori
10. **Error hapus kategori** — Pesan error saat hapus kategori yang masih punya alat
#### CRUD Alat
11. **Daftar Alat** — Tabel daftar alat dengan gambar
12. **Form Tambah Alat** — Form input alat baru (dengan upload gambar)
#### Peminjaman
13. **Katalog Alat** — Halaman katalog alat untuk peminjam
14. **Form Peminjaman** — Form pengajuan peminjaman
15. **Daftar Peminjaman Saya** — Daftar peminjaman milik peminjam
16. **Kelola Peminjaman (Petugas)** — Daftar peminjaman dengan tombol aksi
17. **Persetujuan berhasil** — Notifikasi "Peminjaman disetujui"
18. **Pengembalian dengan denda** — Notifikasi "Alat dikembalikan. Denda: Rp xxx"
#### Log & Laporan
19. **Log Aktivitas** — Riwayat semua aktivitas di sistem
20. **Laporan Peminjaman** — Rekapitulasi peminjaman
### E.3 Cara Menjalankan Pengujian Manual
```bash
# 1. Pastikan database sudah dimigrasikan dan di-seed
php artisan migrate:fresh --seed
# 2. Buat symbolic link untuk storage
php artisan storage:link
# 3. Jalankan development server
php artisan serve
# 4. Buka di browser
# http://127.0.0.1:8000
# 5. Login dengan akun berikut:
# Admin   : admin@admin.com / password
# Petugas : budi.petugas@sekolah.com / password
# Peminjam: andi.pratama@siswa.com / password
```
### E.4 Ringkasan Hasil Pengujian
| Modul                | Jumlah Test | Pass | Fail | Persentase |
|----------------------|-------------|------|------|------------|
| Autentikasi          | 6           | 6    | 0    | 100%       |
| Manajemen Pengguna   | 6           | 6    | 0    | 100%       |
| Manajemen Kategori   | 4           | 4    | 0    | 100%       |
| Manajemen Alat       | 5           | 5    | 0    | 100%       |
| Peminjaman           | 9           | 9    | 0    | 100%       |
| Trigger & Procedure  | 6           | 6    | 0    | 100%       |
| Akses Kontrol        | 4           | 4    | 0    | 100%       |
| **TOTAL**            | **40**      | **40**| **0**| **100%**  |
---
## LAMPIRAN
### Kredensial Login Default
| Peran    | Email                          | Password  |
|----------|--------------------------------|-----------|
| Admin    | admin@admin.com                | password  |
| Petugas  | budi.petugas@sekolah.com       | password  |
| Petugas  | siti.petugas@sekolah.com       | password  |
| Petugas  | ahmad.petugas@sekolah.com      | password  |
| Peminjam | andi.pratama@siswa.com         | password  |
### Struktur Direktori Proyek
```
peminjaman/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php
│   │   │   ├── AlatController.php
│   │   │   ├── KategoriController.php
│   │   │   ├── PenggunaController.php
│   │   │   ├── PeminjamanController.php
│   │   │   └── LogAktivitasController.php
│   │   └── Middleware/
│   │       └── RoleMiddleware.php
│   ├── Models/
│   │   ├── Pengguna.php
│   │   ├── Alat.php
│   │   ├── Kategori.php
│   │   ├── Peminjaman.php
│   │   └── LogAktivitas.php
│   └── Providers/
├── database/
│   ├── migrations/
│   │   ├── 0001_01_01_000000_buat_tabel_pengguna.php
│   │   ├── 2026_01_18_214530_buat_tabel_kategori.php
│   │   ├── 2026_01_18_214538_buat_tabel_alat.php
│   │   ├── 2026_01_18_214550_buat_tabel_peminjaman.php
│   │   ├── 2026_01_18_214631_buat_tabel_log_aktivitas.php
│   │   └── 2026_01_18_214712_buat_trigger_dan_prosedur.php
│   └── seeders/
│       └── DatabaseSeeder.php
├── resources/views/
│   ├── admin/        (Dashboard, CRUD pengguna/kategori/alat, peminjaman, log)
│   ├── petugas/      (Dashboard, peminjaman, laporan)
│   ├── peminjam/     (Dashboard, katalog, peminjaman saya)
│   ├── auth/         (Login)
│   └── layouts/      (Template utama)
├── routes/
│   └── web.php
└── docs/
    ├── dokumentasi_program.md  ← (file ini)
    └── erd_dbdiagram.dbml
```