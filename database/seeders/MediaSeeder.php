<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 300;
        $faker = Faker::create();
        
        $this->command->info("📸 Creating {$count} media...");

        // Get IDs
        $productIds = Product::pluck('id')->toArray();
        $countryIds = Country::pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->error('❌ Please run ProductSeeder first!');
            return;
        }

        // Create media for the first product (base data)
        $firstProduct = Product::first();
        if ($firstProduct) {
            DB::table('media')->insertOrIgnore([
                [
                    'mediable_type' => 'App\Models\Product',
                    'mediable_id' => $firstProduct->id,
                    'type' => 'image',
                    'url' => 'https://cdn.example.com/products/gaza-hoodie-1.jpg',
                    'alt_text' => 'Gaza Hoodie',
                    'thumbnail' => null,
                    'order' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'mediable_type' => 'App\Models\Product',
                    'mediable_id' => $firstProduct->id,
                    'type' => 'video',
                    'url' => 'https://cdn.example.com/products/gaza-video.mp4',
                    'thumbnail' => 'https://cdn.example.com/products/thumb.jpg',
                    'alt_text' => 'Gaza Hoodie Video',
                    'order' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            ]);
        }

        // Create media in large batches
        $chunkSize = 10000;
        $chunks = ceil(($count - 2) / $chunkSize);
        $mediaTypes = ['image', 'video'];

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 2 - ($i * $chunkSize));
            $media = [];

            for ($j = 0; $j < $remaining; $j++) {
                $isProduct = $faker->boolean(90); // 90% for products, 10% for countries
                $type = $faker->randomElement($mediaTypes);

                $mediaItem = [
                    'mediable_type' => $isProduct ? 'App\Models\Product' : 'App\Models\Country',
                    'mediable_id' => $isProduct ? $faker->randomElement($productIds) : $faker->randomElement($countryIds),
                    'type' => $type,
                    'url' => $type === 'image' 
                        ? $faker->imageUrl(800, 600, 'products', true)
                        : 'https://cdn.example.com/videos/' . $faker->uuid() . '.mp4',
                    'alt_text' => $faker->sentence(3),
                    'thumbnail' => $type === 'video' ? $faker->imageUrl(400, 300, 'thumbnails', true) : null,
                    'order' => $faker->numberBetween(1, 10),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $media[] = $mediaItem;
            }

            DB::table('media')->insert($media);
            $processed = 2 + ($i + 1) * $chunkSize;
            $this->command->info("  ✓ " . min($processed, $count) . " media created");

            unset($media);
            gc_collect_cycles();
        }

        $this->command->info("✅ {$count} media created successfully!");
    }
}