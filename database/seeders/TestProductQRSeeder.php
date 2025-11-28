<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\City;
use App\Models\Landmark;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestProductQRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a complete test product with city, landmarks, and media.
     * 
     * After seeding, you'll see a link in your terminal output like:
     *   🔗 Test URL: /experience/xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx
     * 
     * To open this link:
     *   - If you're on a local server: prepend your local domain, e.g. http://localhost:8000/experience/xxxxxxxx...
     *   - If deployed, use the real domain, e.g. https://yourdomain.com/experience/xxxxxxxx...
     * 
     * Copy the path given after "Test URL:" and paste it in your browser after your site root.
     */
    public function run(): void
    {
        $this->command->info("🧪 Creating test product with full QR features...");

        // Step 1: Get or create a test city
        $city = City::firstOrCreate(
            ['name' => 'غزة'],
            [
                'country_id' => 1,
                'name_ar' => 'غزة',
                'name_en' => 'Gaza',
                'native_name' => 'غزة',
                'region' => 'Middle East',
                'subregion' => 'Palestine',
                'latitude' => 31.9454,
                'longitude' => 35.2338,
                'population' => 2000000,
                'description' => 'مدينة ساحلية عريقة على البحر المتوسط غنية بالتراث والثقافة الفلسطينية الأصيلة',
                'description_ar' => 'مدينة ساحلية عريقة على البحر المتوسط غنية بالتراث والثقافة الفلسطينية الأصيلة',
                'description_en' => 'An ancient coastal city on the Mediterranean rich with Palestinian heritage and culture',
            ]
        );
        $this->command->info("✓ City: {$city->name}");

        // Step 2: Create a test product
        $product = Product::create([
            'uuid' => Str::uuid(),
            'city_id' => $city->id,
            'name' => 'تجربة غزة التفاعلية',
            'name_ar' => 'تجربة غزة التفاعلية',
            'name_en' => 'Gaza Interactive Experience',
            'title' => 'اكتشف جمال وتاريخ غزة من خلال تجربة تفاعلية فريدة',
            'short_description' => 'منتج فريد يجمع بين الموضة والثقافة والتاريخ',
            'description' => 'هذا المنتج المميز يأخذك في رحلة استكشافية عبر معالم غزة التاريخية والثقافية، مع صور وفيديوهات وتسجيلات صوتية تعريفية.',
            'description_ar' => 'رحلة استكشافية عبر معالم غزة التاريخية والثقافية',
            'description_en' => 'An exploratory journey through Gaza\'s historical and cultural landmarks',
            'color' => 'أسود وذهبي',
            'colors' => json_encode(['#000000', '#FFD700', '#FFFFFF']),
            'sizes' => json_encode(['S', 'M', 'L', 'XL', 'XXL']),
            'price_cost' => 100,
            'price_sell' => 150,
            'price' => 79.99,
            'discount' => 20,
            'image' => 'https://picsum.photos/seed/gaza-test-product/600/600?random=1',
            'qr_code' => 'test-qr-code.png',
            'is_package' => true,
            'published' => true,
        ]);
        $this->command->info("✓ Product created: {$product->name} (UUID: {$product->uuid})");

        // Step 3: Create test landmarks with timeline and ambient data
        $landmarkData = [
            [
                'name' => 'الجامع الأموي الكبير',
                'name_ar' => 'الجامع الأموي الكبير',
                'name_en' => 'The Great Umayyad Mosque',
                'type' => 'مسجد',
                'short_description' => 'من أقدم المساجس الأثرية في غزة',
                'description' => 'مسجد تاريخي يعود للعصر الأموي، يتميز بعمارة إسلامية تقليدية فريدة. يحتوي على زخارف إسلامية جميلة ومآذن عالية.',
                'ambient' => 'تسمع وقع خطواتك على الأحجار القديمة وتشم رائحة البخور الفلسطيني التقليدي وهو يتسلل عبر الأروقة المزخرفة. أضواء الشمس الذهبية تخترق النوافذ الملونة مرسلة ظلالاً نقشية على الأرضيات الفسيفسائية.',
                'timeline' => [
                    'القرن الثامن: بناء المسجد في العصر الأموي كمركز ديني وثقافي',
                    'القرون الوسطى: التوسعات والإضافات المعمارية وتزيين المحاريب',
                    'العصر الحديث: الترميم والحفاظ على التراث للأجيال القادمة',
                ],
            ],
            [
                'name' => 'سوق الشجاعية التقليدي',
                'name_ar' => 'سوق الشجاعية التقليدي',
                'name_en' => 'Al-Shuja\'iya Traditional Market',
                'type' => 'سوق',
                'short_description' => 'أقدم أسواق غزة التجارية',
                'description' => 'سوق تقليدي عريق يضم المئات من المتاجر والحرفيين، مكان حي يعكس الثقافة الفلسطينية الأصيلة.',
                'ambient' => 'ضجة الأصوات المحتدمة للباعة ينادون على بضائعهم، رائحة التوابل والعطور الشرقية تملأ الأرجاء. ألوان زاهية من الحرير والحلي تتلألأ تحت أضواء الفوانيس الذهبية. صخب حي يعكس ألف سنة من التجارة والحرفة.',
                'timeline' => [
                    'القرن الثاني عشر: نشأة السوق كمركز تجاري ودعم للاقتصاد المحلي',
                    'العصور الوسطى حتى الحديثة: ازدهار تجاري مستمر وتطور الحرف اليدوية',
                    'اليوم: حفاظ على التراث والتقاليس في عالم معاصر متسارع',
                ],
            ],
            [
                'name' => 'شاطئ غزة البحري',
                'name_ar' => 'شاطئ غزة البحري',
                'name_en' => 'Gaza Beach',
                'type' => 'حديقة',
                'short_description' => 'الشاطئ الرملي الجميل على بحر المتوسط',
                'description' => 'شاطئ ساحر يتمتع برمال ذهبية ومياه صافية، مكان مثالي للاستجمام والتأمل.',
                'ambient' => 'صوت الموج الهادئ يصطدم برفق بالرمال الذهبية، رائحة الملح والنسيم البحري تلامس وجهك. الشمس تغيب ببطء على الأفق ترسم لوحة من الذهب والبرتقالي. هدوء سلام يخيم على المكان، مزيج من طقطقة الحصى وندف الطيور البحرية.',
                'timeline' => [
                    'العصور القديمة: ميناء تجاري حيوي وموقع استراتيجي للملاحة البحرية',
                    'العصر الوسيط: مكان اجتماع ولقاء للتجار والمسافرين من مختلف الأقطار',
                    'الحاضر: ملاذ الاستجمام والجمال الطبيعي للسكان والزوار',
                ],
            ],
        ];

        foreach ($landmarkData as $ldata) {
            // Check if landmark already exists
            $slug = Str::slug($ldata['name']);
            $existingLandmark = Landmark::where('slug', $slug)->where('city_id', $city->id)->first();
            
            if ($existingLandmark) {
                // Update existing landmark
                $landmark = $existingLandmark;
                $landmark->update([
                    'name_ar' => $ldata['name_ar'] ?? $ldata['name'],
                    'name_en' => $ldata['name_en'] ?? $ldata['name'],
                    'ambient_description' => $ldata['ambient'] ?? null,
                    'ambient_description_ar' => $ldata['ambient'] ?? null,
                    'ambient_description_en' => $ldata['ambient'] ?? null,
                    'timeline' => $ldata['timeline'] ?? [],
                ]);
                $this->command->info("✓ Landmark updated: {$landmark->name}");
            } else {
                // Create new landmark
                $landmark = Landmark::create([
                    'city_id' => $city->id,
                    'name' => $ldata['name'],
                    'name_ar' => $ldata['name_ar'] ?? $ldata['name'],
                    'name_en' => $ldata['name_en'] ?? $ldata['name'],
                    'slug' => $slug,
                    'type' => $ldata['type'],
                    'short_description' => $ldata['short_description'],
                    'description' => $ldata['description'],
                    'ambient_description' => $ldata['ambient'] ?? null,
                    'ambient_description_ar' => $ldata['ambient'] ?? null,
                    'ambient_description_en' => $ldata['ambient'] ?? null,
                    'timeline' => $ldata['timeline'] ?? [],
                    'image' => 'https://picsum.photos/seed/' . Str::slug($ldata['name']) . '/400/300?random=1',
                ]);
                $this->command->info("✓ Landmark created: {$landmark->name}");
            }

            // Add media to landmark
            $this->addMediaToModel($landmark, 'landmark');
        }

        $this->command->info("✅ Test product and all related data created successfully!");
        $this->command->info("🔗 Test URL: /experience/{$product->uuid}");
        $this->command->info("👉 To open this link, prepend your domain. Example:");
        $this->command->info("    http://localhost:8000/experience/{$product->uuid}");
    }

    /**
     * Add sample media (images, videos, audio) to a model.
     *
     * @param $model
     * @param string $type
     * @param bool $withVideo
     */
    private function addMediaToModel($model, $type, $withVideo = true)
    {
        // Check if media already exists for this model
        if ($model->media()->exists()) {
            $this->command->info("  ✓ Media already exists for {$model->name}");
            return;
        }

        // Add sample images
        for ($i = 1; $i <= 2; $i++) {
            Media::create([
                'mediable_id' => $model->id,
                'mediable_type' => get_class($model),
                'type' => 'image',
                'role' => $i === 1 ? 'main' : 'sub',
                'url' => 'https://picsum.photos/seed/' . $model->id . '-img-' . $i . '/400/300?random=' . $i,
                'alt_text' => (property_exists($model, 'name') ? $model->name : (property_exists($model, 'title') ? $model->title : 'عنصر')) . ' - صورة ' . $i,
                'order' => $i,
            ]);
        }

        // Add sample video (optional)
        if ($withVideo) {
            Media::create([
                'mediable_id' => $model->id,
                'mediable_type' => get_class($model),
                'type' => 'video',
                'role' => 'main',
                'url' => 'https://commondatastorage.googleapis.com/gtv-videos-library/sample/BigBuckBunny.mp4',
                'alt_text' => 'فيديو توضيحي لـ ' . (property_exists($model, 'name') ? $model->name : (property_exists($model, 'title') ? $model->title : 'عنصر')),
                'order' => 3,
            ]);
        }
        $this->command->info("  ✓ Media added for {$model->name}");
    }
}
