<?php
// backend/app/Models/Bill.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_number',
        'cashier_id',
        'customer_name',
        'customer_phone',
        'subtotal',
        'discount',
        'discount_type',
        'discount_amount',
        'tax_amount',
        'total',
        'payment_mode',
        'amount_paid',
        'change_amount',
        'status',
    ];

    protected $casts = [
        'subtotal'        => 'decimal:2',
        'discount'        => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount'      => 'decimal:2',
        'total'           => 'decimal:2',
        'amount_paid'     => 'decimal:2',
        'change_amount'   => 'decimal:2',
    ];

    // Relationships
    public function items()
    {
        return $this->hasMany(BillItem::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    // Scopes
    public function scopePaid($query)
    {
        return $query->where('status', 'paid');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
