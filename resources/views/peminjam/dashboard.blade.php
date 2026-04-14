{{-- Dashboard Peminjam --}}
@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Peminjam Dashboard</h2>
        <p>Welcome, {{ Auth::user()->nama }}!</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <a href="{{ route('peminjam.alat.index') }}" class="block border border-gray-200 border-l-4 border-l-emerald-400 bg-emerald-50/20 p-6 rounded-md hover:bg-emerald-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-900 mb-1 tracking-wider">Pinjam Alat</h3>
                <p class="text-gray-500 mb-4 text-xs">Lihat katalog dan ajukan peminjaman.</p>
                <span class="font-bold text-emerald-600">Katalog &rarr;</span>
            </a>

            <a href="{{ route('peminjam.peminjaman.index') }}" class="block border border-gray-200 border-l-4 border-l-emerald-400 bg-emerald-50/20 p-6 rounded-md hover:bg-emerald-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-900 mb-1 tracking-wider">Peminjaman Saya</h3>
                <p class="text-gray-500 mb-4 text-xs">Cek status dan kembalikan alat.</p>
                <span class="font-bold text-emerald-600">Riwayat &rarr;</span>
            </a>
        </div>
    </div>
</div>
@endsection
