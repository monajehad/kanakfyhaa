<?php

namespace Database\Seeders;

use App\Models\Slider;
use Illuminate\Database\Seeder;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = [
            [
                'title' => 'هوديهات فلسطينية أصيلة',
                'description' => 'عبر عن حبك لمدينتك مع تصاميم فريدة وأصيلة',
                'link' => null,
                'active' => true,
                'order' => 1,
            ],
            [
                'title' => 'اكتشف المجموعة الجديدة',
                'description' => 'تصاميم حصرية تجمع بين الأصالة والعصرية',
                'link' => null,
                'active' => true,
                'order' => 2,
            ],
            [
                'title' => 'كأنك فيها',
                'description' => 'ارتدِ فخرك وحبك لمدينتك في كل مكان',
                'link' => null,
                'active' => true,
                'order' => 3,
            ],
        ];

        foreach ($sliders as $slider) {
            Slider::create($slider);
        }
    }
}
