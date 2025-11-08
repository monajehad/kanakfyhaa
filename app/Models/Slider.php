<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'subtitle',
        'description',
        'image',
        'button_text',
        'button_url',
        'order',
        'active',
    ];

    /**
     * You can define image handling logic here,
     * such as an accessor for the image path if needed.
     */

    // Example: If you wish to retrieve the full image url
    public function getImageUrlAttribute()
    {
        // Assumes image is stored via Laravel's storage system
        return $this->image ? asset('storage/' . $this->image) : null;
    }
}
