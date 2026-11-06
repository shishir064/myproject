<?php

namespace App\Models;

use App\Models\Address;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',

        // Customer Information
        'customer_name',
        'email',
        'phone',

        // Shipping Address
        'address',
        'city',
        'Province',
        'zip_code',
        'country',

        // Order Amounts
        'subtotal',
        'discount_amount',
        'shipping_amount',
        'total_amount',

        // Payment Information
        'payment_method',
        'payment_status',

        // Order Status
        'status',

        // Additional Information
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'shipping_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function address()
    {
        return $this->hasOne(Address::class);
    }
}
