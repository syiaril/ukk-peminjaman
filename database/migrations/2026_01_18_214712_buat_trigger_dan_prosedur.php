<?php

/**
 * Migration: Buat Trigger, Fungsi, dan Stored Procedure
 * 
 * Membuat objek database MySQL untuk otomatisasi proses bisnis:
 * 
 * 1. TRIGGER: kurangi_stok_setelah_disetujui
 *    - Otomatis mengurangi stok alat saat peminjaman disetujui
 * 
 * 2. TRIGGER: tambah_stok_setelah_dikembalikan
 *    - Otomatis menambah stok alat saat alat dikembalikan
 * 
 * 3. FUNCTION: hitung_denda(tanggal_wajib, tanggal_kembali)
 *    - Menghitung denda keterlambatan (Rp 5.000 per hari)
 *    - Mengembalikan 0 jika tidak terlambat
 * 
 * 4. PROCEDURE: proses_persetujuan_peminjaman(id_peminjaman, id_petugas)
 *    - Proses persetujuan peminjaman secara transaksional
 *    - Update status + catat log aktivitas dalam satu transaksi
 * 
 * 5. PROCEDURE: proses_pengembalian(id_peminjaman, id_petugas)
 *    - Proses pengembalian alat secara transaksional
 *    - Hitung denda + update status + catat log dalam satu transaksi
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat trigger, fungsi, dan stored procedure.
     */
    public function up(): void
    {
        // =============================================================
        // TRIGGER 1: Kurangi Stok Setelah Peminjaman Disetujui
        // =============================================================
        // Trigger ini berjalan SETELAH UPDATE pada tabel peminjaman.
        // Jika status berubah menjadi 'disetujui' (dan sebelumnya bukan 'disetujui'),
        // maka stok alat yang bersangkutan dikurangi 1.
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
        // TRIGGER 2: Tambah Stok Setelah Alat Dikembalikan
        // =============================================================
        // Trigger ini berjalan SETELAH UPDATE pada tabel peminjaman.
        // Jika status berubah menjadi 'dikembalikan' (dan sebelumnya bukan 'dikembalikan'),
        // maka stok alat yang bersangkutan ditambah 1 (alat kembali tersedia).
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

        // =============================================================
        // FUNCTION: Hitung Denda Keterlambatan
        // =============================================================
        // Fungsi MySQL yang menghitung denda berdasarkan selisih hari.
        // Parameter:
        //   - tanggal_wajib: tanggal batas pengembalian
        //   - tanggal_kembali: tanggal alat dikembalikan
        // Return: nominal denda (DECIMAL)
        //   - Jika terlambat: hari_terlambat × Rp 5.000
        //   - Jika tepat waktu: Rp 0
        DB::unprepared('DROP FUNCTION IF EXISTS hitung_denda');
        DB::unprepared('
            CREATE FUNCTION hitung_denda(tanggal_wajib DATE, tanggal_kembali DATE) RETURNS DECIMAL(10,2)
            DETERMINISTIC
            BEGIN
                DECLARE denda DECIMAL(10,2);
                DECLARE hari_terlambat INT;
                SET denda = 0;
                -- Cek apakah tanggal kembali melewati batas waktu
                IF tanggal_kembali > tanggal_wajib THEN
                    -- Hitung jumlah hari keterlambatan
                    SET hari_terlambat = DATEDIFF(tanggal_kembali, tanggal_wajib);
                    -- Kalikan dengan tarif denda per hari (Rp 5.000)
                    SET denda = hari_terlambat * 5000;
                END IF;
                RETURN denda;
            END
        ');

        // =============================================================
        // PROCEDURE 1: Proses Persetujuan Peminjaman
        // =============================================================
        // Stored Procedure untuk menyetujui peminjaman secara transaksional.
        // Dalam satu transaksi:
        //   1. Update status peminjaman menjadi 'disetujui'
        //      (Trigger otomatis mengurangi stok alat)
        //   2. Catat log aktivitas persetujuan
        // Jika ada error, transaksi otomatis di-rollback.
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_persetujuan_peminjaman');
        DB::unprepared('
            CREATE PROCEDURE proses_persetujuan_peminjaman(IN id_peminjaman INT, IN id_petugas INT)
            BEGIN
                -- Handler untuk error: otomatis rollback jika ada exception
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                -- Update status peminjaman menjadi disetujui
                UPDATE peminjaman SET status = "disetujui" WHERE id = id_peminjaman;
                
                -- Catat log aktivitas persetujuan
                INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                VALUES (id_petugas, "Setujui Peminjaman", CONCAT("Peminjaman ", id_peminjaman, " disetujui"), NOW(), NOW());
                
                COMMIT;
            END
        ');

        // =============================================================
        // PROCEDURE 2: Proses Pengembalian Alat
        // =============================================================
        // Stored Procedure untuk memproses pengembalian alat secara transaksional.
        // Dalam satu transaksi:
        //   1. Ambil tanggal wajib kembali dari database
        //   2. Hitung denda menggunakan fungsi hitung_denda()
        //   3. Update status menjadi 'dikembalikan', isi tanggal_kembali, dan denda
        //      (Trigger otomatis menambah stok alat)
        //   4. Catat log aktivitas pengembalian (jika id_petugas not null)
        // Jika ada error, transaksi otomatis di-rollback.
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_pengembalian');
        DB::unprepared('
            CREATE PROCEDURE proses_pengembalian(IN id_peminjaman INT, IN id_petugas INT)
            BEGIN
                DECLARE tanggal_wajib DATE;
                DECLARE nominal_denda DECIMAL(10,2);
                
                -- Handler untuk error: otomatis rollback jika ada exception
                DECLARE EXIT HANDLER FOR SQLEXCEPTION ROLLBACK;
                START TRANSACTION;
                
                -- Ambil tanggal wajib kembali dari data peminjaman
                SELECT tanggal_wajib_kembali INTO tanggal_wajib FROM peminjaman WHERE id = id_peminjaman;
                
                -- Hitung denda menggunakan fungsi hitung_denda (Rp 5.000/hari terlambat)
                SET nominal_denda = hitung_denda(tanggal_wajib, CURDATE());
                
                -- Update peminjaman: status dikembalikan, tanggal kembali hari ini, isi denda
                UPDATE peminjaman 
                SET status = "dikembalikan", 
                    tanggal_kembali = CURDATE(), 
                    denda = nominal_denda 
                WHERE id = id_peminjaman;
                
                -- Catat log aktivitas pengembalian (jika id_petugas diberikan)
                IF id_petugas IS NOT NULL THEN
                    INSERT INTO log_aktivitas (pengguna_id, aksi, deskripsi, created_at, updated_at)
                    VALUES (id_petugas, "Konfirmasi Pengembalian", CONCAT("Peminjaman ", id_peminjaman, " dikembalikan. Denda: ", nominal_denda), NOW(), NOW());
                END IF;
                
                COMMIT;
            END
        ');
    }

    /**
     * Batalkan migrasi - hapus semua trigger, fungsi, dan stored procedure.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS kurangi_stok_setelah_disetujui');
        DB::unprepared('DROP TRIGGER IF EXISTS tambah_stok_setelah_dikembalikan');
        DB::unprepared('DROP FUNCTION IF EXISTS hitung_denda');
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_persetujuan_peminjaman');
        DB::unprepared('DROP PROCEDURE IF EXISTS proses_pengembalian');
    }
};
