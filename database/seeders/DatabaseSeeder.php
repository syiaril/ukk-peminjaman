<?php

namespace Database\Seeders;

use App\Models\Pengguna;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        \App\Models\Pengguna::create([
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'admin',
        ]);

        // Petugas
        \App\Models\Pengguna::create([
            'nama' => 'Petugas 1',
            'email' => 'petugas@petugas.com',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'petugas',
        ]);

        // Peminjam
        \App\Models\Pengguna::create([
            'nama' => 'Siswa Peminjam',
            'email' => 'siswa@siswa.com',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'peminjam',
        ]);
    }
}
