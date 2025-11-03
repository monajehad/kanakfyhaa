<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hardcode count (do NOT use $this->command->option('count'))
        $count = 50;
        $faker = Faker::create('ar_SA');
        
        $this->command->info("🌍 Creating {$count} countries...");

        // Create Palestine first (essential data)
        Country::firstOrCreate(
            ['iso2' => 'PS'],
            [
                'name' => 'فلسطين',
                'native_name' => 'Palestine',
                'iso3' => 'PSE',
                'numeric_code' => '275',
                'phone_code' => '+970',
                'capital' => 'القدس',
                'currency_symbol' => '₪',
                'currency_name' => 'شيكل',
                'region' => 'آسيا',
                'subregion' => 'الشرق الأوسط',
                'cities_count' => 16,
                'flag_url' => 'https://www.wafa.ps/image/NewsThumbImg/Default/1a032adb-d74a-4890-8764-2547712a21a5.jpg',
                'timezone' => 'Asia/Gaza',
                'latitude' => 31.5,
                'longitude' => 34.47,
                'population' => 5052000,
                'area' => 6221,
            ]
        );

        // Create remaining countries in chunks
        $chunkSize = 1000;
        $chunks = ceil(($count - 1) / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 1 - ($i * $chunkSize));
            $countries = [];

            for ($j = 0; $j < $remaining; $j++) {
                $countries[] = [
                    'name' => $faker->country(),
                    'native_name' => $faker->country(),
                    'iso2' => strtoupper($faker->unique()->lexify('??')),
                    'iso3' => strtoupper($faker->unique()->lexify('???')),
                    'numeric_code' => $faker->unique()->numberBetween(100, 999),
                    'phone_code' => '+' . $faker->numberBetween(1, 999),
                    'capital' => $faker->city(),
                    'currency_symbol' => $faker->currencyCode(),
                    'currency_name' => $faker->currencyCode(),
                    'region' => $faker->randomElement(['آسيا', 'أوروبا', 'أفريقيا', 'أمريكا الشمالية', 'أمريكا الجنوبية', 'أوقيانوسيا']),
                    'subregion' => $faker->randomElement(['الشرق الأوسط', 'شرق آسيا', 'غرب أوروبا', 'شمال أفريقيا']),
                    'cities_count' => $faker->numberBetween(5, 100),
                    'flag_url' => $faker->imageUrl(640, 480, 'flags', true),
                    'timezone' => $faker->timezone(),
                    'latitude' => $faker->latitude(),
                    'longitude' => $faker->longitude(),
                    'population' => $faker->numberBetween(100000, 100000000),
                    'area' => $faker->numberBetween(1000, 10000000),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('countries')->insert($countries);
            $this->command->info("  ✓ " . (($i + 1) * $chunkSize) . " countries created");
        }

        $this->command->info("✅ {$count} countries created successfully!");
    }
}