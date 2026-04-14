# 🛠️ Panduan Membuat Project UKK dari Nol
## Disesuaikan dengan 11 Langkah Kerja SPK

Panduan ini mengikuti urutan **Langkah Kerja UKK** secara persis, sehingga setiap poin terpenuhi.

---

## 📋 Peta Langkah Kerja → Implementasi

| Langkah Kerja | Apa yang Dikerjakan | Hasil / Deliverable |
|:---:|---|---|
| **1** | Struktur data, tipe data, control program | Dokumen `01_struktur_data_dan_control_program.md` |
| **2** | Metode Waterfall (Analisis → Desain → Implementasi → Uji → Dokumentasi) | Dokumen `02_laporan_metode_waterfall.md` |
| **3** | Flowchart & pseudocode (login, pinjam, kembali+denda) | Dokumen `03_flowchart_dan_pseudocode.md` |
| **4** | Dokumentasi modul (Input-Proses-Output) | Dokumen `04_dokumentasi_modul.md` |
| **5** | Buat database dari ERD | Database MySQL `peminjaman` |
| **6** | Tabel + relasi + SP + function + trigger + commit/rollback | Migration `buat_trigger_dan_prosedur.php` |
| **7** | Folder project + jalankan aplikasi | Folder `peminjaman/` + `php artisan serve` |
| **8** | Coding guidelines & best practices | Dokumen `05_database_proyek_guidelines.md` |
| **9** | Kebutuhan uji coba (min. 5 skenario) | Dokumen `06_pengujian_dan_test_case.md` |
| **10** | Lakukan pengujian + tangkapan layar | Screenshot di `docs/screenshots/` |
| **11** | Laporan singkat (fitur OK, bug, rencana) | Dokumen `07_laporan_singkat.md` |

---

## 📦 Deliverable yang Dikumpulkan

```
📁 Folder Pengumpulan UKK/
├── 📁 peminjaman/                    ← [1] Folder Proyek Aplikasi
│   ├── app/
│   ├── database/
│   ├── resources/
│   ├── routes/
│   └── ...
├── 📄 peminjaman.sql                 ← [2] Database (export .sql)
├── 📁 docs/                          ← [3] Dokumentasi
│   ├── 01_struktur_data_dan_control_program.md    (ERD + Deskripsi)
│   ├── 02_laporan_metode_waterfall.md             (Analisis-Desain)
│   ├── 03_flowchart_dan_pseudocode.md             (Flowchart)
│   ├── 04_dokumentasi_modul.md                    (Fungsi/Prosedur)
│   ├── 05_database_proyek_guidelines.md           (Debugging+Guidelines)
│   ├── 06_pengujian_dan_test_case.md              (Pengujian+Screenshot)
│   ├── 07_laporan_singkat.md                      (Laporan Evaluasi)
│   └── screenshots/                               (Tangkapan Layar)
└── 📄 00_checklist_pengumpulan.md    ← [4] Laporan Evaluasi Singkat
```

---

# LANGKAH KERJA 1
## Struktur Data, Akses Data, Tipe Data & Control Program

**Tujuan:** Merancang seluruh struktur data (tabel database), tipe data setiap kolom, dan bagaimana program mengakses/mengendalikan data tersebut.

### 1.1 Tentukan Entitas (Tabel)

Identifikasi 5 entitas utama dari kebutuhan bisnis:

| No | Entitas | Fungsi |
|----|---------|--------|
| 1 | `pengguna` | Menyimpan akun pengguna (admin, petugas, peminjam) |
| 2 | `kategori` | Mengelompokkan jenis alat |
| 3 | `alat` | Menyimpan data alat yang bisa dipinjam |
| 4 | `peminjaman` | Menyimpan transaksi peminjaman |
| 5 | `log_aktivitas` | Mencatat jejak aktivitas (audit trail) |

### 1.2 Tentukan Tipe Data Setiap Kolom

Contoh tabel `peminjaman` (tabel paling kompleks):

| Kolom | Tipe Data | Constraint | Alasan Pemilihan Tipe |
|-------|-----------|------------|-----------------------|
| `id` | `BIGINT UNSIGNED` | PK, AUTO_INCREMENT | Integer besar untuk ID unik |
| `pengguna_id` | `BIGINT UNSIGNED` | FK → `pengguna(id)` | Merujuk ke siapa yang meminjam |
| `alat_id` | `BIGINT UNSIGNED` | FK → `alat(id)` | Merujuk ke alat yang dipinjam |
| `jumlah` | `INTEGER` | DEFAULT 1 | Jumlah alat yang dipinjam |
| `tanggal_pinjam` | `DATE` | NOT NULL | Hanya perlu tanggal, bukan waktu |
| `tanggal_wajib_kembali` | `DATE` | NOT NULL | Batas akhir pengembalian |
| `tanggal_kembali` | `DATE` | NULLABLE | Diisi saat dikembalikan |
| `status` | `ENUM(...)` | DEFAULT 'diajukan' | Membatasi nilai valid |
| `denda` | `DECIMAL(10,2)` | DEFAULT 0 | Presisi untuk mata uang |

