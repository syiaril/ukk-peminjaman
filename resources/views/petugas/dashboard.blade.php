@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="text-2xl font-bold mb-4">Petugas Dashboard</h2>
        <p>Welcome, {{ Auth::user()->nama }}!</p>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
            <div class="border border-gray-200 border-l-4 border-l-purple-400 bg-purple-50/30 p-4 rounded-md hover:bg-purple-50 transition-colors shadow-sm">
                <h3 class="font-bold text-gray-800">Manajemen Peminjaman</h3>
                <p class="text-gray-500 mb-3 text-xs">Verifikasi alat dan proses transaksi.</p>
                <a href="{{ route('petugas.peminjaman.index') }}" class="font-bold text-purple-600 hover:underline">Masuk &rarr;</a>
            </div>
        </div>
    </div>
</div>
@endsection
