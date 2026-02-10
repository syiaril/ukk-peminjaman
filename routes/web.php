<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return redirect()->route('login');
});

// Auth Routes
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});
Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // View file name might still be dashboard? Or rename to beranda? Let's assume view directory rename later.
    })->name('dashboard'); // Route name dashboard is standard, or beranda? Let's use beranda if we go full Indo? But redirects usually look for dashboard. Let's keep route name dashboard for now or update redirects in AuthController.
    // Wait, AuthController redirects to /admin/dashboard. So I should match that.
    // If I change URL `admin/dashboard` to `admin/beranda`, I must update AuthController too.
    // I already checked AuthController, it redirects to `/admin/dashboard`.
    // I should update AuthController to `/admin/beranda` if I change this.
    // The user said "semua migration dan lainnya... command atau keterangan...".
    // I'll keep it simple: Use `/dashboard` URL for now unless instructed, OR update AuthController.
    // But `Route::resource` for `pengguna` creates `/pengguna`.
    // Let's use Indonesian URLs where easy. `users` -> `pengguna`. `dashboard` -> `beranda` is good. I'll update AuthController later.

    Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    Route::resource('alat', \App\Http\Controllers\AlatController::class);
    Route::get('/log-aktivitas', [\App\Http\Controllers\LogAktivitasController::class, 'index'])->name('log_aktivitas.index');

    // Admin Loan Management
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'adminIndex'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return');
});

// Petugas Routes
Route::middleware(['auth', 'peran:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');
    
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/laporan', [\App\Http\Controllers\PeminjamanController::class, 'laporan'])->name('laporan');
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return');
});

// Peminjam Routes
Route::middleware(['auth', 'peran:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    Route::get('/katalog', [\App\Http\Controllers\PeminjamanController::class, 'katalog'])->name('alat.index');
    Route::post('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman-saya', [\App\Http\Controllers\PeminjamanController::class, 'peminjamanSaya'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/ajukan-pengembalian', [\App\Http\Controllers\PeminjamanController::class, 'requestReturn'])->name('peminjaman.return-request');
});
