<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register Account</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-t from-blue-200 via-yellow-200 to-white bg-fixed">

    <div class="container w-80 md:w-96 lg:w-1/3 mx-auto mt-20 bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-lg font-semibold mb-4 text-center">Register</h2>

        {{-- Notifikasi sukses --}}
        @if (session('success'))
            <div class="text-green-500 text-center mb-4">{{ session('success') }}</div>
        @endif

        {{-- Notifikasi error umum --}}
        @if ($errors->any())
            <div class="text-red-500 text-center mb-4">{{ $errors->first() }}</div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf
            <div class="mb-4 text-sm">
                <label class="block font-medium">Nama Pengguna</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full mt-1 h-10 border-2 border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 text-sm">
                <label class="block font-medium">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full mt-1 h-10 border-2 border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 text-sm">
                <label class="block font-medium">Password</label>
                <input type="password" name="password" required
                    class="w-full mt-1 h-10 border-2 border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('password')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4 text-sm">
                <label class="block font-medium">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full mt-1 h-10 border-2 border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 rounded transition duration-300">
                Register
            </button>
        </form>

        <p class="text-center text-sm mt-4">
            Sudah punya akun? <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Login di sini</a>
        </p>
    </div>

</body>

</html>
