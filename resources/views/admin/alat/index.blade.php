{{-- Daftar Alat --}}
@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-2xl font-bold">Manajemen Alat</h2>
            <div class="flex items-center gap-6">
                <a href="{{ route('admin.kategori.index') }}" class="inline-flex text-blue-400 items-center px-4 py-2 border border-blue-400 rounded text-xs font-bold hover:bg-blue-400 hover:text-white transition-colors">Kategori</a>
                <a href="{{ route('admin.alat.create') }}" class="inline-flex items-center px-4 py-2 border border-black rounded text-xs font-bold hover:bg-black hover:text-white transition-colors">
                    Tambah Alat
                </a>
                <a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-gray-800 flex items-center gap-1 text-sm">
                    &larr; Kembali ke Beranda
                </a>
            </div>
        </div>
        
        <table class="min-w-full leading-normal table-fixed">
            <thead>
                <tr>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider" style="width: 80px;">Foto</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Alat</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Kategori</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Stok</th>
                    <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($alat as $a)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm" style="width: 80px;">
                        @if($a->gambar)
                            <img src="{{ asset('storage/' . $a->gambar) }}" alt="{{ $a->nama_alat }}" class="object-contain rounded" style="width: 64px; height: 64px; max-width: 64px; max-height: 64px;">
                        @else
                            <div class="bg-gray-200 rounded flex items-center justify-center text-gray-400 text-xs" style="width: 64px; height: 64px;">
                                No Image
                            </div>
                        @endif
                    </td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $a->nama_alat }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $a->kategori->nama_kategori }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $a->stok }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="{{ route('admin.alat.edit', $a->id) }}" class="text-blue-600 hover:text-blue-900 mr-4">Edit</a>
                        <form action="{{ route('admin.alat.destroy', $a->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="mt-4">
             {{ $alat->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection
