<?php

/**
 * ============================================================
 * ROUTES WEB - Konfigurasi Routing Aplikasi Peminjaman Alat
 * ============================================================
 * 
 * File ini mendefinisikan semua URL/rute yang bisa diakses di aplikasi.
 * Rute dikelompokkan berdasarkan peran pengguna dan dilindungi oleh middleware.
 * 
 * Struktur rute:
 * 1. Rute Publik (Guest) - Halaman login
 * 2. Rute Admin - Kelola pengguna, kategori, alat, peminjaman, log
 * 3. Rute Petugas - Kelola peminjaman dan laporan
 * 4. Rute Peminjam - Lihat katalog, ajukan peminjaman, lihat riwayat
 */

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// ============================================================
// RUTE UTAMA
// ============================================================
// Redirect halaman utama (/) ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ============================================================
// RUTE AUTENTIKASI (Login & Logout)
// ============================================================
// Rute login hanya bisa diakses oleh pengguna yang BELUM login (guest)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');    // Tampilkan form login
    Route::post('login', [AuthController::class, 'login']);                           // Proses login
});

// Rute logout hanya bisa diakses oleh pengguna yang SUDAH login (auth)
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ============================================================
// RUTE ADMIN
// ============================================================
// Semua rute admin dilindungi oleh:
// - middleware 'auth': harus sudah login
// - middleware 'peran:admin': hanya peran admin yang bisa mengakses
// - prefix 'admin': semua URL diawali dengan /admin/...
// - name 'admin.': semua nama rute diawali dengan admin.
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin - Halaman utama setelah admin login
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    // CRUD Pengguna - Kelola data pengguna (admin, petugas, peminjam)
    // Otomatis membuat rute: index, create, store, show, edit, update, destroy
    Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);

    // CRUD Kategori - Kelola kategori alat (Lab IPA, Olahraga, Musik, dll)
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);

    // CRUD Alat - Kelola data alat/peralatan sekolah
    Route::resource('alat', \App\Http\Controllers\AlatController::class);

    // Log Aktivitas - Lihat riwayat semua aktivitas di sistem (read-only)
    Route::get('/log-aktivitas', [\App\Http\Controllers\LogAktivitasController::class, 'index'])->name('log_aktivitas.index');

    // Manajemen Peminjaman oleh Admin
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'adminIndex'])->name('peminjaman.index');           // Lihat semua peminjaman
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');  // Setujui peminjaman
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');      // Tolak peminjaman
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return'); // Proses pengembalian
});

// ============================================================
// RUTE PETUGAS
// ============================================================
// Semua rute petugas dilindungi oleh:
// - middleware 'auth': harus sudah login
// - middleware 'peran:petugas': hanya peran petugas yang bisa mengakses
// - prefix 'petugas': semua URL diawali dengan /petugas/...
Route::middleware(['auth', 'peran:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    
    // Dashboard Petugas - Halaman utama setelah petugas login
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');
    
    // Kelola Peminjaman
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');                // Lihat semua peminjaman
    Route::get('/laporan', [\App\Http\Controllers\PeminjamanController::class, 'laporan'])->name('laporan');                          // Halaman cetak laporan
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');  // Setujui peminjaman
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');      // Tolak peminjaman
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return'); // Proses pengembalian
});

// ============================================================
// RUTE PEMINJAM
// ============================================================
// Semua rute peminjam dilindungi oleh:
// - middleware 'auth': harus sudah login
// - middleware 'peran:peminjam': hanya peran peminjam yang bisa mengakses
// - prefix 'peminjam': semua URL diawali dengan /peminjam/...
Route::middleware(['auth', 'peran:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    
    // Dashboard Peminjam - Halaman utama setelah peminjam login
    Route::get('/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    // Katalog Alat - Lihat daftar alat yang tersedia untuk dipinjam
    Route::get('/katalog', [\App\Http\Controllers\PeminjamanController::class, 'katalog'])->name('alat.index');

    // Ajukan Peminjaman - Kirim form pengajuan peminjaman baru
    Route::post('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');

    // Riwayat Peminjaman - Lihat daftar peminjaman milik pengguna yang login
    Route::get('/peminjaman-saya', [\App\Http\Controllers\PeminjamanController::class, 'peminjamanSaya'])->name('peminjaman.index');

    // Ajukan Pengembalian - Peminjam mengajukan pengembalian alat ke petugas
    Route::post('/peminjaman/{peminjaman}/ajukan-pengembalian', [\App\Http\Controllers\PeminjamanController::class, 'requestReturn'])->name('peminjaman.return-request');
});
