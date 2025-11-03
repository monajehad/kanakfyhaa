<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    protected $guarded = [];

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
}
