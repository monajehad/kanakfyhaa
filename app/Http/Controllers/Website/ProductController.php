<?php

namespace App\Http\Controllers\Website;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Landmark;
use App\Models\Artifact;

class ProductController extends Controller
{
    public function show(string $uuid)
    {
        $product = Product::where('uuid', $uuid)
            ->published()
            ->with([
                'media' => function ($q) {
                    $q->orderByRaw("CASE WHEN role='main' THEN 0 ELSE 1 END")->orderBy('id');
                },
                'city.media',
                'city.landmarks.media',
            ])
            ->firstOrFail();

        // Format gallery images (main first)
        $gallery = $product->media->map(function ($m) {
            return $m->url && (str_starts_with($m->url, 'http') ? $m->url : asset($m->url));
        })->filter()->values()->toArray();
        if (empty($gallery) && $product->image) {
            $gallery[] = str_starts_with($product->image, 'http') ? $product->image : asset($product->image);
        }

        // Related by city
        $relatedProducts = Product::published()
            ->where('city_id', $product->city_id)
            ->where('id', '<>', $product->id)
            ->with(['media' => function ($q) { $q->where('role', 'main'); }])
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        // City main landmark (one city post) and artifacts
        $cityLandmark = Landmark::where('city_id', $product->city_id)
            ->withCount('artifacts')
            ->with('media')
            ->orderBy('id', 'asc')
            ->first();

        $artifacts = Artifact::whereHas('landmark', function ($q) use ($product) {
                $q->where('city_id', $product->city_id);
            })
            ->with('media', 'landmark')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        // Normalize city media and landmarks count for the view
        $cityMedia = null;
        $cityMediaType = 'image'; // Default type
        $landmarksCount = 0;
        if ($product->city) {
            $city = $product->city;
            $allMedia = $city->media ?? collect();
            // prefer main media (role='main'), then first media regardless of type
            $firstMedia = $allMedia->firstWhere('role', 'main') ?? $allMedia->first();
            if ($firstMedia && $firstMedia->url) {
                $url = $firstMedia->url;
                $cityMedia = str_starts_with($url, 'http') ? $url : asset($url);
                $cityMediaType = $firstMedia->type ?? 'image';
            }
            $landmarksCount = $city->landmarks ? $city->landmarks->count() : Landmark::where('city_id', $city->id)->count();
        }

        return view('website.layout.pages.product', [
            'product' => $product,
            'gallery' => $gallery,
            'relatedProducts' => $relatedProducts,
            'cityLandmark' => $cityLandmark,
            'cityMedia' => $cityMedia,
            'cityMediaType' => $cityMediaType,
            'landmarksCount' => $landmarksCount,
            'artifacts' => $artifacts,
        ]);
    }
}


