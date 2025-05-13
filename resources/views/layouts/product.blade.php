<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Gem Crafters</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="min-h-screen bg-gradient-to-t from-blue-200 via-yellow-200 to-white">

    <div class="navbar bg-slate-50 flex items-center h-20 px-6 relative select-none z-10">
        @include('components.navbar')
    </div>

    <div class="flex flex-col min-h-screen">
        <!-- Konten Utama -->
        <div class="container mx-auto px-4 py-8 flex-grow">
            <!-- Modern Filter Bar -->
            <div class="mb-4 md:mb-6">
                <form method="GET" action="{{ route('product.filter') }}" id="filter-form" class="w-full">
                    <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
                        <!-- Left side - Filter buttons -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Category Filter Dropdown -->
                            <div class="relative">
                                <button type="button" id="category-dropdown-btn"
                                    class="flex items-center gap-2 px-2 md:px-4 py-2 z-10 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 6h16M4 12h16M4 18h7" />
                                    </svg>
                                    <span class="text-sm font-medium">Category</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="category-dropdown"
                                    class="hidden absolute z-20 left-0 mt-2 w-48 bg-white border border-gray-300 rounded-lg shadow-lg">
                                    <div class="p-3">
                                        <p class="font-medium text-sm text-gray-700 mb-2">Select Category</p>
                                        @foreach ($categories as $category)
                                            <div class="flex items-center mb-2">
                                                <input id="category-{{ $category }}" type="radio" name="category"
                                                    value="{{ $category }}"
                                                    {{ request('category') === $category ? 'checked' : '' }}
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500">
                                                <label for="category-{{ $category }}"
                                                    class="ml-2 text-sm text-gray-700">{{ $category }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <!-- Price Range Filter Dropdown -->
                            <div class="relative">
                                <button type="button" id="price-dropdown-btn"
                                    class="flex items-center gap-2 px-2 md:px-4 py-2 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 transition focus:outline-none focus:ring-2 focus:ring-blue-500 select-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-sm font-medium">Price Range</span>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <div id="price-dropdown"
                                    class="hidden absolute left-0 mt-2 md:w-64 bg-white border border-gray-300 rounded-lg shadow-lg z-30">
                                    <div class="p-3">
                                        <p class="font-medium text-sm text-gray-700 mb-3">Select Price Range</p>

                                        <div class="mb-3">
                                            <label for="min-price"
                                                class="block text-xs font-medium text-gray-700 mb-1">Minimum
                                                Price</label>
                                            <input type="number" id="min-price" name="min_price"
                                                value="{{ request('min_price', $priceRange['min']) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>

                                        <div class="mb-3">
                                            <label for="max-price"
                                                class="block text-xs font-medium text-gray-700 mb-1">Maximum
                                                Price</label>
                                            <input type="number" id="max-price" name="max_price"
                                                value="{{ request('max_price', $priceRange['max']) }}"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-md text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        </div>
                                    </div>
                                    <div class="border-t border-gray-200 px-3 py-2">
                                        <button type="button" id="apply-price"
                                            class="w-full bg-blue-600 text-white text-sm py-1.5 rounded hover:bg-blue-700 transition">Apply</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Mobile view: Search and Clear Filters -->
                        <div class="flex w-full md:mt-0 md:w-auto">
                            <!-- Clear Filters Button (Visible when filters are active) -->
                            @if (request('category') || request('min_price') || request('max_price'))
                                <div class="flex items-center mr-auto md:mr-2">
                                    <a href="{{ route('product.index') }}"
                                        class="px-3 py-2 text-sm text-red-600 font-medium bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                        Clear Filters
                                    </a>
                                </div>
                            @endif

                            <!-- Search field - always positioned on right side for mobile -->
                            <div class="relative ml-auto w-[60%] md:w-64">
                                <div
                                    class="flex items-center border border-blue-600 rounded-lg overflow-hidden shadow-sm focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500 z-10">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 ml-3"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <input type="text" id="search" name="search"
                                        value="{{ request('search') }}" placeholder="Search products..."
                                        class="w-full text-blue-600 font-semibold px-3 py-2 bg-transparent border-none text-sm focus:outline-none">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Active Filters Display -->
            @if (request('category') || request('min_price') || request('max_price'))
                <div class="flex items-center md:-mt-4 gap-2 mb-4 bg-blue-50 px-4 py-3 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    <span class="text-sm text-gray-700">Active filters:</span>
                    <div class="flex flex-wrap gap-2">
                        @if (request('category'))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Category: {{ request('category') }}
                            </span>
                        @endif
                        @if (request('min_price') || request('max_price'))
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Price: {{ request('min_price', $priceRange['min']) }} -
                                {{ request('max_price', $priceRange['max']) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Product List -->
            <div id="product-list">
                @include('partials.product-list', ['products' => $products])
            </div>
        </div>

        <!-- Footer -->
        <div class="w-full bg-gray-800 text-white text-center p-4">
            Copyright © 2025 Gems Craft. All Rights Reserved.
        </div>
    </div>

    <!-- JavaScript for Dropdowns -->
    <script>
        // Category dropdown toggle
        const categoryBtn = document.getElementById('category-dropdown-btn');
        const categoryDropdown = document.getElementById('category-dropdown');

        categoryBtn.addEventListener('click', () => {
            categoryDropdown.classList.toggle('hidden');
            priceDropdown.classList.add('hidden'); // Close other dropdown
        });

        // Price dropdown toggle
        const priceBtn = document.getElementById('price-dropdown-btn');
        const priceDropdown = document.getElementById('price-dropdown');

        priceBtn.addEventListener('click', () => {
            priceDropdown.classList.toggle('hidden');
            categoryDropdown.classList.add('hidden'); // Close other dropdown
        });

        // Apply price filter
        document.getElementById('apply-price').addEventListener('click', () => {
            document.getElementById('filter-form').submit();
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', (event) => {
            if (!categoryBtn.contains(event.target) && !categoryDropdown.contains(event.target)) {
                categoryDropdown.classList.add('hidden');
            }

            if (!priceBtn.contains(event.target) && !priceDropdown.contains(event.target)) {
                priceDropdown.classList.add('hidden');
            }
        });

        // Auto-submit when category radio is selected
        document.querySelectorAll('input[name="category"]').forEach(radio => {
            radio.addEventListener('change', function() {
                const selectedCategory = this.value;
                const url = new URL(window.location.href);
                url.searchParams.set('category', selectedCategory);
                window.location.href = url.toString();
            });
        });


        // Search functionality
        const searchInput = document.getElementById('search');
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const searchValue = searchInput.value.trim();
                if (searchValue) {
                    const form = document.getElementById('filter-form');
                    const searchField = document.createElement('input');
                    searchField.type = 'hidden';
                    searchField.name = 'search';
                    searchField.value = searchValue;
                    form.appendChild(searchField);
                    form.submit();
                }
            }
        });
    </script>

    <script>
        $(document).ready(function() {
            // Menangani form add-to-cart
            $(document).off("submit", ".add-to-cart").on("submit", ".add-to-cart", function(e) {
                e.preventDefault();

                let form = $(this);
                let button = form.find("button");

                // Set quantity to 1 sebelum mengirim request
                form.find("input[name='quantity']").val(1);

                // Blok pengulangan klik
                if (button.prop("disabled")) {
                    return;
                }

                button.prop("disabled", true).text("Adding...");

                $.ajax({
                    url: "{{ route('cart.add') }}",
                    method: "POST",
                    data: form.serialize(),
                    beforeSend: function() {},
                    success: function(response) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: "Produk berhasil ditambahkan ke keranjang!",
                            icon: "success",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#3085d6",
                            width: "350px",
                            padding: "0.5rem"
                        });

                        // ✅ Simpan jumlah cart di localStorage
                        localStorage.setItem("cart_count", response.cart_count);

                        // ✅ Kirim event ke halaman utama agar badge ter-update
                        window.dispatchEvent(new Event("cartUpdated"));
                    },
                    error: function() {
                        Swal.fire({
                            title: "Gagal!",
                            text: "Terjadi kesalahan, coba lagi.",
                            icon: "error",
                            confirmButtonText: "OK",
                            confirmButtonColor: "#d33",
                            width: "350px",
                            padding: "0.5rem"
                        });
                    },
                    complete: function() {
                        button.prop("disabled", false).text("Add to Cart");
                    }
                });
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#search').on('keyup', function() {
                let query = $(this).val();
                $.ajax({
                    url: "{{ route('product.search') }}",
                    type: "GET",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#product-list').html(data);
                    }
                });
            });
        });
    </script>

</body>

</html>
