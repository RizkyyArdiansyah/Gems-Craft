<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['code', 'type', 'value', 'min_purchase', 'expiration_date'];
}
