# DOKUMENTASI DATABASE, PROYEK, DAN CODING GUIDELINES
## Aplikasi Peminjaman Alat Sekolah

---

## DAFTAR ISI

- [Poin 5: Pembuatan Database dari ERD](#poin-5-pembuatan-database-dari-erd)
- [Poin 6: Operasi Relasional, Stored Procedure, Function, Trigger, Commit & Rollback](#poin-6-operasi-relasional-stored-procedure-function-trigger-commit--rollback)
- [Poin 7: Folder Proyek dan Menjalankan Aplikasi](#poin-7-folder-proyek-dan-menjalankan-aplikasi)
- [Poin 8: Coding Guidelines dan Best Practices](#poin-8-coding-guidelines-dan-best-practices)

---

# Poin 5: Pembuatan Database dari ERD

## 5.1 Sistem Migrasi Laravel

Database dibuat menggunakan **sistem migrasi Laravel** — setiap tabel didefinisikan dalam file PHP yang bisa dijalankan berulang kali secara konsisten. Pendekatan ini lebih baik daripada membuat tabel secara manual di phpMyAdmin karena:

- ✅ **Reproducible** — database bisa dibuat ulang identik di komputer mana pun
- ✅ **Version Controlled** — perubahan database terlacak di Git
- ✅ **Reversible** — setiap migrasi memiliki method `up()` dan `down()`

## 5.2 Urutan Pembuatan Tabel

Tabel dibuat sesuai **urutan dependensi** (tabel induk dibuat terlebih dahulu):

| No | File Migrasi                              | Tabel yang Dibuat                        |
|----|-------------------------------------------|------------------------------------------|
| 1  | `0001_01_01_000000_buat_tabel_pengguna.php` | `pengguna`, `password_reset_tokens`, `sessions` |
| 2  | `0001_01_01_000001_create_cache_table.php` | `cache`, `cache_locks`                    |
| 3  | `2026_01_18_214530_buat_tabel_kategori.php` | `kategori`                               |
| 4  | `2026_01_18_214538_buat_tabel_alat.php`    | `alat` (FK → `kategori`)                |
| 5  | `2026_01_18_214550_buat_tabel_peminjaman.php` | `peminjaman` (FK → `pengguna`, `alat`) |
| 6  | `2026_01_18_214631_buat_tabel_log_aktivitas.php` | `log_aktivitas` (FK → `pengguna`)   |
| 7  | `2026_01_18_214712_buat_trigger_dan_prosedur.php` | Trigger, Function, Stored Procedure |

## 5.3 SQL Pembuatan Tabel

### Tabel 1: `pengguna`

```sql
CREATE TABLE pengguna (
    id          BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama        VARCHAR(255) NOT NULL,
    email       VARCHAR(255) NOT NULL UNIQUE,
    email_verified_at TIMESTAMP NULL,
    kata_sandi  VARCHAR(255) NOT NULL,
    peran       ENUM('admin', 'petugas', 'peminjam') NOT NULL,
    remember_token VARCHAR(100) NULL,
    created_at  TIMESTAMP NULL,
    updated_at  TIMESTAMP NULL
);
```

**Kode Migrasi Laravel:**
```php
Schema::create('pengguna', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('kata_sandi');
    $table->enum('peran', ['admin', 'petugas', 'peminjam']);
    $table->rememberToken();
    $table->timestamps();
});
```

### Tabel 2: `kategori`

```sql
CREATE TABLE kategori (
    id             BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kategori  VARCHAR(255) NOT NULL,
    created_at     TIMESTAMP NULL,
    updated_at     TIMESTAMP NULL
);
```

**Kode Migrasi Laravel:**
```php
Schema::create('kategori', function (Blueprint $table) {
    $table->id();
    $table->string('nama_kategori');
    $table->timestamps();
});
```

### Tabel 3: `alat`

```sql
CREATE TABLE alat (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kategori_id  BIGINT UNSIGNED NOT NULL,
    nama_alat    VARCHAR(255) NOT NULL,
    deskripsi    TEXT NULL,
    stok         INT NOT NULL DEFAULT 0,
    gambar       VARCHAR(255) NULL,
    created_at   TIMESTAMP NULL,
    updated_at   TIMESTAMP NULL,

    FOREIGN KEY (kategori_id) REFERENCES kategori(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
```

**Kode Migrasi Laravel:**
```php
Schema::create('alat', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kategori_id')->constrained('kategori')->onDelete('cascade');
    $table->string('nama_alat');
    $table->text('deskripsi')->nullable();
    $table->integer('stok')->default(0);
    $table->string('gambar')->nullable();
    $table->timestamps();
});
```

### Tabel 4: `peminjaman`

```sql
CREATE TABLE peminjaman (
    id                     BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengguna_id            BIGINT UNSIGNED NOT NULL,
    alat_id                BIGINT UNSIGNED NOT NULL,
    tanggal_pinjam         DATE NOT NULL,
    tanggal_wajib_kembali  DATE NOT NULL,
    tanggal_kembali        DATE NULL,
    status                 ENUM('diajukan','disetujui','sedang_dikembalikan','dikembalikan','ditolak') NOT NULL DEFAULT 'diajukan',
    denda                  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    created_at             TIMESTAMP NULL,
    updated_at             TIMESTAMP NULL,

    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (alat_id) REFERENCES alat(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
```

**Kode Migrasi Laravel:**
```php
Schema::create('peminjaman', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
    $table->foreignId('alat_id')->constrained('alat')->onDelete('cascade');
    $table->date('tanggal_pinjam');
    $table->date('tanggal_wajib_kembali');
    $table->date('tanggal_kembali')->nullable();
    $table->enum('status', [
        'diajukan', 'disetujui', 'sedang_dikembalikan', 'dikembalikan', 'ditolak'
    ])->default('diajukan');
    $table->decimal('denda', 10, 2)->default(0);
    $table->timestamps();
});
```

### Tabel 5: `log_aktivitas`

```sql
CREATE TABLE log_aktivitas (
    id           BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    pengguna_id  BIGINT UNSIGNED NOT NULL,
    aksi         VARCHAR(255) NOT NULL,
    deskripsi    TEXT NULL,
    created_at   TIMESTAMP NULL,
    updated_at   TIMESTAMP NULL,

    FOREIGN KEY (pengguna_id) REFERENCES pengguna(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);
```

**Kode Migrasi Laravel:**
```php
Schema::create('log_aktivitas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade');
    $table->string('aksi');
    $table->text('deskripsi')->nullable();
    $table->timestamps();
});
```

## 5.4 Menjalankan Migrasi

```bash
# Membuat semua tabel dari migrasi
php artisan migrate

# Output:
#   Migration table created successfully.
#   Running migrations...
#   0001_01_01_000000_buat_tabel_pengguna .......... DONE
#   0001_01_01_000001_create_cache_table ............ DONE
#   2026_01_18_214530_buat_tabel_kategori .......... DONE
#   2026_01_18_214538_buat_tabel_alat .............. DONE
#   2026_01_18_214550_buat_tabel_peminjaman ........ DONE
#   2026_01_18_214631_buat_tabel_log_aktivitas ..... DONE
#   2026_01_18_214712_buat_trigger_dan_prosedur .... DONE

# Mengisi data dummy
php artisan db:seed
```

---

# Poin 6: Operasi Relasional, Stored Procedure, Function, Trigger, Commit & Rollback

## 6.1 Operasi Relasional (Foreign Key)

### 6.1.1 Daftar Relasi Foreign Key

| No | Tabel Anak      | Kolom FK          | Tabel Induk | Kolom Referensi | ON DELETE | ON UPDATE |
|----|-----------------|-------------------|-------------|-----------------|-----------|-----------|
| 1  | `alat`          | `kategori_id`     | `kategori`  | `id`            | CASCADE   | CASCADE   |
| 2  | `peminjaman`    | `pengguna_id`     | `pengguna`  | `id`            | CASCADE   | CASCADE   |
| 3  | `peminjaman`    | `alat_id`         | `alat`      | `id`            | CASCADE   | CASCADE   |
| 4  | `log_aktivitas` | `pengguna_id`     | `pengguna`  | `id`            | CASCADE   | CASCADE   |

> **CASCADE** berarti jika data induk dihapus, data anak juga otomatis terhapus.

### 6.1.2 Contoh Query Relasional (JOIN)

```sql
-- Query 1: Tampilkan semua peminjaman beserta nama peminjam dan nama alat
SELECT
    p.id AS id_peminjaman,
    pg.nama AS nama_peminjam,
    a.nama_alat,
    p.tanggal_pinjam,
    p.tanggal_wajib_kembali,
    p.status,
    p.denda
FROM peminjaman p
INNER JOIN pengguna pg ON p.pengguna_id = pg.id
INNER JOIN alat a ON p.alat_id = a.id
ORDER BY p.created_at DESC;

-- Query 2: Tampilkan semua alat beserta nama kategorinya
SELECT
    a.id,
    a.nama_alat,
    k.nama_kategori,
    a.stok,
    a.deskripsi
FROM alat a
INNER JOIN kategori k ON a.kategori_id = k.id
WHERE a.stok > 0;

-- Query 3: Tampilkan log aktivitas dengan nama pengguna
SELECT
    la.id,
    pg.nama AS nama_pengguna,
    la.aksi,
    la.deskripsi,
    la.created_at
FROM log_aktivitas la
INNER JOIN pengguna pg ON la.pengguna_id = pg.id
ORDER BY la.created_at DESC
LIMIT 20;
```

### 6.1.3 Implementasi Relasi di Eloquent ORM

```php
// Di Laravel, relasi diakses via Eager Loading (menghindari N+1 query)

// Query: Ambil peminjaman + pengguna + alat (1 query JOIN, bukan 3 query terpisah)
$peminjaman = Peminjaman::with(['pengguna', 'alat'])->latest()->paginate(10);

// Query: Ambil alat + kategori
$alat = Alat::with('kategori')->where('stok', '>', 0)->paginate(12);

// Query: Ambil log aktivitas + pengguna
$logs = LogAktivitas::with('pengguna')->latest()->paginate(20);
```

## 6.2 Stored Procedure

### 6.2.1 Stored Procedure: `proses_persetujuan_peminjaman`

**Tujuan:** Memproses persetujuan peminjaman secara atomik (semua berhasil atau semua dibatalkan).

```sql
DELIMITER //
CREATE PROCEDURE proses_persetujuan_peminjaman(
    IN id_peminjaman INT,
    IN id_petugas INT
)
BEGIN
    -- Error handler: jika terjadi error, ROLLBACK semua perubahan
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;    -- ← Membatalkan semua perubahan jika ada error
    END;

    START TRANSACTION;   -- ← Memulai transaksi

    -- Langkah 1: Ubah status peminjaman menjadi 'disetujui'
    UPDATE peminjaman
    SET status = 'disetujui'
    WHERE id = id_peminjaman;

    -- Langkah 2: Catat log aktivitas
    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
    VALUES (
        id_petugas,
        'Setujui Peminjaman',
        CONCAT('Peminjaman ', id_peminjaman, ' disetujui'),
        NOW(), NOW()
    );

    COMMIT;   -- ← Menyimpan semua perubahan secara permanen
END //
DELIMITER ;
```

**Pemanggilan di Laravel:**
```php
DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
```

### 6.2.2 Stored Procedure: `proses_pengembalian`

**Tujuan:** Memproses pengembalian alat, menghitung denda, dan mencatat log secara atomik.

```sql
DELIMITER //
CREATE PROCEDURE proses_pengembalian(
    IN id_peminjaman INT,
    IN id_petugas INT
)
BEGIN
    DECLARE tanggal_wajib DATE;
    DECLARE nominal_denda DECIMAL(10,2);

    -- Error handler: ROLLBACK jika gagal
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;    -- ← ROLLBACK: membatalkan semua perubahan
    END;

    START TRANSACTION;   -- ← Memulai transaksi

    -- Langkah 1: Ambil tanggal batas kembali
    SELECT tanggal_wajib_kembali INTO tanggal_wajib
    FROM peminjaman
    WHERE id = id_peminjaman;

    -- Langkah 2: Hitung denda menggunakan FUNCTION
    SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());

    -- Langkah 3: Update data peminjaman
    UPDATE peminjaman
    SET status = 'dikembalikan',
        tanggal_kembali = CURDATE(),
        denda = nominal_denda
    WHERE id = id_peminjaman;

    -- Langkah 4: Catat log aktivitas
    IF id_petugas IS NOT NULL THEN
        INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
        VALUES (
            id_petugas,
            'Konfirmasi Pengembalian',
            CONCAT('Peminjaman ', id_peminjaman, ' dikembalikan. Denda: ', nominal_denda),
            NOW(), NOW()
        );
    END IF;

    COMMIT;   -- ← COMMIT: simpan semua perubahan permanen
END //
DELIMITER ;
```

**Pemanggilan di Laravel:**
```php
DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);
```

## 6.3 Function

### Function: `hitung_denda`

**Tujuan:** Menghitung nominal denda berdasarkan keterlambatan pengembalian.

```sql
DELIMITER //
CREATE FUNCTION hitung_denda(
    tanggal_wajib DATE,
    tanggal_kembali DATE
)
RETURNS DECIMAL(10,2)
DETERMINISTIC
BEGIN
    DECLARE denda DECIMAL(10,2);
    DECLARE hari_terlambat INT;

    SET denda = 0;

    IF tanggal_kembali > tanggal_wajib THEN
        SET hari_terlambat = DATEDIFF(tanggal_kembali, tanggal_wajib);
        SET denda = hari_terlambat * 5000;   -- Rp 5.000 per hari
    END IF;

    RETURN denda;
END //
DELIMITER ;
```

**Contoh pemanggilan langsung:**
```sql
-- Tepat waktu → denda = 0
SELECT hitung_denda('2026-02-20', '2026-02-20') AS denda;   -- Result: 0.00

-- Terlambat 3 hari → denda = 15000
SELECT hitung_denda('2026-02-20', '2026-02-23') AS denda;   -- Result: 15000.00

-- Lebih awal → denda = 0
SELECT hitung_denda('2026-02-20', '2026-02-18') AS denda;   -- Result: 0.00
```

## 6.4 Trigger

### Trigger 1: `kurangi_stok_setelah_disetujui`

**Tujuan:** Otomatis mengurangi stok alat ketika peminjaman disetujui.

```sql
DELIMITER //
CREATE TRIGGER kurangi_stok_setelah_disetujui
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'disetujui' AND OLD.status != 'disetujui' THEN
        UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
    END IF;
END //
DELIMITER ;
```

**Kapan berjalan:** Otomatis saat `UPDATE peminjaman SET status = 'disetujui'`

### Trigger 2: `tambah_stok_setelah_dikembalikan`

**Tujuan:** Otomatis menambah stok alat ketika alat dikembalikan.

```sql
DELIMITER //
CREATE TRIGGER tambah_stok_setelah_dikembalikan
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'dikembalikan' AND OLD.status != 'dikembalikan' THEN
        UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
    END IF;
END //
DELIMITER ;
```

**Kapan berjalan:** Otomatis saat `UPDATE peminjaman SET status = 'dikembalikan'`

## 6.5 Commit dan Rollback

### 6.5.1 Penjelasan COMMIT dan ROLLBACK

| Perintah     | Fungsi                                                              |
|--------------|---------------------------------------------------------------------|
| `START TRANSACTION` | Memulai blok transaksi — semua perubahan bersifat sementara |
| `COMMIT`     | Menyimpan **semua** perubahan dalam transaksi secara **permanen**    |
| `ROLLBACK`   | **Membatalkan semua** perubahan dalam transaksi (kembali ke kondisi awal) |

### 6.5.2 Implementasi dalam Stored Procedure

```sql
-- Contoh alur COMMIT (berhasil):
START TRANSACTION;
    UPDATE peminjaman SET status = 'disetujui' WHERE id = 1;     -- ✓ Berhasil
    INSERT INTO log_aktivitas (...) VALUES (...);                 -- ✓ Berhasil
COMMIT;    -- Kedua perubahan disimpan permanen ✅

-- Contoh alur ROLLBACK (gagal):
START TRANSACTION;
    UPDATE peminjaman SET status = 'disetujui' WHERE id = 1;     -- ✓ Berhasil
    INSERT INTO log_aktivitas (...) VALUES (...);                 -- ✗ ERROR!
ROLLBACK;  -- UPDATE di atas juga DIBATALKAN ❌ (status tetap 'diajukan')
```

### 6.5.3 Diagram Alur COMMIT vs ROLLBACK

```
   START TRANSACTION
          │
    ┌─────┴─────┐
    ▼           ▼
 [Query 1]  [Query 1]
    │           │
    ▼           ▼
 [Query 2]  [Query 2] → ERROR!
    │           │
    ▼           ▼
  COMMIT     ROLLBACK
    │           │
    ▼           ▼
 Semua      Semua
 tersimpan  dibatalkan
 permanen   (undo)
```

### 6.5.4 Error Handler dalam Stored Procedure

```sql
-- Pola yang digunakan di kedua stored procedure:
DECLARE EXIT HANDLER FOR SQLEXCEPTION
BEGIN
    ROLLBACK;   -- Jika APAPUN gagal → batalkan SEMUA perubahan
END;

-- Ini menjamin konsistensi data:
-- - Status peminjaman tidak berubah tanpa log tercatat
-- - Denda tidak terupdate tanpa status berubah
-- - Semua operasi bersifat "all or nothing"
```

---

# Poin 7: Folder Proyek dan Menjalankan Aplikasi

## 7.1 Struktur Folder Proyek

```
d:\MUHAMAD SYIARIL ISLAMI 2026\UKK\peminjaman\
│
├── app/                          ← Kode aplikasi utama
│   ├── Http/
│   │   ├── Controllers/          ← 6 Controller (logika bisnis)
│   │   │   ├── AuthController.php
│   │   │   ├── PenggunaController.php
│   │   │   ├── KategoriController.php
│   │   │   ├── AlatController.php
│   │   │   ├── PeminjamanController.php
│   │   │   └── LogAktivitasController.php
│   │   └── Middleware/           ← 1 Middleware (hak akses)
│   │       └── RoleMiddleware.php
│   ├── Models/                   ← 5 Model Eloquent
│   │   ├── Pengguna.php
│   │   ├── Kategori.php
│   │   ├── Alat.php
│   │   ├── Peminjaman.php
│   │   └── LogAktivitas.php
│   └── Providers/                ← Service Provider
│       └── AppServiceProvider.php
│
├── bootstrap/                    ← Bootstrap framework
│   └── app.php
│
├── config/                       ← Konfigurasi aplikasi
│   ├── app.php
│   ├── auth.php                  ← Konfigurasi autentikasi (model Pengguna)
│   ├── database.php
│   └── ...
│
├── database/                     ← Database
│   ├── migrations/               ← 7 file migrasi (pembuatan tabel)
│   │   ├── 0001_01_01_000000_buat_tabel_pengguna.php
│   │   ├── 0001_01_01_000001_create_cache_table.php
│   │   ├── 2026_01_18_214530_buat_tabel_kategori.php
│   │   ├── 2026_01_18_214538_buat_tabel_alat.php
│   │   ├── 2026_01_18_214550_buat_tabel_peminjaman.php
│   │   ├── 2026_01_18_214631_buat_tabel_log_aktivitas.php
│   │   └── 2026_01_18_214712_buat_trigger_dan_prosedur.php
│   └── seeders/                  ← Data dummy
│       └── DatabaseSeeder.php
│
├── public/                       ← File publik (entry point)
│   ├── index.php                 ← Front controller
│   └── storage/                  ← Symlink ke storage (gambar)
│
├── resources/                    ← Resource frontend
│   ├── css/
│   │   └── app.css               ← Stylesheet utama (Tailwind CSS)
│   ├── js/
│   │   └── app.js                ← JavaScript utama
│   └── views/                    ← 21 Blade Template
│       ├── admin/                ← 9 halaman admin
│       │   ├── dashboard.blade.php
│       │   ├── pengguna/ (index, create, edit)
│       │   ├── kategori/ (index, create, edit)
│       │   ├── alat/ (index, create, edit)
│       │   ├── peminjaman/ (index)
│       │   └── log_aktivitas/ (index)
│       ├── petugas/              ← 3 halaman petugas
│       │   ├── dashboard.blade.php
│       │   ├── peminjaman/ (index)
│       │   └── laporan.blade.php
│       ├── peminjam/             ← 3 halaman peminjam
│       │   ├── dashboard.blade.php
│       │   ├── alat/ (index)
│       │   └── peminjaman/ (index)
│       ├── auth/
│       │   └── login.blade.php
│       ├── layouts/
│       │   └── app.blade.php     ← Layout utama (header, sidebar, footer)
│       └── vendor/pagination/
│           └── custom.blade.php  ← Custom pagination component
│
├── routes/                       ← Definisi URL
│   └── web.php                   ← 32 route aplikasi
│
├── storage/                      ← File storage (upload gambar)
│   └── app/public/alat/          ← Gambar alat yang diupload
│
├── tests/                        ← Unit & Feature test
│
├── docs/                         ← Dokumentasi proyek
│   ├── 01_struktur_data_dan_control_program.md
│   ├── 02_laporan_metode_waterfall.md
│   ├── 03_flowchart_dan_pseudocode.md
│   ├── 04_dokumentasi_modul.md
│   └── 05_database_proyek_guidelines.md  ← (dokumen ini)
│
├── .env                          ← Konfigurasi environment (database, dll)
├── composer.json                 ← Dependensi PHP
├── package.json                  ← Dependensi JavaScript
├── vite.config.js                ← Konfigurasi build tool
└── tailwind.config.js            ← Konfigurasi Tailwind CSS
```

## 7.2 Aplikasi yang Diperlukan (Prerequisites)

| No | Aplikasi             | Versi Minimum | Fungsi                            |
|----|----------------------|---------------|-----------------------------------|
| 1  | PHP                  | 8.2+          | Runtime bahasa pemrograman        |
| 2  | Composer             | 2.x           | Package manager PHP               |
| 3  | Node.js              | 18+           | Runtime JavaScript (build tool)   |
| 4  | NPM                  | 9+            | Package manager JavaScript        |
| 5  | MySQL Server         | 8.0           | Database server                   |
| 6  | Git                  | 2.x           | Version control                   |
| 7  | Text Editor / IDE    | -             | VS Code (disarankan)              |

## 7.3 Langkah Menjalankan Aplikasi

### Langkah 1: Clone / persiapan proyek

```bash
# Clone dari repository (jika ada)
git clone https://github.com/username/peminjaman.git
cd peminjaman
```

### Langkah 2: Install dependensi

```bash
# Install dependensi PHP (Laravel framework, dll)
composer install

# Install dependensi JavaScript (Tailwind CSS, Vite, dll)
npm install
```

### Langkah 3: Konfigurasi environment

```bash
# Salin file environment
cp .env.example .env

# Generate application key
php artisan key:generate
```

**Edit file `.env` untuk database:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman
DB_USERNAME=root
DB_PASSWORD=
```

### Langkah 4: Buat database dan jalankan migrasi

```bash
# Buat database 'peminjaman' di MySQL terlebih dahulu
# Lalu jalankan migrasi untuk membuat tabel
php artisan migrate

# Isi data dummy (opsional, untuk testing)
php artisan db:seed
```

### Langkah 5: Link storage untuk gambar

```bash
php artisan storage:link
```

### Langkah 6: Jalankan aplikasi

```bash
# Terminal 1: Jalankan server PHP (backend)
php artisan serve
# → Server berjalan di http://localhost:8000

# Terminal 2: Jalankan Vite (frontend build tool)
npm run dev
# → Vite dev server berjalan untuk hot-reload CSS/JS
```

### Langkah 7: Akses aplikasi

Buka browser dan akses: **http://localhost:8000**

**Kredensial login:**

| Peran     | Email                          | Password   |
|-----------|--------------------------------|------------|
| Admin     | `admin@admin.com`              | `password` |
| Petugas   | `budi.petugas@sekolah.com`     | `password` |
| Peminjam  | `andi.pratama@siswa.com`       | `password` |

---

# Poin 8: Coding Guidelines dan Best Practices

## 8.1 Query yang Efisien (Halaman Memuat Cepat)

### 8.1.1 Eager Loading — Mencegah N+1 Query Problem

**❌ Masalah: N+1 Query (LAMBAT)**
```php
// Ini menghasilkan 1 + N query (N = jumlah peminjaman)
$peminjaman = Peminjaman::all();
foreach ($peminjaman as $p) {
    echo $p->pengguna->nama;  // Setiap iterasi = 1 query tambahan!
    echo $p->alat->nama_alat; // Setiap iterasi = 1 query lagi!
}
// Jika ada 100 peminjaman → 1 + 100 + 100 = 201 query! ❌
```

**✅ Solusi: Eager Loading (CEPAT)**
```php
// Ini menghasilkan HANYA 3 query (1 peminjaman + 1 pengguna + 1 alat)
$peminjaman = Peminjaman::with(['pengguna', 'alat'])->get();
foreach ($peminjaman as $p) {
    echo $p->pengguna->nama;  // Tidak ada query tambahan!
    echo $p->alat->nama_alat; // Tidak ada query tambahan!
}
// Hasilnya SELALU 3 query, tidak peduli berapa jumlah data! ✅
```

**Implementasi dalam proyek:**
```php
// PeminjamanController — selalu eager load relasi
Peminjaman::with(['pengguna', 'alat'])->latest()->paginate(10);

// AlatController — eager load kategori
Alat::with('kategori')->latest()->paginate(10);

// LogAktivitasController — eager load pengguna
LogAktivitas::with('pengguna')->latest()->paginate(20);
```

### 8.1.2 Indexing — Query Filter yang Cepat

```php
// Kolom yang sering di-filter sudah diindex:
// - pengguna.email (UNIQUE INDEX) → login cepat
// - peminjaman.pengguna_id (FOREIGN KEY INDEX) → filter per user cepat
// - peminjaman.alat_id (FOREIGN KEY INDEX) → cek peminjaman per alat cepat
// - alat.kategori_id (FOREIGN KEY INDEX) → filter per kategori cepat
```

### 8.1.3 Stored Procedure — Mengurangi Round Trip

```php
// ❌ BURUK: 3 round trip ke database
DB::beginTransaction();
DB::update('UPDATE peminjaman SET status = ...', [...]);     // Round trip 1
DB::insert('INSERT INTO log_aktivitas ...', [...]);          // Round trip 2
DB::commit();                                                 // Round trip 3

// ✅ BAIK: 1 round trip (semua diproses di server database)
DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$id, $petugasId]);
// → 1 call, semua operasi di dalam MySQL server
```

## 8.2 Menghindari Looping yang Tidak Perlu

### 8.2.1 Mass Assignment — Bukan Loop INSERT

```php
// ❌ BURUK: Loop untuk insert banyak data
foreach ($data as $item) {
    Pengguna::create($item);  // N query untuk N data
}

// ✅ BAIK: Mass insert
Pengguna::insert($data);  // 1 query untuk semua data
```

### 8.2.2 Query Builder — Bukan Loop Filter

```php
// ❌ BURUK: Ambil semua, lalu filter di PHP
$semuaAlat = Alat::all();  // Ambil SEMUA dari DB
$alatTersedia = [];
foreach ($semuaAlat as $alat) {
    if ($alat->stok > 0) {
        $alatTersedia[] = $alat;  // Filter di PHP (lambat jika ribuan data)
    }
}

// ✅ BAIK: Filter langsung di query SQL
$alatTersedia = Alat::where('stok', '>', 0)->get();
// → MySQL yang filter, PHP hanya terima yang sudah difilter
```

### 8.2.3 Trigger — Bukan Loop Update Stok

```php
// ❌ BURUK: Update stok manual di controller
$peminjaman->update(['status' => 'disetujui']);
$alat = $peminjaman->alat;
$alat->stok = $alat->stok - 1;  // Harus ingat update stok setiap kali!
$alat->save();

// ✅ BAIK: Trigger otomatis (implementasi saat ini)
DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$id, $petugasId]);
// → Trigger otomatis update stok, tidak perlu kode tambahan
// → Konsistensi terjaga, tidak ada risiko lupa update stok
```

## 8.3 Menggunakan LIMIT untuk Data Besar (Paginasi)

### 8.3.1 Paginasi — Bukan Load Semua Data

```php
// ❌ BURUK: Load semua data sekaligus
$pengguna = Pengguna::all();  // Jika 10.000 user → memori penuh!

