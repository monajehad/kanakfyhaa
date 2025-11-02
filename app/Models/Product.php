<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
         use HasFactory;

    protected $fillable = [
        'country_id', 'name', 'title', 'short_description',
        'description', 'color', 'sizes', 'price_cost',
        'price_sell', 'discount', 'uuid', 'qr_code', 'published'
    ];

    protected $casts = [
        'sizes' => 'array',
    ];

    public function country() {
        return $this->belongsTo(Country::class);
    }

    public function categories() {
        return $this->belongsToMany(Category::class);
    }

    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function experiences() {
        return $this->hasMany(Experience::class);
    }
}
