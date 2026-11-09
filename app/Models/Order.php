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
        // 'customer_name',
        // 'email',
        // 'phone',

        // Shipping Address
        // 'address',
        // 'city',
        // 'Province',
        // 'zip_code',
        // 'country',

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

    protected static function booted(): void
{
    static::creating(function ($order) {

        // Generate Order Number
        if (empty($order->order_number)) {
            $order->order_number = 'ORD-' . strtoupper(uniqid());
        }

        // Get Customer Information
        // $user = User::find($order->user_id);

        // if ($user) {
        //     $order->customer_name = $user->name;
        //     $order->email = $user->email;
        // }
    });
}

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
