<?php

namespace Database\Seeders;

use App\Models\Pengguna;
use App\Models\Kategori;
use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\LogAktivitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // =====================================================
        // 1. PENGGUNA (Users) - 1 Admin, 3 Petugas, 20 Peminjam
        // =====================================================

        // Admin
        $admin = Pengguna::create([
            'nama' => 'Administrator',
            'email' => 'admin@admin.com',
            'kata_sandi' => Hash::make('password'),
            'peran' => 'admin',
        ]);

        // Petugas (Staff)
        $petugasList = [];
        $namaPetugas = [
            ['nama' => 'Budi Santoso', 'email' => 'budi.petugas@sekolah.com'],
            ['nama' => 'Siti Rahayu', 'email' => 'siti.petugas@sekolah.com'],
            ['nama' => 'Ahmad Fauzi', 'email' => 'ahmad.petugas@sekolah.com'],
        ];
        foreach ($namaPetugas as $p) {
            $petugasList[] = Pengguna::create([
                'nama' => $p['nama'],
                'email' => $p['email'],
                'kata_sandi' => Hash::make('password'),
                'peran' => 'petugas',
            ]);
        }

        // Peminjam (Borrowers) - 20 siswa
        $peminjamList = [];
        $namaSiswa = [
            ['nama' => 'Andi Pratama', 'email' => 'andi.pratama@siswa.com'],
            ['nama' => 'Dewi Lestari', 'email' => 'dewi.lestari@siswa.com'],
            ['nama' => 'Rizky Hidayat', 'email' => 'rizky.hidayat@siswa.com'],
            ['nama' => 'Putri Ayu', 'email' => 'putri.ayu@siswa.com'],
            ['nama' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@siswa.com'],
            ['nama' => 'Maya Sari', 'email' => 'maya.sari@siswa.com'],
            ['nama' => 'Dian Permata', 'email' => 'dian.permata@siswa.com'],
            ['nama' => 'Rendi Kurniawan', 'email' => 'rendi.kurniawan@siswa.com'],
            ['nama' => 'Nadia Fitri', 'email' => 'nadia.fitri@siswa.com'],
            ['nama' => 'Hendra Wijaya', 'email' => 'hendra.wijaya@siswa.com'],
            ['nama' => 'Sinta Maharani', 'email' => 'sinta.maharani@siswa.com'],
            ['nama' => 'Yoga Aditya', 'email' => 'yoga.aditya@siswa.com'],
            ['nama' => 'Laras Wulandari', 'email' => 'laras.wulandari@siswa.com'],
            ['nama' => 'Bayu Setiawan', 'email' => 'bayu.setiawan@siswa.com'],
            ['nama' => 'Rina Oktaviani', 'email' => 'rina.oktaviani@siswa.com'],
            ['nama' => 'Gilang Ramadhan', 'email' => 'gilang.ramadhan@siswa.com'],
            ['nama' => 'Anisa Putri', 'email' => 'anisa.putri@siswa.com'],
            ['nama' => 'Taufik Hidayat', 'email' => 'taufik.hidayat@siswa.com'],
            ['nama' => 'Winda Sari', 'email' => 'winda.sari@siswa.com'],
            ['nama' => 'Irfan Maulana', 'email' => 'irfan.maulana@siswa.com'],
        ];
        foreach ($namaSiswa as $s) {
            $peminjamList[] = Pengguna::create([
                'nama' => $s['nama'],
                'email' => $s['email'],
                'kata_sandi' => Hash::make('password'),
                'peran' => 'peminjam',
            ]);
        }

        // =====================================================
        // 2. KATEGORI (Categories) - 8 kategori alat lab/sekolah
        // =====================================================

        $kategoriData = [
            'Alat Laboratorium IPA',
            'Alat Laboratorium Komputer',
            'Alat Olahraga',
            'Alat Musik',
            'Alat Peraga Matematika',
            'Alat Kebersihan',
            'Peralatan Kantor',
            'Alat Elektronik',
        ];

        $kategoriList = [];
        foreach ($kategoriData as $nama) {
            $kategoriList[] = Kategori::create(['nama_kategori' => $nama]);
        }

        // =====================================================
        // 3. ALAT (Items/Equipment) - 40+ alat
        // =====================================================

        $alatData = [
            // Alat Laboratorium IPA (kategori 0)
            ['kategori_idx' => 0, 'nama_alat' => 'Mikroskop Binokuler', 'deskripsi' => 'Mikroskop binokuler untuk pengamatan sel dan jaringan dengan perbesaran hingga 1000x.', 'stok' => 10],
            ['kategori_idx' => 0, 'nama_alat' => 'Tabung Reaksi', 'deskripsi' => 'Tabung reaksi kaca borosilikat ukuran 15ml untuk eksperimen kimia.', 'stok' => 50],
            ['kategori_idx' => 0, 'nama_alat' => 'Bunsen Burner', 'deskripsi' => 'Pembakar bunsen untuk pemanasan dalam eksperimen kimia.', 'stok' => 15],
            ['kategori_idx' => 0, 'nama_alat' => 'Gelas Ukur 100ml', 'deskripsi' => 'Gelas ukur presisi untuk mengukur volume cairan.', 'stok' => 25],
            ['kategori_idx' => 0, 'nama_alat' => 'Neraca Analitik', 'deskripsi' => 'Neraca digital dengan ketelitian 0.01 gram untuk mengukur massa.', 'stok' => 5],
            ['kategori_idx' => 0, 'nama_alat' => 'Pipet Tetes', 'deskripsi' => 'Pipet tetes kaca untuk memindahkan cairan dalam jumlah kecil.', 'stok' => 40],

            // Alat Laboratorium Komputer (kategori 1)
            ['kategori_idx' => 1, 'nama_alat' => 'Laptop ASUS VivoBook', 'deskripsi' => 'Laptop ASUS VivoBook 14 inch, Intel Core i5, RAM 8GB untuk kegiatan belajar.', 'stok' => 20],
            ['kategori_idx' => 1, 'nama_alat' => 'Mouse Wireless Logitech', 'deskripsi' => 'Mouse wireless Logitech M331 silent click untuk lab komputer.', 'stok' => 30],
            ['kategori_idx' => 1, 'nama_alat' => 'Keyboard Mechanical', 'deskripsi' => 'Keyboard mechanical RGB untuk kegiatan programming dan typing.', 'stok' => 15],
            ['kategori_idx' => 1, 'nama_alat' => 'USB Flash Drive 32GB', 'deskripsi' => 'Flash drive USB 3.0 kapasitas 32GB untuk penyimpanan data.', 'stok' => 40],
            ['kategori_idx' => 1, 'nama_alat' => 'Kabel LAN Cat6', 'deskripsi' => 'Kabel LAN kategori 6 untuk koneksi jaringan komputer.', 'stok' => 20],

            // Alat Olahraga (kategori 2)
            ['kategori_idx' => 2, 'nama_alat' => 'Bola Basket Molten', 'deskripsi' => 'Bola basket Molten GG7X official size 7 untuk pertandingan resmi.', 'stok' => 8],
            ['kategori_idx' => 2, 'nama_alat' => 'Bola Voli Mikasa', 'deskripsi' => 'Bola voli Mikasa MVA200 untuk latihan dan pertandingan.', 'stok' => 10],
            ['kategori_idx' => 2, 'nama_alat' => 'Raket Badminton Yonex', 'deskripsi' => 'Raket badminton Yonex Astrox untuk latihan club badminton.', 'stok' => 12],
            ['kategori_idx' => 2, 'nama_alat' => 'Net Badminton', 'deskripsi' => 'Net badminton standar pertandingan dengan tiang penyangga.', 'stok' => 3],
            ['kategori_idx' => 2, 'nama_alat' => 'Matras Senam', 'deskripsi' => 'Matras senam tebal 5cm ukuran 200x100cm untuk kegiatan senam.', 'stok' => 15],
            ['kategori_idx' => 2, 'nama_alat' => 'Stopwatch Digital', 'deskripsi' => 'Stopwatch digital untuk mengukur waktu dalam kegiatan olahraga.', 'stok' => 10],

            // Alat Musik (kategori 3)
            ['kategori_idx' => 3, 'nama_alat' => 'Gitar Akustik Yamaha', 'deskripsi' => 'Gitar akustik Yamaha C315 untuk latihan musik dan pertunjukan.', 'stok' => 8],
            ['kategori_idx' => 3, 'nama_alat' => 'Keyboard Yamaha PSR', 'deskripsi' => 'Keyboard Yamaha PSR-E373 61 keys untuk pelajaran musik.', 'stok' => 4],
            ['kategori_idx' => 3, 'nama_alat' => 'Drum Pad Alesis', 'deskripsi' => 'Drum pad electronic Alesis untuk latihan drum tanpa suara keras.', 'stok' => 3],
            ['kategori_idx' => 3, 'nama_alat' => 'Biola 4/4', 'deskripsi' => 'Biola ukuran penuh 4/4 untuk pelajaran dan latihan orkestra.', 'stok' => 6],
            ['kategori_idx' => 3, 'nama_alat' => 'Seruling Bambu', 'deskripsi' => 'Seruling bambu tradisional untuk pelajaran seni budaya.', 'stok' => 20],

            // Alat Peraga Matematika (kategori 4)
            ['kategori_idx' => 4, 'nama_alat' => 'Jangka Sorong Digital', 'deskripsi' => 'Jangka sorong digital dengan ketelitian 0.01mm untuk praktikum fisika.', 'stok' => 15],
            ['kategori_idx' => 4, 'nama_alat' => 'Model Bangun Ruang', 'deskripsi' => 'Set model bangun ruang transparan (kubus, balok, kerucut, limas, bola).', 'stok' => 10],
            ['kategori_idx' => 4, 'nama_alat' => 'Penggaris Segitiga Set', 'deskripsi' => 'Set penggaris segitiga 30cm untuk menggambar geometri di papan tulis.', 'stok' => 20],
            ['kategori_idx' => 4, 'nama_alat' => 'Kalkulator Scientific', 'deskripsi' => 'Kalkulator scientific Casio FX-991ID Plus untuk perhitungan matematika.', 'stok' => 25],

            // Alat Kebersihan (kategori 5)
            ['kategori_idx' => 5, 'nama_alat' => 'Vacuum Cleaner Portable', 'deskripsi' => 'Vacuum cleaner portable untuk membersihkan ruang kelas dan lab.', 'stok' => 5],
            ['kategori_idx' => 5, 'nama_alat' => 'Mesin Pel Lantai', 'deskripsi' => 'Mesin pel lantai otomatis untuk membersihkan lantai gedung sekolah.', 'stok' => 3],
            ['kategori_idx' => 5, 'nama_alat' => 'Sprayer Disinfektan', 'deskripsi' => 'Sprayer elektrik untuk penyemprotan disinfektan di lingkungan sekolah.', 'stok' => 8],

            // Peralatan Kantor (kategori 6)
            ['kategori_idx' => 6, 'nama_alat' => 'Proyektor Epson', 'deskripsi' => 'Proyektor Epson EB-X51 3800 lumens untuk presentasi di kelas.', 'stok' => 10],
            ['kategori_idx' => 6, 'nama_alat' => 'Printer HP LaserJet', 'deskripsi' => 'Printer laser HP LaserJet Pro untuk mencetak dokumen sekolah.', 'stok' => 5],
            ['kategori_idx' => 6, 'nama_alat' => 'Scanner Epson', 'deskripsi' => 'Scanner Epson Perfection V39 untuk scan dokumen dan foto.', 'stok' => 4],
            ['kategori_idx' => 6, 'nama_alat' => 'Whiteboard Portable', 'deskripsi' => 'Whiteboard portable ukuran 120x80cm dengan tripod stand.', 'stok' => 8],
            ['kategori_idx' => 6, 'nama_alat' => 'Laminator A3', 'deskripsi' => 'Mesin laminator ukuran A3 untuk melapisi dokumen penting.', 'stok' => 3],

            // Alat Elektronik (kategori 7)
            ['kategori_idx' => 7, 'nama_alat' => 'Multimeter Digital', 'deskripsi' => 'Multimeter digital untuk mengukur tegangan, arus, dan resistansi.', 'stok' => 12],
            ['kategori_idx' => 7, 'nama_alat' => 'Solder Station', 'deskripsi' => 'Solder station dengan pengaturan suhu untuk praktikum elektronika.', 'stok' => 8],
            ['kategori_idx' => 7, 'nama_alat' => 'Breadboard Kit', 'deskripsi' => 'Breadboard kit lengkap dengan kabel jumper untuk rangkaian elektronik.', 'stok' => 20],
            ['kategori_idx' => 7, 'nama_alat' => 'Arduino Uno R3', 'deskripsi' => 'Board Arduino Uno R3 untuk belajar microcontroller dan IoT.', 'stok' => 15],
            ['kategori_idx' => 7, 'nama_alat' => 'Oscilloscope Digital', 'deskripsi' => 'Oscilloscope digital 2 channel untuk menganalisis sinyal elektronik.', 'stok' => 4],
            ['kategori_idx' => 7, 'nama_alat' => 'Power Supply Variable', 'deskripsi' => 'Power supply variable 0-30V 5A untuk eksperimen elektronika.', 'stok' => 6],
        ];

        $alatList = [];
        foreach ($alatData as $alat) {
            $alatList[] = Alat::create([
                'kategori_id' => $kategoriList[$alat['kategori_idx']]->id,
                'nama_alat' => $alat['nama_alat'],
                'deskripsi' => $alat['deskripsi'],
                'stok' => $alat['stok'],
            ]);
        }

        // =====================================================
        // 4. PEMINJAMAN (Borrowings) - 50 peminjaman dengan berbagai status
        // =====================================================
        // Disable triggers sementara agar stok tidak berubah saat seeding
        // Kita akan handle stok manual

        $peminjamanData = [];
        $now = Carbon::now();

        // --- Status: dikembalikan (sudah selesai, 15 data) ---
        for ($i = 0; $i < 15; $i++) {
            $peminjam = $peminjamList[array_rand($peminjamList)];
            $alat = $alatList[array_rand($alatList)];
            $tanggalPinjam = $now->copy()->subDays(rand(30, 90));
            $tanggalWajibKembali = $tanggalPinjam->copy()->addDays(rand(3, 14));
            $isLate = rand(0, 3) === 0; // 25% chance telat
            $tanggalKembali = $isLate
                ? $tanggalWajibKembali->copy()->addDays(rand(1, 10))
                : $tanggalWajibKembali->copy()->subDays(rand(0, 3));
            
            $denda = 0;
            if ($tanggalKembali->gt($tanggalWajibKembali)) {
                $denda = $tanggalKembali->diffInDays($tanggalWajibKembali) * 5000;
            }

            $peminjamanData[] = [
                'pengguna_id' => $peminjam->id,
                'alat_id' => $alat->id,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_wajib_kembali' => $tanggalWajibKembali->format('Y-m-d'),
                'tanggal_kembali' => $tanggalKembali->format('Y-m-d'),
                'status' => 'dikembalikan',
                'denda' => $denda,
                'created_at' => $tanggalPinjam,
                'updated_at' => $tanggalKembali,
            ];
        }

        // --- Status: disetujui (sedang dipinjam, 12 data) ---
        for ($i = 0; $i < 12; $i++) {
            $peminjam = $peminjamList[array_rand($peminjamList)];
            $alat = $alatList[array_rand($alatList)];
            $tanggalPinjam = $now->copy()->subDays(rand(1, 14));
            $tanggalWajibKembali = $tanggalPinjam->copy()->addDays(rand(7, 21));

            $peminjamanData[] = [
                'pengguna_id' => $peminjam->id,
                'alat_id' => $alat->id,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_wajib_kembali' => $tanggalWajibKembali->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'disetujui',
                'denda' => 0,
                'created_at' => $tanggalPinjam,
                'updated_at' => $tanggalPinjam,
            ];
        }

        // --- Status: diajukan (menunggu persetujuan, 10 data) ---
        for ($i = 0; $i < 10; $i++) {
            $peminjam = $peminjamList[array_rand($peminjamList)];
            $alat = $alatList[array_rand($alatList)];
            $tanggalPinjam = $now->copy()->subDays(rand(0, 5));
            $tanggalWajibKembali = $tanggalPinjam->copy()->addDays(rand(5, 14));

            $peminjamanData[] = [
                'pengguna_id' => $peminjam->id,
                'alat_id' => $alat->id,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_wajib_kembali' => $tanggalWajibKembali->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'diajukan',
                'denda' => 0,
                'created_at' => $tanggalPinjam,
                'updated_at' => $tanggalPinjam,
            ];
        }

        // --- Status: sedang_dikembalikan (proses pengembalian, 5 data) ---
        for ($i = 0; $i < 5; $i++) {
            $peminjam = $peminjamList[array_rand($peminjamList)];
            $alat = $alatList[array_rand($alatList)];
            $tanggalPinjam = $now->copy()->subDays(rand(7, 20));
            $tanggalWajibKembali = $tanggalPinjam->copy()->addDays(rand(5, 14));

            $peminjamanData[] = [
                'pengguna_id' => $peminjam->id,
                'alat_id' => $alat->id,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_wajib_kembali' => $tanggalWajibKembali->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'sedang_dikembalikan',
                'denda' => 0,
                'created_at' => $tanggalPinjam,
                'updated_at' => $now->copy()->subDays(rand(0, 2)),
            ];
        }

        // --- Status: ditolak (8 data) ---
        for ($i = 0; $i < 8; $i++) {
            $peminjam = $peminjamList[array_rand($peminjamList)];
            $alat = $alatList[array_rand($alatList)];
            $tanggalPinjam = $now->copy()->subDays(rand(10, 60));
            $tanggalWajibKembali = $tanggalPinjam->copy()->addDays(rand(5, 14));

            $peminjamanData[] = [
                'pengguna_id' => $peminjam->id,
                'alat_id' => $alat->id,
                'tanggal_pinjam' => $tanggalPinjam->format('Y-m-d'),
                'tanggal_wajib_kembali' => $tanggalWajibKembali->format('Y-m-d'),
                'tanggal_kembali' => null,
                'status' => 'ditolak',
                'denda' => 0,
                'created_at' => $tanggalPinjam,
                'updated_at' => $tanggalPinjam->copy()->addDay(),
            ];
        }

        // Insert semua peminjaman langsung tanpa trigger
        foreach ($peminjamanData as $data) {
            DB::table('peminjaman')->insert($data);
        }

        // Kurangi stok untuk peminjaman yang disetujui / sedang_dikembalikan
        $peminjamanAktif = DB::table('peminjaman')
            ->whereIn('status', ['disetujui', 'sedang_dikembalikan'])
            ->get();

        foreach ($peminjamanAktif as $p) {
            DB::table('alat')
                ->where('id', $p->alat_id)
                ->decrement('stok');
        }

        // =====================================================
        // 5. LOG AKTIVITAS (Activity Logs) - 30+ log
        // =====================================================

        $logData = [
            // Login activities
            ['pengguna_id' => $admin->id, 'aksi' => 'Login', 'deskripsi' => 'Administrator berhasil login ke sistem.', 'days_ago' => 1],
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Login', 'deskripsi' => 'Petugas Budi Santoso berhasil login ke sistem.', 'days_ago' => 1],
            ['pengguna_id' => $petugasList[1]->id, 'aksi' => 'Login', 'deskripsi' => 'Petugas Siti Rahayu berhasil login ke sistem.', 'days_ago' => 2],
            ['pengguna_id' => $petugasList[2]->id, 'aksi' => 'Login', 'deskripsi' => 'Petugas Ahmad Fauzi berhasil login ke sistem.', 'days_ago' => 3],
            
            // Alat management
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Alat', 'deskripsi' => 'Menambahkan alat baru: Mikroskop Binokuler.', 'days_ago' => 30],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Alat', 'deskripsi' => 'Menambahkan alat baru: Laptop ASUS VivoBook.', 'days_ago' => 30],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Alat', 'deskripsi' => 'Menambahkan alat baru: Bola Basket Molten.', 'days_ago' => 28],
            ['pengguna_id' => $admin->id, 'aksi' => 'Edit Alat', 'deskripsi' => 'Mengubah stok Tabung Reaksi menjadi 50.', 'days_ago' => 20],
            ['pengguna_id' => $admin->id, 'aksi' => 'Edit Alat', 'deskripsi' => 'Mengubah deskripsi Proyektor Epson.', 'days_ago' => 15],
            
            // Kategori management
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Kategori', 'deskripsi' => 'Menambahkan kategori baru: Alat Laboratorium IPA.', 'days_ago' => 35],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Kategori', 'deskripsi' => 'Menambahkan kategori baru: Alat Olahraga.', 'days_ago' => 35],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Kategori', 'deskripsi' => 'Menambahkan kategori baru: Alat Elektronik.', 'days_ago' => 34],
            
            // Peminjaman approvals
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #1 disetujui oleh Budi Santoso.', 'days_ago' => 25],
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #2 disetujui oleh Budi Santoso.', 'days_ago' => 22],
            ['pengguna_id' => $petugasList[1]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #3 disetujui oleh Siti Rahayu.', 'days_ago' => 20],
            ['pengguna_id' => $petugasList[1]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #5 disetujui oleh Siti Rahayu.', 'days_ago' => 18],
            ['pengguna_id' => $petugasList[2]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #7 disetujui oleh Ahmad Fauzi.', 'days_ago' => 15],
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Setujui Peminjaman', 'deskripsi' => 'Peminjaman #10 disetujui oleh Budi Santoso.', 'days_ago' => 10],
            
            // Rejections
            ['pengguna_id' => $petugasList[1]->id, 'aksi' => 'Tolak Peminjaman', 'deskripsi' => 'Peminjaman ditolak: Stok alat tidak tersedia.', 'days_ago' => 19],
            ['pengguna_id' => $petugasList[2]->id, 'aksi' => 'Tolak Peminjaman', 'deskripsi' => 'Peminjaman ditolak: Jadwal bentrok dengan peminjam lain.', 'days_ago' => 14],
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Tolak Peminjaman', 'deskripsi' => 'Peminjaman ditolak: Data peminjam tidak lengkap.', 'days_ago' => 8],
            
            // Returns
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Konfirmasi Pengembalian', 'deskripsi' => 'Peminjaman #1 dikembalikan. Denda: 0.', 'days_ago' => 12],
            ['pengguna_id' => $petugasList[1]->id, 'aksi' => 'Konfirmasi Pengembalian', 'deskripsi' => 'Peminjaman #2 dikembalikan. Denda: 15000.', 'days_ago' => 10],
            ['pengguna_id' => $petugasList[2]->id, 'aksi' => 'Konfirmasi Pengembalian', 'deskripsi' => 'Peminjaman #3 dikembalikan. Denda: 0.', 'days_ago' => 8],
            ['pengguna_id' => $petugasList[0]->id, 'aksi' => 'Konfirmasi Pengembalian', 'deskripsi' => 'Peminjaman #5 dikembalikan. Denda: 25000.', 'days_ago' => 5],
            
            // User management
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Pengguna', 'deskripsi' => 'Menambahkan akun petugas baru: Budi Santoso.', 'days_ago' => 40],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Pengguna', 'deskripsi' => 'Menambahkan akun petugas baru: Siti Rahayu.', 'days_ago' => 40],
            ['pengguna_id' => $admin->id, 'aksi' => 'Tambah Pengguna', 'deskripsi' => 'Menambahkan akun petugas baru: Ahmad Fauzi.', 'days_ago' => 39],
            ['pengguna_id' => $admin->id, 'aksi' => 'Edit Pengguna', 'deskripsi' => 'Mengubah data pengguna: Andi Pratama.', 'days_ago' => 10],
            
            // Borrower activities
            ['pengguna_id' => $peminjamList[0]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Andi Pratama mengajukan peminjaman Laptop ASUS.', 'days_ago' => 5],
            ['pengguna_id' => $peminjamList[1]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Dewi Lestari mengajukan peminjaman Gitar Akustik.', 'days_ago' => 4],
            ['pengguna_id' => $peminjamList[2]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Rizky Hidayat mengajukan peminjaman Arduino Uno.', 'days_ago' => 3],
            ['pengguna_id' => $peminjamList[3]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Putri Ayu mengajukan peminjaman Mikroskop.', 'days_ago' => 2],
            ['pengguna_id' => $peminjamList[4]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Fajar Nugroho mengajukan peminjaman Bola Basket.', 'days_ago' => 1],
            ['pengguna_id' => $peminjamList[5]->id, 'aksi' => 'Ajukan Peminjaman', 'deskripsi' => 'Maya Sari mengajukan peminjaman Proyektor Epson.', 'days_ago' => 0],
        ];

        foreach ($logData as $log) {
            LogAktivitas::create([
                'pengguna_id' => $log['pengguna_id'],
                'aksi' => $log['aksi'],
                'deskripsi' => $log['deskripsi'],
                'created_at' => $now->copy()->subDays($log['days_ago']),
                'updated_at' => $now->copy()->subDays($log['days_ago']),
            ]);
        }

        $this->command->info('');
        $this->command->info('============================================');
        $this->command->info('  DATA DUMMY BERHASIL DIBUAT!');
        $this->command->info('============================================');
        $this->command->info('  Pengguna  : 24 (1 Admin, 3 Petugas, 20 Peminjam)');
        $this->command->info('  Kategori  : 8 kategori');
        $this->command->info('  Alat      : ' . count($alatList) . ' alat');
        $this->command->info('  Peminjaman: 50 (15 dikembalikan, 12 disetujui, 10 diajukan, 5 sedang_dikembalikan, 8 ditolak)');
        $this->command->info('  Log       : ' . count($logData) . ' log aktivitas');
        $this->command->info('============================================');
        $this->command->info('');
        $this->command->info('  LOGIN CREDENTIALS:');
        $this->command->info('  Admin   : admin@admin.com / password');
        $this->command->info('  Petugas : budi.petugas@sekolah.com / password');
        $this->command->info('  Peminjam: andi.pratama@siswa.com / password');
        $this->command->info('============================================');
    }
}
