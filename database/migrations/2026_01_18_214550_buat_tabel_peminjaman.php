<?php

/**
 * Migration: Buat Tabel Peminjaman
 * 
 * Membuat tabel untuk mencatat transaksi peminjaman alat.
 * Tabel ini menghubungkan pengguna (peminjam) dengan alat yang dipinjam,
 * mencatat tanggal pinjam, batas kembali, status, dan denda.
 * 
 * Alur status peminjaman:
 * diajukan -> disetujui -> sedang_dikembalikan -> dikembalikan
 *          -> ditolak
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat tabel peminjaman.
     */
    public function up(): void
    {
        // =====================================================
        // Tabel: peminjaman
        // Menyimpan data transaksi peminjaman alat
        // =====================================================
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();                                                           // Primary key (auto increment)
            $table->foreignId('pengguna_id')->constrained('pengguna');               // Foreign key ke tabel pengguna (peminjam)
            $table->foreignId('alat_id')->constrained('alat');                       // Foreign key ke tabel alat
            $table->integer('jumlah')->default(1);                                    // Jumlah alat yang dipinjam (default: 1)
            $table->date('tanggal_pinjam');                                          // Tanggal mulai peminjaman
            $table->date('tanggal_wajib_kembali');                                   // Batas waktu pengembalian
            $table->date('tanggal_kembali')->nullable();                             // Tanggal alat dikembalikan (null jika belum)
            $table->enum('status', [                                                // Status peminjaman:
                'diajukan',              // Menunggu persetujuan petugas
                'disetujui',             // Disetujui, alat sedang dipinjam
                'sedang_dikembalikan',   // Peminjam mengajukan pengembalian
                'dikembalikan',          // Alat sudah dikembalikan
                'ditolak'                // Peminjaman ditolak oleh petugas
            ])->default('diajukan');     // Default: diajukan
            $table->decimal('denda', 10, 2)->default(0);                            // Denda keterlambatan (Rp 5.000/hari)
            $table->timestamps();                                                    // created_at dan updated_at
        });
    }

    /**
     * Batalkan migrasi - hapus tabel peminjaman.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
