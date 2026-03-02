<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kategori
 * 
 * Merepresentasikan kategori/pengelompokan alat sekolah.
 * Contoh kategori: Alat Laboratorium IPA, Alat Olahraga, Alat Musik, dll.
 * 
 * Tabel: kategori
 * 
 * Relasi:
 * - hasMany Alat (satu kategori bisa memiliki banyak alat)
 */
class Kategori extends Model
{
    use HasFactory;

    // Nama tabel di database
    protected $table = 'kategori';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     * 
     * - nama_kategori: Nama kategori alat (contoh: 'Alat Laboratorium IPA')
     */
    protected $fillable = ['nama_kategori'];

    /**
     * Relasi: Satu kategori memiliki banyak Alat.
     * 
     * Contoh: Kategori "Alat Olahraga" memiliki Bola Basket, Raket, Net, dll.
     */
    public function alat()
    {
        return $this->hasMany(Alat::class);
    }
}
