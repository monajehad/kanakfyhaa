<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'title',
        'short_description',
        'description',
        'color',
        'sizes',
        'price_cost',
        'price_sell',
        'discount',
        'uuid',
        'qr_code',
        'published'
    ];

    protected $casts = [
        'sizes' => 'array',
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
}
