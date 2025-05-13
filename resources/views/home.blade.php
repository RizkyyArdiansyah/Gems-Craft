<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Gem Crafters</title>
    <!-- Swiper.js untuk slider -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.4.1/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/9.4.1/swiper-bundle.min.css">
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS - Animate On Scroll Library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
</head>

<body class="bg-gradient-to-t from-blue-200 via-yellow-200 to-white font-sans">
    <!-- Notification -->
    <div class="bg-slate-50 flex items-center h-20 px-6 relative select-none">
        @if (session('success'))
            <div id="notif"
                class="absolute top-0 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg opacity-0 transition-all duration-500 ease-in-out text-xs text-center md:text-base">
                {{ session('success') }}
            </div>
        @endif

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                let notif = document.getElementById('notif');

                if (notif) {
                    // Bikin notifikasi langsung muncul
                    setTimeout(() => {
                        notif.classList.remove('opacity-0');
                        notif.classList.add('opacity-100');
                    }, 100); // Delay 100ms supaya transisi berjalan lancar

                    // Auto-hide setelah 3 detik
                    setTimeout(() => {
                        notif.classList.remove('opacity-100');
                        notif.classList.add('opacity-0');
                        setTimeout(() => notif.remove(), 500); // Hapus dari DOM setelah animasi selesai
                    }, 3000);
                }
            });
        </script>

        @include('components.navbar')
    </div>

    <!-- Hero Section with entry animations -->
    <section id="home"
        class="relative flex flex-col items-center justify-between px-6 md:px-20 md:flex-row h-screen select-none overflow-hidden">
        <!-- Background Text -->
        <div class="absolute inset-0 flex flex-col justify-center items-center leading-none md:-top-20">
            <p data-aos="zoom-in" data-aos-duration="600"
                class="text-[6rem] md:text-[12rem] font-bold text-gray-300 cursor-pointer opacity-30 uppercase transition-colors duration-300 hover:text-blue-700">
                GEMS</p>
            <p data-aos="zoom-in" data-aos-duration="600" data-aos-delay="100"
                class="text-[6rem] md:text-[12rem] font-bold text-gray-300 cursor-pointer opacity-30 uppercase transition-colors duration-300 hover:text-blue-700">
                STONE</p>
        </div>

        <!-- Content (Teks di Kiri) -->
        <div class="relative flex flex-col gap-y-4 z-10 text-center md:text-left mt-3 md:mt-0 md:-top-20">
            <p data-aos="fade-right" data-aos-duration="600 " class="text-sm text-gray-600">——— PREMIUM COLLECTION ———</p>
            <h1 data-aos="fade-right" data-aos-duration="600    " data-aos-delay="200" class="text-xl md:text-4xl capitalize font-light">Find the perfect ring that reflects</h1>
            <p data-aos="fade-right" data-aos-duration="600 " data-aos-delay="200" class="font-bold text-2xl md:text-5xl capitalize text-gray-800">your personality and elegance</p>
            <div data-aos="fade-up" data-aos-duration="600  " data-aos-delay="200" class="mt-4">
                <a href="/product"
                    class="px-8 py-3 bg-gray-800 text-white rounded-md hover:bg-blue-700 transition-colors duration-300">Shop
                    Now</a>
            </div>
        </div>

        <!-- Image (Gambar di Kanan di Desktop, di Bawah di Mobile) -->
        <div data-aos="fade-left" data-aos-duration="700" class="relative z-10 -top-7 md:-mx-6 md:-top-10">
            <img class="rounded-full h-52 md:h-80 transition-transform duration-500 hover:scale-105"
                src="{{ asset('images/cincin.png') }}" alt="Featured Ring">
        </div>
    </section>

    <!-- Product Slider Section -->
    <section id="featured-products"
        class="min-h-screen py-16 px-6 md:px-20 bg-gradient-to-t from-blue-200 via-white to-yellow-100">
        <div class="container mx-auto">
            <div class="text-center mb-12">
                <h2 data-aos="fade-down" data-aos-duration="1000" class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Featured Collection</h2>
                <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="text-gray-600 max-w-2xl mx-auto">Discover our handcrafted premium jewelry pieces, designed to
                    make every moment special.</p>
            </div>

            <!-- Swiper Slider -->
            <div data-aos="fade-up" data-aos-duration="1200" data-aos-delay="300" class="swiper productSwiper">
                <div class="swiper-wrapper">
                    @foreach ($products as $product)
                        <div class="swiper-slide">
                            <div
                                class="bg-gray-50 rounded-lg p-4 shadow-md transition-all duration-300 hover:shadow-xl">
                                <div class="overflow-hidden rounded-lg mb-4">
                                    <img src="{{ asset('storage/' . $product->images) }}" alt="{{ $product->name }}"
                                        class="w-full h-64 object-cover object-center transform transition-transform duration-500 hover:scale-110">
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h3>
                                <p class="text-blue-600 font-bold mt-2 mb-6">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination mt-6"></div>
                <div class="swiper-button-next text-gray-800"></div>
                <div class="swiper-button-prev text-gray-800"></div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about"
        class="min-h-screen py-16 px-6 md:px-20 bg-gradient-to-t from-yellow-200 via-white to-blue-200">
        <div class="container mx-auto">
            <div class="flex flex-col md:flex-row items-center gap-12">
                <div data-aos="fade-right" data-aos-duration="500" class="md:w-1/2">
                    <img src="{{ asset('images/gems.jpg') }}" alt="About Gem Crafters"
                        class="rounded-lg shadow-xl w-full h-auto object-cover">
                </div>
                <div class="md:w-1/2">
                    <p data-aos="fade-left" data-aos-duration="500" class="text-md text-blue-600 mb-4 font-bold text-center">——— OUR STORY ———</p>
                    <h2 data-aos="fade-left" data-aos-duration="500" data-aos-delay="200" class="text-3xl md:text-4xl font-bold text-gray-800 mb-6 text-center md:text-left">Crafting
                        Elegance Since 2020</h2>
                    <div ></div>
                    <p data-aos="fade-left" data-aos-duration="500" data-aos-delay="400" class="text-gray-600 mb-6 leading-relaxed">
                        At Gem Crafters, we believe that every piece of jewelry tells a story. Our master craftsmen
                        combine traditional techniques with modern design to create timeless pieces that celebrate
                        life's most precious moments.
                    </p>
                    <p data-aos="fade-left" data-aos-duration="500" data-aos-delay="400" class="text-gray-600 mb-8 leading-relaxed">
                        Each gem is carefully selected for its quality and brilliance, ensuring that our jewelry not
                        only looks stunning but also stands the test of time. From engagement rings to anniversary
                        gifts, our collections are designed to be treasured for generations.
                    </p>
                    <div data-aos="fade-up" data-aos-duration="500" data-aos-delay="400" class="flex space-x-6">
                        <div>
                            <p class="text-3xl font-bold text-blue-600">5+</p>
                            <p class="text-gray-600">Years of Experience</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-blue-600">1,000+</p>
                            <p class="text-gray-600">Happy Customers</p>
                        </div>
                        <div>
                            <p class="text-3xl font-bold text-blue-600">100%</p>
                            <p class="text-gray-600">Quality Guarantee</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-16 px-6 md:px-20 bg-gradient-to-t from-white via-blue-200 to-yellow-200">
        <div class="container">
            <div class="flex flex-col md:flex-row gap-12">
                <div class="md:w-1/2">
                    <p data-aos="fade-up" data-aos-duration="1000" class="text-blue-400 mb-8 font-bold text-center text-md">——— GET IN TOUCH ———</p>
                    <h2 data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="text-3xl md:text-4xl font-bold mb-6">Contact Us</h2>
                    <p data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="mb-8 text-slate-950">
                        Have questions about our products or need assistance with your order? Our team is ready to help
                        you find the perfect piece for any occasion.
                    </p>

                    <div class="space-y-6">
                        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100" class="flex items-start">
                            <div class="mr-4 p-3 bg-blue-800 rounded-full">
                                <i class="fas fa-map-marker-alt text-slate-50"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Visit Our Store</p>
                                <p class="text-slate-950">Jl. Gem Stone No. 123, Jakarta Selatan</p>
                            </div>
                        </div>

                        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="300" class="flex items-start">
                            <div class="mr-4 p-3 bg-blue-800 rounded-full">
                                <i class="fas fa-phone text-slate-50"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Call Us</p>
                                <p class="text-slate-950">+62 21 1234 5678</p>
                            </div>
                        </div>

                        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="500" class="flex items-start">
                            <div class="mr-4 p-3 bg-blue-800 rounded-full">
                                <i class="fas fa-envelope text-slate-50"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Email Us</p>
                                <p class="text-slate-950">gemscrafter@gmail.com</p>
                            </div>
                        </div>
                        <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="500" class="flex items-start">
                            <div class="mr-4 p-3 bg-blue-800 rounded-full">
                                <i class="fab fa-instagram text-slate-50"></i>
                            </div>
                            <div>
                                <p class="font-semibold mb-1">Instagram</p>
                                <p class="text-slate-950">Gems Crafter</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div data-aos="fade-up" data-aos-duration="600" class="md:w-1/2 w-full">
                    <form onsubmit="sendWhatsAppMessage(event)"
                        class="bg-gray-800 p-4 md:p-8 rounded-lg shadow-lg h-auto md:h-[98%]">
                        <h3 class="text-lg md:text-xl text-slate-50 font-bold mb-4 md:mb-6 text-center">Send Us a
                            Message</h3>

                        <div class="mb-3 md:mb-4">
                            <label for="name"
                                class="block text-xs md:text-sm font-medium text-gray-300 mb-1 md:mb-2">Your
                                Name</label>
                            <input type="text" id="name" name="name"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md p-1 md:p-2 text-sm md:text-base text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-3 md:mb-4">
                            <label for="email"
                                class="block text-xs md:text-sm font-medium text-gray-300 mb-1 md:mb-2">Your
                                Email</label>
                            <input type="email" id="email" name="email"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md p-1 md:p-2 text-sm md:text-base text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-3 md:mb-4">
                            <label for="subject"
                                class="block text-xs md:text-sm font-medium text-gray-300 mb-1 md:mb-2">Subject</label>
                            <input type="text" id="subject" name="subject"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md p-1 md:p-2 text-sm md:text-base text-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <div class="mb-4 md:mb-6">
                            <label for="message"
                                class="block text-xs md:text-sm font-medium text-gray-300 mb-1 md:mb-2">Your
                                Message</label>
                            <textarea id="message" name="message" rows="2"
                                class="w-full bg-gray-700 border border-gray-600 rounded-md p-1 md:p-2 text-sm md:text-base text-white focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 py-2 md:py-3 px-4 md:px-6 text-sm md:text-base rounded-md font-medium hover:bg-blue-700 transition-colors duration-300">
                            Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-800 text-gray-300 py-12 px-6 md:px-20">
        <div class="container mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="100">
                    <h3 class="text-xl font-bold text-white mb-4">Gem Crafters</h3>
                    <p class="mb-4">Crafting timeless elegance since 2020. Premium jewelry for life's special
                        moments.</p>
                    <p>&copy; 2025 Gem Crafters. All rights reserved.</p>
                </div>

                <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="300">
                    <h4 class="text-lg font-semibold text-white mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="#home" class="hover:text-blue-400 transition-colors duration-300">Home</a></li>
                        <li><a href="/product" class="hover:text-blue-400 transition-colors duration-300">Products</a>
                        </li>
                        <li><a href="#about" class="hover:text-blue-400 transition-colors duration-300">About Us</a>
                        </li>
                        <li><a href="#contact" class="hover:text-blue-400 transition-colors duration-300">Contact</a>
                        </li>
                    </ul>
                </div>

                <div data-aos="fade-up" data-aos-duration="800" data-aos-delay="500" class="">
                    <h4 class="text-lg font-semibold text-white mb-4">Newsletter</h4>
                    <p class="mb-4">Subscribe to get updates on new collections and exclusive offers.</p>
                    <form class="flex">
                        <input type="email" placeholder="Your email"
                            class="px-4 py-2 w-full rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800">
                        <button type="submit"
                            class="bg-blue-600 px-4 py-2 rounded-r-md hover:bg-blue-700 transition-colors duration-300">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </footer>

    <!-- Initialize Swiper and AOS -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Initialize AOS (Animate On Scroll)
            AOS.init({
                once: false, // whether animation should happen only once - while scrolling down
                mirror: false, // whether elements should animate out while scrolling past them
                offset: 120, // offset (in px) from the original trigger point
                duration: 500, // animation duration
                easing: 'ease-in-out', // default easing for AOS animations
                disable: 'mobile', // accepts following values: 'phone', 'tablet', 'mobile', boolean, expression or function
                anchorPlacement: 'top-bottom', // defines which position of the element regarding to window should trigger the animation
            });
            
            // Initial animation for elements visible on page load
            setTimeout(() => {
                AOS.refresh();
            }, 100);

            // Initialize Swiper
            new Swiper(".productSwiper", {
                slidesPerView: 1,
                spaceBetween: 20,
                pagination: {
                    el: ".swiper-pagination",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".swiper-button-next",
                    prevEl: ".swiper-button-prev",
                },
                breakpoints: {
                    640: {
                        slidesPerView: 2,
                    },
                    1024: {
                        slidesPerView: 3,
                    },
                },
                autoplay: {
                    delay: 1000,
                    disableOnInteraction: false,
                },
            });

            // Smooth scroll untuk link navigasi
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function(e) {
                    e.preventDefault();

                    document.querySelector(this.getAttribute('href')).scrollIntoView({
                        behavior: 'smooth'
                    });
                });
            });
            
            // Refresh AOS on scroll to handle any elements that didn't animate properly
            window.addEventListener('scroll', function() {
                AOS.refresh();
            });
        });
    </script>

    <script>
        function sendWhatsAppMessage(event) {
            event.preventDefault(); // mencegah submit form

            const name = document.getElementById("name").value.trim();
            const email = document.getElementById("email").value.trim();
            const subject = document.getElementById("subject").value.trim();
            const message = document.getElementById("message").value.trim();

            if (!name || !email || !subject || !message) {
                alert("Please fill in all fields.");
                return;
            }

            // Nomor WhatsApp tujuan (ganti dengan milik kamu, tanpa tanda +)
            const phoneNumber = "6289516192149";

            // Format pesan
            const whatsappMessage =
                `Hello, my name is *${name}* (%0AEmail: ${email})%0A%0A*Subject:* ${subject}%0A%0A*Message:* ${message}`;

            // Redirect ke WhatsApp
            window.open(`https://wa.me/${phoneNumber}?text=${whatsappMessage}`, '_blank');
        }
    </script>

    <!-- Optional: Add reveal animation for page load -->
    <style>
        .reveal-init {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 1s ease, transform 1s ease;
        }
        
        .reveal-show {
            opacity: 1;
            transform: translateY(0);
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .body-load {
            animation: fadeIn 1.5s ease-in-out;
        }
    </style>
    
    <script>
        // Add body fade-in animation
        document.body.classList.add('body-load');
        
        // Add reveal effect for elements without AOS
        document.addEventListener("DOMContentLoaded", function() {
            const revealElements = document.querySelectorAll('.reveal-init');
            
            setTimeout(() => {
                revealElements.forEach(element => {
                    element.classList.add('reveal-show');
                });
            }, 300);
        });
    </script>
</body>

</html>