<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use Illuminate\Http\Request;

/**
 * AlatController
 * 
 * Controller untuk mengelola data alat/peralatan sekolah.
 * Digunakan oleh Admin untuk operasi CRUD (Create, Read, Update, Delete) alat.
 */
class AlatController extends Controller
{
    /**
     * Menampilkan daftar semua alat.
     * 
     * Mengambil data alat beserta relasi kategori-nya,
     * kemudian menampilkannya dalam bentuk tabel dengan paginasi (10 data per halaman).
     */
    public function index()
    {
        // Mengambil semua alat beserta data kategorinya, dipaginasi 10 per halaman
        $alat = Alat::with('kategori')->paginate(10);
        return view('admin.alat.index', compact('alat'));
    }

    /**
     * Menampilkan form untuk membuat alat baru.
     * 
     * Mengambil semua data kategori untuk ditampilkan sebagai pilihan dropdown di form.
     */
    public function create()
    {
        // Ambil semua kategori untuk pilihan dropdown di form
        $kategori = Kategori::all();
        return view('admin.alat.create', compact('kategori'));
    }

    /**
     * Menyimpan data alat baru ke database.
     * 
     * Melakukan validasi input terlebih dahulu, termasuk:
     * - Nama alat wajib diisi (maks 255 karakter)
     * - Kategori wajib dipilih dan harus valid
     * - Stok wajib diisi, berupa angka, minimal 0
     * - Gambar opsional, harus berupa file gambar (JPEG/PNG/JPG/GIF, maks 2MB)
     * 
     * Jika ada file gambar yang diupload, file akan disimpan ke folder 'alat' 
     * di disk 'public' (storage/app/public/alat/).
     */
    public function store(Request $request)
    {
        // Validasi data input dari form
        $request->validate([
            'nama_alat' => 'required|string|max:255',          // Nama alat wajib diisi
            'kategori_id' => 'required|exists:kategori,id',    // Kategori harus valid (ada di tabel kategori)
            'stok' => 'required|integer|min:0',                // Stok wajib, angka, minimal 0
            'deskripsi' => 'nullable|string',                  // Deskripsi opsional
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional, maks 2MB
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'kategori_id.exists' => 'Kategori tidak valid.',
            'stok.required' => 'Stok wajib diisi.',
            'stok.integer' => 'Stok harus berupa angka.',
            'stok.min' => 'Stok tidak boleh kurang dari 0.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);
        
        // Proses upload gambar jika ada file yang dikirim
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            // Simpan gambar ke folder 'alat' di disk 'public' (storage/app/public/alat/)
            $gambarPath = $request->file('gambar')->store('alat', 'public');
        }

        // Buat record alat baru di database
        Alat::create([
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
            'gambar' => $gambarPath, // Path gambar atau null jika tidak ada
        ]);

        // Redirect ke halaman daftar alat dengan pesan sukses
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dibuat.');
    }

    /**
     * Menampilkan form edit untuk alat tertentu.
     * 
     * Menggunakan Route Model Binding - Laravel otomatis mencari alat berdasarkan ID.
     * Data kategori juga diambil untuk pilihan dropdown.
     */
    public function edit(Alat $alat)
    {
        // Ambil semua kategori untuk pilihan dropdown di form edit
        $kategori = Kategori::all();
        return view('admin.alat.edit', compact('alat', 'kategori'));
    }

    /**
     * Memperbarui data alat yang sudah ada di database.
     * 
     * Proses validasi sama seperti method store().
     * Jika ada gambar baru diupload, gambar lama akan dihapus terlebih dahulu
     * dari storage sebelum menyimpan gambar baru.
     */
    public function update(Request $request, Alat $alat)
    {
        // Validasi data input dari form edit
        $request->validate([
            'nama_alat' => 'required|string|max:255',          // Nama alat wajib diisi
            'kategori_id' => 'required|exists:kategori,id',    // Kategori harus valid
            'stok' => 'required|integer|min:0',                // Stok wajib, angka, minimal 0
            'deskripsi' => 'nullable|string',                  // Deskripsi opsional
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Gambar opsional
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'nama_alat.required' => 'Nama alat wajib diisi.',
            'kategori_id.required' => 'Kategori wajib dipilih.',
            'stok.required' => 'Stok wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.mimes' => 'Format gambar harus JPG, PNG, atau GIF.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);
        
        // Siapkan data yang akan diupdate (tanpa gambar dulu)
        $data = [
            'nama_alat' => $request->nama_alat,
            'kategori_id' => $request->kategori_id,
            'stok' => $request->stok,
            'deskripsi' => $request->deskripsi,
        ];

        // Jika ada gambar baru yang diupload
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama dari storage jika ada
            if ($alat->gambar && \Storage::disk('public')->exists($alat->gambar)) {
                \Storage::disk('public')->delete($alat->gambar);
            }
            // Simpan gambar baru dan masukkan path-nya ke data update
            $data['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        // Update data alat di database
        $alat->update($data);

        // Redirect ke halaman daftar alat dengan pesan sukses
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil diperbarui.');
    }

    /**
     * Menghapus data alat dari database.
     * 
     * Menggunakan Route Model Binding - Laravel otomatis mencari alat berdasarkan ID.
     * Data alat akan dihapus secara permanen dari database.
     */
    public function destroy(Alat $alat)
    {
        // Hapus data alat dari database
        $alat->delete();

        // Redirect ke halaman daftar alat dengan pesan sukses
        return redirect()->route('admin.alat.index')->with('success', 'Alat berhasil dihapus.');
    }
}
