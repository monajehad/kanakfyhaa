<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{City, Landmark};
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class LandmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 100; // Create exactly 100 landmarks
        $faker = Faker::create('ar_SA');
        
        $this->command->info("🏛️ Creating {$count} landmarks...");

        $cities = City::all();
        
        if ($cities->isEmpty()) {
            $this->command->error('❌ Please run CitySeeder first!');
            return;
        }

        $landmarkTypes = ['مسجد', 'كنيسة', 'متحف', 'قلعة', 'سوق', 'مدرسة', 'مكتبة', 'حديقة', 'نصب تذكاري', 'قصر'];

        for ($i = 1; $i <= $count; $i++) {
            $city = $cities->random();
            $name = $faker->randomElement($landmarkTypes) . ' ' . $city->name;

            // Create unique slug
            $slugBase = Str::slug($name);
            $slug = $slugBase;
            $counter = 1;
            while (Landmark::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            Landmark::create([
                'city_id' => $city->id,
                'name' => $name,
                'slug' => $slug,
                'type' => $faker->randomElement($landmarkTypes),
                'short_description' => 'أحد المعالم الشهيرة في ' . $city->name,
                'description' => $faker->paragraph(3),
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop',

            ]);

            if ($i % 10 == 0) {
                $this->command->info("  ✓ {$i} landmarks created");
            }
        }

        $this->command->info("✅ {$count} landmarks created successfully!");
    }
}
