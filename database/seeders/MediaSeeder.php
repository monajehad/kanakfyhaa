<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Product;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       {
        $product = Product::first();
        $country = Country::first();

        if ($product) {
            $product->media()->createMany([
                [
                    'type' => 'image',
                    'url' => 'https://cdn.example.com/products/gaza-hoodie-1.jpg',
                    'alt_text' => 'هودي غزة ',
                    'order' => 1
                ],
                [
                    'type' => 'video',
                    'url' => 'https://cdn.example.com/products/gaza-video.mp4',
                    'thumbnail' => 'https://cdn.example.com/products/thumb.jpg',
                    'alt_text' => 'فيديو  لهودي ',
                    'order' => 2
                ]
            ]);
        }

        if ($country) {
            $country->media()->createMany([
                [
                    'type' => 'image',
                    'url' => 'https://www.wafa.ps/image/NewsThumbImg/Default/1a032adb-d74a-4890-8764-2547712a21a5.jpg',
                    'alt_text' => 'علم فلسطين',
                    'order' => 1
                ],
                [
                    'type' => 'video',
                    'url' => 'https://www.wafa.ps/image/NewsThumbImg/Default/1a032adb-d74a-4890-8764-2547712a21a5.jpg',
                    'thumbnail' => 'null',
                    'alt_text' => 'جولة في غزة',
                    'order' => 2
                ]
            ]);
        }}
    }
}
