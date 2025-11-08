<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    //
    use HasFactory;

    protected $fillable = [
        'title',
        'short_description',
        'action_href',
        'action_button_text',
        'order',
    ];
    public function image()
    {
        return $this->morphOne(Media::class, 'mediable');
    }
}
