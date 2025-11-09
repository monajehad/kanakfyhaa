<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'city_id',
        'country_id',
        'name',
        'name_ar',
        'name_en',
        'title',
        'short_description',
        'description',
        'description_ar',
        'description_en',
        'color',
        'colors',
        'sizes',
        'price_cost',
        'price_sell',
        'price',
        'discount',
        'uuid',
        'qr_code',
        'image',
        'is_package',
        'published'
    ];

    protected $casts = [
        'sizes' => 'array',
        'colors' => 'array',
        'is_package' => 'boolean',
        'published' => 'boolean',
    ];
     protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (!$product->uuid) {
                $product->uuid = Str::uuid();
            }
        });
    }
 
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function experiences()
    {
        return $this->hasMany(Experience::class);
    }
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * Get the product name based on current language
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
     * Get the product description based on current language
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
     * Scope to get only published products
     */
    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    /**
     * Scope to get only package products
     */
    public function scopePackages($query)
    {
        return $query->where('is_package', true);
    }

    /**
     * Get the final price (considering discount)
     */
    public function getFinalPriceAttribute()
    {
        $price = $this->price ?? $this->price_sell ?? 0;
        if ($this->discount > 0) {
            return $price - ($price * $this->discount / 100);
        }
        return $price;
    }
}