> [!NOTE]
> Untuk daftar lengkap tipe data seluruh tabel, lihat [01_struktur_data_dan_control_program.md](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/docs/01_struktur_data_dan_control_program.md) Bagian 3.

### 1.3 Tentukan Control Program

Control program = bagaimana alur request berjalan dalam aplikasi. Laravel menggunakan pola **MVC**:

```
Browser → Routes (web.php) → Middleware (cek login + peran)
    → Controller (logika bisnis) → Model (akses database)
    → View (tampilan HTML) → Browser
```

**Komponen kontrol:**

| Komponen | File | Fungsi |
|----------|------|--------|
| **Routes** | `routes/web.php` | Peta URL → Controller |
| **Middleware** | `RoleMiddleware.php` | Filter akses berdasarkan peran |
| **Controller** | 6 file di `app/Http/Controllers/` | Logika bisnis |
| **Model** | 5 file di `app/Models/` | Akses/manipulasi data |
| **View** | 21 file Blade di `resources/views/` | Tampilan HTML |

### 1.4 Matriks Hak Akses (Control Access)

| Fitur | Admin | Petugas | Peminjam |
|-------|:-----:|:-------:|:--------:|
| Kelola Pengguna (CRUD) | ✅ | ❌ | ❌ |
| Kelola Kategori (CRUD) | ✅ | ❌ | ❌ |
| Kelola Alat (CRUD) | ✅ | ❌ | ❌ |
| Lihat Log Aktivitas | ✅ | ❌ | ❌ |
| Lihat Semua Peminjaman | ✅ | ✅ | ❌ |
| Setujui/Tolak Peminjaman | ✅ | ✅ | ❌ |
| Konfirmasi Pengembalian | ✅ | ✅ | ❌ |
| Cetak Laporan | ❌ | ✅ | ❌ |
| Lihat Katalog | ❌ | ❌ | ✅ |
| Ajukan Peminjaman | ❌ | ❌ | ✅ |
| Ajukan Pengembalian | ❌ | ❌ | ✅ |

---

# LANGKAH KERJA 2
## Metode Waterfall Sederhana

**Tujuan:** Mengikuti tahapan pengembangan secara berurutan.

```
┌───────────────────┐
│  a. Analisis      │  ← Apa yang dibutuhkan?
│     Kebutuhan     │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  b. Desain        │  ← Bagaimana strukturnya?
│     (ERD + Diagram)│
└────────┬──────────┘
         ▼
┌───────────────────┐
│  c. Implementasi  │  ← Tulis kodenya!
│     Kode          │
└────────┬──────────┘
         ▼
┌───────────────────┐
│  d. Pengujian     │  ← Apakah berjalan benar?
└────────┬──────────┘
         ▼
┌───────────────────┐
│  e. Dokumentasi   │  ← Catat semuanya
└───────────────────┘
```

### 2a. Analisis Kebutuhan

Sebelum coding, jawab pertanyaan ini:

**Masalah apa yang diselesaikan?**
- Pencatatan peminjaman alat sekolah masih manual (buku)
- Stok sulit dipantau real-time
- Perhitungan denda keterlambatan rawan salah

**Siapa penggunanya (Aktor)?**

| Aktor | Deskripsi | Fitur Utama |
|-------|-----------|-------------|
| Admin | Pengelola sistem | CRUD semua data master + log |
| Petugas | Petugas peminjaman | Setujui/tolak/konfirmasi kembali |
| Peminjam | Siswa/guru | Lihat katalog, ajukan pinjam/kembali |

**Kebutuhan Fungsional (FR):**

| Kode | Kebutuhan |
|------|-----------|
| FR-01 | Semua aktor dapat login dan logout |
| FR-02 | Admin bisa CRUD pengguna |
| FR-03 | Admin bisa CRUD kategori |
| FR-04 | Admin bisa CRUD alat (+ upload gambar) |
| FR-05 | Peminjam bisa lihat katalog alat tersedia (stok > 0) |
| FR-06 | Peminjam bisa ajukan peminjaman |
| FR-07 | Petugas/Admin bisa setujui/tolak peminjaman |
| FR-08 | Peminjam bisa ajukan pengembalian |
| FR-09 | Petugas/Admin bisa konfirmasi pengembalian + hitung denda otomatis |
| FR-10 | Stok otomatis berkurang/bertambah saat disetujui/dikembalikan |
| FR-11 | Denda dihitung otomatis Rp 5.000/hari keterlambatan |
| FR-12 | Log aktivitas tercatat otomatis |

**Kebutuhan Non-Fungsional (NFR):**

| Kode | Kebutuhan |
|------|-----------|
| NFR-01 | Password di-hash menggunakan bcrypt |
| NFR-02 | Hak akses berbasis peran (RBAC) |
| NFR-03 | Proses disetujui/kembali bersifat transaksional (COMMIT/ROLLBACK) |
| NFR-04 | UI responsif (Tailwind CSS) |
| NFR-05 | Paginasi untuk data besar |

### 2b. Desain (ERD dan Diagram Program)

**ERD (Entity Relationship Diagram):**

