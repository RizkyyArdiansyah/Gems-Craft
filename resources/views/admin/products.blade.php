<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard</title>
    @vite('resources/css/app.css')
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        .no-transition * {
            transition: none !important;
        }

        [x-cloak] {
            display: none !important;
        }
        body::-webkit-scrollbar {
            display: none;
            /* untuk Chrome, Safari, Edge */
        }
    </style>
</head>

<body class="bg-blue-200 select-none">
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 2000)" x-show="show" x-transition
            class="fixed top-4 right-4 bg-green-500 text-white px-3 py-1 rounded shadow-lg z-50 flex">
            {{ session('success') }}
            <svg class="text-white my-auto ml-1 size-5 animate-pulse" xmlns="http://www.w3.org/2000/svg" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>
    @endif

    @include('components.sidebar')
    <div id="contentContainer" class="flex-1 no-transition bg-blue-200 duration-300 ease-in-out">
        <div class="flex w-full bg-white mb-4 py-3 px-4">
            <h1
                class="text-2xl font-bold bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                Product</h1>
            <h1
                class="ml-2 font-bold select-none text-2xl bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                Management</h1>
        </div>

        <div x-data="{ showAddModal: false }">
            <div class="flex justify-end mb-2">
                <button @click="showAddModal = true"
                    class="bg-green-500 text-white px-2 py-2 md:px-3  rounded-md hover:bg-green-600 flex items-center mr-2 font-semibold ">
                    <p class="mr-1 text-xs md:text-[0.9rem]">Add Product</p>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-4 md:size-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>

                </button>
            </div>
            <div x-cloak x-data="editProductModal()" x-show="showAddModal"
                class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50">
                <div class="bg-white p-6 rounded-lg shadow-lg md:w-full max-w-md w-72">
                    <h2 class="text-md text-center font-bold mb-4">Add New Product</h2>
                    <hr class="border border-slate-800 mb-4">
                    </hr>
                    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                        @submit="formatAddBeforeSubmit()">
                        @csrf
                        <div class="mb-4">
                            <label for="product_name" class="block text-sm font-medium">Product Name</label>
                            <input type="text" name="product_name" id="product_name" required
                                class="w-full border border-slate-500 p-2 rounded mt-1">
                        </div>
                        <div class="mb-4">
                            <label for="price" class="block text-sm font-medium">Price</label>
                            <input type="text" name="price" id="price" required
                                class="w-full border border-slate-500 p-2 rounded mt-1"
                                oninput="formatRupiahInput(this)" placeholder="Rp 0">
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium">Category</label>
                            <input type="text" name="category" id="category" required
                                class="w-full border border-slate-500 p-2 rounded mt-1">
                        </div>
                        <div class="mb-4">
                            <label for="stock" class="block text-sm font-medium">Stock</label>
                            <input type="number" name="stock" id="stock" required
                                class="w-full border border-slate-500 p-2 rounded mt-1">
                        </div>
                        <div class="mb-4">
                            <label for="images" class="block text-sm font-medium">Image</label>
                            <input type="file" name="images" id="images" @change="previewImage"
                                class="w-full border border-slate-500 p-2 rounded mt-1 mb-2" required>
                            <template x-if="imagePreview">
                                <img :src="imagePreview" alt="Preview" class="h-32 object-contain border rounded">
                            </template>
                        </div>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 bg-gray-600 hover:bg-red-500 text-white rounded">
                                Cancel
                            </button>
                            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">
                                Save Product
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="bg-white shadow-lg mx-2 rounded-lg overflow-x-auto" x-data="editProductModal()">
            <div x-data="imageModal()">
                <table class="w-full rounded-2xl border-collapse text-[0.5rem] md:text-[0.8rem]">
                    <thead>
                        <tr class="bg-blue-100 select-none">
                            <th class="py-2 px-4 border text-center">No</th>
                            <th class="py-2 px-4 border text-center">Product</th>
                            <th class="py-2 px-4 border text-center">Price</th>
                            <th class="py-2 px-4 border text-center">Category</th>
                            <th class="py-2 px-4 border text-center">Stock</th>
                            <th class="py-2 px-4 border text-center">Image</th>
                            <th class="py-2 px-4 border text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr class="border-b">
                                <td class="py-2 px-4 text-center border">{{ $loop->iteration }}</td>
                                <td class="py-2 px-4 text-center border capitalize">{{ $product->product_name }}</td>
                                <td class="py-2 px-4 text-center border">Rp
                                    {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="py-2 px-4 text-center border">{{ $product->category }}</td>
                                <td class="py-2 px-4 text-center border">{{ $product->stock }}</td>
                                <td class="py-2 px-4 border">
                                    <img @click="openImageModal('{{ asset('storage/' . $product->images) }}')"
                                        class="w-[6rem] md:h-16 m-auto cursor-pointer hover:opacity-80 transition"
                                        src="{{ asset('storage/' . $product->images) }}"
                                        alt="{{ $product->product_name }}"></img>
                                </td>
                                <td class="py-2 px-3 border">
                                    <div class="flex flex-col md: text-center gap-y-2 md:gap-y-4">
                                        <button
                                            @click="openEditModal({ 
                                        id: {{ $product->id }}, 
                                        product_name: '{{ $product->product_name }}', 
                                        price: {{ $product->price }},
                                        priceFormatted: formatRupiah('{{ $product->price }}'), 
                                        category: '{{ $product->category }}',
                                        stock: {{ $product->stock }},  
                                    })"
                                            class="bg-blue-500 hover:bg-blue-700 rounded-md mx-auto w-5 md:w-[4rem] h-5 md:h-7 text-slate-50">
                                            <svg class="mx-auto size-4 md:size-5" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 32 32">
                                                <path fill="currentColor"
                                                    d="M27.87 7.863L23.024 4.82l-7.89 12.566l4.843 3.04zM14.395 21.25l-.107 2.855l2.527-1.337l2.35-1.24l-4.673-2.936zM29.163 3.24L26.63 1.647a1.364 1.364 0 0 0-1.88.43l-1 1.588l4.843 3.042l1-1.586c.4-.64.21-1.483-.43-1.883zm-3.965 23.82c0 .275-.225.5-.5.5h-19a.5.5 0 0 1-.5-.5v-19a.5.5 0 0 1 .5-.5h13.244l1.884-3H5.698c-1.93 0-3.5 1.57-3.5 3.5v19c0 1.93 1.57 3.5 3.5 3.5h19c1.93 0 3.5-1.57 3.5-3.5V11.097l-3 4.776v11.19z" />
                                            </svg>
                                        </button>

                                        <form id="delete-form-{{ $product->id }}"
                                            action="{{ route('products.delete', $product->id) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" onclick="confirmDelete({{ $product->id }})"
                                                class="bg-red-500 hover:bg-red-700 rounded-md mx-auto w-5 md:w-[4rem] h-5 md:h-7 text-slate-50">
                                                <svg class="mx-auto size-4 md:size-5"
                                                    xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">Belum ada produk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">{{ $products->links() }}</div>


                <div x-cloak x-show="showImage"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-70 z-50 backdrop-blur-sm"
                    x-cloak>
                    <div class="w-[80%] md:w-1/2 bg-white rounded-xl p-3 shadow-xl relative">
                        <button @click="showImage = false" class="absolute -top-2.5 -right-2 font-bold">❌</button>
                        <!-- Modal image display -->
                        <img :src="imageUrl" alt="Gambar Produk" class="w-full h-auto rounded-lg" />
                    </div>
                </div>



                <div x-cloak x-show="show" style="display: none;"
                    class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 backdrop-blur-sm">
                    <div class="bg-white w-72 md:w-96 p-6 rounded-lg">
                        <h2 class="text-xl font-bold mb-4 text-center">Update Product</h2>
                        <form :action="`{{ route('products.update', '') }}/${product.id}`" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label class="block text-sm mb-1">Product Name</label>
                                <input type="text" name="product_name" x-model="product.product_name"
                                    class="border border-slate-500 w-full p-2 rounded capitalize" required>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm mb-1">Price</label>
                                <input type="text" name="price" x-model="product.priceFormatted"
                                    @input="formatInputRupiah()" class="border border-slate-500 w-full p-2 rounded"
                                    required>
                                <input type="hidden" name="price" :value="product.price">
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm mb-1">Category</label>
                                <input type="text" name="category" x-model="product.category"
                                    class="border border-slate-500 w-full p-2 rounded capitalize" required>
                            </div>
                            <div class="mb-3">
                                <label class="block text-sm mb-1">Stock</label>
                                <input type="number" name="stock" x-model="product.stock"
                                    class="border border-slate-500 w-full p-2 rounded" required>
                            </div>
                            <div class="flex flex-col mb-3">
                                <label class="block text-sm mb-1">Image</label>
                                <div class="flex gap-x-2">
                                    <input type="file" name="images"
                                        class="border border-slate-500 w-full h-12 p-2 rounded">
                                </div>
                            </div>
                            <div class="flex justify-end gap-2 mt-4">
                                <button type="button" @click="close()"
                                    class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-red-500">Cancel</button>
                                <button type="submit"
                                    class="px-4 py-2 bg-green-500 hover:bg-green-700 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function confirmDelete(id) {
                Swal.fire({
                    title: 'Delete this product?',
                    text: "This product will be permanently deleted!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    customClass: {
                        popup: 'minimal-swal-popup',
                        title: 'text-gray-800 text-lg font-semibold',
                        htmlContainer: 'text-gray-500 text-sm',
                        confirmButton: 'bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded shadow-none',
                        cancelButton: 'bg-gray-300 hover:bg-gray-400 text-gray-700 px-3 py-1.5 rounded shadow-none',
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });

            }
        </script>


        <script>
            function imageModal() {
                return {
                    showImage: false,
                    imageUrl: '',
                    openImageModal(url) {
                        this.imageUrl = url;
                        this.showImage = true;
                    }
                }
            }

            function editProductModal() {
                return {
                    show: false,
                    product: {
                        id: null,
                        product_name: '',
                        price: 0,
                        priceFormatted: '',
                        category: '',
                        stock: 0,
                        images: ''
                    },
                    openEditModal(product) {
                        this.product = {
                            ...product,
                            priceFormatted: this.formatRupiah(product.price.toString())
                        };
                        this.show = true;
                    },
                    close() {
                        this.show = false;
                        this.product = {
                            id: null,
                            product_name: '',
                            price: 0,
                            priceFormatted: '',
                            category: '',
                            stock: 0,
                            images: ''
                        };
                    },
                    formatRupiah(angka) {
                        let number_string = angka.replace(/[^,\d]/g, '').toString();
                        let split = number_string.split(',');
                        let sisa = split[0].length % 3;
                        let rupiah = split[0].substr(0, sisa);
                        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

                        if (ribuan) {
                            let separator = sisa ? '.' : '';
                            rupiah += separator + ribuan.join('.');
                        }

                        rupiah = split[1] != undefined ? rupiah + ',' + split[1] : rupiah;
                        return rupiah ? 'Rp ' + rupiah : '';
                    },
                    unformatRupiah(rupiah) {
                        return rupiah.replace(/[^0-9]/g, '');
                    },
                    formatInputRupiah() {
                        const raw = this.unformatRupiah(this.product.priceFormatted);
                        this.product.price = parseInt(raw) || 0;
                        this.product.priceFormatted = this.formatRupiah(raw);
                    }
                }
            }
        </script>



        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const sidebar = document.getElementById('sidebar');
                const toggleButton = document.getElementById('toggleSidebar');
                const closeIcon = document.getElementById('closeIcon');
                const openIcon = document.getElementById('openIcon');
                const linkTexts = document.querySelectorAll('.link-text');
                const contentContainer = document.getElementById('contentContainer');

                function applySidebarState(isClosed) {
                    sidebar.classList.toggle('w-12', isClosed);
                    sidebar.classList.toggle('w-48', !isClosed);
                    closeIcon.classList.toggle('hidden', isClosed);
                    openIcon.classList.toggle('hidden', !isClosed);
                    contentContainer.classList.toggle('ml-48', !isClosed);
                    contentContainer.classList.toggle('ml-12', isClosed);
                    linkTexts.forEach(link => {
                        link.classList.toggle('hidden', isClosed);
                    });
                }

                const savedState = localStorage.getItem('sidebarClosed') === 'true';
                applySidebarState(savedState);

                // Setelah state diterapkan, hilangkan no-transition biar gak animasi pas load
                setTimeout(() => {
                    sidebar.classList.remove('no-transition');
                    contentContainer.classList.remove('no-transition');
                }, 50);

                toggleButton.addEventListener('click', () => {
                    const isClosed = sidebar.classList.contains('w-12');
                    applySidebarState(!isClosed);
                    localStorage.setItem('sidebarClosed', !isClosed);
                });
            });
        </script>
</body>

</html>
