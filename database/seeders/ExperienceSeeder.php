<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Experience;

class ExperienceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Experience::insert([
            [
                'product_id' => 1,
                'user_name' => 'امل  ',
                'comment' => 'تجربة رائعة جدًا.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'product_id' => 1,
                'user_name' => 'ليلى ',
                'comment' => 'الرمز طريقة سهلة تجربة جميلة.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
