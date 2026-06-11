<?php

namespace App\Services;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class SeoService
{
    /**
     * Generate sitemap.xml content
     */
    public static function generateSitemap()
    {
        $urls = [];
        
        // Static pages
        $urls[] = self::urlEntry(URL::to('/'), 'daily', 1.0);
        $urls[] = self::urlEntry(URL::to('/about'), 'weekly', 0.8);
        $urls[] = self::urlEntry(URL::to('/contact'), 'weekly', 0.8);
        $urls[] = self::urlEntry(URL::to('/privacy'), 'monthly', 0.5);
        $urls[] = self::urlEntry(URL::to('/terms'), 'monthly', 0.5);
        
        // Dynamic categories
        $categories = \App\Models\Category::where('is_active', true)->get();
        foreach ($categories as $category) {
            $urls[] = self::urlEntry(route('categories.show', $category->slug), 'weekly', 0.9);
        }
        
        // Dynamic ads
        $ads = \App\Models\Ad::where('is_active', true)
            ->where('status', 'approved')
            ->latest()
            ->take(1000)
            ->get();
            
        foreach ($ads as $ad) {
            $urls[] = self::urlEntry(route('ads.show', $ad->slug), 'daily', 0.7);
        }
        
        // Generate XML
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        
        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>{$url['loc']}</loc>\n";
            $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }
        
        $xml .= '</urlset>';
        
        return $xml;
    }
    
    private static function urlEntry($loc, $changefreq, $priority)
    {
        return [
            'loc' => $loc,
            'lastmod' => now()->format('Y-m-d'),
            'changefreq' => $changefreq,
            'priority' => $priority,
        ];
    }
    
    /**
     * Get Open Graph tags
     */
    public static function getOpenGraphTags($page = null, $data = [])
    {
        $defaultTags = [
            'og:title' => 'حراج السعودية - سوق السيارات والعقارات والإعلانات المبوبة',
            'og:description' => 'أكبر سوق إعلانات مبوبة في السعودية. بيع وشراء السيارات، العقارات، الأجهزة، والخدمات. إعلانات مجانية وسريعة.',
            'og:type' => 'website',
            'og:url' => URL::current(),
            'og:image' => URL::to('/images/og-image.jpg'),
            'og:site_name' => 'حراج السعودية',
            'og:locale' => 'ar_AR',
            'og:locale:alternate' => 'en_US',
        ];
        
        if ($page === 'ad' && isset($data['ad'])) {
            $ad = $data['ad'];
            $defaultTags['og:title'] = $ad->title . ' - حراج السعودية';
            $defaultTags['og:description'] = Str::limit($ad->description, 200);
            $defaultTags['og:type'] = 'product';
            if ($ad->images->first()) {
                $defaultTags['og:image'] = asset('storage/' . $ad->images->first()->image_path);
            }
        }
        
        if ($page === 'category' && isset($data['category'])) {
            $category = $data['category'];
            $defaultTags['og:title'] = $category->name . ' - حراج السعودية';
            $defaultTags['og:description'] = 'تصفح ' . $category->name . ' في حراج السعودية. أفضل العروض والأسعار.';
        }
        
        return $defaultTags;
    }
}