<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'name_en',
        'native_name',
        'iso2',
        'iso3',
        'numeric_code',
        'phone_code',
        'capital',
        'currency_symbol',
        'currency_name',
        'region',
        'subregion',
        'cities_count',
        'flag_url',
        'timezone',
        'latitude',
        'longitude',
        'population',
        'area',
        'short_description',
        'short_description_ar',
        'short_description_en',
        'description',
        'description_ar',
        'description_en',
        'cod_supported',
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }
    
    public function cities()
    {
        return $this->hasMany(City::class);
    }

    /**
     * Get the country name based on current language
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
     * Get the country description based on current language
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
     * Get the country short description based on current language
     */
    public function getLocalizedShortDescriptionAttribute()
    {
        $lang = app()->getLocale();
        if ($lang === 'ar' && $this->short_description_ar) {
            return $this->short_description_ar;
        }
        if ($lang === 'en' && $this->short_description_en) {
            return $this->short_description_en;
        }
        return $this->short_description;
    }
}
