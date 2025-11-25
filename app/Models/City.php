<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['country_id', 'name', 'name_ar', 'name_en', 'native_name', 'region', 'subregion', 'latitude', 'longitude', 'population', 'description', 'description_ar', 'description_en'];
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
     * Get the first media (image or video) for this city
     */
    public function getFirstMediaAttribute()
    {
        return $this->media()
            ->orderByRaw("CASE WHEN role='main' THEN 0 ELSE 1 END")
            ->orderBy('id')
            ->first();
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
     * Get the city description based on current language
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
     * Scope to get cities with published products
     */
    public function scopeWithPublishedProducts($query)
    {
        return $query->whereHas('products', function($q) {
            $q->where('published', true);
        });
    }
}
