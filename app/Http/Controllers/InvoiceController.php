<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Cart;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Snap;
use Midtrans\Config;
// Import PDF Facade
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    // Metode yang sudah ada tetap dipertahankan
    public function index($orderId)
    {
        // dd(session()->all());
        $user = Auth::user();

        // Ambil 1 order berdasarkan order_id
        $order = Order::with('items')
                    ->where('user_id', $user->id)
                    ->where('order_id', $orderId)
                    ->firstOrFail();
        

        // Hitung total harga item dalam order
        Cart::where('user_id', $user->id)->delete();
        $cartTotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
        $shippingCost = session('shipping.cost', 0);
        $discountAmount = session('discount_amount', 0);


        return view('layouts.invoice', compact('order', 'cartTotal', 'shippingCost', 'discountAmount'));
    }

    public function getOrder($orderId)
    {
        $user = Auth::user();
        
        // Cari order berdasarkan user dan order_id, serta ambil juga itemnya
        $order = Order::with('items') // Ambil data dari relasi "items"
                    ->where('user_id', $user->id)
                    ->where('order_id', $orderId)
                    ->first();


        if (!$order) {
            return response()->json(['error' => 'Order not found'], 404);
        }

        return response()->json($order); // Return data dalam format JSON
    }

    public function showOrderPage()
    {
        $user = Auth::user(); // Ambil user yang sedang login

        $orders = Order::with(['items.product', 'user']) // Ambil relasi yang diperlukan
                    ->where('user_id', Auth::id())
                    ->whereIn('payment_status', ['paid', 'pending']) // Ambil paid dan pending
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('layouts.order', compact('orders', 'user'));
    }

    // Method untuk proses checkout dari halaman riwayat pesanan
    public function processCheckout($IdOrder)
    {
        // Ambil data order berdasarkan order_id (bukan id numerik)
        $order = Order::where('order_id', $IdOrder)
                    ->where('user_id', Auth::id())
                    ->where('payment_status', 'pending')
                    ->firstOrFail();
        
        // Pastikan order valid dan ambil total biaya
        $total_cost = $order->total_cost; // Pastikan field ini sesuai dengan struktur database Anda
        
        // Siapkan data untuk Midtrans
        $transaction_details = [
            'order_id' => $order->order_id,
            'gross_amount' => (int)$total_cost,
        ];
        
        // Opsional: Tambahkan data customer jika diperlukan
        $customer_details = [
            'first_name' => Auth::user()->name,
            'email' => Auth::user()->email,
            // 'phone' => Auth::user()->phone, // Jika ada
        ];
        
        $midtrans_params = [
            'transaction_details' => $transaction_details,
            'customer_details' => $customer_details,
        ];
        
        // Dapatkan Snap Token dari Midtrans
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');
        \Midtrans\Config::$isSanitized = config('midtrans.is_sanitized');
        \Midtrans\Config::$is3ds = config('midtrans.is_3ds');
        
        try {
            $snapToken = \Midtrans\Snap::getSnapToken($midtrans_params);
            
            // PERBAIKAN: Tidak menyimpan token ke database karena kolom payment_token belum ada
            // $order->payment_token = $snapToken; 
            // $order->save();
            
            return response()->json([
                'success' => true,
                'order_id' => $order->order_id,
                'snap_token' => $snapToken,
                'gross_amount' => $total_cost,
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat token pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk membatalkan pesanan
    public function cancelOrder($orderId)
    {
        // Gunakan transaction untuk memastikan semua operasi berjalan dengan benar
        DB::beginTransaction();
        
        try {
            $order = Order::where('id', $orderId)
                        ->where('user_id', Auth::id())
                        ->where('payment_status', 'pending')
                        ->firstOrFail();
            
            // Hapus order items terlebih dahulu
            OrderItem::where('order_id', $order->id)->delete();
            
            // Hapus order
            $order->delete();
            
            DB::commit();
            
            return redirect()->route('orders.index')->with('success', 'Pesanan berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan. Silakan coba lagi.');
        }
    }

    /**
 * Generate PDF dari invoice
 *
 * @param string $orderId
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function generatePdf($orderId)
{
    $user = Auth::user();

    // Ambil order berdasarkan order_id
    $order = Order::with(['items.product', 'user'])
                ->where('user_id', $user->id)
                ->where('order_id', $orderId)
                ->firstOrFail();

    // Hitung total harga item dalam order
    $cartTotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
    $shippingCost = $order->shipping_amount ?? 0; // Ambil dari order jika ada
    $discountAmount = $order->discount_amount ?? 0; // Ambil dari order jika ada

    // Load view dengan data yang diperlukan dan konfigurasi khusus untuk PDF
    $pdf = PDF::loadView('layouts.pdf', compact('order', 'cartTotal', 'shippingCost', 'discountAmount'));
    
    // Atur opsi PDF
    $pdf->setPaper('a4', 'portrait');
    $pdf->setOption('isHtml5ParserEnabled', true);
    $pdf->setOption('isPhpEnabled', true);
    $pdf->setOption('isRemoteEnabled', true);
    
    // Download file dengan nama yang sesuai
    return $pdf->download('invoice-' . $orderId . '.pdf');
}

}

    