```
  ┌──────────┐         ┌──────────┐
  │ PENGGUNA │         │ KATEGORI │
  │──────────│         │──────────│
  │ PK id    │──┐      │ PK id    │──┐
  │ nama     │  │      │ nama_    │  │
  │ email    │  │      │ kategori │  │
  │ kata_sandi│ │      └──────────┘  │
  │ peran    │  │                     │ 1:N
  └──────────┘  │                     │
                │      ┌──────────┐   │
         1:N    │      │   ALAT   │   │
                │      │──────────│   │
                │      │ PK id    │   │
                │      │FK kategori_id│←┘
                │      │ nama_alat│
                │      │ stok     │──┐
                │      │ gambar   │  │
                │      └──────────┘  │
                │                     │ 1:N
  ┌──────────────┐  ┌────────────────┘
  │LOG_AKTIVITAS │  │  ┌──────────────────┐
  │──────────────│  │  │   PEMINJAMAN     │
  │ PK id       │  │  │──────────────────│
  │FK pengguna_id│←┘  │ PK id            │
  │ aksi        │  └──│FK pengguna_id    │
  │ deskripsi   │     │FK alat_id        │←┘
  └──────────────┘     │ tanggal_pinjam   │
   (ON DELETE CASCADE) │ tanggal_wajib    │
                       │ tanggal_kembali  │
                       │ status (ENUM)    │
                       │ denda (DECIMAL)  │
                       └──────────────────┘
```

**Relasi:**
- `pengguna` 1:N `peminjaman` (satu pengguna, banyak peminjaman)
- `pengguna` 1:N `log_aktivitas` (satu pengguna, banyak log)
- `kategori` 1:N `alat` (satu kategori, banyak alat)
- `alat` 1:N `peminjaman` (satu alat, banyak peminjaman)

**Diagram Alur Status Peminjaman:**

```
          ┌──────────┐
          │ diajukan │ ← status awal
          └────┬─────┘
      ┌────────┼─────────┐
      ▼                  ▼
┌──────────┐      ┌──────────┐
│disetujui │      │ ditolak  │ → SELESAI
└────┬─────┘      └──────────┘
     ▼
┌───────────────────┐
│sedang_dikembalikan│ ← peminjam ajukan kembali
└────────┬──────────┘
         ▼
┌──────────────┐
│ dikembalikan │ → SELESAI (denda dihitung)
└──────────────┘
```

> [!TIP]
> Untuk ERD detail lengkap dengan use case diagram dan activity diagram, lihat [02_laporan_metode_waterfall.md](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/docs/02_laporan_metode_waterfall.md) Bagian B.

### 2c–2e (Implementasi, Pengujian, Dokumentasi)

Dicakup di Langkah Kerja 5–11 di bawah.

---

# LANGKAH KERJA 3
## Flowchart & Pseudocode (3 Proses Utama)

### 3a. Proses Login

**Deskripsi:** Pengguna input email + password → validasi → redirect berdasarkan peran.

**Flowchart (ringkas):**
```
[MULAI]
   ↓
[Tampilkan Form Login]
   ↓
[Input email, password]
   ↓
<Format valid?> ──TIDAK──→ [Tampilkan error validasi] → [kembali]
   ↓ YA
[Cari pengguna di DB by email]
   ↓
<Email ditemukan & password cocok (bcrypt)?> ──TIDAK──→ [Tampilkan "Kredensial tidak cocok"]
   ↓ YA
[Regenerasi sesi]
   ↓
[Baca kolom peran]
   ↓
┌───────────┬──────────┬────────────┐
│ admin     │ petugas  │ peminjam   │
│ → /admin/ │ → /pet./ │ → /pem./  │
│ dashboard │ dashboard│ dashboard  │
└───────────┴──────────┴────────────┘
   ↓
[SELESAI]
```

**Pseudocode:**
```
PROGRAM Proses_Login
MULAI
    TAMPILKAN halaman_form_login
    MASUKAN email, password

    JIKA email KOSONG ATAU bukan_format_email MAKA
        TAMPILKAN error "Email wajib diisi dan harus valid"
        KEMBALI
    AKHIR JIKA

    SET pengguna ← CARI di tabel 'pengguna' DIMANA email = email_input
    JIKA pengguna TIDAK DITEMUKAN ATAU bcrypt_verify(password, pengguna.kata_sandi) = FALSE MAKA
        TAMPILKAN error "Kredensial tidak cocok"
        KEMBALI
    AKHIR JIKA

    REGENERASI sesi
    JIKA pengguna.peran = "admin" MAKA
        REDIRECT ke "/admin/dashboard"
    LAIN JIKA pengguna.peran = "petugas" MAKA
        REDIRECT ke "/petugas/dashboard"
    LAIN
        REDIRECT ke "/peminjam/dashboard"
    AKHIR JIKA
SELESAI
```

### 3b. Proses Peminjaman Alat

**Deskripsi:** Peminjam lihat katalog → pilih alat → isi form → simpan dengan status `diajukan` → petugas setujui/tolak → trigger kurangi stok.

