<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewsBarItem extends Model
{
    // Define the table name as 'news_bar_items'
    protected $table = 'news_bar_items';

    // Specify which attributes can be mass-assigned
    protected $fillable = [
        'text',
        'active',
        'order',
        'news_bar_id',
    ];

    // Define relation to NewsBar if necessary
    public function newsBar()
    {
        return $this->belongsTo(NewsBar::class, 'news_bar_id');
    }
}
