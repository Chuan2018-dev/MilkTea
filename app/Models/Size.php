<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'price_adjustment',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get order items for this size
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope active sizes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Get formatted price adjustment
     */
    public function getFormattedPriceAdjustmentAttribute(): string
    {
        if ($this->price_adjustment > 0) {
            return '+PHP ' . number_format($this->price_adjustment, 2);
        } elseif ($this->price_adjustment < 0) {
            return '-PHP ' . number_format(abs($this->price_adjustment), 2);
        }
        return '';
    }
}
