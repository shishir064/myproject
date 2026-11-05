<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'sku',
        'description',
        'category_id',
        'brand_id',
        'status',
        'purchase_price',
        'price',
        'discount_type',
        'discount_value',
        'stock',
        'low_stock_threshold',
        'weight',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'purchase_price' => 'decimal:2',
            'price' => 'decimal:2',
            'discount_value' => 'decimal:2',
            'weight' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class);
    }
}