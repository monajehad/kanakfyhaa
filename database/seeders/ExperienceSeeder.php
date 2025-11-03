<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 300;
        $faker = Faker::create('ar_SA');
        
        $this->command->info("⭐ Creating {$count} experiences/reviews...");

        // Get product IDs
        $productIds = Product::pluck('id')->toArray();

        if (empty($productIds)) {
            $this->command->error('❌ Please run ProductSeeder first!');
            return;
        }

        // Insert example experiences for the first product
        DB::table('experiences')->insertOrIgnore([
            [
                'product_id' => 1,
                'user_name' => 'أمل',
                'comment' => 'تجربة رائعة جدًا.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 1,
                'user_name' => 'ليلى',
                'comment' => 'الرمز طريقة سهلة تجربة جميلة.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Data pools
        $arabicNames = [
            'محمد', 'أحمد', 'علي', 'حسن', 'فاطمة', 'عائشة', 'مريم', 'زينب',
            'خديجة', 'سارة', 'نور', 'ليلى', 'أمل', 'ياسمين', 'دينا', 'ريم'
        ];

        $positiveComments = [
            'منتج ممتاز جدًا وجودة عالية',
            'سعيد جدًا بالشراء، أنصح به بشدة',
            'تجربة رائعة والتوصيل سريع',
            'جودة القماش ممتازة ومريح جدًا',
            'القياس مناسب والتصميم جميل',
            'منتج يستحق كل ريال دفعته فيه',
            'أفضل عملية شراء قمت بها',
        ];

        $neutralComments = [
            'المنتج جيد بشكل عام',
            'مقبول للسعر',
            'كما في الوصف',
            'لا بأس به',
        ];

        $negativeComments = [
            'القياس غير مناسب، أصغر من المتوقع',
            'الجودة أقل من المتوقع',
            'التوصيل تأخر قليلاً',
            'اللون مختلف عن الصورة',
        ];

        // Generate experiences in big batches
        $chunkSize = 10000;
        $chunks = ceil(($count - 2) / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 2 - ($i * $chunkSize));
            $experiences = [];

            for ($j = 0; $j < $remaining; $j++) {
                // 70% positive, 20% neutral, 10% negative
                $rand = $faker->numberBetween(1, 100);
                if ($rand <= 70) {
                    $comment = $faker->randomElement($positiveComments);
                } elseif ($rand <= 90) {
                    $comment = $faker->randomElement($neutralComments);
                } else {
                    $comment = $faker->randomElement($negativeComments);
                }

                $experiences[] = [
                    'product_id' => $faker->randomElement($productIds),
                    'user_name' => $faker->randomElement($arabicNames),
                    'comment' => $comment,
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ];
            }

            DB::table('experiences')->insert($experiences);
            $processed = 2 + ($i + 1) * $chunkSize;
            $this->command->info("  ✓ " . min($processed, $count) . " experiences created");

            unset($experiences);
            gc_collect_cycles();
        }

        $this->command->info("✅ {$count} experiences created successfully!");
    }
}