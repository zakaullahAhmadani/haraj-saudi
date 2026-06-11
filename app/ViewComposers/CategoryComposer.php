<?php

namespace App\ViewComposers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    public function compose(View $view)
    {
        try {
            $headerCategories = Category::where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('order')
                ->get();
                
            $allCategories = Category::where('is_active', true)
                ->with('parent')
                ->orderBy('order')
                ->get();
                
            $view->with('headerCategories', $headerCategories);
            $view->with('allCategories', $allCategories);
        } catch (\Exception $e) {
            $view->with('headerCategories', collect([]));
            $view->with('allCategories', collect([]));
        }
    }
}