<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug'];

    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    /**
     * Get all of the category's images.
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
}
