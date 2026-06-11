<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $mainCategories = [
            ['name' => 'أجهزة منزلية', 'order' => 1, 'children' => [
                'إصلاح غسالة أوتوماتيك',
                'إصلاح ثلاجة',
                'إصلاح غسالة صحون',
                'إصلاح مكيف هواء',
                'إصلاح مجفف ملابس',
            ]],
            ['name' => 'شراء سيارات', 'order' => 2, 'children' => []],
            ['name' => 'تأجير سيارات وحافلات', 'order' => 3, 'children' => []],
            ['name' => 'خردة وسكراب', 'order' => 4, 'children' => []],
            ['name' => 'خدمة سحب السيارات', 'order' => 5, 'children' => []],
            ['name' => 'عطور وبخور', 'order' => 6, 'children' => []],
            ['name' => 'جبس وديكور', 'order' => 7, 'children' => []],
            ['name' => 'تركيب ستائر', 'order' => 8, 'children' => []],
            ['name' => 'تركيب زجاج', 'order' => 9, 'children' => []],
            ['name' => 'سباكة وكهرباء', 'order' => 10, 'children' => []],
            ['name' => 'تظليل سيارات', 'order' => 11, 'children' => []],
            ['name' => 'تجديد وترميم منازل', 'order' => 12, 'children' => []],
            ['name' => 'محلات لابتوب وجوال', 'order' => 13, 'children' => []],
            ['name' => 'شراء أثاث', 'order' => 14, 'children' => []],
            ['name' => 'نقل عفش', 'order' => 15, 'children' => []],
            ['name' => 'إصلاح إطارات', 'order' => 16, 'children' => []],
        ];

        foreach ($mainCategories as $categoryData) {
            $children = $categoryData['children'];
            unset($categoryData['children']);

            $categoryData['slug'] = Str::slug($categoryData['name']) . '-' . uniqid();

            $category = Category::create($categoryData);

            foreach ($children as $index => $childName) {
                Category::create([
                    'name'      => $childName,
                    'slug'      => Str::slug($childName) . '-' . uniqid(),
                    'parent_id' => $category->id,
                    'order'     => $categoryData['order'] * 10 + $index + 1,
                ]);
            }
        }
    }
}
