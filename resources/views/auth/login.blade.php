<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Account</title>
    @vite('resources/css/app.css')

</head>

<body class="bg-gradient-to-t from-blue-200 via-yellow-200 to-white bg-fixed">

    <div class="container w-72 h-auto py-3 mx-auto mt-44 bg-slate-50 rounded-lg select-none opacity-90">
        <div class="flex-col ml-3 mr-3 mb-2">
            <h2 class="text-xl font-semibold mb-4  text-center ">Login</h2>
            @if ($errors->any())
                <div id="error-message"
                    class="text-slate-200 text-center mb-2 bg-red-500 rounded-lg p-2 transition-opacity duration-700 ease-out">
                    {{ $errors->first() }}
                </div>

                <script>
                    setTimeout(() => {
                        const errorMessage = document.getElementById("error-message");
                        if (errorMessage) {
                            errorMessage.classList.add("opacity-0"); // Mulai animasi fade-out
                            setTimeout(() => {
                                errorMessage.style.display = "none"; // Hilangkan dari DOM setelah animasi selesai
                            }, 700);
                        }
                    }, 2000); // Tampilkan selama 1 detik, lalu fade-out
                </script>
            @endif
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block">Email</label>
                    <input type="email" name="email" required
                        class="w-full mt-1 h-8 border-2 border-gray-400 p-2 rounded   ">
                </div>
                <div class="mb-4">
                    <label class="block">Password</label>
                    <input type="password" name="password" required
                        class="w-full mt-1 h-8 border-2 border-gray-400 p-2 rounded">
                </div>
                <div class="mb-3 text-end -mt-2">
                    <a class="hover:text-blue-500 text-xs" href="{{ route('password.request') }}">Forgot Password?</a></p>
                </div>
                <button type="submit"
                    class="w-full bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 mb-2 rounded">Login</button>
                <div class="mb-3">
                    <p class="text-xs text-center mt-2">Don't have an account? <a class="hover:text-blue-500"
                            href="{{ route('register') }}">Register</a></p>

                </div>
            </form>
            @if (session('email_not_verified'))
                @php
                    $email = session('email_address');
                    $emailProvider = explode('@', $email)[1];

                    // Cek domain email dan buat link redirect ke provider email
                    $emailLinks = [
                        'gmail.com' => 'https://mail.google.com/',
                        'yahoo.com' => 'https://mail.yahoo.com/',
                        'outlook.com' => 'https://outlook.live.com/',
                        'hotmail.com' => 'https://outlook.live.com/',
                    ];

                    $mailUrl = $emailLinks[$emailProvider] ?? '#';
                @endphp

                <div class="text-slate-50 text-center mb-2 bg-yellow-500 rounded-lg p-2">
                    <p>Silakan verifikasi email Anda sebelum login.</p>
                    <a href="{{ $mailUrl }}" target="_blank"
                        class="bg-blue-500 hover:bg-blue-700 text-white px-3 py-1 mt-2 rounded inline-block">
                        Buka Email untuk Verifikasi
                    </a>

                    <form action="{{ route('verification.resend') }}" method="POST" class="mt-2">
                        @csrf
                        <input class="hidden" type="email" name="email" id="email" required 
                            class="w-full border p-2 rounded mt-1 focus:outline-none focus:ring focus:border-blue-300"
                            placeholder="Email Anda"
                            value="{{ old('email', session('email_address')) }}">
                    
                        <button type="submit" class="mt-2 bg-green-500 hover:bg-green-700 text-white px-3 py-1 rounded">
                            Kirim Ulang Email Verifikasi
                        </button>
                    </form>
                </div>
            @endif


        </div>

    </div>




</body>

</html>
