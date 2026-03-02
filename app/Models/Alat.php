<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Alat
 * 
 * Merepresentasikan data alat/peralatan sekolah yang bisa dipinjam.
 * Setiap alat memiliki kategori, nama, deskripsi, stok, dan gambar.
 * 
 * Tabel: alat
 * 
 * Relasi:
 * - belongsTo Kategori (setiap alat memiliki satu kategori)
 * - hasMany Peminjaman (satu alat bisa dipinjam berkali-kali)
 */
class Alat extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'alat';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     * 
     * - kategori_id: ID kategori alat (foreign key ke tabel kategori)
     * - nama_alat: Nama alat (contoh: 'Mikroskop Binokuler')
     * - deskripsi: Deskripsi/keterangan alat (opsional)
     * - stok: Jumlah alat yang tersedia
     * - gambar: Path file gambar alat (disimpan di storage/app/public/alat/)
     */
    protected $fillable = [
        'kategori_id',
        'nama_alat',
        'deskripsi',
        'stok',
        'gambar',
    ];

    /**
     * Relasi: Alat dimiliki oleh satu Kategori.
     * 
     * Contoh: Mikroskop Binokuler -> kategori "Alat Laboratorium IPA"
     */
    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    /**
     * Relasi: Satu alat bisa memiliki banyak Peminjaman.
     * 
     * Contoh: Mikroskop Binokuler bisa dipinjam oleh banyak siswa di waktu berbeda.
     */
    public function peminjaman()
    {
        return $this->hasMany(Peminjaman::class);
    }
}
