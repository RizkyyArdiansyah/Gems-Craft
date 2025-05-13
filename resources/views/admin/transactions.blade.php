<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    @vite('resources/css/app.css')

    <style>
        .no-transition * {
            transition: none !important;
        }

        .fade-in-animation {
            animation: fadeIn 0.3s ease-in-out;
        }

        .scale-in-animation {
            animation: scaleIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                transform: scale(0.95);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }

        .spinner {
            border: 3px solid rgba(0, 0, 0, 0.1);
            border-radius: 50%;
            border-top: 3px solid #3498db;
            width: 24px;
            height: 24px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Custom scrollbar for modal content */
        .modal-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .modal-scrollbar::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e0;
            border-radius: 10px;
        }

        .modal-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #a0aec0;
        }

        /* Collapsible sections */
        .section-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }

        .section-content.open {
            max-height: 500px;
        }

        body::-webkit-scrollbar {
            display: none;
            /* untuk Chrome, Safari, Edge */
        }

    </style>
</head>

<body class="bg-gray-100">
    <div class="flex min-h-screen">
        @include('components.sidebar')

        <div id="contentContainer"
            class="w-full flex-1 transition-all duration-300 ease-in-out no-transition select-none">
            <div class="flex w-full bg-white shadow-sm mb-4 py-3 px-4">
                <h1
                    class="text-2xl font-bold bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                    Order</h1>
                <h1
                    class="ml-2 font-bold select-none text-2xl bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                    Lists</h1>
            </div>

            <div class="mx-2 md:mx-4 bg-white shadow-lg rounded-xl w-[19.5rem] md:w-auto">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-xs md:text-sm">
                        <thead>
                            <tr class="bg-blue-100">
                                <th class="py-2 px-3 md:px-4 border-b text-center">No</th>
                                <th class="py-2 px-3 md:px-4 border-b text-center">Order ID</th>
                                <th class="py-2 px-3 md:px-4 border-b text-center">Total Harga</th>
                                <th class="py-2 px-3 md:px-4 border-b text-center">Status</th>
                                <th class="py-2 px-3 md:px-4 border-b text-center">Tanggal</th>
                                <th class="py-2 px-3 md:px-4 border-b text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="py-2 px-3 md:px-4 border-b text-center">{{ $loop->iteration }}</td>
                                    <td class="py-2 px-3 md:px-4 border-b text-center truncate">{{ $order->order_id }}
                                    </td>
                                    <td class="py-2 px-3 md:px-4 border-b text-center">Rp
                                        {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                                    <td class="py-2 px-3 md:px-4 border-b text-center">
                                        <span
                                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            {{ $order->payment_status == 'paid'
                                                ? 'bg-green-100 text-green-800'
                                                : ($order->payment_status == 'pending'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-gray-100 text-gray-800') }}">
                                            {{ $order->payment_status }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 md:px-4 border-b text-center">
                                        {{ $order->created_at->format('d M Y') }}</td>
                                    <td class="py-2 px-3 md:px-4 border-b text-center">
                                        <button data-id="{{ $order->id }}"
                                            class="detail-btn inline-flex items-center px-2 py-1 border border-transparent rounded-md shadow-sm text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-150">
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-8 text-gray-500">Belum ada orderan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 px-4 py-2">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Detail Order - Improved for Mobile -->
    <div id="orderDetailModal"
        class="fixed inset-0 bg-black bg-opacity-50 hidden md:items-center justify-center z-50 p-1 md:p-4">
        <div
            class="bg-white my-8 w-[85%] max-w-md md:max-w-lg rounded-xl shadow-xl relative scale-in-animation mx-auto max-h-[75vh] flex flex-col overflow-hidden">
            <div class="flex justify-between items-center p-3 md:p-4 border-b sticky top-0 bg-white z-10">
                <h2 class="text-lg md:text-xl font-bold text-gray-800">Detail Order</h2>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div id="modalLoading" class="flex justify-center items-center py-12">
                <div class="spinner"></div>
                <span class="ml-2 text-gray-600">Loading...</span>
            </div>

            <div id="orderDetailContent" class="p-3 md:p-4 max-h-[70vh] overflow-y-auto hidden modal-scrollbar"></div>

            <div class="border-t p-3 md:p-4 flex justify-end sticky bottom-0 bg-white">
                <button id="closeModalBtn"
                    class="px-4 py-2 bg-red-600 text-white font-medium rounded-md hover:bg-slate-100 hover:text-black hover:outline hover:outline-red-500 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-150">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Sidebar functionality
            const sidebar = document.getElementById('sidebar');
            const toggleButton = document.getElementById('toggleSidebar');
            const closeIcon = document.getElementById('closeIcon');
            const openIcon = document.getElementById('openIcon');
            const linkTexts = document.querySelectorAll('.link-text');
            const contentContainer = document.getElementById('contentContainer');

            function applySidebarState(isClosed) {
                sidebar?.classList.toggle('w-12', isClosed);
                sidebar?.classList.toggle('w-48', !isClosed);
                closeIcon?.classList.toggle('hidden', isClosed);
                openIcon?.classList.toggle('hidden', !isClosed);
                contentContainer?.classList.toggle('ml-48', !isClosed);
                contentContainer?.classList.toggle('ml-12', isClosed);
                linkTexts.forEach(link => link.classList.toggle('hidden', isClosed));
            }

            const savedState = localStorage.getItem('sidebarClosed') === 'true';
            applySidebarState(savedState);

            setTimeout(() => {
                sidebar?.classList.remove('no-transition');
                contentContainer?.classList.remove('no-transition');
            }, 50);

            toggleButton?.addEventListener('click', () => {
                const isClosed = sidebar?.classList.contains('w-12');
                applySidebarState(!isClosed);
                localStorage.setItem('sidebarClosed', !isClosed);
            });

            // Modal Detail Order functionality
            const detailButtons = document.querySelectorAll('.detail-btn');
            const modal = document.getElementById('orderDetailModal');
            const modalContent = document.getElementById('orderDetailContent');
            const modalLoading = document.getElementById('modalLoading');
            const closeModalBtn = document.getElementById('closeModalBtn');
            const closeModalIcon = document.getElementById('closeModal');

            // Format currency
            const formatRupiah = (angka) => {
                return new Intl.NumberFormat('id-ID').format(angka);
            };

            // Get status badge classes
            const getStatusBadgeClasses = (status) => {
                switch (status.toLowerCase()) {
                    case 'paid':
                        return 'bg-green-100 text-green-800';
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-800';
                    default:
                        return 'bg-gray-100 text-gray-800';
                }
            };

            // Toggle section content
            const toggleSection = (sectionId) => {
                const content = document.getElementById(sectionId);
                content.classList.toggle('open');

                const icon = document.querySelector(`[data-section="${sectionId}"] svg`);
                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            };

            detailButtons.forEach(button => {
                button.addEventListener('click', async (e) => {
                    e.preventDefault();
                    const orderId = button.getAttribute('data-id');

                    // Show modal with loading state
                    modal.classList.remove('hidden');
                    modal.classList.add('flex', 'fade-in-animation');
                    modalContent.classList.add('hidden');
                    modalLoading.classList.remove('hidden');

                    try {
                        const response = await fetch(`/orders/ajax/${orderId}`);

                        if (!response.ok) {
                            throw new Error('Failed to fetch order details');
                        }

                        const data = await response.json();

                        // Build items HTML
                        let itemsHtml = '';
                        data.items.forEach(item => {
                            itemsHtml += `
                                <div class="flex justify-between border-b py-2">
                                    <div class="flex-1">
                                        <p class="font-medium">${item.product_name}</p>
                                        <p class="text-sm text-gray-600">Qty: ${item.quantity}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium">Rp ${(item.total_price)}</p>
                                    </div>
                                </div>
                            `;
                        });

                        // Build full modal content with collapsible sections
                        modalContent.innerHTML = `
                            <!-- Order Summary - Always visible -->
                            <div class="bg-blue-50 p-3 rounded-md mb-3">
                                <div class="mb-1 flex justify-between">
                                    <span class="text-blue-500">Order ID :</span>
                                    <span class="text-xs">${data.order_id}</span>
                                </div>
                                <div class="mb-1 flex justify-between">
                                    <span class="text-blue-500">Status :</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClasses(data.status)}">
                                        ${data.status}
                                    </span>
                                </div>
                                <div class="flex justify-between font-semibold">
                                    <span class="text-blue-500">Total :</span>
                                    <span>Rp ${(data.total)}</span>
                                </div>
                            </div>
                            
                            <!-- Collapsible Sections -->
                            <!-- Customer Information -->
                            <div class="mb-2 border rounded-md overflow-hidden">
                                <button class="w-full flex justify-between items-center p-3 bg-gray-50 hover:bg-gray-100 text-left font-medium focus:outline-none" 
                                        onclick="(function(){
                                            document.getElementById('customerSection').classList.toggle('open');
                                            this.querySelector('svg').classList.toggle('rotate-180');
                                        }).call(this)">
                                    <span>Customer Info</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="customerSection" class="section-content open">
                                    <div class="p-3">
                                        <div class="grid grid-cols-1 gap-2">
                                            <p class=""><span class="text-blue-500">Nama :</span> ${data.name}</p>
                                            <p class=""><span class="text-blue-500">Email :</span> ${data.email}</p>
                                            <p class=""><span class="text-blue-500">Telepon :</span> ${data.phone}</p>
                                            <p class=""><span class="text-blue-500">Tanggal :</span> ${data.tanggal}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Shipping Information -->
                            <div class="mb-2 border rounded-md overflow-hidden">
                                <button class="w-full flex justify-between items-center p-3 bg-gray-50 hover:bg-gray-100 text-left font-medium focus:outline-none" 
                                        onclick="(function(){
                                            document.getElementById('shippingSection').classList.toggle('open');
                                            this.querySelector('svg').classList.toggle('rotate-180');
                                        }).call(this)">
                                    <span>Shipping Info</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="shippingSection" class="section-content">
                                    <div class="p-3">
                                        <p class="mb-1"><span class="text-blue-500">Kurir :</span> ${data.courier} (${data.service})</p>
                                        <p class="mb-1"><span class="text-blue-500">Provinsi / Kota :</span> ${data.province}, ${data.city}</p>
                                        <p class="mb-1"><span class="text-blue-500">Alamat :</span> ${data.address}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Order Items -->
                            <div class="mb-2 border rounded-md overflow-hidden">
                                <button class="w-full flex justify-between items-center p-3 bg-gray-50 hover:bg-gray-100 text-left font-medium focus:outline-none" 
                                        onclick="(function(){
                                            document.getElementById('itemsSection').classList.toggle('open');
                                            this.querySelector('svg').classList.toggle('rotate-180');
                                        }).call(this)">
                                    <span>Order Items</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="itemsSection" class="section-content">
                                    <div class="p-3">
                                        ${itemsHtml || '<p class="text-gray-500 text-center py-2">No items found</p>'}
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Payment Details -->
                            <div class="mb-2 border rounded-md overflow-hidden">
                                <button class="w-full flex justify-between items-center p-3 bg-gray-50 hover:bg-gray-100 text-left font-medium focus:outline-none" 
                                        onclick="(function(){
                                            document.getElementById('paymentSection').classList.toggle('open');
                                            this.querySelector('svg').classList.toggle('rotate-180');
                                        }).call(this)">
                                    <span>Payment Details</span>
                                    <svg class="w-5 h-5 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </button>
                                <div id="paymentSection" class="section-content">
                                    <div class="p-3">
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">Subtotal:</span>
                                            <span>Rp ${(data.subtotal)}</span>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">Biaya Kirim:</span>
                                            <span>Rp ${(data.shipping_amount)}</span>
                                        </div>
                                        <div class="flex justify-between mb-1">
                                            <span class="text-gray-600">Diskon:</span>
                                            <span>Rp ${(data.discount_amount)}</span>
                                        </div>
                                        <div class="flex justify-between font-bold mt-2 pt-2 border-t">
                                            <span>Total:</span>
                                            <span>Rp ${(data.total)}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;

                        // Hide loading, show content
                        modalLoading.classList.add('hidden');
                        modalContent.classList.remove('hidden');

                    } catch (error) {
                        console.error('Error fetching order details:', error);
                        modalLoading.classList.add('hidden');
                        modalContent.classList.remove('hidden');
                        modalContent.innerHTML = `
                            <div class="bg-red-50 p-4 rounded-md">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-red-800">Error Loading Order Details</h3>
                                        <div class="mt-2 text-sm text-red-700">
                                            <p>Failed to load order details. Please try again later.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `;
                    }
                });
            });

            // Close modal functionality
            const closeModalFn = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex', 'fade-in-animation');
                modalContent.innerHTML = '';
            };

            closeModalBtn.addEventListener('click', closeModalFn);
            closeModalIcon.addEventListener('click', closeModalFn);

            // Close modal when clicking outside
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModalFn();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    closeModalFn();
                }
            });
        });
    </script>
</body>

</html>
