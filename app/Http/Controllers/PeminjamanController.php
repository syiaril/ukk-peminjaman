<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Katalog alat untuk peminjam
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

    // Simpan pengajuan peminjaman baru
    public function store(Request $request)
    {
        $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date|after_or_equal:today',
            'durasi' => 'required|integer|min:1|max:3',
        ], [
            'alat_id.required' => 'Alat wajib dipilih.',
            'alat_id.exists' => 'Alat tidak valid.',
            'jumlah.required' => 'Jumlah wajib diisi.',
            'jumlah.min' => 'Jumlah minimal 1.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini.',
            'durasi.required' => 'Durasi wajib diisi.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'durasi.max' => 'Durasi maksimal 3 hari.',
        ]);

        $alat = Alat::find($request->alat_id);
        $jumlah = (int) $request->jumlah;
        if ($alat->stok < $jumlah) {
            return back()->with('error', 'Stok alat tidak mencukupi. Stok tersedia: ' . $alat->stok);
        }

        $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tanggalWajibKembali = $tanggalPinjam->copy()->addDays((int)$request->durasi);

        Peminjaman::create([
            'pengguna_id' => Auth::id(),
            'alat_id' => $request->alat_id,
            'jumlah' => $jumlah,
            'tanggal_pinjam' => $tanggalPinjam,
            'tanggal_wajib_kembali' => $tanggalWajibKembali,
            'status' => 'diajukan',
        ]);

        return redirect()->route('peminjam.peminjaman.index')->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    // Daftar peminjaman milik user yang login
    public function peminjamanSaya()
    {
        $peminjaman = Peminjaman::where('pengguna_id', Auth::id())->with('alat')->orderBy('created_at', 'desc')->paginate(10);
        return view('peminjam.peminjaman.index', compact('peminjaman'));
    }

    // Daftar semua peminjaman (admin)
    public function adminIndex()
    {
        $today = now()->toDateString();
        
        $peminjaman = Peminjaman::from('peminjaman as p_main') // Gunakan alias agar tidak rancu
            ->with(['pengguna', 'alat'])
            ->select('p_main.*')
            // Hitung riwayat aman peminjam (hanya milik user tersebut)
            ->addSelect(['riwayat_aman' => Peminjaman::selectRaw('count(*)')
                ->whereColumn('pengguna_id', 'p_main.pengguna_id')
                ->where('status', 'dikembalikan')
                ->where('denda', 0)
            ])
            // Hitung riwayat telat peminjam (hanya milik user tersebut)
            ->addSelect(['riwayat_telat' => Peminjaman::selectRaw('count(*)')
                ->whereColumn('pengguna_id', 'p_main.pengguna_id')
                ->where(function($q) use ($today) {
                    $q->where('denda', '>', 0)
                      ->orWhere(function($sq) use ($today) {
                          $sq->where('status', 'disetujui')
                            ->where('tanggal_wajib_kembali', '<', $today);
                      });
                })
            ])
            ->orderByRaw("CASE WHEN status = 'diajukan' THEN 0 ELSE 1 END")
            ->orderBy('riwayat_telat', 'asc')
            ->orderBy('riwayat_aman', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    // Daftar semua peminjaman (petugas)
    public function index()
    {
        $today = now()->toDateString();

        $peminjaman = Peminjaman::from('peminjaman as p_main') // Gunakan alias agar tidak rancu
            ->with(['pengguna', 'alat'])
            ->select('p_main.*')
            ->addSelect(['riwayat_aman' => Peminjaman::selectRaw('count(*)')
                ->whereColumn('pengguna_id', 'p_main.pengguna_id')
                ->where('status', 'dikembalikan')
                ->where('denda', 0)
            ])
            ->addSelect(['riwayat_telat' => Peminjaman::selectRaw('count(*)')
                ->whereColumn('pengguna_id', 'p_main.pengguna_id')
                ->where(function($q) use ($today) {
                    $q->where('denda', '>', 0)
                      ->orWhere(function($sq) use ($today) {
                          $sq->where('status', 'disetujui')
                            ->where('tanggal_wajib_kembali', '<', $today);
                      });
                })
            ])
            ->orderByRaw("CASE WHEN status = 'diajukan' THEN 0 ELSE 1 END")
            ->orderBy('riwayat_telat', 'asc')
            ->orderBy('riwayat_aman', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    // Cetak laporan peminjaman
    public function laporan()
    {
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->get();
        return view('petugas.laporan', compact('peminjaman'));
    }

    // Setujui peminjaman (via stored procedure)
    public function approve(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }

        if ($peminjaman->alat->stok < $peminjaman->jumlah) {
             return back()->with('error', 'Stok alat tidak mencukupi. Stok tersedia: ' . $peminjaman->alat->stok . ', dibutuhkan: ' . $peminjaman->jumlah);
        }

        DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
        
        return back()->with('success', 'Peminjaman disetujui. Stok diperbarui oleh sistem.');
    }

    // Tolak peminjaman
    public function reject(Peminjaman $peminjaman)
    {
         if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }

        $peminjaman->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    // Proses pengembalian alat (via stored procedure)
    public function returnTool(Peminjaman $peminjaman)
    {
         if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
            return back()->with('error', 'Peminjaman tidak dalam status dapat dikembalikan.');
        }
        
        DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);
        $peminjaman->refresh();

        return back()->with('success', "Alat dikembalikan. Denda: Rp " . number_format($peminjaman->denda));
    }

    // Peminjam mengajukan pengembalian
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
