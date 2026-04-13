# DOKUMENTASI PROGRAM
## Aplikasi Peminjaman Alat Sekolah
**Nama:** Muhamad Syiaril Islami
**Versi:** 1.0
**Framework:** Laravel 11 · PHP 8.x · MySQL
**Tanggal:** April 2026
---
## DAFTAR ISI
- [A. ERD (Entity Relationship Diagram)](#a-erd-entity-relationship-diagram)
- [B. Deskripsi Program](#b-deskripsi-program)
- [C. Dokumentasi Fungsi dan Prosedur](#c-dokumentasi-fungsi-dan-prosedur)
- [D. Debugging](#d-debugging)
- [E. Pengujian dan Tangkapan Layar Hasil Uji](#e-pengujian-dan-tangkapan-layar-hasil-uji)
---
# A. ERD (Entity Relationship Diagram)
## A.1 Diagram ERD
```
  ┌────────────────────────┐          ┌──────────────────────┐
  │       PENGGUNA         │          │      KATEGORI        │
  │────────────────────────│          │──────────────────────│
  │ *id          : BIGINT  │          │ *id       : BIGINT   │
  │  nama        : VARCHAR │          │  nama_    : VARCHAR  │
  │  email       : VARCHAR │──┐       │  kategori            │
  │  kata_sandi  : VARCHAR │  │       └──────────┬───────────┘
  │  peran       : ENUM    │  │                  │ 1
  └──────────┬─────────────┘  │                  │
             │                │                  │ hasMany
        1    │                │                  │
             │ hasMany        │                  ▼ *
             │                │       ┌──────────────────────┐
             ▼ *              │       │        ALAT          │
  ┌────────────────────────┐  │       │──────────────────────│
  │    LOG_AKTIVITAS       │  │       │ *id         : BIGINT │
  │────────────────────────│  │       │  kategori_id: FK     │
  │ *id          : BIGINT  │  │       │  nama_alat  : VARCHAR│
  │  pengguna_id : FK      │──┘       │  deskripsi  : TEXT   │
  │  aksi        : VARCHAR │  │       │  stok       : INT    │
  │  deskripsi   : TEXT    │  │       │  gambar     : VARCHAR│
  └────────────────────────┘  │       └──────────┬───────────┘
                              │                  │ 1
     FK ke PENGGUNA ──────────┘                  │
     (ON DELETE CASCADE)                         │ hasMany
                                                 │
                              ┌──────────────────▼────────────┐
                              │         PEMINJAMAN            │
                              │───────────────────────────────│
                              │ *id                  : BIGINT │
                              │  pengguna_id         : FK     │
                              │  alat_id             : FK     │
                              │  jumlah              : INT    │
                              │  tanggal_pinjam      : DATE   │
                              │  tanggal_wajib_kembali: DATE  │
                              │  tanggal_kembali     : DATE   │
                              │  status              : ENUM   │
                              │  denda               : DECIMAL│
                              └───────────────────────────────┘
```
> Keterangan: `*` = Primary Key, `FK` = Foreign Key
## A.2 Relasi Antar Tabel
| No | Tabel Asal  | Tabel Tujuan    | Kardinalitas | Keterangan                                    |
|----|-------------|-----------------|--------------|-----------------------------------------------|
| 1  | `pengguna`  | `peminjaman`    | 1 : N        | Satu pengguna bisa punya banyak peminjaman    |
| 2  | `pengguna`  | `log_aktivitas` | 1 : N        | Satu pengguna bisa punya banyak log (CASCADE) |
| 3  | `kategori`  | `alat`          | 1 : N        | Satu kategori bisa punya banyak alat          |
| 4  | `alat`      | `peminjaman`    | 1 : N        | Satu alat bisa dipinjam berkali-kali          |
## A.3 Struktur Tabel
### Tabel `pengguna`
| No | Kolom               | Tipe Data         | Constraint                  | Keterangan                          |
|----|---------------------|-------------------|-----------------------------|-------------------------------------|
| 1  | `id`                | BIGINT UNSIGNED   | PRIMARY KEY, AUTO_INCREMENT | Identitas unik pengguna             |
| 2  | `nama`              | VARCHAR(255)      | NOT NULL                    | Nama lengkap                        |
| 3  | `email`             | VARCHAR(255)      | NOT NULL, UNIQUE            | Email (digunakan untuk login)       |
| 4  | `email_verified_at` | TIMESTAMP         | NULLABLE                    | Waktu verifikasi email              |
| 5  | `kata_sandi`        | VARCHAR(255)      | NOT NULL                    | Password di-hash (bcrypt)           |
| 6  | `peran`             | ENUM              | NOT NULL                    | `admin`, `petugas`, `peminjam`      |
| 7  | `remember_token`    | VARCHAR(100)      | NULLABLE                    | Token "Ingat Saya"                  |
| 8  | `created_at`        | TIMESTAMP         | NULLABLE                    | Waktu dibuat                        |
| 9  | `updated_at`        | TIMESTAMP         | NULLABLE                    | Waktu diperbarui                    |
### Tabel `kategori`
| No | Kolom           | Tipe Data       | Constraint                  | Keterangan            |
|----|-----------------|-----------------|-----------------------------|-----------------------|
| 1  | `id`            | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Identitas unik        |
| 2  | `nama_kategori` | VARCHAR(255)    | NOT NULL                    | Nama kategori alat    |
| 3  | `created_at`    | TIMESTAMP       | NULLABLE                    | Waktu dibuat          |
| 4  | `updated_at`    | TIMESTAMP       | NULLABLE                    | Waktu diperbarui      |
### Tabel `alat`
| No | Kolom         | Tipe Data       | Constraint                     | Keterangan                 |
|----|---------------|-----------------|--------------------------------|----------------------------|
| 1  | `id`          | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT    | Identitas unik             |
| 2  | `kategori_id` | BIGINT UNSIGNED | FOREIGN KEY → `kategori(id)`   | Referensi ke kategori      |
| 3  | `nama_alat`   | VARCHAR(255)    | NOT NULL                       | Nama alat                  |
| 4  | `deskripsi`   | TEXT            | NULLABLE                       | Deskripsi alat             |
| 5  | `stok`        | INTEGER         | NOT NULL, DEFAULT 0            | Jumlah stok tersedia       |
| 6  | `gambar`      | VARCHAR(255)    | NULLABLE                       | Path file gambar           |
| 7  | `created_at`  | TIMESTAMP       | NULLABLE                       | Waktu dibuat               |
| 8  | `updated_at`  | TIMESTAMP       | NULLABLE                       | Waktu diperbarui           |
### Tabel `peminjaman`
| No | Kolom                   | Tipe Data       | Constraint                          | Keterangan                     |
|----|-------------------------|-----------------|--------------------------------------|--------------------------------|
| 1  | `id`                    | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT         | Identitas unik                 |
| 2  | `pengguna_id`           | BIGINT UNSIGNED | FOREIGN KEY → `pengguna(id)`        | Referensi ke peminjam          |
| 3  | `alat_id`               | BIGINT UNSIGNED | FOREIGN KEY → `alat(id)`            | Referensi ke alat              |
| 4  | `jumlah`                | INTEGER         | NOT NULL, DEFAULT 1                 | Jumlah unit yang dipinjam      |
| 5  | `tanggal_pinjam`        | DATE            | NOT NULL                            | Tanggal mulai peminjaman       |
| 6  | `tanggal_wajib_kembali` | DATE            | NOT NULL                            | Batas akhir pengembalian       |
| 7  | `tanggal_kembali`       | DATE            | NULLABLE                            | Tanggal dikembalikan           |
| 8  | `status`                | ENUM            | DEFAULT 'diajukan'                  | `diajukan`, `disetujui`, `sedang_dikembalikan`, `dikembalikan`, `ditolak` |
| 9  | `denda`                 | DECIMAL(10,2)   | DEFAULT 0                           | Denda keterlambatan (Rp 5.000/hari) |
| 10 | `created_at`            | TIMESTAMP       | NULLABLE                            | Waktu dibuat                   |
| 11 | `updated_at`            | TIMESTAMP       | NULLABLE                            | Waktu diperbarui               |
### Tabel `log_aktivitas`
| No | Kolom         | Tipe Data       | Constraint                                      | Keterangan                |
|----|---------------|-----------------|--------------------------------------------------|---------------------------|
| 1  | `id`          | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT                      | Identitas unik            |
| 2  | `pengguna_id` | BIGINT UNSIGNED | FOREIGN KEY → `pengguna(id)`, ON DELETE CASCADE  | Referensi ke pelaku       |
| 3  | `aksi`        | VARCHAR(255)    | NOT NULL                                         | Jenis aksi                |
| 4  | `deskripsi`   | TEXT            | NULLABLE                                         | Detail deskripsi          |
| 5  | `created_at`  | TIMESTAMP       | NULLABLE                                         | Waktu aktivitas           |
| 6  | `updated_at`  | TIMESTAMP       | NULLABLE                                         | Waktu diperbarui          |
---
# B. Deskripsi Program
## B.1 Gambaran Umum
Aplikasi Peminjaman Alat Sekolah adalah sistem informasi berbasis web yang mengelola seluruh proses peminjaman dan pengembalian alat/peralatan sekolah secara digital. Sistem ini menggantikan pencatatan manual dan mendukung pengelolaan stok otomatis, perhitungan denda otomatis, serta kontrol akses berbasis peran.
## B.2 Tujuan
1. Mempermudah pengajuan dan persetujuan peminjaman alat sekolah
2. Mengelola stok alat secara otomatis melalui trigger database
3. Menghitung denda keterlambatan pengembalian secara otomatis (Rp 5.000/hari)
4. Menyediakan log aktivitas (audit trail) untuk seluruh proses
5. Membatasi akses fitur sesuai peran pengguna (admin, petugas, peminjam)
## B.3 Fitur Utama
| No | Fitur                      | Keterangan                                                           |
|----|----------------------------|----------------------------------------------------------------------|
| 1  | Autentikasi                | Login/logout dengan redirect otomatis sesuai peran                   |
| 2  | Manajemen Pengguna         | CRUD pengguna oleh Admin                                             |
| 3  | Manajemen Kategori         | CRUD kategori alat oleh Admin                                        |
| 4  | Manajemen Alat             | CRUD alat dengan upload gambar oleh Admin                            |
| 5  | Katalog Alat               | Daftar alat tersedia + filter kategori untuk Peminjam                |
| 6  | Pengajuan Peminjaman       | Peminjam mengajukan peminjaman dengan jumlah dan durasi maks 3 hari  |
| 7  | Persetujuan/Penolakan      | Petugas/Admin menyetujui atau menolak pengajuan                      |
| 8  | Pengajuan Pengembalian     | Peminjam mengajukan pengembalian alat                                |
| 9  | Konfirmasi Pengembalian    | Petugas/Admin mengkonfirmasi + hitung denda otomatis                 |
| 10 | Manajemen Stok Otomatis    | Stok berkurang/bertambah sesuai jumlah (trigger MySQL)               |
| 11 | Perhitungan Denda          | Rp 5.000/hari keterlambatan (function MySQL)                         |
| 12 | Laporan Peminjaman         | Rekapitulasi seluruh data peminjaman untuk Petugas                   |
| 13 | Log Aktivitas              | Riwayat semua aktivitas penting untuk Admin                          |
## B.4 Peran Pengguna
| Peran       | Hak Akses                                                                     |
|-------------|-------------------------------------------------------------------------------|
| **Admin**   | CRUD pengguna, kategori, alat. Kelola peminjaman. Lihat log aktivitas.        |
| **Petugas** | Kelola peminjaman (setujui/tolak/konfirmasi pengembalian). Lihat laporan.     |
| **Peminjam**| Lihat katalog alat. Ajukan peminjaman. Ajukan pengembalian.                   |
## B.5 Matriks Hak Akses
| Fitur                       | Admin | Petugas | Peminjam |
|-----------------------------|:-----:|:-------:|:--------:|
| Dashboard Admin             |  ✅   |   ❌    |    ❌    |
| Dashboard Petugas           |  ❌   |   ✅    |    ❌    |
| Dashboard Peminjam          |  ❌   |   ❌    |    ✅    |
| Kelola Pengguna (CRUD)      |  ✅   |   ❌    |    ❌    |
| Kelola Kategori (CRUD)      |  ✅   |   ❌    |    ❌    |
| Kelola Alat (CRUD)          |  ✅   |   ❌    |    ❌    |
| Lihat Log Aktivitas         |  ✅   |   ❌    |    ❌    |
| Lihat Semua Peminjaman      |  ✅   |   ✅    |    ❌    |
| Setujui/Tolak Peminjaman    |  ✅   |   ✅    |    ❌    |
| Konfirmasi Pengembalian     |  ✅   |   ✅    |    ❌    |
| Cetak Laporan               |  ❌   |   ✅    |    ❌    |
| Lihat Katalog Alat          |  ❌   |   ❌    |    ✅    |
| Ajukan Peminjaman           |  ❌   |   ❌    |    ✅    |
| Lihat Riwayat Saya          |  ❌   |   ❌    |    ✅    |
| Ajukan Pengembalian         |  ❌   |   ❌    |    ✅    |
## B.6 Alur Status Peminjaman
```
                  ┌───────────┐
                  │  diajukan │ ← Peminjam mengajukan
                  └─────┬─────┘
                        │
            ┌───────────┼───────────┐
            ▼                       ▼
    ┌──────────────┐        ┌───────────┐
    │  disetujui   │        │  ditolak  │ ← Selesai
    │(stok - jumlah)│       └───────────┘
    └──────┬───────┘
            │
            ▼
  ┌──────────────────────┐
  │ sedang_dikembalikan  │ ← Peminjam ajukan pengembalian
  └──────────┬───────────┘
            │
            ▼
    ┌──────────────┐
    │ dikembalikan │ ← Petugas konfirmasi + denda dihitung
    │(stok + jumlah)│
    └──────────────┘
```
## B.7 Arsitektur MVC
```
  ┌──────────┐     ┌─────────────┐     ┌──────────┐     ┌──────────────┐
  │  Browser │ ──→ │   Routes    │ ──→ │Controller│ ──→ │    Model     │
  │ (Client) │     │  (web.php)  │     │          │     │  (Eloquent)  │
  └──────────┘     └─────────────┘     └──────────┘     └──────────────┘
       ▲                                    │                   │
       │                                    ▼                   ▼
       │                              ┌──────────┐     ┌──────────────┐
       └─────────────────────────────│   View   │ ←── │   Database   │
                                      │ (Blade)  │     │   (MySQL)    │
                                      └──────────┘     └──────────────┘
```
## B.8 Teknologi
| Komponen   | Teknologi              |
|------------|------------------------|
| Backend    | Laravel 11 (PHP 8.2+)  |
| Database   | MySQL 8.x              |
| Frontend   | Blade Template         |
| CSS        | Tailwind CSS           |
| Build Tool | Vite                   |
| Storage    | Laravel Storage        |
---
# C. Dokumentasi Fungsi dan Prosedur
## C.1 Database Logic
### C.1.1 Trigger: `kurangi_stok_setelah_disetujui`
| Properti   | Nilai                                                 |
|------------|-------------------------------------------------------|
| Tipe       | AFTER UPDATE pada tabel `peminjaman`                  |
| Kondisi    | Status berubah menjadi `'disetujui'`                  |
| Aksi       | `UPDATE alat SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id` |
```sql
CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
        UPDATE alat SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END
```
### C.1.2 Trigger: `tambah_stok_setelah_dikembalikan`
| Properti   | Nilai                                                 |
|------------|-------------------------------------------------------|
| Tipe       | AFTER UPDATE pada tabel `peminjaman`                  |
| Kondisi    | Status berubah menjadi `'dikembalikan'`               |
| Aksi       | `UPDATE alat SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id` |
```sql
CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
        UPDATE alat SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END
```
### C.1.3 Function: `hitung_denda`
| Properti | Nilai                                                          |
|----------|----------------------------------------------------------------|
| Input    | `tanggal_wajib` (DATE), `tanggal_kembali` (DATE)              |
| Output   | DECIMAL(10,2) — nominal denda                                 |
| Logika   | Jika terlambat: denda = selisih hari × Rp 5.000. Jika tidak: 0 |
```sql
CREATE FUNCTION hitung_denda(tanggal_wajib DATE, tanggal_kembali DATE)
RETURNS DECIMAL(10,2)
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
### C.1.4 Stored Procedure: `proses_persetujuan_peminjaman`
| Properti | Nilai                                                          |
|----------|----------------------------------------------------------------|
| Input    | `id_peminjaman` (INT), `id_petugas` (INT)                      |
| Aksi     | 1. Update status → `disetujui`  2. Catat log aktivitas         |
| Fitur    | Menggunakan TRANSACTION (COMMIT/ROLLBACK)                      |
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
### C.1.5 Stored Procedure: `proses_pengembalian`
| Properti | Nilai                                                          |
|----------|----------------------------------------------------------------|
| Input    | `id_peminjaman` (INT), `id_petugas` (INT)                      |
| Aksi     | 1. Hitung denda via `hitung_denda()`  2. Update status → `dikembalikan`  3. Catat log |
| Fitur    | Menggunakan TRANSACTION (COMMIT/ROLLBACK)                      |
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
    SET status = "dikembalikan", tanggal_kembali = CURDATE(), denda = nominal_denda
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
## C.2 Controller
### C.2.1 AuthController (3 method)
**File:** `app/Http/Controllers/AuthController.php`
| Method            | HTTP | Fungsi                                          |
|-------------------|------|-------------------------------------------------|
| `showLoginForm()` | GET  | Menampilkan halaman form login                  |
| `login()`         | POST | Proses autentikasi + redirect sesuai peran      |
| `logout()`        | POST | Logout, invalidasi session, regenerasi token    |
**Kode `login()`:**
```php
public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        $peran = Auth::user()->peran;
        if ($peran === 'admin') return redirect()->intended('/admin/dashboard');
        elseif ($peran === 'petugas') return redirect()->intended('/petugas/dashboard');
        else return redirect()->intended('/peminjam/dashboard');
    }
    return back()->withErrors([
        'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
    ])->onlyInput('email');
}
```
### C.2.2 PenggunaController (6 method — Admin)
**File:** `app/Http/Controllers/PenggunaController.php`
| Method      | HTTP   | Input                                       | Output                          |
|-------------|--------|---------------------------------------------|---------------------------------|
| `index()`   | GET    | —                                           | Daftar pengguna (paginasi 10)   |
| `create()`  | GET    | —                                           | Form tambah pengguna            |
| `store()`   | POST   | nama, email, kata_sandi, peran              | Simpan + redirect               |
| `edit()`    | GET    | ID pengguna                                 | Form edit pengguna              |
| `update()`  | PUT    | nama, email, kata_sandi (nullable), peran   | Update + redirect               |
| `destroy()` | DELETE | ID pengguna                                 | Hapus + redirect                |
### C.2.3 KategoriController (6 method — Admin)
**File:** `app/Http/Controllers/KategoriController.php`
| Method      | HTTP   | Input           | Output                         |
|-------------|--------|-----------------|--------------------------------|
| `index()`   | GET    | —               | Daftar kategori (paginasi 10)  |
| `create()`  | GET    | —               | Form tambah kategori           |
| `store()`   | POST   | nama_kategori   | Simpan + redirect              |
| `edit()`    | GET    | ID kategori     | Form edit kategori             |
| `update()`  | PUT    | nama_kategori   | Update + redirect              |
| `destroy()` | DELETE | ID kategori     | Hapus + redirect               |
### C.2.4 AlatController (6 method — Admin)
**File:** `app/Http/Controllers/AlatController.php`
| Method      | HTTP   | Input                                         | Output                        |
|-------------|--------|-----------------------------------------------|-------------------------------|
| `index()`   | GET    | —                                             | Daftar alat + kategori        |
| `create()`  | GET    | —                                             | Form tambah alat              |
| `store()`   | POST   | nama_alat, kategori_id, stok, deskripsi, gambar | Simpan + upload gambar      |
| `edit()`    | GET    | ID alat                                       | Form edit alat                |
| `update()`  | PUT    | nama_alat, kategori_id, stok, deskripsi, gambar | Update + ganti gambar       |
| `destroy()` | DELETE | ID alat                                       | Hapus + hapus file gambar     |
### C.2.5 PeminjamanController (9 method)
**File:** `app/Http/Controllers/PeminjamanController.php`
| Method             | HTTP | Peran         | Fungsi                                             |
|--------------------|------|---------------|----------------------------------------------------|
| `katalog()`        | GET  | Peminjam      | Tampilkan katalog alat (stok > 0, filter kategori) |
| `store()`          | POST | Peminjam      | Ajukan peminjaman baru (validasi jumlah & stok)    |
| `peminjamanSaya()` | GET  | Peminjam      | Riwayat peminjaman milik user login                |
| `requestReturn()`  | POST | Peminjam      | Ajukan pengembalian (status → sedang_dikembalikan) |
| `adminIndex()`     | GET  | Admin         | Lihat semua peminjaman                             |
| `index()`          | GET  | Petugas       | Lihat semua peminjaman                             |
| `laporan()`        | GET  | Petugas       | Cetak laporan peminjaman                           |
| `approve()`        | POST | Admin/Petugas | Setujui via stored procedure + trigger stok        |
| `reject()`         | POST | Admin/Petugas | Tolak peminjaman (stok tidak berubah)              |
| `returnTool()`     | POST | Admin/Petugas | Konfirmasi pengembalian via stored procedure       |
**Kode `approve()` — Proses Persetujuan:**
```php
public function approve(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'diajukan') {
        return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
    }
    if ($peminjaman->alat->stok < $peminjaman->jumlah) {
        return back()->with('error', 'Stok alat tidak mencukupi.');
    }
    DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [
        $peminjaman->id, Auth::id()
    ]);
    return back()->with('success', 'Peminjaman disetujui. Stok diperbarui oleh sistem.');
}
```
**Kode `returnTool()` — Proses Pengembalian:**
```php
public function returnTool(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
        return back()->with('error', 'Peminjaman tidak dalam status dapat dikembalikan.');
    }
    DB::statement('CALL proses_pengembalian(?, ?)', [
        $peminjaman->id, Auth::id()
    ]);
    $peminjaman->refresh();
    return back()->with('success', "Alat dikembalikan. Denda: Rp " . number_format($peminjaman->denda));
}
```
### C.2.6 LogAktivitasController (1 method — Admin)
**File:** `app/Http/Controllers/LogAktivitasController.php`
| Method    | HTTP | Fungsi                                        |
|-----------|------|-----------------------------------------------|
| `index()` | GET  | Tampilkan log aktivitas (paginasi 20)         |
## C.3 Middleware
### RoleMiddleware
**File:** `app/Http/Middleware/RoleMiddleware.php`
| Fungsi | Keterangan                                                                |
|--------|---------------------------------------------------------------------------|
| Input  | Request + daftar peran yang diizinkan                                     |
| Proses | 1. Cek login  2. Cek peran sesuai  3. Redirect jika tidak berhak         |
| Output | Lanjut request ATAU redirect ke dashboard masing-masing                   |
```php
public function handle(Request $request, Closure $next, ...$roles): Response
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    if (in_array($user->peran, $roles)) {
        return $next($request);
    }
    // Redirect ke dashboard sesuai peran
    if ($user->peran === 'admin') return redirect()->route('admin.dashboard');
    elseif ($user->peran === 'petugas') return redirect()->route('petugas.dashboard');
    else return redirect()->route('peminjam.dashboard');
}
```
## C.4 Model Eloquent
| No | Model          | Tabel            | Relasi                                    |
|----|----------------|------------------|-------------------------------------------|
| 1  | `Pengguna`     | `pengguna`       | hasMany Peminjaman, hasMany LogAktivitas  |
| 2  | `Kategori`     | `kategori`       | hasMany Alat                              |
| 3  | `Alat`         | `alat`           | belongsTo Kategori, hasMany Peminjaman    |
| 4  | `Peminjaman`   | `peminjaman`     | belongsTo Pengguna, belongsTo Alat        |
| 5  | `LogAktivitas` | `log_aktivitas`  | belongsTo Pengguna                        |
> Model `Pengguna` menggunakan override `getAuthPassword()` karena kolom password bernama `kata_sandi` (bukan default `password`).
---
# D. Debugging
## D.1 Bug yang Ditemukan dan Diperbaiki
### Bug 1: Gambar alat tidak tampil
| Aspek     | Detail                                                              |
|-----------|---------------------------------------------------------------------|
| Gejala    | Gambar alat tidak muncul di halaman, menampilkan icon broken image  |
| Penyebab  | Symbolic link `public/storage` → `storage/app/public` belum dibuat |
| Solusi    | Jalankan `php artisan storage:link`                                 |
| Status    | ✅ Diperbaiki                                                       |
### Bug 2: Stok tidak sinkron setelah seeding
| Aspek     | Detail                                                              |
|-----------|---------------------------------------------------------------------|
| Gejala    | Stok alat tidak berkurang meski ada peminjaman aktif setelah seeding |
| Penyebab  | `DB::table()->insert()` bypass trigger (trigger hanya aktif saat UPDATE) |
| Solusi    | Tambahkan loop `decrement` manual di DatabaseSeeder untuk peminjaman yang statusnya `disetujui` |
| Status    | ✅ Diperbaiki                                                       |
### Bug 3: Login gagal meski password benar
| Aspek     | Detail                                                              |
|-----------|---------------------------------------------------------------------|
| Gejala    | Semua user tidak bisa login, selalu muncul error "Kredensial tidak cocok" |
| Penyebab  | Laravel default mencari kolom `password`, tabel menggunakan `kata_sandi` |
| Solusi    | Override method `getAuthPassword()` di model `Pengguna` untuk return `$this->kata_sandi` |
| Status    | ✅ Diperbaiki                                                       |
### Bug 4: Error saat hapus kategori yang punya alat
| Aspek     | Detail                                                              |
|-----------|---------------------------------------------------------------------|
| Gejala    | Error foreign key constraint saat menghapus kategori yang masih memiliki alat |
| Penyebab  | Tidak ada pengecekan relasi sebelum proses delete                   |
| Solusi    | Tambahkan cek `$kategori->alat()->count()` sebelum delete           |
| Status    | ✅ Diperbaiki                                                       |
### Bug 5: Stok bisa negatif saat persetujuan
| Aspek     | Detail                                                              |
|-----------|---------------------------------------------------------------------|
| Gejala    | Petugas bisa menyetujui peminjaman meskipun stok alat kurang dari jumlah yang diminta |
| Penyebab  | Tidak ada validasi stok sebelum memanggil stored procedure          |
| Solusi    | Tambahkan cek `$peminjaman->alat->stok < $peminjaman->jumlah` di controller sebelum approve |
| Status    | ✅ Diperbaiki                                                       |
## D.2 Bug Minor Belum Diperbaiki
| No | Bug                                       | Severity   | Dampak                                         |
|----|-------------------------------------------|------------|-------------------------------------------------|
| 1  | Tidak ada konfirmasi sebelum hapus data   | 🟡 Minor   | User bisa tidak sengaja menghapus data          |
| 2  | Tidak ada fitur pencarian/search          | 🟡 Minor   | Sulit mencari data jika jumlah besar            |
| 3  | Password baru tidak perlu konfirmasi ulang| 🟡 Minor   | Risiko typo saat ganti password                 |
| 4  | Gambar lama tidak terhapus saat edit gagal| 🟢 Trivial | Potensi file orphan di storage                  |
| 5  | Tidak ada notifikasi real-time            | 🟢 Trivial | Peminjam harus refresh untuk cek status         |
> Tidak ditemukan bug **critical** yang mengganggu fungsi utama aplikasi.
---
# E. Pengujian dan Tangkapan Layar Hasil Uji
## E.1 Skenario Pengujian (Black Box Testing)
### A. Login User (5 skenario)
**A1: Login dengan email dan password SALAH**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Email: `salah@email.com` · Password: `wrongpass`          |
| Proses     | Sistem mencari email di database → tidak ditemukan        |
| Hasil      | ❌ Gagal login — Muncul: *"Kredensial tidak cocok"*       |
| Status     | ✅ PASSED — Sistem menolak akses dengan benar              |
**A2: Login dengan email BENAR tapi password SALAH**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Email: `admin@admin.com` · Password: `wrongpassword`      |
| Proses     | Email ditemukan → hash bcrypt tidak cocok                 |
| Hasil      | ❌ Gagal login — Muncul: *"Kredensial tidak cocok"*       |
| Status     | ✅ PASSED                                                  |
**A3: Login sebagai Admin**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Email: `admin@admin.com` · Password: `password`           |
| Hasil      | ✅ Berhasil — Redirect ke `/admin/dashboard`              |
| Status     | ✅ PASSED                                                  |
**A4: Login sebagai Petugas**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Email: `budi.petugas@sekolah.com` · Password: `password`  |
| Hasil      | ✅ Berhasil — Redirect ke `/petugas/dashboard`            |
| Status     | ✅ PASSED                                                  |
**A5: Login sebagai Peminjam**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Email: `andi.pratama@siswa.com` · Password: `password`    |
| Hasil      | ✅ Berhasil — Redirect ke `/peminjam/dashboard`           |
| Status     | ✅ PASSED                                                  |
### B. Tambah Alat (5 skenario)
**B1: Tambah alat data lengkap** → ✅ PASSED (tersimpan di database)
**B2: Tambah alat tanpa nama** → ✅ PASSED (validasi `required` menolak)
**B3: Tambah alat stok negatif** → ✅ PASSED (validasi `min:0` menolak)
**B4: Tambah alat kategori tidak valid** → ✅ PASSED (validasi `exists` menolak)
**B5: Edit alat yang sudah ada** → ✅ PASSED (data berhasil diperbarui)
### C. Pinjam Alat (5 skenario)
**C1: Lihat katalog alat** → ✅ PASSED (menampilkan alat dengan stok > 0)
**C2: Ajukan peminjaman alat**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Input      | Peminjam: Andi Pratama · Alat: Mikroskop · Jumlah: 2 · Durasi: 3 hari |
| Hasil      | ✅ Berhasil — Status = `diajukan`, tanggal wajib kembali dihitung otomatis |
| Status     | ✅ PASSED                                                  |
**C3: Petugas setujui peminjaman (Stored Procedure + Trigger)**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Proses     | `CALL proses_persetujuan_peminjaman(id, petugas_id)` → trigger `kurangi_stok` |
| Hasil      | ✅ 3 hal terjadi otomatis: 1. Status → disetujui  2. Stok berkurang sesuai jumlah  3. Log tercatat |
| Status     | ✅ PASSED                                                  |
**C4: Petugas tolak peminjaman** → ✅ PASSED (status → ditolak, stok tetap)
**C5: Pinjam alat stok habis** → ✅ PASSED (sistem menolak: "Stok tidak mencukupi")
### D. Kembalikan Alat & Denda (4 skenario)
**D1: Peminjam ajukan pengembalian** → ✅ PASSED (status → sedang_dikembalikan)
**D2: Petugas konfirmasi pengembalian TERLAMBAT**
| Aspek      | Detail                                                    |
|------------|-----------------------------------------------------------|
| Proses     | `CALL proses_pengembalian()` → `hitung_denda()` → trigger `tambah_stok` |
| Hasil      | ✅ 4 hal otomatis: 1. Status → dikembalikan  2. Denda dihitung (hari × Rp 5.000)  3. Tanggal kembali tercatat  4. Log tercatat |
| Status     | ✅ PASSED                                                  |
**D3: Pengembalian tepat waktu** → ✅ PASSED (denda = Rp 0)
**D4: Test function `hitung_denda()` langsung**
| Skenario          | Input                                | Output      | Status |
|-------------------|--------------------------------------|-------------|--------|
| Tepat waktu       | `hitung_denda('2026-02-20', '2026-02-20')` | `0.00`    | ✅     |
| Terlambat 5 hari  | `hitung_denda('2026-02-20', '2026-02-25')` | `25000.00`| ✅     |
| Lebih awal 2 hari | `hitung_denda('2026-02-20', '2026-02-18')` | `0.00`    | ✅     |
### E. Cek Privilege User (5 skenario)
**E1: Admin akses semua halaman admin** → ✅ PASSED
**E2: Peminjam TIDAK bisa akses halaman admin** → ✅ PASSED (redirect ke `/peminjam/dashboard`)
**E3: Petugas TIDAK bisa akses halaman admin** → ✅ PASSED (redirect ke `/petugas/dashboard`)
**E4: Petugas bisa akses halaman peminjaman** → ✅ PASSED
**E5: User tanpa login redirect ke login** → ✅ PASSED
## E.2 Ringkasan Hasil Pengujian
| No | Kategori                | Skenario | Passed | Failed | Status          |
|----|-------------------------|----------|--------|--------|-----------------|
| A  | Login User              | 5        | 5      | 0      | ✅ ALL PASSED   |
| B  | Tambah Alat             | 5        | 5      | 0      | ✅ ALL PASSED   |
| C  | Pinjam Alat             | 5        | 5      | 0      | ✅ ALL PASSED   |
| D  | Kembalikan Alat & Denda | 4        | 4      | 0      | ✅ ALL PASSED   |
| E  | Cek Privilege User      | 5        | 5      | 0      | ✅ ALL PASSED   |
|    | **TOTAL**               | **24**   | **24** | **0**  | **✅ 100%**     |
## E.3 Komponen Database yang Diuji
| No | Komponen           | Nama                                   | Hasil         |
|----|--------------------|----------------------------------------|---------------|
| 1  | Stored Procedure   | `proses_persetujuan_peminjaman()`      | ✅ Berfungsi  |
| 2  | Stored Procedure   | `proses_pengembalian()`                | ✅ Berfungsi  |
| 3  | Function           | `hitung_denda()`                       | ✅ Berfungsi  |
| 4  | Trigger            | `kurangi_stok_setelah_disetujui`       | ✅ Berfungsi  |
| 5  | Trigger            | `tambah_stok_setelah_dikembalikan`     | ✅ Berfungsi  |
| 6  | COMMIT             | Dalam kedua stored procedure           | ✅ Berfungsi  |
| 7  | ROLLBACK           | Handler error dalam stored procedure   | ✅ Terdefinisi|
## E.4 Tangkapan Layar yang Perlu Diambil
Simpan screenshot ke folder `docs/screenshots/`:
| No | Halaman                  | Cara Mengakses                          | Nama File                  |
|----|--------------------------|-----------------------------------------|----------------------------|
| 1  | Halaman Login            | Buka http://localhost:8000              | `01_login.png`             |
| 2  | Login Gagal              | Login dengan email/password salah       | `02_login_gagal.png`       |
| 3  | Dashboard Admin          | Login admin@admin.com / password        | `03_dashboard_admin.png`   |
| 4  | Daftar Pengguna          | Menu → Pengguna                         | `04_daftar_pengguna.png`   |
| 5  | Daftar Alat              | Menu → Alat                             | `05_daftar_alat.png`       |
| 6  | Daftar Peminjaman        | Menu → Peminjaman                       | `06_daftar_peminjaman.png` |
| 7  | Log Aktivitas            | Menu → Log Aktivitas                    | `07_log_aktivitas.png`     |
| 8  | Dashboard Peminjam       | Login andi.pratama@siswa.com / password | `08_dashboard_peminjam.png`|
| 9  | Katalog Alat             | Menu → Katalog Alat                     | `09_katalog_alat.png`      |
| 10 | Riwayat Peminjaman       | Menu → Riwayat Peminjaman               | `10_riwayat_peminjaman.png`|
---
*Dokumen ini merupakan dokumentasi program untuk pengumpulan UKK*
*Aplikasi Peminjaman Alat Sekolah — April 2026*
