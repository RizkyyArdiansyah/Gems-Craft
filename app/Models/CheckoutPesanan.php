<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckoutPesanan extends Model
{
    use HasFactory;

    protected $table = 'checkout_pesanan';

    protected $fillable = [
        'name', 'email', 'phone', 'province', 'city', 'courier', 'address', 'total_cost'
    ];
}

