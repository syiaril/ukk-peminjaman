<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    // Daftar semua kategori
    public function index()
    {
        $kategori = Kategori::paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    // Form tambah kategori
    public function create()
    {
        return view('admin.kategori.create');
    }

    // Simpan kategori baru
    public function store(Request $request)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255'], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dibuat.');
    }

    // Form edit kategori
    public function edit(Kategori $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    // Update kategori
    public function update(Request $request, Kategori $kategori)
    {
        $request->validate(['nama_kategori' => 'required|string|max:255'], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        $kategori->update([
            'nama_kategori' => $request->nama_kategori,
        ]);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // Hapus kategori (cek dulu apakah masih ada alat terkait)
    public function destroy(Kategori $kategori)
    {
        if ($kategori->alat()->count() > 0) {
            return redirect()->route('admin.kategori.index')
                ->with('error', 'Kategori tidak bisa dihapus karena masih ada ' . $kategori->alat()->count() . ' alat yang terdaftar di kategori ini.');
        }

        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
