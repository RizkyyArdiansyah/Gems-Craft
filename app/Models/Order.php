<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'user_id', 'name', 'email', 'phone',  'address', 'total_cost', 'payment_status', 'province', 'city', 'courier', 'service', 'shipping_amount', 'discount_amount'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }
}
