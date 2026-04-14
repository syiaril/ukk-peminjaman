<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Pengguna;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // Dashboard admin dengan data analitik
    public function admin()
    {
        // Statistik utama
        $totalPengguna = Pengguna::count();
        $totalAlat = Alat::count();
        $totalPeminjaman = Peminjaman::count();
        $totalKategori = Kategori::count();
        $totalDenda = Peminjaman::sum('denda') ?? 0;
        $peminjamanAktif = Peminjaman::whereIn('status', ['diajukan', 'disetujui', 'sedang_dikembalikan'])->count();

        // Distribusi status peminjaman (pie chart)
        $statusDistribusi = Peminjaman::select('status', DB::raw('count(*) as jumlah'))
            ->groupBy('status')
            ->pluck('jumlah', 'status')
            ->toArray();

        // Tren peminjaman per bulan (6 bulan terakhir, line chart)
        $trenBulanan = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulan = Carbon::now()->subMonths($i);
            $trenBulanan[] = [
                'bulan' => $bulan->translatedFormat('M Y'),
                'jumlah' => Peminjaman::whereYear('created_at', $bulan->year)
                    ->whereMonth('created_at', $bulan->month)
                    ->count(),
            ];
        }

        // Alat paling sering dipinjam (top 5, bar chart)
        $alatPopuler = Peminjaman::select('alat_id', DB::raw('count(*) as total'))
            ->groupBy('alat_id')
            ->orderByDesc('total')
            ->limit(5)
            ->with('alat')
            ->get()
            ->map(fn($p) => [
                'nama' => $p->alat->nama_alat ?? 'Dihapus',
                'total' => $p->total,
            ]);

        // Distribusi alat per kategori (doughnut chart)
        $kategoriDistribusi = Kategori::withCount('alat')
            ->orderByDesc('alat_count')
            ->get()
            ->map(fn($k) => [
                'nama' => $k->nama_kategori,
                'jumlah' => $k->alat_count,
            ]);

        // Peminjaman terbaru (5 terakhir)
        $peminjamanTerbaru = Peminjaman::with(['pengguna', 'alat'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'totalPengguna',
            'totalAlat',
            'totalPeminjaman',
            'totalKategori',
            'totalDenda',
            'peminjamanAktif',
            'statusDistribusi',
            'trenBulanan',
            'alatPopuler',
            'kategoriDistribusi',
            'peminjamanTerbaru'
        ));
    }
}
