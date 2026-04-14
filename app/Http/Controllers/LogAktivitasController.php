<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    // Daftar semua log aktivitas
    public function index()
    {
        $log_aktivitas = LogAktivitas::with('pengguna')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.log_aktivitas.index', compact('log_aktivitas'));
    }
}
