<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogAktivitas extends Model
{
    use HasFactory;

    protected $table = 'log_aktivitas';

    protected $fillable = ['pengguna_id', 'aksi', 'deskripsi'];

    // Relasi ke pengguna yang melakukan aksi
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class);
    }
}
