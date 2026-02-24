@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <p>Welcome, {{ Auth::user()->nama }}!</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <a href="{{ route('admin.pengguna.index') }}" class="block border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-800">Pengguna</h3>
                <p class="text-gray-500 mb-3 text-xs">Kelola akun sistem.</p>
                <span class="font-bold text-blue-600">Masuk &rarr;</span>
            </a>
            <a href="{{ route('admin.alat.index') }}" class="block border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-800">Alat</h3>
                <p class="text-gray-500 mb-3 text-xs">Inventaris & kategori.</p>
                <span class="font-bold text-blue-600">Alat &rarr;</span>
            </a>
            <a href="{{ route('admin.peminjaman.index') }}" class="block border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-800">Peminjaman</h3>
                <p class="text-gray-500 mb-3 text-xs">Persetujuan & pengembalian.</p>
                <span class="font-bold text-blue-600">Lihat Semua &rarr;</span>
            </a>
            <a href="{{ route('admin.log_aktivitas.index') }}" class="block border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline">
                <h3 class="font-bold text-gray-800">Log Aktivitas</h3>
                <p class="text-gray-500 mb-3 text-xs">Riwayat sistem.</p>
                <span class="font-bold text-blue-600">Buka &rarr;</span>
            </a>
        </div>
    </div>
</div>
@endsection
