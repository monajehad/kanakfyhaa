<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Slider extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'link', 'button_text', 'button_url', 'active', 'order'];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get all of the slider's images.
     */
    public function media()
    {
        return $this->morphMany(\App\Models\Media::class, 'mediable');
    }

    /**
     * Shortcut for the main image (role: main).
     */
    public function mainImage()
    {
        return $this->morphOne(\App\Models\Media::class, 'mediable')
                    ->where('role', 'main');
    }

    /**
     * Scope to get active sliders.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true)->orderBy('order', 'asc');
    }
}
