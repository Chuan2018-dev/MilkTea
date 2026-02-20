<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Addon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('images/default-addon.svg');
        }

        if (filter_var($this->image, FILTER_VALIDATE_URL)) {
            return $this->image;
        }

        $path = 'products/' . $this->image;

        if (Storage::disk('public')->exists($path)) {
            return route('media.products', ['filename' => $this->image]);
        }

        return asset('images/default-addon.svg');
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '+$' . number_format($this->price, 2);
    }

    /**
     * Scope for active addons
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