// ✅ BAIK: Paginasi dengan LIMIT (implementasi saat ini)
$pengguna = Pengguna::latest()->paginate(10);
// → Hanya ambil 10 data per halaman
// → SQL: SELECT * FROM pengguna ORDER BY created_at DESC LIMIT 10 OFFSET 0
```

### 8.3.2 Implementasi Paginasi di Setiap Controller

| Controller               | Method                 | Limit per Page | Query                                         |
|--------------------------|------------------------|----------------|-----------------------------------------------|
| `PenggunaController`     | `index()`              | 10             | `Pengguna::latest()->paginate(10)`            |
| `KategoriController`     | `index()`              | 10             | `Kategori::latest()->paginate(10)`            |
| `AlatController`         | `index()`              | 10             | `Alat::with('kategori')->latest()->paginate(10)` |
| `PeminjamanController`   | `index()` (admin)      | 10             | `Peminjaman::with([...])->latest()->paginate(10)` |
| `PeminjamanController`   | `katalog()`            | 12             | `Alat::where('stok','>',0)->paginate(12)`     |
| `PeminjamanController`   | `peminjamanSaya()`     | 10             | `Peminjaman::where(...)->paginate(10)`        |
| `PeminjamanController`   | `laporan()`            | 20             | `Peminjaman::with([...])->paginate(20)`       |
| `LogAktivitasController` | `index()`              | 20             | `LogAktivitas::with('pengguna')->paginate(20)` |

### 8.3.3 Custom Pagination Component

Paginasi ditampilkan menggunakan komponen Blade custom di `vendor/pagination/custom.blade.php` untuk tampilan yang konsisten dan modern di seluruh halaman.

## 8.4 Praktik Keamanan

### 8.4.1 Password Hashing

```php
// Password TIDAK PERNAH disimpan sebagai plain text
// Laravel secara otomatis hash menggunakan bcrypt

