<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reset Password</title>
    @vite('resources/css/app.css')
</head>
<body class="min-h-screen bg-gradient-to-t from-blue-200 via-yellow-200 to-white">

    <div class="container w-72 h-auto py-3 mx-auto mt-44 bg-slate-50 rounded-lg select-none opacity-90 px-4">
        <h2 class="text-xl font-bold text-center mt-2 mb-6">Reset Password</h2>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-2 rounded mb-3">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <div class="mb-4">
                <label class="block text-md mb-1">Email</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full border p-2 rounded focus:outline-none focus:ring focus:border-blue-300 border-slate-800">
            </div>

            <div class="mb-4">
                <label class="block text-md mb-1">New Password</label>
                <input type="password" name="password" required
                    class="w-full border p-2 rounded focus:outline-none focus:ring focus:border-blue-300 border-slate-800">
            </div>

            <div class="mb-4">
                <label class="block text-md mb-1">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full border p-2 rounded focus:outline-none focus:ring focus:border-blue-300 border-slate-800">
            </div>

            <button type="submit"
                class="w-full bg-blue-500 hover:bg-blue-700 text-white px-3 py-2 rounded mb-3">
                Reset Password
            </button>
        </form>
    </div>

</body>
</html>
