<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['country_id', 'name', 'name_ar', 'name_en', 'native_name', 'region', 'subregion', 'latitude', 'longitude', 'population'];
    public function country() {
        return $this->belongsTo(Country::class);
    }
    public function products() {
        return $this->hasMany(Product::class);
    }
    public function landmarks() {
    return $this->hasMany(Landmark::class);
}
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Get the city name based on current language
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
     * Scope to get cities with published products
     */
    public function scopeWithPublishedProducts($query)
    {
        return $query->whereHas('products', function($q) {
            $q->where('published', true);
        });
    }
}
