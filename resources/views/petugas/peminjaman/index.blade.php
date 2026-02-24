@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Manajemen Peminjaman</h2>
            <a href="{{ route('petugas.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Kembali ke Beranda
            </a>
        </div>
        
        <div class="mb-6">
            <a href="{{ route('petugas.laporan') }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-black rounded text-xs font-bold hover:bg-black hover:text-white transition-colors">
                Cetak Laporan
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Peminjam</th>
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
                            {{ $p->pengguna->nama }}
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            {{ $p->alat->nama_alat }}
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <div class="text-xs">Pinjam: {{ $p->tanggal_pinjam }}</div>
                            <div class="text-xs font-bold">Wajib Kembali: {{ $p->tanggal_wajib_kembali }}</div>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $p->status === 'disetujui' ? 'bg-green-100 text-green-800' : 
                                   ($p->status === 'sedang_dikembalikan' ? 'bg-purple-100 text-purple-800 border-2 border-purple-300' : 
                                   ($p->status === 'dikembalikan' ? 'bg-blue-100 text-blue-800' : 
                                   ($p->status === 'ditolak' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800'))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                         <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                            @if($p->status === 'diajukan')
                                    <form action="{{ route('petugas.peminjaman.approve', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-800 border border-gray-800 px-3 py-1 rounded-sm hover:bg-gray-800 hover:text-white transition-colors">Setujui</button>
                                    </form>
                                    <form action="{{ route('petugas.peminjaman.reject', $p->id) }}" method="POST">
                                        @csrf
                                        <button class="font-bold text-gray-400 border border-gray-400 px-3 py-1 rounded-sm hover:bg-gray-400 hover:text-white transition-colors">Tolak</button>
                                    </form>
                            @elseif($p->status === 'disetujui')
                                <span class="text-gray-400 italic text-xs">Menunggu pengajuan pengembalian</span>
                            @elseif($p->status === 'sedang_dikembalikan')
                                <form action="{{ route('petugas.peminjaman.return', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Konfirmasi pengembalian dan pulihkan stok?');">
                                    @csrf
                                    <button class="font-bold text-purple-700 border-2 border-purple-500 px-3 py-1 rounded-sm hover:bg-purple-700 hover:text-white transition-colors text-xs">
                                        Konfirmasi Pengembalian
                                    </button>
                                </form>
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