**Pseudocode:**
```
PROGRAM Proses_Peminjaman

// === PEMINJAM ===
MULAI
    SET daftar_alat ← QUERY "SELECT * FROM alat WHERE stok > 0"
    TAMPILKAN daftar_alat (paginasi 12)

    MASUKAN alat_id, tanggal_pinjam, durasi (1-14 hari)

    // Validasi
    JIKA stok alat < 1 MAKA TAMPILKAN error "Stok habis"; KEMBALI
    JIKA tanggal_pinjam < HARI_INI MAKA TAMPILKAN error; KEMBALI

    SET tanggal_wajib_kembali ← tanggal_pinjam + durasi
    INSERT INTO peminjaman (pengguna_id, alat_id, tanggal_pinjam, tanggal_wajib_kembali, status='diajukan')

// === PETUGAS ===
    JIKA aksi = "SETUJUI" MAKA
        CALL proses_persetujuan_peminjaman(id, petugas_id)
        // → START TRANSACTION
        // → UPDATE status = 'disetujui'
        // → INSERT log_aktivitas
        // → COMMIT
        // → TRIGGER: stok = stok - 1

    LAIN JIKA aksi = "TOLAK" MAKA
        UPDATE status = 'ditolak'
    AKHIR JIKA
SELESAI
```

### 3c. Proses Pengembalian & Perhitungan Denda

**Deskripsi:** Peminjam ajukan pengembalian → petugas konfirmasi → stored procedure hitung denda → trigger tambah stok.

**Pseudocode:**
```
PROGRAM Proses_Pengembalian

// === TAHAP 1: PEMINJAM ===
    JIKA peminjaman.pengguna_id ≠ user_login MAKA TOLAK (403)
    JIKA peminjaman.status ≠ "disetujui" MAKA TAMPILKAN error
    UPDATE status = "sedang_dikembalikan"

// === TAHAP 2: PETUGAS KONFIRMASI ===
    CALL proses_pengembalian(id_peminjaman, id_petugas)
    // Di dalam Stored Procedure:
    //   START TRANSACTION
    //   SET tanggal_wajib ← SELECT tanggal_wajib_kembali
    //   SET denda ← hitung_denda(tanggal_wajib, HARI_INI)
    //      └→ JIKA terlambat: denda = hari × 5000, LAIN: 0
    //   UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=HARI_INI, denda=nominal
    //   INSERT log_aktivitas
    //   COMMIT
    //   TRIGGER: stok = stok + 1

SELESAI
```

**Contoh Perhitungan Denda:**

| Tanggal Wajib | Tanggal Kembali | Hari Terlambat | Denda |
|---------------|-----------------|:--------------:|------:|
| 2026-02-20 | 2026-02-18 | 0 (lebih awal) | Rp 0 |
| 2026-02-20 | 2026-02-20 | 0 (tepat waktu) | Rp 0 |
| 2026-02-20 | 2026-02-23 | 3 hari | Rp 15.000 |
| 2026-02-20 | 2026-03-02 | 10 hari | Rp 50.000 |

> [!TIP]
> Flowchart ASCII detail lengkap ada di [03_flowchart_dan_pseudocode.md](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/docs/03_flowchart_dan_pseudocode.md).

---

# LANGKAH KERJA 4
## Dokumentasi Modul (Input → Proses → Output)

Setiap **method/function** harus didokumentasikan dengan format ini:

### Contoh: Method `approve()` di PeminjamanController

| Aspek | Detail |
|-------|--------|
| **Fungsi** | Menyetujui pengajuan peminjaman |
| **Input** | `$peminjaman` — objek Peminjaman (Route Model Binding) |
| **Proses** | 1. Validasi status = `diajukan` → 2. Cek stok ≥ 1 → 3. `CALL proses_persetujuan_peminjaman(id, petugas_id)` → 4. Trigger otomatis: `stok = stok - 1` |
| **Output** | **Berhasil:** Redirect + flash "Peminjaman disetujui" / **Gagal:** Redirect + flash error |

### Contoh: Method `store()` di PeminjamanController

| Aspek | Detail |
|-------|--------|
| **Fungsi** | Mengajukan peminjaman alat baru |
| **Input** | `alat_id` (integer, required), `tanggal_pinjam` (date, ≥ hari ini), `durasi` (integer, 1-14) |
| **Proses** | 1. Validasi input → 2. Cek stok ≥ 1 → 3. Hitung `tgl_wajib_kembali = tgl_pinjam + durasi` → 4. INSERT peminjaman (status = 'diajukan') |
| **Output** | Redirect ke riwayat peminjaman + flash "Peminjaman berhasil diajukan" |

### Contoh: MySQL Function `hitung_denda()`

| Aspek | Detail |
|-------|--------|
| **Fungsi** | Menghitung denda keterlambatan pengembalian |
| **Input** | `tanggal_wajib` (DATE), `tanggal_kembali` (DATE) |
| **Proses** | JIKA `tanggal_kembali > tanggal_wajib` MAKA `denda = DATEDIFF × 5.000`, LAIN `denda = 0` |
| **Output** | `DECIMAL(10,2)` — nominal denda dalam Rupiah |

### Daftar Lengkap Modul

