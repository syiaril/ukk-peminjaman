<?php

/**
 * Migration: Tambah Kolom Jumlah ke Tabel Peminjaman
 * 
 * Menambahkan kolom 'jumlah' ke tabel peminjaman agar peminjam
 * bisa meminjam lebih dari 1 unit alat yang sama dalam satu pengajuan.
 * Default: 1 (agar data lama tetap valid).
 * 
 * Juga memperbarui trigger agar stok dikurangi/ditambah sesuai jumlah,
 * bukan hardcode 1.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi - tambah kolom jumlah dan update trigger.
     */
    public function up(): void
    {
        // Tambah kolom jumlah ke tabel peminjaman
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->integer('jumlah')->default(1)->after('alat_id'); // Jumlah alat yang dipinjam
        });

        // =============================================================
        // Update TRIGGER 1: Kurangi Stok Sesuai Jumlah Peminjaman
        // =============================================================
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_setelah_disetujui');
        DB::unprepared('
            CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
            FOR EACH ROW
            BEGIN
                IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
                    UPDATE alat SET stok = stok - NEW.jumlah WHERE id = NEW.alat_id;
                END IF;
            END
        ');

        // =============================================================
        // Update TRIGGER 2: Tambah Stok Sesuai Jumlah Peminjaman
        // =============================================================
        DB::unprepared('DROP TRIGGER IF EXISTS tambah_stok_setelah_dikembalikan');
        DB::unprepared('
            CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
            FOR EACH ROW
            BEGIN
                IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
                    UPDATE alat SET stok = stok + NEW.jumlah WHERE id = NEW.alat_id;
                END IF;
            END
        ');
    }

    /**
     * Batalkan migrasi - hapus kolom jumlah dan kembalikan trigger ke stok ±1.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn('jumlah');
        });

        // Kembalikan trigger ke versi lama (stok ±1)
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_setelah_disetujui');
        DB::unprepared('
            CREATE TRIGGER kurangi_stok_setelah_disetujui AFTER UPDATE ON peminjaman
            FOR EACH ROW
            BEGIN
                IF NEW.status = "disetujui" AND OLD.status != "disetujui" THEN
                    UPDATE alat SET stok = stok - 1 WHERE id = NEW.alat_id;
                END IF;
            END
        ');

        DB::unprepared('DROP TRIGGER IF EXISTS tambah_stok_setelah_dikembalikan');
        DB::unprepared('
            CREATE TRIGGER tambah_stok_setelah_dikembalikan AFTER UPDATE ON peminjaman
            FOR EACH ROW
            BEGIN
                IF NEW.status = "dikembalikan" AND OLD.status != "dikembalikan" THEN
                    UPDATE alat SET stok = stok + 1 WHERE id = NEW.alat_id;
                END IF;
            END
        ');
    }
};
