# DOKUMENTASI STRUKTUR DATA DAN CONTROL PROGRAM
## Aplikasi Peminjaman Alat Sekolah

---

## DAFTAR ISI

1. [Pendahuluan](#1-pendahuluan)
2. [Struktur Data (Database)](#2-struktur-data-database)
3. [Tipe Data pada Setiap Tabel](#3-tipe-data-pada-setiap-tabel)
4. [Relasi Antar Tabel](#4-relasi-antar-tabel)
5. [Akses Terhadap Struktur Data (Model Eloquent)](#5-akses-terhadap-struktur-data-model-eloquent)
6. [Trigger, Fungsi, dan Stored Procedure](#6-trigger-fungsi-dan-stored-procedure)
7. [Control Program (Alur Kendali Aplikasi)](#7-control-program-alur-kendali-aplikasi)
8. [Middleware dan Hak Akses](#8-middleware-dan-hak-akses)
9. [Routing (Peta URL Aplikasi)](#9-routing-peta-url-aplikasi)
10. [Validasi Data](#10-validasi-data)

---

## 1. Pendahuluan

Aplikasi **Peminjaman Alat Sekolah** adalah sistem berbasis web yang dibangun menggunakan framework **Laravel 11** dengan database **MySQL**. Aplikasi ini memungkinkan pengelolaan peminjaman alat/peralatan sekolah dengan tiga peran pengguna: **Admin**, **Petugas**, dan **Peminjam**.

### Teknologi yang Digunakan

| Komponen         | Teknologi                  |
|------------------|-----------------------------|
| Framework        | Laravel 11                  |
| Bahasa Backend   | PHP 8.x                    |
| Database         | MySQL                       |
| Frontend         | Blade Template + Tailwind CSS |
| Build Tool       | Vite                        |
| Authentication   | Laravel Auth (Session-based)|

---

## 2. Struktur Data (Database)

Aplikasi ini memiliki **5 tabel utama** dan **3 tabel pendukung** dalam database:

### Tabel Utama

| No | Nama Tabel       | Deskripsi                                       |
|----|------------------|-------------------------------------------------|
| 1  | `pengguna`       | Menyimpan data pengguna (admin, petugas, peminjam) |
| 2  | `kategori`       | Menyimpan kategori/jenis alat                   |
| 3  | `alat`           | Menyimpan data alat/peralatan yang tersedia      |
| 4  | `peminjaman`     | Menyimpan data transaksi peminjaman alat         |
| 5  | `log_aktivitas`  | Menyimpan catatan aktivitas pengguna di sistem   |

### Tabel Pendukung (Sistem Laravel)

| No | Nama Tabel              | Deskripsi                                  |
|----|-------------------------|--------------------------------------------|
| 1  | `password_reset_tokens` | Menyimpan token untuk reset kata sandi      |
| 2  | `sessions`              | Menyimpan data sesi pengguna yang aktif     |
| 3  | `cache`                 | Menyimpan data cache aplikasi               |

---

## 3. Tipe Data pada Setiap Tabel

### 3.1 Tabel `pengguna`

Tabel ini menyimpan seluruh data pengguna aplikasi, termasuk admin, petugas, dan peminjam.

| No | Kolom               | Tipe Data           | Constraint                   | Keterangan                                |
|----|---------------------|---------------------|------------------------------|-------------------------------------------|
| 1  | `id`                | `BIGINT UNSIGNED`   | PRIMARY KEY, AUTO_INCREMENT  | Identitas unik pengguna                   |
| 2  | `nama`              | `VARCHAR(255)`      | NOT NULL                     | Nama lengkap pengguna                     |
| 3  | `email`             | `VARCHAR(255)`      | NOT NULL, UNIQUE             | Alamat email (digunakan untuk login)       |
| 4  | `email_verified_at` | `TIMESTAMP`         | NULLABLE                     | Waktu verifikasi email                     |
| 5  | `kata_sandi`        | `VARCHAR(255)`      | NOT NULL                     | Kata sandi yang sudah di-hash (bcrypt)     |
| 6  | `peran`             | `ENUM`              | DEFAULT 'peminjam'           | Peran pengguna: `admin`, `petugas`, `peminjam` |
| 7  | `remember_token`    | `VARCHAR(100)`      | NULLABLE                     | Token untuk fitur "Ingat Saya"            |
| 8  | `created_at`        | `TIMESTAMP`         | NULLABLE                     | Waktu pembuatan data                       |
| 9  | `updated_at`        | `TIMESTAMP`         | NULLABLE                     | Waktu terakhir diperbarui                  |

### 3.2 Tabel `kategori`

Tabel ini menyimpan kategori/jenis alat yang tersedia di sekolah.

| No | Kolom           | Tipe Data           | Constraint                   | Keterangan                     |
|----|-----------------|---------------------|------------------------------|--------------------------------|
| 1  | `id`            | `BIGINT UNSIGNED`   | PRIMARY KEY, AUTO_INCREMENT  | Identitas unik kategori       |
| 2  | `nama_kategori` | `VARCHAR(255)`      | NOT NULL                     | Nama kategori alat             |
| 3  | `created_at`    | `TIMESTAMP`         | NULLABLE                     | Waktu pembuatan data           |
| 4  | `updated_at`    | `TIMESTAMP`         | NULLABLE                     | Waktu terakhir diperbarui      |

### 3.3 Tabel `alat`

Tabel ini menyimpan data alat/peralatan yang tersedia untuk dipinjam.

| No | Kolom         | Tipe Data           | Constraint                          | Keterangan                          |
|----|---------------|---------------------|--------------------------------------|-------------------------------------|
| 1  | `id`          | `BIGINT UNSIGNED`   | PRIMARY KEY, AUTO_INCREMENT         | Identitas unik alat                |
| 2  | `kategori_id` | `BIGINT UNSIGNED`   | FOREIGN KEY → `kategori(id)`        | Referensi ke tabel kategori         |
| 3  | `nama_alat`   | `VARCHAR(255)`      | NOT NULL                             | Nama alat                           |
| 4  | `deskripsi`   | `TEXT`              | NULLABLE                             | Deskripsi detail alat               |
| 5  | `stok`        | `INTEGER`           | NOT NULL                             | Jumlah stok alat yang tersedia      |
| 6  | `gambar`      | `VARCHAR(255)`      | NULLABLE                             | Path file gambar alat               |
| 7  | `created_at`  | `TIMESTAMP`         | NULLABLE                             | Waktu pembuatan data                |
| 8  | `updated_at`  | `TIMESTAMP`         | NULLABLE                             | Waktu terakhir diperbarui           |

### 3.4 Tabel `peminjaman`

Tabel ini menyimpan data transaksi peminjaman alat oleh peminjam.

| No | Kolom                   | Tipe Data           | Constraint                          | Keterangan                                            |
|----|-------------------------|---------------------|--------------------------------------|-------------------------------------------------------|
| 1  | `id`                    | `BIGINT UNSIGNED`   | PRIMARY KEY, AUTO_INCREMENT         | Identitas unik peminjaman                             |
| 2  | `pengguna_id`           | `BIGINT UNSIGNED`   | FOREIGN KEY → `pengguna(id)`        | Referensi ke peminjam                                  |
| 3  | `alat_id`               | `BIGINT UNSIGNED`   | FOREIGN KEY → `alat(id)`            | Referensi ke alat yang dipinjam                        |
| 4  | `tanggal_pinjam`        | `DATE`              | NOT NULL                             | Tanggal mulai peminjaman                               |
| 5  | `tanggal_wajib_kembali` | `DATE`              | NOT NULL                             | Batas akhir pengembalian                               |
| 6  | `tanggal_kembali`       | `DATE`              | NULLABLE                             | Tanggal alat dikembalikan (diisi saat dikembalikan)    |
| 7  | `status`                | `ENUM`              | DEFAULT 'diajukan'                   | Status: `diajukan`, `disetujui`, `sedang_dikembalikan`, `dikembalikan`, `ditolak` |
| 8  | `denda`                 | `DECIMAL(10,2)`     | DEFAULT 0                            | Nominal denda keterlambatan (Rp 5.000/hari)            |
| 9  | `created_at`            | `TIMESTAMP`         | NULLABLE                             | Waktu pembuatan data                                   |
| 10 | `updated_at`            | `TIMESTAMP`         | NULLABLE                             | Waktu terakhir diperbarui                              |

### 3.5 Tabel `log_aktivitas`

Tabel ini menyimpan catatan log semua aktivitas penting yang dilakukan pengguna di sistem.

| No | Kolom         | Tipe Data           | Constraint                                       | Keterangan                      |
|----|---------------|---------------------|--------------------------------------------------|---------------------------------|
| 1  | `id`          | `BIGINT UNSIGNED`   | PRIMARY KEY, AUTO_INCREMENT                      | Identitas unik log              |
| 2  | `pengguna_id` | `BIGINT UNSIGNED`   | FOREIGN KEY → `pengguna(id)`, ON DELETE CASCADE  | Referensi ke pengguna pelaku    |
| 3  | `aksi`        | `VARCHAR(255)`      | NOT NULL                                          | Jenis aksi (contoh: "Login", "Setujui Peminjaman") |
| 4  | `deskripsi`   | `TEXT`              | NULLABLE                                          | Detail deskripsi aktivitas      |
| 5  | `created_at`  | `TIMESTAMP`         | NULLABLE                                          | Waktu aktivitas terjadi         |
| 6  | `updated_at`  | `TIMESTAMP`         | NULLABLE                                          | Waktu terakhir diperbarui       |

---

## 4. Relasi Antar Tabel

### 4.1 Diagram Relasi (ERD)

```
┌──────────────┐       ┌──────────────┐       ┌──────────────────┐
│   pengguna   │       │   kategori   │       │  log_aktivitas   │
│──────────────│       │──────────────│       │──────────────────│
│ PK id        │──┐    │ PK id        │──┐    │ PK id            │
│    nama      │  │    │    nama_     │  │    │ FK pengguna_id   │──┐
│    email     │  │    │    kategori  │  │    │    aksi          │  │
│    kata_sandi│  │    │    created_at│  │    │    deskripsi     │  │
│    peran     │  │    │    updated_at│  │    │    created_at    │  │
│    ...       │  │    └──────────────┘  │    │    updated_at    │  │
└──────────────┘  │                      │    └──────────────────┘  │
                  │    ┌──────────────┐  │                          │
                  │    │     alat     │  │                          │
                  │    │──────────────│  │                          │
                  │    │ PK id        │  │                          │
                  │    │ FK kategori_id│──┘                         │
                  │    │    nama_alat │                             │
                  │    │    deskripsi │                             │
                  │    │    stok     │──┐                           │
                  │    │    gambar   │  │                           │
                  │    └─────────────┘  │                           │
                  │                      │                          │
                  │    ┌──────────────────┘                         │
                  │    │  ┌──────────────────┐                     │
                  │    │  │   peminjaman     │                     │
                  │    │  │──────────────────│                     │
                  │    │  │ PK id            │                     │
                  └────┼──│ FK pengguna_id   │                     │
                       └──│ FK alat_id       │                     │
                          │    tanggal_pinjam│                     │
                          │    tanggal_wajib │                     │
                          │    tanggal_      │                     │
                          │    kembali       │                     │
                          │    status        │                     │
                          │    denda         │                     │
                          └──────────────────┘                     │
                                                                   │
                  ┌────────────────────────────────────────────────┘
                  │ (pengguna → log_aktivitas: ON DELETE CASCADE)
                  ▼
```

### 4.2 Jenis Relasi

| No | Relasi                            | Jenis           | Keterangan                                            |
|----|-----------------------------------|-----------------|-------------------------------------------------------|
| 1  | `pengguna` → `peminjaman`         | One-to-Many     | Satu pengguna dapat memiliki banyak peminjaman         |
| 2  | `alat` → `peminjaman`             | One-to-Many     | Satu alat dapat dipinjam dalam banyak transaksi        |
| 3  | `kategori` → `alat`               | One-to-Many     | Satu kategori dapat memiliki banyak alat               |
| 4  | `pengguna` → `log_aktivitas`      | One-to-Many     | Satu pengguna dapat memiliki banyak log aktivitas      |

---

## 5. Akses Terhadap Struktur Data (Model Eloquent)

Laravel menggunakan **Eloquent ORM** untuk mengakses data. Setiap tabel direpresentasikan oleh sebuah Model.

### 5.1 Model `Pengguna` (`app/Models/Pengguna.php`)

```php
class Pengguna extends Authenticatable
{
    protected $table = 'pengguna';

    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran'];

    protected $hidden = ['password', 'remember_token'];

    // Menggunakan kolom 'kata_sandi' sebagai password autentikasi
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    // Casting otomatis
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'kata_sandi' => 'hashed',
        ];
    }
}
```

**Akses Data:**
- `Pengguna::all()` — Mengambil semua pengguna
- `Pengguna::find($id)` — Mencari pengguna berdasarkan ID
- `Pengguna::create([...])` — Membuat pengguna baru
- `Pengguna::latest()->paginate(10)` — Mengambil pengguna terbaru dengan paginasi

### 5.2 Model `Kategori` (`app/Models/Kategori.php`)

```php
class Kategori extends Model
{
    protected $table = 'kategori';

    protected $fillable = ['nama_kategori'];

    // Relasi: Satu kategori memiliki banyak alat
    public function alat()
    {
        return $this->hasMany(Alat::class);
    }
}
```

**Akses Data:**
- `Kategori::all()` — Mengambil semua kategori
- `Kategori::paginate(10)` — Mengambil kategori dengan paginasi
- `$kategori->alat` — Mengakses semua alat dalam kategori tersebut

### 5.3 Model `Alat` (`app/Models/Alat.php`)

```php
class Alat extends Model
{
    protected $table = 'alat';

    protected $fillable = ['kategori_id', 'nama_alat', 'deskripsi', 'stok', 'gambar'];

    // Relasi: Alat milik satu kategori
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    // Relasi: Alat memiliki banyak peminjaman
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
```

**Akses Data:**
- `Alat::with('kategori')->paginate(10)` — Mengambil alat beserta data kategorinya
- `Alat::where('stok', '>', 0)->get()` — Mengambil alat yang masih tersedia
- `$alat->kategori` — Mengakses kategori dari alat
- `$alat->peminjaman` — Mengakses semua peminjaman untuk alat tersebut

### 5.4 Model `Peminjaman` (`app/Models/Peminjaman.php`)

```php
class Peminjaman extends Model
{
    protected $table = 'peminjaman';

    protected $fillable = [
        'pengguna_id', 'alat_id', 'tanggal_pinjam',
        'tanggal_wajib_kembali', 'tanggal_kembali', 'status', 'denda'
    ];

    // Relasi: Peminjaman milik satu pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    // Relasi: Peminjaman milik satu alat
    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
```

**Akses Data:**
- `Peminjaman::with(['pengguna', 'alat'])->get()` — Mengambil semua peminjaman beserta data pengguna dan alat
- `Peminjaman::where('pengguna_id', $id)->get()` — Mengambil peminjaman berdasarkan pengguna
- `Peminjaman::where('status', 'diajukan')->get()` — Mengambil peminjaman yang menunggu persetujuan

### 5.5 Model `LogAktivitas` (`app/Models/LogAktivitas.php`)

```php
class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';

    protected $fillable = ['pengguna_id', 'aksi', 'deskripsi'];

    // Relasi: Log milik satu pengguna
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
```

**Akses Data:**
- `LogAktivitas::with('pengguna')->orderBy('created_at', 'desc')->paginate(20)` — Mengambil log aktivitas terbaru

---

## 6. Trigger, Fungsi, dan Stored Procedure

Aplikasi ini menggunakan fitur database MySQL tingkat lanjut untuk menjaga konsistensi data dan otomatisasi proses bisnis.

### 6.1 Trigger: `kurangi_stok_setelah_disetujui`

| Properti     | Nilai                                                        |
|--------------|--------------------------------------------------------------|
| **Tipe**     | AFTER UPDATE                                                  |
| **Tabel**    | `peminjaman`                                                  |
| **Kondisi**  | Ketika `status` berubah menjadi `'disetujui'`                 |
| **Aksi**     | Mengurangi stok alat sebanyak 1 pada tabel `alat`             |

```sql
CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
        UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
    END IF;
END
```

### 6.2 Trigger: `tambah_stok_setelah_dikembalikan`

| Properti     | Nilai                                                        |
|--------------|--------------------------------------------------------------|
| **Tipe**     | AFTER UPDATE                                                  |
| **Tabel**    | `peminjaman`                                                  |
| **Kondisi**  | Ketika `status` berubah menjadi `'dikembalikan'`              |
| **Aksi**     | Menambah stok alat sebanyak 1 pada tabel `alat`               |

```sql
CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
        UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
    END IF;
END
```

### 6.3 Fungsi: `hitung_denda`

| Properti     | Nilai                                                         |
|--------------|---------------------------------------------------------------|
| **Input**    | `tanggal_wajib` (DATE), `tanggal_kembali` (DATE)             |
| **Output**   | `DECIMAL(10,2)` — nominal denda                               |
| **Logika**   | Jika `tanggal_kembali > tanggal_wajib`, denda = selisih hari × Rp 5.000 |

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

### 6.4 Stored Procedure: `proses_persetujuan_peminjaman`

| Properti     | Nilai                                                         |
|--------------|---------------------------------------------------------------|
| **Input**    | `id_peminjaman` (INT), `id_petugas` (INT)                     |
| **Aksi**     | 1. Mengubah status peminjaman menjadi `'disetujui'`           |
|              | 2. Mencatat log aktivitas persetujuan                         |
| **Fitur**    | Menggunakan TRANSACTION untuk menjaga konsistensi data        |

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

### 6.5 Stored Procedure: `proses_pengembalian`

| Properti     | Nilai                                                         |
|--------------|---------------------------------------------------------------|
| **Input**    | `id_peminjaman` (INT), `id_petugas` (INT)                     |
| **Aksi**     | 1. Menghitung denda menggunakan fungsi `hitung_denda`         |
|              | 2. Mengubah status menjadi `'dikembalikan'`                   |
|              | 3. Mengisi `tanggal_kembali` dan `denda`                      |
|              | 4. Mencatat log aktivitas pengembalian                        |
| **Fitur**    | Menggunakan TRANSACTION untuk menjaga konsistensi data        |

```sql
CREATE PROCEDURE proses_pengembalian(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE tanggal_wajib DATE;
    DECLARE nominal_denda DECIMAL(10,2);
    
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
    START TRANSACTION;
    
    SELECT tanggal_wajib_kembali INTO tanggal_wajib FROM peminjaman WHERE id = id_peminjaman;
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

---

## 7. Control Program (Alur Kendali Aplikasi)

### 7.1 Arsitektur MVC (Model-View-Controller)

Aplikasi ini menggunakan pola arsitektur **MVC** yang disediakan oleh framework Laravel:

```
┌──────────┐     ┌─────────────┐     ┌──────────┐     ┌──────────────┐
│  Browser │ ──→ │   Routes    │ ──→ │Controller│ ──→ │    Model     │
│ (Client) │     │  (web.php)  │     │          │     │  (Eloquent)  │
└──────────┘     └─────────────┘     └──────────┘     └──────────────┘
     ▲                                    │                   │
     │                                    ▼                   ▼
     │                              ┌──────────┐     ┌──────────────┐
     └──────────────────────────────│   View   │ ←── │   Database   │
                                    │ (Blade)  │     │   (MySQL)    │
                                    └──────────┘     └──────────────┘
```

### 7.2 Daftar Controller dan Fungsinya

#### 7.2.1 `AuthController` — Pengelolaan Autentikasi

| Method           | HTTP   | Fungsi                                                    |
|------------------|--------|-----------------------------------------------------------|
| `showLoginForm()`| GET    | Menampilkan halaman form login                            |
| `login()`        | POST   | Memproses login dan mengarahkan ke dashboard sesuai peran |
| `logout()`       | POST   | Memproses logout dan menghapus sesi                       |

**Alur Login:**
1. Pengguna memasukkan email dan password
2. Sistem memvalidasi kredensial
3. Jika valid, pengguna diarahkan ke dashboard sesuai peran:
   - `admin` → `/admin/dashboard`
   - `petugas` → `/petugas/dashboard`
   - `peminjam` → `/peminjam/dashboard`
4. Jika tidak valid, menampilkan pesan error

#### 7.2.2 `PenggunaController` — Pengelolaan Data Pengguna (Admin)

| Method       | HTTP   | Fungsi                                           |
|--------------|--------|--------------------------------------------------|
| `index()`    | GET    | Menampilkan daftar semua pengguna (paginasi 10)  |
| `create()`   | GET    | Menampilkan form tambah pengguna baru             |
| `store()`    | POST   | Menyimpan data pengguna baru ke database          |
| `edit()`     | GET    | Menampilkan form edit pengguna                    |
| `update()`   | PUT    | Memperbarui data pengguna                         |
| `destroy()`  | DELETE | Menghapus data pengguna                           |

#### 7.2.3 `KategoriController` — Pengelolaan Kategori (Admin)

| Method       | HTTP   | Fungsi                                            |
|--------------|--------|---------------------------------------------------|
| `index()`    | GET    | Menampilkan daftar kategori (paginasi 10)         |
| `create()`   | GET    | Menampilkan form tambah kategori                   |
| `store()`    | POST   | Menyimpan kategori baru                            |
| `edit()`     | GET    | Menampilkan form edit kategori                     |
| `update()`   | PUT    | Memperbarui data kategori                          |
| `destroy()`  | DELETE | Menghapus kategori                                 |

#### 7.2.4 `AlatController` — Pengelolaan Data Alat (Admin)

| Method       | HTTP   | Fungsi                                             |
|--------------|--------|----------------------------------------------------|
| `index()`    | GET    | Menampilkan daftar alat beserta kategorinya         |
| `create()`   | GET    | Menampilkan form tambah alat baru                   |
| `store()`    | POST   | Menyimpan data alat baru (termasuk upload gambar)   |
| `edit()`     | GET    | Menampilkan form edit alat                          |
| `update()`   | PUT    | Memperbarui data alat (termasuk ganti gambar)       |
| `destroy()`  | DELETE | Menghapus data alat                                 |

#### 7.2.5 `PeminjamanController` — Pengelolaan Peminjaman

| Method            | HTTP | Peran     | Fungsi                                              |
|-------------------|------|-----------|-----------------------------------------------------|
| `katalog()`       | GET  | Peminjam  | Menampilkan katalog alat yang tersedia (stok > 0)   |
| `store()`         | POST | Peminjam  | Mengajukan peminjaman baru                           |
| `peminjamanSaya()`| GET  | Peminjam  | Melihat riwayat peminjaman sendiri                   |
| `requestReturn()` | POST | Peminjam  | Mengajukan pengembalian alat                         |
| `adminIndex()`    | GET  | Admin     | Melihat semua peminjaman                             |
| `index()`         | GET  | Petugas   | Melihat semua peminjaman                             |
| `laporan()`       | GET  | Petugas   | Mencetak laporan peminjaman                          |
| `approve()`       | POST | Admin/Petugas | Menyetujui peminjaman (via Stored Procedure)     |
| `reject()`        | POST | Admin/Petugas | Menolak peminjaman                               |
| `returnTool()`    | POST | Admin/Petugas | Konfirmasi pengembalian alat (via Stored Procedure) |

#### 7.2.6 `LogAktivitasController` — Pengelolaan Log Aktivitas (Admin)

| Method     | HTTP | Fungsi                                              |
|------------|------|-----------------------------------------------------|
| `index()`  | GET  | Menampilkan log aktivitas terbaru (paginasi 20)     |

### 7.3 Alur Proses Bisnis Peminjaman

```
┌─────────────┐     ┌────────────────┐     ┌────────────────────┐
│  Peminjam   │     │    Petugas/    │     │    Sistem          │
│             │     │    Admin       │     │    (Database)      │
└──────┬──────┘     └───────┬────────┘     └─────────┬──────────┘
       │                    │                         │
       │ 1. Lihat Katalog   │                         │
       │───────────────────→│                         │
       │                    │                         │
       │ 2. Ajukan          │                         │
       │    Peminjaman      │                         │
       │───────────────────→│      INSERT peminjaman  │
       │                    │────────────────────────→│
       │                    │    (status: diajukan)   │
       │                    │                         │
       │                    │ 3. Setujui/Tolak        │
       │                    │    Peminjaman            │
       │                    │────────────────────────→│
       │                    │    CALL proses_          │
       │                    │    persetujuan_          │
       │                    │    peminjaman()          │
       │                    │                         │
       │                    │    ┌─ Trigger:          │
       │                    │    │  kurangi_stok      │
       │                    │    └──────────────────→ │
       │                    │                         │
       │ 4. Ajukan          │                         │
       │    Pengembalian    │                         │
       │───────────────────→│      UPDATE status      │
       │                    │────────────────────────→│
       │                    │  (sedang_dikembalikan)  │
       │                    │                         │
       │                    │ 5. Konfirmasi           │
       │                    │    Pengembalian          │
       │                    │────────────────────────→│
       │                    │    CALL proses_          │
       │                    │    pengembalian()        │
       │                    │                         │
       │                    │    ┌─ Function:         │
       │                    │    │  hitung_denda()    │
       │                    │    ├─ Trigger:          │
       │                    │    │  tambah_stok       │
       │                    │    └──────────────────→ │
       │                    │                         │
```

### 7.4 Diagram Status Peminjaman

```
                    ┌───────────┐
                    │  diajukan │ ← Status awal saat peminjam mengajukan
                    └─────┬─────┘
                          │
              ┌───────────┼───────────┐
              ▼                       ▼
      ┌──────────────┐        ┌───────────┐
      │  disetujui   │        │  ditolak  │ ← Proses selesai
      └──────┬───────┘        └───────────┘
              │
              ▼
  ┌──────────────────────┐
  │ sedang_dikembalikan  │ ← Peminjam mengajukan pengembalian
  └──────────┬───────────┘
              │
              ▼
      ┌──────────────┐
      │ dikembalikan │ ← Petugas konfirmasi, denda dihitung
      └──────────────┘
```

---

## 8. Middleware dan Hak Akses

### 8.1 Middleware `RoleMiddleware` (`app/Http/Middleware/RoleMiddleware.php`)

Middleware ini mengontrol akses pengguna berdasarkan peran (role).

```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah pengguna sudah login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        // 2. Cek apakah peran pengguna sesuai dengan yang diizinkan
        if (in_array($user->peran, $roles)) {
            return $next($request); // Izinkan akses
        }

        // 3. Redirect ke dashboard masing-masing jika tidak berhak
        // admin → /admin/dashboard
        // petugas → /petugas/dashboard
        // peminjam → /peminjam/dashboard
    }
}
```

### 8.2 Matriks Hak Akses

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
| Lihat Peminjaman Saya       |  ❌   |   ❌    |    ✅    |
| Ajukan Pengembalian         |  ❌   |   ❌    |    ✅    |

---

## 9. Routing (Peta URL Aplikasi)

### 9.1 Route Publik (Guest)

| Method | URL        | Controller        | Aksi             | Keterangan         |
|--------|------------|--------------------|------------------|--------------------|
| GET    | `/`        | -                  | Redirect         | Redirect ke login  |
| GET    | `/login`   | `AuthController`   | `showLoginForm`  | Halaman login      |
| POST   | `/login`   | `AuthController`   | `login`          | Proses login       |
| POST   | `/logout`  | `AuthController`   | `logout`         | Proses logout      |

### 9.2 Route Admin (`/admin/*`)

| Method | URL                                | Controller              | Aksi        | Keterangan                  |
|--------|------------------------------------|--------------------------|-------------|-----------------------------|
| GET    | `/admin/dashboard`                | -                        | View        | Dashboard admin              |
| GET    | `/admin/pengguna`                 | `PenggunaController`     | `index`     | Daftar pengguna              |
| GET    | `/admin/pengguna/create`          | `PenggunaController`     | `create`    | Form tambah pengguna         |
| POST   | `/admin/pengguna`                 | `PenggunaController`     | `store`     | Simpan pengguna baru         |
| GET    | `/admin/pengguna/{id}/edit`       | `PenggunaController`     | `edit`      | Form edit pengguna           |
| PUT    | `/admin/pengguna/{id}`            | `PenggunaController`     | `update`    | Update pengguna              |
| DELETE | `/admin/pengguna/{id}`            | `PenggunaController`     | `destroy`   | Hapus pengguna               |
| GET    | `/admin/kategori`                 | `KategoriController`     | `index`     | Daftar kategori              |
| GET    | `/admin/kategori/create`          | `KategoriController`     | `create`    | Form tambah kategori         |
| POST   | `/admin/kategori`                 | `KategoriController`     | `store`     | Simpan kategori baru         |
| GET    | `/admin/kategori/{id}/edit`       | `KategoriController`     | `edit`      | Form edit kategori           |
| PUT    | `/admin/kategori/{id}`            | `KategoriController`     | `update`    | Update kategori              |
| DELETE | `/admin/kategori/{id}`            | `KategoriController`     | `destroy`   | Hapus kategori               |
| GET    | `/admin/alat`                     | `AlatController`         | `index`     | Daftar alat                  |
| GET    | `/admin/alat/create`              | `AlatController`         | `create`    | Form tambah alat             |
| POST   | `/admin/alat`                     | `AlatController`         | `store`     | Simpan alat baru             |
| GET    | `/admin/alat/{id}/edit`           | `AlatController`         | `edit`      | Form edit alat               |
| PUT    | `/admin/alat/{id}`                | `AlatController`         | `update`    | Update alat                  |
| DELETE | `/admin/alat/{id}`                | `AlatController`         | `destroy`   | Hapus alat                   |
| GET    | `/admin/log-aktivitas`            | `LogAktivitasController` | `index`     | Daftar log aktivitas         |
| GET    | `/admin/peminjaman`               | `PeminjamanController`   | `adminIndex`| Daftar seluruh peminjaman    |
| POST   | `/admin/peminjaman/{id}/setujui`  | `PeminjamanController`   | `approve`   | Setujui peminjaman           |
| POST   | `/admin/peminjaman/{id}/tolak`    | `PeminjamanController`   | `reject`    | Tolak peminjaman             |
| POST   | `/admin/peminjaman/{id}/kembali`  | `PeminjamanController`   | `returnTool`| Konfirmasi pengembalian      |

### 9.3 Route Petugas (`/petugas/*`)

| Method | URL                                   | Controller             | Aksi         | Keterangan               |
|--------|---------------------------------------|--------------------------|--------------|--------------------------|
| GET    | `/petugas/dashboard`                 | -                        | View         | Dashboard petugas         |
| GET    | `/petugas/peminjaman`                | `PeminjamanController`   | `index`      | Daftar peminjaman         |
| GET    | `/petugas/laporan`                   | `PeminjamanController`   | `laporan`    | Cetak laporan             |
| POST   | `/petugas/peminjaman/{id}/setujui`   | `PeminjamanController`   | `approve`    | Setujui peminjaman        |
| POST   | `/petugas/peminjaman/{id}/tolak`     | `PeminjamanController`   | `reject`     | Tolak peminjaman          |
| POST   | `/petugas/peminjaman/{id}/kembali`   | `PeminjamanController`   | `returnTool` | Konfirmasi pengembalian   |

### 9.4 Route Peminjam (`/peminjam/*`)

| Method | URL                                             | Controller             | Aksi             | Keterangan                      |
|--------|--------------------------------------------------|--------------------------|------------------|---------------------------------|
| GET    | `/peminjam/dashboard`                           | -                        | View             | Dashboard peminjam               |
| GET    | `/peminjam/katalog`                             | `PeminjamanController`   | `katalog`        | Katalog alat tersedia            |
| POST   | `/peminjam/peminjaman`                          | `PeminjamanController`   | `store`          | Ajukan peminjaman baru           |
| GET    | `/peminjam/peminjaman-saya`                     | `PeminjamanController`   | `peminjamanSaya` | Riwayat peminjaman saya          |
| POST   | `/peminjam/peminjaman/{id}/ajukan-pengembalian` | `PeminjamanController`   | `requestReturn`  | Ajukan pengembalian alat         |

---

## 10. Validasi Data

### 10.1 Validasi Pengguna

| Field      | Aturan Validasi                            | Pesan Error                        |
|------------|--------------------------------------------|------------------------------------|
| `nama`     | `required`, `string`, `max:255`            | Nama wajib diisi                   |
| `email`    | `required`, `email`, `unique:pengguna`     | Email wajib diisi / sudah terdaftar|
| `password` | `required`, `string`, `min:8`              | Kata sandi wajib diisi             |
| `peran`    | `required`, `in:admin,petugas,peminjam`    | Peran wajib dipilih                |

### 10.2 Validasi Kategori

| Field           | Aturan Validasi              | Pesan Error                   |
|-----------------|------------------------------|-------------------------------|
| `nama_kategori` | `required`, `string`, `max:255` | Nama kategori wajib diisi   |

### 10.3 Validasi Alat

| Field         | Aturan Validasi                                    | Pesan Error                              |
|---------------|-----------------------------------------------------|------------------------------------------|
| `nama_alat`   | `required`, `string`, `max:255`                    | Nama alat wajib diisi                    |
| `kategori_id` | `required`, `exists:kategori,id`                   | Kategori wajib dipilih / tidak valid     |
| `stok`        | `required`, `integer`, `min:0`                     | Stok wajib diisi / harus angka / min 0   |
| `deskripsi`   | `nullable`, `string`                                | —                                        |
| `gambar`      | `nullable`, `image`, `mimes:jpeg,png,jpg,gif`, `max:2048` | File harus gambar / maks 2MB        |

### 10.4 Validasi Peminjaman

| Field           | Aturan Validasi                        | Pesan Error                                     |
|-----------------|------------------------------------------|--------------------------------------------------|
| `alat_id`       | `required`, `exists:alat,id`            | Alat wajib dipilih / tidak valid                 |
| `tanggal_pinjam`| `required`, `date`, `after_or_equal:today` | Tanggal pinjam wajib diisi / min hari ini      |
| `durasi`        | `required`, `integer`, `min:1`, `max:14`| Durasi wajib diisi / min 1 hari / maks 14 hari   |

---

*Dokumen ini dibuat sebagai bagian dari Laporan Proyek UKK — Aplikasi Peminjaman Alat Sekolah*
*Tanggal: 24 Februari 2026*
