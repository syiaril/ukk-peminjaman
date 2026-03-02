<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

/**
 * KategoriController
 * 
 * Controller untuk mengelola data kategori alat.
 * Digunakan oleh Admin untuk operasi CRUD (Create, Read, Update, Delete) kategori.
 * Contoh kategori: Alat Laboratorium IPA, Alat Olahraga, Alat Musik, dll.
 */
class KategoriController extends Controller
{
    /**
     * Menampilkan daftar semua kategori.
     * 
     * Mengambil semua data kategori dari database dan menampilkannya
     * dalam bentuk tabel dengan paginasi (10 data per halaman).
     */
    public function index()
    {
        // Ambil semua kategori dengan paginasi 10 per halaman
        $kategori = Kategori::paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    /**
     * Menampilkan form untuk membuat kategori baru.
     */
    public function create()
    {
        return view('admin.kategori.create');
    }

    /**
     * Menyimpan data kategori baru ke database.
     * 
     * Melakukan validasi: nama kategori wajib diisi, berupa string, maks 255 karakter.
     * Setelah berhasil disimpan, redirect ke halaman daftar kategori.
     */
    public function store(Request $request)
    {
        // Validasi input: nama kategori wajib diisi
        $request->validate(['nama_kategori' => 'required|string|max:255'], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        // Simpan kategori baru ke database
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        // Redirect ke daftar kategori dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dibuat.');
    }

    /**
     * Menampilkan form edit untuk kategori tertentu.
     * 
     * Menggunakan Route Model Binding - Laravel otomatis mencari kategori berdasarkan ID.
     */
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Memperbarui data kategori yang sudah ada di database.
     * 
     * Validasi sama seperti method store().
     */
    public function update(Request $request, Kategori $kategori)
    {
        // Validasi input: nama kategori wajib diisi
        $request->validate(['nama_kategori' => 'required|string|max:255'], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        // Update data kategori di database
        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        // Redirect ke daftar kategori dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Menghapus data kategori dari database.
     * 
     * Sebelum menghapus, dilakukan pengecekan apakah kategori masih memiliki alat.
     * Jika masih ada alat yang terkait, kategori TIDAK BISA dihapus
     * untuk mencegah kehilangan data alat (karena ada foreign key constraint).
     */
    public function destroy(Kategori $kategori)
    {
        // Cek apakah kategori masih memiliki alat yang terkait
        if ($kategori->alat()->count() > 0) {
            // Tolak penghapusan dan tampilkan pesan error
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih ada ' . $kategori->alat()->count() . ' alat yang terdaftar di kategori ini.');
        }

        // Hapus kategori dari database
        $kategori->delete();

        // Redirect ke daftar kategori dengan pesan sukses
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
