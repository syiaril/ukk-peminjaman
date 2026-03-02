<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

/**
 * RoleMiddleware (Middleware Peran)
 * 
 * Middleware kustom untuk mengontrol akses berdasarkan peran (role) pengguna.
 * Digunakan untuk membatasi halaman yang hanya bisa diakses oleh peran tertentu.
 * 
 * Cara penggunaan di routes/web.php:
 * - middleware('peran:admin')           -> Hanya admin yang bisa akses
 * - middleware('peran:petugas')         -> Hanya petugas yang bisa akses
 * - middleware('peran:admin,petugas')   -> Admin DAN petugas bisa akses
 * 
 * Middleware ini didaftarkan di bootstrap/app.php dengan alias 'peran'.
 */
class RoleMiddleware
{
    /**
     * Memproses request HTTP yang masuk.
     * 
     * Langkah-langkah:
     * 1. Cek apakah pengguna sudah login
     *    - Jika belum login -> redirect ke halaman login
     * 2. Cek apakah peran pengguna termasuk dalam daftar peran yang diizinkan
     *    - Jika cocok -> lanjutkan ke halaman yang dituju
     *    - Jika tidak cocok -> redirect ke dashboard sesuai perannya
     * 
     * @param Request $request     Request HTTP yang masuk
     * @param Closure $next        Callback untuk melanjutkan ke middleware/controller berikutnya
     * @param mixed   ...$roles    Daftar peran yang diizinkan (bisa lebih dari satu)
     * @return Response
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek apakah pengguna sudah login
        if (!Auth::check()) {
            // Belum login -> redirect ke halaman login
            return redirect()->route('login');
        }

        // Ambil data pengguna yang sedang login
        $user = Auth::user();
        
        // Cek apakah peran pengguna termasuk dalam daftar peran yang diizinkan
        if (in_array($user->peran, $roles)) {
            // Peran sesuai -> lanjutkan request ke halaman yang dituju
            return $next($request);
        }

        // Peran tidak sesuai -> redirect ke dashboard sesuai peran pengguna
        // (bukan menampilkan error 403, tapi diarahkan ke halaman yang sesuai)
        if ($user->peran === 'admin') {
            return redirect()->route('admin.dashboard');       // Redirect ke dashboard admin
        } elseif ($user->peran === 'petugas') {
            return redirect()->route('petugas.dashboard');     // Redirect ke dashboard petugas
        } else {
            return redirect()->route('peminjam.dashboard');    // Redirect ke dashboard peminjam
        }
    }
}
