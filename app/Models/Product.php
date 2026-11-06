<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'images',
        'price',
        'discount_type',
        'discount_value',
        'category_id',
        'brand_id',
        'in_stock',
        'is_active',
        'is_featured',
        'on_sale',
    ];

    protected function casts(): array
    {
        return [
            'images' => 'array',
            'price' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'in_stock' => 'boolean',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'on_sale' => 'boolean',
        ];
    }

    // Category Relationship
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // Brand Relationship
    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Calculate Discounted Price
    public function getDiscountedPriceAttribute(): float
    {
        $price = (float) $this->price;
        $discount = (float) ($this->discount_value ?? 0);

        if ($this->discount_type === 'percentage') {
            return round($price - ($price * $discount / 100), 2);
        }

        if ($this->discount_type === 'fixed') {
            return max(0, round($price - $discount, 2));
        }

        return $price;
    }
}
