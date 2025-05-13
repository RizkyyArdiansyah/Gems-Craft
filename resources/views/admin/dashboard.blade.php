<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Dashboard | Gems Craft</title>
    @vite('resources/css/app.css')
    
    <!-- Chart.js CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    
    <style>
        .no-transition * {
            transition: none !important;
        }
        
        .card-stats {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .card-stats:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }
        
        .time-filter-btn {
            transition: all 0.3s ease;
        }
        
        .time-filter-btn.active {
            background-color: #2563eb;
            color: white;
        }
    </style>
</head>

<body class="bg-gray-50">
    <div class="flex">
        @include('components.sidebar')
        
        <!-- Content -->
        <div id="contentContainer" class="w-full no-transition ml-48 min-h-screen bg-gray-50 duration-300 ease-in-out">
            <!-- Header -->
            <div class="w-full bg-white shadow-sm mb-6 py-4 px-6">
                <div class="flex justify-between items-center">
                    <div class="flex items-center">
                        <h1 class="text-2xl font-bold text-gray-800">
                            Dashboard
                        </h1>
                        <h1 class="ml-2 font-bold select-none text-2xl bg-gradient-to-r from-cyan-500 via-yellow-500 to-amber-300 bg-clip-text text-transparent">
                            Gems Craft
                        </h1>
                    </div>
                    
                </div>
            </div>

            <div class="px-6 pb-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 select-none">
                    <!-- Card Total Transaksi -->
                    <div class="bg-white shadow-sm rounded-xl p-6 flex items-center card-stats border border-gray-100">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.93 12A8.054 8.054 0 1 1 12 5.07V3h-1a10 10 0 1 0 10 10v-1Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20.364 3.636A9 9 0 0 0 14 1v9h9a9 9 0 0 0-2.636-6.364M16 3.294A7.01 7.01 0 0 1 20.706 8H16Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Paid Transactions</p>
                            <h2 class="text-xl font-bold text-gray-800">Rp {{ number_format($totalPaidCost, 0, ',', '.') }}</h2>
                            <p class="text-green-500 text-xs font-medium mt-1">▲ {{ $totalTransaction }} completed transactions</p>
                        </div>
                    </div>
                    
                    
                    <!-- Card Total Produk -->
                    <div class="bg-white shadow-sm rounded-xl p-6 flex items-center card-stats border border-gray-100">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Products</p>
                            <h2 class="text-xl font-bold text-gray-800">{{ $totalProduct }}</h2>
                            <p class="text-green-500 text-xs font-medium mt-1">▲ {{ $totalProduct }} New products this month</p>
                        </div>
                    </div>
                    
                    <!-- Card Total Pelanggan -->
                    <div class="bg-white shadow-sm rounded-xl p-6 flex items-center card-stats border border-gray-100">
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-500 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Total Customers</p>
                            <h2 class="text-xl font-bold text-gray-800">{{ $totalUser }}</h2>
                            <p class="text-green-500 text-xs font-medium mt-1">▲ {{ $totalUser }} Active customers</p>
                        </div>
                    </div>
                    
                    <!-- Card Diskon Aktif -->
                    <div class="bg-white shadow-sm rounded-xl p-6 flex items-center card-stats border border-gray-100">
                        <div class="bg-gradient-to-r from-red-500 to-red-600 text-white p-3 rounded-xl">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 005.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 009.568 3z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z" />
                            </svg>
                        </div>
                        <div class="ml-4">
                            <p class="text-gray-500 text-sm font-medium">Active Discounts</p>
                            <h2 class="text-xl font-bold text-gray-800">{{ $totalDiscount }}</h2>
                            <p class="text-red-500 text-xs font-medium mt-1">▼ {{ $totalDiscount }} Recent discounts</p>
                        </div>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <!-- Sales Performance Chart -->
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100 lg:col-span-2">
                        <div class="flex flex-wrap justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Sales Performance</h3>
                            <div class="flex flex-wrap gap-2 mt-2 sm:mt-0">
                                <button class="time-filter-btn px-3 py-1 text-sm rounded-full border border-gray-200 hover:bg-blue-50" data-days="1">1 Day</button>
                                <button class="time-filter-btn px-3 py-1 text-sm rounded-full border border-gray-200 hover:bg-blue-50" data-days="3">3 Days</button>
                                <button class="time-filter-btn px-3 py-1 text-sm rounded-full border border-gray-200 hover:bg-blue-50 active" data-days="7">7 Days</button>
                                <button class="time-filter-btn px-3 py-1 text-sm rounded-full border border-gray-200 hover:bg-blue-50" data-days="30">30 Days</button>
                            </div>
                        </div>
                        <div class="chart-container">
                            <canvas id="salesChart"></canvas>
                        </div>
                    </div>

                    <!-- Top Products Chart -->
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Top Categories</h3>
                        <div class="chart-container">
                            <canvas id="productsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders and Customer Acquisition -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Recent Orders -->
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100 lg:col-span-2">
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-lg font-bold text-gray-800">Recent Orders</h3>
                            <a href="{{ route('transactions') }}" class="text-blue-500 text-sm hover:underline">View All</a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center tracking-wider">Order ID</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center tracking-wider">Customer</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center tracking-wider">Date</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center tracking-wider">Amount</th>
                                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center tracking-wider">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($recentOrders as $order)
                                    <tr>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs font-medium text-ellipsis text-gray-900">{{ $order->order_id }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700">{{ $order->user->name ?? 'Customer' }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700">{{ $order->created_at->format('M d, Y') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-xs text-gray-700">Rp {{ number_format($order->total_cost, 0, ',', '.') }}</td>
                                        <td class="px-4 py-3 whitespace-nowrap text-center">
                                            @if($order->payment_status == 'paid')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Paid</span>
                                            @else
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">{{ ucfirst($order->payment_status) }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Additional space for future components -->
                    <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                        <h3 class="text-lg font-bold text-gray-800 mb-6">Quick Stats</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">Monthly Sales</p>
                                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($totalPaidCost, 0, ',', '.') }}</p>
                                </div>
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div>
                                    <p class="text-sm text-gray-600">Total Orders</p>
                                    <p class="text-lg font-bold text-gray-800">{{ $totalTransaction }}</p>
                                </div>
                                <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
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

            // Remove no-transition after state is applied
            setTimeout(() => {
                sidebar.classList.remove('no-transition');
                contentContainer.classList.remove('no-transition');
            }, 50);

            toggleButton.addEventListener('click', () => {
                const isClosed = sidebar.classList.contains('w-12');
                applySidebarState(!isClosed);
                localStorage.setItem('sidebarClosed', !isClosed);
            });

            // Charts setup
            setupSalesChart();
            setupProductsChart();
            
            // Time filter buttons
            const timeFilterButtons = document.querySelectorAll('.time-filter-btn');
            timeFilterButtons.forEach(button => {
                button.addEventListener('click', () => {
                    // Remove active class from all buttons
                    timeFilterButtons.forEach(b => b.classList.remove('active'));
                    // Add active class to clicked button
                    button.classList.add('active');
                    // Update chart with new data based on days
                    const days = button.getAttribute('data-days');
                    updateSalesChartData(days);
                });
            });
        });

        // Sales performance chart setup
        function setupSalesChart() {
            const ctx = document.getElementById('salesChart').getContext('2d');
            
            // Get initial data from controller
            const salesData = @json($salesChartData);
            
            window.salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: salesData.labels,
                    datasets: [
                        {
                            label: 'Sales (Rp)',
                            data: salesData.sales,
                            borderColor: 'rgb(59, 130, 246)',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        },
                        {
                            label: 'Orders',
                            data: salesData.orders,
                            borderColor: 'rgb(16, 185, 129)',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 2,
                            fill: true,
                            tension: 0.4
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return 'Rp ' + value.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    }
                }
            });
        }

        // Update sales chart data based on days selected
        function updateSalesChartData(days) {
            // Fetch new data from the server
            fetch(`/admin/dashboard/sales-performance?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    window.salesChart.data.labels = data.labels;
                    window.salesChart.data.datasets[0].data = data.sales;
                    window.salesChart.data.datasets[1].data = data.orders;
                    window.salesChart.update();
                })
                .catch(error => console.error('Error fetching sales data:', error));
        }

        // Top products chart setup
        function setupProductsChart() {
            const ctx = document.getElementById('productsChart').getContext('2d');
            
            // Get data from controller
            const productData = @json($topProductsData);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: productData.labels,
                    datasets: [{
                        data: productData.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)',
                            'rgba(239, 68, 68, 0.8)',
                            'rgba(107, 114, 128, 0.8)',
                            'rgba(124, 58, 237, 0.8)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }
    </script>
</body>
</html>