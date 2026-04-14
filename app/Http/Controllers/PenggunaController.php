<?php

namespace App\Http\Controllers;

use App\Models\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    // Daftar semua pengguna
    public function index()
    {
        $pengguna = Pengguna::latest()->paginate(10);
        return view('admin.pengguna.index', compact('pengguna'));
    }

    // Form tambah pengguna
    public function create()
    {
        return view('admin.pengguna.create');
    }

    // Simpan pengguna baru
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:pengguna',
            'password' => 'required|string|min:8',
            'peran' => 'required|in:admin,petugas,peminjam',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Kata sandi wajib diisi.',
            'peran.required' => 'Peran wajib dipilih.',
        ]);

        Pengguna::create([
            'nama' => $request->nama,
            'email' => $request->email,
            'kata_sandi' => Hash::make($request->password),
            'peran' => $request->peran,
        ]);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dibuat.');
    }

    // Form edit pengguna
    public function edit(Pengguna $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    // Update pengguna
    public function update(Request $request, Pengguna $pengguna)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('pengguna')->ignore($pengguna->id)],
            'peran' => 'required|in:admin,petugas,peminjam',
        ], [
            'nama.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'peran.required' => 'Peran wajib dipilih.',
        ]);

        $data = [
            'nama' => $request->nama,
            'email' => $request->email,
            'peran' => $request->peran,
        ];

        // Update password jika diisi
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8'], ['password.min' => 'Kata sandi minimal 8 karakter.']);
            $data['kata_sandi'] = Hash::make($request->password);
        }

        $pengguna->update($data);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    // Hapus pengguna
    public function destroy(Pengguna $pengguna)
    {
        $pengguna->delete();
        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
