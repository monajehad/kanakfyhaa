<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    private const PRODUCTS_PER_PAGE = 20;

    public function index(Request $request)
    {
        // Get filter data for the UI
        $cities = City::whereHas('products', function($query) {
            $query->where('published', true);
        })->get()->map(function($city) {
            return [
                'id' => $city->id,
                'name' => [
                    'ar' => $city->name_ar ?? $city->name,
                    'en' => $city->name_en ?? $city->name
                ]
            ];
        })->toArray();

        $categories = Category::all()->map(function($category) {
            return [
                'id' => $category->id,
                'name' => [
                    'ar' => $category->name_ar ?? $category->name,
                    'en' => $category->name_en ?? $category->name
                ]
            ];
        })->toArray();

        // Get all unique colors and sizes from products
        $allProducts = Product::where('published', true)->get();
        $allColors = [];
        $allSizes = [];
        
        foreach ($allProducts as $product) {
            $colors = $product->colors;
            if (is_string($colors)) {
                $colors = json_decode($colors, true) ?? [];
            }
            if (is_array($colors)) {
                $allColors = array_merge($allColors, $colors);
            }

            $sizes = $product->sizes;
            if (is_string($sizes)) {
                $sizes = json_decode($sizes, true) ?? [];
            }
            if (is_array($sizes)) {
                $allSizes = array_merge($allSizes, $sizes);
            }
        }

        $uniqueColors = array_unique($allColors);
        $uniqueSizes = array_unique($allSizes);
        sort($uniqueSizes);

        // Get price range
        $minPrice = Product::where('published', true)->min('price_sell') ?? Product::where('published', true)->min('price') ?? 0;
        $maxPrice = Product::where('published', true)->max('price_sell') ?? Product::where('published', true)->max('price') ?? 1000;

        return response()->view('website.layout.pages.search', [
            'cities' => $cities,
            'categories' => $categories,
            'colors' => array_values($uniqueColors),
            'sizes' => array_values($uniqueSizes),
            'minPrice' => floor($minPrice),
            'maxPrice' => ceil($maxPrice),
            'filters' => $request->only(['q', 'city', 'category', 'min_price', 'max_price', 'colors', 'sizes', 'is_package', 'sort'])
        ]);
    }

    public function search(Request $request)
    {
        $query = Product::where('published', true)
            ->with(['media' => function($mediaQuery) {
                $mediaQuery->where('role', 'main');
            }])
            ->with('city');

        // Search keyword
        if ($request->filled('q')) {
            $searchTerm = $request->input('q');
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name_ar', 'like', '%' . $searchTerm . '%')
                  ->orWhere('name_en', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description_ar', 'like', '%' . $searchTerm . '%')
                  ->orWhere('description_en', 'like', '%' . $searchTerm . '%');
            });
        }

        // Filter by city
        if ($request->filled('city')) {
            $query->where('city_id', $request->input('city'));
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('categories.id', $request->input('category'));
            });
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->where(function($q) use ($request) {
                $q->where('price_sell', '>=', $request->input('min_price'))
                  ->orWhere(function($subQ) use ($request) {
                      $subQ->whereNull('price_sell')
                           ->where('price', '>=', $request->input('min_price'));
                  });
            });
        }

        if ($request->filled('max_price')) {
            $query->where(function($q) use ($request) {
                $q->where('price_sell', '<=', $request->input('max_price'))
                  ->orWhere(function($subQ) use ($request) {
                      $subQ->whereNull('price_sell')
                           ->where('price', '<=', $request->input('max_price'));
                  });
            });
        }

        // Filter by colors
        if ($request->filled('colors')) {
            $colors = is_array($request->input('colors')) ? $request->input('colors') : [$request->input('colors')];
            $query->where(function($q) use ($colors) {
                foreach ($colors as $color) {
                    $q->orWhereJsonContains('colors', $color);
                }
            });
        }

        // Filter by sizes
        if ($request->filled('sizes')) {
            $sizes = is_array($request->input('sizes')) ? $request->input('sizes') : [$request->input('sizes')];
            $query->where(function($q) use ($sizes) {
                foreach ($sizes as $size) {
                    $q->orWhereJsonContains('sizes', $size);
                }
            });
        }

        // Filter by package
        if ($request->filled('is_package')) {
            $query->where('is_package', $request->input('is_package') === '1' || $request->input('is_package') === true);
        }

        // Sorting
        $sortBy = $request->input('sort', 'relevance');
        switch ($sortBy) {
            case 'price_low':
                $query->orderByRaw('COALESCE(price_sell, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(price_sell, price) DESC');
                break;
            case 'name':
                $query->orderBy('name');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('id');
        }

        $page = $request->input('page', 1);
        $offset = ($page - 1) * self::PRODUCTS_PER_PAGE;
        
        $total = $query->count();
        $products = $query->offset($offset)->limit(self::PRODUCTS_PER_PAGE)->get();

        $formattedProducts = $products->map(function($product) {
            return $this->formatProduct($product);
        })->toArray();

        return response()->json([
            'success' => true,
            'products' => $formattedProducts,
            'total' => $total,
            'hasMore' => ($offset + count($formattedProducts)) < $total,
            'page' => $page,
            'perPage' => self::PRODUCTS_PER_PAGE
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
            if (!str_starts_with($imageUrl, 'http://') && !str_starts_with($imageUrl, 'https://')) {
                $imageUrl = asset($imageUrl);
            }
        } elseif ($product->image) {
            $imageUrl = $product->image;
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
            'isPackage' => $product->is_package ?? false,
            'city' => $product->city ? [
                'id' => $product->city->id,
                'name' => [
                    'ar' => $product->city->name_ar ?? $product->city->name,
                    'en' => $product->city->name_en ?? $product->city->name
                ]
            ] : null
        ];
    }
}

