<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    private const PRODUCTS_PER_PAGE = 6;

    public function __invoke()
    {
        // Get cities that have published products
        // NOTE: We intentionally avoid eager-loading the hasMany products with a limit
        // because Eloquent cannot apply per-parent limits during eager loading.
        // We instead fetch the first page per city explicitly.
        $citiesPaginator = City::whereHas('products', function($query) {
                $query->where('published', true);
            })
            ->orderBy('id')
            ->paginate(self::PRODUCTS_PER_PAGE);

        $cities = $citiesPaginator->getCollection()
            ->map(function($city) {
                // Fetch only the first page of products for this city
                $products = Product::where('city_id', $city->id)
                    ->where('published', true)
                    ->with(['media' => function($mediaQuery) {
                        $mediaQuery->where('role', 'main');
                    }])
                    ->orderBy('id')
                    ->limit(self::PRODUCTS_PER_PAGE)
                    ->get();

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
                    'products' => $products->map(function($product) {
                        return $this->formatProduct($product);
                    })->toArray()
                ];
            })
            ->values()
            ->toArray();

        return response()->view('website.layout.pages.home', [
            'cities' => $cities,
            'citiesMeta' => [
                'currentPage' => $citiesPaginator->currentPage(),
                'lastPage' => $citiesPaginator->lastPage(),
                'hasMore' => $citiesPaginator->currentPage() < $citiesPaginator->lastPage(),
            ],
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

    public function loadCities(Request $request)
    {
        $page = (int) $request->input('page', 1);

        $citiesPaginator = City::whereHas('products', function($query) {
                $query->where('published', true);
            })
            ->orderBy('id')
            ->paginate(self::PRODUCTS_PER_PAGE, ['*'], 'page', $page);

        $cities = $citiesPaginator->getCollection()
            ->map(function($city) {
                $products = Product::where('city_id', $city->id)
                    ->where('published', true)
                    ->with(['media' => function($mediaQuery) {
                        $mediaQuery->where('role', 'main');
                    }])
                    ->orderBy('id')
                    ->limit(self::PRODUCTS_PER_PAGE)
                    ->get();

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
                    'products' => $products->map(function($product) {
                        return $this->formatProduct($product);
                    })->toArray()
                ];
            })
            ->values()
            ->toArray();

        return response()->json([
            'success' => true,
            'cities' => $cities,
            'currentPage' => $citiesPaginator->currentPage(),
            'lastPage' => $citiesPaginator->lastPage(),
            'hasMore' => $citiesPaginator->currentPage() < $citiesPaginator->lastPage(),
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
            'uuid' => $product->uuid,
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
