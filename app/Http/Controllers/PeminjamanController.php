<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Alat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * PeminjamanController
 * 
 * Controller utama untuk mengelola seluruh proses peminjaman alat.
 * Controller ini digunakan oleh 3 peran:
 * - Peminjam: melihat katalog, mengajukan peminjaman, melihat riwayat, ajukan pengembalian
 * - Petugas: melihat daftar peminjaman, menyetujui/menolak, memproses pengembalian, cetak laporan
 * - Admin: melihat semua peminjaman dan mengelolanya
 */
class PeminjamanController extends Controller
{
    /**
     * [PEMINJAM] Menampilkan katalog alat yang tersedia untuk dipinjam.
     * 
     * Hanya menampilkan alat yang stoknya > 0 (masih tersedia).
     * Mendukung filter berdasarkan kategori melalui parameter 'kategori_id'.
     * Data dipaginasi 12 per halaman (grid 3x4 atau 4x3).
     */
    public function katalog(Request $request)
    {
        // Query alat yang stoknya masih ada (> 0), beserta data kategorinya
        $query = Alat::with('kategori')->where('stok', '>', 0);

        // Jika ada filter kategori yang dipilih, tambahkan kondisi WHERE
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        // Paginasi 12 per halaman dan pertahankan parameter query string (untuk filter)
        $alat = $query->paginate(12)->appends($request->query());

        // Ambil semua kategori untuk dropdown filter
        $kategoriList = \App\Models\Kategori::orderBy('nama_kategori')->get();

        return view('peminjam.alat.index', compact('alat', 'kategoriList'));
    }

    /**
     * [PEMINJAM] Menyimpan pengajuan peminjaman baru.
     * 
     * Validasi:
     * - Alat harus valid dan stok tersedia
     * - Tanggal pinjam tidak boleh sebelum hari ini
     * - Durasi peminjaman 1-14 hari (maksimal 2 minggu)
     * 
     * Tanggal wajib kembali dihitung otomatis: tanggal_pinjam + durasi.
     * Status awal peminjaman: 'diajukan' (menunggu persetujuan petugas).
     */
    public function store(Request $request)
    {
        // Validasi data input peminjaman
        $request->validate([
            'alat_id' => 'required|exists:alat,id',                    // Alat harus valid
            'tanggal_pinjam' => 'required|date|after_or_equal:today',  // Tanggal tidak boleh lampau
            'durasi' => 'required|integer|min:1|max:14',               // Durasi 1-14 hari
        ], [
            // Pesan error kustom dalam Bahasa Indonesia
            'alat_id.required' => 'Alat wajib dipilih.',
            'alat_id.exists' => 'Alat tidak valid.',
            'tanggal_pinjam.required' => 'Tanggal pinjam wajib diisi.',
            'tanggal_pinjam.after_or_equal' => 'Tanggal pinjam tidak boleh kurang dari hari ini.',
            'durasi.required' => 'Durasi wajib diisi.',
            'durasi.min' => 'Durasi minimal 1 hari.',
            'durasi.max' => 'Durasi maksimal 14 hari.',
        ]);

        // Cek apakah stok alat masih tersedia
        $alat = Alat::find($request->alat_id);
        if ($alat->stok < 1) {
            return back()->with('error', 'Stok alat habis.');
        }

        // Hitung tanggal wajib kembali berdasarkan tanggal pinjam + durasi
        $tanggalPinjam = \Carbon\Carbon::parse($request->tanggal_pinjam);
        $tanggalWajibKembali = $tanggalPinjam->copy()->addDays((int)$request->durasi);

        // Simpan data peminjaman baru ke database dengan status 'diajukan'
        Peminjaman::create([
            'pengguna_id' => Auth::id(),                    // ID peminjam yang sedang login
            'alat_id' => $request->alat_id,                // ID alat yang dipinjam
            'tanggal_pinjam' => $tanggalPinjam,            // Tanggal mulai pinjam
            'tanggal_wajib_kembali' => $tanggalWajibKembali, // Batas waktu pengembalian
            'status' => 'diajukan',                        // Status awal: menunggu persetujuan
        ]);

        // Redirect ke halaman riwayat peminjaman dengan pesan sukses
        return redirect()->route('peminjam.peminjaman.index')->with('success', 'Permintaan peminjaman berhasil diajukan.');
    }

    /**
     * [PEMINJAM] Menampilkan daftar peminjaman milik peminjam yang sedang login.
     * 
     * Hanya menampilkan peminjaman yang dimiliki oleh pengguna yang sedang login.
     * Data diurutkan dari yang terbaru dan dipaginasi 10 per halaman.
     */
    public function peminjamanSaya()
    {
        // Ambil peminjaman milik pengguna yang sedang login, beserta data alat
        $peminjaman = Peminjaman::where('pengguna_id', Auth::id())->with('alat')->orderBy('created_at', 'desc')->paginate(10);
        return view('peminjam.peminjaman.index', compact('peminjaman'));
    }

    /**
     * [ADMIN] Menampilkan semua data peminjaman untuk halaman admin.
     * 
     * Admin dapat melihat semua peminjaman dari semua pengguna,
     * beserta data pengguna dan alat yang terkait.
     */
    public function adminIndex()
    {
        // Ambil semua peminjaman beserta relasi pengguna dan alat, urutkan terbaru
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.peminjaman.index', compact('peminjaman'));
    }

