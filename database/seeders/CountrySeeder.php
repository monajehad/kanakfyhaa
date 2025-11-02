<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Country::create([
            'name' => 'فلسطين',
            'native_name' => 'Palestine',
            'iso2' => 'Pl',
            'iso3' => 'PlE',
            'numeric_code' => '298',
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
        ]);
    }
}
