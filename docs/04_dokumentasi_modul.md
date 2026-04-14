# DOKUMENTASI MODUL
## Aplikasi Peminjaman Alat Sekolah

Dokumen ini menjelaskan setiap modul dalam aplikasi secara terpisah, mencakup **Input**, **Proses**, dan **Output** untuk setiap fungsi, prosedur, dan method.

---

## DAFTAR ISI

- [Modul 1: Autentikasi](#modul-1-autentikasi)
- [Modul 2: Pengelolaan Pengguna](#modul-2-pengelolaan-pengguna)
- [Modul 3: Pengelolaan Kategori](#modul-3-pengelolaan-kategori)
- [Modul 4: Pengelolaan Alat](#modul-4-pengelolaan-alat)
- [Modul 5: Pengelolaan Peminjaman](#modul-5-pengelolaan-peminjaman)
- [Modul 6: Log Aktivitas](#modul-6-log-aktivitas)
- [Modul 7: Middleware Hak Akses](#modul-7-middleware-hak-akses)
- [Modul 8: Database Logic (Trigger, Function, Stored Procedure)](#modul-8-database-logic)

---

# Modul 1: Autentikasi

**File:** `app/Http/Controllers/AuthController.php`
**Model:** `app/Models/Pengguna.php`
**View:** `resources/views/auth/login.blade.php`
**Hak Akses:** Guest (belum login)

## 1.1 Method `showLoginForm()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan halaman form login                    |
| **Input**   | Tidak ada (HTTP GET request)                      |
| **Proses**  | Mengembalikan view `auth.login`                   |
| **Output**  | Halaman HTML form login dengan field email & password |

**Kode:**
```php
public function showLoginForm()
{
    return view('auth.login');
}
```

## 1.2 Method `login(Request $request)`

| Aspek   | Detail                                                       |
|---------|--------------------------------------------------------------|
| **Fungsi**  | Memproses autentikasi pengguna                           |
| **Input**   | `email` (string, required, format email), `password` (string, required) |
| **Proses**  | 1. Validasi format input<br>2. Cek kredensial di database menggunakan `Auth::attempt()`<br>3. Regenerasi session<br>4. Baca kolom `peran` untuk menentukan redirect |
| **Output**  | **Berhasil:** Redirect ke dashboard sesuai peran<br>**Gagal:** Kembali ke form login dengan pesan error |

**Detail Input:**

| Parameter  | Tipe    | Validasi                | Keterangan              |
|------------|---------|-------------------------|-------------------------|
| `email`    | string  | required, email         | Email terdaftar          |
| `password` | string  | required                | Dicocokkan dengan bcrypt |

**Detail Output Redirect:**

| Peran     | Tujuan Redirect            |
|-----------|----------------------------|
| admin     | `/admin/dashboard`         |
| petugas   | `/petugas/dashboard`       |
| peminjam  | `/peminjam/dashboard`      |

**Kode:**
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

    return back()->withErrors(['email' => 'Kredensial yang diberikan tidak cocok.']);
}
```

## 1.3 Method `logout(Request $request)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Mengeluarkan pengguna dari sistem                 |
| **Input**   | Session pengguna aktif (HTTP POST request)        |
| **Proses**  | 1. `Auth::logout()` — hapus autentikasi<br>2. Invalidasi session<br>3. Regenerasi token CSRF |
| **Output**  | Redirect ke halaman login (`/`)                   |

**Kode:**
```php
public function logout(Request $request)
{
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
}
```

## 1.4 Method Model `getAuthPassword()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Override method autentikasi Laravel untuk kolom custom |
| **Input**   | Tidak ada (dipanggil internal oleh framework)     |
| **Proses**  | Mengembalikan nilai kolom `kata_sandi` (bukan default `password`) |
| **Output**  | String hash kata sandi pengguna                   |

---

# Modul 2: Pengelolaan Pengguna

**File:** `app/Http/Controllers/PenggunaController.php`
**Model:** `app/Models/Pengguna.php`
**View:** `resources/views/admin/pengguna/` (index, create, edit)
**Hak Akses:** Admin

## 2.1 Method `index()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan daftar semua pengguna                 |
| **Input**   | Tidak ada (HTTP GET request)                      |
| **Proses**  | Query `Pengguna::latest()->paginate(10)` — ambil semua pengguna, urut terbaru, 10 per halaman |
| **Output**  | Halaman daftar pengguna dengan paginasi (tabel: nama, email, peran, aksi) |

**Kode:**
```php
public function index()
{
    $pengguna = Pengguna::latest()->paginate(10);
    return view('admin.pengguna.index', compact('pengguna'));
}
```

## 2.2 Method `create()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form tambah pengguna baru             |
| **Input**   | Tidak ada (HTTP GET request)                      |
| **Proses**  | Mengembalikan view `admin.pengguna.create`        |
| **Output**  | Halaman form: nama, email, password, peran        |

## 2.3 Method `store(Request $request)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menyimpan data pengguna baru ke database          |
| **Input**   | Data form pengguna                                |
| **Proses**  | 1. Validasi input<br>2. Hash password<br>3. INSERT ke tabel `pengguna` |
| **Output**  | **Berhasil:** Redirect ke daftar pengguna + flash message sukses<br>**Gagal:** Kembali ke form + pesan error validasi |

**Detail Input:**

| Parameter     | Tipe    | Validasi                              | Keterangan                  |
|---------------|---------|---------------------------------------|-----------------------------|
| `nama`        | string  | required, max:255                     | Nama lengkap pengguna        |
| `email`       | string  | required, email, unique:pengguna      | Email unik, tidak boleh duplikat |
| `kata_sandi`  | string  | required, min:6                       | Minimal 6 karakter, auto-hash |
| `peran`       | string  | required, in:admin,petugas,peminjam   | Peran pengguna               |

**Kode:**
```php
public function store(Request $request)
{
    $request->validate([
        'nama' => 'required|max:255',
        'email' => 'required|email|unique:pengguna',
        'kata_sandi' => 'required|min:6',
        'peran' => 'required|in:admin,petugas,peminjam',
    ]);

    Pengguna::create([
        'nama' => $request->nama,
        'email' => $request->email,
        'kata_sandi' => $request->kata_sandi,  // auto-hash via Eloquent cast
        'peran' => $request->peran,
    ]);

    return redirect()->route('admin.pengguna.index')
                     ->with('success', 'Pengguna berhasil ditambahkan!');
}
```

## 2.4 Method `edit(Pengguna $pengguna)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form edit data pengguna               |
| **Input**   | `$pengguna` — objek Pengguna (via Route Model Binding) |
| **Proses**  | Mengembalikan view `admin.pengguna.edit` dengan data pengguna |
| **Output**  | Halaman form edit terisi data pengguna saat ini    |

## 2.5 Method `update(Request $request, Pengguna $pengguna)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Memperbarui data pengguna yang ada                |
| **Input**   | Data form yang diubah + objek Pengguna            |
| **Proses**  | 1. Validasi input (email unique kecuali milik sendiri)<br>2. Jika password diisi, hash dan update<br>3. UPDATE tabel `pengguna` |
| **Output**  | Redirect ke daftar pengguna + flash message sukses |

**Detail Input:**

| Parameter     | Tipe    | Validasi                                    | Keterangan                      |
|---------------|---------|---------------------------------------------|---------------------------------|
| `nama`        | string  | required, max:255                           | Nama baru                        |
| `email`       | string  | required, email, unique:pengguna,id         | Unik kecuali record sendiri     |
| `kata_sandi`  | string  | nullable, min:6                             | Kosong = password tidak diubah  |
| `peran`       | string  | required, in:admin,petugas,peminjam         | Peran baru                       |

**Kode:**
```php
public function update(Request $request, Pengguna $pengguna)
{
    $request->validate([
        'nama' => 'required|max:255',
        'email' => 'required|email|unique:pengguna,email,' . $pengguna->id,
        'kata_sandi' => 'nullable|min:6',
        'peran' => 'required|in:admin,petugas,peminjam',
    ]);

    $data = [
        'nama' => $request->nama,
        'email' => $request->email,
        'peran' => $request->peran,
    ];

    if ($request->filled('kata_sandi')) {
        $data['kata_sandi'] = $request->kata_sandi;
    }

    $pengguna->update($data);
    return redirect()->route('admin.pengguna.index')
                     ->with('success', 'Pengguna berhasil diperbarui!');
}
```

## 2.6 Method `destroy(Pengguna $pengguna)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menghapus data pengguna dari database             |
| **Input**   | `$pengguna` — objek Pengguna (via Route Model Binding) |
| **Proses**  | DELETE dari tabel `pengguna` (log_aktivitas CASCADE) |
| **Output**  | Redirect ke daftar pengguna + flash message sukses |

**Kode:**
```php
public function destroy(Pengguna $pengguna)
{
    $pengguna->delete();
    return redirect()->route('admin.pengguna.index')
                     ->with('success', 'Pengguna berhasil dihapus!');
}
```

---

# Modul 3: Pengelolaan Kategori

**File:** `app/Http/Controllers/KategoriController.php`
**Model:** `app/Models/Kategori.php`
**View:** `resources/views/admin/kategori/` (index, create, edit)
**Hak Akses:** Admin

## 3.1 Method `index()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan daftar semua kategori alat            |
| **Input**   | Tidak ada (HTTP GET request)                      |
| **Proses**  | Query `Kategori::latest()->paginate(10)`          |
| **Output**  | Halaman daftar kategori dengan paginasi (tabel: nama kategori, aksi) |

## 3.2 Method `create()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form tambah kategori baru             |
| **Input**   | Tidak ada                                         |
| **Proses**  | Mengembalikan view `admin.kategori.create`        |
| **Output**  | Halaman form: nama kategori                       |

## 3.3 Method `store(Request $request)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menyimpan kategori baru ke database               |
| **Input**   | `nama_kategori` (string, required, max:255)       |
| **Proses**  | 1. Validasi input<br>2. INSERT ke tabel `kategori` |
| **Output**  | **Berhasil:** Redirect ke daftar + flash sukses<br>**Gagal:** Kembali + pesan error |

**Detail Input:**

| Parameter       | Tipe    | Validasi        | Keterangan          |
|-----------------|---------|-----------------|----------------------|
| `nama_kategori` | string  | required, max:255 | Nama kategori alat |

**Kode:**
```php
public function store(Request $request)
{
    $request->validate([
        'nama_kategori' => 'required|max:255',
    ]);

    Kategori::create($request->only('nama_kategori'));
    return redirect()->route('admin.kategori.index')
                     ->with('success', 'Kategori berhasil ditambahkan!');
}
```

## 3.4 Method `edit(Kategori $kategori)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form edit kategori                    |
| **Input**   | `$kategori` — objek Kategori (Route Model Binding) |
| **Proses**  | Mengembalikan view `admin.kategori.edit` dengan data |
| **Output**  | Halaman form edit terisi data kategori saat ini    |

## 3.5 Method `update(Request $request, Kategori $kategori)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Memperbarui nama kategori                         |
| **Input**   | `nama_kategori` (string, required, max:255)       |
| **Proses**  | 1. Validasi input<br>2. UPDATE tabel `kategori`   |
| **Output**  | Redirect ke daftar kategori + flash message sukses |

## 3.6 Method `destroy(Kategori $kategori)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menghapus kategori dari database                  |
| **Input**   | `$kategori` — objek Kategori                      |
| **Proses**  | DELETE dari tabel `kategori`                      |
| **Output**  | Redirect ke daftar + flash message sukses          |

---

# Modul 4: Pengelolaan Alat

**File:** `app/Http/Controllers/AlatController.php`
**Model:** `app/Models/Alat.php`
**View:** `resources/views/admin/alat/` (index, create, edit)
**Hak Akses:** Admin

## 4.1 Method `index()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan daftar semua alat beserta kategorinya |
| **Input**   | Tidak ada                                         |
| **Proses**  | Query `Alat::with('kategori')->latest()->paginate(10)` — eager load relasi kategori |
| **Output**  | Halaman daftar alat (tabel: gambar, nama, kategori, stok, aksi) |

## 4.2 Method `create()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form tambah alat baru                 |
| **Input**   | Tidak ada                                         |
| **Proses**  | Ambil semua kategori untuk dropdown, kembalikan view |
| **Output**  | Halaman form: nama alat, kategori (dropdown), deskripsi, stok, gambar |

**Kode:**
```php
public function create()
{
    $kategori = Kategori::all();
    return view('admin.alat.create', compact('kategori'));
}
```

## 4.3 Method `store(Request $request)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menyimpan data alat baru ke database              |
| **Input**   | Data form alat termasuk file gambar               |
| **Proses**  | 1. Validasi input<br>2. Upload gambar ke `storage/app/public/alat`<br>3. INSERT ke tabel `alat` |
| **Output**  | **Berhasil:** Redirect ke daftar + flash sukses<br>**Gagal:** Kembali + error validasi |

**Detail Input:**

| Parameter     | Tipe     | Validasi                                   | Keterangan                    |
|---------------|----------|--------------------------------------------|-------------------------------|
| `kategori_id` | integer  | required, exists:kategori,id               | FK ke tabel kategori           |
| `nama_alat`   | string   | required, max:255                          | Nama alat                      |
| `deskripsi`   | text     | nullable                                   | Deskripsi alat (opsional)      |
| `stok`        | integer  | required, integer, min:0                   | Jumlah stok awal               |
| `gambar`      | file     | nullable, image, mimes:jpg,png,jpeg, max:2048 | File gambar maks 2 MB      |

**Kode:**
```php
public function store(Request $request)
{
    $request->validate([
        'kategori_id' => 'required|exists:kategori,id',
        'nama_alat' => 'required|max:255',
        'deskripsi' => 'nullable',
        'stok' => 'required|integer|min:0',
        'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $data = $request->only(['kategori_id', 'nama_alat', 'deskripsi', 'stok']);

    if ($request->hasFile('gambar')) {
        $data['gambar'] = $request->file('gambar')->store('alat', 'public');
    }

    Alat::create($data);
    return redirect()->route('admin.alat.index')
                     ->with('success', 'Alat berhasil ditambahkan!');
}
```

## 4.4 Method `edit(Alat $alat)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan form edit data alat                   |
| **Input**   | `$alat` — objek Alat (Route Model Binding)        |
| **Proses**  | Ambil semua kategori + data alat, kembalikan view |
| **Output**  | Halaman form edit terisi data alat saat ini + dropdown kategori |

## 4.5 Method `update(Request $request, Alat $alat)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Memperbarui data alat yang ada                    |
| **Input**   | Data form yang diubah + objek Alat                |
| **Proses**  | 1. Validasi input<br>2. Jika ada gambar baru: hapus gambar lama, upload gambar baru<br>3. UPDATE tabel `alat` |
| **Output**  | Redirect ke daftar alat + flash message sukses     |

**Detail Input:**

| Parameter     | Tipe     | Validasi                                   | Keterangan                          |
|---------------|----------|--------------------------------------------|--------------------------------------|
| `kategori_id` | integer  | required, exists:kategori,id               | FK ke tabel kategori                  |
| `nama_alat`   | string   | required, max:255                          | Nama alat baru                        |
| `deskripsi`   | text     | nullable                                   | Deskripsi baru                        |
| `stok`        | integer  | required, integer, min:0                   | Stok baru                             |
| `gambar`      | file     | nullable, image, mimes:jpg,png,jpeg, max:2048 | Gambar baru (kosong = tidak diubah) |

**Kode:**
```php
public function update(Request $request, Alat $alat)
{
    $request->validate([
        'kategori_id' => 'required|exists:kategori,id',
        'nama_alat' => 'required|max:255',
        'deskripsi' => 'nullable',
        'stok' => 'required|integer|min:0',
        'gambar' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
    ]);

    $data = $request->only(['kategori_id', 'nama_alat', 'deskripsi', 'stok']);

    if ($request->hasFile('gambar')) {
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }
        $data['gambar'] = $request->file('gambar')->store('alat', 'public');
    }

    $alat->update($data);
    return redirect()->route('admin.alat.index')
                     ->with('success', 'Alat berhasil diperbarui!');
}
```

## 4.6 Method `destroy(Alat $alat)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menghapus data alat dari database                 |
| **Input**   | `$alat` — objek Alat                              |
| **Proses**  | 1. Hapus file gambar dari storage (jika ada)<br>2. DELETE dari tabel `alat` |
| **Output**  | Redirect ke daftar alat + flash message sukses     |

**Kode:**
```php
public function destroy(Alat $alat)
{
    if ($alat->gambar) {
        Storage::disk('public')->delete($alat->gambar);
    }
    $alat->delete();
    return redirect()->route('admin.alat.index')
                     ->with('success', 'Alat berhasil dihapus!');
}
```

---

# Modul 5: Pengelolaan Peminjaman

**File:** `app/Http/Controllers/PeminjamanController.php`
**Model:** `app/Models/Peminjaman.php`
**View:** `resources/views/admin/peminjaman/`, `resources/views/petugas/peminjaman/`, `resources/views/peminjam/`
**Hak Akses:** Admin, Petugas, Peminjam (berbeda per method)

## 5.1 Method `katalog(Request $request)` — Peminjam

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan katalog alat yang tersedia untuk dipinjam |
| **Input**   | `kategori_id` (opsional, query parameter untuk filter) |
| **Proses**  | 1. Query alat dengan `stok > 0`<br>2. Jika `kategori_id` ada, filter berdasarkan kategori<br>3. Eager load relasi kategori<br>4. Paginasi 12 item per halaman |
| **Output**  | Halaman katalog alat (card: gambar, nama, kategori, stok, tombol pinjam) |

**Detail Input:**

| Parameter     | Tipe     | Validasi  | Keterangan                       |
|---------------|----------|-----------|----------------------------------|
| `kategori_id` | integer  | opsional  | Filter alat berdasarkan kategori |

**Kode:**
```php
public function katalog(Request $request)
{
    $query = Alat::where('stok', '>', 0)->with('kategori');

    if ($request->filled('kategori_id')) {
        $query->where('kategori_id', $request->kategori_id);
    }

    $alat = $query->paginate(12);
    $kategori = Kategori::all();
    return view('peminjam.alat.index', compact('alat', 'kategori'));
}
```

## 5.2 Method `store(Request $request)` — Peminjam

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Mengajukan permintaan peminjaman alat baru        |
| **Input**   | Data form peminjaman                              |
| **Proses**  | 1. Validasi input<br>2. Cek stok alat ≥ 1<br>3. Hitung `tanggal_wajib_kembali`<br>4. INSERT ke tabel `peminjaman` status `diajukan` |
| **Output**  | **Berhasil:** Redirect ke riwayat + flash sukses<br>**Gagal:** Kembali + error |

**Detail Input:**

| Parameter        | Tipe    | Validasi                                     | Keterangan                         |
|------------------|---------|----------------------------------------------|-------------------------------------|
| `alat_id`        | integer | required, exists:alat,id                     | ID alat yang dipinjam               |
| `tanggal_pinjam` | date    | required, date, after_or_equal:today         | Minimal hari ini                    |
| `durasi`         | integer | required, integer, min:1, max:14             | Lama peminjaman dalam hari          |

**Detail Output (Data yang disimpan):**

| Kolom                   | Nilai                                |
|-------------------------|--------------------------------------|
| `pengguna_id`           | ID pengguna yang login               |
| `alat_id`               | ID alat yang dipilih                 |
| `tanggal_pinjam`        | Tanggal pinjam dari form             |
| `tanggal_wajib_kembali` | `tanggal_pinjam + durasi` hari       |
| `status`                | `"diajukan"`                         |

**Kode:**
```php
public function store(Request $request)
{
    $request->validate([
        'alat_id' => 'required|exists:alat,id',
        'tanggal_pinjam' => 'required|date|after_or_equal:today',
        'durasi' => 'required|integer|min:1|max:14',
    ]);

    $alat = Alat::findOrFail($request->alat_id);
    if ($alat->stok < 1) {
        return back()->with('error', 'Stok alat habis.');
    }

    $tanggalPinjam = Carbon::parse($request->tanggal_pinjam);
    $tanggalWajibKembali = $tanggalPinjam->copy()->addDays($request->durasi);

    Peminjaman::create([
        'pengguna_id' => Auth::id(),
        'alat_id' => $request->alat_id,
        'tanggal_pinjam' => $tanggalPinjam,
        'tanggal_wajib_kembali' => $tanggalWajibKembali,
        'status' => 'diajukan',
    ]);

    return redirect()->route('peminjam.peminjaman.index')
                     ->with('success', 'Peminjaman berhasil diajukan!');
}
```

## 5.3 Method `peminjamanSaya()` — Peminjam

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan riwayat peminjaman milik pengguna yang login |
| **Input**   | ID pengguna yang login (dari session)             |
| **Proses**  | Query peminjaman WHERE `pengguna_id = Auth::id()`, eager load `alat`, urut terbaru, paginasi |
| **Output**  | Halaman riwayat: tabel peminjaman (alat, tanggal, status, denda, aksi) |

**Kode:**
```php
public function peminjamanSaya()
{
    $peminjaman = Peminjaman::where('pengguna_id', Auth::id())
                            ->with('alat')
                            ->latest()
                            ->paginate(10);
    return view('peminjam.peminjaman.index', compact('peminjaman'));
}
```

## 5.4 Method `ajukanPengembalian(Peminjaman $peminjaman)` — Peminjam

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Mengajukan pengembalian alat yang sedang dipinjam  |
| **Input**   | `$peminjaman` — objek Peminjaman (Route Model Binding) |
| **Proses**  | 1. Cek kepemilikan (`pengguna_id = Auth::id()`)<br>2. Cek status = `disetujui`<br>3. UPDATE status = `sedang_dikembalikan` |
| **Output**  | **Berhasil:** Redirect + flash sukses<br>**Gagal:** 403 Forbidden / flash error |

**Kode:**
```php
public function ajukanPengembalian(Peminjaman $peminjaman)
{
    if ($peminjaman->pengguna_id !== Auth::id()) {
        abort(403);
    }
    if ($peminjaman->status !== 'disetujui') {
        return back()->with('error', 'Hanya peminjaman yang disetujui.');
    }

    $peminjaman->update(['status' => 'sedang_dikembalikan']);
    return back()->with('success', 'Pengembalian diajukan. Serahkan alat ke petugas.');
}
```

## 5.5 Method `index()` — Admin / Petugas

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan seluruh data peminjaman               |
| **Input**   | Tidak ada                                         |
| **Proses**  | Query `Peminjaman::with(['pengguna', 'alat'])->latest()->paginate(10)` |
| **Output**  | Halaman daftar peminjaman (tabel: peminjam, alat, tanggal, status, denda, aksi) |

## 5.6 Method `approve(Peminjaman $peminjaman)` — Admin / Petugas

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menyetujui pengajuan peminjaman                   |
| **Input**   | `$peminjaman` — objek Peminjaman                  |
| **Proses**  | 1. Validasi status = `diajukan`<br>2. Validasi stok alat ≥ 1<br>3. `CALL proses_persetujuan_peminjaman(id, petugas_id)`<br>4. Trigger otomatis: `stok = stok - 1` |
| **Output**  | **Berhasil:** Redirect + flash "Peminjaman disetujui. Stok diperbarui."<br>**Gagal:** Redirect + flash error |

**Proses Internal (Stored Procedure):**

| Langkah | Operasi                                      |
|---------|----------------------------------------------|
| 1       | `START TRANSACTION`                          |
| 2       | `UPDATE peminjaman SET status = 'disetujui'` |
| 3       | `INSERT INTO log_aktivitas` (catat aksi)     |
| 4       | `COMMIT`                                     |
| 5       | Trigger: `UPDATE alat SET stok = stok - 1`   |

**Kode:**
```php
public function approve(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'diajukan') {
        return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
    }
    if ($peminjaman->alat->stok < 1) {
        return back()->with('error', 'Stok alat tidak mencukupi.');
    }

    DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [
        $peminjaman->id, Auth::id()
    ]);

    return back()->with('success', 'Peminjaman disetujui. Stok diperbarui oleh sistem.');
}
```

## 5.7 Method `reject(Peminjaman $peminjaman)` — Admin / Petugas

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menolak pengajuan peminjaman                      |
| **Input**   | `$peminjaman` — objek Peminjaman                  |
| **Proses**  | 1. Validasi status = `diajukan`<br>2. UPDATE status = `ditolak` |
| **Output**  | Redirect + flash "Peminjaman ditolak"              |

**Kode:**
```php
public function reject(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'diajukan') {
        return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
    }

    $peminjaman->update(['status' => 'ditolak']);
    return back()->with('success', 'Peminjaman ditolak.');
}
```

## 5.8 Method `returnTool(Peminjaman $peminjaman)` — Admin / Petugas

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Mengkonfirmasi pengembalian alat dan menghitung denda |
| **Input**   | `$peminjaman` — objek Peminjaman                  |
| **Proses**  | 1. Validasi status = `disetujui` atau `sedang_dikembalikan`<br>2. `CALL proses_pengembalian(id, petugas_id)`<br>3. Di dalam procedure: hitung denda, update status, catat log<br>4. Trigger otomatis: `stok = stok + 1`<br>5. Refresh data untuk ambil denda |
| **Output**  | Redirect + flash "Alat dikembalikan. Denda: Rp XXX" |

**Proses Internal (Stored Procedure + Function):**

| Langkah | Operasi                                                |
|---------|--------------------------------------------------------|
| 1       | `START TRANSACTION`                                   |
| 2       | Ambil `tanggal_wajib_kembali` dari peminjaman          |
| 3       | `hitung_denda(tanggal_wajib, CURDATE())` — return denda |
| 4       | `UPDATE peminjaman SET status='dikembalikan', tanggal_kembali=CURDATE(), denda=nominal` |
| 5       | `INSERT INTO log_aktivitas` (catat pengembalian)       |
| 6       | `COMMIT`                                               |
| 7       | Trigger: `UPDATE alat SET stok = stok + 1`             |

**Kode:**
```php
public function returnTool(Peminjaman $peminjaman)
{
    if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
        return back()->with('error', 'Peminjaman tidak dapat dikembalikan.');
    }

    DB::statement('CALL proses_pengembalian(?, ?)', [
        $peminjaman->id, Auth::id()
    ]);

    $peminjaman->refresh();
    return back()->with('success', 'Alat dikembalikan. Denda: Rp ' . number_format($peminjaman->denda));
}
```

## 5.9 Method `laporan()` — Petugas

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan laporan seluruh peminjaman             |
| **Input**   | Tidak ada                                         |
| **Proses**  | Query `Peminjaman::with(['pengguna', 'alat'])->latest()->paginate(20)` |
| **Output**  | Halaman laporan peminjaman (detail lengkap, bisa dicetak) |

**Kode:**
```php
public function laporan()
{
    $peminjaman = Peminjaman::with(['pengguna', 'alat'])->latest()->paginate(20);
    return view('petugas.laporan', compact('peminjaman'));
}
```

---

# Modul 6: Log Aktivitas

**File:** `app/Http/Controllers/LogAktivitasController.php`
**Model:** `app/Models/LogAktivitas.php`
**View:** `resources/views/admin/log_aktivitas/index.blade.php`
**Hak Akses:** Admin

## 6.1 Method `index()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menampilkan riwayat log aktivitas seluruh sistem  |
| **Input**   | Tidak ada (HTTP GET request)                      |
| **Proses**  | Query `LogAktivitas::with('pengguna')->latest()->paginate(20)` — eager load relasi pengguna |
| **Output**  | Halaman daftar log (tabel: waktu, pengguna, aksi, deskripsi) |

**Detail Output (kolom yang ditampilkan):**

| Kolom        | Sumber                | Keterangan                     |
|--------------|----------------------|--------------------------------|
| Waktu        | `created_at`         | Tanggal dan jam aktivitas       |
| Pengguna     | `pengguna.nama`      | Nama pengguna yang melakukan    |
| Aksi         | `aksi`               | Jenis aktivitas (Setujui, dll) |
| Deskripsi    | `deskripsi`          | Detail narasi aktivitas         |

**Kode:**
```php
class LogAktivitasController extends Controller
{
    public function index()
    {
        $logs = LogAktivitas::with('pengguna')
                            ->latest()
                            ->paginate(20);
        return view('admin.log_aktivitas.index', compact('logs'));
    }
}
```

---

# Modul 7: Middleware Hak Akses

**File:** `app/Http/Middleware/RoleMiddleware.php`
**Hak Akses:** Diterapkan pada semua route yang dilindungi

## 7.1 Method `handle(Request $request, Closure $next, ...$roles)`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Mengontrol akses pengguna berdasarkan peran       |
| **Input**   | `$request` (HTTP Request), `$next` (Closure), `...$roles` (daftar peran yang diizinkan) |
| **Proses**  | 1. Cek apakah pengguna sudah login (`Auth::check()`)<br>2. Cek apakah `peran` pengguna ada di `$roles`<br>3. Jika tidak berhak, redirect ke dashboard sesuai peran |
| **Output**  | **Berhak:** Lanjutkan request ke controller<br>**Tidak login:** Redirect ke `/login`<br>**Tidak berhak:** Redirect ke dashboard peran sendiri |

**Detail Logika:**

| Kondisi                         | Aksi                                   |
|---------------------------------|----------------------------------------|
| Belum login                     | Redirect ke `route('login')`           |
| Peran sesuai (`in_array`)       | `$next($request)` — lanjutkan         |
| Peran admin, akses bukan admin  | Redirect ke `route('admin.dashboard')` |
| Peran petugas, akses bukan petugas | Redirect ke `route('petugas.dashboard')` |
| Peran peminjam, akses lainnya   | Redirect ke `route('peminjam.dashboard')` |

**Registrasi di Route:**
```php
// Contoh penggunaan middleware di route
Route::middleware(['auth', 'peran:admin'])->group(function () { ... });
Route::middleware(['auth', 'peran:petugas'])->group(function () { ... });
Route::middleware(['auth', 'peran:peminjam'])->group(function () { ... });
```

**Kode:**
```php
class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        if (in_array($user->peran, $roles)) {
            return $next($request);
        }

        // Redirect ke dashboard sendiri jika tidak berhak
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

---

# Modul 8: Database Logic

**File:** `database/migrations/2026_01_18_214712_buat_trigger_dan_prosedur.php`

## 8.1 Trigger `kurangi_stok_setelah_disetujui`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Otomatis mengurangi stok alat saat peminjaman disetujui |
| **Input**   | Event: `AFTER UPDATE` pada tabel `peminjaman`     |
| **Proses**  | Cek apakah `NEW.status = 'disetujui'` DAN `OLD.status ≠ 'disetujui'`<br>Jika ya: `UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id` |
| **Output**  | Stok alat berkurang 1 unit secara otomatis         |

**Kode SQL:**
```sql
CREATE TRIGGER kurangi_stok_setelah_disetujui
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'disetujui' AND OLD.status != 'disetujui' THEN
        UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
    END IF;
END;
```

## 8.2 Trigger `tambah_stok_setelah_dikembalikan`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Otomatis menambah stok alat saat alat dikembalikan |
| **Input**   | Event: `AFTER UPDATE` pada tabel `peminjaman`     |
| **Proses**  | Cek apakah `NEW.status = 'dikembalikan'` DAN `OLD.status ≠ 'dikembalikan'`<br>Jika ya: `UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id` |
| **Output**  | Stok alat bertambah 1 unit secara otomatis          |

**Kode SQL:**
```sql
CREATE TRIGGER tambah_stok_setelah_dikembalikan
AFTER UPDATE ON peminjaman
FOR EACH ROW
BEGIN
    IF NEW.status = 'dikembalikan' AND OLD.status != 'dikembalikan' THEN
        UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
    END IF;
END;
```

## 8.3 Function `hitung_denda()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Menghitung denda keterlambatan pengembalian alat   |
| **Input**   | `tanggal_wajib` (DATE) — batas tanggal kembali<br>`tanggal_kembali` (DATE) — tanggal pengembalian aktual |
| **Proses**  | 1. Hitung selisih hari: `DATEDIFF(tanggal_kembali, tanggal_wajib)`<br>2. Jika positif (terlambat): `denda = hari_terlambat × 5000`<br>3. Jika nol/negatif (tepat/lebih awal): `denda = 0` |
| **Output**  | `DECIMAL(10,2)` — nominal denda dalam Rupiah       |

**Contoh Perhitungan:**

| tanggal_wajib | tanggal_kembali | Selisih Hari | Output Denda    |
|---------------|-----------------|--------------|-----------------|
| 2026-02-20    | 2026-02-18      | -2 (awal)    | Rp 0            |
| 2026-02-20    | 2026-02-20      | 0 (tepat)    | Rp 0            |
| 2026-02-20    | 2026-02-23      | 3 (lambat)   | Rp 15.000       |
| 2026-02-20    | 2026-03-02      | 10 (lambat)  | Rp 50.000       |

**Kode SQL:**
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

## 8.4 Stored Procedure `proses_persetujuan_peminjaman()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Memproses persetujuan peminjaman secara transaksional |
| **Input**   | `id_peminjaman` (INT) — ID peminjaman yang disetujui<br>`id_petugas` (INT) — ID petugas yang menyetujui |
| **Proses**  | Dalam satu transaksi:<br>1. UPDATE peminjaman SET status = `disetujui`<br>2. INSERT log_aktivitas (aksi = "Setujui Peminjaman")<br>3. COMMIT (Trigger `kurangi_stok` otomatis berjalan) |
| **Output**  | Status peminjaman berubah + log tercatat + stok berkurang |

**Error Handling:**
- `DECLARE EXIT HANDLER FOR SQLEXCEPTION` → `ROLLBACK` seluruh transaksi

**Kode SQL:**
```sql
CREATE PROCEDURE proses_persetujuan_peminjaman(
    IN id_peminjaman INT,
    IN id_petugas INT
)
BEGIN
    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

    START TRANSACTION;

    UPDATE peminjaman SET status = 'disetujui'
    WHERE id = id_peminjaman;

    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
    VALUES (
        id_petugas,
        'Setujui Peminjaman',
        CONCAT('Peminjaman ', id_peminjaman, ' disetujui'),
        NOW(), NOW()
    );

    COMMIT;
END;
```

## 8.5 Stored Procedure `proses_pengembalian()`

| Aspek   | Detail                                                |
|---------|-------------------------------------------------------|
| **Fungsi**  | Memproses pengembalian alat lengkap dengan denda   |
| **Input**   | `id_peminjaman` (INT) — ID peminjaman yang dikembalikan<br>`id_petugas` (INT) — ID petugas yang mengkonfirmasi |
| **Proses**  | Dalam satu transaksi:<br>1. SELECT `tanggal_wajib_kembali`<br>2. Panggil function `hitung_denda()`<br>3. UPDATE peminjaman: status=`dikembalikan`, tanggal_kembali=CURDATE(), denda=nominal<br>4. INSERT log_aktivitas<br>5. COMMIT (Trigger `tambah_stok` otomatis berjalan) |
| **Output**  | Status berubah + denda tercatat + log tercatat + stok bertambah |

**Error Handling:**
- `DECLARE EXIT HANDLER FOR SQLEXCEPTION` → `ROLLBACK` seluruh transaksi

**Kode SQL:**
```sql
CREATE PROCEDURE proses_pengembalian(
    IN id_peminjaman INT,
    IN id_petugas INT
)
BEGIN
    DECLARE tanggal_wajib DATE;
    DECLARE nominal_denda DECIMAL(10,2);

    DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;

    START TRANSACTION;

    -- Langkah 1: Ambil tanggal batas kembali
    SELECT tanggal_wajib_kembali INTO tanggal_wajib
    FROM peminjaman WHERE id = id_peminjaman;

    -- Langkah 2: Hitung denda
    SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());

    -- Langkah 3: Update peminjaman
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

    COMMIT;
END;
```

---

## Ringkasan Seluruh Modul

| No | Modul              | File Controller                    | Jumlah Method | Hak Akses   |
|----|--------------------|------------------------------------|---------------|-------------|
| 1  | Autentikasi        | `AuthController.php`               | 3             | Guest       |
| 2  | Pengguna           | `PenggunaController.php`           | 6             | Admin       |
| 3  | Kategori           | `KategoriController.php`           | 6             | Admin       |
| 4  | Alat               | `AlatController.php`               | 6             | Admin       |
| 5  | Peminjaman         | `PeminjamanController.php`         | 9             | Multi-peran |
| 6  | Log Aktivitas      | `LogAktivitasController.php`       | 1             | Admin       |
| 7  | Middleware         | `RoleMiddleware.php`               | 1             | Sistem      |
| 8  | Database Logic     | Migration (trigger, function, SP)  | 5             | Sistem      |
|    | **Total**          |                                    | **37**        |             |

| No | Komponen Database  | Nama                                    | Tipe              |
|----|--------------------|-----------------------------------------|--------------------|
| 1  | Trigger 1          | `kurangi_stok_setelah_disetujui`        | AFTER UPDATE       |
| 2  | Trigger 2          | `tambah_stok_setelah_dikembalikan`      | AFTER UPDATE       |
| 3  | Function           | `hitung_denda()`                        | DETERMINISTIC      |
| 4  | Stored Procedure 1 | `proses_persetujuan_peminjaman()`       | Transaksional      |
| 5  | Stored Procedure 2 | `proses_pengembalian()`                 | Transaksional      |

---

*Dokumen ini merupakan bagian dari Laporan Proyek UKK — Aplikasi Peminjaman Alat Sekolah*
*Tanggal: 24 Februari 2026*
