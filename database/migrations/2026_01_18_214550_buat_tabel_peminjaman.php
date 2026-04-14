<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel peminjaman alat
        // Alur status: diajukan -> disetujui -> sedang_dikembalikan -> dikembalikan
        //              diajukan -> ditolak
        Schema::create('peminjaman', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengguna_id')->constrained('pengguna');
            $table->foreignId('alat_id')->constrained('alat');
            $table->integer('jumlah')->default(1);
            $table->date('tanggal_pinjam');
            $table->date('tanggal_wajib_kembali');
            $table->date('tanggal_kembali')->nullable();
            $table->enum('status', [
                'diajukan',
                'disetujui',
                'sedang_dikembalikan',
                'dikembalikan',
                'ditolak'
            ])->default('diajukan');
            $table->decimal('denda', 10, 2)->default(0); // Rp 5.000/hari keterlambatan
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
