<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Cart</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="p-4 w-full">
        <div class="container mx-auto mt-5 rounded-md bg-slate-200 select-none">
            <div class="flex flex-row justify-between items-center">
                <h1 class="text-xs font-bold mt-2 ml-7 py-2 md:text-[1.1rem]">Shopping Cart</h1>
                @include('components/icons.progress', ['currentStep' => 'Cart'])
                <button id="clear-cart-btn"
                    class="flex justify-center items-center text-[11px] w-40 h-7 md:text-sm bg-slate-50 rounded-lg md:w-28 mr-7 text-black hover:bg-red-600 hover:text-slate-50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9L14.394 18m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0A48.108 48.108 0 0 0 15.75 5.393M3.478 5.79a48.108 48.108 0 0 1 3.478-.397m7.5 0V4.477c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                    </svg>
                    <span class="hidden md:inline-block ml-2">Clear Cart</span>
                </button>
            </div>

            <div class="px-3 py-2 md:p-4 w-full">
                <div class="container p-2.5 md:p-3">
                    <hr class="border-3 border-slate-400 shadow-xl">
                    @if ($cartItems->count() > 0)
                        <table class="w-full border-collapse md:table-fixed">
                            <thead>
                                <tr class="capitalize text-center text-sm">
                                    <td class="border w-[23%] md:w-28"></td>
                                    <td class="border p-2 text-xs w-32">Product</td>
                                    <td class="border p-2 text-xs w-28">Price</td>
                                    <td class="border p-2 text-xs w-32">Quantity</td>
                                    <td class="border p-2 text-xs w-32">Total</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cartItems as $item)
                                    <tr class="capitalize text-center">
                                        <td class="border px-0.5 md:w-28 md:h-40">
                                            <img src="{{ asset('storage/' . $item->product->images) }}" alt="Product Image" class="mx-auto w-[50%] md:w-24">
                                        </td>
                                        <td class="border p-2 w-32 text-[0.55rem] md:text-sm md:font-semibold">
                                            {{ $item->product->product_name }}
                                        </td>
                                        <td class="border p-2 w-28 text-[0.55rem] md:text-sm">
                                            Rp {{ number_format($item->product->price, 0, ',', '.') }}
                                        </td>
                                        <td class="border w-16 md:w-32 md:text-lg">

                                            <form action="{{ route('cart.updateQuantity') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                                <input type="hidden" name="action" value="decrement">
                                                <button type="submit">-</button>
                                            </form>
                                            <span class="px-2 md:px-4 text-[0.55rem] md:text-[0.80rem]">{{ $item->quantity }}</span>
                                            <form action="{{ route('cart.updateQuantity') }}" method="POST"
                                                style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="cart_id" value="{{ $item->id }}">
                                                <input type="hidden" name="action" value="increment">
                                                <button type="submit">+</button>
                                            </form>
                                        </td>
                                        <td class="border p-2 w-30 text-[0.55rem] md:text-sm font-semibold">
                                            Rp {{ number_format($item->product->price * $item->quantity, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex flex-row justify-between mt-3">
                            <a href="/product"
                                class="text-[0.6rem] md:text-xs bg-white md:w-28 font-semibold border-black rounded-md hover:bg-blue-400 hover:text-white p-2">←
                                Back to shop</a>
                            <a href="/shipping"
                                class="text-[0.6rem] md:text-xs bg-white md:w-50 font-semibold rounded-md hover:bg-blue-400 hover:text-white p-2">Continue
                                to Shipping →</a>
                        </div>
                    @else
                        <div class="flex container justify-center items-center p-20">
                            <p class="text-gray-600 text-center text-2xl font-bold">Cart is empty.</p>
                        </div>
                        <a href="/product"
                            class="text-xs bg-white w-28 rounded-md hover:bg-blue-400 hover:text-white p-2">← Back to
                            shop</a>
                    @endif
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $(".update-quantity").click(function(e) {
                    e.preventDefault(); // Mencegah reload halaman

                    let cartId = $(this).data("cart-id");
                    let action = $(this).data("action");
                    let button = $(this); // Simpan referensi tombol

                    $.ajax({
                        url: "{{ route('cart.updateQuantity') }}",
                        method: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            cart_id: cartId,
                            action: action
                        },
                        success: function(response) {
                            if (response.success) {
                                if (response.quantity > 0) {
                                    button.closest(".cart-item").find(".quantity").text(response
                                        .quantity);
                                    button.closest(".cart-item").find(".total-price").text("Rp " +
                                        response.total_price);
                                } else {
                                    button.closest(".cart-item")
                                        .remove(); // Hapus jika quantity = 0
                                }
                            }
                        }
                    });
                });
            });
        </script>


        <script>
            document.getElementById("clear-cart-btn").addEventListener("click", function() {
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This product will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#e3342f',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, delete it!',
                    reverseButtons: true,
                    customClass: {
                        popup: 'tailwind-swal-popup',
                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-black px-4 py-2 rounded'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });

            });
        </script>
    </div>
</body>

</html>
