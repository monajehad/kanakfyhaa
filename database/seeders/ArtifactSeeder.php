<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Landmark, Artifact};
use Faker\Factory as Faker;

class ArtifactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $count = 100; // Create exactly 100 artifacts
        $faker = Faker::create('ar_SA');
        
        $this->command->info("🎨 Creating {$count} artifacts...");

        $landmarks = Landmark::all();
        
        if ($landmarks->isEmpty()) {
            $this->command->error('❌ Please run LandmarkSeeder first!');
            return;
        }

        $artifactTypes = ['تمثال', 'لوحة', 'مخطوطة', 'عملة', 'سيف', 'خنجر', 'خاتم', 'قلادة', 'سجادة', 'مزهرية'];

        for ($i = 1; $i <= $count; $i++) {
            $landmark = $landmarks->random();
            $artifactType = $faker->randomElement($artifactTypes);

            Artifact::create([
                'landmark_id' => $landmark->id,
                'title' => "{$artifactType} من {$landmark->name}",
                'short_description' => $faker->sentence(8),
                'description' => $faker->paragraph(3),
                'image' => 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop',

            ]);

            if ($i % 10 == 0) {
                $this->command->info("  ✓ {$i} artifacts created");
            }
        }

        $this->command->info("✅ {$count} artifacts created successfully!");
    }
}
