<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MidtransController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/midtrans/callback', [PaymentController::class, 'callBack']);
Route::post('/midtrans/webhook', [MidtransController::class, 'handleWebhook']);
