<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-t from-blue-200 via-yellow-200 to-white bg-fixed">

    <div class="container w-72 h-auto py-4 mx-auto mt-52 bg-slate-50 rounded-lg select-none opacity-90">
        <p class="text-center text-md font-bold">Forgot Password</p>

        @if (session('status'))
            <div class="bg-green-400 text-white p-2 rounded w-[88%] justify-center items-center mx-auto mt-2">
                {{ session('status') }}
            </div>
        @endif
        <div class="flex flex-col px-4 py-1">

            <form action="{{ route('password.email') }}" method="POST" class="mt-4">
                @csrf
                <input type="email" name="email" placeholder="Enter your email" required
                    class="w-full border border-slate-800 p-2 rounded">

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white p-1 rounded mt-2">
                    Send Reset Link
                </button>
            </form>
        </div>

    </div>


</body>

</html>
