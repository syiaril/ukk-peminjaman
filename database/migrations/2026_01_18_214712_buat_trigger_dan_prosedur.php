<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Trigger: kurangi stok saat peminjaman disetujui
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

        // Trigger: tambah stok saat alat dikembalikan
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

        // Fungsi: hitung denda keterlambatan (Rp 5.000/hari)
        DB::unprepared('DROP FUNCTION IF EXISTS hitung_denda');
        DB::unprepared('
            CREATE FUNCTION hitung_denda(tanggal_wajib DATE, tanggal_kembali DATE) RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE denda DECIMAL(10,2);
                DECLARE hari_terlambat INT;
                SET denda = 0;
                IF tanggal_kembali > tanggal_wajib THEN
                    SET hari_terlambat = DATEDIFF(tanggal_kembali, tanggal_wajib);
                    SET denda = hari_terlambat * 5000;
                END IF;
                RETURN denda;
            END
        ');

        // Procedure: setujui peminjaman (update status + catat log)
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_persetujuan_peminjaman');
        DB::unprepared('
            CREATE PROCEDURE proses_persetujuan_peminjaman(IN id_peminjaman INT, IN id_petugas INT)
            BEGIN
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;
                
                INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                VALUES (id_petugas, "Setujui Peminjaman", CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());
                
                COMMIT;
            END
        ');

        // Procedure: proses pengembalian (hitung denda + update status + catat log)
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_pengembalian');
        DB::unprepared('
            CREATE PROCEDURE proses_pengembalian(IN id_peminjaman INT, IN id_petugas INT)
            BEGIN
                DECLARE tanggal_wajib DATE;
                DECLARE nominal_denda DECIMAL(10,2);
                
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                SELECT tanggal_wajib_kembali INTO tanggal_wajib FROM peminjaman WHERE id = id_peminjaman;
                SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());
                
                UPDATE peminjaman 
                SET status = "dikembalikan", 
                    tanggal_kembali = CURDATE(), 
                    denda = nominal_denda 
                WHERE id = id_peminjaman;
                
                IF id_petugas IS NOT NULL THEN
                    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                    VALUES (id_petugas, "Konfirmasi Pengembalian", CONCAT("Peminjaman ", id_peminjaman, " dikembalikan. Denda: ", nominal_denda), NOW(), NOW());
                END IF;
                
                COMMIT;
            END
        ');
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_setelah_disetujui');
        DB::unprepared('DROP TRIGGER IF EXISTS tambah_stok_setelah_dikembalikan');
        DB::unprepared('DROP FUNCTION IF EXISTS hitung_denda');
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_persetujuan_peminjaman');
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_pengembalian');
    }
};
