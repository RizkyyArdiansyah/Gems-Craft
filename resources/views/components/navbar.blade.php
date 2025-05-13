<!-- Tambahkan class untuk navbar, dan atur backdrop-filter untuk memungkinkan efek blur -->
<nav class="navbar fixed top-0 left-0 right-0 w-full z-50 p-1 transition-all duration-300 bg-white/0">
    <div class="container mx-auto flex items-center">
        <div class="flex items-center">
            <img class="w-14 h-14 rounded-full" src="{{ asset('images/logoci.png') }}" alt="Logo">
            <h1
                class="ml-3 font-bold select-none text-2xl bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                Gems Craft
            </h1>
        </div>

        <!-- Menu Tengah (Hidden di Mobile, Muncul di Desktop) -->
        <div class="hidden md:flex flex-grow justify-center text-sm gap-x-6">
            <a href="/#home" class="nav-link hover:text-blue-500 transition-colors duration-200">HOME</a>
            <a href="/product" class="nav-link hover:text-blue-500 transition-colors duration-200">PRODUCT</a>
            <a href="/#about" class="nav-link hover:text-blue-500 transition-colors duration-200">ABOUT</a>
            <a href="/#contact" class="nav-link hover:text-blue-500 transition-colors duration-200">CONTACT</a>
        </div>

        <!-- Icon Cart & Search (Hidden di Mobile, Muncul di Desktop) -->
        <div class="hidden md:flex items-center ml-auto gap-x-3">
            <a href="{{ route('cart.index') }}" class="relative">
                <x-icons.cart class="transform scale-x-[-1]" />
                @if ($cartCount > 0)
                    <span
                        class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs font-semibold px-1 py-0 rounded-full">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
            <a href="{{ route('order.page') }}"
                class="relative inline-flex items-center rounded-full hover:bg-gray-100 transition-colors"
                title="Pesanan Saya">
                <x-icons.order />
            </a>
            <div class="relative">
                <button id="user-menu-btn" class="flex items-center gap-x-2 hover:text-blue-500">
                    @auth
                        <!-- Jika user punya foto, tampilkan foto, jika tidak tampilkan ikon -->
                        @if (Auth::user()->profile_picture)
                            <img class="w-8 h-8 rounded-full border-2 border-gray-300"
                                src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="User">
                        @else
                            <!-- Gunakan Heroicons jika tidak ada foto -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 hover:text-gray-800"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5.121 17.804A12.07 12.07 0 0112 15.75c2.377 0 4.596.693 6.465 1.873M16.5 10.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                            </svg>
                        @endif
                    @else
                        <!-- Tampilkan ikon default jika user belum login -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 hover:text-gray-800"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A12.07 12.07 0 0112 15.75c2.377 0 4.596.693 6.465 1.873M16.5 10.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                        </svg>
                    @endauth
                </button>

                <!-- Dropdown Menu -->
                <div id="user-menu"
                    class="hidden absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 z-50 border-2 border-black">
                    @auth
                        <p class="px-4 py-2 text-sm text-gray-700">Hello, {{ Auth::user()->name }}</p>
                        <a href="{{ route('profile.show') }}"
                            class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                        <form action="{{ route('logout') }}" method="POST" class="block">
                            @csrf
                            <button type="submit"
                                class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}"
                            class="block px-4 py-2 text-sm text-blue-500 hover:bg-gray-100">Login</a>
                    @endauth
                </div>
            </div>
        </div>

        <!-- Hamburger Icon (Muncul di Mobile) -->
        <button id="menu-toggle" class="md:hidden ml-auto">
            <svg class="w-8 h-8 text-gray-800" xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                <line x1="3" x2="21" y1="6" y2="6"></line>
                <line x1="3" x2="21" y1="12" y2="12"></line>
                <line x1="3" x2="21" y1="18" y2="18"></line>
            </svg>
        </button>
    </div>
</nav>

