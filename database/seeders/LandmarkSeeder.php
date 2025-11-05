<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\{City, Landmark};
use Illuminate\Support\Str;

class LandmarkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */public function run(): void
{
   $cities = City::all();

    // افترض أن لديك $cities جاهزة وعداد $i
    foreach ($cities as $city) {
        for ($i = 1; $i <= 10; $i++) { // مثال: إنشاء 10 معالم لكل مدينة
            $name = "معلم $i في " . $city->name;

            // إنشاء slug أساسي
            $slugBase = \Illuminate\Support\Str::slug($name);

            // التأكد من تفرد slug
            $slug = $slugBase;
            $counter = 1;
            while (\App\Models\Landmark::where('slug', $slug)->exists()) {
                $slug = $slugBase . '-' . $counter;
                $counter++;
            }

            // إنشاء المعلم
            Landmark::create([
                'city_id' => $city->id,
                'name' => $name,
                'slug' => $slug,
                'type' => 'مسجد', 

                'short_description' => 'أحد المعالم الشهيرة في ' . $city->name,
                'description' => 'تفاصيل المعلم التاريخي ' . $name,
            ]);
        }
    }
}

}
