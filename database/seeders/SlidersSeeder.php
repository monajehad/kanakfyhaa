<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class SlidersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        DB::table('sliders')->insert([
            [
                'title' => 'ارتدي العالم',
                'subtitle' => 'تجربة أزياء فريدة من نوعها',
                'description' => 'اختر تيشيرتك المفضل بتصاميم مستوحاة من أجمل المدن وكن بطل القصة!',
                'image' => 'sliders/slide-01.png', // Images are assumed to be stored in storage/app/public/sliders
                'button_text' => 'اكتشف الآن',
                'button_url' => '/shop',
                'order' => 1,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'كأنك فيها',
                'subtitle' => 'تصاميم مستوحاة من التراث',
                'description' => 'عش تجربة افتراضية مستوحاة من التراث والمدن العربية بتصاميم فريدة.',
                'image' => 'sliders/slide-02.png',
                'button_text' => 'تسوق الآن',
                'button_url' => '/products',
                'order' => 2,
                'active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'عروض الصيف',
                'subtitle' => 'خصومات تصل إلى 50%',
                'description' => 'استمتع بأحدث العروض على التشيرتات والاكسسوارات الصيفية.',
                'image' => 'sliders/slide-03.png',
                'button_text' => 'تسوق العروض',
                'button_url' => '/offers',
                'order' => 3,
                'active' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }
}