| No | Modul | Jumlah Method | File |
|----|-------|:-------------:|------|
| 1 | Autentikasi | 3 | `AuthController.php` |
| 2 | Pengelolaan Pengguna | 6 | `PenggunaController.php` |
| 3 | Pengelolaan Kategori | 6 | `KategoriController.php` |
| 4 | Pengelolaan Alat | 6 | `AlatController.php` |
| 5 | Pengelolaan Peminjaman | 9 | `PeminjamanController.php` |
| 6 | Log Aktivitas | 1 | `LogAktivitasController.php` |
| 7 | Middleware Hak Akses | 1 | `RoleMiddleware.php` |
| 8 | Database Logic | 5 | 2 SP + 1 Function + 2 Trigger |
| | **Total** | **37** | |

> [!IMPORTANT]
> Dokumentasi lengkap 37 method dengan format IPO (Input-Proses-Output) ada di [04_dokumentasi_modul.md](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/docs/04_dokumentasi_modul.md).

---

# LANGKAH KERJA 5
## Buat Database dari ERD

### 5.1 Buat Project Laravel + Database

```bash
# 1. Buat project baru
composer create-project laravel/laravel peminjaman
cd peminjaman

# 2. Install dependencies frontend
npm install

# 3. Buat database MySQL
mysql -u root -p -e "CREATE DATABASE peminjaman;"
```

### 5.2 Konfigurasi `.env`

```env
APP_NAME="Aplikasi Peminjaman Alat"
APP_TIMEZONE=Asia/Jakarta
APP_LOCALE=id
APP_FAKER_LOCALE=id_ID

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=peminjaman
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=database
```

### 5.3 Buat Migration (sesuai ERD)

**Urutan migration HARUS mengikuti dependensi FK:**

```bash
# 1. Pengguna (induk dari peminjaman & log)
# → Edit migration default 0001_01_01_000000_create_users_table.php
#    Rename ke: 0001_01_01_000000_buat_tabel_pengguna.php

# 2. Kategori (induk dari alat)
php artisan make:migration buat_tabel_kategori

# 3. Alat (FK → kategori)
php artisan make:migration buat_tabel_alat

# 4. Peminjaman (FK → pengguna, alat)
php artisan make:migration buat_tabel_peminjaman

# 5. Log Aktivitas (FK → pengguna)
php artisan make:migration buat_tabel_log_aktivitas

# 6. Trigger, Function, Stored Procedure
php artisan make:migration buat_trigger_dan_prosedur
```

**Isi migration tabel `pengguna`** (modifikasi dari default):

```php
Schema::create('pengguna', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('kata_sandi');           // bukan 'password'
    $table->enum('peran', ['admin', 'petugas', 'peminjam'])->default('peminjam');
    $table->rememberToken();
    $table->timestamps();
});
```

**Isi migration tabel `alat`:**

```php
Schema::create('alat', function (Blueprint $table) {
    $table->id();
    $table->foreignId('kategori_id')->constrained('kategori');
    $table->string('nama_alat');
    $table->text('deskripsi')->nullable();
    $table->integer('stok');
    $table->string('gambar')->nullable();
    $table->timestamps();
});
```

**Isi migration tabel `peminjaman`:**

```php
Schema::create('peminjaman', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengguna_id')->constrained('pengguna');
    $table->foreignId('alat_id')->constrained('alat');
    $table->integer('jumlah')->default(1);
    $table->date('tanggal_pinjam');
    $table->date('tanggal_wajib_kembali');
    $table->date('tanggal_kembali')->nullable();
    $table->enum('status', ['diajukan','disetujui','sedang_dikembalikan','dikembalikan','ditolak'])
          ->default('diajukan');
    $table->decimal('denda', 10, 2)->default(0);
    $table->timestamps();
});
```

### 5.4 Jalankan Migration

```bash
php artisan migrate
```

---

# LANGKAH KERJA 6
## Operasi Relasional, Stored Procedure, Function, Trigger, COMMIT & ROLLBACK

> [!IMPORTANT]
> Inilah yang membedakan project ini dari project CRUD biasa. Langkah ini menunjukkan penguasaan **database tingkat lanjut**.

### 6.1 Operasi Relasional (Foreign Key)

Sudah didefinisikan di migration:

```php
$table->foreignId('kategori_id')->constrained('kategori');     // alat → kategori
$table->foreignId('pengguna_id')->constrained('pengguna');     // peminjaman → pengguna
$table->foreignId('alat_id')->constrained('alat');             // peminjaman → alat
$table->foreignId('pengguna_id')->constrained('pengguna')
      ->onDelete('cascade');                                    // log → pengguna (CASCADE)
```

### 6.2 Trigger — Manajemen Stok Otomatis

```sql
-- Trigger 1: KURANGI stok saat peminjaman DISETUJUI
CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
        UPDATE alat SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END;

-- Trigger 2: TAMBAH stok saat alat DIKEMBALIKAN
CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
        UPDATE alat SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id;
    END IF;
END;
```

