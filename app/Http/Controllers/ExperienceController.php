<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\City;
use App\Models\Landmark;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExperienceController extends Controller
{
    public function show($uuid)
    {
        $product = Product::where('uuid', $uuid)
            ->with([
                'city' => function ($q) {
                    $q->with('media');
                },
                'media' => function ($q) {
                    $q->orderByRaw("CASE WHEN role='main' THEN 0 ELSE 1 END")->orderBy('id');
                }
            ])
            ->firstOrFail();
        
        $city = $product->city;

        // جلب المعالم للمدينة الحالية فقط مع كل العلاقات
        $landmarks = $city->landmarks()
            ->with('media')
            ->get();

        // Generate QR code as data URL
        $qrUrl = 'data:image/svg+xml;base64,' . base64_encode(
            QrCode::size(300)
                ->encoding('UTF-8')
                ->generate(url('/experience/' . $product->uuid))
        );

        return view('website.layout.pages.qr', compact('product', 'city', 'landmarks', 'qrUrl'));
    }
}
