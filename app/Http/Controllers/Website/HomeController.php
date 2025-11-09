<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __invoke()
    {
        // Use Eloquent to get the news bar settings
        $newsBar = \App\Models\NewsBar::first();

        // Get all active sliders (not just one)
        $sliders = \App\Models\Slider::where('active', true)
            ->orderBy('order')
            ->orderBy('id')
            ->get();

        // Get cities with their published products from database
        $cities = City::with(['products' => function($query) {
            $query->where('published', true)
                  ->orderBy('id');
        }])
        ->whereHas('products', function($query) {
            $query->where('published', true);
        })
        ->get()
        ->map(function($city) {
            return [
                'id' => $city->id,
                'name' => [
                    'ar' => $city->name_ar ?? $city->name,
                    'en' => $city->name_en ?? $city->name
                ],
                'products' => $city->products->map(function($product) {
                    // Ensure colors and sizes are arrays
                    $colors = $product->colors;
                    if (is_string($colors)) {
                        $colors = json_decode($colors, true) ?? [];
                    }
                    if (!is_array($colors)) {
                        $colors = [];
                    }

                    $sizes = $product->sizes;
                    if (is_string($sizes)) {
                        $sizes = json_decode($sizes, true) ?? [];
                    }
                    if (!is_array($sizes)) {
                        $sizes = [];
                    }

                    return [
                        'id' => $product->id,
                        'name' => [
                            'ar' => $product->name_ar ?? $product->name,
                            'en' => $product->name_en ?? $product->name
                        ],
                        'description' => [
                            'ar' => $product->description_ar ?? $product->description ?? '',
                            'en' => $product->description_en ?? $product->description ?? ''
                        ],
                        'price' => $product->price ?? $product->price_sell ?? 0,
                        'image' => $product->image ?? '',
                        'colors' => $colors,
                        'sizes' => $sizes,
                        'isPackage' => $product->is_package ?? false
                    ];
                })->toArray()
            ];
        })->toArray();

        return response()->view('website.layout.pages.home', [
            'newsBar' => $newsBar,
            'sliders' => $sliders,
            'cities' => $cities,
        ]);
    }
}
