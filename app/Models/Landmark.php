<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Landmark extends Model
{
      use HasFactory;

    protected $fillable = [
        'city_id',
        'name',
        'name_ar',
        'name_en',
        'slug',
        'type',
        'short_description',
        'short_description_ar',
        'short_description_en',
        'description',
        'description_ar',
        'description_en',
        'image',
        'ambient_description',
        'ambient_description_ar',
        'ambient_description_en',
        'timeline',
    ];

    protected $casts = [
        'timeline' => 'array',
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Generate slug automatically from name
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($landmark) {
            if (!$landmark->slug && $landmark->name) {
                $landmark->slug = Str::slug($landmark->name);
                
                // Ensure uniqueness
                $originalSlug = $landmark->slug;
                $counter = 1;
                while (static::where('slug', $landmark->slug)->exists()) {
                    $landmark->slug = $originalSlug . '-' . $counter;
                    $counter++;
                }
            }
        });
    }

    /**
     * Get the landmark name based on current language
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
     * Get the landmark description based on current language
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
     * Get the landmark short description based on current language
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

    /**
     * Get the landmark ambient description based on current language
     */
    public function getLocalizedAmbientDescriptionAttribute()
    {
        $lang = app()->getLocale();
        if ($lang === 'ar' && $this->ambient_description_ar) {
            return $this->ambient_description_ar;
        }
        if ($lang === 'en' && $this->ambient_description_en) {
            return $this->ambient_description_en;
        }
        return $this->ambient_description;
    }

    /**
     * Scope to get landmarks by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
