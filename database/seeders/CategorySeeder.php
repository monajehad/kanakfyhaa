<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 100;
        $faker = Faker::create('ar_SA');
        
        $this->command->info("📂 Creating {$count} categories...");

        // Base categories
        $baseCategories = [
            ['name' => 'هودي', 'slug' => 'hoody'],
            ['name' => 'تيشيرتات', 'slug' => 'tshirts'],
            ['name' => 'سويت شيرت', 'slug' => 'sweatshirts'],
            ['name' => 'بلوفرات', 'slug' => 'pullovers'],
            ['name' => 'جاكيتات', 'slug' => 'jackets'],
        ];

        foreach ($baseCategories as $category) {
            Category::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }

        // Generate additional categories
        $productsTypes = ['قمصان', 'بناطيل', 'فساتين', 'أحذية', 'إكسسوارات', 'حقائب', 'ساعات', 'نظارات'];
        $chunkSize = 1000;
        $chunks = ceil(($count - count($baseCategories)) / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - count($baseCategories) - ($i * $chunkSize));
            $categories = [];

            for ($j = 0; $j < $remaining; $j++) {
                $name = $faker->randomElement($productsTypes) . ' ' . $faker->word();
                $categories[] = [
                    'name' => $name,
                    'slug' => Str::slug($name) . '-' . $faker->unique()->numberBetween(1000, 999999),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('categories')->insert($categories);
            $this->command->info("  ✓ " . (count($baseCategories) + ($i + 1) * $chunkSize) . " categories created");
        }

        $this->command->info("✅ {$count} categories created successfully!");
    }
}