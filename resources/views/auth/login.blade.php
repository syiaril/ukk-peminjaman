{{--
    Halaman Login (auth/login.blade.php)

    Halaman untuk pengguna masuk ke sistem.
    Tidak menggunakan layout utama (standalone page).
    Menampilkan form dengan field email dan password.
    Setelah login berhasil, pengguna diarahkan ke dashboard sesuai perannya.
--}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Peminjaman Alat</title>
    {{-- Memuat asset CSS dan JS menggunakan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    {{-- Card form login --}}
    <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center text-gray-800 mb-8">Masuk Aplikasi</h2>
        
        {{-- Form login: mengirim data ke route 'login' dengan method POST --}}
        <form method="POST" action="{{ route('login') }}">
            @csrf {{-- Token CSRF untuk keamanan --}}
            
            {{-- Field Email --}}
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Alamat Email</label>
                <input type="email" name="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" value="{{ old('email') }}" required autofocus>
                {{-- Menampilkan pesan error validasi untuk email --}}
                @error('email')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Field Password --}}
            <div class="mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Kata Sandi</label>
                <input type="password" name="password" id="password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" required>
                {{-- Menampilkan pesan error validasi untuk password --}}
                @error('password')
                    <p class="text-red-500 text-xs italic mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Submit Login --}}
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Masuk
                </button>
            </div>
        </form>
    </div>
</body>
</html>
