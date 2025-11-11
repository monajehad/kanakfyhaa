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

        // City landmarks and artifacts
        $landmarks = Landmark::where('city_id', $product->city_id)
            ->withCount('artifacts')
            ->with('media')
            ->orderBy('id', 'desc')
            ->limit(6)
            ->get();

        $artifacts = Artifact::whereIn('landmark_id', $landmarks->pluck('id'))
            ->with('media', 'landmark')
            ->orderBy('id', 'desc')
            ->limit(8)
            ->get();

        return view('website.layout.pages.product', [
            'product' => $product,
            'gallery' => $gallery,
            'relatedProducts' => $relatedProducts,
            'landmarks' => $landmarks,
            'artifacts' => $artifacts,
        ]);
    }
}


