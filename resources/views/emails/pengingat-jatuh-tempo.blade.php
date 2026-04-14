<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f4f7f6; margin: 0; padding: 20px; color: #374151; }
        .container { background: #ffffff; max-width: 600px; margin: auto; padding: 40px; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); }
        .header { background: {{ $tipe === 'besok' ? '#f59e0b' : '#ef4444' }}; color: white; padding: 30px; border-radius: 8px; text-align: center; margin-bottom: 30px; }
        .header h2 { margin: 0; font-size: 24px; font-weight: 700; }
        .content { line-height: 1.6; }
        .detail { background: #f9fafb; padding: 20px; border-radius: 8px; margin: 25px 0; border: 1px solid #e5e7eb; }
        .detail p { margin: 8px 0; font-size: 15px; }
        .detail strong { color: #111827; width: 120px; display: inline-block; }
        .footer { text-align: center; color: #9ca3af; font-size: 13px; margin-top: 30px; border-top: 1px solid #e5e7eb; padding-top: 20px; }
        .button { display: inline-block; background: #3b82f6; color: white; padding: 12px 24px; border-radius: 6px; text-decoration: none; font-weight: 600; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $tipe === 'besok' ? '⚠️ Pengingat Pengembalian' : '🔴 Jatuh Tempo Hari Ini!' }}</h2>
        </div>

        <div class="content">
            <p>Halo, <strong>{{ $peminjaman->pengguna->nama }}</strong></p>

            @if($tipe === 'besok')
                <p>Ini adalah pengingat bahwa batas waktu pengembalian barang yang Anda pinjam adalah <strong>besok</strong>.</p>
            @else
                <p>Batas waktu pengembalian barang Anda adalah <strong>hari ini</strong>. Segera kembalikan ke petugas untuk menghindari denda keterlambatan.</p>
            @endif

            <div class="detail">
                <p><strong>Nama Barang:</strong> {{ $peminjaman->alat->nama_alat }}</p>
                <p><strong>Tgl Pinjam:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->translatedFormat('d F Y') }}</p>
                <p><strong>Jatuh Tempo:</strong> {{ \Carbon\Carbon::parse($peminjaman->tanggal_wajib_kembali)->translatedFormat('d F Y') }}</p>
                @if($peminjaman->jumlah > 1)
                    <p><strong>Jumlah:</strong> {{ $peminjaman->jumlah }} unit</p>
                @endif
            </div>

            <p>Silakan pastikan barang dalam kondisi baik saat dikembalikan. Terima kasih atas kerjasama Anda!</p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis oleh <strong>Sistem Peminjaman Barang</strong>.</p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
