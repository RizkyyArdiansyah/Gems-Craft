<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')

    <style>
        .no-transition * {
            transition: none !important;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>

</head>
<body class="bg-gray-100">

<div class="flex text-white">
    <div id="sidebar" class="fixed top-0 left-0 h-[1000vh] bg-blue-500 shadow-lg w-48 flex flex-col duration-300 ease-in-out no-transition select-none">
        <button id="toggleSidebar" class="p-3 flex items-center">
            <span id="closeIcon" class="hover:text-slate-500">❌</span>
            <span id="openIcon" class="hidden hover:text-slate-700 ml-1 ">☰</span>
        </button>

        <nav class="mt-5 space-y-2 flex-1">
            <a href="{{ route('dashboard') }}" class="flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}">
                <svg class="text-white w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M7.5 14.25v2.25m3-4.5v4.5m3-6.75v6.75m3-9v9M6 20.25h12A2.25 2.25 0 0 0 20.25 18V6A2.25 2.25 0 0 0 18 3.75H6A2.25 2.25 0 0 0 3.75 6v12A2.25 2.25 0 0 0 6 20.25Z" />
                </svg>
                <span class="ml-3 link-text">Dashboard</span>
            </a>
            <a href="{{ route('transactions') }}" class="flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('transactions') ? 'bg-gray-700' : '' }}">
                <svg class="text-white w-7 h-7" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M18.93 12A8.054 8.054 0 1 1 12 5.07V3h-1a10 10 0 1 0 10 10v-1Z" />
                    <path fill="currentColor"
                        d="M20.364 3.636A9 9 0 0 0 14 1v9h9a9 9 0 0 0-2.636-6.364M16 3.294A7.01 7.01 0 0 1 20.706 8H16Z" />
                </svg>
                <span class="ml-3 link-text">Transactions</span>
            </a>
            <a href="{{ route('products') }}" class="flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('products') ? 'bg-gray-700' : '' }}">
                <svg class="text-white w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
                <span class="ml-3 link-text">Product</span>
            </a>
            <a href="{{ route('customers') }}" class="flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('customers') ? 'bg-gray-700' : '' }}">
                <svg class="text-white w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                </svg>
                <span class="ml-3 link-text">Customer</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
            
            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                class="flex items-center p-2 rounded hover:bg-gray-700 {{ request()->routeIs('logout') ? 'bg-gray-700' : '' }}">
                <svg class="text-white w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                </svg>
                <span class="ml-3 link-text">Logout</span>
            </a>
            
        </nav>
    </div>

</div>


</body>
</html>
