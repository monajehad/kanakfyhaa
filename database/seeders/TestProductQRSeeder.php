<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\City;
use App\Models\Landmark;
use App\Models\Artifact;
use App\Models\Media;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TestProductQRSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Creates a complete test product with city, landmarks, artifacts, and media.
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

        // Step 3: Create test landmarks with artifacts and media
        $landmarkData = [
            [
                'name' => 'الجامع الأموي الكبير',
                'type' => 'مسجد',
                'short_description' => 'من أقدم المساجس الأثرية في غزة',
                'description' => 'مسجد تاريخي يعود للعصر الأموي، يتميز بعمارة إسلامية تقليدية فريدة. يحتوي على زخارف إسلامية جميلة ومآذن عالية.',
                'artifacts' => [
                    [
                        'title' => 'المحراب الأصلي',
                        'short_description' => 'محراب من الحجر المنحوت بزخارف إسلامية',
                        'description' => 'تحفة معمارية نادرة من القرن الثامن الميلادي. التاريخ التفصيلي: تم بناء هذا المحراب عام 720م في ظل الخلافة الأموية ويعد من أقدم المحاريب في المنطقة، ويمثل مرحلة تطور فنون الحجارة الإسلامية في تلك الحقبة.',
                    ],
                    [
                        'title' => 'المنبر التاريخي',
                        'short_description' => 'منبر خشبي منحوت بفن العمارة الإسلامية',
                        'description' => 'يعود إلى القرون الوسطى، محفوظ بعناية. التاريخ التفصيلي: صُنع المنبر في القرن الثاني عشر الميلادي خلال العصر الأيوبي، ويتميز بنقوش معقدة وزخارف تراثية تعكس براعة الحرفيين المحليين في تلك الفترة.',
                    ],
                ],
            ],
            [
                'name' => 'سوق الشجاعية التقليدي',
                'type' => 'سوق',
                'short_description' => 'أقدم أسواق غزة التجارية',
                'description' => 'سوق تقليدي عريق يضم المئات من المتاجر والحرفيين، مكان حي يعكس الثقافة الفلسطينية الأصيلة.',
                'artifacts' => [
                    [
                        'title' => 'دكاكين الصياغة التقليدية',
                        'short_description' => 'محلات صياغة ذهب وفضة تقليدية',
                        'description' => 'يعمل فيها الصاغة على إنتاج الحلي التقليدية بفن عريق. التاريخ التفصيلي: تعود نشأة هذه الدكاكين إلى مطلع القرن العشرين، حيث كانت تصنع الحلي بشكل يدوي كامل بناءً على التصاميم التراثية الفلسطينية المتوارثة.',
                    ],
                    [
                        'title' => 'متاجر الحرف اليدوية',
                        'short_description' => 'متاجر متخصصة في الحرف الفلسطينية التقليدية',
                        'description' => 'تعرض التطريز والخزف والمنسوجات التقليدية. التاريخ التفصيلي: نشأت هذه المتاجر في منتصف القرن العشرين وتشتهر بعرض منتجات مستخلصة من التراث الغزي، خصوصًا المطرزات والخزف ذي الزخارف المحلية.',
                    ],
                ],
            ],
            [
                'name' => 'شاطئ غزة البحري',
                'type' => 'حديقة',
                'short_description' => 'الشاطئ الرملي الجميل على بحر المتوسط',
                'description' => 'شاطئ ساحر يتمتع برمال ذهبية ومياه صافية، مكان مثالي للاستجمام والتأمل.',
                'artifacts' => [
                    [
                        'title' => 'برج الملاحة البحرية',
                        'short_description' => 'برج تاريخي يطل على البحر',
                        'description' => 'استخدم في الملاحة البحرية القديمة. التاريخ التفصيلي: يُعتقد أن البرج بني في القرن التاسع عشر لمساعدة السفن على الملاحة، وقد كان يعتبر من أهم معالم الساحل في تلك الحقبة.',
                    ],
                ],
            ],
        ];

        foreach ($landmarkData as $ldata) {
            $landmark = Landmark::create([
                'city_id' => $city->id,
                'name' => $ldata['name'],
                'slug' => Str::slug($ldata['name']),
                'type' => $ldata['type'],
                'short_description' => $ldata['short_description'],
                'description' => $ldata['description'],
                'image' => 'https://picsum.photos/seed/' . Str::slug($ldata['name']) . '/400/300?random=1',
            ]);
            $this->command->info("✓ Landmark: {$landmark->name}");

            // Add media to landmark
            $this->addMediaToModel($landmark, 'landmark');

            // Create artifacts for this landmark
            foreach ($ldata['artifacts'] as $adata) {
                $artifact = Artifact::create([
                    'landmark_id' => $landmark->id,
                    'title' => $adata['title'],
                    'short_description' => $adata['short_description'],
                    'description' => $adata['description'],
                    'image' => 'https://picsum.photos/seed/' . Str::slug($adata['title']) . '/300/200?random=1',
                ]);
                $this->command->info("  ✓ Artifact: {$artifact->title}");

                // Add media to artifact
                // قد يكون هناك فيديو، وقد لا يكون
                $hasVideo = (bool) random_int(0, 1); // 50% chance to assign videos

                $this->addMediaToModel($artifact, 'artifact', $hasVideo);
            }
        }

        $this->command->info("✅ Test product and all related data created successfully!");
        $this->command->info("🔗 Test URL: /experience/{$product->uuid}");
        $this->command->info("👉 To open this link, prepend your domain. Example:");
        $this->command->info("    http://localhost:8000/experience/{$product->uuid}");
    }

    /**
     * Add sample media (images, videos, audio) to a model.
     * It possible to has videos and mabe not. يمكنك تعيين $withVideo = false لمنع إدراج فيديو.
     *
     * @param $model
     * @param string $type
     * @param bool $withVideo
     */
    private function addMediaToModel($model, $type, $withVideo = true)
    {
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
    }
}
