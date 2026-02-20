<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'base_price',
        'image',
        'category',
        'is_active',
        'available_sizes',
        'available_sugar_levels',
        'available_ice_levels',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'is_active' => 'boolean',
        'available_sizes' => 'json',
        'available_sugar_levels' => 'json',
        'available_ice_levels' => 'json',
    ];

    /**
     * Get order items for the product
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/default-product.svg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = 'products/' . $this->image;

        if (Storage::disk('public')->exists($path)) {
            return route('media.products', ['filename' => $this->image]);
        }

        return asset('images/default-product.svg');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->base_price, 2);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by category
     */
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Get size price multiplier
     */
    public function getSizePriceMultiplier(string $size): float
    {
        $multipliers = [
            'small' => 0.85,
            'medium' => 1.0,
            'large' => 1.25,
        ];
        return $multipliers[$size] ?? 1.0;
    }

    /**
     * Calculate price for specific size
     */
    public function getPriceForSize(string $size): float
    {
        return round($this->base_price * $this->getSizePriceMultiplier($size), 2);
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        $labels = [
            'milk_tea' => 'Milk Tea',
            'fruit_tea' => 'Fruit Tea',
            'smoothie' => 'Smoothie',
            'coffee' => 'Coffee',
            'other' => 'Other',
        ];
        return $labels[$this->category] ?? 'Other';
    }
}
