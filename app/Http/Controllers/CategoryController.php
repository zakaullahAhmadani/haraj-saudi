<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $ads = Ad::active()
            ->where('category_id', $category->id)
            ->with(['user', 'category', 'images'])
            ->orderByDesc('views')
            ->orderByDesc('created_at')
            ->paginate(18);

        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $locations = Ad::saudiCities();

        return view('search.results', compact('ads', 'categories', 'locations', 'category'));
    }
}