<!-- Dropdown Menu Mobile -->
<div id="mobile-menu"
    class="hidden fixed top-16 left-0 w-full bg-slate-50 shadow-md space-y-5 flex flex-col items-center py-4 z-50">
    <a href="/#home" class="nav-link hover:text-blue-500">HOME</a>
    <a href="/product" class="nav-link hover:text-blue-500">PRODUCT</a>
    <a href="/#about" class="nav-link hover:text-blue-500">ABOUT</a>
    <a href="/#contact" class="nav-link hover:text-blue-500">CONTACT</a>
    <div class="flex gap-x-3 gap-y-3">
        <div class="relative">
            <a href="{{ route('cart.index') }}" class="relative">
                <x-icons.cart class="transform scale-x-[-1]" />
                @if ($cartCount > 0)
                    <span
                        class="absolute top-0 right-0 translate-x-1/2 -translate-y-1/2 bg-red-500 text-white text-xs font-semibold px-1 py-0 rounded-full">
                        {{ $cartCount }}
                    </span>
                @endif
            </a>
        </div>
        <a href="{{ route('order.page') }}"
            class="relative inline-flex items-center rounded-full hover:bg-gray-100 transition-colors"
            title="Pesanan Saya">
            <x-icons.order />
        </a>
        <div class="relative">
            <button id="mobile-user-menu-btn" class="flex items-center gap-x-2 hover:text-blue-500">
                @auth
                    @if (Auth::user()->profile_picture)
                        <img class="w-8 h-8 rounded-full border-2 border-gray-300"
                            src="{{ asset('storage/' . Auth::user()->profile_picture) }}" alt="User">
                    @else
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 hover:text-gray-800"
                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A12.07 12.07 0 0112 15.75c2.377 0 4.596.693 6.465 1.873M16.5 10.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                        </svg>
                    @endif
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-blue-500 hover:text-gray-800"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M5.121 17.804A12.07 12.07 0 0112 15.75c2.377 0 4.596.693 6.465 1.873M16.5 10.5a4.5 4.5 0 10-9 0 4.5 4.5 0 009 0z" />
                    </svg>
                @endauth
            </button>

            <!-- Dropdown Menu -->
            <div id="mobile-user-menu"
                class="hidden absolute left-7 -top-8 w-40 bg-white shadow-lg rounded-lg border-2 border-black py-2 z-50">
                @auth
                    <p class="px-4 py-2 text-sm text-gray-700">Hello, {{ Auth::user()->name }}</p>
                    <a href="{{ route('profile.show') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
                    <form action="{{ route('logout') }}" method="POST" class="block">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-red-500 hover:bg-gray-100">Logout</button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-2 text-sm text-blue-500 hover:bg-gray-100">Login</a>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Script untuk menu toggle, dropdown user, dll -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const menuToggle = document.getElementById("menu-toggle");
        const mobileMenu = document.getElementById("mobile-menu");
        const userMenuBtn = document.getElementById("user-menu-btn");
        const userMenu = document.getElementById("user-menu");
        const mobileUserMenuBtn = document.getElementById("mobile-user-menu-btn");
        const mobileUserMenu = document.getElementById("mobile-user-menu");

        // Toggle Mobile Menu
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener("click", function(event) {
                event.stopPropagation();
                mobileMenu.classList.toggle("hidden");
            });
        }

        // Toggle Desktop User Menu
        if (userMenuBtn && userMenu) {
            userMenuBtn.addEventListener("click", function(event) {
                event.stopPropagation();
                userMenu.classList.toggle("hidden");
            });
        }

        // Toggle Mobile User Menu
        if (mobileUserMenuBtn && mobileUserMenu) {
            mobileUserMenuBtn.addEventListener("click", function(event) {
                event.stopPropagation();
                mobileUserMenu.classList.toggle("hidden");
            });
        }

        // Klik di luar menu untuk menutup semuanya
        document.addEventListener("click", function(event) {
            if (mobileMenu && !menuToggle.contains(event.target) && !mobileMenu.contains(event
                    .target)) {
                mobileMenu.classList.add("hidden");
            }

            if (userMenu && !userMenuBtn.contains(event.target) && !userMenu.contains(event.target)) {
                userMenu.classList.add("hidden");
            }

            if (mobileUserMenu && !mobileUserMenuBtn.contains(event.target) && !mobileUserMenu.contains(
                    event.target)) {
                mobileUserMenu.classList.add("hidden");
            }
        });
    });
</script>

