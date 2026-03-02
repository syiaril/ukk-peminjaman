<?php

/**
 * Migration: Buat Tabel Kategori
 * 
 * Membuat tabel untuk menyimpan kategori/pengelompokan alat sekolah.
 * Contoh kategori: Alat Laboratorium IPA, Alat Olahraga, Alat Musik, dll.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat tabel kategori.
     */
    public function up(): void
    {
        // =====================================================
        // Tabel: kategori
        // Menyimpan daftar kategori alat sekolah
        // =====================================================
        Schema::create('kategori', function (Blueprint $table) {
            $table->id();                           // Primary key (auto increment)
            $table->string('nama_kategori');         // Nama kategori (contoh: 'Alat Laboratorium IPA')
            $table->timestamps();                    // created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi - hapus tabel kategori.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori');
    }
};
