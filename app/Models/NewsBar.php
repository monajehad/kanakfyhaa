<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsBar extends Model
{
    // Define the table name since the table is 'news_bar'
    protected $table = 'news_bar';

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'speed',
        'direction',
        'pause_on_hover',
        'theme',
        'item_space',
    ];

    // If there is a one-to-many relation to NewsBarItem, set it up for future
    public function items()
    {
        return $this->hasMany(NewsBarItem::class, 'news_bar_id');
    }
}
