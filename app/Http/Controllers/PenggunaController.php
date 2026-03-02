<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

/**
 * PenggunaController
 * 
 * Controller untuk mengelola data pengguna sistem.
 * Digunakan oleh Admin untuk operasi CRUD (Create, Read, Update, Delete) pengguna.
 * 
 * Tipe pengguna yang bisa dikelola:
 * - Admin: mengelola seluruh sistem
 * - Petugas: mengelola peminjaman dan pengembalian
 * - Peminjam: meminjam alat (siswa/guru)
 */
class PenggunaController extends Controller
{
    /**
     * Menampilkan daftar semua pengguna.
     * 
     * Data diurutkan dari yang terbaru (latest) dan dipaginasi 10 per halaman.
     */
    public function index()
    {
        // Ambil semua pengguna, urutkan terbaru, paginasi 10 per halaman
        $pengguna = Pengguna::latest()->paginate(10);
        return view('admin.pengguna.index', compact('pengguna'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function create()
    {
        return view('admin.pengguna.create');
    }

    /**
     * Menyimpan data pengguna baru ke database.
     * 
     * Validasi:
     * - Nama wajib diisi (maks 255 karakter)
     * - Email wajib, format valid, dan unik (belum terdaftar)
     * - Password wajib diisi, minimal 8 karakter
     * - Peran wajib dipilih: admin, petugas, atau peminjam
     * 
     * Password di-hash menggunakan Hash::make() sebelum disimpan ke database
     * untuk keamanan (tidak disimpan dalam bentuk plain text).
     */
    public function store(Request $request)
    {
        // Validasi input data pengguna baru
        $request->validate([
            'nama' => 'required|string|max:255',                  // Nama wajib diisi
            'email' => 'required|string|email|max:255|unique:pengguna', // Email unik
            'password' => 'required|string|min:8',                // Password minimal 8 karakter
            'peran' => 'required|in:admin,petugas,peminjam',      // Peran harus salah satu dari 3 pilihan
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'peran.required' => 'Peran wajib dipilih.',
        ]);

        // Simpan pengguna baru ke database dengan password yang sudah di-hash
        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->password), // Hash password untuk keamanan
            'peran' => $request->peran,
        ]);

        // Redirect ke daftar pengguna dengan pesan sukses
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    /**
     * Menampilkan form edit untuk pengguna tertentu.
     * 
     * Menggunakan Route Model Binding - Laravel otomatis mencari pengguna berdasarkan ID.
     */
    public function edit(Pengguna $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    /**
     * Memperbarui data pengguna yang sudah ada di database.
     * 
     * Validasi email menggunakan Rule::unique()->ignore() agar email
     * pengguna yang sedang diedit tidak dianggap duplikat.
     * 
     * Password bersifat opsional saat update - jika tidak diisi,
     * password lama tetap digunakan.
     */
    public function update(Request $request, Pengguna $pengguna)
    {
        // Validasi data update pengguna
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('pengguna')->ignore($pengguna->id)], // Abaikan email milik sendiri
            'peran' => 'required|in:admin,petugas,peminjam',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'peran.required' => 'Peran wajib dipilih.',
        ]);

        // Siapkan data yang akan diupdate (tanpa password dulu)
        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'peran' => $request->peran,
        ];

        // Jika admin mengisi field password, update password juga
        if ($request->filled('password')) {
            // Validasi password minimal 8 karakter
            $request->validate(['password' => 'string|min:8'], ['password.min' => 'Kata sandi minimal 8 karakter.']);
            // Hash password baru dan masukkan ke data update
            $data['kata_sandi'] = Hash::make($request->password);
        }

        // Update data pengguna di database
        $pengguna->update($data);

        // Redirect ke daftar pengguna dengan pesan sukses
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna dari database.
     * 
     * Menggunakan Route Model Binding - Laravel otomatis mencari pengguna berdasarkan ID.
     * Data pengguna akan dihapus secara permanen.
     */
    public function destroy(Pengguna $pengguna)
    {
        // Hapus data pengguna dari database
        $pengguna->delete();

        // Redirect ke daftar pengguna dengan pesan sukses
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
