<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\NewsBar;
use App\Models\NewsBarItem;

class NewsBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete all previous records
        NewsBar::truncate();
        NewsBarItem::truncate();

        // Create the news bar settings row
        $newsBar = NewsBar::create([
            'speed' => 120,
            'direction' => 'rtl',       // Default as per migration
            'pause_on_hover' => true,   // Default as per migration
            'theme' => 'dark',          // Default as per migration
            'item_space' => 40,         // Default as per migration
        ]);

        // Seed default news bar items using Eloquent
        $newsBarItems = [
            [
                'text' => 'يسرنا الإعلان عن افتتاح أول معرض "كأنك فيها" في مدينة غزة، حيث يجمع بين تجربة التسوق الفريدة والابتكار التفاعلي، ويدعو جميع سكان غزة وزوارها لاستكشاف تشكيلة المنتجات التي تمثل روح كل مدينة فلسطينية وتعكس حكاياتها الممتدة عبر الأجيال.',
                'active' => true,
                'order' => 1,
            ],
            [
                'text' => 'استفيدوا من خصومات مذهلة تصل إلى 50٪ على جميع منتجات المدن في معرضنا المميز، واشتروا هداياكم الفريدة التي تجسد التاريخ الفلسطيني وتراثه العريق، مع ضمان الجودة والتصاميم الحصرية المستوحاة من ثقافة مدننا العريقة.',
                'active' => true,
                'order' => 2,
            ],
            [
                'text' => 'ندعوكم للانضمام إلينا في تجربة تفاعلية لا مثيل لها تتيح لكم معرفة القصص الخفية وراء كل منتج عبر تقنية QR المبتكرة، واستكشاف تفاصيل المدن الفلسطينية بطريقة جديدة تدمج بين الثقافة والحداثة.',
                'active' => true,
                'order' => 3,
            ],
            [
                'text' => 'كل منتج في مجموعتنا يحمل في تفاصيله قصة مدينة فلسطينية نابضة بالحياة، ليكون لكل قطعة حكاية تروى وتصميم ينقل رسالة أمل ومحبة وفخر بالهوية الفلسطينية ❤️ شاركونا الاحتفاء بهذا المشروع المُلهم.',
                'active' => true,
                'order' => 4,
            ],
        ];

        foreach ($newsBarItems as $item) {
            $newsBar->items()->create($item);
        }
    }
}
