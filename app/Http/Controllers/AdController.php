<?php

namespace App\Http\Controllers;

use App\Models\Ad;
use App\Models\Category;
use App\Models\AdImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index()
    {
        // Get pinned posts (always on top, don't rotate)
        $pinnedAds = Ad::pinnedByAdmin()
            ->with(['user', 'category', 'images'])
            ->orderBy('featured_position')
            ->get();
        
        // Get regular posts
        $regularAds = Ad::active()
            ->where(function($query) {
                $query->whereNull('featured_position')
                      ->orWhere('featured_until', '<', now());
            })
            ->with(['user', 'category', 'images'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Get trending ads for sidebar
        $trendingAds = Ad::active()
            ->with(['user', 'category', 'images'])
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();
        
        $categories = Category::where('is_active', true)
            ->withCount('ads')
            ->orderBy('order')
            ->limit(10)
            ->get();
        
        $allCategories = Category::where('is_active', true)
            ->with('parent')
            ->orderBy('order')
            ->get();
        
        $searchCities = Ad::saudiCities();
        
        return view('ads.index', compact(
            'pinnedAds', 'regularAds', 'trendingAds', 
            'categories', 'allCategories', 'searchCities'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $locations = Ad::saudiCities();

        return view('ads.create', compact('categories', 'locations'));
    }

    public function store(Request $request)
    {
        // NO IMAGE VALIDATION - ACCEPT ANY FILE
        $request->validate([
            'title'       => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:20',
            'location'    => 'required|string|max:100',
            'phone'       => 'required|string|max:20',
            'whatsapp'    => 'nullable|string|max:20',
            'price'       => 'nullable|numeric|min:0',
            'keywords'    => 'nullable|string|max:500',
            // 'images' validation REMOVED - accept any file
        ]);

        $ad = Ad::create([
            'user_id'     => Auth::id(),
            'title'       => $request->title,
            'slug'        => Str::slug($request->title) . '-' . uniqid(),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'location'    => $request->location,
            'phone'       => $request->phone,
            'whatsapp'    => $request->whatsapp,
            'price'       => $request->price,
            'keywords'    => $request->keywords,
            'is_active'   => true,
            'status'      => 'approved',
            'views'       => 0,
        ]);

        // Upload images - NO VALIDATION, ANY FILE ACCEPTED
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                if ($image && $image->isValid()) {
                    $extension = $image->getClientOriginalExtension();
                    if (empty($extension)) {
                        $extension = 'jpg';
                    }
                    $filename = time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                    $path = $image->storeAs('ads/' . $ad->id, $filename, 'public');
                    
                    AdImage::create([
                        'ad_id' => $ad->id,
                        'image_path' => $path,
                        'order' => $index
                    ]);
                }
            }
        }

        return redirect()->route('home')
            ->with('success', 'تم نشر إعلانك بنجاح!');
    }

    public function show($slug)
    {
        $ad = Ad::where('slug', $slug)
            ->with(['user', 'category', 'images'])
            ->firstOrFail();
        
        $ad->increment('views');
        
        $relatedAds = Ad::where('category_id', $ad->category_id)
            ->where('id', '!=', $ad->id)
            ->active()
            ->with(['user', 'images'])
            ->latest()
            ->limit(12)
            ->get();
        
        return view('ads.show', compact('ad', 'relatedAds'));
    }

    public function edit(Ad $ad)
    {
        // Check if user owns the ad
        if (Auth::id() !== $ad->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $locations = Ad::saudiCities();
        
        return view('ads.edit', compact('ad', 'categories', 'locations'));
    }

    public function update(Request $request, Ad $ad)
    {
        // Check if user owns the ad
        if (Auth::id() !== $ad->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // NO IMAGE VALIDATION
        $request->validate([
            'title'          => 'required|string|max:200',
            'category_id'    => 'required|exists:categories,id',
            'description'    => 'required|string|min:20',
            'location'       => 'required|string|max:100',
            'phone'          => 'required|string|max:20',
            'whatsapp'       => 'nullable|string|max:20',
            'price'          => 'nullable|numeric|min:0',
            'keywords'       => 'nullable|string|max:500',
            'removed_images' => 'nullable|string'
            // 'images' validation REMOVED
        ]);

        // Update ad details
        $ad->update([
            'title'       => $request->title,
            'category_id' => $request->category_id,
            'description' => $request->description,
            'location'    => $request->location,
            'phone'       => $request->phone,
            'whatsapp'    => $request->whatsapp,
            'price'       => $request->price,
            'keywords'    => $request->keywords,
        ]);

        // Remove deleted images
        if ($request->filled('removed_images')) {
            $removedImageIds = explode(',', $request->removed_images);
            foreach ($removedImageIds as $imageId) {
                $image = AdImage::find($imageId);
                if ($image && $image->ad_id == $ad->id) {
                    if (Storage::disk('public')->exists($image->image_path)) {
                        Storage::disk('public')->delete($image->image_path);
                    }
                    $image->delete();
                }
            }
        }

        // Add new images - ANY FILE ACCEPTED
        if ($request->hasFile('images')) {
            $currentImageCount = $ad->images()->count();
            $newImagesCount = count($request->file('images'));
            
            if (($currentImageCount + $newImagesCount) > 4) {
                return back()->withErrors(['images' => 'لا يمكن أن يتجاوز عدد الصور 4 صور'])
                    ->withInput();
            }
            
            foreach ($request->file('images') as $index => $image) {
                if ($image && $image->isValid()) {
                    $extension = $image->getClientOriginalExtension() ?: 'jpg';
                    $filename = time() . '_' . $index . '_' . rand(1000, 9999) . '.' . $extension;
                    $path = $image->storeAs('ads/' . $ad->id, $filename, 'public');
                    
                    AdImage::create([
                        'ad_id' => $ad->id,
                        'image_path' => $path,
                        'order' => $ad->images()->count()
                    ]);
                }
            }
        }

        return redirect()->route('ads.show', $ad->slug)
            ->with('success', 'تم تحديث الإعلان بنجاح!');
    }

    public function destroy(Ad $ad)
    {
        // Check if user owns the ad
        if (Auth::id() !== $ad->user_id) {
            abort(403, 'Unauthorized action.');
        }
        
        // Delete all images from storage
        foreach ($ad->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        
        // Delete the ad
        $ad->delete();
        
        return redirect()->route('home')
            ->with('success', 'تم حذف الإعلان بنجاح!');
    }
}