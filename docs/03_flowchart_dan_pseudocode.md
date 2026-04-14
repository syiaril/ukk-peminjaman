# DESKRIPSI, FLOWCHART, DAN PSEUDOCODE
## Proses Utama Aplikasi Peminjaman Alat Sekolah

---

## DAFTAR ISI

- [A. Proses Login](#a-proses-login)
- [B. Proses Peminjaman Alat](#b-proses-peminjaman-alat)
- [C. Proses Pengembalian Alat dan Perhitungan Denda](#c-proses-pengembalian-alat-dan-perhitungan-denda)

---

# A. Proses Login

## A.1 Deskripsi

Proses login merupakan gerbang utama untuk mengakses sistem. Pengguna memasukkan **email** dan **kata sandi** melalui halaman login. Sistem memvalidasi format input terlebih dahulu — email harus valid dan password tidak boleh kosong. Jika format valid, sistem mencocokkan kredensial dengan data di tabel `pengguna`. Kata sandi dibandingkan menggunakan fungsi **bcrypt hash** sehingga password asli tidak pernah disimpan dalam database.

Setelah autentikasi berhasil, sistem membaca kolom `peran` dari data pengguna untuk menentukan **halaman dashboard tujuan**:
- **Admin** → `/admin/dashboard` (akses penuh ke semua modul)
- **Petugas** → `/petugas/dashboard` (manajemen peminjaman & laporan)
- **Peminjam** → `/peminjam/dashboard` (katalog & riwayat peminjaman)

Sesi pengguna di-regenerasi setelah login berhasil untuk mencegah **session fixation attack**. Jika autentikasi gagal, pengguna dikembalikan ke halaman login dengan pesan error.

## A.2 Flowchart Proses Login

```
                    ┌─────────────┐
                    │   MULAI     │
                    └──────┬──────┘
                           │
                           ▼
                ┌─────────────────────┐
                │  Tampilkan Halaman  │
                │  Form Login         │
                └──────────┬──────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │  Input:             │
                │  - Email            │
                │  - Password         │
                └──────────┬──────────┘
                           │
                           ▼
                  ┌────────────────┐
                 ╱  Apakah format   ╲        ┌──────────────────────┐
                ╱   input valid?     ╲──────→│  Tampilkan pesan     │
                ╲  (email & password ╱  TIDAK │  error validasi     │
                 ╲  terisi?)        ╱        └──────────┬───────────┘
                  └───────┬────────┘                    │
                     YA   │                             │
                          ▼                             │
               ┌──────────────────────┐                 │
               │ Cari pengguna di DB  │                 │
               │ berdasarkan email    │                 │
               └──────────┬───────────┘                 │
                          │                             │
                          ▼                             │
                  ┌────────────────┐                    │
                 ╱  Apakah email    ╲                   │
                ╱   ditemukan &      ╲       ┌──────────┴───────────┐
                ╲   password cocok   ╱──────→│ Tampilkan pesan      │
                 ╲  (bcrypt)?       ╱  TIDAK │ "Kredensial tidak    │
                  └───────┬────────┘         │  cocok"              │
                     YA   │                  └──────────┬───────────┘
                          │                             │
                          ▼                             │
               ┌──────────────────────┐                 │
               │ Regenerasi sesi      │                 │
               │ (session regenerate) │                 │
               └──────────┬───────────┘                 │
                          │                             │
                          ▼                             │
               ┌──────────────────────┐                 │
               │ Baca kolom `peran`   │                 │
               │ dari data pengguna   │                 │
               └──────────┬───────────┘                 │
                          │                             │
              ┌───────────┼───────────┐                 │
              ▼           ▼           ▼                 │
        ┌──────────┐ ┌──────────┐ ┌──────────┐         │
        │peran =   │ │peran =   │ │peran =   │         │
        │'admin'   │ │'petugas' │ │'peminjam'│         │
        └────┬─────┘ └────┬─────┘ └────┬─────┘         │
             │            │            │                │
             ▼            ▼            ▼                │
      ┌───────────┐ ┌───────────┐ ┌───────────┐        │
      │ Redirect  │ │ Redirect  │ │ Redirect  │        │
      │ /admin/   │ │ /petugas/ │ │ /peminjam/│        │
      │ dashboard │ │ dashboard │ │ dashboard │        │
      └─────┬─────┘ └─────┬─────┘ └─────┬─────┘        │
            │              │             │              │
            └──────────────┴─────────────┘              │
                           │                            │
                           ▼                            │
                    ┌─────────────┐                     │
                    │   SELESAI   │←────────────────────┘
                    └─────────────┘       (kembali ke form login)
```

## A.3 Pseudocode Proses Login

```
PROGRAM Proses_Login

MULAI
    TAMPILKAN halaman_form_login

    MASUKAN email, password

    // ── Validasi Format Input ──
    JIKA email KOSONG ATAU bukan_format_email MAKA
        TAMPILKAN pesan_error "Email wajib diisi dan harus valid"
        KEMBALI ke halaman_form_login
    AKHIR JIKA

    JIKA password KOSONG MAKA
        TAMPILKAN pesan_error "Kata sandi wajib diisi"
        KEMBALI ke halaman_form_login
    AKHIR JIKA

    // ── Autentikasi ──
    SET pengguna ← CARI di tabel 'pengguna' DIMANA email = email_input

    JIKA pengguna TIDAK DITEMUKAN ATAU bcrypt_verify(password, pengguna.kata_sandi) = FALSE MAKA
        TAMPILKAN pesan_error "Kredensial yang diberikan tidak cocok"
        KEMBALI ke halaman_form_login
    AKHIR JIKA

    // ── Buat Sesi ──
    REGENERASI sesi                   // Mencegah session fixation
    SIMPAN pengguna ke sesi_aktif

    // ── Redirect Berdasarkan Peran ──
    JIKA pengguna.peran = "admin" MAKA
        REDIRECT ke "/admin/dashboard"
    LAIN JIKA pengguna.peran = "petugas" MAKA
        REDIRECT ke "/petugas/dashboard"
    LAIN JIKA pengguna.peran = "peminjam" MAKA
        REDIRECT ke "/peminjam/dashboard"
    AKHIR JIKA

SELESAI
```

---

# B. Proses Peminjaman Alat

## B.1 Deskripsi

Proses peminjaman alat melibatkan **dua aktor utama**: Peminjam dan Petugas/Admin. Alur dimulai dari peminjam yang menjelajahi katalog alat, kemudian mengajukan peminjaman, dan diakhiri oleh petugas/admin yang menyetujui atau menolak pengajuan.

### Tahap 1: Peminjam Melihat Katalog
Peminjam membuka halaman katalog yang menampilkan semua alat dengan **stok > 0**. Peminjam dapat memfilter alat berdasarkan kategori. Setiap alat menampilkan nama, deskripsi, gambar, stok tersedia, dan tombol pinjam.

### Tahap 2: Peminjam Mengajukan Peminjaman
Peminjam mengisi form peminjaman dengan memilih **tanggal pinjam** (minimal hari ini) dan **durasi** (1–14 hari). Sistem menghitung `tanggal_wajib_kembali` secara otomatis. Data peminjaman disimpan dengan status awal `diajukan`.

### Tahap 3: Petugas/Admin Memproses Pengajuan
Petugas melihat semua pengajuan dan memilih untuk **menyetujui** atau **menolak**. Jika disetujui, sistem memanggil **Stored Procedure** `proses_persetujuan_peminjaman()` yang menjalankan transaksi: mengubah status dan mencatat log. Setelah status berubah, **Trigger** `kurangi_stok_setelah_disetujui` otomatis mengurangi stok alat sebanyak 1.

## B.2 Flowchart Proses Peminjaman Alat

```
                    ┌─────────────┐
                    │   MULAI     │
                    └──────┬──────┘
                           │
           ════════════════╪═══════════════════
           ║   PEMINJAM    ║
           ════════════════╪═══════════════════
                           │
                           ▼
                ┌─────────────────────┐
                │  Buka Halaman       │
                │  Katalog Alat       │
                └──────────┬──────────┘
                           │
                           ▼
               ┌───────────────────────┐
               │ Query: SELECT alat    │
               │ WHERE stok > 0       │
               │ (filter by kategori?) │
               └───────────┬───────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │ Tampilkan daftar    │
                │ alat yang tersedia  │
                └──────────┬──────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │ Pilih alat yang     │
                │ ingin dipinjam      │
                └──────────┬──────────┘
                           │
                           ▼
                ┌─────────────────────┐
                │  Input:             │
                │  - Tanggal pinjam   │
                │  - Durasi (1-14 hr) │
                └──────────┬──────────┘
                           │
                           ▼
                  ┌────────────────┐
                 ╱  Validasi:       ╲       ┌────────────────────┐
                ╱  - alat_id valid?  ╲─────→│ Tampilkan pesan    │
                ╲  - tanggal ≥ hari  ╱ TIDAK│ error validasi     │
                 ╲   ini?           ╱       └────────┬───────────┘
                  ╲ - durasi 1-14? ╱                 │
                   └──────┬───────┘                  │
                     YA   │                          │
                          ▼                          │
                  ┌────────────────┐                 │
                 ╱  Apakah stok    ╲                 │
                ╱   alat ≥ 1?      ╲────────────────→│
                 ╲                 ╱  TIDAK           │
                  └───────┬───────┘  (stok habis)    │
                     YA   │                          │
                          ▼                          │
               ┌──────────────────────┐              │
               │ Hitung:              │              │
               │ tgl_wajib_kembali =  │              │
               │ tgl_pinjam + durasi  │              │
               └──────────┬───────────┘              │
                          │                          │
                          ▼                          │
               ┌──────────────────────┐              │
               │ INSERT ke tabel      │              │
               │ 'peminjaman':        │              │
               │ - pengguna_id        │              │
               │ - alat_id            │              │
               │ - tanggal_pinjam     │              │
               │ - tgl_wajib_kembali  │              │
               │ - status = 'diajukan'│              │
               └──────────┬───────────┘              │
                          │                          │
                          ▼                          │
               ┌──────────────────────┐              │
               │ Tampilkan pesan      │              │
               │ "Peminjaman berhasil │              │
               │  diajukan"           │              │
               └──────────┬───────────┘              │
                          │                          │
           ════════════════╪═══════════════════       │
           ║ PETUGAS/ADMIN ║                         │
           ════════════════╪═══════════════════       │
                          │                          │
                          ▼                          │
               ┌──────────────────────┐              │
               │ Buka halaman daftar  │              │
               │ peminjaman           │              │
               └──────────┬───────────┘              │
                          │                          │
                          ▼                          │
               ┌──────────────────────┐              │
               │ Pilih peminjaman     │              │
               │ berstatus 'diajukan' │              │
               └──────────┬───────────┘              │
                          │                          │
                  ┌───────┴────────┐                 │
                  ▼                ▼                  │
           ┌────────────┐  ┌────────────┐            │
           │  SETUJUI   │  │   TOLAK    │            │
           └─────┬──────┘  └─────┬──────┘            │
                 │               │                   │
                 ▼               ▼                   │
        ┌─────────────────┐  ┌──────────────┐        │
        │CALL proses_     │  │UPDATE status │        │
        │persetujuan_     │  │= 'ditolak'  │        │
        │peminjaman(      │  └──────┬───────┘        │
        │ id, petugas_id) │         │                │
        └────────┬────────┘         │                │
                 │                  │                 │
      ┌──────────┴──────────┐       │                │
      │ Di dalam Procedure: │       │                │
      │ ┌─ START TRANSACTION│       │                │
      │ ├─ UPDATE status    │       │                │
      │ │  = 'disetujui'    │       │                │
      │ ├─ INSERT log_      │       │                │
      │ │  aktivitas        │       │                │
      │ └─ COMMIT           │       │                │
      └──────────┬──────────┘       │                │
                 │                  │                │
                 ▼                  │                │
      ┌──────────────────────┐      │                │
      │ TRIGGER otomatis:    │      │                │
      │ kurangi_stok_setelah │      │                │
      │ _disetujui           │      │                │
      │ → stok = stok - 1   │      │                │
      └──────────┬───────────┘      │                │
                 │                  │                │
                 └────────┬─────────┘                │
                          ▼                          │
               ┌──────────────────────┐              │
               │ Tampilkan pesan      │              │
               │ sukses/ditolak       │              │
               └──────────┬───────────┘              │
                          │                          │
                          ▼                          │
                    ┌─────────────┐                  │
                    │   SELESAI   │←─────────────────┘
                    └─────────────┘
```

## B.3 Pseudocode Proses Peminjaman Alat

```
PROGRAM Proses_Peminjaman_Alat

// ══════════════════════════════════════
// BAGIAN 1: PEMINJAM MENGAJUKAN PEMINJAMAN
// ══════════════════════════════════════

MULAI
    // ── Tampilkan Katalog ──
    SET daftar_alat ← QUERY "SELECT * FROM alat WHERE stok > 0"

    JIKA ada_filter_kategori MAKA
        SET daftar_alat ← FILTER daftar_alat DIMANA kategori_id = kategori_terpilih
    AKHIR JIKA

    TAMPILKAN daftar_alat dengan paginasi (12 per halaman)

    // ── Peminjam Memilih Alat ──
    MASUKAN alat_id, tanggal_pinjam, durasi

    // ── Validasi Input ──
    JIKA alat_id TIDAK ADA di tabel 'alat' MAKA
        TAMPILKAN error "Alat tidak valid"
        KEMBALI
    AKHIR JIKA

    JIKA tanggal_pinjam < HARI_INI MAKA
        TAMPILKAN error "Tanggal pinjam tidak boleh kurang dari hari ini"
        KEMBALI
    AKHIR JIKA

    JIKA durasi < 1 ATAU durasi > 14 MAKA
        TAMPILKAN error "Durasi harus antara 1-14 hari"
        KEMBALI
    AKHIR JIKA

    // ── Cek Ketersediaan Stok ──
    SET alat ← CARI di tabel 'alat' DIMANA id = alat_id

    JIKA alat.stok < 1 MAKA
        TAMPILKAN error "Stok alat habis"
        KEMBALI
    AKHIR JIKA

    // ── Hitung Tanggal Wajib Kembali ──
    SET tanggal_wajib_kembali ← tanggal_pinjam + durasi HARI

    // ── Simpan ke Database ──
    INSERT INTO peminjaman (
        pengguna_id     ← ID_pengguna_login,
        alat_id         ← alat_id,
        tanggal_pinjam  ← tanggal_pinjam,
        tanggal_wajib_kembali ← tanggal_wajib_kembali,
        status          ← "diajukan"
    )

    TAMPILKAN pesan "Permintaan peminjaman berhasil diajukan"
    REDIRECT ke halaman_riwayat_peminjaman_saya


// ══════════════════════════════════════
// BAGIAN 2: PETUGAS/ADMIN MEMPROSES PENGAJUAN
// ══════════════════════════════════════

    // ── Tampilkan Daftar Pengajuan ──
    SET daftar_peminjaman ← QUERY "SELECT * FROM peminjaman ORDER BY created_at DESC"
    TAMPILKAN daftar_peminjaman

    // ── Petugas Memilih Aksi ──
    MASUKAN aksi (SETUJUI atau TOLAK), id_peminjaman

    SET peminjaman ← CARI di tabel 'peminjaman' DIMANA id = id_peminjaman

    // ── Validasi Status ──
    JIKA peminjaman.status ≠ "diajukan" MAKA
        TAMPILKAN error "Peminjaman tidak dalam status diajukan"
        KEMBALI
    AKHIR JIKA

    // ── Proses Setujui ──
    JIKA aksi = "SETUJUI" MAKA

        // Cek stok
        JIKA peminjaman.alat.stok < 1 MAKA
            TAMPILKAN error "Stok alat tidak mencukupi"
            KEMBALI
        AKHIR JIKA

        // Panggil Stored Procedure
        CALL proses_persetujuan_peminjaman(id_peminjaman, id_petugas)
        //  └── Di dalam procedure:
        //      ├── START TRANSACTION
        //      ├── UPDATE peminjaman SET status = "disetujui"
        //      ├── INSERT INTO log_aktivitas (aksi = "Setujui Peminjaman")
        //      └── COMMIT
        //  └── Trigger otomatis: stok = stok - 1

        TAMPILKAN pesan "Peminjaman disetujui. Stok diperbarui oleh sistem."

    // ── Proses Tolak ──
    LAIN JIKA aksi = "TOLAK" MAKA
        UPDATE peminjaman SET status = "ditolak"
        TAMPILKAN pesan "Peminjaman ditolak"
    AKHIR JIKA

SELESAI
```

---

# C. Proses Pengembalian Alat dan Perhitungan Denda

## C.1 Deskripsi

Proses pengembalian alat terdiri dari **dua tahap** yang melibatkan dua aktor:

### Tahap 1: Peminjam Mengajukan Pengembalian
Peminjam membuka halaman "Peminjaman Saya" dan memilih peminjaman yang berstatus `disetujui` untuk diajukan pengembaliannya. Sistem memverifikasi bahwa peminjaman benar milik pengguna yang login dan statusnya `disetujui`. Jika valid, status diubah menjadi `sedang_dikembalikan` — menandakan alat sedang dalam proses serah terima ke petugas.

### Tahap 2: Petugas/Admin Mengkonfirmasi Pengembalian
Petugas menerima alat secara fisik dan mengkonfirmasi di sistem. Sistem memanggil **Stored Procedure** `proses_pengembalian()`, yang menjalankan langkah-langkah dalam satu transaksi:

1. **Ambil** `tanggal_wajib_kembali` dari data peminjaman
2. **Panggil Function** `hitung_denda(tanggal_wajib, CURDATE())`:
   - Jika `tanggal_kembali > tanggal_wajib_kembali`: **denda** = selisih hari × Rp 5.000
   - Jika tepat waktu atau lebih awal: **denda** = Rp 0
3. **Update** peminjaman: status → `dikembalikan`, `tanggal_kembali` → hari ini, `denda` → hasil perhitungan
4. **Catat** log aktivitas pengembalian
5. **Trigger** `tambah_stok_setelah_dikembalikan` otomatis menambah stok alat +1

Seluruh proses bersifat **transaksional** — jika terjadi error di salah satu langkah, semua perubahan di-rollback untuk menjaga konsistensi data.

## C.2 Flowchart Proses Pengembalian Alat dan Perhitungan Denda

```
                    ┌─────────────┐
                    │   MULAI     │
                    └──────┬──────┘
                           │
           ════════════════╪═══════════════════
           ║   PEMINJAM    ║
           ════════════════╪═══════════════════
                           │
                           ▼
               ┌──────────────────────┐
               │ Buka halaman         │
               │ "Peminjaman Saya"    │
               └──────────┬───────────┘
                          │
                          ▼
               ┌──────────────────────┐
               │ Pilih peminjaman     │
               │ status = 'disetujui' │
               │ Klik "Ajukan         │
               │ Pengembalian"        │
               └──────────┬───────────┘
                          │
                          ▼
                  ┌────────────────┐
                 ╱  Apakah         ╲        ┌─────────────────┐
                ╱   pengguna_id =   ╲──────→│ TOLAK (403)     │
                ╲   user_login?     ╱ TIDAK │ Akses ditolak   │
                 ╲                 ╱        └─────────────────┘
                  └───────┬───────┘
                     YA   │
                          ▼
                  ┌────────────────┐
                 ╱  Apakah status   ╲       ┌─────────────────────┐
                ╱   = 'disetujui'?  ╲─────→│ Tampilkan error     │
                 ╲                  ╱ TIDAK │ "Hanya yang disetujui│
                  └───────┬────────┘        │  bisa dikembalikan" │
                     YA   │                 └─────────────────────┘
                          ▼
               ┌──────────────────────┐
               │ UPDATE peminjaman    │
               │ SET status =         │
               │ 'sedang_dikembalikan'│
               └──────────┬───────────┘
                          │
                          ▼
               ┌──────────────────────┐
               │ Tampilkan pesan:     │
               │ "Silakan serahkan    │
               │  alat ke petugas"    │
               └──────────┬───────────┘
                          │
           ════════════════╪═══════════════════
           ║ PETUGAS/ADMIN ║
           ════════════════╪═══════════════════
                          │
                          ▼
               ┌──────────────────────┐
               │ Petugas menerima alat│
               │ secara fisik         │
               └──────────┬───────────┘
                          │
                          ▼
               ┌──────────────────────┐
               │ Klik tombol          │
               │ "Kembalikan" pada    │
               │ peminjaman           │
               └──────────┬───────────┘
                          │
                          ▼
                  ┌────────────────┐
                 ╱ status =         ╲       ┌─────────────────────┐
                ╱ 'disetujui' ATAU   ╲─────→│ Tampilkan error     │
                ╲ 'sedang_           ╱ TIDAK │ "Status tidak bisa  │
                 ╲dikembalikan'?    ╱        │  dikembalikan"      │
                  └───────┬────────┘         └─────────────────────┘
                     YA   │
                          ▼
      ┌═══════════════════════════════════════════════════┐
      ║ CALL proses_pengembalian(id_peminjaman, id_petugas)║
      ║                                                    ║
      ║  ┌─ START TRANSACTION                              ║
      ║  │                                                 ║
      ║  ├─ LANGKAH 1: Ambil tanggal_wajib_kembali        ║
      ║  │  SELECT tanggal_wajib_kembali                   ║
      ║  │  FROM peminjaman WHERE id = id_peminjaman       ║
      ║  │                                                 ║
      ║  ├─ LANGKAH 2: Hitung Denda                        ║
      ║  │  ┌─────────────────────────────────────────┐    ║
      ║  │  │ FUNCTION hitung_denda(tgl_wajib, hari_ini)│  ║
      ║  │  │                                          │   ║
      ║  │  │  JIKA hari_ini > tgl_wajib MAKA         │   ║
      ║  │  │    hari_terlambat = hari_ini - tgl_wajib │   ║
      ║  │  │    denda = hari_terlambat × 5000         │   ║
      ║  │  │  LAIN                                    │   ║
      ║  │  │    denda = 0                             │   ║
      ║  │  │  RETURN denda                            │   ║
      ║  │  └─────────────────────────────────────────┘    ║
      ║  │                                                 ║
      ║  ├─ LANGKAH 3: Update peminjaman                   ║
      ║  │  UPDATE peminjaman SET                           ║
      ║  │    status = 'dikembalikan'                       ║
      ║  │    tanggal_kembali = HARI_INI                   ║
      ║  │    denda = nominal_denda                         ║
      ║  │                                                 ║
      ║  ├─ LANGKAH 4: Catat log aktivitas                 ║
      ║  │  INSERT INTO log_aktivitas                       ║
      ║  │  (aksi = "Konfirmasi Pengembalian")              ║
      ║  │                                                 ║
      ║  └─ COMMIT                                         ║
      └═══════════════════════════════════════╤════════════┘
                          │                   │
                          │                   ▼
                          │     ┌──────────────────────┐
                          │     │ TRIGGER otomatis:     │
                          │     │ tambah_stok_setelah_  │
                          │     │ dikembalikan           │
                          │     │ → stok = stok + 1     │
                          │     └──────────┬───────────┘
                          │                │
                          ▼                │
               ┌──────────────────────┐    │
               │ Refresh data         │←───┘
               │ peminjaman           │
               └──────────┬───────────┘
                          │
                          ▼
               ┌──────────────────────┐
               │ Tampilkan pesan:     │
               │ "Alat dikembalikan.  │
               │  Denda: Rp XXX"     │
               └──────────┬───────────┘
                          │
                          ▼
                    ┌─────────────┐
                    │   SELESAI   │
                    └─────────────┘
```

## C.3 Pseudocode Proses Pengembalian Alat dan Perhitungan Denda

```
PROGRAM Proses_Pengembalian_dan_Perhitungan_Denda

// ══════════════════════════════════════
// BAGIAN 1: PEMINJAM MENGAJUKAN PENGEMBALIAN
// ══════════════════════════════════════

MULAI
    // ── Tampilkan Riwayat Peminjaman ──
    SET daftar_peminjaman ← QUERY "SELECT * FROM peminjaman
                                    WHERE pengguna_id = ID_USER_LOGIN
                                    ORDER BY created_at DESC"
    TAMPILKAN daftar_peminjaman

    // ── Peminjam Memilih Peminjaman ──
    MASUKAN id_peminjaman

    SET peminjaman ← CARI di tabel 'peminjaman' DIMANA id = id_peminjaman

    // ── Validasi Kepemilikan ──
    JIKA peminjaman.pengguna_id ≠ ID_USER_LOGIN MAKA
        TOLAK AKSES (403 Forbidden)
        KEMBALI
    AKHIR JIKA

    // ── Validasi Status ──
    JIKA peminjaman.status ≠ "disetujui" MAKA
        TAMPILKAN error "Hanya peminjaman yang disetujui dapat dikembalikan"
        KEMBALI
    AKHIR JIKA

    // ── Ubah Status ──
    UPDATE peminjaman SET status = "sedang_dikembalikan"

    TAMPILKAN pesan "Permintaan pengembalian diajukan. Serahkan alat ke petugas."


// ══════════════════════════════════════
// BAGIAN 2: PETUGAS KONFIRMASI PENGEMBALIAN
// ══════════════════════════════════════

    // ── Petugas Menerima Alat Fisik ──
    MASUKAN id_peminjaman (dari daftar peminjaman)

    SET peminjaman ← CARI di tabel 'peminjaman' DIMANA id = id_peminjaman

    // ── Validasi Status ──
    JIKA peminjaman.status ≠ "disetujui" DAN peminjaman.status ≠ "sedang_dikembalikan" MAKA
        TAMPILKAN error "Peminjaman tidak dalam status dapat dikembalikan"
        KEMBALI
    AKHIR JIKA

    // ── Panggil Stored Procedure ──
    CALL proses_pengembalian(id_peminjaman, id_petugas)
    //
    // ┌═════════════════════════════════════════════════════════════┐
    // ║ STORED PROCEDURE: proses_pengembalian                       ║
    // ║                                                             ║
    // ║   START TRANSACTION                                        ║
    // ║                                                             ║
    // ║   // Langkah 1: Ambil tanggal batas                        ║
    // ║   SET tanggal_wajib ← SELECT tanggal_wajib_kembali         ║
    // ║                       FROM peminjaman                       ║
    // ║                       WHERE id = id_peminjaman              ║
    // ║                                                             ║
    // ║   // Langkah 2: Hitung denda via FUNCTION                   ║
    // ║   SET nominal_denda ← hitung_denda(tanggal_wajib, HARI_INI)║
    // ║   ┌─────────────────────────────────────────────────────┐   ║
    // ║   │ FUNCTION hitung_denda(tanggal_wajib, tanggal_kembali)│  ║
    // ║   │                                                      │  ║
    // ║   │   SET denda ← 0                                      │  ║
    // ║   │   JIKA tanggal_kembali > tanggal_wajib MAKA          │  ║
    // ║   │       SET hari_terlambat ← tanggal_kembali            │  ║
    // ║   │                           - tanggal_wajib (dalam hari)│  ║
    // ║   │       SET denda ← hari_terlambat × 5000               │  ║
    // ║   │   AKHIR JIKA                                          │  ║
    // ║   │   RETURN denda                                        │  ║
    // ║   └─────────────────────────────────────────────────────┘   ║
    // ║                                                             ║
    // ║   // Langkah 3: Update data peminjaman                      ║
    // ║   UPDATE peminjaman SET                                     ║
    // ║       status          = "dikembalikan"                      ║
    // ║       tanggal_kembali = HARI_INI                            ║
    // ║       denda           = nominal_denda                       ║
    // ║   WHERE id = id_peminjaman                                  ║
    // ║                                                             ║
    // ║   // Langkah 4: Catat log aktivitas                         ║
    // ║   JIKA id_petugas TIDAK NULL MAKA                           ║
    // ║       INSERT INTO log_aktivitas (                            ║
    // ║           pengguna_id = id_petugas,                          ║
    // ║           aksi        = "Konfirmasi Pengembalian",           ║
    // ║           deskripsi   = "Peminjaman [id] dikembalikan.       ║
    // ║                         Denda: [nominal_denda]"              ║
    // ║       )                                                      ║
    // ║   AKHIR JIKA                                                 ║
    // ║                                                             ║
    // ║   COMMIT                                                    ║
    // ║                                                             ║
    // ║   // TRIGGER OTOMATIS (setelah status = 'dikembalikan'):    ║
    // ║   // → tambah_stok_setelah_dikembalikan                     ║
    // ║   // → UPDATE alat SET stok = stok + 1 WHERE id = alat_id  ║
    // ║                                                             ║
    // └═════════════════════════════════════════════════════════════┘

    // ── Refresh dan Tampilkan Hasil ──
    SET peminjaman ← REFRESH data peminjaman dari database

    TAMPILKAN pesan "Alat dikembalikan. Denda: Rp " + FORMAT_ANGKA(peminjaman.denda)

SELESAI
```

## C.4 Contoh Perhitungan Denda

| No | Tanggal Wajib Kembali | Tanggal Dikembalikan | Hari Terlambat | Denda (Rp 5.000/hari) |
|----|------------------------|----------------------|----------------|------------------------|
| 1  | 2026-02-20             | 2026-02-18           | 0 (lebih awal) | Rp 0                   |
| 2  | 2026-02-20             | 2026-02-20           | 0 (tepat waktu)| Rp 0                   |
| 3  | 2026-02-20             | 2026-02-21           | 1 hari         | Rp 5.000               |
| 4  | 2026-02-20             | 2026-02-25           | 5 hari         | Rp 25.000              |
| 5  | 2026-02-20             | 2026-03-02           | 10 hari        | Rp 50.000              |

**Rumus:**
```
JIKA tanggal_kembali > tanggal_wajib_kembali MAKA
    denda = (tanggal_kembali - tanggal_wajib_kembali) × Rp 5.000
LAIN
    denda = Rp 0
```

---

*Dokumen ini merupakan bagian dari Laporan Proyek UKK — Aplikasi Peminjaman Alat Sekolah*
*Tanggal: 24 Februari 2026*
