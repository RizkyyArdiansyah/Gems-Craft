<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Gems Craft Invoice</title>
    <style>
        /* CSS Reset and Basic Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            padding: 20px;
            color: #333;
            font-size: 14px;
        }

        /* Container */
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            padding: 20px;
            background-color: #fff;
        }

        /* Header Section */
        .invoice-header {
            border-bottom: 1px solid #000;
            padding-bottom: 15px;
            margin-bottom: 20px;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo {
            width: 60px;
            height: 30px;
            position: relative;
            left: -12px;
        }

        .invoice-title {
            font-size: 16px;
            font-weight: 600;
            text-align: center;
        }

        .invoice-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .order-info {
            font-size: 10px;
            line-height: 1.5;
        }

        .order-id {
            text-align: right;
            font-size: 8px;
            margin-bottom: 5px;
        }

        .status-order {
            color: green;
        }

        /* Shipping Details */
        .shipping-details {
            text-align: right;
            margin-bottom: 20px;
        }

        .shipping-details h3 {
            font-size: 11px;
            margin-bottom: 8px;
            font-weight: 600;
        }

        .shipping-details p {
            font-size: 10px;
            margin: 2px 0;
            line-height: 1.3;
        }

        /* Order Items Section */
        .order-items-title {
            font-size: 12px;
            font-weight: 600;
            margin-bottom: 10px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            margin-top: 10px;
        }

        th {
            background-color: #f3f4f6;
            text-align: center;
            padding: 8px;
            font-size: 10px;
            border: 1px solid #e1e1e1;
        }

        td {
            padding: 8px;
            font-size: 10px;
            border: 1px solid #e1e1e1;
        }

        td:nth-child(2) {
            text-align: center;
        }

        td:nth-child(3) {
            text-align: center;
        }

        /* Totals Section */
        .totals {
            margin-top: 15px;
            font-size: 10px;
            line-height: 1.8;
        }

        /* Responsive adjustments for dompdf */
        @page {
            margin: 20px;
        }

        .logo img {
            width: 60px;
            height: 60px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <!-- Header Section -->
        <div class="invoice-header">
            @php
                $logoPath = public_path('images/logoci.png');
                if (file_exists($logoPath)) {
                    $logoData = base64_encode(file_get_contents($logoPath));
                    $logoSrc = 'data:image/png;base64,' . $logoData;
                } else {
                    $logoSrc = '';
                }
            @endphp

            <div class="logo">
                @if($logoSrc)
                <img src="{{ $logoSrc }}" alt="Logo">
                @else
                <span>Gems Craft</span>
                @endif
            </div>
            <h1 class="invoice-title">Gems Craft Invoice</h1>
        </div>

        <div class="invoice-info">
            <div class="order-info">
                <p>Order Date : <span>{{ $order->created_at }} </span></p>
                <p>Status : <span class="status-order">{{ $order->payment_status }}</span></p>
            </div>
            <div class="order-id">
                <p>Order ID : <span>{{ $order->order_id }}</span></p>
            </div>
        </div>

        <!-- Shipping Details -->
        <div class="shipping-details">
            <h3>Shipping Detail</h3>
            <div>
                <p>{{ $order->name }}</p>
                <p>{{ $order->phone }}</p>
                <p>{{ $order->province }}, {{ $order->city_name }}</p>
                <p>{{ $order->address }}</p>
            </div>
        </div>

        <!-- Order Items Section -->
        <div class="order-items">
            <h3 class="order-items-title">Order Items</h3>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Qty</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($order->items as $item)
                        <tr>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Total Section -->
        <div class="totals">
            <p>Total Price : Rp <span>{{ number_format($cartTotal, 0, ',', '.') }}</span></p>
            <p>Shipping Price : Rp <span>{{ number_format($shippingCost, 0, ',', '.') }}</span></p>
            <p>Discount : Rp <span>{{ number_format($discountAmount, 0, ',', '.') }}</span></p>
            <p>Subtotal : Rp <span>{{ number_format($order->total_cost, 0, ',', '.') }}</span></p>
        </div>
    </div>
</body>

</html>