<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Models\Invoice;
use Faker\Provider\ar_EG\Payment;
use App\Http\Controllers\PdfController;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.email');

    Route::get('/reset-password/{token}', [AuthController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Email Verification Routes
|--------------------------------------------------------------------------
*/

Route::get('/email/verify/{id}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('verification.resend');

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [AuthController::class, 'showHomePage'])->name('home');
Route::get('/product', [ProductController::class, 'index'])->name('product.index');
Route::get('/product/filter', [ProductController::class, 'filter'])->name('product.filter');
Route::get('/search', [ProductController::class, 'search'])->name('product.search');

Route::get('/cart/count', function () {
    return response()->json([
        'cart_count' => Auth::check()
            ? \App\Models\Cart::where('user_id', Auth::id())->sum('quantity')
            : 0
    ]);
})->name('cart.count');

/*
|--------------------------------------------------------------------------
| Admin Routes (Authenticated & cekRole:admin)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'cekRole:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/transactions', [DashboardController::class, 'orders'])->name('transactions');
    Route::get('/orders/{id}', [DashboardController::class, 'show'])->name('orders.show');

    // Product management
    Route::get('/dashboard/products', [DashboardController::class, 'products'])->name('products');
    Route::post('/dashboard/products', [DashboardController::class, 'store'])->name('products.store');
    Route::get('/dashboard/products/{id}/edit', [DashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/dashboard/products/{id}', [DashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/dashboard/products/{id}', [DashboardController::class, 'deleteProduct'])->name('products.delete');

    // Orders
    Route::get('/dashboard/orders', [DashboardController::class, 'orders'])->name('dashboard.orders');
    Route::get('/dashboard/orders/{id}', [DashboardController::class, 'showOrder'])->name('dashboard.orders.show');
    Route::get('/orders/ajax/{id}', [DashboardController::class, 'showAjax'])->name('orders.ajax');

    // Customers
    Route::get('/customers', [DashboardController::class, 'customers'])->name('customers');
    Route::post('/user/store', [DashboardController::class, 'storeUser'])->name('user.store');
    Route::delete('/user/delete/{id}', [DashboardController::class, 'destroyUser'])->name('user.destroy');
});

/*
|--------------------------------------------------------------------------
| User Routes (Authenticated & cekRole:user)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'cekRole:user'])->group(function () {
    // Profile
    Route::get('/profile', [AuthController::class, 'showSetting'])->name('profile.show');
    Route::put('/profile/update', [AuthController::class, 'updateProfilePicture'])->name('profile.update');
    Route::put('/password/update', [AuthController::class, 'updatePassword'])->name('password.change');
    Route::post('/profile/save-state', [AuthController::class, 'saveState'])->name('profile.save-state');

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update-quantity', [CartController::class, 'updateQuantity'])->name('cart.updateQuantity');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');

    // Shipping
    Route::get('/shipping', [ShippingController::class, 'shipping'])->name('shipping');
    Route::post('/shipping/get-cities', [ShippingController::class, 'getCities'])->name('shipping.getCities');
    Route::post('/cek-ongkir', [ShippingController::class, 'getOngkir'])->name('shipping.calculate');
    Route::post('/shipping/services', [ShippingController::class, 'getShippingServices'])->name('shipping.services');
    Route::post('/shipping/store', [ShippingController::class, 'storeCost'])->name('shipping.storeCost');
    Route::post('/shipping/store-cost', [ShippingController::class, 'storeCost'])->name('shipping.storeCost');

    Route::get('/shipping/session', fn () => response()->json(session('shipping', [])))->name('shipping.getSession');

    // Payment
    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('/shipping/reset', [PaymentController::class, 'reset'])->name('shipping.reset');


    // Checkout & Invoice
    Route::post('/checkout', [PaymentController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/payment', [MidtransController::class, 'getSnapToken'])->name('checkout.payment');
    Route::get('/invoice', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/{orderId}', [InvoiceController::class, 'index'])->name('invoice.show');

    Route::get('/orders', [InvoiceController::class, 'showOrderPage'])->name('order.page');
    Route::get('/checkout/process/{orderId}', [InvoiceController::class, 'processCheckout'])->name('checkout.process');
    Route::delete('/order/cancel/{order}', [InvoiceController::class, 'cancelOrder'])->name('order.cancel');

    Route::get('/invoice/{orderId}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoice.pdf');
    Route::get('/invoice/{orderId}/view-pdf', [InvoiceController::class, 'streamPdf'])->name('invoice.view-pdf');

});

/*
|--------------------------------------------------------------------------
| Midtrans Webhook (Public)
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/snap-token', [MidtransController::class, 'getSnapToken']);
Route::post('/midtrans/callback', [PaymentController::class, 'callBack']);
Route::post('/midtrans/webhook', [MidtransController::class, 'handleWebhook']);

/*
|--------------------------------------------------------------------------
| Logout (Authenticated Only)
|--------------------------------------------------------------------------
*/

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
