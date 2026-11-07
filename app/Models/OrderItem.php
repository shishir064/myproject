<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    
    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'discount',
        'unit_amount',
        'quantity',
        'total_amount',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'discount' => 'decimal:2',
            'unit_amount' => 'decimal:2',
            'total_amount' => 'decimal:2',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
