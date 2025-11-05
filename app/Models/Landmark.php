<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
