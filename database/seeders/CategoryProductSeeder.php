<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 300;
        
        $this->command->info("🔗 Linking products to categories ({$count} relations)...");

        // Get the IDs
        $productIds = Product::pluck('id')->toArray();
        $categoryIds = Category::pluck('id')->toArray();

        if (empty($productIds) || empty($categoryIds)) {
            $this->command->error('❌ Please run ProductSeeder and CategorySeeder first!');
            return;
        }

        // Create the base relation
        DB::table('category_product')->insertOrIgnore([
            ['product_id' => 1, 'category_id' => 1]
        ]);

        // Create random relations in large batches
        $chunkSize = 10000;
        $chunks = ceil(($count - 1) / $chunkSize);
        $createdRelations = 0;

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 1 - ($i * $chunkSize));
            $relations = [];

            for ($j = 0; $j < $remaining; $j++) {
                // Each product can belong to 1-3 categories
                $productId = $productIds[array_rand($productIds)];
                $categoryId = $categoryIds[array_rand($categoryIds)];

                // Avoid duplicates using composite key
                $relations["{$productId}_{$categoryId}"] = [
                    'product_id' => $productId,
                    'category_id' => $categoryId,
                ];
            }

            if (!empty($relations)) {
                DB::table('category_product')->insertOrIgnore(array_values($relations));
                $createdRelations += count($relations);
                $this->command->info("  ✓ {$createdRelations} relations created");
            }

            unset($relations);
            gc_collect_cycles();
        }

        $this->command->info("✅ {$createdRelations} relations created successfully!");
    }
}