### 6.3 Function — Perhitungan Denda

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
END;
```

### 6.4 Stored Procedure + COMMIT & ROLLBACK

```sql
-- Procedure 1: Persetujuan peminjaman
CREATE PROCEDURE proses_persetujuan_peminjaman(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;   -- ← ROLLBACK jika error
    START TRANSACTION;                                  -- ← Mulai transaksi

    UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;

    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
    VALUES (id_petugas, "Setujui Peminjaman",
            CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());

    COMMIT;                                             -- ← COMMIT jika sukses
END;

-- Procedure 2: Pengembalian + hitung denda
CREATE PROCEDURE proses_pengembalian(IN id_peminjaman INT, IN id_petugas INT)
BEGIN
    DECLARE tanggal_wajib DATE;
    DECLARE nominal_denda DECIMAL(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;   -- ← ROLLBACK jika error
    START TRANSACTION;                                  -- ← Mulai transaksi

    SELECT tanggal_wajib_kembali INTO tanggal_wajib
    FROM peminjaman WHERE id = id_peminjaman;

    SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());  -- ← Panggil FUNCTION

    UPDATE peminjaman
    SET status = "dikembalikan", tanggal_kembali = CURDATE(), denda = nominal_denda
    WHERE id = id_peminjaman;

    IF id_petugas IS NOT NULL THEN
        INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
        VALUES (id_petugas, "Konfirmasi Pengembalian",
                CONCAT("Peminjaman ", id_peminjaman, " dikembalikan. Denda: ", nominal_denda),
                NOW(), NOW());
    END IF;

    COMMIT;                                             -- ← COMMIT jika sukses
END;
```

> [!WARNING]
> Di Laravel, semua SQL di atas ditulis dalam file migration menggunakan `DB::unprepared()`. Lihat file [buat_trigger_dan_prosedur.php](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/database/migrations/2026_01_18_214712_buat_trigger_dan_prosedur.php) untuk implementasi lengkap.

### 6.5 Cara Memanggil dari Controller (PHP)

```php
// Memanggil Stored Procedure dari Laravel
DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);
```

---

# LANGKAH KERJA 7
## Folder Project + Jalankan Aplikasi

### 7.1 Buat Folder Project (sudah dilakukan di Langkah 5)

```bash
composer create-project laravel/laravel peminjaman
cd peminjaman
```

### 7.2 Buat Komponen Aplikasi

**Urutan pembuatan yang direkomendasikan:**

```bash
# ── 1. MODEL (5 model) ──
php artisan make:model Pengguna    # extends Authenticatable, bukan Model!
php artisan make:model Kategori
php artisan make:model Alat
php artisan make:model Peminjaman
php artisan make:model LogAktivitas

# ── 2. CONTROLLER (6 controller) ──
php artisan make:controller AuthController
php artisan make:controller PenggunaController --resource
php artisan make:controller KategoriController --resource
php artisan make:controller AlatController --resource
php artisan make:controller PeminjamanController
php artisan make:controller LogAktivitasController

# ── 3. MIDDLEWARE (1 middleware) ──
php artisan make:middleware RoleMiddleware
```

### 7.3 Konfigurasi Autentikasi

**Edit `config/auth.php`** — ganti model:

```php
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\Pengguna::class,  // ← ganti dari User ke Pengguna
    ],
],
```

**Model Pengguna — Override auth password:**

```php
class Pengguna extends Authenticatable  // bukan extends Model!
{
    protected $table = 'pengguna';
    protected $fillable = ['nama', 'email', 'kata_sandi', 'peran'];

    public function getAuthPassword()
    {
        return $this->kata_sandi;  // beritahu Laravel kolom password kita
    }

    protected function casts(): array
    {
        return [
            'kata_sandi' => 'hashed',  // auto-hash saat set value
        ];
    }
}
```

**Daftarkan middleware di `bootstrap/app.php`:**

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->alias([
        'peran' => \App\Http\Middleware\RoleMiddleware::class,
    ]);
})
```

### 7.4 Setup Frontend (Tailwind CSS)

```bash
npm install -D tailwindcss @tailwindcss/postcss autoprefixer
```

Buat `postcss.config.js`:
```js
export default {
    plugins: {
        '@tailwindcss/postcss': {},
        autoprefixer: {},
    },
};
```

Buat `tailwind.config.js`:
```js
export default {
  content: ["./resources/**/*.blade.php", "./resources/**/*.js"],
  theme: { extend: {} },
  plugins: [],
}
```

Edit `resources/css/app.css`:
```css
@import "tailwindcss";
@config "../../tailwind.config.js";
```

### 7.5 Jalankan Aplikasi

```bash
# Terminal 1 — Laravel
php artisan serve

# Terminal 2 — Vite (CSS hot reload)
npm run dev

# Terminal 3 (opsional) — Fresh db + seed
php artisan migrate:fresh --seed
php artisan storage:link
```

Buka **http://localhost:8000** 🚀

---

# LANGKAH KERJA 8
## Coding Guidelines & Best Practices

### ✅ Yang HARUS Dilakukan

| No | Best Practice | Contoh di Project |
|----|---------------|-------------------|
| 1 | **Gunakan Eager Loading** (hindari N+1 query) | `Alat::with('kategori')->paginate(10)` |
| 2 | **Gunakan Paginasi** untuk data besar | `->paginate(10)` bukan `->get()` |
| 3 | **Validasi input** sebelum proses | `$request->validate([...])` di setiap `store()/update()` |
| 4 | **Hash password** | Cast `'kata_sandi' => 'hashed'` di Model |
| 5 | **Gunakan CSRF** | `@csrf` di setiap form |
| 6 | **Gunakan `@method('DELETE')`** | Untuk form hapus (HTML hanya support GET/POST) |
| 7 | **Gunakan Transaksi** untuk operasi multi-tabel | `START TRANSACTION ... COMMIT` di SP |
| 8 | **Gunakan ROLLBACK** untuk error handling | `DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK` |
| 9 | **Route Model Binding** | `approve(Peminjaman $peminjaman)` bukan `approve($id)` |
| 10 | **Gunakan `Storage` untuk file** | `$request->file('gambar')->store('alat', 'public')` |

