<?php

/**
 * Migration: Buat Tabel Log Aktivitas
 * 
 * Membuat tabel untuk menyimpan catatan/riwayat aktivitas yang terjadi di sistem.
 * Digunakan sebagai audit trail untuk melacak siapa melakukan apa dan kapan.
 * 
 * Log dicatat otomatis melalui Stored Procedure (contoh: saat persetujuan peminjaman)
 * atau bisa dicatat manual melalui kode PHP.
 * 
 * Menggunakan onDelete('cascade'): jika pengguna dihapus, 
 * semua log aktivitas miliknya juga ikut terhapus.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat tabel log_aktivitas.
     */
    public function up(): void
    {
        // =====================================================
        // Tabel: log_aktivitas
        // Menyimpan riwayat semua aktivitas penting di sistem
        // =====================================================
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();                                                               // Primary key (auto increment)
            $table->foreignId('pengguna_id')->constrained('pengguna')->onDelete('cascade'); // Foreign key, cascade delete
            $table->string('aksi');                                                     // Jenis aksi (contoh: 'Login', 'Tambah Alat')
            $table->text('deskripsi')->nullable();                                      // Detail deskripsi aksi (opsional)
            $table->timestamps();                                                       // created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi - hapus tabel log_aktivitas.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
