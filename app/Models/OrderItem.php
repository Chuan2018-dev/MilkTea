<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'unit_price',
        'quantity',
        'size',
        'sugar_level',
        'ice_level',
        'addons',
        'addons_total',
        'subtotal',
        'special_instructions',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'addons_total' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'addons' => 'json',
    ];

    /**
     * Get order for the item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get product for the item
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get size label
     */
    public function getSizeLabelAttribute(): string
    {
        $labels = [
            'small' => 'Small',
            'medium' => 'Medium',
            'large' => 'Large',
        ];
        return $labels[$this->size] ?? ucfirst($this->size);
    }

    /**
     * Get sugar level label
     */
    public function getSugarLevelLabelAttribute(): string
    {
        return $this->sugar_level === '100%' ? 'Normal' : $this->sugar_level . ' Sugar';
    }

    /**
     * Get ice level label
     */
    public function getIceLevelLabelAttribute(): string
    {
        $labels = [
            'no_ice' => 'No Ice',
            'less' => 'Less Ice',
            'regular' => 'Regular Ice',
            'extra' => 'Extra Ice',
        ];
        return $labels[$this->ice_level] ?? ucfirst($this->ice_level);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return '$' . number_format($this->subtotal, 2);
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return '$' . number_format($this->unit_price, 2);
    }

    /**
     * Get addons list as array
     */
    public function getAddonsListAttribute(): array
    {
        return $this->addons ?? [];
    }

    /**
     * Calculate subtotal
     */
    public function calculateSubtotal(): void
    {
        $this->subtotal = ($this->unit_price * $this->quantity) + ($this->addons_total * $this->quantity);
    }
}
