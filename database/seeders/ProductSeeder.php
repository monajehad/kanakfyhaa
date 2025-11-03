<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\City;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 300;
        $faker = Faker::create('ar_SA');
        
        $this->command->info("🛍️ Creating {$count} products...");

        // Get city IDs
        $cityIds = City::pluck('id')->toArray();
        
        if (empty($cityIds)) {
            $this->command->error('❌ Please run CitySeeder first!');
            return;
        }

        // Create base product (Gaza Hoodie)
        Product::firstOrCreate(
            ['uuid' => 'gaza-hoodie-001'],
            [
                'city_id' => 1,
                'name' => 'هودي غزة',
                'title' => 'قطعة تعبّر عن مدينة غزة — كل تفصيلة تروي قصة',
                'short_description' => 'هودي أنيق مستوحى من ثقافة غزة مع رمز QR فريد.',
                'description' => 'مستوحى من ألوان شاطئ غزة وغروبها الذهبي، هذا الهودي جزء من سلسلة «كأنك فيها» التي تدمج بين الموضة والهوية الثقافية.',
                'color' => 'أسود',
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'price_cost' => 80,
                'price_sell' => 130,
                'discount' => 15,
                'qr_code' => 'qr_gaza.png',
                'published' => true,
            ]
        );

        // Arrays for random data
        $colors = ['أسود', 'أبيض', 'أزرق', 'أحمر', 'أخضر', 'رمادي', 'بني', 'بيج', 'وردي'];
        $productTypes = ['هودي', 'تيشيرت', 'بلوفر', 'جاكيت', 'سويت شيرت'];
        $cities = ['القدس', 'غزة', 'رام الله', 'نابلس', 'الخليل', 'بيت لحم', 'جنين', 'طولكرم'];

        // Create products in large batches
        $chunkSize = 10000; // 10k products per batch
        $chunks = ceil(($count - 1) / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 1 - ($i * $chunkSize));
            $products = [];

            for ($j = 0; $j < $remaining; $j++) {
                $priceCost = $faker->numberBetween(50, 200);
                $priceSell = $priceCost + $faker->numberBetween(30, 100);
                $discount = $faker->numberBetween(0, 30);

                $products[] = [
                    'city_id' => $faker->randomElement($cityIds),
                    'name' => $faker->randomElement($productTypes) . ' ' . $faker->randomElement($cities),
                    'title' => 'قطعة تعبّر عن ' . $faker->randomElement($cities) . ' — ' . $faker->sentence(5),
                    'short_description' => $faker->sentence(10),
                    'description' => $faker->paragraph(3),
                    'color' => $faker->randomElement($colors),
                    'sizes' => json_encode($faker->randomElements(['XS', 'S', 'M', 'L', 'XL', 'XXL'], $faker->numberBetween(3, 6))),
                    'price_cost' => $priceCost,
                    'price_sell' => $priceSell,
                    'discount' => $discount,
                    'uuid' => Str::uuid(),
                    'qr_code' => 'qr_' . Str::random(10) . '.png',
                    'published' => $faker->boolean(80), // 80% published
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('products')->insert($products);
            $processed = ($i + 1) * $chunkSize;
            $this->command->info("  ✓ " . min($processed, $count) . " products created");
            
            // Free memory
            unset($products);
            gc_collect_cycles();
        }

        $this->command->info("✅ {$count} products created successfully!");
    }
}