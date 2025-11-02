<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Experience extends Model
{
      use HasFactory;

    protected $fillable = [
        'product_id', 'user_name', 'comment',
        
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function media() {
        return $this->morphMany(Media::class, 'mediable');
    }
}
