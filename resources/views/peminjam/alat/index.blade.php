@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Katalog Alat</h2>
            <a href="{{ route('peminjam.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                &larr; Kembali ke Beranda
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($alat as $a)
            <div class="border rounded-lg p-4 hover:shadow-lg transition">
                @if($a->gambar)
                    <img src="{{ asset('storage/' . $a->gambar) }}" alt="{{ $a->nama_alat }}" class="w-full rounded-md mb-4" style="max-height: 200px; object-fit: contain;">
                @else
                    <div class="w-full bg-gray-200 rounded-md mb-4 flex items-center justify-center text-gray-400" style="height: 150px;">
                        <span class="text-sm">Tidak ada gambar</span>
                    </div>
                @endif
                <h3 class="font-bold text-xl mb-2">{{ $a->nama_alat }}</h3>
                <p class="text-gray-600 mb-2">{{ $a->deskripsi }}</p>
                <p class="text-sm text-gray-500 mb-4">Stok: {{ $a->stok }}</p>
                
                <form action="{{ route('peminjam.peminjaman.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="alat_id" value="{{ $a->id }}">
                    
                    <div class="mb-2">
                        <label class="block text-xs font-bold mb-1">Tanggal Pinjam</label>
                        <input type="date" name="tanggal_pinjam" class="w-full border rounded px-2 py-1" required>
                    </div>
                     <div class="mb-4">
                        <label class="block text-xs font-bold mb-1">Durasi (Hari)</label>
                        <input type="number" name="durasi" min="1" max="14" value="1" class="w-full border rounded px-2 py-1" required>
                    </div>
                    
                    <button type="submit" class="w-full bg-blue-600 text-white rounded py-2 hover:bg-blue-700">
                        Ajukan Peminjaman
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
