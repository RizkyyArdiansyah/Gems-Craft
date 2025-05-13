<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Discount;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        // dd(session()->all()); 
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please log in to access your cart.');
        }

        $cartItems = Cart::with('product')
            ->where('user_id', Auth::id())
            ->get();
        
        $cartTotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
        $shippingData = session('shipping', []);
        $shippingCost = session('shipping.cost', 0);

        $discountCode = $request->input('discount_code');
        $discount = Discount::where('code', $discountCode)
            ->where('expiration_date', '>=', now()) // Cek masa berlaku
            ->first();
    
            $discountAmount = 0;
            $totalWithShipping = $cartTotal + $shippingCost;
            $errorMessage = null;
            
            // Cek apakah diskon valid
            if ($discount) {
                // Cek apakah total cart memenuhi syarat min_purchase
                if ($cartTotal >= $discount->min_purchase) {
                    if ($discount->type == 'percentage') {
                        $discountAmount = ($totalWithShipping * $discount->value) / 100;
                    } elseif ($discount->type == 'nominal') {
                        $discountAmount = $discount->value;
                    }
                } else {
                    // Set pesan error jika total cart kurang dari min_purchase
                    return redirect()->back()->with('error_message', "Oops! You need to spend at least Rp " . number_format($discount->min_purchase, 0, ',', '.') . " to get this discount.");
                }
            }
            // Simpan diskon dan kode ke session
        session()->put('discount_amount', $discountAmount);
        session()->put('discount_code', $discountCode);

        $finalTotal = max(0, $totalWithShipping - $discountAmount);

        // Jika shippingData kosong, redirect ke halaman shipping
        if (empty($shippingData)) {
            return redirect()->route('shipping')->with('error', 'Please enter shipping details first.');
        }

        return view('layouts.payment', compact('shippingData', 'cartItems', 'cartTotal','shippingData', 'shippingCost',  'discountAmount', 'finalTotal', 'discountCode'));
    }

    public function checkout(Request $request)
{
    
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in to proceed with checkout.');
    }

    $user = Auth::user();
    $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
    
    if ($cartItems->isEmpty()) {
        return redirect()->back()->with('error', 'Your cart is empty.');
    }

    // Ambil data shipping dari session
    $shippingData = session('shipping', []);
    if (empty($shippingData)) {
        return redirect()->back()->with('error', 'Shipping details are missing.');
    }

    // Hitung total biaya
    $cartTotal = $cartItems->sum(fn($item) => $item->quantity * $item->product->price);
    $shippingCost = $shippingData['cost'] ?? 0;
    $discountAmount = session('discount_amount', 0);
    $totalWithShipping = $cartTotal + $shippingCost;
    $finalTotal = max(0, $totalWithShipping - $discountAmount); // Sudah dikurangi diskon
    

    $order_id = 'ORD-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(6));

    // Simpan ke tabel orders
    $order = Order::create([
        'user_id'       => $user->id,
        'order_id'      => $order_id,
        'name'          => $shippingData['name'],
        'email'         => $shippingData['email'],
        'phone'         => $shippingData['phone'],
        'address'       => $shippingData['address'],
        'province'      => $shippingData['province_name'],
        'city'          => $shippingData['city_name'],
        'courier'       => $shippingData['courier'],
        'discount_amount' => $discountAmount,
        'shipping_amount' => $shippingCost,
        'service'       => $shippingData['service'],
        'total_cost'    => $finalTotal,
        'payment_type'  => 'null',
        'payment_status'=> 'pending',
    ]);

    // Simpan ke order_items
    foreach ($cartItems as $item) {
        OrderItem::create([
            'order_id'      => $order->id,
            'product_id'    => $item->product_id,
            'product_name'  => $item->product->product_name,
            'quantity'      => $item->quantity,
            'price'         => $item->product->price,
            'total_price'   => $item->quantity * $item->product->price,
            'status'        => 'pending',
        ]);
    }

    // Kirim response ke frontend untuk Midtrans
    return response()->json([
        'success'       => true,
        'order_id'      => $order_id,
        'gross_amount'  => $finalTotal, // pastikan ini yang dikirim ke Midtrans
    ]);
}

public function callBack(Request $request) {
    $serverKey = config('midtrans.server_key');
    $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    if (!hash_equals($hashed, $request->signature_key)) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    $order = Order::with('items.product')->where('order_id', $request->order_id)->first();

    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    switch ($request->transaction_status) {
        case 'settlement':
            $order->update([
                'payment_status' => 'paid',
                'payment_type' => $request->payment_type,
            ]);
            OrderItem::where('order_id', $order->id)->update(['status' => 'paid']);

            foreach ($order->items as $item) {
                $product = $item->product;
                if ($product && $product->stock >= $item->quantity) {
                    $product->stock -= $item->quantity;
                    $product->save();
                }
            }
            break;

        case 'pending':
            $order->update(['payment_status' => 'pending']);
            OrderItem::where('order_id', $order->id)->update(['status' => 'pending']);
            break;

        case 'deny':
        case 'expire':
        case 'cancel':
            $order->update(['payment_status' => 'failed']);
            OrderItem::where('order_id', $order->id)->update(['status' => 'failed']);
            break;
    }

    return response()->json(['message' => 'Payment status updated'], 200);
}
public function reset()
{
    // Hapus session shipping data
    session()->forget('shipping');

    // Redirect ke halaman shipping (ubah sesuai route yang kamu pakai)
    return redirect()->route('shipping')->with('message', 'Shipping data has been reset.');
}



}
