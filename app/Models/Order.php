<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'customer_name',
        'email',
        'phone',
        'country',
        'city',
        'address',
        'postal_code',
        'notes',
        'items',
        'subtotal',
        'shipping',
        'total',
        'currency_symbol',
        'currency_rate',
        'payment_method',
        'payment_status',
        'order_status',
        'transaction_id',
        'payer_email',
        'order_date',
    ];

    protected $casts = [
        'items' => 'array',
        'order_date' => 'datetime',
    ];
}


