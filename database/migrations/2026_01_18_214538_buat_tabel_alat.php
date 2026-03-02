<?php

/**
 * Migration: Buat Tabel Alat
 * 
 * Membuat tabel untuk menyimpan data alat/peralatan sekolah yang bisa dipinjam.
 * Setiap alat terhubung dengan satu kategori melalui foreign key 'kategori_id'.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat tabel alat.
     */
    public function up(): void
    {
        // =====================================================
        // Tabel: alat
        // Menyimpan data alat sekolah yang bisa dipinjam siswa/guru
        // =====================================================
        Schema::create('alat', function (Blueprint $table) {
            $table->id();                                                  // Primary key (auto increment)
            $table->foreignId('kategori_id')->constrained('kategori');      // Foreign key ke tabel kategori (RESTRICT on delete)
            $table->string('nama_alat');                                   // Nama alat (contoh: 'Mikroskop Binokuler')
            $table->text('deskripsi')->nullable();                         // Deskripsi/keterangan alat (opsional)
            $table->integer('stok');                                       // Jumlah stok alat yang tersedia
            $table->string('gambar')->nullable();                          // Path file gambar (contoh: 'alat/mikroskop.jpg')
            $table->timestamps();                                          // created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi - hapus tabel alat.
     */
    public function down(): void
    {
        Schema::dropIfExists('alat');
    }
};
