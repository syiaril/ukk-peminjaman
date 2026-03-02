<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * AuthController
 * 
 * Controller untuk mengelola proses autentikasi (login & logout).
 * Menangani tampilan form login, proses verifikasi kredensial,
 * dan proses logout pengguna dari sistem.
 */
class AuthController extends Controller
{
    /**
     * Menampilkan halaman form login.
     * 
     * Halaman ini hanya bisa diakses oleh pengguna yang belum login (guest),
     * diatur melalui middleware 'guest' di routes/web.php.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Memproses percobaan login pengguna.
     * 
     * Langkah-langkah:
     * 1. Validasi input email dan password
     * 2. Cek kredensial dengan Auth::attempt()
     * 3. Jika berhasil, regenerasi session untuk keamanan (mencegah session fixation)
     * 4. Redirect ke dashboard sesuai peran pengguna (admin/petugas/peminjam)
     * 5. Jika gagal, kembalikan ke halaman login dengan pesan error
     */
    public function login(Request $request)
    {
        // Validasi input: email dan password wajib diisi
        $credentials = $request->validate([
            'email' => ['required', 'email'],    // Email wajib dan format valid
            'password' => ['required'],           // Password wajib diisi
        ]);

        // Coba autentikasi dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {
            // Regenerasi session ID untuk keamanan (mencegah session fixation attack)
            $request->session()->regenerate();

            // Ambil peran pengguna yang berhasil login
            $peran = Auth::user()->peran;

            // Arahkan ke dashboard sesuai peran pengguna
            if ($peran === 'admin') {
                return redirect()->intended('/admin/dashboard');      // Dashboard Admin
            } elseif ($peran === 'petugas') {
                return redirect()->intended('/petugas/dashboard');    // Dashboard Petugas
            } else {
                return redirect()->intended('/peminjam/dashboard');   // Dashboard Peminjam
            }
        }

        // Jika autentikasi gagal, kembali ke form login dengan pesan error
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan catatan kami.',
        ])->onlyInput('email'); // Hanya kembalikan input email (password dikosongkan)
    }

    /**
     * Memproses logout pengguna.
     * 
     * Langkah-langkah:
     * 1. Logout pengguna dari sistem (Auth::logout)
     * 2. Invalidasi session yang sedang aktif
     * 3. Regenerasi token CSRF untuk keamanan
     * 4. Redirect ke halaman utama (/)
     */
    public function logout(Request $request)
    {
        // Logout pengguna dari sistem
        Auth::logout();

        // Hapus semua data session
        $request->session()->invalidate();

        // Regenerasi token CSRF untuk keamanan
        $request->session()->regenerateToken();

        // Redirect ke halaman utama
        return redirect('/');
    }
}
