<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Gem Crafters</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-gray-100 min-h-screen flex items-start justify-center">

    <div x-cloak class="md:w-2/3 lg:w-1/2 mx-auto my-8 p-6 bg-white rounded-xl shadow-lg select-none"
        x-data="{
            openProfile: true,
            openPassword: false,
        
            init() {
                this.openProfile = JSON.parse(localStorage.getItem('openProfile') ?? 'true');
                this.openPassword = JSON.parse(localStorage.getItem('openPassword') ?? 'false');
            },
        
            toggleProfile() {
                this.openProfile = !this.openProfile;
                localStorage.setItem('openProfile', this.openProfile);
            },
        
            togglePassword() {
                this.openPassword = !this.openPassword;
                localStorage.setItem('openPassword', this.openPassword);
            }
        }" x-init="init">

        <div class="flex justify-between items-center border-b pb-2 mb-4">
            <h2 class="text-lg font-semibold text-gray-700">Profile Setting</h2>
        </div>

        <!-- Update Profile Section -->
        <div>
            <div class="flex justify-between items-center">
                <h2 class="text-sm md:text-md font-semibold text-blue-600">Update Profile</h2>
                <button @click="toggleProfile" class="text-sm font-semibold text-blue-500 hover:underline">
                    <span x-text="openProfile ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <div x-show="openProfile" x-cloak class="mt-4 transition-all duration-300">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    @if (session('success_profile'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                            {{ session('success_profile') }}
                        </div>
                    @endif

                    @if (session('error_profile'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                            {{ session('error_profile') }}
                        </div>
                    @endif


                    <div class="mb-4">
                        <div class="flex items-center mt-2 gap-4">
                            @if (Auth::user()->profile_picture)
                                <img src="{{ asset('storage/' . Auth::user()->profile_picture) }}"
                                    class="w-16 h-16 rounded-full border-2 border-gray-300">
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg"
                                    class="w-16 h-16 text-blue-500 border-2 border-gray-300 rounded-full p-2"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5.121 17.804A12.07 12.07 0 0112 15.75c2.377 0 4.596.693 6.465 1.873M16.5 10.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                                </svg>
                            @endif
                            <input type="file" name="profile_picture" class="text-sm">
                        </div>
                    </div>
                    <button type="submit"
                        class="bg-blue-500 text-sm text-white px-4 py-2 rounded hover:bg-blue-600">Save Picture</button>
                </form>
            </div>
        </div>

        <hr class="my-6">

        <!-- Change Password Section -->
        <div>
            <div class="flex justify-between items-center">
                <h2 class="text-sm md:text-md font-semibold text-blue-600">Change Password</h2>
                <button @click="togglePassword" class="text-sm font-semibold text-blue-500 hover:underline">
                    <span x-text="openPassword ? 'Hide' : 'Show'"></span>
                </button>
            </div>
            <div x-show="openPassword" x-cloak class="mt-4 transition-all duration-300">
                <form action="{{ route('password.change') }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if (session('success_password'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            class="bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                            {{ session('success_password') }}
                        </div>
                    @endif

                    @if (session('error_password'))
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                            {{ session('error_password') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 2000)"
                            class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif


                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm mb-1">Password Lama</label>
                        <input type="password" name="current_password"
                            class="w-full px-4 py-2 border rounded-lg h-9 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm mb-1">Password Baru</label>
                        <input type="password" name="new_password"
                            class="w-full px-4 py-2 border rounded-lg h-9 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation"
                            class="w-full px-4 py-2 border rounded-lg h-9 focus:outline-none focus:ring-2 focus:ring-blue-400">
                    </div>

                    <button type="submit"
                        class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 text-sm">Change
                        Password</button>
                </form>
            </div>
        </div>

        <div class="flex justify-center mt-8">
            <a href="/"
                class="group relative inline-flex items-center justify-center px-5 py-2 bg-gradient-to-r from-red-500 to-rose-600 text-white font-medium rounded-md overflow-hidden shadow-md transition-all duration-300 hover:from-red-600 hover:to-red-800">
                <span class="relative flex items-center">
                    <svg class="rotate-180 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Back to Home
                </span>
            </a>
        </div>

    </div>

</body>

</html>
