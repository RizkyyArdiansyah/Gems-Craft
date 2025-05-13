<div class="bg-gradient-to-b from-gray-50 to-gray-100 min-h-screen py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto z-30">
        <!-- Product Grid -->

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6 lg:gap-8 select-none">
            @forelse ($products->sortByDesc(function($product) { return $product->stock > 0 ? 1 : 0; }) as $product)
                <div
                    class="bg-white rounded-xl shadow-md overflow-hidden transition-all duration-300 {{ $product->stock > 0 ? 'hover:shadow-xl hover:-translate-y-1' : 'opacity-75' }} group">
                    <!-- Badge for category -->
                    <div class="relative">
                        <span
                            class="absolute top-3 left-3 bg-indigo-100 text-indigo-800 text-xs px-3 py-1 rounded-full font-medium z-[5]">
                            {{ $product->category }}
                        </span>

                        <!-- Out of stock overlay for products with stock 0 -->
                        @if ($product->stock <= 0)
                            <div class="absolute inset-0 bg-gray-900/50 z-10 flex items-center justify-center">
                                <span class="bg-red-500 text-white font-bold py-2 px-4 rounded-lg transform rotate-12">
                                    OUT OF STOCK
                                </span>
                            </div>
                        @endif

                        <!-- Product Image with hover effect -->
                        <div class="relative h-56 sm:h-64 overflow-hidden bg-gray-50">
                            <img src="{{ asset('storage/' . $product->images) }}" alt="{{ $product->product_name }}"
                                class="w-full h-full object-contain p-6 transition-transform duration-500 {{ $product->stock > 0 ? 'group-hover:scale-105' : 'grayscale' }}">
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-black/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            </div>
                        </div>
                    </div>

                    <!-- Product Information -->
                    <div class="p-5">
                        <h2 class="text-base font-semibold text-gray-800 line-clamp-2 min-h-[3rem] mb-3">
                            {{ $product->product_name }}
                        </h2>

                        <div class="flex justify-between items-center">
                            <p
                                class="font-bold text-lg {{ $product->stock > 0 ? 'text-indigo-700' : 'text-gray-500' }}">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </p>
                            <div class="flex items-center">
                                <p
                                    class="text-sm {{ $product->stock > 0 ? 'text-gray-500' : 'text-red-500 font-medium' }}">
                                    Stok: {{ $product->stock }}
                                </p>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="mt-5 grid grid-cols-2 gap-3">
                            @auth
                                @if ($product->stock > 0)
                                    <form class="add-to-cart">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <input type="hidden" name="quantity" value="1">
                                        <button type="submit"
                                            class="w-full bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50 font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] transition-colors duration-200 flex items-center justify-center"
                                            data-product-id="{{ $product->id }}">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                            </svg>
                                            <span>Add Cart</span>
                                        </button>
                                    </form>

                                    <button
                                        class="buy-now w-full bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] transition-all duration-200 flex items-center justify-center shadow-sm hover:shadow-md"
                                        data-product-id="{{ $product->id }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Buy Now</span>
                                    </button>
                                @else
                                    <button disabled
                                        class="w-full bg-gray-200 border-2 border-gray-300 text-gray-400 cursor-not-allowed font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        <span>Add Cart</span>
                                    </button>

                                    <button disabled
                                        class="w-full bg-gray-300 text-gray-400 cursor-not-allowed font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7" />
                                        </svg>
                                        <span>Buy Now</span>
                                    </button>
                                @endif
                            @else
                                <button onclick="{{ $product->stock > 0 ? 'showLoginAlert()' : '' }}"
                                    class="w-full {{ $product->stock > 0 ? 'bg-white border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-50' : 'bg-gray-200 border-2 border-gray-300 text-gray-400 cursor-not-allowed' }} font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span>Add Cart</span>
                                </button>
                                <button onclick="{{ $product->stock > 0 ? 'showLoginAlert()' : '' }}"
                                    class="w-full {{ $product->stock > 0 ? 'bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white' : 'bg-gray-300 text-gray-400 cursor-not-allowed' }} font-medium rounded-lg px-1.5 py-2.5 text-[0.64rem] transition-all duration-200 flex items-center justify-center shadow-sm hover:shadow-md">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7" />
                                    </svg>
                                    <span>Buy Now</span>
                                </button>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full flex flex-col items-center justify-center py-16 bg-white rounded-xl shadow-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-300" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                            d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <p class="mt-6 text-gray-500 text-lg font-medium">Tidak ditemukan produk yang sesuai.</p>
                    <p class="text-gray-400 mt-2 text-center px-4">Mohon coba filter atau pencarian lain</p>
                    <button onclick="window.location.href='/product'"
                        class="mt-6 bg-gradient-to-r from-indigo-600 to-indigo-700 hover:from-indigo-700 hover:to-indigo-800 text-white px-6 py-3 rounded-lg hover:shadow-md transition-all duration-200 font-medium">
                        Lihat Semua Produk
                    </button>
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Script for cart functionality -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).ready(function() {
        // Buy Now functionality
        $(document).off("click", ".buy-now").on("click", ".buy-now", function() {
            let button = $(this);
            let productId = button.data("product-id");

            // Disable button and show loading state
            button.prop("disabled", true);
            const originalText = button.html();
            button.html(
                '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Memproses...'
                );

            $.ajax({
                url: "{{ route('cart.add') }}",
                method: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    product_id: productId,
                    quantity: 1
                },
                success: function(response) {
                    // Update cart count in localStorage
                    localStorage.setItem("cart_count", response.cart_count);

                    // Redirect to cart page
                    window.location.href = "{{ route('cart.index') }}";
                },
                error: function(xhr) {
                    let errorMessage = "Terjadi kesalahan, silakan coba lagi.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Gagal!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#4f46e5",
                        width: "350px",
                        padding: "1.5rem"
                    });

                    // Restore button state
                    button.prop("disabled", false).html(originalText);
                }
            });
        });

        // Add to Cart functionality
        $(document).off("submit", ".add-to-cart").on("submit", ".add-to-cart", function(e) {
            e.preventDefault();

            let form = $(this);
            let button = form.find("button");

            // Set quantity to 1
            form.find("input[name='quantity']").val(1);

            // Prevent double submission
            if (button.prop("disabled")) {
                return;
            }

            // Disable button and show loading state
            button.prop("disabled", true);
            const originalText = button.html();
            button.html(
                '<svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Menambahkan...'
                );

            $.ajax({
                url: "{{ route('cart.add') }}",
                method: "POST",
                data: form.serialize(),
                success: function(response) {
                    // Show success message
                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        customClass: {
                            popup: 'shadow-lg rounded-lg'
                        },
                        didOpen: (toast) => {
                            toast.addEventListener('mouseenter', Swal.stopTimer)
                            toast.addEventListener('mouseleave', Swal
                                .resumeTimer)
                        }
                    });

                    Toast.fire({
                        icon: 'success',
                        title: 'Produk ditambahkan ke keranjang'
                    });

                    // Update cart count in localStorage
                    localStorage.setItem("cart_count", response.cart_count);

                    // Dispatch event for cart badge update
                    window.dispatchEvent(new Event("cartUpdated"));
                },
                error: function(xhr) {
                    let errorMessage = "Terjadi kesalahan, silakan coba lagi.";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    }

                    Swal.fire({
                        title: "Gagal!",
                        text: errorMessage,
                        icon: "error",
                        confirmButtonText: "OK",
                        confirmButtonColor: "#4f46e5",
                        width: "350px",
                        padding: "1.5rem"
                    });
                },
                complete: function() {
                    // Restore button state
                    setTimeout(function() {
                        button.prop("disabled", false).html(originalText);
                    }, 500);
                }
            });
        });
    });

    // Login alert function
    function showLoginAlert() {
        Swal.fire({
            title: 'Perlu Login',
            text: 'Silakan login terlebih dahulu untuk melanjutkan',
            icon: 'info',
            confirmButtonText: 'Login Sekarang',
            confirmButtonColor: '#4f46e5',
            showCancelButton: true,
            cancelButtonText: 'Nanti Saja',
            cancelButtonColor: '#6b7280',
            width: '350px',
            padding: '1.5rem',
            customClass: {
                confirmButton: 'px-4 py-2',
                cancelButton: 'px-4 py-2'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '/login'; // Redirect to login page
            }
        });
    }
</script>


