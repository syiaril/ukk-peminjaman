# KEBUTUHAN UJI COBA DAN HASIL PENGUJIAN
## Aplikasi Peminjaman Alat Sekolah

**Tanggal Pengujian:** 24 Februari 2026
**Metode Pengujian:** Black Box Testing (pengujian fungsional)
**Alat Uji:** PHP Script + Database MySQL langsung

---

## DAFTAR ISI

- [A. Login User (5 skenario)](#a-login-user)
- [B. Tambah Alat (5 skenario)](#b-tambah-alat)
- [C. Pinjam Alat (5 skenario)](#c-pinjam-alat)
- [D. Kembalikan Alat & Denda (4 skenario)](#d-kembalikan-alat--perhitungan-denda)
- [E. Cek Privilege User (5 skenario)](#e-cek-privilege-user)
- [Ringkasan Hasil Pengujian](#ringkasan-hasil-pengujian)

---

# A. Login User

## A1: Login dengan email dan password yang SALAH

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | User memasukkan email dan password yang tidak terdaftar   |
| **Input**    | Email: `salah@email.com` · Password: `wrongpass`         |
| **Proses**   | Sistem mencari email di database → **tidak ditemukan**    |
| **Hasil**    | ❌ **GAGAL LOGIN** — Muncul notifikasi: *"Kredensial yang diberikan tidak cocok"* |
| **Status**   | ✅ PASSED — Sistem menolak akses dengan benar             |

## A2: Login dengan email BENAR tapi password SALAH

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | User memasukkan email terdaftar tapi password salah       |
| **Input**    | Email: `admin@admin.com` · Password: `wrongpassword`     |
| **Proses**   | Email ditemukan (user: **Administrator**) → hash bcrypt **tidak cocok** |
| **Hasil**    | ❌ **GAGAL LOGIN** — Muncul notifikasi: *"Kredensial yang diberikan tidak cocok"* |
| **Status**   | ✅ PASSED — Password di-hash, tidak bisa ditebak          |

## A3: Login sebagai Admin (credentials benar)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin login dengan email dan password yang benar          |
| **Input**    | Email: `admin@admin.com` · Password: `password`          |
| **Proses**   | Email ditemukan → password cocok → peran = `admin` → redirect |
| **Hasil**    | ✅ **BERHASIL LOGIN** — Redirect ke **`/admin/dashboard`** |
| **Status**   | ✅ PASSED                                                  |

## A4: Login sebagai Petugas (credentials benar)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Petugas login dengan email dan password yang benar        |
| **Input**    | Email: `budi.petugas@sekolah.com` · Password: `password` |
| **Proses**   | Email ditemukan → password cocok → peran = `petugas` → redirect |
| **Hasil**    | ✅ **BERHASIL LOGIN** — Redirect ke **`/petugas/dashboard`** |
| **Status**   | ✅ PASSED                                                  |

## A5: Login sebagai Peminjam (credentials benar)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Peminjam login dengan email dan password yang benar       |
| **Input**    | Email: `andi.pratama@siswa.com` · Password: `password`    |
| **Proses**   | Email ditemukan → password cocok → peran = `peminjam` → redirect |
| **Hasil**    | ✅ **BERHASIL LOGIN** — Redirect ke **`/peminjam/dashboard`** |
| **Status**   | ✅ PASSED                                                  |

---

# B. Tambah Alat

## B1: Tambah alat dengan data LENGKAP

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin menambah alat baru dengan semua field terisi        |
| **Input**    | Nama: `Alat Test Pengujian` · Kategori: `Alat Laboratorium IPA` · Stok: `5` |
| **Proses**   | Validasi OK → INSERT ke tabel `alat` → jumlah alat bertambah (40 → 41) |
| **Hasil**    | ✅ **BERHASIL** — Alat tersimpan di database, ID baru: 41 |
| **Status**   | ✅ PASSED                                                  |

## B2: Tambah alat TANPA NAMA (validasi gagal)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin menambah alat tanpa mengisi nama                    |
| **Input**    | Nama: *(kosong)* · Kategori: `Alat Laboratorium IPA` · Stok: `3` |
| **Proses**   | Validasi rule `required` → **DITOLAK**                   |
| **Hasil**    | ❌ **GAGAL** — Muncul pesan error: *"Nama alat wajib diisi"* |
| **Status**   | ✅ PASSED — Validasi input berfungsi dengan benar          |

## B3: Tambah alat dengan STOK NEGATIF

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin memasukkan stok dengan nilai negatif                |
| **Input**    | Nama: `Alat Negatif` · Stok: `-5`                       |
| **Proses**   | Validasi rule `min:0` → **DITOLAK**                      |
| **Hasil**    | ❌ **GAGAL** — Muncul pesan error: *"Stok minimal 0"*     |
| **Status**   | ✅ PASSED — Validasi mencegah data tidak logis             |

## B4: Tambah alat dengan KATEGORI TIDAK VALID

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin memilih kategori yang tidak ada di database         |
| **Input**    | Nama: `Alat Invalid` · Kategori ID: `99999`              |
| **Proses**   | Validasi rule `exists:kategori,id` → **DITOLAK**         |
| **Hasil**    | ❌ **GAGAL** — Muncul pesan error: *"Kategori tidak valid"* |
| **Status**   | ✅ PASSED — Foreign key dan validasi berfungsi             |

## B5: Edit alat yang sudah ada

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Admin mengubah nama dan stok alat yang sudah ada          |
| **Input**    | Nama baru: `Alat Test Diperbarui` · Stok baru: `10`     |
| **Proses**   | Validasi OK → UPDATE tabel `alat`                         |
| **Hasil**    | ✅ **BERHASIL** — Nama = "Alat Test Diperbarui", Stok = 10 |
| **Status**   | ✅ PASSED                                                  |

---

# C. Pinjam Alat

## C1: Lihat katalog alat tersedia

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Peminjam melihat daftar alat yang bisa dipinjam           |
| **Input**    | Tidak ada (halaman katalog)                               |
| **Proses**   | Query: `alat WHERE stok > 0` dengan paginasi 12 per halaman |
| **Hasil**    | ✅ **BERHASIL** — Menampilkan 41 alat tersedia dari total 41 |
| **Status**   | ✅ PASSED                                                  |

## C2: Ajukan peminjaman alat

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Peminjam mengajukan peminjaman alat dari katalog          |
| **Input**    | Peminjam: `Andi Pratama` · Alat: `Mikroskop Binokuler` · Durasi: 5 hari |
| **Proses**   | Validasi stok > 0 → hitung tanggal wajib kembali → INSERT peminjaman |
| **Hasil**    | ✅ **BERHASIL** — Peminjaman tersimpan, status = `diajukan` |
| **Output**   | Tanggal pinjam: 24/02/2026 · Wajib kembali: 01/03/2026  |
| **Status**   | ✅ PASSED                                                  |

## C3: Petugas menyetujui peminjaman (Stored Procedure + Trigger)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Petugas menyetujui pengajuan peminjaman                   |
| **Input**    | Peminjaman ID: 52 · Petugas: `Budi Santoso`              |
| **Proses**   | `CALL proses_persetujuan_peminjaman(52, 2)` → trigger `kurangi_stok` |
| **Hasil**    | ✅ **BERHASIL** — 3 hal terjadi otomatis:                 |
| **Detail**   | 1. Status → `disetujui` ✅<br>2. Stok: 9 → **8** (trigger berjalan) ✅<br>3. Log: *"Peminjaman 52 disetujui"* tercatat ✅ |
| **Status**   | ✅ PASSED — Stored procedure dan trigger berfungsi         |

## C4: Petugas menolak peminjaman

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Petugas menolak pengajuan peminjaman                      |
| **Input**    | Peminjaman ID: 53                                         |
| **Proses**   | UPDATE status = `ditolak` (tanpa mengurangi stok)        |
| **Hasil**    | ✅ **BERHASIL** — Status berubah ke `ditolak`, stok tetap  |
| **Status**   | ✅ PASSED                                                  |

## C5: Pinjam alat dengan STOK HABIS

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Peminjam mencoba meminjam alat yang stoknya habis         |
| **Input**    | Alat dengan stok = 0                                      |
| **Proses**   | Validasi: `if ($alat->stok < 1)` → **DITOLAK**           |
| **Hasil**    | ❌ **GAGAL** — Muncul pesan: *"Stok alat habis"*          |
| **Status**   | ✅ PASSED — Sistem mencegah peminjaman alat habis          |

---

# D. Kembalikan Alat & Perhitungan Denda

## D1: Peminjam mengajukan pengembalian alat

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Peminjam mengajukan pengembalian alat yang sedang dipinjam |
| **Input**    | Peminjaman ID: 52 (status: `disetujui`)                   |
| **Proses**   | Cek kepemilikan → cek status → UPDATE status              |
| **Hasil**    | ✅ **BERHASIL** — Status berubah ke `sedang_dikembalikan`  |
| **Status**   | ✅ PASSED                                                  |

## D2: Petugas konfirmasi pengembalian TERLAMBAT (dengan denda)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Petugas mengkonfirmasi pengembalian alat yang **terlambat 3 hari** |
| **Input**    | Peminjaman ID: 52 · Tanggal wajib: 21/02/2026 · Tanggal kembali: 24/02/2026 |
| **Proses**   | `CALL proses_pengembalian(52, 2)` → `hitung_denda()` → trigger `tambah_stok` |
| **Hasil**    | ✅ **BERHASIL** — 4 hal terjadi otomatis:                 |
| **Detail**   | 1. Status → `dikembalikan` ✅<br>2. Denda: 3 hari × Rp 5.000 = **Rp 15.000** ✅<br>3. Tanggal kembali: 24/02/2026 tercatat ✅<br>4. Log: *"Peminjaman 52 dikembalikan. Denda: 15000.00"* ✅ |
| **Status**   | ✅ PASSED — SP, Function, dan Trigger semuanya berfungsi   |

## D3: Pengembalian TEPAT WAKTU (tanpa denda)

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Petugas mengkonfirmasi pengembalian alat yang **tepat waktu** |
| **Input**    | Tanggal wajib: 26/02/2026 · Tanggal kembali: 24/02/2026 (2 hari lebih awal) |
| **Proses**   | `hitung_denda('2026-02-26', '2026-02-24')` → 0           |
| **Hasil**    | ✅ **BERHASIL** — Denda = **Rp 0** (tidak ada denda)      |
| **Status**   | ✅ PASSED                                                  |

## D4: Test function `hitung_denda()` langsung di SQL

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Menguji function `hitung_denda()` dengan berbagai skenario |

| Skenario              | Input                                | Output      | Status |
|-----------------------|--------------------------------------|-------------|--------|
| Tepat waktu           | `hitung_denda('2026-02-20', '2026-02-20')` | `0.00`    | ✅     |
| Terlambat 5 hari      | `hitung_denda('2026-02-20', '2026-02-25')` | `25000.00`| ✅     |
| Lebih awal 2 hari     | `hitung_denda('2026-02-20', '2026-02-18')` | `0.00`    | ✅     |

| **Hasil**    | ✅ **BERHASIL** — Function menghitung Rp 5.000 per hari keterlambatan |
|--------------|-------------------------------------------------------------------|
| **Status**   | ✅ PASSED                                                          |

---

# E. Cek Privilege User

## E1: Admin memiliki akses KE SEMUA halaman admin

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Memverifikasi admin bisa mengakses semua fitur admin      |
| **User**     | **Administrator** (peran: `admin`)                        |

| Halaman              | Akses     | Middleware            |
|----------------------|-----------|-----------------------|
| `/admin/dashboard`   | ✅ DIIZINKAN | `peran:admin`       |
| `/admin/pengguna`    | ✅ DIIZINKAN | `peran:admin`       |
| `/admin/kategori`    | ✅ DIIZINKAN | `peran:admin`       |
| `/admin/alat`        | ✅ DIIZINKAN | `peran:admin`       |
| `/admin/peminjaman`  | ✅ DIIZINKAN | `peran:admin`       |
| `/admin/log-aktivitas` | ✅ DIIZINKAN | `peran:admin`    |

| **Hasil**    | ✅ **BERHASIL** — Admin memiliki akses penuh               |
|--------------|----------------------------------------------------------|
| **Status**   | ✅ PASSED                                                  |

## E2: Peminjam TIDAK bisa akses halaman admin

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Memverifikasi peminjam ditolak dari halaman admin          |
| **User**     | **Andi Pratama** (peran: `peminjam`)                      |

| Halaman              | Akses       | Aksi                             |
|----------------------|-------------|----------------------------------|
| `/admin/dashboard`   | ❌ DITOLAK  | Redirect ke `/peminjam/dashboard` |
| `/admin/pengguna`    | ❌ DITOLAK  | Redirect ke `/peminjam/dashboard` |

| **Hasil**    | ✅ **BERHASIL** — Peminjam di-redirect ke dashboard sendiri |
|--------------|----------------------------------------------------------|
| **Status**   | ✅ PASSED                                                  |

## E3: Petugas TIDAK bisa akses halaman admin

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Memverifikasi petugas ditolak dari halaman admin           |
| **User**     | **Budi Santoso** (peran: `petugas`)                       |

| Halaman              | Akses       | Aksi                             |
|----------------------|-------------|----------------------------------|
| `/admin/pengguna`    | ❌ DITOLAK  | Redirect ke `/petugas/dashboard`  |
| `/admin/alat`        | ❌ DITOLAK  | Redirect ke `/petugas/dashboard`  |

| **Hasil**    | ✅ **BERHASIL** — Petugas di-redirect ke dashboard sendiri  |
|--------------|----------------------------------------------------------|
| **Status**   | ✅ PASSED                                                  |

## E4: Petugas bisa akses halaman peminjaman

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Memverifikasi petugas bisa akses fitur peminjaman          |
| **User**     | **Budi Santoso** (peran: `petugas`)                       |

| Halaman              | Akses       | Middleware            |
|----------------------|-------------|------------------------|
| `/petugas/peminjaman`| ✅ DIIZINKAN | `peran:petugas`       |
| `/petugas/laporan`   | ✅ DIIZINKAN | `peran:petugas`       |

| **Hasil**    | ✅ **BERHASIL** — Petugas memiliki akses ke modul peminjaman |
|--------------|----------------------------------------------------------|
| **Status**   | ✅ PASSED                                                  |

## E5: User tanpa login TIDAK bisa akses halaman apapun

| Aspek        | Detail                                                   |
|--------------|----------------------------------------------------------|
| **Kasus**    | Memverifikasi semua halaman memerlukan login                |
| **Kondisi**  | Tidak ada session aktif (belum login)                      |

| Halaman              | Akses       | Aksi                  |
|----------------------|-------------|------------------------|
| `/admin/dashboard`   | ❌ DITOLAK  | Redirect ke `/login`   |
| `/petugas/dashboard` | ❌ DITOLAK  | Redirect ke `/login`   |
| `/peminjam/dashboard`| ❌ DITOLAK  | Redirect ke `/login`   |

| **Hasil**    | ✅ **BERHASIL** — Middleware `auth` mengharuskan login      |
|--------------|----------------------------------------------------------|
| **Status**   | ✅ PASSED                                                  |

---

# Ringkasan Hasil Pengujian

## Tabel Ringkasan

| No | Kategori                      | Jumlah Skenario | Passed | Failed | Status     |
|----|-------------------------------|-----------------|--------|--------|------------|
| A  | Login User                    | 5               | 5      | 0      | ✅ PASSED  |
| B  | Tambah Alat                   | 5               | 5      | 0      | ✅ PASSED  |
| C  | Pinjam Alat                   | 5               | 5      | 0      | ✅ PASSED  |
| D  | Kembalikan Alat & Denda       | 4               | 4      | 0      | ✅ PASSED  |
| E  | Cek Privilege User            | 5               | 5      | 0      | ✅ PASSED  |
|    | **TOTAL**                     | **24**          | **24** | **0**  | **✅ ALL PASSED** |

## Komponen Database yang Diuji

| No | Komponen             | Nama                                   | Hasil            |
|----|----------------------|----------------------------------------|------------------|
| 1  | Stored Procedure     | `proses_persetujuan_peminjaman()`      | ✅ Berfungsi     |
| 2  | Stored Procedure     | `proses_pengembalian()`                | ✅ Berfungsi     |
| 3  | Function             | `hitung_denda()`                       | ✅ Berfungsi     |
| 4  | Trigger              | `kurangi_stok_setelah_disetujui`       | ✅ Berfungsi     |
| 5  | Trigger              | `tambah_stok_setelah_dikembalikan`     | ✅ Berfungsi     |
| 6  | COMMIT               | Dalam kedua stored procedure           | ✅ Berfungsi     |
| 7  | ROLLBACK             | Handler error dalam stored procedure   | ✅ Terdefinisi   |

## Kesimpulan

Seluruh **24 skenario** uji coba telah dilaksanakan dan **semua BERHASIL (PASSED)**. Aplikasi berfungsi sesuai dengan spesifikasi kebutuhan, meliputi:

1. **Autentikasi** — Login/logout berfungsi dengan validasi password bcrypt dan redirect berbasis peran
2. **Manajemen Alat** — CRUD dengan validasi input (required, tipe data, foreign key)
3. **Peminjaman** — Alur lengkap dari pengajuan → persetujuan/penolakan → pengembalian
4. **Perhitungan Denda** — Function `hitung_denda()` menghitung Rp 5.000/hari dengan tepat
5. **Hak Akses** — Middleware RBAC memastikan setiap peran hanya bisa akses fitur yang sesuai

---

*Dokumen ini merupakan bagian dari Laporan Proyek UKK — Aplikasi Peminjaman Alat Sekolah*
*Tanggal Pengujian: 24 Februari 2026*
