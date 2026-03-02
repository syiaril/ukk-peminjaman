{{--
    Halaman Tambah Alat (admin/alat/create.blade.php)

    Form untuk menambahkan alat baru ke dalam sistem.
    Field yang tersedia:
    - Nama Alat (wajib)
    - Kategori (dropdown, wajib)
    - Stok (angka, wajib)
    - Deskripsi (opsional)
    - Foto Alat (upload gambar, opsional, maks 2MB)
    
    Data dikirim ke route 'admin.alat.store' dengan method POST.
    Menggunakan enctype="multipart/form-data" untuk mendukung upload file.
--}}
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-lg">
    <div class="md:flex">
        <div class="w-full p-4">
            <h2 class="text-2xl font-bold mb-4">Tambah Alat</h2>
            {{-- Form tambah alat dengan dukungan upload file (enctype multipart) --}}
            <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf {{-- Token CSRF untuk keamanan --}}
                
                {{-- Input: Nama Alat --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_alat">
                        Nama Alat
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_alat" type="text" name="nama_alat" required>
                </div>

                {{-- Dropdown: Pilih Kategori --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="kategori_id">
                        Kategori
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kategori_id" name="kategori_id" required>
                        {{-- Loop semua kategori dari database --}}
                        @foreach($kategori as $k)
                            <option value="{{ $k->id }}">{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Input: Stok Alat --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stok">
                        Stok
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stok" type="number" name="stok" min="0" required>
                </div>

                {{-- Textarea: Deskripsi Alat (opsional) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">
                        Deskripsi
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deskripsi" name="deskripsi"></textarea>
                </div>

                {{-- Upload: Foto Alat (opsional) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="gambar">
                        Foto Alat
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="gambar" type="file" name="gambar" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB</p>
                </div>

                {{-- Tombol Simpan dan Batal --}}
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Simpan
                    </button>
                    <a href="{{ route('admin.alat.index') }}" class="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
