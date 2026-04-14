# LAPORAN PROYEK APLIKASI PEMINJAMAN ALAT SEKOLAH
## Menggunakan Metode Waterfall

---

## DAFTAR ISI

- [A. Analisis Kebutuhan](#a-analisis-kebutuhan)
- [B. Desain (ERD dan Diagram Program)](#b-desain-erd-dan-diagram-program)
- [C. Implementasi Kode](#c-implementasi-kode)
- [D. Pengujian](#d-pengujian)
- [E. Dokumentasi](#e-dokumentasi)

---

## Pendahuluan

Laporan ini menyajikan dokumentasi lengkap pengembangan **Aplikasi Peminjaman Alat Sekolah** menggunakan metode **Waterfall**. Metode Waterfall adalah model pengembangan perangkat lunak secara sekuensial (berurutan) yang terdiri dari beberapa tahap yang saling bergantung.

### Diagram Metode Waterfall

```
┌───────────────────┐
│  A. Analisis      │
│     Kebutuhan     │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  B. Desain        │
│     (ERD & Diagram│
│      Program)     │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  C. Implementasi  │
│     Kode          │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  D. Pengujian     │
│                   │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  E. Dokumentasi   │
│                   │
└───────────────────┘
```

---

# A. Analisis Kebutuhan

## A.1 Latar Belakang

Sekolah memiliki berbagai alat dan peralatan yang digunakan untuk kegiatan belajar-mengajar, seperti alat laboratorium, alat olahraga, peralatan komputer, alat musik, dan lain-lain. Proses peminjaman alat yang masih dilakukan secara manual (buku catatan) menimbulkan beberapa masalah:

1. **Pencatatan tidak terstruktur** — Data peminjaman mudah hilang atau tertukar
2. **Stok sulit dipantau** — Tidak ada informasi real-time tentang ketersediaan alat
3. **Proses lambat** — Persetujuan peminjaman memerlukan waktu karena harus menghubungi petugas
4. **Tidak ada riwayat** — Sulit melacak siapa yang meminjam alat dan kapan dikembalikan
5. **Perhitungan denda manual** — Rentan kesalahan dalam menghitung denda keterlambatan

## A.2 Tujuan Aplikasi

| No | Tujuan                                                                 |
|----|------------------------------------------------------------------------|
| 1  | Memudahkan pengelolaan data alat dan peminjaman secara digital          |
| 2  | Menyediakan sistem manajemen stok otomatis                              |
| 3  | Mempercepat proses persetujuan dan penolakan peminjaman                 |
| 4  | Menghitung denda keterlambatan secara otomatis                          |
| 5  | Menyediakan riwayat dan laporan peminjaman yang lengkap                 |
| 6  | Memberikan hak akses berbeda sesuai peran pengguna                      |

## A.3 Kebutuhan Fungsional

### A.3.1 Kebutuhan Fungsional Admin

| Kode  | Kebutuhan                                                            |
|-------|----------------------------------------------------------------------|
| FR-01 | Admin dapat login dan logout dari sistem                              |
| FR-02 | Admin dapat mengelola data pengguna (CRUD: tambah, lihat, edit, hapus)|
| FR-03 | Admin dapat mengelola data kategori alat (CRUD)                       |
| FR-04 | Admin dapat mengelola data alat (CRUD), termasuk upload gambar        |
| FR-05 | Admin dapat melihat seluruh data peminjaman                           |
| FR-06 | Admin dapat menyetujui atau menolak pengajuan peminjaman              |
| FR-07 | Admin dapat mengkonfirmasi pengembalian alat                          |
| FR-08 | Admin dapat melihat log aktivitas seluruh pengguna                    |

### A.3.2 Kebutuhan Fungsional Petugas

| Kode  | Kebutuhan                                                            |
|-------|----------------------------------------------------------------------|
| FR-09 | Petugas dapat login dan logout dari sistem                            |
| FR-10 | Petugas dapat melihat seluruh data peminjaman                         |
| FR-11 | Petugas dapat menyetujui atau menolak pengajuan peminjaman            |
| FR-12 | Petugas dapat mengkonfirmasi pengembalian alat                        |
| FR-13 | Petugas dapat mencetak/melihat laporan peminjaman                     |

### A.3.3 Kebutuhan Fungsional Peminjam

| Kode  | Kebutuhan                                                            |
|-------|----------------------------------------------------------------------|
| FR-14 | Peminjam dapat login dan logout dari sistem                           |
| FR-15 | Peminjam dapat melihat katalog alat yang tersedia                     |
| FR-16 | Peminjam dapat memfilter alat berdasarkan kategori                    |
| FR-17 | Peminjam dapat mengajukan peminjaman alat                             |
| FR-18 | Peminjam dapat melihat riwayat peminjaman sendiri                     |
| FR-19 | Peminjam dapat mengajukan pengembalian alat                           |

## A.4 Kebutuhan Non-Fungsional

| Kode   | Kebutuhan                                                           |
|--------|---------------------------------------------------------------------|
| NFR-01 | Sistem harus responsif dan dapat diakses dari berbagai perangkat     |
| NFR-02 | Kata sandi pengguna harus disimpan dalam bentuk hash (bcrypt)        |
| NFR-03 | Sistem harus memiliki hak akses berbasis peran (RBAC)                |
| NFR-04 | Stok alat harus diperbarui secara otomatis menggunakan trigger       |
| NFR-05 | Denda harus dihitung otomatis menggunakan fungsi database            |
| NFR-06 | Proses persetujuan dan pengembalian harus bersifat transaksional     |
| NFR-07 | Sistem harus mendukung paginasi untuk data yang banyak               |

## A.5 Aktor / Pengguna Sistem

| No | Aktor     | Deskripsi                                                         |
|----|-----------|-------------------------------------------------------------------|
| 1  | Admin     | Mengelola seluruh data master (pengguna, kategori, alat) dan log  |
| 2  | Petugas   | Memproses peminjaman (approve/reject/return) dan cetak laporan    |
| 3  | Peminjam  | Melihat katalog, mengajukan peminjaman, dan mengajukan pengembalian|

## A.6 Kebutuhan Perangkat

### Perangkat Lunak (Software)

| Komponen       | Spesifikasi                     |
|----------------|---------------------------------|
| Bahasa Program | PHP 8.x                        |
| Framework      | Laravel 11                      |
| Database       | MySQL 8.0                       |
| Frontend       | Blade Template, Tailwind CSS    |
| Build Tool     | Vite, Node.js                   |
| Web Server     | Apache / Nginx / Built-in PHP   |
| OS             | Windows / Linux / macOS         |
| Browser        | Chrome, Firefox, Edge (modern)  |

### Perangkat Keras (Hardware)  — Minimum

| Komponen  | Spesifikasi Minimum        |
|-----------|----------------------------|
| Processor | Intel Core i3 / setara     |
| RAM       | 4 GB                       |
| Storage   | 500 MB (aplikasi)          |
| Koneksi   | LAN / Internet             |

---

# B. Desain (ERD dan Diagram Program)

## B.1 Entity Relationship Diagram (ERD)

### B.1.1 Diagram ERD

```
┌────────────────────────┐          ┌──────────────────────┐
│       PENGGUNA         │          │      KATEGORI        │
│────────────────────────│          │──────────────────────│
│ *id          : BIGINT  │          │ *id       : BIGINT   │
│  nama        : VARCHAR │          │  nama_    : VARCHAR  │
│  email       : VARCHAR │──┐       │  kategori            │
│  kata_sandi  : VARCHAR │  │       │  created_at: TIMESTAMP│
│  peran       : ENUM    │  │       │  updated_at: TIMESTAMP│
│  created_at  : TIMESTAMP│ │       └──────────┬───────────┘
│  updated_at  : TIMESTAMP│ │                  │
└──────────┬─────────────┘  │                  │ 1
           │                │                  │
     1     │                │                  │ hasMany
           │                │                  │
           │ hasMany        │                  ▼ *
           │                │       ┌──────────────────────┐
           ▼ *              │       │        ALAT          │
┌────────────────────────┐  │       │──────────────────────│
│    LOG_AKTIVITAS       │  │       │ *id         : BIGINT │
│────────────────────────│  │       │  kategori_id: BIGINT │──→ FK ke KATEGORI
│ *id          : BIGINT  │  │       │  nama_alat  : VARCHAR│
│  pengguna_id : BIGINT  │──┘       │  deskripsi  : TEXT   │
│  aksi        : VARCHAR │  │       │  stok       : INT    │
│  deskripsi   : TEXT    │  │       │  gambar     : VARCHAR│
│  created_at  : TIMESTAMP│ │       │  created_at : TIMESTAMP│
│  updated_at  : TIMESTAMP│ │       │  updated_at : TIMESTAMP│
└────────────────────────┘  │       └──────────┬───────────┘
                            │                  │ 1
    FK ke PENGGUNA ─────────┘                  │
    (ON DELETE CASCADE)                        │ hasMany
                                               │
                            ┌──────────────────▼────────────┐
                            │         PEMINJAMAN            │
                            │───────────────────────────────│
                            │ *id                  : BIGINT │
                            │  pengguna_id         : BIGINT │──→ FK ke PENGGUNA
                            │  alat_id             : BIGINT │──→ FK ke ALAT
                            │  tanggal_pinjam      : DATE   │
                            │  tanggal_wajib_kembali: DATE  │
                            │  tanggal_kembali     : DATE   │
                            │  status              : ENUM   │
                            │  denda               : DECIMAL│
                            │  created_at          : TIMESTAMP│
                            │  updated_at          : TIMESTAMP│
                            └───────────────────────────────┘
```

> **Keterangan:** `*` = Primary Key, FK = Foreign Key

### B.1.2 Deskripsi Relasi

| No | Tabel Asal  | Tabel Tujuan   | Kardinalitas | Keterangan                                  |
|----|-------------|----------------|--------------|----------------------------------------------|
| 1  | pengguna    | peminjaman     | 1 : N        | Satu pengguna memiliki banyak peminjaman      |
| 2  | alat        | peminjaman     | 1 : N        | Satu alat memiliki banyak transaksi peminjaman|
| 3  | kategori    | alat           | 1 : N        | Satu kategori memiliki banyak alat            |
| 4  | pengguna    | log_aktivitas  | 1 : N        | Satu pengguna memiliki banyak log (CASCADE)   |

## B.2 Diagram Use Case

```
                          ┌─────────────────────────────────────────────────┐
                          │            SISTEM PEMINJAMAN ALAT               │
                          │                                                 │
  ┌───────┐               │  ┌─────────────────┐  ┌──────────────────┐     │
  │       │───────────────│─→│     Login        │  │     Logout       │     │
  │ Admin │               │  └─────────────────┘  └──────────────────┘     │
  │       │───────────────│─→┌─────────────────┐                            │
  │       │               │  │ Kelola Pengguna │ (CRUD)                     │
  │       │───────────────│─→┌─────────────────┐                            │
  │       │               │  │ Kelola Kategori │ (CRUD)                     │
  │       │───────────────│─→┌─────────────────┐                            │
  │       │               │  │ Kelola Alat     │ (CRUD + Upload Gambar)     │
  │       │───────────────│─→┌─────────────────┐                            │
  │       │               │  │ Kelola Peminj.  │ (Setujui/Tolak/Kembalikan)│
  │       │───────────────│─→┌─────────────────┐                            │
  └───────┘               │  │ Lihat Log Akt.  │                            │
                          │  └─────────────────┘                            │
                          │                                                 │
  ┌───────┐               │  ┌─────────────────┐                            │
  │       │───────────────│─→│ Lihat Peminjaman│                            │
  │Petugas│───────────────│─→│ Setujui/Tolak   │                            │
  │       │───────────────│─→│ Konfirmasi      │                            │
  │       │───────────────│─→│ Pengembalian    │                            │
  │       │───────────────│─→│ Cetak Laporan   │                            │
  └───────┘               │  └─────────────────┘                            │
                          │                                                 │
  ┌───────┐               │  ┌─────────────────┐                            │
  │       │───────────────│─→│ Lihat Katalog   │                            │
  │Peminj.│───────────────│─→│ Ajukan Pinjam   │                            │
  │       │───────────────│─→│ Riwayat Saya    │                            │
  │       │───────────────│─→│ Ajukan Kembali  │                            │
  └───────┘               │  └─────────────────┘                            │
                          └─────────────────────────────────────────────────┘
```

## B.3 Activity Diagram — Proses Peminjaman

```
┌───────────┐   ┌───────────┐   ┌───────────────┐   ┌──────────────┐
│  Peminjam │   │  Petugas/ │   │   Sistem      │   │   Database   │
│           │   │  Admin    │   │   (Laravel)   │   │   (MySQL)    │
└─────┬─────┘   └─────┬─────┘   └──────┬────────┘   └──────┬───────┘
      │               │                │                     │
      │ [START]        │                │                     │
      ▼               │                │                     │
 ┌─────────┐          │                │                     │
 │  Login  │─────────────────────────→ │                     │
 └────┬────┘          │                │  Validasi           │
      │               │                │  Kredensial ───────→│
      │               │                │← ─ ─ ─ ─ ─ ─ ─ ─ ─│
      │               │                │                     │
      ▼               │                │                     │
 ┌──────────┐         │                │                     │
 │  Lihat   │─────────────────────────→│  Query alat        │
 │ Katalog  │         │                │  WHERE stok > 0 ──→│
 └────┬─────┘         │                │← ─ ─ ─ ─ ─ ─ ─ ─ ─│
      │               │                │                     │
      ▼               │                │                     │
 ┌──────────┐         │                │                     │
 │  Pilih   │         │                │                     │
 │  Alat &  │         │                │                     │
 │  Isi Form│─────────────────────────→│  Validasi Form     │
 └────┬─────┘         │                │  INSERT peminjaman  │
      │               │                │  status='diajukan'─→│
      │               │                │← ─ ─ ─ ─ ─ ─ ─ ─ ─│
      │               │                │                     │
      │               ▼                │                     │
      │          ┌──────────┐          │                     │
      │          │  Review  │          │                     │
      │          │ Pengajuan│──────── →│                     │
      │          └────┬─────┘          │                     │
      │               │                │                     │
      │         ┌─────┴──────┐         │                     │
      │         ▼            ▼         │                     │
      │    [Setujui]    [Tolak]        │                     │
      │         │            │         │                     │
      │         ▼            ▼         │                     │
      │    CALL proses_  UPDATE status │                     │
      │    persetujuan   ='ditolak'───→│                     │
      │    peminjaman()────────────── →│                     │
      │         │                      │  ┌─ TRIGGER:        │
      │         │                      │  │ kurangi_stok     │
      │         │                      │  └─────────────────→│
      │         │                      │                     │
      ▼         │                      │                     │
 ┌──────────┐   │                      │                     │
 │  Ajukan  │   │                      │                     │
 │Pengemba- │───────────────────────→  │  UPDATE status      │
 │  lian    │   │                      │  ='sedang_          │
 └────┬─────┘   │                      │  dikembalikan' ───→│
      │         │                      │← ─ ─ ─ ─ ─ ─ ─ ─ ─│
      │         ▼                      │                     │
      │    ┌──────────┐                │                     │
      │    │Konfirmasi│                │                     │
      │    │Pengembali│────────────── →│  CALL proses_       │
      │    │   an     │                │  pengembalian() ──→│
      │    └──────────┘                │                     │
      │                                │  ┌─ FUNCTION:       │
      │                                │  │ hitung_denda()   │
      │                                │  ├─ TRIGGER:        │
      │                                │  │ tambah_stok      │
      │                                │  └─────────────────→│
      │                                │                     │
      ▼                                │                     │
   [END]                               │                     │
```

## B.4 Diagram Alur Status Peminjaman

```
          ┌─────────────────────────────────────────────────────────────┐
          │                                                             │
          │   ┌──────────┐                                              │
          │   │          │   Peminjam mengajukan                        │
          │   │  START   │   peminjaman baru                            │
          │   │          │                                              │
          │   └────┬─────┘                                              │
          │        │                                                    │
          │        ▼                                                    │
          │   ┌──────────┐                                              │
          │   │          │                                              │
          │   │ DIAJUKAN │───────────────────────────────────────┐      │
          │   │          │                                       │      │
          │   └────┬─────┘                                       │      │
          │        │                                             │      │
          │   Petugas/Admin                                 Petugas/    │
          │   menyetujui                                    Admin      │
          │        │                                        menolak    │
          │        ▼                                             │      │
          │   ┌──────────┐       Trigger:                        │      │
          │   │          │     kurangi_stok                       │      │
          │   │DISETUJUI │     stok = stok - 1               ┌───▼────┐│
          │   │          │                                    │        ││
          │   └────┬─────┘                                    │DITOLAK ││
          │        │                                          │        ││
          │   Peminjam                                        └───┬────┘│
          │   mengajukan                                          │     │
          │   pengembalian                                        ▼     │
          │        │                                           [END]    │
          │        ▼                                                    │
          │   ┌──────────────┐                                          │
          │   │   SEDANG     │                                          │
          │   │DIKEMBALIKAN  │                                          │
          │   │              │                                          │
          │   └──────┬───────┘                                          │
          │          │                                                  │
          │     Petugas/Admin                                           │
          │     konfirmasi                                              │
          │          │                                                  │
          │          ▼                                                  │
          │   ┌──────────────┐    Trigger:           Function:          │
          │   │              │    tambah_stok         hitung_denda()    │
          │   │ DIKEMBALIKAN │    stok = stok + 1     denda = hari ×    │
          │   │              │                        Rp 5.000          │
          │   └──────┬───────┘                                          │
          │          │                                                  │
          │          ▼                                                  │
          │       [END]                                                 │
          │                                                             │
          └─────────────────────────────────────────────────────────────┘
```

## B.5 Desain Antarmuka (Sitemap)

```
                          ┌──────────────┐
                          │  Halaman     │
                          │  Login       │
                          └──────┬───────┘
                                 │
              ┌──────────────────┼──────────────────┐
              ▼                  ▼                  ▼
     ┌────────────────┐ ┌────────────────┐ ┌────────────────┐
     │ Admin Dashboard│ │Petugas Dashboard│ │Peminjam Dashb. │
     └───────┬────────┘ └───────┬────────┘ └───────┬────────┘
             │                  │                   │
    ┌────┬───┼───┬─────┐   ┌───┼───┐          ┌────┼────┐
    ▼    ▼   ▼   ▼     ▼   ▼   ▼   ▼          ▼    ▼    ▼
  Peng- Kate-Alat Pem- Log Pem- Lap-        Kata- Pemi- Riwayat
  guna  gori     inj.  Akt inj. oran        log   njam  Saya
  (CRUD)(CRUD)(CRUD)         (Kelola)        Alat  Baru
```

### Daftar Halaman / View

| No | Halaman                      | File View                               | Peran     |
|----|------------------------------|------------------------------------------|-----------|
| 1  | Login                        | `auth/login.blade.php`                  | Guest     |
| 2  | Dashboard Admin              | `admin/dashboard.blade.php`             | Admin     |
| 3  | Daftar Pengguna              | `admin/pengguna/index.blade.php`        | Admin     |
| 4  | Tambah Pengguna              | `admin/pengguna/create.blade.php`       | Admin     |
| 5  | Edit Pengguna                | `admin/pengguna/edit.blade.php`         | Admin     |
| 6  | Daftar Kategori              | `admin/kategori/index.blade.php`        | Admin     |
| 7  | Tambah Kategori              | `admin/kategori/create.blade.php`       | Admin     |
| 8  | Edit Kategori                | `admin/kategori/edit.blade.php`         | Admin     |
| 9  | Daftar Alat                  | `admin/alat/index.blade.php`            | Admin     |
| 10 | Tambah Alat                  | `admin/alat/create.blade.php`           | Admin     |
| 11 | Edit Alat                    | `admin/alat/edit.blade.php`             | Admin     |
| 12 | Daftar Peminjaman (Admin)    | `admin/peminjaman/index.blade.php`      | Admin     |
| 13 | Log Aktivitas                | `admin/log_aktivitas/index.blade.php`   | Admin     |
| 14 | Dashboard Petugas            | `petugas/dashboard.blade.php`           | Petugas   |
| 15 | Daftar Peminjaman (Petugas)  | `petugas/peminjaman/index.blade.php`    | Petugas   |
| 16 | Laporan Peminjaman           | `petugas/laporan.blade.php`             | Petugas   |
| 17 | Dashboard Peminjam           | `peminjam/dashboard.blade.php`          | Peminjam  |
| 18 | Katalog Alat                 | `peminjam/alat/index.blade.php`         | Peminjam  |
| 19 | Riwayat Peminjaman Saya      | `peminjam/peminjaman/index.blade.php`   | Peminjam  |
| 20 | Layout Utama                 | `layouts/app.blade.php`                 | Semua     |
| 21 | Custom Pagination            | `vendor/pagination/custom.blade.php`    | Semua     |

---

# C. Implementasi Kode

## C.1 Struktur Direktori Proyek

```
peminjaman/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php          ← Autentikasi (login/logout)
│   │   │   ├── PenggunaController.php      ← CRUD data pengguna
│   │   │   ├── KategoriController.php      ← CRUD data kategori
│   │   │   ├── AlatController.php          ← CRUD data alat
│   │   │   ├── PeminjamanController.php    ← Proses peminjaman
│   │   │   └── LogAktivitasController.php  ← Tampilan log aktivitas
│   │   └── Middleware/
│   │       └── RoleMiddleware.php          ← Kontrol hak akses peran
│   └── Models/
│       ├── Pengguna.php                    ← Model pengguna
│       ├── Kategori.php                    ← Model kategori
│       ├── Alat.php                        ← Model alat
│       ├── Peminjaman.php                  ← Model peminjaman
│       └── LogAktivitas.php               ← Model log aktivitas
├── database/
│   ├── migrations/
│   │   ├── ..._buat_tabel_pengguna.php     ← Migrasi tabel pengguna
│   │   ├── ..._buat_tabel_kategori.php     ← Migrasi tabel kategori
│   │   ├── ..._buat_tabel_alat.php         ← Migrasi tabel alat
│   │   ├── ..._buat_tabel_peminjaman.php   ← Migrasi tabel peminjaman
│   │   ├── ..._buat_tabel_log_aktivitas.php← Migrasi tabel log
│   │   └── ..._buat_trigger_dan_prosedur.php ← Trigger, fungsi, prosedur
│   └── seeders/
│       └── DatabaseSeeder.php              ← Data dummy untuk testing
├── resources/views/
│   ├── admin/          ← 9 halaman admin
│   ├── petugas/        ← 3 halaman petugas
│   ├── peminjam/       ← 3 halaman peminjam
│   ├── auth/           ← 1 halaman login
│   └── layouts/        ← 1 layout utama
├── routes/
│   └── web.php                             ← Definisi semua route
└── public/                                 ← Asset publik (CSS, JS, gambar)
```

## C.2 Implementasi Model (Eloquent ORM)

### Model `Pengguna` — Autentikasi Pengguna

```php
class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';
    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran'];
    protected $hidden = ['password', 'remember_token'];

    public function getAuthPassword()
    {
        return $this->kata_sandi; // Custom password field
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'kata_sandi' => 'hashed', // Auto hash pada mass assignment
        ];
    }
}
```

> **Penjelasan:** Model ini meng-extend `Authenticatable` agar dapat digunakan oleh sistem autentikasi Laravel. Method `getAuthPassword()` di-override karena menggunakan kolom `kata_sandi` bukan `password`.

### Model `Alat` — Data Peralatan

```php
class Alat extends Model
{
    protected $table = 'alat';
    protected $fillable = ['kategori_id', 'nama_alat', 'deskripsi', 'stok', 'gambar'];

    public function kategori() {
        return $this->belongsTo(Kategori::class); // Alat milik 1 kategori
    }

    public function peminjaman() {
        return $this->hasMany(Peminjaman::class); // Alat punya banyak peminjaman
    }
}
```

### Model `Peminjaman` — Transaksi Peminjaman

```php
class Peminjaman extends Model
{
    protected $table = 'peminjaman';
    protected $fillable = [
        'pengguna_id', 'alat_id', 'tanggal_pinjam',
        'tanggal_wajib_kembali', 'tanggal_kembali', 'status', 'denda'
    ];

    public function pengguna() {
        return $this->belongsTo(Pengguna::class);
    }

    public function alat() {
        return $this->belongsTo(Alat::class);
    }
}
```

## C.3 Implementasi Controller

### AuthController — Proses Login

```php
public function login(Request $request)
{
    // 1. Validasi input
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    // 2. Coba autentikasi
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        // 3. Redirect berdasarkan peran
        $peran = Auth::user()->peran;
        if ($peran === 'admin') {
            return redirect()->intended('/admin/dashboard');
        } elseif ($peran === 'petugas') {
            return redirect()->intended('/petugas/dashboard');
        } else {
            return redirect()->intended('/peminjam/dashboard');
        }
    }

    // 4. Jika gagal, kembali dengan error
    return back()->withErrors([
        'email' => 'Kredensial yang diberikan tidak cocok.',
    ]);
}
```

### PeminjamanController — Proses Persetujuan (Stored Procedure)

```php
public function approve(Peminjaman $peminjaman)
{
    // 1. Validasi status
    if ($peminjaman->status !== 'diajukan') {
        return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
    }

    // 2. Cek ketersediaan stok
    if ($peminjaman->alat->stok < 1) {
        return back()->with('error', 'Stok alat tidak mencukupi.');
    }

    // 3. Panggil Stored Procedure
    DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [
        $peminjaman->id, 
        Auth::id()
    ]);
    // Trigger otomatis mengurangi stok

    return back()->with('success', 'Peminjaman disetujui.');
}
```

### PeminjamanController — Proses Pengembalian (Stored Procedure + Function)

```php
public function returnTool(Peminjaman $peminjaman)
{
    // 1. Validasi status
    if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
        return back()->with('error', 'Peminjaman tidak dapat dikembalikan.');
    }

    // 2. Panggil Stored Procedure
    DB::statement('CALL proses_pengembalian(?, ?)', [
        $peminjaman->id, 
        Auth::id()
    ]);
    // Function hitung_denda() dipanggil di dalam procedure
    // Trigger otomatis menambah stok

    $peminjaman->refresh();
    return back()->with('success', "Alat dikembalikan. Denda: Rp " . number_format($peminjaman->denda));
}
```

## C.4 Implementasi Middleware — Hak Akses Berbasis Peran

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah peran sesuai
        $user = Auth::user();
        if (in_array($user->peran, $roles)) {
            return $next($request); // Akses diizinkan
        }

        // 3. Redirect jika tidak berhak
        if ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->peran === 'petugas') {
            return redirect()->route('petugas.dashboard');
        } else {
            return redirect()->route('peminjam.dashboard');
        }
    }
}
```

**Penggunaan dalam Route:**
```php
// Hanya admin yang bisa akses
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->group(function () { ... });

// Hanya petugas yang bisa akses
Route::middleware(['auth', 'peran:petugas'])->prefix('petugas')->group(function () { ... });

// Hanya peminjam yang bisa akses
Route::middleware(['auth', 'peran:peminjam'])->prefix('peminjam')->group(function () { ... });
```

## C.5 Implementasi Database Tingkat Lanjut

### Trigger — Manajemen Stok Otomatis

```sql
-- Trigger 1: Kurangi stok saat peminjaman disetujui
CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
        UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
    END IF;
END;

-- Trigger 2: Tambah stok saat alat dikembalikan
CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
        UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
    END IF;
END;
```

### Function — Perhitungan Denda Otomatis

```sql
-- Denda Rp 5.000 per hari keterlambatan
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
END;
```

### Stored Procedure — Proses Transaksional

```sql
-- Prosedur persetujuan peminjaman (dengan TRANSACTION)
CREATE PROCEDURE proses_persetujuan_peminjaman(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
    START TRANSACTION;

    UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;

    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
    VALUES (id_petugas, "Setujui Peminjaman",
            CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());

    COMMIT;
END;

-- Prosedur pengembalian (dengan perhitungan denda)
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
END;
```

## C.6 Implementasi Routing

### Ringkasan Route

| Grup            | Prefix      | Middleware            | Jumlah Route |
|-----------------|-------------|-----------------------|--------------|
| Guest           | `/`         | `guest`               | 3            |
| Admin           | `/admin`    | `auth`, `peran:admin` | 18           |
| Petugas         | `/petugas`  | `auth`, `peran:petugas`| 6           |
| Peminjam        | `/peminjam` | `auth`, `peran:peminjam`| 5          |
| **Total**       |             |                       | **32**       |

---

# D. Pengujian

## D.1 Metode Pengujian

Pengujian dilakukan menggunakan metode **Black Box Testing**, yaitu pengujian yang berfokus pada fungsionalitas aplikasi tanpa mengetahui struktur internal kode.

## D.2 Pengujian Modul Login

| No | Skenario                                    | Input                                      | Hasil yang Diharapkan                      | Status |
|----|---------------------------------------------|--------------------------------------------|--------------------------------------------|--------|
| 1  | Login dengan kredensial admin yang valid     | Email: `admin@admin.com`, Password: `password` | Redirect ke `/admin/dashboard`          | ✅ Berhasil |
| 2  | Login dengan kredensial petugas yang valid   | Email: `budi.petugas@sekolah.com`, Password: `password` | Redirect ke `/petugas/dashboard` | ✅ Berhasil |
| 3  | Login dengan kredensial peminjam yang valid  | Email: `andi.pratama@siswa.com`, Password: `password` | Redirect ke `/peminjam/dashboard`   | ✅ Berhasil |
| 4  | Login dengan email salah                    | Email: `salah@email.com`, Password: `password` | Pesan error "Kredensial tidak cocok"    | ✅ Berhasil |
| 5  | Login dengan password kosong                | Email: `admin@admin.com`, Password: (kosong)| Validasi: password wajib diisi              | ✅ Berhasil |
| 6  | Logout                                       | Klik tombol Logout                         | Redirect ke halaman login                   | ✅ Berhasil |

## D.3 Pengujian Modul Pengguna (Admin)

| No | Skenario                         | Input                                             | Hasil yang Diharapkan                 | Status |
|----|----------------------------------|---------------------------------------------------|---------------------------------------|--------|
| 1  | Menampilkan daftar pengguna      | Akses `/admin/pengguna`                           | Daftar pengguna tampil dengan paginasi| ✅ Berhasil |
| 2  | Tambah pengguna baru             | Nama, email, password, peran                       | Data tersimpan, redirect ke daftar    | ✅ Berhasil |
| 3  | Tambah pengguna email duplikat   | Email yang sudah terdaftar                         | Pesan error "Email sudah terdaftar"   | ✅ Berhasil |
| 4  | Edit data pengguna               | Ubah nama dan peran                                | Data terperbarui                      | ✅ Berhasil |
| 5  | Hapus pengguna                   | Klik hapus pada pengguna                           | Data terhapus dari database           | ✅ Berhasil |

## D.4 Pengujian Modul Kategori (Admin)

| No | Skenario                         | Input                                             | Hasil yang Diharapkan                 | Status |
|----|----------------------------------|---------------------------------------------------|---------------------------------------|--------|
| 1  | Menampilkan daftar kategori      | Akses `/admin/kategori`                           | Daftar kategori tampil                | ✅ Berhasil |
| 2  | Tambah kategori baru             | Nama kategori: "Alat Baru"                        | Data tersimpan                        | ✅ Berhasil |
| 3  | Tambah kategori tanpa nama       | Nama kategori: (kosong)                            | Pesan error "Nama wajib diisi"        | ✅ Berhasil |
| 4  | Edit nama kategori               | Ubah nama kategori                                 | Data terperbarui                      | ✅ Berhasil |
| 5  | Hapus kategori                   | Klik hapus pada kategori                           | Data terhapus                         | ✅ Berhasil |

## D.5 Pengujian Modul Alat (Admin)

| No | Skenario                         | Input                                             | Hasil yang Diharapkan                    | Status |
|----|----------------------------------|----------------------------------------------------|------------------------------------------|--------|
| 1  | Menampilkan daftar alat          | Akses `/admin/alat`                                | Daftar alat tampil dengan kategori       | ✅ Berhasil |
| 2  | Tambah alat baru dengan gambar   | Nama, kategori, stok, deskripsi, gambar (.jpg)     | Data tersimpan, gambar terupload         | ✅ Berhasil |
| 3  | Tambah alat tanpa nama           | Nama: (kosong)                                     | Pesan error "Nama alat wajib diisi"      | ✅ Berhasil |
| 4  | Tambah alat stok negatif         | Stok: -5                                           | Pesan error "Stok tidak boleh <0"        | ✅ Berhasil |
| 5  | Upload file bukan gambar         | File: dokumen.pdf                                  | Pesan error "File harus berupa gambar"   | ✅ Berhasil |
| 6  | Edit data alat                   | Ubah stok dan deskripsi                            | Data terperbarui                         | ✅ Berhasil |
| 7  | Hapus alat                       | Klik hapus pada alat                               | Data terhapus                            | ✅ Berhasil |

## D.6 Pengujian Modul Peminjaman

| No | Skenario                              | Input / Aksi                                     | Hasil yang Diharapkan                           | Status |
|----|---------------------------------------|---------------------------------------------------|-------------------------------------------------|--------|
| 1  | Peminjam melihat katalog              | Akses `/peminjam/katalog`                         | Alat dengan stok > 0 tampil                     | ✅ Berhasil |
| 2  | Peminjam filter katalog per kategori  | Pilih kategori dari dropdown                      | Alat terfilter sesuai kategori                  | ✅ Berhasil |
| 3  | Peminjam mengajukan peminjaman        | Pilih alat, tanggal, durasi (5 hari)              | Peminjaman tersimpan, status "diajukan"         | ✅ Berhasil |
| 4  | Ajukan peminjaman tanggal lampau      | Tanggal pinjam: kemarin                            | Pesan error "tidak boleh kurang dari hari ini"  | ✅ Berhasil |
| 5  | Ajukan peminjaman durasi > 14 hari    | Durasi: 20 hari                                    | Pesan error "Durasi maksimal 14 hari"           | ✅ Berhasil |
| 6  | Petugas menyetujui peminjaman         | Klik "Setujui" pada peminjaman status "diajukan"  | Status berubah ke "disetujui", stok berkurang   | ✅ Berhasil |
| 7  | Petugas menolak peminjaman            | Klik "Tolak" pada peminjaman                       | Status berubah ke "ditolak"                     | ✅ Berhasil |
| 8  | Peminjam ajukan pengembalian          | Klik "Ajukan Pengembalian"                         | Status berubah ke "sedang_dikembalikan"         | ✅ Berhasil |
| 9  | Petugas konfirmasi pengembalian       | Klik "Kembalikan" pada peminjaman                  | Status "dikembalikan", denda dihitung, stok +1  | ✅ Berhasil |
| 10 | Denda keterlambatan dihitung otomatis | Kembalikan alat yang terlambat 3 hari              | Denda = 3 × Rp 5.000 = Rp 15.000               | ✅ Berhasil |

## D.7 Pengujian Hak Akses (Middleware)

| No | Skenario                                      | Aksi                                         | Hasil yang Diharapkan                 | Status |
|----|-----------------------------------------------|----------------------------------------------|---------------------------------------|--------|
| 1  | Akses halaman tanpa login                     | Buka `/admin/dashboard` tanpa login          | Redirect ke halaman login             | ✅ Berhasil |
| 2  | Peminjam akses halaman admin                  | Login sebagai peminjam, buka `/admin/...`    | Redirect ke dashboard peminjam        | ✅ Berhasil |
| 3  | Petugas akses halaman admin                   | Login sebagai petugas, buka `/admin/...`     | Redirect ke dashboard petugas         | ✅ Berhasil |
| 4  | Admin akses halaman petugas                   | Login sebagai admin, buka `/petugas/...`     | Redirect ke dashboard admin           | ✅ Berhasil |

## D.8 Pengujian Trigger dan Stored Procedure

| No | Skenario                                         | Kondisi Awal         | Aksi                        | Hasil yang Diharapkan                    | Status |
|----|--------------------------------------------------|----------------------|-----------------------------|------------------------------------------|--------|
| 1  | Trigger kurangi stok saat disetujui              | Stok alat: 10        | Setujui peminjaman          | Stok alat menjadi 9                      | ✅ Berhasil |
| 2  | Trigger tambah stok saat dikembalikan            | Stok alat: 9         | Konfirmasi pengembalian     | Stok alat kembali ke 10                  | ✅ Berhasil |
| 3  | Function hitung denda (tepat waktu)              | Wajib kembali: hari ini | Kembali: hari ini        | Denda = Rp 0                             | ✅ Berhasil |
| 4  | Function hitung denda (terlambat)                | Terlambat 5 hari     | Konfirmasi pengembalian     | Denda = 5 × 5000 = Rp 25.000            | ✅ Berhasil |
| 5  | Stored procedure persetujuan (transaction)       | Status: diajukan     | CALL proses_persetujuan...  | Status disetujui + log tercatat          | ✅ Berhasil |
| 6  | Stored procedure pengembalian (transaction)      | Status: disetujui    | CALL proses_pengembalian... | Status dikembalikan + denda + log        | ✅ Berhasil |

---

# E. Dokumentasi

## E.1 Ringkasan Proyek

| Aspek                | Detail                                                    |
|----------------------|-----------------------------------------------------------|
| **Nama Proyek**      | Aplikasi Peminjaman Alat Sekolah                          |
| **Framework**        | Laravel 11 (PHP 8.x)                                     |
| **Database**         | MySQL 8.0                                                 |
| **Frontend**         | Blade Template + Tailwind CSS + Vite                      |
| **Jenis Aplikasi**   | Aplikasi Web (Multi-User, Role-Based)                     |
| **Metode**           | Waterfall                                                 |
| **Jumlah Tabel**     | 5 tabel utama + 3 tabel pendukung                        |
| **Jumlah Model**     | 5 model Eloquent                                          |
| **Jumlah Controller**| 6 controller + 1 base controller                         |
| **Jumlah View**      | 21 halaman Blade                                          |
| **Jumlah Route**     | 32 route                                                  |
| **Trigger**          | 2 trigger (manajemen stok otomatis)                       |
| **Function**         | 1 function (perhitungan denda)                            |
| **Stored Procedure** | 2 prosedur (persetujuan & pengembalian)                   |

## E.2 Fitur Utama

### Fitur Admin
- ✅ Dashboard admin dengan navigasi cepat ke semua modul
- ✅ CRUD data pengguna (admin, petugas, peminjam) dengan validasi email unik
- ✅ CRUD data kategori alat
- ✅ CRUD data alat dengan upload gambar dan pengelolaan stok
- ✅ Manajemen peminjaman (setujui, tolak, konfirmasi pengembalian)
- ✅ Lihat log aktivitas sistem

### Fitur Petugas
- ✅ Dashboard petugas dengan akses ke manajemen peminjaman
- ✅ Lihat semua data peminjaman
- ✅ Setujui/tolak pengajuan peminjaman
- ✅ Konfirmasi pengembalian dengan perhitungan denda otomatis
- ✅ Cetak/lihat laporan peminjaman

### Fitur Peminjam
- ✅ Dashboard peminjam dengan akses ke katalog dan riwayat
- ✅ Lihat katalog alat yang tersedia (filter per kategori)
- ✅ Ajukan peminjaman dengan memilih tanggal dan durasi
- ✅ Lihat riwayat peminjaman sendiri
- ✅ Ajukan pengembalian alat

### Fitur Sistem
- ✅ Autentikasi session-based dengan redirect berbasis peran
- ✅ Middleware hak akses berbasis peran (RBAC)
- ✅ Manajemen stok otomatis menggunakan trigger MySQL
- ✅ Perhitungan denda otomatis menggunakan function MySQL
- ✅ Proses bisnis transaksional menggunakan stored procedure
- ✅ Pencatatan log aktivitas otomatis
- ✅ Paginasi pada semua halaman daftar
- ✅ Validasi input pada semua form
- ✅ Flash messages untuk feedback operasi

## E.3 Kredensial Default (Data Dummy)

| Peran     | Email                          | Password   |
|-----------|--------------------------------|------------|
| Admin     | `admin@admin.com`              | `password` |
| Petugas   | `budi.petugas@sekolah.com`     | `password` |
| Peminjam  | `andi.pratama@siswa.com`       | `password` |

## E.4 Cara Menjalankan Aplikasi

### Prasyarat
1. PHP 8.x terinstal
2. Composer terinstal
3. Node.js & NPM terinstal
4. MySQL Server aktif

### Langkah Instalasi

```bash
# 1. Clone repository
git clone <repository-url>
cd peminjaman

# 2. Install dependencies PHP
composer install

# 3. Install dependencies JavaScript
npm install

# 4. Konfigurasi environment
cp .env.example .env
php artisan key:generate

# 5. Konfigurasi database di file .env
# DB_DATABASE=peminjaman
# DB_USERNAME=root
# DB_PASSWORD=

# 6. Jalankan migrasi database
php artisan migrate

# 7. Isi data dummy (opsional)
php artisan db:seed

# 8. Link storage untuk gambar
php artisan storage:link

# 9. Build assets frontend
npm run dev

# 10. Jalankan server
php artisan serve
```

Aplikasi dapat diakses di: `http://localhost:8000`

## E.5 Kesimpulan

Aplikasi Peminjaman Alat Sekolah berhasil dikembangkan menggunakan metode Waterfall dengan tahapan yang terstruktur mulai dari analisis kebutuhan hingga dokumentasi. Aplikasi ini mampu:

1. **Mendigitalisasi** proses peminjaman alat yang sebelumnya manual
2. **Mengotomasi** pengelolaan stok melalui trigger database
3. **Menghitung denda** keterlambatan secara otomatis melalui function database
4. **Menjaga konsistensi data** melalui stored procedure yang bersifat transaksional
5. **Mengontrol akses** secara ketat melalui sistem peran (admin, petugas, peminjam)
6. **Mencatat aktivitas** sistem secara otomatis untuk keperluan audit

Dengan arsitektur MVC yang diterapkan Laravel dan penggunaan fitur database tingkat lanjut (trigger, function, stored procedure), aplikasi ini berjalan secara efisien, aman, dan mudah untuk dimaintenance di masa depan.

---

*Disusun oleh: Muhamad Syiaril Islami*
*Proyek UKK — Tahun 2026*
*Tanggal: 24 Februari 2026*
