<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Alat</title>
    <style>
        body { font-family: sans-serif; padding: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { bg-color: #f2f2f2; }
        .header { text-align: center; margin-bottom: 30px; }
        .footer { margin-top: 50px; text-align: right; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <h1>Laporan Peminjaman Alat</h1>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Tgl Kembali (Est)</th>
                <th>Tgl Kembali (Real)</th>
                <th>Status</th>
                <th>Denda</th>
            </tr>
        </thead>
        <tbody>
            @foreach($peminjaman as $index => $p)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $p->pengguna->nama }}</td>
                <td>{{ $p->alat->nama_alat }}</td>
                <td>{{ $p->tanggal_pinjam }}</td>
                <td>{{ $p->tanggal_wajib_kembali }}</td>
                <td>{{ $p->tanggal_kembali ?? '-' }}</td>
                <td>{{ ucfirst($p->status) }}</td>
                <td>Rp {{ number_format($p->denda) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Petugas: {{ Auth::user()->nama }}</p>
        <br><br>
        <p>( ____________________ )</p>
    </div>

    <button class="no-print" onclick="window.print()" style="margin-top: 20px; padding: 10px 20px; cursor: pointer;">
        Cetak Lagi
    </button>
</body>
</html>
