<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         Product::create([
            'country_id' => 1,
            'name' => 'هودي غزة',
            'title' => '   قطعة تعبّر عن مدينة غزة — كل تفصيلة تروي قصة ',
            'short_description' => 'هودي أنيق مستوحى من ثقافة غزة مع رمز QR فريد.',
            'description' => 'مستوحى من ألوان شاطئ غزة وغروبها الذهبي، هذا الهودي جزء من سلسلة «كأنك فيها» التي تدمج بين الموضة والهوية الثقافية. امسح الرمز لتتعرف على القصة الكاملة والمشاهد التي ألهمت التصميم. ',
            'color' => 'أسود',
            'sizes' => json_encode(['S', 'M', 'L', 'XL']),
            'price_cost' => 80,
            'price_sell' => 130,
            'discount' => 15,
            'uuid' => Str::uuid(),
            'qr_code' => 'qr_gaza.png',
            'published' => true,
        ]);
    }
}
