<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompleteCategorySeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing categories to avoid duplicates
        Category::truncate();

        // Main Categories with English and Arabic names
        $mainCategories = [
            'Home Appliances' => 'الأجهزة المنزلية',
            'Car buying' => 'شراء السيارات',
            'Rent Car/ Buses' => 'تأجير سيارات / حافلات',
            'Scrap' => 'خردة',
            'Towing Service' => 'خدمة السحب',
            'Perfume' => 'عطور',
            'Gypsum Boarding' => 'ألواح جبسية',
            'Curtain installation' => 'تركيب ستائر',
            'Glass Installation' => 'تركيب زجاج',
            'Plumbing and Electrician' => 'سباكة وكهرباء',
            'Automobile Tint Repair' => 'تلميع وتظليل السيارات',
            'Home Renovation' => 'تجديد المنازل',
            'Laptop or Mobile Shops' => 'محلات لابتوب وجوال',
            'Furniture Buyers' => 'مشترو الأثاث',
            'Movers and Packers' => 'نقل وعفش',
            'Tire Puncture Repair' => 'إصلاح ثقوب الإطارات'
        ];

        $createdCategories = [];
        $order = 1;

        foreach ($mainCategories as $name => $arabicName) {
            $slug = Str::slug($name);
            $category = Category::create([
                'name' => $name,
                'arabic_name' => $arabicName,
                'slug' => $slug,
                'is_active' => true,
                'order' => $order,
                'description' => $arabicName . ' - خدمات في المملكة العربية السعودية'
            ]);
            $createdCategories[$name] = $category;
            $order++;
        }

        // Add subcategories for Home Appliances
        $homeAppliances = $createdCategories['Home Appliances'] ?? null;
        
        if ($homeAppliances) {
            $subCategories = [
                'automatic washer repair' => 'إصلاح الغسالات الأوتوماتيكية',
                'fridge repair' => 'إصلاح الثلاجات',
                'dishwasher repair' => 'إصلاح غسالات الصحون',
                'ac repair' => 'إصلاح المكيفات',
                'dryer repair' => 'إصلاح المجففات'
            ];
            
            $subOrder = 1;
            foreach ($subCategories as $name => $arabicName) {
                Category::create([
                    'name' => $name,
                    'arabic_name' => $arabicName,
                    'slug' => Str::slug($name),
                    'parent_id' => $homeAppliances->id,
                    'is_active' => true,
                    'order' => $subOrder,
                    'description' => $arabicName . ' - خدمة متخصصة'
                ]);
                $subOrder++;
            }
        }

        $this->command->info('تم إضافة ' . count($mainCategories) . ' تصنيف رئيسي و 5 تصنيفات فرعية بنجاح!');
        $this->command->info('Categories added successfully!');
    }
}