<?php

namespace App\Console\Commands;

use App\Mail\PengingatJatuhTempo;
use App\Models\Peminjaman;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class KirimNotifikasiJatuhTempo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notif:jatuh-tempo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim email notifikasi H-1 dan saat jatuh tempo pengembalian barang';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $besok = Carbon::tomorrow()->toDateString();
        $hariIni = Carbon::today()->toDateString();

        $this->info("Memulai pengiriman notifikasi jatuh tempo...");
        Log::info("Command notif:jatuh-tempo dijalankan.");

        // 1. Notifikasi H-1 (Jatuh tempo besok)
        $pinjamBesok = Peminjaman::with(['pengguna', 'alat'])
            ->whereDate('tanggal_wajib_kembali', $besok)
            ->whereNull('tanggal_kembali') // Belum dikembalikan
            ->get();

        $this->info("Ditemukan " . $pinjamBesok->count() . " peminjaman jatuh tempo besok.");

        foreach ($pinjamBesok as $p) {
            if ($p->pengguna && $p->pengguna->email) {
                try {
                    Mail::to($p->pengguna->email)->send(new PengingatJatuhTempo($p, 'besok'));
                    $this->info("Email H-1 terkirim ke: {$p->pengguna->email}");
                    Log::info("Email pengingat H-1 dikirim ke {$p->pengguna->email} untuk peminjaman ID: {$p->id}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$p->pengguna->email}: " . $e->getMessage());
                    Log::error("Gagal kirim email H-1 ke {$p->pengguna->email}: " . $e->getMessage());
                }
            }
        }

        // 2. Notifikasi Hari-H (Jatuh tempo hari ini)
        $pinjamHariIni = Peminjaman::with(['pengguna', 'alat'])
            ->whereDate('tanggal_wajib_kembali', $hariIni)
            ->whereNull('tanggal_kembali') // Belum dikembalikan
            ->get();

        $this->info("Ditemukan " . $pinjamHariIni->count() . " peminjaman jatuh tempo hari ini.");

        foreach ($pinjamHariIni as $p) {
            if ($p->pengguna && $p->pengguna->email) {
                try {
                    Mail::to($p->pengguna->email)->send(new PengingatJatuhTempo($p, 'hari_ini'));
                    $this->info("Email Hari-H terkirim ke: {$p->pengguna->email}");
                    Log::info("Email pengingat Hari-H dikirim ke {$p->pengguna->email} untuk peminjaman ID: {$p->id}");
                } catch (\Exception $e) {
                    $this->error("Gagal mengirim email ke {$p->pengguna->email}: " . $e->getMessage());
                    Log::error("Gagal kirim email Hari-H ke {$p->pengguna->email}: " . $e->getMessage());
                }
            }
        }

        $this->info('Proses selesai!');
        return Command::SUCCESS;
    }
}
