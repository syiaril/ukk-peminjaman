@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Peminjam Dashboard</h2>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
            <div class="border border-gray-200 border-l-4 border-l-emerald-400 bg-emerald-50/20 p-6 rounded-md hover:bg-emerald-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-900 mb-1 tracking-wider">Pinjam Alat</h3>
                <p class="text-gray-500 mb-4 text-xs">Lihat katalog dan ajukan peminjaman.</p>
                <a href="{{ route('peminjam.alat.index') }}" class="font-bold text-emerald-600 hover:underline">
                    Katalog &rarr;
                </a>
            </div>

            <div class="border border-gray-200 border-l-4 border-l-emerald-400 bg-emerald-50/20 p-6 rounded-md hover:bg-emerald-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-900 mb-1 tracking-wider">Peminjaman Saya</h3>
                <p class="text-gray-500 mb-4 text-xs">Cek status dan kembalikan alat.</p>
                <a href="{{ route('peminjam.peminjaman.index') }}" class="font-bold text-emerald-600 hover:underline">
                    Riwayat &rarr;
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
