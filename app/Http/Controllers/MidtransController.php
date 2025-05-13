<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Transaction;

class MidtransController extends Controller
{
    public function getSnapToken(Request $request)
{
    try {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Ambil order dari database
        $order = Order::where('order_id', $request->order_id)->first();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }

        // Data transaksi
        $transaction = [
            'transaction_details' => [
                'order_id'    => $order->order_id,  // Pakai order_id dari database
                'gross_amount'=> $order->total_cost, // Pakai total dari database
            ],
            'customer_details' => [
                'first_name' => $order->name,
                'email'      => $order->email,
                'phone'      => $order->phone,
            ],
        ];

        // Generate Snap Token
        $snapToken = Snap::getSnapToken($transaction);
        return response()->json([
            'success' => true,
            'snap_token' => $snapToken,
            'order_id' => $order->order_id
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function handleWebhook(Request $request)
{
    $serverKey = config('midtrans.server_key');
    $grossAmount = number_format((float) $request->gross_amount, 2, '.', '');
    $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

    // Signature check
    if (!hash_equals($hashed, $request->signature_key)) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    $orderId = $request->order_id;
    $transactionStatus = $request->transaction_status;
    $fraudStatus = $request->fraud_status ?? null;

    // Ambil order berdasarkan order_id (string)
    $order = Order::with('items.product')->where('order_id', $orderId)->first();
    if (!$order) {
        return response()->json(['message' => 'Order not found'], 404);
    }

    // Update status pembayaran
    if ($transactionStatus === 'capture' && $fraudStatus === 'accept') {
        $order->payment_status = 'paid';
    } elseif ($transactionStatus === 'settlement') {
        $order->payment_status = 'paid';
    } elseif ($transactionStatus === 'pending') {
        $order->payment_status = 'pending';
    } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
        $order->payment_status = 'failed';
    }

    $order->save();

    // Jika sukses bayar, update order_items dan stok produk
    if ($order->payment_status === 'paid') {
        foreach ($order->items as $item) {
            $item->update(['status' => 'paid']);

            $product = $item->product;
            if ($product && $product->stock >= $item->quantity) {
                $product->stock -= $item->quantity;
                $product->save();
            }
        }
    }

    return response()->json(['message' => 'Webhook handled successfully'], 200);
}


}
