<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Ad;
use App\Models\AdImage;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductionDataSeeder extends Seeder
{
    public function run(): void
    {
        // ========== 1. CREATE ADMIN USER ==========
        $admin = User::updateOrCreate(
            ['email' => 'admin@souq.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('admin123'),
                'is_admin' => true,
                'is_active' => true,
            ]
        );

        // ========== 2. CLEAR EXISTING DATA ==========
        $this->command->info('Clearing existing data...');
        
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        AdImage::truncate();
        Ad::truncate();
        Category::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // ========== 3. CREATE ALL CATEGORIES ==========
        $this->command->info('Creating categories...');
        
        // Main categories with correct slugs
        $mainCategoriesList = [
            ['name' => 'الأجهزة المنزلية', 'slug' => 'home-appliances', 'order' => 1],
            ['name' => 'شراء السيارات', 'slug' => 'car-buying', 'order' => 2],
            ['name' => 'تأجير سيارات وحافلات', 'slug' => 'rent-car-buses', 'order' => 3],
            ['name' => 'خردة', 'slug' => 'scrap', 'order' => 4],
            ['name' => 'خدمة السحب', 'slug' => 'towing-service', 'order' => 5],
            ['name' => 'عطور', 'slug' => 'perfume', 'order' => 6],
            ['name' => 'ألواح جبسية', 'slug' => 'gypsum-boarding', 'order' => 7],
            ['name' => 'تركيب ستائر', 'slug' => 'curtain-installation', 'order' => 8],
            ['name' => 'تركيب زجاج', 'slug' => 'glass-installation', 'order' => 9],
            ['name' => 'سباكة وكهرباء', 'slug' => 'plumbing-and-electrician', 'order' => 10],
            ['name' => 'تلميع وتظليل السيارات', 'slug' => 'automobile-tint-repair', 'order' => 11],
            ['name' => 'تجديد المنازل', 'slug' => 'home-renovation', 'order' => 12],
            ['name' => 'محلات لابتوب وجوال', 'slug' => 'laptop-or-mobile-shops', 'order' => 13],
            ['name' => 'مشترو الأثاث', 'slug' => 'furniture-buyers', 'order' => 14],
            ['name' => 'نقل وعفش', 'slug' => 'movers-and-packers', 'order' => 15],
            ['name' => 'إصلاح ثقوب الإطارات', 'slug' => 'tire-puncture-repair', 'order' => 16],
        ];

        $createdCategories = [];
        
        foreach ($mainCategoriesList as $cat) {
            $category = Category::create([
                'name' => $cat['name'],
                'arabic_name' => $cat['name'],
                'slug' => $cat['slug'],
                'is_active' => true,
                'order' => $cat['order'],
            ]);
            $createdCategories[$cat['slug']] = $category;
        }

        // Add subcategories for Home Appliances
        $homeAppliances = $createdCategories['home-appliances'] ?? null;
        
        if ($homeAppliances) {
            $subCategoriesList = [
                ['name' => 'إصلاح الغسالات الأوتوماتيكية', 'slug' => 'automatic-washer-repair', 'order' => 1],
                ['name' => 'إصلاح الثلاجات', 'slug' => 'fridge-repair', 'order' => 2],
                ['name' => 'إصلاح غسالات الصحون', 'slug' => 'dishwasher-repair', 'order' => 3],
                ['name' => 'إصلاح المكيفات', 'slug' => 'ac-repair', 'order' => 4],
                ['name' => 'إصلاح المجففات', 'slug' => 'dryer-repair', 'order' => 5],
            ];

            foreach ($subCategoriesList as $sub) {
                Category::create([
                    'name' => $sub['name'],
                    'arabic_name' => $sub['name'],
                    'slug' => $sub['slug'],
                    'parent_id' => $homeAppliances->id,
                    'is_active' => true,
                    'order' => $sub['order'],
                ]);
            }
        }

        $this->command->info('Categories created: ' . Category::count());

        // ========== 4. CREATE SAMPLE ADS ==========
        $this->command->info('Creating sample ads...');
        
        // Get category IDs safely
        $categories = [
            'automatic_washer' => Category::where('slug', 'automatic-washer-repair')->first(),
            'ac_repair' => Category::where('slug', 'ac-repair')->first(),
            'movers' => Category::where('slug', 'movers-and-packers')->first(),
            'curtain' => Category::where('slug', 'curtain-installation')->first(),
            'car_buying' => Category::where('slug', 'car-buying')->first(),
            'plumbing' => Category::where('slug', 'plumbing-and-electrician')->first(),
            'automobile_tint' => Category::where('slug', 'automobile-tint-repair')->first(),
            'home_renovation' => Category::where('slug', 'home-renovation')->first(),
        ];
        
        $sampleAds = [];

        // Only create ads if category exists
        if ($categories['automatic_washer']) {
            $sampleAds[] = [
                'title' => 'احتراف صيانة الغسالات - فني متخصص',
                'description' => 'خدمة صيانة جميع أنواع الغسالات الأوتوماتيكية. فنيين متخصصين. ضمان 6 أشهر.',
                'price' => 150,
                'location' => 'الرياض',
                'phone' => '0500000001',
                'whatsapp' => '0500000001',
                'category_id' => $categories['automatic_washer']->id,
                'is_pinned' => true,
            ];
        }

        if ($categories['ac_repair']) {
            $sampleAds[] = [
                'title' => 'تبريد وتكييف - صيانة وتركيب مكيفات',
                'description' => 'صيانة وتركيب جميع أنواع المكيفات. فنيين خبرة. ضمان سنة.',
                'price' => 200,
                'location' => 'جدة',
                'phone' => '0500000002',
                'whatsapp' => '0500000002',
                'category_id' => $categories['ac_repair']->id,
                'is_pinned' => true,
            ];
        }

        if ($categories['movers']) {
            $sampleAds[] = [
                'title' => 'نقل عفش بالرياض - فك وتركيب',
                'description' => 'نقل عفش فك وتركيب بضمان. عمالة مدربة وسيارات مجهزة.',
                'price' => 500,
                'location' => 'الرياض',
                'phone' => '0500000003',
                'whatsapp' => '0500000003',
                'category_id' => $categories['movers']->id,
                'is_pinned' => false,
            ];
        }

        if ($categories['curtain']) {
            $sampleAds[] = [
                'title' => 'تركيب ستائر - جميع الأنواع',
                'description' => 'تركيب ستائر رول - ستائر خشبية - ستائر قماش. خصم 20% للطلب الأول.',
                'price' => 300,
                'location' => 'الدمام',
                'phone' => '0500000004',
                'whatsapp' => '0500000004',
                'category_id' => $categories['curtain']->id,
                'is_pinned' => false,
            ];
        }

        if ($categories['car_buying']) {
            $sampleAds[] = [
                'title' => 'شراء سيارات مستعملة - نقداً فوراً',
                'description' => 'نشتري جميع أنواع السيارات المستعملة. نقداً فوراً. معاينة مجانية.',
                'price' => null,
                'location' => 'الرياض',
                'phone' => '0500000005',
                'whatsapp' => '0500000005',
                'category_id' => $categories['car_buying']->id,
                'is_pinned' => false,
            ];
        }

        if ($categories['plumbing']) {
            $sampleAds[] = [
                'title' => 'سباكة وكهرباء - فني منزلي',
                'description' => 'جميع أعمال السباكة والكهرباء. تركيب وصيانة. فنيين معتمدين.',
                'price' => 250,
                'location' => 'جدة',
                'phone' => '0500000006',
                'whatsapp' => '0500000006',
                'category_id' => $categories['plumbing']->id,
                'is_pinned' => false,
            ];
        }

        if ($categories['automobile_tint']) {
            $sampleAds[] = [
                'title' => 'تلميع وتظليل السيارات - خدمة متنقلة',
                'description' => 'تلميع فاخر وتظليل احترافي. مواد أصلية وضمان.',
                'price' => 400,
                'location' => 'الرياض',
                'phone' => '0500000007',
                'whatsapp' => '0500000007',
                'category_id' => $categories['automobile_tint']->id,
                'is_pinned' => false,
            ];
        }

        if ($categories['home_renovation']) {
            $sampleAds[] = [
                'title' => 'تجديد المنازل والدهانات',
                'description' => 'تجديد شامل للمنازل. دهانات داخلية وخارجية. ديكورات حديثة.',
                'price' => 1000,
                'location' => 'الدمام',
                'phone' => '0500000008',
                'whatsapp' => '0500000008',
                'category_id' => $categories['home_renovation']->id,
                'is_pinned' => false,
            ];
        }

        // Create ads
        foreach ($sampleAds as $adData) {
            $isPinned = $adData['is_pinned'];
            unset($adData['is_pinned']);
            
            Ad::create([
                'title' => $adData['title'],
                'slug' => Str::slug($adData['title']) . '-' . uniqid(),
                'description' => $adData['description'],
                'price' => $adData['price'],
                'location' => $adData['location'],
                'phone' => $adData['phone'],
                'whatsapp' => $adData['whatsapp'],
                'category_id' => $adData['category_id'],
                'user_id' => $admin->id,
                'is_active' => true,
                'status' => 'approved',
                'views' => rand(50, 500),
                'is_pinned' => $isPinned,
                'pinned_at' => $isPinned ? now() : null,
            ]);
            
            $this->command->info("Added ad: {$adData['title']}");
        }

        // Create storage link if not exists
        if (!file_exists(public_path('storage'))) {
            $this->command->info('Creating storage link...');
            \Artisan::call('storage:link');
        }

        $this->command->info('✅ Production data seeded successfully!');
        $this->command->info('📊 Categories: ' . Category::count());
        $this->command->info('📝 Ads: ' . Ad::count());
        $this->command->info('👤 Admin: admin@souq.com / admin123');
    }
}