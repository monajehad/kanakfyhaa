<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Artifact extends Model
{
    use HasFactory;

    protected $fillable = [
        'landmark_id',
        'title',
        'short_description',
        'description',
        'image',
        
    ];

    public function landmark() {
        return $this->belongsTo(Landmark::class);
    }
    public function media()
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    /**
     * Scope to get artifacts by landmark
     */
    public function scopeForLandmark($query, $landmarkId)
    {
        return $query->where('landmark_id', $landmarkId);
    }
}
