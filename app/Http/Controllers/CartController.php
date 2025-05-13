<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;



class CartController extends Controller
{
    public function index()
{
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'Please log in to access your cart.');
    }

    session()->forget('shipping');
    $cartItems = Cart::where('user_id', Auth::id())->get();
    return view('layouts.cart', compact('cartItems'));
}
public function addToCart(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $userId = Auth::id();
    $product = Product::findOrFail($request->product_id);

    // Cek apakah produk sudah ada di cart user
    $cartItem = Cart::where('user_id', $userId)
                    ->where('product_id', $product->id)
                    ->first();

    if ($cartItem) {
        $cartItem->increment('quantity', $request->quantity);
    } else {
        Cart::create([
            'user_id' => $userId,
            'product_id' => $product->id,
            'quantity' => $request->quantity,
            'price' => $product->price
        ]);
    }

    // **✅ HITUNG ULANG TOTAL ITEM DI CART**
    $cartNumber = Cart::where('user_id', $userId)->count();
session(['cart_number' => $cartNumber]);

return response()->json([
    'success' => true,
    'cart_number' => $cartNumber
]);

}


    public function updateQuantity(Request $request)
{
    $request->validate([
        'cart_id' => 'required|exists:cart,id',
        'action' => 'required|in:increment,decrement'
    ]);

    $cartItem = Cart::where('id', $request->cart_id)
                    ->where('user_id', Auth::id())
                    ->firstOrFail();

    if ($request->action === 'increment') {
        $cartItem->increment('quantity');
    } elseif ($request->action === 'decrement' && $cartItem->quantity > 1) {
        $cartItem->decrement('quantity');
    } else {
        $cartItem->delete(); // Hapus jika quantity sudah 1 dan dikurangi
    }

    return redirect()->back()->with('success', 'Cart updated!');
}
public function updateCart()
{
    $cartQuantity = Auth::check() ? Cart::where('user_id', Auth::id())->sum('quantity') : 0;
    session(['cart_count' => $cartQuantity]); // Update session
}

public function clearCart()
{
    Cart::where('user_id', Auth::id())->delete();
    return redirect()->back()->with('success', 'Cart cleared successfully!');
}

}
