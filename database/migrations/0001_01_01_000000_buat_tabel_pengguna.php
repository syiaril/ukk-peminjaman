<?php

/**
 * Migration: Buat Tabel Pengguna
 * 
 * Membuat 3 tabel yang berhubungan dengan pengguna dan sesi:
 * 1. pengguna - Data pengguna sistem (admin, petugas, peminjam)
 * 2. password_reset_tokens - Token untuk fitur reset password
 * 3. sessions - Data sesi pengguna yang sedang aktif
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi - membuat tabel pengguna, password_reset_tokens, dan sessions.
     */
    public function up(): void
    {
        // =====================================================
        // Tabel: pengguna
        // Menyimpan data semua pengguna yang bisa login ke sistem
        // =====================================================
        Schema::create('pengguna', function (Blueprint $table) {
            $table->id();                                           // Primary key (auto increment)
            $table->string('nama');                                 // Nama lengkap pengguna
            $table->string('email')->unique();                      // Email unik (digunakan untuk login)
            $table->timestamp('email_verified_at')->nullable();     // Waktu verifikasi email (opsional)
            $table->string('kata_sandi');                           // Password yang sudah di-hash
            $table->enum('peran', ['admin', 'petugas', 'peminjam']) // Peran pengguna
                  ->default('peminjam');                            // Default: peminjam
            $table->rememberToken();                                // Token "remember me" untuk login persisten
            $table->timestamps();                                   // created_at dan updated_at
        });

        // =====================================================
        // Tabel: password_reset_tokens
        // Menyimpan token untuk fitur lupa password / reset password
        // =====================================================
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();                     // Email sebagai primary key
            $table->string('token');                                // Token reset password
            $table->timestamp('created_at')->nullable();            // Waktu token dibuat
        });

        // =====================================================
        // Tabel: sessions
        // Menyimpan data sesi pengguna yang sedang aktif (driver: database)
        // =====================================================
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();                        // Session ID sebagai primary key
            $table->foreignId('user_id')->nullable()->index();      // ID pengguna (nullable untuk guest)
            $table->string('ip_address', 45)->nullable();           // Alamat IP pengguna
            $table->text('user_agent')->nullable();                 // User agent browser
            $table->longText('payload');                            // Data session yang di-serialize
            $table->integer('last_activity')->index();              // Timestamp aktivitas terakhir
        });
    }

    /**
     * Batalkan migrasi - hapus semua tabel yang dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengguna');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
