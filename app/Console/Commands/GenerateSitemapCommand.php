<?php

namespace App\Console\Commands;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemapCommand extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate sitemap.xml file for SEO';

    public function handle()
    {
        $this->info('Generating sitemap...');
        
        // Get categories
        $categories = Category::where('is_active', true)->get();
        $this->info('Categories found: ' . $categories->count());
        
        // Get ads
        $ads = Ad::where('is_active', true)
            ->where('status', 'approved')
            ->latest()
            ->take(1000)
            ->get();
        $this->info('Ads found: ' . $ads->count());
        
        // Start XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        // Home page
        $xml .= "    <url>\n";
        $xml .= "        <loc>" . url('/') . "</loc>\n";
        $xml .= "        <lastmod>" . now()->toDateString() . "</lastmod>\n";
        $xml .= "        <changefreq>daily</changefreq>\n";
        $xml .= "        <priority>1.0</priority>\n";
        $xml .= "    </url>\n";
        
        // Categories
        foreach ($categories as $category) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . route('categories.show', $category->slug) . "</loc>\n";
            $xml .= "        <lastmod>" . now()->toDateString() . "</lastmod>\n";
            $xml .= "        <changefreq>weekly</changefreq>\n";
            $xml .= "        <priority>0.8</priority>\n";
            $xml .= "    </url>\n";
        }
        
        // Ads
        foreach ($ads as $ad) {
            $xml .= "    <url>\n";
            $xml .= "        <loc>" . route('ads.show', $ad->slug) . "</loc>\n";
            $xml .= "        <lastmod>" . ($ad->updated_at?->toDateString() ?? $ad->created_at->toDateString()) . "</lastmod>\n";
            $xml .= "        <changefreq>daily</changefreq>\n";
            $xml .= "        <priority>0.9</priority>\n";
            $xml .= "    </url>\n";
        }
        
        $xml .= '</urlset>';
        
        // Save to public directory
        File::put(public_path('sitemap.xml'), $xml);
        
        $this->info('✅ Sitemap generated successfully!');
        $this->info('📍 Location: ' . public_path('sitemap.xml'));
        $this->info('📊 Total URLs: ' . (1 + $categories->count() + $ads->count()));
        
        return Command::SUCCESS;
    }
}