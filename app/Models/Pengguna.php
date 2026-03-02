<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Model Pengguna
 * 
 * Merepresentasikan data pengguna/user sistem peminjaman alat.
 * Extends Authenticatable agar bisa digunakan untuk autentikasi Laravel (login/logout).
 * 
 * Tabel: pengguna
 * 
 * Tipe peran (role):
 * - 'admin': Administrator sistem (kelola semua data)
 * - 'petugas': Petugas perpustakaan/lab (kelola peminjaman)
 * - 'peminjam': Siswa/guru yang meminjam alat
 * 
 * Catatan: Kolom password menggunakan nama 'kata_sandi' (Bahasa Indonesia),
 * sehingga method getAuthPassword() di-override untuk mengembalikan kolom yang benar.
 */
class Pengguna extends Authenticatable
{
    use HasFactory, Notifiable;

    // Nama tabel di database
    protected $table = 'pengguna';

    /**
     * Kolom yang boleh diisi secara mass assignment.
     * 
     * - nama: Nama lengkap pengguna
     * - email: Alamat email (digunakan untuk login, bersifat unik)
     * - kata_sandi: Password yang sudah di-hash (menggunakan kolom Bahasa Indonesia)
     * - peran: Peran pengguna (admin/petugas/peminjam)
     */
    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (json/array).
     * 
     * Password dan remember_token tidak akan ditampilkan dalam response API
     * untuk menjaga keamanan data pengguna.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Override method getAuthPassword() dari Authenticatable.
     * 
     * Secara default, Laravel mencari kolom 'password' untuk autentikasi.
     * Karena kita menggunakan nama kolom 'kata_sandi', method ini di-override
     * agar Laravel mengambil password dari kolom yang benar.
     * 
     * @return string Password yang sudah di-hash
     */
    public function getAuthPassword()
    {
        return $this->kata_sandi;
    }

    /**
     * Definisi casting tipe data untuk kolom tertentu.
     * 
     * - email_verified_at: Di-cast ke objek DateTime
     * - password: Otomatis di-hash oleh Laravel (default)
     * - kata_sandi: Otomatis di-hash oleh Laravel
     * 
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',      // Casting bawaan Laravel
            'kata_sandi' => 'hashed',    // Casting untuk kolom kata_sandi (Bahasa Indonesia)
        ];
    }
}
