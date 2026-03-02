<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

/**
 * LogAktivitasController
 * 
 * Controller untuk menampilkan log aktivitas sistem.
 * Digunakan oleh Admin untuk melihat riwayat semua aktivitas yang terjadi di sistem,
 * seperti: login, tambah alat, setujui peminjaman, konfirmasi pengembalian, dll.
 * 
 * Log aktivitas dicatat secara otomatis melalui Stored Procedure di database.
 */
class LogAktivitasController extends Controller
{
    /**
     * Menampilkan daftar semua log aktivitas.
     * 
     * Mengambil data log beserta relasi pengguna (siapa yang melakukan aksi),
     * diurutkan dari yang terbaru (desc) dan dipaginasi 20 data per halaman.
     */
    public function index()
    {
        // Ambil semua log aktivitas dengan relasi pengguna, urutkan terbaru, paginasi 20
        $log_aktivitas = LogAktivitas::with('pengguna')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.log_aktivitas.index', compact('log_aktivitas'));
    }
}
