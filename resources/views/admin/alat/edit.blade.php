{{--
    Halaman Edit Alat (admin/alat/edit.blade.php)

    Form untuk mengedit data alat yang sudah ada.
    Field diisi otomatis dengan data alat saat ini.
    Jika ada foto, ditampilkan preview foto saat ini.
    Foto bisa diganti dengan mengupload file baru, atau dikosongkan jika tidak ingin mengubah.
    
    Data dikirim ke route 'admin.alat.update' dengan method PUT.
--}}
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-lg">
    <div class="md:flex">
        <div class="w-full p-4">
            <h2 class="text-2xl font-bold mb-4">Edit Alat</h2>
            {{-- Form edit alat: menggunakan method PUT (override via @method) --}}
            <form action="{{ route('admin.alat.update', $alat->id) }}" method="POST" enctype="multipart/form-data">
                @csrf {{-- Token CSRF untuk keamanan --}}
                @method('PUT') {{-- Override method POST menjadi PUT untuk update --}}
                
                {{-- Input: Nama Alat (diisi dengan nilai saat ini) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nama_alat">
                        Nama Alat
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="nama_alat" type="text" name="nama_alat" value="{{ $alat->nama_alat }}" required>
                </div>

                {{-- Dropdown: Pilih Kategori (kategori saat ini ditandai 'selected') --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="kategori_id">
                        Kategori
                    </label>
                    <select class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="kategori_id" name="kategori_id" required>
                        @foreach($kategori as $k)
                            {{-- Tandai kategori yang sedang dipilih dengan 'selected' --}}
                            <option value="{{ $k->id }}" {{ $alat->kategori_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kategori }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Input: Stok Alat (diisi dengan nilai saat ini) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="stok">
                        Stok
                    </label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="stok" type="number" name="stok" value="{{ $alat->stok }}" min="0" required>
                </div>

                {{-- Textarea: Deskripsi Alat (diisi dengan nilai saat ini) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="deskripsi">
                        Deskripsi
                    </label>
                    <textarea class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="deskripsi" name="deskripsi">{{ $alat->deskripsi }}</textarea>
                </div>

                {{-- Upload: Foto Alat (dengan preview foto saat ini jika ada) --}}
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="gambar">
                        Foto Alat
                    </label>
                    {{-- Tampilkan preview foto yang sudah ada --}}
                    @if($alat->gambar)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $alat->gambar) }}" alt="{{ $alat->nama_alat }}" class="w-32 h-32 object-cover rounded">
                            <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
                        </div>
                    @endif
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="gambar" type="file" name="gambar" accept="image/*">
                    <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, GIF. Maks: 2MB. Kosongkan jika tidak ingin mengubah foto.</p>
                </div>

                {{-- Tombol Perbarui dan Batal --}}
                <div class="flex items-center justify-between">
                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                        Perbarui
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
