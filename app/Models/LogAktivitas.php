<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model LogAktivitas
 * 
 * Merepresentasikan catatan/log aktivitas yang terjadi di dalam sistem.
 * Log ini digunakan untuk audit trail - melacak siapa melakukan apa dan kapan.
 * 
 * Tabel: log_aktivitas
 * 
 * Contoh log:
 * - "Administrator menambahkan alat baru: Mikroskop"
 * - "Petugas Budi menyetujui peminjaman #5"
 * - "Petugas Siti mengkonfirmasi pengembalian #3"
 * 
 * Relasi:
 * - belongsTo Pengguna (setiap log dimiliki oleh satu pengguna yang melakukan aksi)
 */
class LogAktivitas extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'log_aktivitas';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     * 
     * - pengguna_id: ID pengguna yang melakukan aksi (foreign key ke tabel pengguna)
     * - aksi: Jenis aksi yang dilakukan (contoh: 'Login', 'Tambah Alat', 'Setujui Peminjaman')
     * - deskripsi: Detail penjelasan aksi yang dilakukan (opsional)
     */
    protected $fillable = ['pengguna_id', 'aksi', 'deskripsi'];

    /**
     * Relasi: Log aktivitas dimiliki oleh satu Pengguna.
     * 
     * Digunakan untuk menampilkan nama pengguna yang melakukan aksi pada halaman log.
     */
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
