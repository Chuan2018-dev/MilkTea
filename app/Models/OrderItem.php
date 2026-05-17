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
        'size_id',
        'sugar_level',
        'ice_level',
        'quantity',
        'unit_price',
        'total_price',
        'special_instructions',
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    /**
     * Get the order that owns the item
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the size
     */
    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    /**
     * Get add-ons for this order item
     */
    public function addOns()
    {
        return $this->belongsToMany(AddOn::class, 'order_item_add_on')
            ->withPivot('price')
            ->withTimestamps();
    }

    /**
     * Get formatted total price
     */
    public function getFormattedTotalPriceAttribute(): string
    {
        return 'PHP ' . number_format($this->total_price, 2);
    }

    /**
     * Get formatted unit price
     */
    public function getFormattedUnitPriceAttribute(): string
    {
        return 'PHP ' . number_format($this->unit_price, 2);
    }

    /**
     * Get item details with add-ons
     */
    public function getItemDetailsAttribute(): string
    {
        $iceLevel = ucfirst(str_replace('_', ' ', $this->ice_level));
        $details = $this->product->name . ' (' . $this->size->display_name . ', ' . $this->sugar_level . ' sugar, ' . $iceLevel . ')';
        if ($this->addOns->count() > 0) {
            $addonNames = $this->addOns->pluck('name')->join(', ');
            $details .= ' + ' . $addonNames;
        }
        return $details;
    }
}
