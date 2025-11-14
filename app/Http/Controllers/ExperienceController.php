<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ExperienceController extends Controller
{
    public function show($uuid)
    {
        $product = Product::with(['city'])->where('uuid', $uuid)->firstOrFail();
        $city = $product->city;

        // جلب المعالم مع الآثار ووسائطها
        $landmarks = $city->landmarks()->with(['media', 'artifacts.media'])->get();

        // Generate QR code as data URL
        $qrUrl = 'data:image/svg+xml;base64,' . base64_encode(
            QrCode::size(300)
                ->encoding('UTF-8')
                ->generate(url('/experience/' . $product->uuid))
        );

        return view('website.layout.pages.qr', compact('product', 'city', 'landmarks', 'qrUrl'));
    }
}
