<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const PRODUCTS_PER_PAGE = 12;

    public function __invoke()
    {
        // Get cities with their published products from database (first page only)
        $cities = City::with(['products' => function($query) {
            $query->where('published', true)
                  ->with(['media' => function($mediaQuery) {
                      $mediaQuery->where('role', 'main');
                  }])
                  ->orderBy('id')
                  ->limit(self::PRODUCTS_PER_PAGE);
        }])
        ->whereHas('products', function($query) {
            $query->where('published', true);
        })
        ->get()
        ->map(function($city) {
            $totalProducts = Product::where('city_id', $city->id)
                ->where('published', true)
                ->count();

            return [
                'id' => $city->id,
                'name' => [
                    'ar' => $city->name_ar ?? $city->name,
                    'en' => $city->name_en ?? $city->name
                ],
                'totalProducts' => $totalProducts,
                'hasMore' => $totalProducts > self::PRODUCTS_PER_PAGE,
                'products' => $city->products->map(function($product) {
                    return $this->formatProduct($product);
                })->toArray()
            ];
        })->toArray();

        return response()->view('website.layout.pages.home', [
            'cities' => $cities,
        ]);
    }

    public function loadMore(Request $request)
    {
        $cityId = $request->input('city_id');
        $page = $request->input('page', 1);
        $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;

        $city = City::findOrFail($cityId);
        
        $products = Product::where('city_id', $cityId)
            ->where('published', true)
            ->with(['media' => function($mediaQuery) {
                $mediaQuery->where('role', 'main');
            }])
            ->orderBy('id')
            ->offset($offset)
            ->limit(self::PRODUCTS_PER_PAGE)
            ->get();

        $totalProducts = Product::where('city_id', $cityId)
            ->where('published', true)
            ->count();

        $hasMore = ($offset + $products->count()) < $totalProducts;

        $formattedProducts = $products->map(function($product) {
            return $this->formatProduct($product);
        })->toArray();

        return response()->json([
            'success' => true,
            'products' => $formattedProducts,
            'hasMore' => $hasMore,
            'total' => $totalProducts
        ]);
    }

    private function formatProduct($product)
    {
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

        // Get image from media relationship (main image) or fallback to image field
        $mainImage = $product->media->where('role', 'main')->first();
        $imageUrl = '';
        if ($mainImage && $mainImage->url) {
            $imageUrl = $mainImage->url;
            // If it's a relative path, make it absolute
            if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
                $imageUrl = asset($imageUrl);
            }
        } elseif ($product->image) {
            $imageUrl = $product->image;
            // If it's a relative path, make it absolute
            if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
                $imageUrl = asset($imageUrl);
            }
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
            'image' => $imageUrl,
            'colors' => $colors,
            'sizes' => $sizes,
            'isPackage' => $product->is_package ?? false
        ];
    }
}
