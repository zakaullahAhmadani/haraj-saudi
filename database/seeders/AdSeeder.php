<?php

namespace Database\Seeders;

use App\Models\Ad;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdSeeder extends Seeder
{
    public function run(): void
    {
        // Create a default user if none exists
        $user = User::first();
        if (!$user) {
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'is_admin' => false,
                'is_active' => true,
            ]);
        }

        // Get all categories
        $categories = Category::all();
        $cities = Ad::saudiCities();

        $this->command->info('Categories found: ' . $categories->count());
        
        if ($categories->count() == 0) {
            $this->command->error('No categories found! Please run CategorySeeder first.');
            return;
        }

        $adCounter = 0;
        
        foreach ($categories as $category) {
            // Create 2 ads per category
            for ($i = 0; $i < 2; $i++) {
                $city = $cities[array_rand($cities)];
                $title = $category->name . ' - خدمة احترافية';
                
                // Pin first 5 ads
                $isPinned = ($adCounter < 5);
                
                Ad::create([
                    'user_id'     => $user->id,
                    'category_id' => $category->id,
                    'title'       => $title . ' في ' . $city,
                    'slug'        => Str::slug($title) . '-' . uniqid(),
                    'description' => 'نقدم أفضل خدمة في ' . $city . ' بأسعار مناسبة. فريق محترف وذو خبرة عالية. ضمان الجودة وأسعار منافسة.',
                    'location'    => $city,
                    'phone'       => '+9665' . rand(10000000, 99999999),
                    'whatsapp'    => '+9665' . rand(10000000, 99999999),
                    'price'       => rand(100, 5000),
                    'is_active'   => true,
                    'status'      => 'approved',
                    'views'       => rand(10, 500),
                    'is_pinned'   => $isPinned,
                    'pinned_at'   => $isPinned ? now() : null,
                ]);
                
                $adCounter++;
            }
        }
        
        $this->command->info('✅ AdSeeder completed!');
        $this->command->info('📊 Total ads: ' . Ad::count());
        $this->command->info('📌 Pinned ads: ' . Ad::where('is_pinned', true)->count());
    }
}