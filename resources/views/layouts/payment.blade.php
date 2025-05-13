<!DOCTYPE html>
<html lang="en">

<head>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        @vite('resources/css/app.css')
        <title>Payment</title>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

        <style>
            /* Awal halaman dalam keadaan transparan dan sedikit kecil */
            .fade-enter {
                opacity: 0;
                transform: scale(0.95) translateY(10px);
            }

            /* Saat halaman dimuat, kembali ke normal */
            .fade-enter-active {
                opacity: 1;
                transform: scale(1) translateY(0);
                transition: opacity 0.6s ease-out, transform 0.6s ease-out;
            }
        </style>



    </head>

<body x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
    <div class="p-4 w-full">
        <div class="container mx-auto mt-5 rounded-md bg-slate-100 select-none">
            <div class="flex flex-row justify-between items-center select-none">
                <h1 class="text-xl font-bold ml-4 py-2">Payment</h1>
                @include('components/icons.progress', ['currentStep' => 'Payment'])
                <div onclick="toggleModal()"
                    class="text-start font-semibold text-[0.8rem] md:text-sm mt-3 bg-white w-32 md:w-32 rounded-md hover:bg-blue-400 hover:text-slate-50 p-2 mr-4 md:mr-8 cursor-pointer">
                    <span>
                        <— <span class="ml-1 md:ml-2">Back
                    </span></span>
                </div>
            </div>

            <div x-bind:class="{
                {{-- 'opacity-0 translate-x-full': !
                    show,
                'opacity-100 translate-x-0 transition-all duration-700 ease-out': show --}}
            }" class="p-4 ">
                <div class="flex flex-col items-center justify-center md:items-start md:flex-row gap-x-2 gap-y-2">
                    <!-- Container Kiri: Summary Cart -->
                    <div class="w-full bg-white p-4 rounded-md shadow md:w-[43%]">
                        <div class="mb-4">
                            <h2 class="text-xs md:text-md font-semibold">Summary Cart</h2>
                            <p class="text-[0.5rem]">You have {{ count($cartItems) }} items in your cart.</p>
                        </div>

                        <div class="space-y-2">
                            @foreach ($cartItems as $item)
                                <div class="flex items-center justify-between text-xs border-b pb-2">
                                    <!-- Gambar Produk -->
                                    <img src="{{ asset('storage/' . $item->product->images) }}" alt="Product Image"
                                        width="100" class="w-12 h-8 rounded">

                                    <!-- Nama Produk -->
                                    <span
                                        class="w-[50%] ml-3 text-ellipsis capitalize text-[0.7rem]">{{ $item->product->product_name }}</span>

                                    <span class="text-[0.7rem] md:text-[0.6rem] text-center w-8 md:w-10">x
                                        {{ $item->quantity }} </span>

                                    <!-- Total Harga Produk -->
                                    <span class="font-semibold text-[0.6rem] w-20 text-end">Rp
                                        {{ number_format($item->product->price, 0, ',', '.') }}</span>
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Keseluruhan -->
                        <div class="flex flex-row justify-between text-xs py-3">
                            <div class="flex flex-col gap-y-2 font-semibold w-1/2">
                                <p>Subtotal</p>
                                <p>Shipping Cost (+)</p>
                                <p>Discount (-)</p>
                            </div>
                            <div class="flex flex-col gap-y-1.5 font-semibold w-1/2 text-end text-[0.65rem] ">
                                <div class="">
                                    Rp
                                    {{ number_format($cartTotal, 0, ',', '.') }}
                                </div>
                                <div id="cost-display" class="text-tight">
                                    @if (session('shipping.cost'))
                                        <span class="font-semibold">Rp
                                            {{ number_format(session('shipping.cost'), 0, '.', '.') }}</span>
                                    @else
                                        <span class="font-semibold mr-4">-</span>
                                    @endif
                                </div>
                                <div class="">
                                    @empty($discountAmount)
                                        <span class="font-semibold mr-4">-</span>
                                    @else
                                        <span class="font-semibold">Rp
                                            {{ number_format($discountAmount, 0, ',', '.') }}</span>
                                    @endempty
                                </div>
                            </div>

                        </div>
                        <form method="GET" action="{{ route('payment.index') }}" class="mb-4 mt-4">
                            <label for="discount_code" class="block text-sm font-medium text-gray-700">Enter
                                Discount Code:</label>
                            <div class="flex items-center mt-1">
                                <input type="text" id="discount_code" name="discount_code"
                                    value="{{ request('discount_code') }}"
                                    class="w-full p-2 border border-gray-500 h-7 rounded-md focus:ring-blue-500 focus:border-blue-500">
                                <button type="submit"
                                    class="ml-2 px-3 bg-blue-500 text-white h-7 text-xs hover:font-semibold rounded-md hover:bg-blue-600">
                                    Apply
                                </button>
                            </div>
                            @if (session('error_message'))
                                <div id="discount-error" class="bg-red-500 text-white p-2 rounded-md mt-2 text-sm">
                                    {{ session('error_message') }}
                                </div>
                            @endif

                            @if (session('invalid_message'))
                                <div id="discount-invalid" class="bg-yellow-500 text-white p-2 rounded-md mt-2 text-sm">
                                    {{ session('invalid_message') }}
                                </div>
                            @endif

                            <script>
                                // ✅ Pesan akan hilang dalam 3 detik
                                setTimeout(() => {
                                    document.getElementById('discount-error')?.classList.add('hidden');
                                    document.getElementById('discount-invalid')?.classList.add('hidden');
                                }, 3000);
                            </script>
                        </form>
                    </div>
                    <!-- Container Kanan: User Shipping Details -->
                    <div class="w-full bg-white p-4 rounded-md shadow md:w-[53%]">
                        <!-- Shipping Details -->
                        <div x-data="{ openShipping: true, openPayment: true }">
                            <!-- Shipping Details -->
                            <div class="bg-white p-3 rounded-md shadow-md md:w-full">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-xs md:text-md font-semibold text-red-600">Shipping Details</h2>
                                    <button @click="openShipping = !openShipping"
                                        class="text-xs font-semibold text-blue-500">
                                        <span x-text="openShipping ? 'Hide' : 'Show'"></span>
                                    </button>
                                </div>
                                <div x-show="openShipping" class="space-y-2 text-xs mt-3">
                                    <div class="container gap-y-3 text-xs">
                                        @php
                                            $shippingDetails = [
                                                'Name' => session('shipping.name', 'Not provided'),
                                                'Email' => session('shipping.email', 'Not provided'),
                                                'Phone' => session('shipping.phone', 'Not provided'),
                                                'Province' => session('shipping.province_name', 'Not provided'),
                                                'City' => session('shipping.city_name', 'Not provided'),
                                                'Address' => session('shipping.address', 'Not provided'),
                                                'Courier' => session('shipping.courier', 'Not provided'),
                                                'Service' => session('shipping.service', 'Not provided'),
                                            ];
                                        @endphp

                                        @foreach ($shippingDetails as $label => $value)
                                            <div class="flex flex-row py-1">
                                                <div class="flex flex-row items-center">
                                                    <span class="font-semibold w-20 ">{{ $label }}</span>
                                                    <span class="font-semibold">:</span>
                                                </div>
                                                <span class="text-gray-600 ml-2">{{ $value }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>


                            <!-- Payment Details -->
                            <div class="bg-white p-3 rounded-md shadow-md md:w-full mt-3">
                                <div class="flex justify-between items-center">
                                    <h2 class="text-xs md:text-md font-semibold text-red-600">Payment Details</h2>
                                    <button @click="openPayment = !openPayment"
                                        class="text-xs font-semibold text-blue-500">
                                        <span x-text="openPayment ? 'Hide' : 'Show'"></span>
                                    </button>
                                </div>
                                <div x-show="openPayment" class="space-y-2 text-xs mt-2">
                                    <div class="flex flex-row">
                                        <div class="flex flex-row w-20 justify-between">
                                            <span class="font-semibold">Total Cost</span>
                                            <span class="font-semibold">:</span>
                                        </div>
                                        <div class="ml-2">
                                            <span class="font-bold">
                                                Rp
                                                {{ number_format($finalTotal, 0, ',', '.') }}
                                            </span>
                                        </div>


                                    </div>
                                    <button onclick="checkoutModal()"
                                        class="bg-blue-500 hover:bg-blue-700 hover:font-semibold text-white px-4 py-2 rounded">
                                        Checkout
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div id="warningModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-0 opacity-0 backdrop-blur-sm invisible transition-opacity duration-300">
                        <div
                            class="bg-white p-6 rounded-lg shadow-lg w-80 md:w-96 transform scale-95 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-20 m-auto text-red-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <h1 class="text-xl text-center font-bold mt-4 mb-10">Are you sure?</h1>
                            <p class="mb-10">You will be redirected to the shipping page, and your current shipping
                                details will be reset.</p>
                            <div class="flex justify-between space-x-2">
                                <button onclick="redirectToShipping()"
                                    class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Yes,
                                    Proceed</button>
                                <button onclick="toggleModal()"
                                    class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-32">Cancel</button>
                            </div>
                        </div>
                    </div>

                    <div id="checkoutModal"
                        class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-0 opacity-0 backdrop-blur-sm invisible transition-opacity duration-300">
                        <div
                            class="bg-white p-6 rounded-lg shadow-lg w-80 md:w-96 transform scale-95 transition-transform duration-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-20 m-auto text-red-400">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                            </svg>
                            <h1 class="text-xl text-center font-bold mt-4 mb-10">Ready to Checkout?</h1>
                            <p class="mb-10"> Before you proceed to checkout, please ensure that your shipping details
                                and selected items are correct.</p>
                            <form id="checkoutForm" action="{{ route('checkout') }}" method="POST">
                                @csrf
                                <div class="flex justify-between space-x-2">
                                    <button type="button" id="pay-button"
                                        class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">Yes,
                                        Proceed</button>
                                    <button type="button" onclick="checkoutModal()"
                                        class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded w-32">Cancel</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        function toggleModal() {
                            let modal = document.getElementById("warningModal");

                            if (modal.classList.contains("opacity-0")) {
                                // Buka modal (fade-in & slide-up)
                                modal.classList.remove("opacity-0", "invisible");
                                modal.classList.add("opacity-100", "bg-opacity-50");
                                modal.querySelector("div").classList.remove("scale-95");
                                modal.querySelector("div").classList.add("scale-100");
                            } else {
                                // Tutup modal (fade-out & slide-down)
                                modal.classList.remove("opacity-100", "bg-opacity-50");
                                modal.classList.add("opacity-0", "invisible");
                                modal.querySelector("div").classList.remove("scale-100");
                                modal.querySelector("div").classList.add("scale-95");
                            }
                        }

                        function checkoutModal() {
                            let modal = document.getElementById("checkoutModal");

                            if (modal.classList.contains("opacity-0")) {
                                // Buka modal (fade-in & slide-up)
                                modal.classList.remove("opacity-0", "invisible");
                                modal.classList.add("opacity-100", "bg-opacity-50");
                                modal.querySelector("div").classList.remove("scale-95");
                                modal.querySelector("div").classList.add("scale-100");
                            } else {
                                // Tutup modal (fade-out & slide-down)
                                modal.classList.remove("opacity-100", "bg-opacity-50");
                                modal.classList.add("opacity-0", "invisible");
                                modal.querySelector("div").classList.remove("scale-100");
                                modal.querySelector("div").classList.add("scale-95");
                            }
                        }

                        function checkout() {
                            document.getElementById("checkoutForm").submit();
                        }

                        function redirectToShipping() {
                            // Tutup modal dulu sebelum pindah halaman
                            toggleModal();
                            // Redirect ke halaman shipping
                            window.location.href = "{{ route('shipping.reset') }}";
                        }
                    </script>

                    <script>
                        document.getElementById('pay-button').addEventListener('click', function() {
                            fetch('/checkout', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                    }
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        fetch('/checkout/payment', {
                                                method: 'POST',
                                                headers: {
                                                    'Content-Type': 'application/json',
                                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                                },
                                                body: JSON.stringify({
                                                    order_id: data.order_id
                                                }) // Kirim order_id ke Midtrans
                                            })
                                            .then(response => response.json())
                                            .then(paymentData => {
                                                console.log("Checkout Response:", data);
                                                console.log("Payment Request Sent:", {
                                                    order_id: data.order_id
                                                });
                                                console.log("Payment Data:", paymentData);

                                                if (paymentData.success) {
                                                    snap.pay(paymentData.snap_token, {
                                                        onSuccess: function(result) {
                                                            alert('Payment success');
                                                            window.location.href = `/invoice/${data.order_id}`;
                                                        },
                                                        onPending: function(result) {
                                                            alert("Payment pending: " + result.order_id);
                                                        },
                                                        onError: function(result) {
                                                            alert("Payment failed: " + result.status_message);
                                                        }
                                                    });
                                                } else {
                                                    alert("Failed to generate payment: " + paymentData.message);
                                                }
                                            })
                                            .catch(error => console.error('Error:', error));
                                    } else {
                                        alert("Checkout failed: " + data.message);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                        });
                    </script>


                    {{-- <script>
                        document.getElementById("checkoutBtn").addEventListener("click", function () {
                            // Ambil data dari form
                            let name = document.querySelector("input[name='name']").value;
                            let email = document.querySelector("input[name='email']").value;
                            let phone = document.querySelector("input[name='phone']").value;
                            let amount = {{ $finalTotal }}; // Total dari server (pastikan variabel ini tersedia)
                    
                            // Kirim request ke backend untuk mendapatkan snap_token
                            fetch("{{ route('midtrans.token') }}", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                                },
                                body: JSON.stringify({ name, email, phone, amount })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.snap_token) {
                                    // Tampilkan modal pembayaran Midtrans
                                    window.snap.pay(data.snap_token, {
                                        onSuccess: function(result) {
                                            alert("Payment Success!");
                                            console.log(result);
                                            window.location.href = "/order/success";
                                        },
                                        onPending: function(result) {
                                            alert("Waiting for payment!");
                                            console.log(result);
                                        },
                                        onError: function(result) {
                                            alert("Payment failed!");
                                            console.log(result);
                                        }
                                    });
                                }
                            })
                            .catch(error => console.error("Error:", error));
                        });
                    </script> --}}
                </div>
            </div>
        </div>
    </div>


</body>

</html>