    /**
     * [PETUGAS] Menampilkan semua data peminjaman untuk halaman petugas.
     * 
     * Petugas dapat melihat semua peminjaman dan melakukan aksi
     * (setujui, tolak, proses pengembalian).
     */
    public function index()
    {
        // Ambil semua peminjaman beserta relasi pengguna dan alat, urutkan terbaru
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->paginate(10);
        return view('petugas.peminjaman.index', compact('peminjaman'));
    }

    /**
     * [PETUGAS] Menampilkan halaman cetak laporan peminjaman.
     * 
     * Mengambil SEMUA data peminjaman (tanpa paginasi) untuk dicetak.
     */
    public function laporan()
    {
        // Ambil semua peminjaman tanpa paginasi untuk keperluan cetak laporan
        $peminjaman = Peminjaman::with(['pengguna', 'alat'])->orderBy('created_at', 'desc')->get();
        return view('petugas.laporan', compact('peminjaman'));
    }

    /**
     * [PETUGAS/ADMIN] Menyetujui peminjaman yang statusnya 'diajukan'.
     * 
     * Validasi:
     * - Status peminjaman harus 'diajukan'
     * - Stok alat harus masih tersedia (>= 1)
     * 
     * Proses persetujuan menggunakan Stored Procedure 'proses_persetujuan_peminjaman'
     * yang secara otomatis:
     * 1. Mengubah status menjadi 'disetujui'
     * 2. Mencatat log aktivitas
     * 3. Trigger database otomatis mengurangi stok alat
     */
    public function approve(Peminjaman $peminjaman)
    {
        // Validasi: hanya peminjaman dengan status 'diajukan' yang bisa disetujui
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }

        // Cek apakah stok alat masih mencukupi
        if ($peminjaman->alat->stok < 1) {
             return back()->with('error', 'Stok alat tidak mencukupi.');
        }

        // Panggil Stored Procedure untuk proses persetujuan
        // Procedure ini mengubah status dan mencatat log secara transaksional
        DB::statement('CALL proses_persetujuan_peminjaman(?, ?)', [$peminjaman->id, Auth::id()]);
        
        return back()->with('success', 'Peminjaman disetujui. Stok diperbarui oleh sistem.');
    }

    /**
     * [PETUGAS/ADMIN] Menolak peminjaman yang statusnya 'diajukan'.
     * 
     * Hanya mengubah status menjadi 'ditolak'.
     * Stok alat TIDAK berubah karena belum pernah dikurangi.
     */
    public function reject(Peminjaman $peminjaman)
    {
        // Validasi: hanya peminjaman dengan status 'diajukan' yang bisa ditolak
         if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman tidak dalam status diajukan.');
        }

        // Update status menjadi 'ditolak'
        $peminjaman->update(['status' => 'ditolak']);
        return back()->with('success', 'Peminjaman ditolak.');
    }

    /**
     * [PETUGAS/ADMIN] Memproses pengembalian alat yang sudah dipinjam.
     * 
     * Validasi: status harus 'disetujui' atau 'sedang_dikembalikan'.
     * 
     * Proses pengembalian menggunakan Stored Procedure 'proses_pengembalian'
     * yang secara otomatis:
     * 1. Menghitung denda menggunakan fungsi hitung_denda() (Rp 5.000/hari keterlambatan)
     * 2. Mengubah status menjadi 'dikembalikan'
     * 3. Mengisi tanggal_kembali dengan tanggal hari ini
     * 4. Mencatat log aktivitas
     * 5. Trigger database otomatis menambah stok alat kembali
     */
    public function returnTool(Peminjaman $peminjaman)
    {
        // Validasi: hanya peminjaman yang 'disetujui' atau 'sedang_dikembalikan' yang bisa dikembalikan
         if ($peminjaman->status !== 'disetujui' && $peminjaman->status !== 'sedang_dikembalikan') {
            return back()->with('error', 'Peminjaman tidak dalam status dapat dikembalikan.');
        }
        
        // Panggil Stored Procedure untuk proses pengembalian
        // Procedure ini menghitung denda, update status, dan mencatat log secara transaksional
        DB::statement('CALL proses_pengembalian(?, ?)', [$peminjaman->id, Auth::id()]);

        // Refresh data model untuk mendapatkan nilai denda yang baru dihitung oleh procedure
        $peminjaman->refresh();

        // Tampilkan pesan sukses beserta nominal denda
        return back()->with('success', "Alat dikembalikan. Denda: Rp " . number_format($peminjaman->denda));
    }

    /**
     * [PEMINJAM] Mengajukan permintaan pengembalian alat.
     * 
     * Peminjam mengajukan pengembalian, kemudian harus menyerahkan alat ke petugas.
     * Status berubah dari 'disetujui' menjadi 'sedang_dikembalikan'.
     * 
     * Validasi keamanan: memastikan peminjaman ini milik pengguna yang sedang login
     * (mencegah peminjam mengembalikan pinjaman orang lain).
     */
    public function requestReturn(Peminjaman $peminjaman)
    {
        // Validasi kepemilikan: pastikan peminjaman ini milik pengguna yang login
        if ($peminjaman->pengguna_id !== Auth::id()) {
            abort(403); // Forbidden - akses ditolak
        }

        // Validasi status: hanya peminjaman yang 'disetujui' yang bisa diajukan pengembalian
        if ($peminjaman->status !== 'disetujui') {
            return back()->with('error', 'Hanya peminjaman yang disetujui yang dapat dikembalikan.');
        }

        // Update status menjadi 'sedang_dikembalikan'
        $peminjaman->update(['status' => 'sedang_dikembalikan']);

        return back()->with('success', 'Permintaan pengembalian diajukan. Silakan serahkan alat ke petugas.');
    }
}
