<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        // Get pinned posts (with caching)
        $pinnedAds = Cache::remember('home_pinned_ads', 3600, function () {
            return Ad::pinned()
                ->with(['user', 'category', 'images'])
                ->orderBy('pinned_at', 'desc')
                ->get();
        });

        $pinnedIds = $pinnedAds->pluck('id')->toArray();

        // Get trending posts
        $trendingAds = Cache::remember('home_trending_ads', 3600, function () use ($pinnedIds) {
            return Ad::active()
                ->with(['user', 'category', 'images'])
                ->whereNotIn('id', $pinnedIds)
                ->orderBy('views', 'desc')
                ->limit(8)
                ->get();
        });

        // Get regular posts (no cache for pagination)
        $regularAds = Ad::active()
            ->with(['user', 'category', 'images'])
            ->whereNotIn('id', $pinnedIds)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        // Get categories
        $categories = Cache::remember('home_categories', 86400, function () {
            return Category::where('is_active', true)
                ->whereNull('parent_id')
                ->withCount('ads')
                ->orderBy('order')
                ->get();
        });

        $allCategories = Cache::remember('home_all_categories', 86400, function () {
            return Category::where('is_active', true)
                ->with('parent')
                ->orderBy('order')
                ->get();
        });

        $searchCities = Ad::saudiCities();

        return view('home', compact(
            'pinnedAds', 'trendingAds', 'regularAds',
            'categories', 'allCategories', 'searchCities'
        ));
    }
}