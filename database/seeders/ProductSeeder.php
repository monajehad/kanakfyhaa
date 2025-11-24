<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\City;
use App\Models\ProductPackage;
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

        // Create sample products for Gaza, Jerusalem, and Hebron
        $sampleProducts = [
            // Gaza Products
            [
                'uuid' => 'gaza-hoodie-001',
                'city_id' => 1,
                'name' => 'هودي غزة الكلاسيكي',
                'name_ar' => 'هودي غزة الكلاسيكي',
                'name_en' => 'Gaza Classic Hoodie',
                'title' => 'قطعة تعبّر عن مدينة غزة — كل تفصيلة تروي قصة',
                'short_description' => 'هودي أنيق مستوحى من ثقافة غزة مع رمز QR فريد.',
                'description' => 'مستوحى من ألوان شاطئ غزة وغروبها الذهبي، هذا الهودي جزء من سلسلة «كأنك فيها» التي تدمج بين الموضة والهوية الثقافية.',
                'description_ar' => 'تصميم عصري يعبر عن صمود وجمال غزة',
                'description_en' => 'Modern design expressing Gaza\'s resilience and beauty',
                'color' => 'أسود',
                'colors' => json_encode(['#000000', '#FFFFFF', '#C8D400']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price_cost' => 80,
                'price_sell' => 130,
                'price' => 49.99,
                'discount' => 15,
                'image' => 'https://picsum.photos/seed/gaza-hoodie-001/600/600',
                'qr_code' => 'qr_gaza.png',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'gaza-hoodie-002',
                'city_id' => 1,
                'name' => 'هودي غزة العتيقة',
                'name_ar' => 'هودي غزة العتيقة',
                'name_en' => 'Gaza Heritage Hoodie',
                'description' => 'يحمل روح التاريخ والأصالة',
                'description_ar' => 'يحمل روح التاريخ والأصالة',
                'description_en' => 'Carries the spirit of history and authenticity',
                'color' => 'بني',
                'colors' => json_encode(['#000000', '#8B4513', '#2F4F4F']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price' => 54.99,
                'price_sell' => 54.99,
                'image' => 'https://picsum.photos/seed/gaza-hoodie-002/600/600',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'gaza-hoodie-003',
                'city_id' => 1,
                'name' => 'هودي غزة المودرن',
                'name_ar' => 'هودي غزة المودرن',
                'name_en' => 'Gaza Modern Hoodie',
                'description_ar' => 'تصميم عصري وجريء',
                'description_en' => 'Contemporary and bold design',
                'color' => 'أزرق',
                'colors' => json_encode(['#1E3A8A', '#DC2626', '#C8D400']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'price' => 52.99,
                'price_sell' => 52.99,
                'image' => 'https://picsum.photos/seed/gaza-hoodie-003/600/600',
                'is_package' => true,
                'published' => true,
            ],
            // Jerusalem Products
            [
                'uuid' => 'jerusalem-hoodie-001',
                'city_id' => 2,
                'name' => 'هودي القدس التراثي',
                'name_ar' => 'هودي القدس التراثي',
                'name_en' => 'Jerusalem Heritage Hoodie',
                'description_ar' => 'يحمل عبق التاريخ والقدسية',
                'description_en' => 'Carries the fragrance of history and sanctity',
                'color' => 'ذهبي',
                'colors' => json_encode(['#DAA520', '#000000', '#FFFFFF']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price' => 59.99,
                'price_sell' => 59.99,
                'image' => 'https://picsum.photos/seed/jerusalem-hoodie-001/600/600',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'jerusalem-hoodie-002',
                'city_id' => 2,
                'name' => 'هودي القدس المقدسة',
                'name_ar' => 'هودي القدس المقدسة',
                'name_en' => 'Jerusalem Sacred Hoodie',
                'description_ar' => 'تصميم يليق بأولى القبلتين',
                'description_en' => 'Design worthy of the first qibla',
                'color' => 'أسود',
                'colors' => json_encode(['#000000', '#C8D400', '#8B4513']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price' => 64.99,
                'price_sell' => 64.99,
                'image' => 'https://picsum.photos/seed/jerusalem-hoodie-002/600/600',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'jerusalem-hoodie-003',
                'city_id' => 2,
                'name' => 'هودي القدس الذهبية',
                'name_ar' => 'هودي القدس الذهبية',
                'name_en' => 'Jerusalem Golden Hoodie',
                'description_ar' => 'كقبة الصخرة في جمالها',
                'description_en' => 'Like the Dome of the Rock in its beauty',
                'color' => 'ذهبي',
                'colors' => json_encode(['#DAA520', '#1E3A8A', '#FFFFFF']),
                'sizes' => json_encode(['M', 'L', 'XL', 'XXL']),
                'price' => 69.99,
                'price_sell' => 69.99,
                'image' => 'https://picsum.photos/seed/jerusalem-hoodie-003/600/600',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'jerusalem-hoodie-004',
                'city_id' => 2,
                'name' => 'هودي القدس العريقة',
                'name_ar' => 'هودي القدس العريقة',
                'name_en' => 'Jerusalem Ancient Hoodie',
                'description_ar' => 'عراقة وأصالة لا تنتهي',
                'description_en' => 'Endless heritage and authenticity',
                'color' => 'بني',
                'colors' => json_encode(['#8B4513', '#000000', '#C8D400']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price' => 62.99,
                'price_sell' => 62.99,
                'image' => 'https://picsum.photos/seed/jerusalem-hoodie-004/600/600',
                'is_package' => true,
                'published' => true,
            ],
            // Hebron Products
            [
                'uuid' => 'hebron-hoodie-001',
                'city_id' => 3,
                'name' => 'هودي الخليل الأصيل',
                'name_ar' => 'هودي الخليل الأصيل',
                'name_en' => 'Hebron Authentic Hoodie',
                'description_ar' => 'يعكس عراقة مدينة الخليل',
                'description_en' => 'Reflects Hebron\'s ancient heritage',
                'color' => 'أسود',
                'colors' => json_encode(['#000000', '#8B4513', '#FFFFFF']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
                'price' => 54.99,
                'price_sell' => 54.99,
                'image' => 'https://picsum.photos/seed/hebron-hoodie-001/600/600',
                'is_package' => true,
                'published' => true,
            ],
            [
                'uuid' => 'hebron-hoodie-002',
                'city_id' => 3,
                'name' => 'هودي الخليل التراثي',
                'name_ar' => 'هودي الخليل التراثي',
                'name_en' => 'Hebron Heritage Hoodie',
                'description_ar' => 'تراث وتاريخ في قطعة واحدة',
                'description_en' => 'Heritage and history in one piece',
                'color' => 'رمادي',
                'colors' => json_encode(['#2F4F4F', '#C8D400', '#000000']),
                'sizes' => json_encode(['S', 'M', 'L', 'XL']),
                'price' => 57.99,
                'price_sell' => 57.99,
                'image' => 'https://picsum.photos/seed/hebron-hoodie-002/600/600',
                'is_package' => true,
                'published' => true,
            ],
        ];

        foreach ($sampleProducts as $product) {
            Product::firstOrCreate(
                ['uuid' => $product['uuid']],
                $product
            );
        }

        // Ensure sample products have packages when flagged
        foreach ($sampleProducts as $productData) {
            if (!empty($productData['is_package'])) {
                $p = Product::where('uuid', $productData['uuid'])->first();
                if ($p && !$p->package) {
                    $p->package()->create([
                        'name' => $productData['name'] . ' Package',
                        'name_ar' => ($productData['name_ar'] ?? $productData['name']) . ' باقة',
                        'name_en' => ($productData['name_en'] ?? $productData['name']) . ' Package',
                        'description' => $productData['description'] ?? null,
                        'description_ar' => $productData['description_ar'] ?? null,
                        'description_en' => $productData['description_en'] ?? null,
                        'items' => json_encode([
                            ['name' => $productData['name'], 'name_ar' => $productData['name_ar'] ?? $productData['name']]
                        ]),
                        'price' => $productData['price'] ?? ($productData['price_sell'] ?? 0),
                        'original_price' => $productData['price_sell'] ?? ($productData['price'] ?? null),
                        'discount' => $productData['discount'] ?? 0,
                        'shipping_price' => $productData['shipping_price'] ?? 0,
                        'quantity' => 1,
                        'is_active' => true,
                        'order' => 0,
                    ]);
                }
            }
        }

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

                $productName = $faker->randomElement($productTypes) . ' ' . $faker->randomElement($cities);
                $productColors = ['#000000', '#FFFFFF', '#C8D400', '#8B4513', '#2F4F4F', '#DAA520', '#1E3A8A', '#DC2626'];
                $selectedColors = $faker->randomElements($productColors, $faker->numberBetween(2, 4));
                
                $products[] = [
                    'city_id' => $faker->randomElement($cityIds),
                    'name' => $productName,
                    'name_ar' => $productName,
                    'name_en' => $productName,
                    'title' => 'قطعة تعبّر عن ' . $faker->randomElement($cities) . ' — ' . $faker->sentence(5),
                    'short_description' => $faker->sentence(10),
                    'description' => $faker->paragraph(3),
                    'description_ar' => $faker->paragraph(2),
                    'description_en' => $faker->paragraph(2),
                    'color' => $faker->randomElement($colors),
                    'colors' => json_encode($selectedColors),
                    'sizes' => json_encode($faker->randomElements(['XS', 'S', 'M', 'L', 'XL', 'XXL'], $faker->numberBetween(3, 6))),
                    'price_cost' => $priceCost,
                    'price_sell' => $priceSell,
                    'price' => $priceSell - ($priceSell * $discount / 100),
                    'discount' => $discount,
                    'uuid' => Str::uuid(),
                    'qr_code' => 'qr_' . Str::random(10) . '.png',
                    'image' => 'https://picsum.photos/seed/' . Str::uuid() . '/600/600',
                    'is_package' => $faker->boolean(70), // 70% are packages
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

        // Create packages for any products inserted in bulk that are flagged as packages
        $this->command->info('🔧 Creating packages for seeded products flagged as packages...');

        Product::where('is_package', true)
            ->doesntHave('package')
            ->chunk(200, function ($products) use ($faker) {
                foreach ($products as $product) {
                    // Build a small items list for the package
                    $items = [
                        [
                            'name' => $product->name,
                            'name_ar' => $product->name_ar ?? $product->name,
                        ]
                    ];

                    // Optionally add 1-2 extra items
                    if (rand(0, 1) === 1) {
                        $items[] = [
                            'name' => $product->name . ' - إضافي',
                            'name_ar' => ($product->name_ar ?? $product->name) . ' - إضافي',
                        ];
                    }

                    $price = $product->price ?? ($product->price_sell ?? 0);

                    $product->package()->create([
                        'name' => $product->name . ' Package',
                        'name_ar' => ($product->name_ar ?? $product->name) . ' باقة',
                        'name_en' => ($product->name_en ?? $product->name) . ' Package',
                        'description' => $product->description ?? null,
                        'description_ar' => $product->description_ar ?? null,
                        'description_en' => $product->description_en ?? null,
                        'items' => json_encode($items),
                        'price' => $price,
                        'original_price' => $product->price_sell ?? $price,
                        'discount' => $product->discount ?? 0,
                        'shipping_price' => rand(5, 25),
                        'quantity' => 1,
                        'is_active' => true,
                        'order' => 0,
                    ]);
                }
            });

        $this->command->info('🔧 Packages creation completed.');
    }
}