<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Do not use $this->command->option('count') to avoid "The 'count' option does not exist." error
        $count = 1000;
        $faker = Faker::create('ar_SA');
        
        $this->command->info("🏙️ Creating {$count} cities...");

        // Get country IDs
        $countryIds = Country::pluck('id')->toArray();
        
        if (empty($countryIds)) {
            $this->command->error('❌ Please run CountrySeeder first!');
            return;
        }

        // Create Gaza first (essential data)
        City::firstOrCreate(
            ['name' => 'غزة', 'country_id' => 1],
            [
                'native_name' => 'Gaza',
                'region' => 'غزة',
                'subregion' => 'غزة',
                'latitude' => 31.5,
                'longitude' => 34.47,
            ]
        );

        // Create cities in chunks
        $chunkSize = 5000;
        $chunks = ceil(($count - 1) / $chunkSize);

        for ($i = 0; $i < $chunks; $i++) {
            $remaining = min($chunkSize, $count - 1 - ($i * $chunkSize));
            $cities = [];

            for ($j = 0; $j < $remaining; $j++) {
                $cities[] = [
                    'country_id' => $faker->randomElement($countryIds),
                    'name' => $faker->city(),
                    'native_name' => $faker->city(),
                    // Fix: use $faker->city() for 'region' to avoid unknown "state" format error
                    'region' => $faker->city(),
                    'subregion' => $faker->citySuffix(),
                    'latitude' => $faker->latitude(),
                    'longitude' => $faker->longitude(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            DB::table('cities')->insert($cities);
            $this->command->info("  ✓ " . min((($i + 1) * $chunkSize), $count) . " cities created");
        }

        $this->command->info("✅ {$count} cities created successfully!");
    }
}