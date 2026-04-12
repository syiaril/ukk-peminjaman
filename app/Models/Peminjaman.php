<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Peminjaman
 * 
 * Merepresentasikan data transaksi peminjaman alat.
 * Ini adalah tabel utama yang mencatat siapa meminjam alat apa dan kapan.
 * 
 * Tabel: peminjaman
 * 
 * Alur status peminjaman:
 * 1. 'diajukan'            -> Peminjam mengajukan peminjaman (awal)
 * 2. 'disetujui'           -> Petugas menyetujui, stok berkurang (trigger)
 * 3. 'sedang_dikembalikan' -> Peminjam mengajukan pengembalian
 * 4. 'dikembalikan'        -> Petugas mengkonfirmasi, stok bertambah (trigger), denda dihitung
 *    atau
 * 2. 'ditolak'             -> Petugas menolak peminjaman
 * 
 * Relasi:
 * - belongsTo Pengguna (setiap peminjaman dimiliki oleh satu peminjam)
 * - belongsTo Alat (setiap peminjaman merujuk ke satu alat)
 */
class Peminjaman extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'peminjaman';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     * 
     * - pengguna_id: ID peminjam (foreign key ke tabel pengguna)
     * - alat_id: ID alat yang dipinjam (foreign key ke tabel alat)
     * - jumlah: Jumlah unit alat yang dipinjam (default 1)
     * - tanggal_pinjam: Tanggal mulai peminjaman
     * - tanggal_wajib_kembali: Batas waktu pengembalian (dihitung dari tanggal_pinjam + durasi)
     * - tanggal_kembali: Tanggal alat dikembalikan (null jika belum dikembalikan)
     * - status: Status peminjaman (diajukan/disetujui/sedang_dikembalikan/dikembalikan/ditolak)
     * - denda: Nominal denda keterlambatan (Rp 5.000/hari, dihitung oleh fungsi hitung_denda)
     */
    protected $fillable = [
        'pengguna_id',
        'alat_id',
        'jumlah',
        'tanggal_pinjam',
        'tanggal_wajib_kembali',
        'tanggal_kembali',
        'status',
        'denda',
    ];

    /**
     * Relasi: Peminjaman dimiliki oleh satu Pengguna (peminjam).
     * 
     * Digunakan untuk menampilkan nama peminjam pada tabel peminjaman.
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    /**
     * Relasi: Peminjaman merujuk ke satu Alat.
     * 
     * Digunakan untuk menampilkan nama alat yang dipinjam.
     */
    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
