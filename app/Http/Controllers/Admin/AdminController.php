<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Cache;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Models\AdImage;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /* ─────────────────────────────────────────
     |  DASHBOARD
     ───────────────────────────────────────── */
    public function dashboard()
    {
        $totalPosts      = Ad::count();
        $totalUsers      = User::count();
        $totalCategories = Category::count();
        $activePosts     = Ad::where('is_active', true)->count();
        $pendingPosts    = Ad::where('is_active', false)->count();

        $currentMonth = now()->month;
        $currentYear  = now()->year;

        $postsThisMonth = Ad::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();
        $postsThisWeek  = Ad::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $postsThisYear  = Ad::whereYear('created_at', $currentYear)->count();

        $usersThisMonth = User::whereMonth('created_at', $currentMonth)->whereYear('created_at', $currentYear)->count();
        $usersThisWeek  = User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();
        $usersThisYear  = User::whereYear('created_at', $currentYear)->count();

        // Last 12 months chart data
        $monthlyData = [];
        for ($i = 11; $i >= 0; $i--) {
            $date          = now()->subMonths($i);
            $monthlyData[] = [
                'month' => $date->format('M Y'),
                'posts' => Ad::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
                'users' => User::whereYear('created_at', $date->year)->whereMonth('created_at', $date->month)->count(),
            ];
        }

        // Weekly data current month
        $weeklyData = [];
        for ($i = 0; $i < 4; $i++) {
            $weekStart    = now()->startOfMonth()->addWeeks($i);
            $weekEnd      = (clone $weekStart)->endOfWeek();
            $weeklyData[] = [
                'week'  => 'Week ' . ($i + 1),
                'posts' => Ad::whereBetween('created_at', [$weekStart, $weekEnd])->count(),
            ];
        }

        $categoryDistribution = Category::withCount('ads')
            ->having('ads_count', '>', 0)
            ->orderBy('ads_count', 'desc')
            ->limit(5)
            ->get();

        $recentPosts = Ad::with(['user', 'category'])->latest()->limit(10)->get();
        $recentUsers = User::latest()->limit(10)->get();
        $topUsers    = User::withCount('ads')->having('ads_count', '>', 0)->orderBy('ads_count', 'desc')->limit(5)->get();

        // Currently pinned posts
        $pinnedPosts = Ad::pinnedByAdmin()->with(['user', 'category'])->get();

        return view('admin.dashboard', compact(
            'totalPosts', 'totalUsers', 'totalCategories', 'activePosts', 'pendingPosts',
            'postsThisMonth', 'postsThisWeek', 'postsThisYear',
            'usersThisMonth', 'usersThisWeek', 'usersThisYear',
            'monthlyData', 'weeklyData', 'categoryDistribution',
            'recentPosts', 'recentUsers', 'topUsers', 'pinnedPosts'
        ));
    }

    /* ─────────────────────────────────────────
     |  POSTS LIST
     ───────────────────────────────────────── */
    public function posts()
    {
        $posts = Ad::with(['user', 'category', 'images'])
            ->latest()
            ->paginate(20);

        return view('admin.posts', compact('posts'));
    }

    /* ─────────────────────────────────────────
     |  ADMIN CREATE POST
     ───────────────────────────────────────── */
    public function createPost()
    {
        $categories = Category::where('is_active', true)->orderBy('order')->get();
        $locations  = Ad::saudiCities();

        return view('admin.posts-create', compact('categories', 'locations'));
    }

    public function storePost(Request $request)
    {
        // NO IMAGE VALIDATION - REMOVED 'images.*' validation completely
        $request->validate([
            'title'       => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string|min:10',
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
            'is_pinned'   => $request->has('is_pinned') ? true : false,
            'pinned_at'   => $request->has('is_pinned') ? now() : null,
        ]);

        // Handle images - NO VALIDATION, accept any file type
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

        return redirect()->route('admin.posts')->with('success', 'Post created successfully!');
    }

    /* ─────────────────────────────────────────
     |  DELETE / TOGGLE POST
     ───────────────────────────────────────── */
    public function deletePost($id)
    {
        $post = Ad::findOrFail($id);
        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }
        $post->delete();

        return redirect()->route('admin.posts')->with('success', 'Post deleted successfully!');
    }

    public function bulkDeletePosts(Request $request)
    {
        $ids = $request->ids;
        if ($ids && is_array($ids)) {
            $posts = Ad::whereIn('id', $ids)->get();
            foreach ($posts as $post) {
                foreach ($post->images as $image) {
                    Storage::disk('public')->delete($image->image_path);
                }
                $post->delete();
            }
            return response()->json(['success' => true, 'message' => count($ids) . ' posts deleted successfully']);
        }
        return response()->json(['success' => false, 'message' => 'No posts selected']);
    }

    public function togglePostStatus($id)
    {
        $post            = Ad::findOrFail($id);
        $post->is_active = !$post->is_active;
        $post->save();

        return redirect()->back()->with('success', 'Post status updated!');
    }

    /* ─────────────────────────────────────────
     |  FEATURED / PINNED TOP POSTS
     ───────────────────────────────────────── */
    public function featured()
    {
        // Get currently active pinned posts (positions 1-5, not expired)
        $pinnedPosts = Ad::with(['user', 'category', 'images'])
            ->whereNotNull('featured_position')
            ->where('featured_until', '>', now())
            ->orderBy('featured_position')
            ->get();

        // Get ALL active posts for the search/select dropdown
        $allPosts = Ad::with(['user', 'category', 'images'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // Slots 1-5 mapped
        $slots = [];
        for ($i = 1; $i <= 5; $i++) {
            $slots[$i] = $pinnedPosts->firstWhere('featured_position', $i);
        }

        return view('admin.featured', compact('pinnedPosts', 'allPosts', 'slots'));
    }

    public function pinPost(Request $request)
    {
        $request->validate([
            'ad_id'    => 'required|exists:ads,id',
            'position' => 'required|integer|min:1|max:5',
            'hours'    => 'required|integer|min:1|max:168',
        ]);

        // Clear any existing post in that slot
        Ad::where('featured_position', $request->position)
          ->update(['featured_position' => null, 'featured_until' => null]);

        // Also clear this ad from any other slot it might be in
        Ad::where('id', $request->ad_id)
          ->update(['featured_position' => null, 'featured_until' => null]);

        $ad                    = Ad::findOrFail($request->ad_id);
        $ad->featured_position = (int) $request->position;
        $ad->featured_until    = now()->addHours((int) $request->hours);
        $ad->is_active         = true;
        $ad->save();

        return redirect()->route('admin.featured')
            ->with('success', "Post pinned to position #{$request->position} for {$request->hours} hours!");
    }

    public function unpinPost($id)
    {
        $ad                    = Ad::findOrFail($id);
        $ad->featured_position = null;
        $ad->featured_until    = null;
        $ad->save();

        return redirect()->route('admin.featured')->with('success', 'Post unpinned successfully!');
    }

    /* ─────────────────────────────────────────
     |  USERS
     ───────────────────────────────────────── */
    public function users()
    {
        $users = User::withCount('ads')->latest()->paginate(20);
        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot delete your own account!');
        }
        foreach ($user->ads as $post) {
            foreach ($post->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        $user->delete();
        return redirect()->route('admin.users')->with('success', 'User deleted successfully!');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'You cannot block your own account!');
        }
        $user->is_active = !$user->is_active;
        $user->save();
        return redirect()->back()->with('success', 'User status updated!');
    }
}