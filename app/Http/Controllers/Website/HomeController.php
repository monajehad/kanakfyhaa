<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function __invoke()
    {
        // Use Eloquent to get the news bar settings
        $newsBar = \App\Models\NewsBar::first();
        return response()->view('website.layout.main', [
            'newsBar' => $newsBar
        ]);
    }


    
}
