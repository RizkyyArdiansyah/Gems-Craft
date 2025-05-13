<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Finish Order</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x/dist/cdn.min.js" defer></script>

    <style>
        @media print {
            body * {
                visibility: hidden;
                /* Sembunyikan semua elemen */
            }

            #invoice-card,
            #invoice-card * {
                visibility: visible;
                /* Tampilkan hanya elemen invoice */
            }

            #invoice-card {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body x-data="{ show: false }" x-init="setTimeout(() => show = true, 200)">
    <div class="p-4 w-full">
        <div class="container mx-auto mt-5 rounded-md bg-slate-100 select-none">
            <div class="flex flex-row justify-between items-center select-none">
                <h1 class="text-xl font-bold ml-4 md:py-2">Invoice</h1>
                @include('components/icons.progress', ['currentStep' => 'Finish'])
                <a href="/product"
                    class="text-start font-semibold text-[0.66rem] md:text-sm mt-3 bg-white w-36 md:w-32 rounded-md hover:bg-red-500 hover:text-slate-50 p-2 mr-2 md:mr-8 cursor-pointer">
                    <span>⬅ <span class="ml-0.5 md:ml-2">Home</span></span>
                </a>
            </div>
            <div x-data="paymentSuccess" x-init="startTyping()"
                class="flex flex-col items-center justify-center mt-10 md:mt-20 text-center">
                <!-- Wrapper teks + ikon -->
                <div x-show="!showInvoice" class="flex flex-col items-center">
                    <!-- Teks dengan animasi -->
                    <h1 class="font-bold text-[0.7rem] md:text-md mx-8 text-slate-50 bg-slate-400 rounded-md p-3">
                        <span x-text="displayedText"></span>
                    </h1>

                    <!-- SVG tanda ceklis langsung muncul di tengah -->
                    <div class="flex justify-center">
                        <svg class="text-green-400 w-72 md:w-96 animate-pulse mt-4" xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                </div>

                <!-- Button untuk menampilkan invoice -->
                <button @click="showInvoice = true" x-show="!showInvoice"
                    class="mt-2 mb-6 px-4 py-2 bg-blue-500 text-white hover:font-semibold rounded-md hover:bg-blue-700 transition">
                    Cek Invoice
                </button>

                <div class="md:mb-2 mx-auto">
                    <svg x-show="showInvoice" onclick="printInvoice()"
                        class="ml-[15rem] mb-2 md:mb-0 md:ml-[34.6rem] w-6 hover:text-blue-400 cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0 1 10.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0 .229 2.523a1.125 1.125 0 0 1-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0 0 21 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 0 0-1.913-.247M6.34 18H5.25A2.25 2.25 0 0 1 3 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 0 1 1.913-.247m10.5 0a48.536 48.536 0 0 0-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5Zm-3 0h.008v.008H15V10.5Z" />
                    </svg>
                </div>
                <!-- Card Invoice -->
                <div id="invoice-card" x-show="showInvoice"
                    x-transition:enter="transition-opacity ease-out duration-500 transform"
                    x-transition:enter-start="opacity-0 translate-y-5"
                    x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition-opacity ease-in duration-300 transform"
                    x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-5"
                    class="w-3/4 md:w-[36rem] bg-white shadow-md rounded-lg p-4 m-auto text-left mb-10">
                    <div class="flex text-center items-center">
                        <img class="w-12 h-12 -ml-3 rounded-full" src="{{ asset('images/logoci.png') }}" alt="Logo">
                        <p class="mx-auto font-semibold">Gems Craft Invoice</p>
                    </div>
                    <!-- data tabel order -->

                    @if ($order->payment_status === 'paid')
                        <div class="flex flex-col">
                            <hr class="border-p border-slate-800 mb-1">
                            </hr>
                            <p class="text-[0.5rem] text-end">Order ID : <span
                                    id="invoice-order-id">{{ $order->order_id }}</span></p>
                        </div>
                        <div class="flex flex-col gap-y-1 mb-4">
                            <p class="text-[0.6rem]">Order Date : <span>{{ $order->created_at }} </p>
                            <p class="text-[0.6rem]">Status : <span
                                    class="text-green-500">{{ $order->payment_status }}</span></p>
                            <h2 class="text-[0.7rem] font-semibold mb-2 text-end">Shipping Detail </h2>
                            <div class="flex flex-col text-end">
                                <p class="text-[0.6rem]">{{ $order->name }}</p>
                                <p class="text-[0.6rem]">{{ $order->phone }}</p>
                                <p class="text-[0.6rem]">{{ $order->province }}, {{ $order->city_name }}</p>
                                <p class="text-[0.6rem]">{{ $order->address }}</p>
                            </div>
                        </div>
                        <h3 class="text-xs font-semibold mt-2">Order Items</h3>
                        <table class="w-full border-collapse border border-gray-300 mt-2 text-[0.6rem]">
                            <thead>
                                <tr class="bg-gray-200 text-center">
                                    <th class="p-2 border border-gray-300">Product</th>
                                    <th class="p-2 border border-gray-300">Qty</th>
                                    <th class="p-2 border border-gray-300">Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order->items as $item)
                                    <tr class="border-b">
                                        <td class="p-2 border border-gray-300">{{ $item->product_name }}</td>
                                        <td class="p-2 border border-gray-300 text-center">{{ $item->quantity }}
                                        </td>
                                        <td class="p-2 border text-center border-gray-300">Rp
                                            {{ number_format($item->price, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-col py-3 gap-y-2">
                            <p class="text-[0.6rem] text-start">Total Price : Rp <span
                                    id="invoice-total">{{ number_format($cartTotal, 0, ',', '.') }}</span>
                            </p>
                            <p class="text-[0.6rem] text-start">Shipping Price : Rp <span
                                    id="invoice-total">{{ number_format($order->shipping_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="text-[0.6rem] text-start">Discount : Rp <span
                                    id="invoice-total">{{ number_format($order->discount_amount, 0, ',', '.') }}</span>
                            </p>
                            <p class="text-[0.6rem] text-start">Subtotal : Rp <span
                                    id="invoice-total">{{ number_format($order->total_cost, 0, ',', '.') }}</span>
                            </p>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Alpine.js -->
            <script>
                document.addEventListener('alpine:init', () => {
                    Alpine.data('paymentSuccess', () => ({
                        fullText: "Thank you! Your payment has been successfully processed. Your order is now being prepared and will be shipped soon. If you have any questions, feel free to contact us. Happy shopping!",
                        displayedText: "",
                        index: 0,
                        showInvoice: false,


                        startTyping() {
                            let interval = setInterval(() => {
                                if (this.index < this.fullText.length) {
                                    this.displayedText += this.fullText[this.index];
                                    this.index++;
                                } else {
                                    clearInterval(interval);
                                }
                            }, 25);
                        }
                    }));
                });
            </script>
            <script>
                function printInvoice() {
                    window.print();
                }
            </script>









</body>

</html>
