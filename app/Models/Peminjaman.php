<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'pengguna_id',
        'alat_id',
        'tanggal_pinjam',
        'tanggal_wajib_kembali',
        'tanggal_kembali',
        'status',
        'denda',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function alat()
    {
        return $this->belongsTo(Alat::class);
    }
}
