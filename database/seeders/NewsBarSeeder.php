<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class NewsBarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear previous settings
        DB::table('news_bar')->truncate();

        // Insert the single row for `news_bar` table (settings)
        DB::table('news_bar')->insert([
            'speed' => 120,
            'direction' => 'rtl',       // Default as per migration
            'pause_on_hover' => true,   // Default as per migration
            'theme' => 'dark',          // Default as per migration
            'item_space' => 40,         // Default as per migration
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Clear previous news bar items
        DB::table('news_bar_items')->truncate();

        // Seed default news bar items
        $newsBarItems = [
            [
                'text' => 'يسرنا الإعلان عن افتتاح أول معرض "كأنك فيها" في مدينة غزة، حيث يجمع بين تجربة التسوق الفريدة والابتكار التفاعلي، ويدعو جميع سكان غزة وزوارها لاستكشاف تشكيلة المنتجات التي تمثل روح كل مدينة فلسطينية وتعكس حكاياتها الممتدة عبر الأجيال.',
                'active' => true,
                'order' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'text' => 'استفيدوا من خصومات مذهلة تصل إلى 50٪ على جميع منتجات المدن في معرضنا المميز، واشتروا هداياكم الفريدة التي تجسد التاريخ الفلسطيني وتراثه العريق، مع ضمان الجودة والتصاميم الحصرية المستوحاة من ثقافة مدننا العريقة.',
                'active' => true,
                'order' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'text' => 'ندعوكم للانضمام إلينا في تجربة تفاعلية لا مثيل لها تتيح لكم معرفة القصص الخفية وراء كل منتج عبر تقنية QR المبتكرة، واستكشاف تفاصيل المدن الفلسطينية بطريقة جديدة تدمج بين الثقافة والحداثة.',
                'active' => true,
                'order' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'text' => 'كل منتج في مجموعتنا يحمل في تفاصيله قصة مدينة فلسطينية نابضة بالحياة، ليكون لكل قطعة حكاية تروى وتصميم ينقل رسالة أمل ومحبة وفخر بالهوية الفلسطينية ❤️ شاركونا الاحتفاء بهذا المشروع المُلهم.',
                'active' => true,
                'order' => 4,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('news_bar_items')->insert($newsBarItems);
    }
}
