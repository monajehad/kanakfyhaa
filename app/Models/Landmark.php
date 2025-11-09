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
        'slug',
        'type',
        'short_description',
        'description',
        'image',
       
    ];

    public function city() {
        return $this->belongsTo(City::class);
    }

  
    public function artifacts() {
        return $this->hasMany(Artifact::class);
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
     * Scope to get landmarks by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