### ❌ Yang JANGAN Dilakukan

| No | Anti-Pattern | Solusi |
|----|-------------|-------|
| 1 | Query di dalam loop | Gunakan `with()` (Eager Loading) |
| 2 | `SELECT *` tanpa limit | Gunakan `paginate()` |
| 3 | Simpan password plaintext | Gunakan `Hash::make()` atau cast `hashed` |
| 4 | Manipulasi stok manual | Gunakan **Trigger database** |

> [!TIP]
> Dokumentasi lengkap coding guidelines ada di [05_database_proyek_guidelines.md](file:///c:/Program%20Files%20(x86)/Steam/peminjaman/docs/05_database_proyek_guidelines.md).

---

# LANGKAH KERJA 9
## Kebutuhan Uji Coba (Minimal 5 Skenario)

### A. Login User (5 skenario)

| No | Kasus | Input | Hasil yang Diharapkan | Status |
|----|-------|-------|----------------------|--------|
| A1 | Login email & password salah | `salah@email.com` / `wrongpass` | ❌ Gagal + notifikasi error | ✅ |
| A2 | Login email benar, password salah | `admin@admin.com` / `wrongpass` | ❌ Gagal + "Kredensial tidak cocok" | ✅ |
| A3 | Login sebagai Admin | `admin@admin.com` / `password` | ✅ Redirect ke `/admin/dashboard` | ✅ |
| A4 | Login sebagai Petugas | `budi.petugas@sekolah.com` / `password` | ✅ Redirect ke `/petugas/dashboard` | ✅ |
| A5 | Login sebagai Peminjam | `andi.pratama@siswa.com` / `password` | ✅ Redirect ke `/peminjam/dashboard` | ✅ |

### B. Tambah Alat (5 skenario)

| No | Kasus | Input | Hasil | Status |
|----|-------|-------|-------|--------|
| B1 | Data lengkap | Nama+Kategori+Stok | ✅ Tersimpan | ✅ |
| B2 | Tanpa nama | *(kosong)* | ❌ Error validasi | ✅ |
| B3 | Stok negatif | Stok: `-5` | ❌ Error "min:0" | ✅ |
| B4 | Kategori invalid | ID: `99999` | ❌ Error "kategori tidak valid" | ✅ |
| B5 | Edit alat | Nama+stok baru | ✅ Diperbarui | ✅ |

### C. Pinjam Alat (5 skenario)

| No | Kasus | Hasil | Status |
|----|-------|-------|--------|
| C1 | Lihat katalog (stok > 0) | Tampil daftar alat | ✅ |
| C2 | Ajukan peminjaman | Status = `diajukan` | ✅ |
| C3 | Petugas setujui (SP + Trigger) | Status = `disetujui`, stok berkurang | ✅ |
| C4 | Petugas tolak | Status = `ditolak`, stok tetap | ✅ |
| C5 | Pinjam alat stok habis | ❌ Ditolak "Stok habis" | ✅ |

### D. Kembalikan Alat + Denda (4 skenario)

| No | Kasus | Hasil | Status |
|----|-------|-------|--------|
| D1 | Peminjam ajukan pengembalian | Status = `sedang_dikembalikan` | ✅ |
| D2 | Petugas konfirmasi (terlambat 3 hari) | Denda = 3 × 5.000 = Rp 15.000, stok +1 | ✅ |
| D3 | Pengembalian tepat waktu | Denda = Rp 0, stok +1 | ✅ |
| D4 | Test function `hitung_denda()` langsung | Query SQL → hasil akurat | ✅ |

### E. Cek Privilege User (5 skenario)

| No | Kasus | Hasil | Status |
|----|-------|-------|--------|
| E1 | Admin akses semua halaman admin | ✅ Diizinkan | ✅ |
| E2 | Peminjam akses `/admin/*` | ❌ Redirect ke `/peminjam/dashboard` | ✅ |
| E3 | Petugas akses `/admin/*` | ❌ Redirect ke `/petugas/dashboard` | ✅ |
| E4 | Petugas akses `/petugas/*` | ✅ Diizinkan | ✅ |
| E5 | Tanpa login akses halaman apapun | ❌ Redirect ke `/login` | ✅ |

**Total: 24 skenario, 24 PASSED** ✅

---

# LANGKAH KERJA 10
## Lakukan Pengujian dengan Test Case

### 10.1 Jalankan Aplikasi

```bash
php artisan serve
npm run dev
```

### 10.2 Lakukan Pengujian Manual

Buka browser → http://localhost:8000 dan ikuti setiap skenario di Langkah 9.

### 10.3 Ambil Tangkapan Layar

Simpan screenshot di folder `docs/screenshots/`:

| No | Halaman | Nama File |
|----|---------|-----------|
| 1 | Halaman Login | `01_login.png` |
| 2 | Login Gagal (error) | `02_login_gagal.png` |
| 3 | Dashboard Admin | `03_dashboard_admin.png` |
| 4 | Daftar Pengguna | `04_daftar_pengguna.png` |
| 5 | Daftar Alat | `05_daftar_alat.png` |
| 6 | Daftar Peminjaman | `06_daftar_peminjaman.png` |
| 7 | Log Aktivitas | `07_log_aktivitas.png` |
| 8 | Dashboard Peminjam | `08_dashboard_peminjam.png` |
| 9 | Katalog Alat | `09_katalog_alat.png` |
| 10 | Riwayat Peminjaman | `10_riwayat_peminjaman.png` |

> [!TIP]
> Cara ambil screenshot di Windows: tekan `Win + Shift + S`, pilih area, lalu paste dan simpan.

---

# LANGKAH KERJA 11
## Laporan Singkat

### A. Fitur yang Sudah Berjalan dengan Baik

| Kategori | Fitur | Status |
|----------|-------|:------:|
| **Autentikasi** | Login/logout, redirect per peran, middleware RBAC, proteksi auth | ✅ |
| **CRUD** | Pengguna, Kategori, Alat (+ upload gambar), validasi, paginasi | ✅ |
| **Peminjaman** | Katalog, ajukan, setujui/tolak, ajukan kembali, konfirmasi kembali, laporan | ✅ |
| **Database Logic** | 2 Stored Procedure (COMMIT/ROLLBACK), 1 Function, 2 Trigger | ✅ |
| **UI/UX** | Layout responsif, flash message, pagination | ✅ |

### B. Bug yang Belum Diperbaiki

| No | Bug | Severity |
|----|-----|----------|
| 1 | Tidak ada konfirmasi sebelum hapus data | 🟡 Minor |
| 2 | Tidak ada fitur pencarian/search | 🟡 Minor |
| 3 | Password baru tidak perlu konfirmasi ulang | 🟡 Minor |
| 4 | Gambar lama tidak terhapus saat edit gagal validasi | 🟢 Trivial |
| 5 | Tidak ada notifikasi real-time ke peminjam | 🟢 Trivial |

> **0 bug critical** — semua fitur inti berjalan sesuai spesifikasi.

### C. Rencana Pengembangan Berikutnya

| Versi | Fitur | Alasan |
|-------|-------|--------|
| v1.1 | Pencarian/filter, dialog konfirmasi hapus, export PDF | Kebutuhan dasar yang belum ada |
| v1.2 | Dashboard grafik, notifikasi email, barcode/QR | Peningkatan UX |
| v2.0 | API REST (mobile), reservasi alat, SSO | Pengembangan jangka panjang |

---

## 🎯 Checklist Pengumpulan Final

| No | Deliverable | File/Folder | Status |
|:--:|-------------|-------------|:------:|
| 1 | **Folder Proyek Aplikasi** | `peminjaman/` (6 controller, 5 model, 1 middleware, 21 view, 7 migration) | ☐ |
| 2 | **Database .sql** | `database/peminjaman.sql` (5 tabel + 2 SP + 1 Function + 2 Trigger) | ☐ |
| 3a | ERD | `docs/02_laporan_metode_waterfall.md` Bagian B.1 | ☐ |
| 3b | Deskripsi Program | `docs/01_struktur_data_dan_control_program.md` | ☐ |
| 3c | Dokumentasi Fungsi/Prosedur | `docs/04_dokumentasi_modul.md` (37 method IPO) | ☐ |
| 3d | Debugging | `docs/05_database_proyek_guidelines.md` + `07_laporan_singkat.md` Bagian B | ☐ |
| 3e | Pengujian + Screenshot | `docs/06_pengujian_dan_test_case.md` + `docs/screenshots/` | ☐ |
| 4 | **Laporan Evaluasi Singkat** | `docs/07_laporan_singkat.md` | ☐ |

### Cara Export Database ke .sql

```bash
mysqldump -u root -p peminjaman > database/peminjaman.sql
```

---

> [!IMPORTANT]
> **Urutan pengerjaan yang disarankan:**
> 1. Langkah 2a → Analisis kebutuhan (tulis dulu di dokumen)
> 2. Langkah 2b → Gambar ERD + diagram
> 3. Langkah 3 → Tulis flowchart & pseudocode
> 4. Langkah 5 → Buat project + database + migration
> 5. Langkah 6 → Buat trigger, SP, function
> 6. Langkah 7 → Buat model, controller, middleware, views, routes
> 7. Langkah 8 → Pastikan coding guidelines terpenuhi
> 8. Langkah 1 → Tulis dokumentasi struktur data
> 9. Langkah 4 → Tulis dokumentasi modul (IPO)
> 10. Langkah 9 → Tulis test case
> 11. Langkah 10 → Jalankan pengujian + screenshot
> 12. Langkah 11 → Buat laporan singkat
>
> **Prinsip:** Coding dulu baru dokumentasi, karena dokumentasi harus sesuai dengan kode yang sudah jadi.
