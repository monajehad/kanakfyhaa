<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{Landmark, Artifact};

class ArtifactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $landmarks = Landmark::all();

        foreach ($landmarks as $landmark) {
            for ($i = 1; $i <= 2; $i++) {
                Artifact::create([
                    'landmark_id' => $landmark->id,
                    'title' => "تحفة {$i} من {$landmark->name}",
                    'short_description' => 'قطعة فنية  ',
                    'description' => 'تفاصيل عن هذه التحفة .',
                ]);
            }
        }
    }
}
