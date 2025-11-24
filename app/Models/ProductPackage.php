<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductPackage extends Model
{
    use HasFactory;

    protected $table = 'product_packages';

    protected $fillable = [
        'product_id',
        'name',
        'name_ar',
        'name_en',
        'description',
        'description_ar',
        'description_en',
        'items',
        'price',
        'original_price',
        'discount',
        'shipping_price',
        'quantity',
        'is_active',
        'order',
    ];

    protected $casts = [
        'items' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'original_price' => 'decimal:2',
        'shipping_price' => 'decimal:2',
    ];

    /**
     * Get the product this package belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the package name based on current language
     */
    public function getLocalizedNameAttribute()
    {
        $lang = app()->getLocale();
        if ($lang === 'ar' && $this->name_ar) {
            return $this->name_ar;
        }
        if ($lang === 'en' && $this->name_en) {
            return $this->name_en;
        }
        return $this->name;
    }

    /**
     * Get the package description based on current language
     */
    public function getLocalizedDescriptionAttribute()
    {
        $lang = app()->getLocale();
        if ($lang === 'ar' && $this->description_ar) {
            return $this->description_ar;
        }
        if ($lang === 'en' && $this->description_en) {
            return $this->description_en;
        }
        return $this->description;
    }

    /**
     * Get the final price (considering discount)
     */
    public function getFinalPriceAttribute()
    {
        $price = $this->price ?? 0;
        if ($this->discount > 0) {
            return $price - ($price * $this->discount / 100);
        }
        return $price;
    }

    /**
     * Scope to get only active packages
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get packages ordered by display order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderBy('id');
    }
}
