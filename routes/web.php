<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

// Redirect ke login
Route::get('/', function () {
    return redirect()->route('login');
});

// Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Admin
Route::middleware(['auth', 'peran:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('dashboard');

    // CRUD
    Route::resource('pengguna', \App\Http\Controllers\PenggunaController::class);
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class);
    Route::resource('alat', \App\Http\Controllers\AlatController::class);

    // Log aktivitas (read-only)
    Route::get('/log-aktivitas', [\App\Http\Controllers\LogAktivitasController::class, 'index'])->name('log_aktivitas.index');

    // Manajemen peminjaman
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'adminIndex'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return');
});

// Rute Petugas
Route::middleware(['auth', 'peran:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
    Route::get('/dashboard', function () {
        return view('petugas.dashboard');
    })->name('dashboard');

    // Kelola peminjaman & laporan
    Route::get('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'index'])->name('peminjaman.index');
    Route::get('/laporan', [\App\Http\Controllers\PeminjamanController::class, 'laporan'])->name('laporan');
    Route::post('/peminjaman/{peminjaman}/setujui', [\App\Http\Controllers\PeminjamanController::class, 'approve'])->name('peminjaman.approve');
    Route::post('/peminjaman/{peminjaman}/tolak', [\App\Http\Controllers\PeminjamanController::class, 'reject'])->name('peminjaman.reject');
    Route::post('/peminjaman/{peminjaman}/kembali', [\App\Http\Controllers\PeminjamanController::class, 'returnTool'])->name('peminjaman.return');
});

// Rute Peminjam
Route::middleware(['auth', 'peran:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function () {
    Route::get('/dashboard', function () {
        return view('peminjam.dashboard');
    })->name('dashboard');

    Route::get('/katalog', [\App\Http\Controllers\PeminjamanController::class, 'katalog'])->name('alat.index');
    Route::post('/peminjaman', [\App\Http\Controllers\PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::get('/peminjaman-saya', [\App\Http\Controllers\PeminjamanController::class, 'peminjamanSaya'])->name('peminjaman.index');
    Route::post('/peminjaman/{peminjaman}/ajukan-pengembalian', [\App\Http\Controllers\PeminjamanController::class, 'requestReturn'])->name('peminjaman.return-request');
});
