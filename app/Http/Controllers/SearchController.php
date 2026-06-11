<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = Ad::active()->with(['user', 'category', 'images']);

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                  ->orWhere('description', 'LIKE', "%{$keyword}%")
                  ->orWhere('keywords', 'LIKE', "%{$keyword}%");
            });
        }

        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        $ads = $query->orderByDesc('views')
            ->orderByDesc('created_at')
            ->paginate(30);

        $categories = Category::where('is_active', true)
            ->with('parent')
            ->orderBy('order')
            ->get();
        $locations = Ad::saudiCities();

        return view('search.results', compact('ads', 'categories', 'locations'));
    }
}