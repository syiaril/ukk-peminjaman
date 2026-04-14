{{-- Dashboard Admin dengan Grafik Analitik --}}
@extends('layouts.app')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard</h2>
            <p class="text-sm text-gray-500 mt-1">Selamat datang, <span class="font-semibold text-gray-700">{{ Auth::user()->nama }}</span></p>
        </div>
        <span class="text-xs text-gray-400 font-mono">{{ now()->translatedFormat('l, d F Y') }}</span>
    </div>

    {{-- Akses Cepat (Tombol Sebelumnya) --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.pengguna.index') }}" class="block border border-gray-100 border-l-4 border-l-blue-500 bg-white p-4 rounded-xl hover:bg-blue-50/50 hover:border-l-blue-600 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline group relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity translate-x-4 -translate-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Pengguna</h3>
            <p class="text-gray-500 mb-3 text-xs">Kelola akun dan hak akses sistem.</p>
            <span class="font-bold text-blue-600 text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">Kelola <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
        </a>
        <a href="{{ route('admin.alat.index') }}" class="block border border-gray-100 border-l-4 border-l-emerald-500 bg-white p-4 rounded-xl hover:bg-emerald-50/50 hover:border-l-emerald-600 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline group relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity translate-x-4 -translate-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M22.7 19l-9.1-9.1c.9-2.3.4-5-1.5-6.9-2-2-5-2.4-7.4-1.3L9 6 6 9 1.6 4.7C.5 7.1.9 10.1 2.9 12.1c1.9 1.9 4.6 2.4 6.9 1.5l9.1 9.1c.4.4 1 .4 1.4 0l2.3-2.3c.5-.4.5-1.1.1-1.4z"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Daftar Alat</h3>
            <p class="text-gray-500 mb-3 text-xs">Tambah, edit, hapus inventaris & kategori.</p>
            <span class="font-bold text-emerald-600 text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">Kelola <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
        </a>
        <a href="{{ route('admin.peminjaman.index') }}" class="block border border-gray-100 border-l-4 border-l-violet-500 bg-white p-4 rounded-xl hover:bg-violet-50/50 hover:border-l-violet-600 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline group relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity translate-x-4 -translate-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-violet-600" fill="currentColor" viewBox="0 0 24 24"><path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm2 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Peminjaman</h3>
            <p class="text-gray-500 mb-3 text-xs">Persetujuan & pengembalian alat.</p>
            <span class="font-bold text-violet-600 text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">Lihat Semua <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
        </a>
        <a href="{{ route('admin.log_aktivitas.index') }}" class="block border border-gray-100 border-l-4 border-l-rose-500 bg-white p-4 rounded-xl hover:bg-rose-50/50 hover:border-l-rose-600 hover:shadow-md transition-all shadow-sm cursor-pointer no-underline group relative overflow-hidden">
            <div class="absolute right-0 top-0 opacity-5 group-hover:opacity-10 transition-opacity translate-x-4 -translate-y-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-rose-600" fill="currentColor" viewBox="0 0 24 24"><path d="M11.99 2C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/><path d="M12.5 7H11v6l5.25 3.15.75-1.23-4.5-2.67z"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 text-base mb-1">Log Aktivitas</h3>
            <p class="text-gray-500 mb-3 text-xs">Pantau riwayat aksi di dalam sistem.</p>
            <span class="font-bold text-rose-600 text-sm inline-flex items-center gap-1 group-hover:gap-2 transition-all">Buka <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg></span>
        </a>
    </div>

    {{-- Kartu Statistik --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-blue-500 to-blue-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">{{ number_format($totalPengguna) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Pengguna</p>
        </div>
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-emerald-500 to-emerald-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">{{ number_format($totalAlat) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Alat</p>
        </div>
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-violet-500 to-violet-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">{{ number_format($totalPeminjaman) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Total Peminjaman</p>
        </div>
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-amber-500 to-amber-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">{{ number_format($totalKategori) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Kategori</p>
        </div>
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-rose-500 to-rose-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">{{ number_format($peminjamanAktif) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Aktif</p>
        </div>
        <div class="rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-0.5 bg-gradient-to-br from-orange-500 to-orange-600">
            <div class="absolute -right-4 -bottom-4 w-20 h-20 rounded-full bg-white opacity-10"></div>
            <div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm relative z-10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-2xl font-extrabold tracking-tight leading-none relative z-10">Rp {{ number_format($totalDenda) }}</p>
            <p class="text-xs font-medium opacity-80 mt-1 uppercase tracking-wider relative z-10">Total Denda</p>
        </div>
    </div>

    {{-- Grafik - Baris 1: Tren + Status --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Tren Peminjaman Bulanan (line chart) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300 hover:shadow-md lg:col-span-2">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                Tren Peminjaman (6 Bulan)
            </h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartTren"></canvas>
            </div>
        </div>

        {{-- Distribusi Status (doughnut chart) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300 hover:shadow-md">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                Status Peminjaman
            </h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>
    </div>

    {{-- Grafik - Baris 2: Alat Populer + Kategori --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Alat Paling Sering Dipinjam (horizontal bar chart) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300 hover:shadow-md">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                Top 5 Alat Terpopuler
            </h3>
            <div class="relative" style="height: 260px;">
                <canvas id="chartAlatPopuler"></canvas>
            </div>
        </div>

        {{-- Distribusi Kategori (doughnut chart) --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300 hover:shadow-md">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                Alat per Kategori
            </h3>
            <div class="relative" style="height: 260px;">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    {{-- Tabel Peminjaman Terbaru --}}
    <div class="grid grid-cols-1 gap-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300 hover:shadow-md">
            <h3 class="text-sm font-bold text-gray-700 mb-4 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-rose-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Peminjaman Terbaru
            </h3>
            <div class="overflow-x-auto -mx-5 -mb-5">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-xs text-gray-400 uppercase tracking-wider border-b border-gray-100">
                            <th class="px-5 py-3 text-left font-semibold">Peminjam</th>
                            <th class="px-5 py-3 text-left font-semibold">Alat</th>
                            <th class="px-5 py-3 text-center font-semibold">Jml</th>
                            <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                            <th class="px-5 py-3 text-center font-semibold">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @forelse ($peminjamanTerbaru as $p)
                        <tr class="hover:bg-gray-50/50 transition-colors">
                            <td class="px-5 py-3 font-medium text-gray-700">{{ $p->pengguna->nama ?? '-' }}</td>
                            <td class="px-5 py-3 text-gray-600">{{ $p->alat->nama_alat ?? '-' }}</td>
                            <td class="px-5 py-3 text-center text-gray-600">{{ $p->jumlah }}</td>
                            <td class="px-5 py-3 text-gray-500 text-xs font-mono">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                            <td class="px-5 py-3 text-center">
                                @php
                                    $colors = [
                                        'diajukan' => 'bg-yellow-100 text-yellow-700',
                                        'disetujui' => 'bg-blue-100 text-blue-700',
                                        'sedang_dikembalikan' => 'bg-indigo-100 text-indigo-700',
                                        'dikembalikan' => 'bg-emerald-100 text-emerald-700',
                                        'ditolak' => 'bg-red-100 text-red-700',
                                    ];
                                @endphp
                                <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $colors[$p->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(str_replace('_', ' ', $p->status)) }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada data peminjaman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    /* Kartu statistik */
    .stat-card {
        @apply rounded-xl p-4 text-white shadow-lg relative overflow-hidden transition-all duration-300;
    }
    .stat-card:hover {
        @apply shadow-xl -translate-y-0.5;
    }
    .stat-card::after {
        content: '';
        @apply absolute -right-4 -bottom-4 w-20 h-20 rounded-full opacity-10 bg-white;
    }
    .stat-icon {
        @apply w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center mb-3 backdrop-blur-sm;
    }
    .stat-value {
        @apply text-2xl font-extrabold tracking-tight leading-none;
    }
    .stat-label {
        @apply text-xs font-medium opacity-80 mt-1 uppercase tracking-wider;
    }

    /* Kartu grafik */
    .chart-card {
        @apply bg-white rounded-xl shadow-sm border border-gray-100 p-5 transition-shadow duration-300;
    }
    .chart-card:hover {
        @apply shadow-md;
    }
    .chart-title {
        @apply text-sm font-bold text-gray-700 mb-4 flex items-center gap-2;
    }
    .chart-wrapper {
        @apply relative;
    }

    /* Navigasi cepat */
    .quick-nav-card {
        @apply flex items-center gap-3 bg-white rounded-xl p-4 shadow-sm border border-gray-100 no-underline transition-all duration-300;
    }
    .quick-nav-card:hover {
        @apply shadow-md -translate-y-0.5 border-gray-200;
    }
    .quick-nav-icon {
        @apply w-10 h-10 rounded-lg flex items-center justify-center flex-shrink-0 transition-all duration-300;
    }
</style>
@endsection

@section('scripts')
{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Konfigurasi default Chart.js
    Chart.defaults.font.family = "'Inter', 'Segoe UI', sans-serif";
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.labels.usePointStyle = true;
    Chart.defaults.plugins.legend.labels.pointStyleWidth = 8;

    // Palet warna
    const colors = {
        blue: 'rgba(59, 130, 246, 1)',
        blueLight: 'rgba(59, 130, 246, 0.1)',
        emerald: 'rgba(16, 185, 129, 1)',
        violet: 'rgba(139, 92, 246, 1)',
        amber: 'rgba(245, 158, 11, 1)',
        rose: 'rgba(244, 63, 94, 1)',
        orange: 'rgba(249, 115, 22, 1)',
        indigo: 'rgba(99, 102, 241, 1)',
        slate: 'rgba(100, 116, 139, 1)',
    };

    const palette = [colors.blue, colors.emerald, colors.violet, colors.amber, colors.rose, colors.orange, colors.indigo, colors.slate];
    const paletteLight = palette.map(c => c.replace(', 1)', ', 0.15)'));

    // 1. Tren Peminjaman Bulanan (Line Chart)
    const trenData = @json($trenBulanan);
    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: trenData.map(d => d.bulan),
            datasets: [{
                label: 'Peminjaman',
                data: trenData.map(d => d.jumlah),
                borderColor: colors.blue,
                backgroundColor: colors.blueLight,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#fff',
                pointBorderColor: colors.blue,
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { weight: '600' },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => `${ctx.parsed.y} peminjaman`
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#94a3b8', font: { size: 11 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: {
                        color: '#94a3b8',
                        font: { size: 11 },
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            interaction: { intersect: false, mode: 'index' },
        }
    });

    // 2. Distribusi Status (Doughnut Chart)
    const statusData = @json($statusDistribusi);
    const statusLabels = {
        'diajukan': 'Diajukan',
        'disetujui': 'Disetujui',
        'sedang_dikembalikan': 'Dikembalikan (proses)',
        'dikembalikan': 'Dikembalikan',
        'ditolak': 'Ditolak',
    };
    const statusColors = {
        'diajukan': colors.amber,
        'disetujui': colors.blue,
        'sedang_dikembalikan': colors.indigo,
        'dikembalikan': colors.emerald,
        'ditolak': colors.rose,
    };

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(statusData).map(k => statusLabels[k] || k),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: Object.keys(statusData).map(k => statusColors[k] || colors.slate),
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        font: { size: 11 },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} peminjaman`
                    }
                }
            }
        }
    });

    // 3. Top 5 Alat Terpopuler (Horizontal Bar)
    const alatData = @json($alatPopuler);
    new Chart(document.getElementById('chartAlatPopuler'), {
        type: 'bar',
        data: {
            labels: alatData.map(d => d.nama),
            datasets: [{
                label: 'Total Peminjaman',
                data: alatData.map(d => d.total),
                backgroundColor: palette.slice(0, alatData.length).map(c => c.replace(', 1)', ', 0.8)')),
                borderColor: palette.slice(0, alatData.length),
                borderWidth: 1,
                borderRadius: 6,
                barThickness: 28,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.x} kali dipinjam`
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                    ticks: { color: '#94a3b8', stepSize: 1, precision: 0 }
                },
                y: {
                    grid: { display: false },
                    ticks: { color: '#475569', font: { size: 11, weight: '500' } }
                }
            }
        }
    });

    // 4. Distribusi Kategori (Doughnut)
    const kategoriData = @json($kategoriDistribusi);
    new Chart(document.getElementById('chartKategori'), {
        type: 'doughnut',
        data: {
            labels: kategoriData.map(d => d.nama),
            datasets: [{
                data: kategoriData.map(d => d.jumlah),
                backgroundColor: palette.slice(0, kategoriData.length).map(c => c.replace(', 1)', ', 0.8)')),
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 16,
                        font: { size: 11 },
                        color: '#64748b'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} alat`
                    }
                }
            }
        }
    });
});
</script>
@endsection
