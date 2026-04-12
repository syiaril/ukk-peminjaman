{{--
    Halaman Manajemen Peminjaman - Admin (admin/peminjaman/index.blade.php)

    Menampilkan tabel semua peminjaman dari semua pengguna.
    Fitur: data peminjam, alat, tanggal, status (dengan badge warna), denda, dan aksi.
    Aksi yang tersedia berdasarkan status:
    - Diajukan: tombol Setujui / Tolak
    - Disetujui: menunggu pengajuan pengembalian dari peminjam
    - Sedang Dikembalikan: tombol Konfirmasi Pengembalian (menghitung denda otomatis)
    - Dikembalikan/Ditolak: tidak ada aksi (selesai)
--}}
@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Manajemen Semua Peminjaman</h2>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Kembali ke Beranda
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pengguna</th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alat</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                         <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $p)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="font-bold">{{ $p->pengguna->nama }}</div>
                            <div class="text-xs text-gray-500">{{ $p->pengguna->email }}</div>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $p->alat->nama_alat }}
                            @if($p->jumlah > 1)
                                <span class="text-xs font-bold text-blue-600">&times;{{ $p->jumlah }}</span>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="text-xs">Pinjam: {{ $p->tanggal_pinjam }}</div>
                            <div class="text-xs font-bold">Wajib Kembali: {{ $p->tanggal_wajib_kembali }}</div>
                            @if($p->tanggal_kembali)
                                <div class="text-xs text-green-600">Dikembalikan: {{ $p->tanggal_kembali }}</div>
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $p->status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($p->status === 'sedang_dikembalikan' ? 'bg-purple-100 text-purple-800 border-2 border-purple-300' : 
                                   ($p->status === 'dikembalikan' ? 'bg-blue-100 text-blue-800' : 
                                   ($p->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                            @if($p->denda > 0)
                                <div class="text-xs text-red-600 mt-1 font-bold">Denda: Rp {{ number_format($p->denda) }}</div>
                            @endif
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                            @if($p->status === 'diajukan')
                                <div class="flex gap-2">
                                    <form action="{{ route('admin.peminjaman.approve', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-800 border border-gray-800 px-3 py-1 rounded-sm hover:bg-gray-800 hover:text-white transition-colors">Setujui</button>
                                    </form>
                                    <form action="{{ route('admin.peminjaman.reject', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-400 border border-gray-400 px-3 py-1 rounded-sm hover:bg-gray-400 hover:text-white transition-colors">Tolak</button>
                                    </form>
                                </div>
                            @elseif($p->status === 'disetujui')
                                <span class="text-gray-400 italic text-xs">Menunggu pengajuan pengembalian</span>
                            @elseif($p->status === 'sedang_dikembalikan')
                                <form action="{{ route('admin.peminjaman.return', $p->id) }}" method="POST" onsubmit="return confirm('Konfirmasi pengembalian dan pulihkan stok?');">
                                    @csrf
                                    <button class="font-bold text-purple-700 border-2 border-purple-500 px-3 py-1 rounded-sm hover:bg-purple-700 hover:text-white transition-colors">
                                        Konfirmasi Pengembalian
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300 italic">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $peminjaman->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection
