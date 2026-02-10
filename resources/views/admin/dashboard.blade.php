@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Admin Dashboard</h2>
        <p>Welcome, {{ Auth::user()->name }}!</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm">
            <div class="border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-800">Pengguna</h3>
                <p class="text-gray-500 mb-3 text-xs">Kelola akun sistem.</p>
                <a href="{{ route('admin.pengguna.index') }}" class="font-bold text-blue-600 hover:underline">Masuk &rarr;</a>
            </div>
            <div class="border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-800">Alat</h3>
                <p class="text-gray-500 mb-3 text-xs">Inventaris & kategori.</p>
                <div class="flex gap-4">
                    <a href="{{ route('admin.alat.index') }}" class="font-bold text-blue-600 hover:underline">Alat</a>
                    <a href="{{ route('admin.kategori.index') }}" class="font-bold text-blue-600 hover:underline">Kategori</a>
                </div>
            </div>
            <div class="border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-800">Peminjaman</h3>
                <p class="text-gray-500 mb-3 text-xs">Persetujuan & pengembalian.</p>
                 <a href="{{ route('admin.peminjaman.index') }}" class="font-bold text-blue-600 hover:underline">Lihat Semua &rarr;</a>
            </div>
            <div class="border border-gray-200 border-l-4 border-l-blue-400 bg-blue-50/30 p-4 rounded-md hover:bg-blue-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-800">Log Aktivitas</h3>
                <p class="text-gray-500 mb-3 text-xs">Riwayat sistem.</p>
                 <a href="{{ route('admin.log_aktivitas.index') }}" class="font-bold text-blue-600 hover:underline">Buka &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
