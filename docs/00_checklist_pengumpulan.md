# CHECKLIST PENGUMPULAN UKK
## Aplikasi Peminjaman Alat Sekolah

**Nama:** Muhamad Syiaril Islami
**Tanggal:** 24 Februari 2026

---

## 1. ✅ Folder Proyek Aplikasi (Kode Program Lengkap)

**Lokasi:** `d:\MUHAMAD SYIARIL ISLAMI 2026\UKK\peminjaman\`

| Komponen          | Jumlah | Lokasi                              |
|-------------------|--------|--------------------------------------|
| Controller        | 6      | `app/Http/Controllers/`              |
| Model             | 5      | `app/Models/`                        |
| Middleware         | 1      | `app/Http/Middleware/`              |
| Migrasi Database  | 7      | `database/migrations/`               |
| Seeder            | 1      | `database/seeders/`                  |
| Blade Views       | 21     | `resources/views/`                   |
| Routes            | 1      | `routes/web.php`                     |
| CSS               | 1      | `resources/css/app.css`              |
| Config Auth       | 1      | `config/auth.php`                    |

---

## 2. ✅ Database dengan Ekstensi .sql

**File:** `database/peminjaman.sql` (41.7 KB)

| Isi File                  | Status |
|---------------------------|--------|
| Struktur 5 tabel utama    | ✅     |
| Data dummy (24 user, 40 alat, 51+ peminjaman) | ✅ |
| 2 Stored Procedure        | ✅     |
| 1 Function                | ✅     |
| 2 Trigger                 | ✅     |
| Foreign Key & Constraint  | ✅     |

**Cara import:**
```sql
mysql -u root -p peminjaman < database/peminjaman.sql
```

---

## 3. ✅ Dokumentasi

### a. ERD (Entity Relationship Diagram)

**File:** `docs/02_laporan_metode_waterfall.md` → Bagian B.1 (Desain ERD)

| Tabel          | Relasi                           |
|----------------|----------------------------------|
| `pengguna`     | 1 → N `peminjaman`, 1 → N `log_aktivitas` |
| `kategori`     | 1 → N `alat`                    |
| `alat`         | 1 → N `peminjaman`              |
| `peminjaman`   | N → 1 `pengguna`, N → 1 `alat`  |
| `log_aktivitas`| N → 1 `pengguna`                |

---

### b. Deskripsi Program

**File:** `docs/01_struktur_data_dan_control_program.md`

Berisi:
- Struktur data (variabel, tipe data, relasi antar tabel)
- Control program (alur navigasi, middleware, routing)
- Deskripsi setiap komponen MVC

**File tambahan:** `docs/05_database_proyek_guidelines.md` → Poin 7 (Folder Proyek)

---

### c. Dokumentasi Fungsi / Prosedur

**File:** `docs/04_dokumentasi_modul.md`

| Modul              | Method | Highlights                      |
|--------------------|--------|---------------------------------|
| Autentikasi        | 3      | Login, logout, getAuthPassword  |
| Pengguna           | 6      | CRUD + validasi email unik      |
| Kategori           | 6      | CRUD lengkap                    |
| Alat               | 6      | CRUD + upload gambar            |
| Peminjaman         | 9      | Katalog, ajukan, approve, return|
| Log Aktivitas      | 1      | Read-only log viewer            |
| Middleware          | 1      | RBAC per peran                  |
| Database Logic     | 5      | 2 SP, 1 Function, 2 Trigger    |
| **Total**          | **37** |                                 |

Setiap method didokumentasikan dengan format: **Input → Proses → Output**

**File tambahan:** `docs/03_flowchart_dan_pseudocode.md`
- Flowchart ASCII diagram untuk 3 proses utama
- Pseudocode lengkap

---

### d. Debugging

**File:** `docs/05_database_proyek_guidelines.md` → Poin 8 (Coding Guidelines)

Berisi:
- Penanganan error pada Stored Procedure (ROLLBACK)
- Validasi input untuk mencegah error
- Contoh kode buruk vs baik (N+1 query, loop tidak perlu)
- 15 best practices yang diterapkan

**File tambahan:** `docs/07_laporan_singkat.md` → Bagian B (Bug)
- 5 bug minor yang ditemukan dan didokumentasikan
- 0 bug critical

---

### e. Pengujian dan Tangkapan Layar Hasil Uji

**File:** `docs/06_pengujian_dan_test_case.md`

| Kategori              | Skenario | Passed | Status     |
|-----------------------|----------|--------|------------|
| A. Login User         | 5        | 5      | ✅ PASSED  |
| B. Tambah Alat        | 5        | 5      | ✅ PASSED  |
| C. Pinjam Alat        | 5        | 5      | ✅ PASSED  |
| D. Kembalikan & Denda | 4        | 4      | ✅ PASSED  |
| E. Cek Privilege      | 5        | 5      | ✅ PASSED  |
| **TOTAL**             | **24**   | **24** | **✅ ALL** |

**Script test:** `test_scenarios.php` (bisa dijalankan ulang: `php test_scenarios.php`)

#### Tangkapan Layar yang Perlu Diambil:

> **PETUNJUK:** Buka aplikasi di browser (http://localhost:8000) dan ambil screenshot untuk setiap halaman berikut. Simpan di folder `docs/screenshots/`.

| No | Halaman                       | Cara Mengakses                           | Nama File            |
|----|-------------------------------|------------------------------------------|----------------------|
| 1  | Halaman Login                 | Buka http://localhost:8000               | `01_login.png`       |
| 2  | Login Gagal (error)           | Login dengan email/password salah         | `02_login_gagal.png` |
| 3  | Dashboard Admin               | Login admin@admin.com / password          | `03_dashboard_admin.png` |
| 4  | Daftar Pengguna               | Menu → Pengguna                           | `04_daftar_pengguna.png` |
| 5  | Daftar Alat                   | Menu → Alat                               | `05_daftar_alat.png` |
| 6  | Daftar Peminjaman             | Menu → Peminjaman                         | `06_daftar_peminjaman.png` |
| 7  | Log Aktivitas                 | Menu → Log Aktivitas                      | `07_log_aktivitas.png` |
| 8  | Dashboard Peminjam            | Login andi.pratama@siswa.com / password   | `08_dashboard_peminjam.png` |
| 9  | Katalog Alat                  | Menu → Katalog Alat                       | `09_katalog_alat.png` |
| 10 | Riwayat Peminjaman            | Menu → Riwayat Peminjaman                 | `10_riwayat_peminjaman.png` |

---

## 4. ✅ Laporan Evaluasi Singkat

**File:** `docs/07_laporan_singkat.md`

| Bagian                          | Isi                              |
|---------------------------------|----------------------------------|
| A. Fitur berjalan baik          | 22+ fitur di 5 kategori         |
| B. Bug belum diperbaiki         | 5 bug minor, 0 critical         |
| C. Rencana pengembangan         | 12 fitur di 3 fase (v1.1-v2.0)  |

---

## Daftar Lengkap File Dokumentasi

| No | File                                        | Poin UKK        |
|----|---------------------------------------------|-----------------|
| 1  | `docs/01_struktur_data_dan_control_program.md` | 1, 2, 3b      |
| 2  | `docs/02_laporan_metode_waterfall.md`        | 2, 3a (ERD), 9 |
| 3  | `docs/03_flowchart_dan_pseudocode.md`        | 3               |
| 4  | `docs/04_dokumentasi_modul.md`               | 4, 3c           |
| 5  | `docs/05_database_proyek_guidelines.md`      | 5, 6, 7, 8, 3d |
| 6  | `docs/06_pengujian_dan_test_case.md`         | 9, 10, 3e       |
| 7  | `docs/07_laporan_singkat.md`                 | 11, 4           |
| 8  | `docs/00_checklist_pengumpulan.md`           | Master checklist|
| 9  | `database/peminjaman.sql`                    | 2 (database)    |
| 10 | `test_scenarios.php`                         | 10 (script uji) |

---

*Dokumen ini merupakan master checklist pengumpulan UKK*
*Tanggal: 24 Februari 2026*
