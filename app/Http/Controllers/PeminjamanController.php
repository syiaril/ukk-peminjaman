<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Untuk Peminjam: Lihat Katalog
    public function katalog(Request $request)
    {
        $query = Alat::with('kategori')->where('stok', '>', 0);

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $alat = $query->paginate(12)->appends($request->query());
        $kategoriList = \App\Models\Kategori::orderBy('nama_kategori')->get();

        return view('peminjam.alat.index', compact('alat', 'kategoriList'));
    }

    // Untuk Peminjam: Ajukan Peminjaman
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1|max:14', // Maksimal 2 minggu
        ], [
            'alat_id.required' => 'Alat wajib dipilih.',
            'alat_id.exists' => 'Alat tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini.',
            'durasi.required' => 'Durasi wajib diisi.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'durasi.max' => 'Durasi maksimal 14 hari.',
        ]);

        $alat = Alat::find($request->alat_id);
        if ($alat->stok < 1) {
            return back()->with('error', 'Stok alat habis.');
        }

        $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tanggalWajibKembali = $tanggalPinjam->copy()->addDays((int)$request->durasi);

        Peminjaman::create([
            'pengguna_id' => Auth::id(),
            'alat_id' => $request->alat_id,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_wajib_kembali' => $tanggalWajibKembali,
            'status' => 'diajukan',
        ]);

        return redirect()->route('peminjam.peminjaman.index')->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    // Untuk Peminjam: Lihat Peminjaman Saya
    public function peminjamanSaya()
    {
        $peminjaman = Peminjaman::where('pengguna_id', Auth::id())->with('alat')->orderBy('created_at', 'desc')->paginate(10);
        return view('peminjam.peminjaman.index', compact('peminjaman'));
    }

    // Untuk Admin: Lihat Semua Peminjaman
    public function adminIndex()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    // Untuk Petugas: Lihat Semua Peminjaman
    public function index()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->paginate(10);
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    // Untuk Petugas: Cetak Laporan
    public function laporan()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->get();
        return view('petugas.laporan', compact('peminjaman'));
    }

    // Untuk Petugas: Setujui Peminjaman
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }

        if ($peminjaman->alat->stok < 1) {
             return back()->with('error', 'Stok alat tidak mencukupi.');
        }

        // Menggunakan Stored Procedure (sesuai kebutuhan)
        DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
        
        return back()->with('success', 'Peminjaman disetujui. Stok diperbarui oleh sistem.');
    }

    // Untuk Petugas: Tolak Peminjaman
    public function reject(Peminjaman $peminjaman)
    {
         if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }
        $peminjaman->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    // Untuk Petugas: Kembalikan Alat
    public function returnTool(Peminjaman $peminjaman)
    {
         if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
            return back()->with('error', 'Peminjaman tidak dalam status dapat dikembalikan.');
        }
        
        // Menggunakan Stored Procedure untuk proses pengembalian
        DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);

        // Refresh data untuk mendapatkan denda yang baru dihitung
        $peminjaman->refresh();

        return back()->with('success', "Alat dikembalikan. Denda: Rp " . number_format($peminjaman->denda));
    }

    // Untuk Peminjam: Ajukan Pengembalian
    public function requestReturn(Peminjaman $peminjaman)
    {
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403);
        }

        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        $peminjaman->update(['status' => 'sedang_dikembalikan']);

        return back()->with('success', 'Permintaan pengembalian diajukan. Silakan serahkan alat ke petugas.');
    }
}
