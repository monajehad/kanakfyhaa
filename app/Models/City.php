<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    use HasFactory;
    protected $fillable = ['country_id', 'name', 'native_name', 'region', 'subregion', 'latitude', 'longitude', 'population'];
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
}