// Di model Pengguna:
protected function casts(): array {
    return [
        'kata_sandi' => 'hashed',  // ← Auto hash saat mass assignment
    ];
}

// Contoh: input "password" → tersimpan sebagai:
// "$2y$12$Xm3R5q8w..." (60 karakter hash yang tidak bisa di-reverse)
```

### 8.4.2 CSRF Protection

```html
<!-- Semua form menggunakan @csrf directive -->
<form method="POST" action="{{ route('login') }}">
    @csrf    <!-- ← Token CSRF otomatis, mencegah Cross-Site Request Forgery -->
    ...
</form>
```

### 8.4.3 Validasi Input

```php
// Setiap input form divalidasi sebelum diproses
$request->validate([
    'email' => 'required|email|unique:pengguna',    // Format email valid + unik
    'kata_sandi' => 'required|min:6',               // Minimal 6 karakter
    'peran' => 'required|in:admin,petugas,peminjam', // Hanya nilai yang diizinkan
    'stok' => 'required|integer|min:0',              // Tidak boleh negatif
    'gambar' => 'nullable|image|mimes:jpg,png|max:2048', // Hanya gambar, maks 2MB
]);
```

### 8.4.4 Route Protection (Middleware)

```php
// Setiap route dilindungi middleware
Route::middleware(['auth', 'peran:admin'])->group(...);    // Hanya admin
Route::middleware(['auth', 'peran:petugas'])->group(...);  // Hanya petugas
Route::middleware(['auth', 'peran:peminjam'])->group(...); // Hanya peminjam
```

## 8.5 Ringkasan Best Practices yang Diterapkan

| No | Practice                    | Implementasi                                     | Manfaat              |
|----|-----------------------------|--------------------------------------------------|----------------------|
| 1  | Eager Loading               | `with(['pengguna', 'alat'])`                     | Cegah N+1 query      |
| 2  | Paginasi (LIMIT)            | `paginate(10)` di semua controller               | Hemat memori         |
| 3  | Query Filter di SQL         | `where('stok', '>', 0)`                          | Cegah loop di PHP    |
| 4  | Stored Procedure            | `CALL proses_persetujuan_peminjaman(...)`         | 1 round trip         |
| 5  | Trigger                     | `kurangi_stok_setelah_disetujui`                  | Cegah inkonsistensi  |
| 6  | Transaction                 | `START TRANSACTION ... COMMIT / ROLLBACK`         | Atomik               |
| 7  | Password Hashing            | `'kata_sandi' => 'hashed'`                       | Keamanan data        |
| 8  | CSRF Token                  | `@csrf` di semua form                            | Cegah CSRF attack    |
| 9  | Input Validation            | `$request->validate([...])`                      | Cegah injection      |
| 10 | Middleware RBAC             | `peran:admin,petugas,peminjam`                   | Kontrol akses        |
| 11 | MVC Architecture            | Model, View, Controller terpisah                 | Maintainability      |
| 12 | Route Model Binding         | `edit(Pengguna $pengguna)`                       | Clean code           |
| 13 | Mass Assignment Protection  | `$fillable` di setiap model                      | Cegah mass assign    |
| 14 | File Storage                | `Storage::disk('public')` + symlink              | Pengelolaan file     |
| 15 | Foreign Key Constraints     | `ON DELETE CASCADE`                              | Integritas data      |

---

*Dokumen ini merupakan bagian dari Laporan Proyek UKK — Aplikasi Peminjaman Alat Sekolah*
*Tanggal: 24 Februari 2026*
