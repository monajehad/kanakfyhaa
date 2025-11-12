<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ExperienceController extends Controller
{
     public function show($uuid)
    {
       $product = Product::with(['city'])->where('uuid', $uuid)->firstOrFail();
    $city = $product->city;

    // جلب المعالم مع الآثار ووسائطها
    $landmarks = $city->landmarks()->with(['media', 'artifacts.media'])->get();

    return view('website.layout.pages.qr', compact('product', 'city', 'landmarks'));
}
}
