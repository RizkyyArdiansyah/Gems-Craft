<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Gem Crafters - Riwayat Pesanan</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.2.0/flowbite.min.js"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>

<body class="bg-gradient-to-br from-indigo-50 to-purple-50 min-h-screen">

    <div class="container mx-auto px-4 py-8 max-w-6xl select-none">
        <!-- Header dengan gradien -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-lg p-5 mb-4">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-lg md:text-3xl font-bold text-white">Riwayat Pesanan</h1>
                    <p class="text-md md:text-lg text-purple-200">Daftar pesanan yang telah Anda lakukan</p>
                </div>
                <!-- Navigasi -->
                <div class="flex flex-col md:flex-row space-y-2 md:space-y-0 md:space-x-3 ml-2 md:ml-0">
                    <a href="{{ route('home') }}"
                        class="flex items-center bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-2 py-1 md:px-4 md:py-2 rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                            </path>
                        </svg>
                        Beranda
                    </a>
                    <a href="{{ route('product.index') }}"
                        class="flex items-center bg-white bg-opacity-20 hover:bg-opacity-30 text-white px-2 py-1 md:px-4 md:py-2 rounded-lg transition duration-300">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                        Produk
                    </a>
                </div>
            </div>
        </div>

        @if ($orders->isEmpty())
            <div class="bg-white p-10 rounded-xl shadow-md text-center">
                <div class="mb-6">
                    <div class="w-24 h-24 mx-auto bg-indigo-100 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                        </svg>
                    </div>
                </div>
                <h2 class="text-2xl font-semibold text-gray-800 mb-3">Belum Ada Pesanan</h2>
                <p class="text-gray-600 mb-6 max-w-md mx-auto">Anda belum memiliki riwayat pesanan. Mulai jelajahi
                    produk kami untuk menemukan permata favorit Anda.</p>
                <a href="{{ route('product.index') }}"
                    class="inline-flex items-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    Lihat Produk
                </a>
            </div>
        @else
            <div class="grid grid-cols-1 gap-7">
                @foreach ($orders as $order)
                    <div
                        class="bg-white rounded-xl shadow-lg overflow-hidden transform transition duration-300 hover:shadow-xl">
                        <div
                            class="bg-gradient-to-r from-indigo-500 to-purple-500 p-3 border-b flex justify-between items-center">
                            <div>
                                <div class="flex flex-row items-center gap-x-1">
                                    <span class="text-[0.7rem] md:text-sm font-medium text-indigo-100 mb-1">Order
                                        ID:</span>
                                    <span
                                        class="text-[0.7rem] md:text-sm font-bold text-white mb-1">{{ $order->order_id }}</span>
                                </div>
                                <div class="text-[0.63rem] md:text-xs text-indigo-100">
                                    {{ $order->created_at->format('d M Y, H:i') }}</div>
                            </div>
                            <div class="flex gap-3 items-center">
                                @if ($order->payment_status == 'paid')
                                    <span
                                        class="px-4 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 shadow-sm">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                @else
                                    <span
                                        class="px-4 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 shadow-sm">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="p-4 md:p-6">
                            <!-- Items -->
                            <div class="-mt-1">
                                <h3 class="text-md md:text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                    Detail Produk
                                </h3>
                                <div class="bg-gray-50 rounded-lg overflow-hidden">
                                    @foreach ($order->items as $item)
                                        <div
                                            class="flex p-4 border-b last:border-b-0 hover:bg-indigo-50 transition duration-200">
                                            <div
                                                class="w-16 h-16 md:w-20 md:h-20 rounded-lg overflow-hidden bg-gray-100 mr-4 flex-shrink-0 shadow-sm">
                                                @if ($item->product && $item->product->images)
                                                    <img src="{{ asset('storage/' . $item->product->images) }}"
                                                        alt="{{ $item->product_name }}"
                                                        class="w-full h-full object-cover">
                                                @else
                                                    <div
                                                        class="w-full h-full flex items-center justify-center bg-indigo-100">
                                                        <svg class="w-8 h-8 text-indigo-300" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex-grow">
                                                <div class="text-sm md:text-lg font-medium text-gray-800">
                                                    {{ $item->product_name }}</div>
                                                <div class="flex justify-between mt-2">
                                                    <div class="text-sm text-gray-600">
                                                        <span
                                                            class="bg-indigo-100 text-indigo-800 text-[0.6rem] md:text-xs font-medium px-2 py-1 rounded">
                                                            Rp {{ number_format($item->price, 0, ',', '.') }}
                                                        </span>
                                                        <span class="mx-1 md:mx-2 text-gray-800">×</span>
                                                        <span
                                                            class="bg-purple-100 text-purple-800 text-[0.6rem] md:text-xs font-medium px-2 py-1 rounded">
                                                            {{ $item->quantity }}
                                                        </span>
                                                    </div>
                                                    <div class="text-[0.75rem] md:text-sm font-medium text-indigo-600">
                                                        Rp {{ number_format($item->total_price, 0, ',', '.') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Order Summary -->
                            <div class="border-t border-dashed pt-2 md:pt-6">
                                <h3 class="text-md md:text-lg font-semibold text-gray-800 mb-4 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                    Ringkasan Pembayaran
                                </h3>
                                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 p-4 rounded-lg">
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600">Subtotal</span>
                                        <span class="text-sm font-medium text-green-500">Rp
                                            {{ number_format($order->items->sum('total_price'), 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between mb-2">
                                        <span class="text-sm text-gray-600">Ongkos Kirim</span>
                                        <span class="text-sm font-medium text-green-500">Rp
                                            {{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                                    </div>
                                    @if ($order->discount_amount > 0)
                                        <div class="flex justify-between mb-2">
                                            <span class="text-sm text-gray-600">Diskon</span>
                                            <span class="text-sm text-red-500 font-medium"> Rp
                                                {{ number_format($order->discount_amount, 0, ',', '.') }} </span>
                                        </div>
                                    @endif
                                    <div class="border-t border-gray-200 mt-2 pt-2">
                                        <div class="flex justify-between font-bold text-lg">
                                            <span class="text-[0.92rem] text-gray-800">Total</span>
                                            <span class="text-[0.92rem] text-indigo-700">Rp
                                                {{ number_format($order->total_cost, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Action Buttons - IMPROVED SECTION -->
                            <div class="mt-6 flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                                @if ($order->payment_status == 'pending')
                                    <!-- Pending Order Buttons -->
                                    <button type="button" id="pay-button-{{ $order->id }}"
                                        class="flex items-center justify-center bg-green-600 hover:bg-green-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md w-full"
                                        data-order-id="{{ $order->order_id }}">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                            </path>
                                        </svg>
                                        Checkout Sekarang
                                    </button>
                                    <form action="{{ route('order.cancel', $order->id) }}" method="POST"
                                        class="w-full"
                                        onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="flex items-center justify-center bg-red-600 hover:bg-red-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md w-full">
                                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                            Batalkan Pesanan
                                        </button>
                                    </form>
                                @else
                                    <!-- Paid Order Buttons -->
                                    <a href="{{ route('invoice.pdf', $order->order_id) }}"
                                        class="flex items-center justify-center bg-indigo-600 hover:bg-indigo-700 text-white font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md w-full">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                            </path>
                                        </svg>
                                        Download Invoice
                                    </a>
                                    <a href="{{ route('home') }}"
                                        class="flex items-center justify-center bg-white border border-gray-300 text-gray-700 hover:bg-gray-50 font-medium px-6 py-3 rounded-lg transition duration-300 shadow-md w-full">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                                            </path>
                                        </svg>
                                        Kembali ke Beranda
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <!-- Back to top button -->
        <button id="backToTopBtn" type="button"
            class="fixed bottom-5 right-5 bg-indigo-600 text-white w-12 h-12 rounded-full shadow-lg flex items-center justify-center hover:bg-indigo-700 transition duration-300 opacity-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18">
                </path>
            </svg>
        </button>

        <script>
            // Back to top button functionality
            const backToTopBtn = document.getElementById('backToTopBtn');

            window.addEventListener('scroll', () => {
                if (window.scrollY > 300) {
                    backToTopBtn.classList.remove('opacity-0');
                    backToTopBtn.classList.add('opacity-100');
                } else {
                    backToTopBtn.classList.remove('opacity-100');
                    backToTopBtn.classList.add('opacity-0');
                }
            });

            backToTopBtn.addEventListener('click', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        </script>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all payment buttons (there might be multiple orders)
            const payButtons = document.querySelectorAll('[id^="pay-button-"]');

            payButtons.forEach(payButton => {
                payButton.addEventListener('click', function() {
                    // Disable button to prevent multiple clicks
                    this.disabled = true;
                    this.innerHTML = '<span class="animate-pulse">Memproses...</span>';

                    // Get order_id from data attribute
                    const orderId = this.getAttribute('data-order-id');

                    // Send request to get snap token
                    fetch(`/checkout/process/${orderId}`, {
                            method: 'GET',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Show Snap Payment Page
                                snap.pay(data.snap_token, {
                                    onSuccess: function(result) {
                                        window.location.href = '/orders/success?order_id=' + data.order_id;
                                    },
                                    onPending: function(result) {
                                        alert('Pembayaran dalam proses. Silahkan selesaikan pembayaran Anda.');
                                        window.location.href = '/orders';
                                    },
                                    onError: function(result) {
                                        alert('Pembayaran gagal. Silahkan coba lagi.');
                                        enableButton(payButton);
                                    },
                                    onClose: function() {
                                        enableButton(payButton);
                                    }
                                });
                            } else {
                                alert('Gagal memproses pembayaran: ' + data.message);
                                enableButton(payButton);
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            alert('Terjadi kesalahan saat memproses pembayaran');
                            enableButton(payButton);
                        });
                });
            });

            // Helper function to reset button state
            function enableButton(button) {
                button.disabled = false;
                button.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Checkout Sekarang
                `;
            }
        });
    </script>

</body>

</html>