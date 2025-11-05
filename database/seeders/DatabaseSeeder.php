<?php

namespace Database\Seeders;

use App\Models\Artifact;
use App\Models\Landmark;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Disable foreign key checks for speed
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Create one Admin user (not editable)
        $this->call(AdminSeeder::class);

        // Seed core data with customizable counts
        $this->call(CountrySeeder::class, false, ['count' => 50]);
        $this->call(CitySeeder::class, false, ['count' => 1000]);
        $this->call(CategorySeeder::class, false, ['count' => 100]);
        
        // Products and their relations
        $this->call(ProductSeeder::class, false, ['count' => 1000000]); // 1 million products
        $this->call(CategoryProductSeeder::class, false, ['count' => 2000000]); // Multiple relations
        $this->call(MediaSeeder::class, false, ['count' => 3000000]); // 3 media per product
        $this->call(ExperienceSeeder::class, false, ['count' => 5000000]); // 5 experiences per product


        $this->call(NewsBarSeeder::class);
        $this->call(LandmarkSeeder::class);
        $this->call(ArtifactSeeder::class);


        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ All data has been seeded successfully!');
    }
}