@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Peminjaman Saya</h2>
            <a href="{{ route('peminjam.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Kembali ke Beranda
            </a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Alat</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Pinjam</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Wajib Kembali</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Denda</th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peminjaman as $p)
                    <tr>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $p->alat->nama_alat }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $p->tanggal_pinjam }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $p->tanggal_wajib_kembali }}</td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                            <span class="px-2 inline-flex text-[10px] leading-4 font-bold border border-gray-300 rounded tracking-tighter
                                {{ $p->status === 'disetujui' ? 'text-green-600 border-green-200' : 
                                   ($p->status === 'sedang_dikembalikan' ? 'text-purple-600 border-purple-200' : 
                                   ($p->status === 'dikembalikan' ? 'text-blue-600 border-blue-200' : 
                                   ($p->status === 'ditolak' ? 'text-red-600 border-red-200' : 'text-gray-500 border-gray-200'))) }}">
                                {{ ucfirst($p->status) }}
                            </span>
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                            @if($p->denda > 0)
                                <span class="text-red-600 font-bold">Rp {{ number_format($p->denda) }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-5 py-5 border-b border-gray-200 bg-white text-xs">
                            @if($p->status === 'disetujui')
                                <form action="{{ route('peminjam.peminjaman.return-request', $p->id) }}" method="POST" onsubmit="return confirm('Serahkan alat?');">
                                    @csrf
                                    <button class="border border-gray-800 text-gray-800 font-bold py-1 px-3 rounded-sm hover:bg-gray-800 hover:text-white transition-colors text-[10px]">
                                        Kembalikan
                                    </button>
                                </form>
                            @else
                                <span class="text-gray-300">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