<!-- Script untuk active links + scroll effect -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sections = document.querySelectorAll("section");
        const navLinks = document.querySelectorAll(".nav-link");
        const navbar = document.querySelector(".navbar");
        let lastScrollTop = 0;
        const currentPath = window.location.pathname;

        // Tambahkan effect smooth scroll
        document.querySelectorAll('a[href^="/#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                // Tidak perlu preventDefault di halaman product
                if (currentPath === '/product') {
                    return; // Biarkan default behavior untuk navigasi dari product ke home
                }

                e.preventDefault();

                const targetId = this.getAttribute('href').split('#')[1];
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    // Jika sudah di halaman home, smooth scroll ke section
                    if (currentPath === '/' || currentPath === '') {
                        // Hitung offset navbar
                        const navbarHeight = navbar.offsetHeight;
                        const offsetPosition = targetElement.offsetTop - navbarHeight -
                            5; // tambah padding 20px

                        window.scrollTo({
                            top: offsetPosition,
                            behavior: 'smooth'
                        });
                    } else {
                        // Jika di halaman lain, tambahkan hash ke URL dan biarkan browser navigate
                        window.location.href = '/#' + targetId;
                    }
                }
            });
        });

        function updateActiveLink() {
            let currentSection = "";

            // Handle hash sections untuk halaman home
            if (currentPath === '/' || currentPath === '') {
                sections.forEach((section) => {
                    const navbarHeight = navbar.offsetHeight;
                    const sectionTop = section.offsetTop - (navbarHeight + 50);
                    const sectionHeight = section.clientHeight;

                    if (window.scrollY >= sectionTop && window.scrollY < sectionTop + sectionHeight) {
                        currentSection = '/#' + section.getAttribute("id");
                    }
                });
            }

            // Update class navbar berdasarkan halaman atau scroll
            navLinks.forEach((link) => {
                // Periksa apakah URL saat ini cocok dengan href link
                let linkHref = link.getAttribute("href");

                // Untuk halaman produk
                if (currentPath === '/product' && linkHref === '/product') {
                    link.classList.add("active-link");
                }
                // Untuk halaman home dengan sections
                else if ((currentPath === '/' || currentPath === '') &&
                    linkHref === '/#' + (currentSection.split('#')[1] || 'home')) {
                    link.classList.add("active-link");
                } else {
                    link.classList.remove("active-link");
                }
            });
        }

        // Function for scroll effect
        function handleScroll() {
            let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

            // Detect if we're on homepage or product page
            if (currentPath === '/' || currentPath === '') {
                // Home page: apply blur effect when scrolling
                if (scrollTop > 10) {
                    navbar.classList.add('bg-white/70');
                    navbar.classList.add('backdrop-blur-md');
                    navbar.classList.add('shadow-md');
                } else {
                    navbar.classList.remove('bg-white/70');
                    navbar.classList.remove('backdrop-blur-md');
                    navbar.classList.remove('shadow-md');
                }
            } else if (currentPath === '/product') {
                // Product page: hide navbar on scroll down, show on scroll up
                if (scrollTop > 10) {
                    navbar.classList.add('bg-white');
                    navbar.classList.add('backdrop-blur-md');
                    navbar.classList.add('shadow-md');
                } else {
                    navbar.classList.remove('bg-white');
                    navbar.classList.remove('backdrop-blur-md');
                    navbar.classList.remove('shadow-md');
                }
            }

            lastScrollTop = scrollTop;
            updateActiveLink();
        }

        // Run when page loads and on scroll
        window.addEventListener("scroll", handleScroll);
        handleScroll(); // Run initially to set correct state
    });
</script>

<!-- Script untuk update cart badge -->
<script>
    function updateCartBadge() {
        fetch("{{ route('cart.count') }}")
            .then(response => response.json())
            .then(data => {
                let cartBadge = document.getElementById('cart-badge');
                if (data.cart_count > 0) {
                    cartBadge.textContent = data.cart_count;
                    cartBadge.classList.remove('hidden');
                } else {
                    cartBadge.classList.add('hidden');
                }
            });
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateCartBadge(); // Update badge saat halaman dimuat
    });
</script>

<!-- Tambah CSS untuk navbar active state -->
<style>
    .active-link {
        color: #3b82f6;
        /* blue-500 */
        font-weight: 600;
    }

    html {
        scroll-behavior: smooth;
    }

    /* Tambahkan scroll padding untuk mengatasi masalah fixed header */
    html {
        scroll-padding-top: 80px;
        /* Sesuaikan dengan tinggi navbar */
    }

    /* Tambahkan margin top pada sections untuk menghindari tumpang tindih navbar */
    section {
        padding-top: 20px;
    }
</style>
