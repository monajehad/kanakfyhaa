<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ExperienceController extends Controller
{
     public function show($uuid)
    {
        $product = Product::with(['city.media'])->where('uuid', $uuid)->first();

        if (!$product) {
            abort(404, 'الصفحة غير متاحة أو الرابط غير صالح.');
        }

        $city = $product->city;
        $media = $city->media ?? collect();

        return view('front.experience', compact('product', 'city', 'media'));
    }
}
