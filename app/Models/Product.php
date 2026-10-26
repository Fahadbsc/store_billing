<?php
// backend/app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'barcode',
        'category_id',
        'price',
        'cost_price',
        'stock',
        'min_stock',
        'unit',
        'store_type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'price'      => 'decimal:2',
        'cost_price' => 'decimal:2',
        'is_active'  => 'boolean',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function billItems()
    {
        return $this->hasMany(BillItem::class);
    }

    public function stockHistories()
    {
        return $this->hasMany(StockHistory::class);
    }

    // Check if stock is low
    public function isLowStock(): bool
    {
        return $this->stock <= $this->min_stock;
    }

    // Check if out of stock
    public function isOutOfStock(): bool
    {
        return $this->stock <= 0;
    }

    // Scope: active products only
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope: low stock products
    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock <= min_stock');
    }
}
