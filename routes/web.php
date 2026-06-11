<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes (No authentication required)
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'search'])->name('search');
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('categories.show');
Route::get('/ads', [AdController::class, 'index'])->name('ads.index');
Route::get('/ads/{slug}', [AdController::class, 'show'])->name('ads.show');

// Guest Routes (Only for non-logged in users)
Route::middleware('guest')->group(function () {
    // Show login page
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    // Show register page
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    // Process login
    Route::post('/login', [LoginController::class, 'login']);
    
    // Process register
    Route::post('/register', [RegisterController::class, 'register']);
});

// Authenticated Routes (Require login)
Route::middleware(['auth'])->group(function () {
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Profile routes
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    
    // Ad management routes
    Route::get('/post-ad', [AdController::class, 'create'])->name('ads.create');
    Route::post('/ads', [AdController::class, 'store'])->name('ads.store');
    Route::get('/ads/{ad}/edit', [AdController::class, 'edit'])->name('ads.edit');
    Route::put('/ads/{ad}', [AdController::class, 'update'])->name('ads.update');
    Route::delete('/ads/{ad}', [AdController::class, 'destroy'])->name('ads.destroy');
});





// admin routes will be defined in a separate file and loaded conditionally based on user role
use App\Http\Controllers\Admin\AdminController;

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/',                          [AdminController::class, 'dashboard'])->name('dashboard');

    // Posts
    Route::get('/posts',                     [AdminController::class, 'posts'])->name('posts');
    Route::post('/posts/bulk-delete',        [AdminController::class, 'bulkDeletePosts'])->name('posts.bulk-delete');
    Route::get('/posts/{id}/delete',         [AdminController::class, 'deletePost'])->name('posts.delete');
    Route::get('/posts/{id}/toggle',         [AdminController::class, 'togglePostStatus'])->name('posts.toggle');

    // Admin create post
    Route::get('/posts/create',              [AdminController::class, 'createPost'])->name('posts.create');
    Route::post('/posts/store',              [AdminController::class, 'storePost'])->name('posts.store');

    // Featured / Pinned top posts
    Route::get('/featured',                  [AdminController::class, 'featured'])->name('featured');
    Route::post('/featured/pin',             [AdminController::class, 'pinPost'])->name('featured.pin');
    Route::get('/featured/{id}/unpin',       [AdminController::class, 'unpinPost'])->name('featured.unpin');

    // Users
    Route::get('/users',                     [AdminController::class, 'users'])->name('users');
    Route::get('/users/{id}/delete',         [AdminController::class, 'deleteUser'])->name('users.delete');
    Route::get('/users/{id}/toggle',         [AdminController::class, 'toggleUserStatus'])->name('users.toggle');
});



use App\Http\Controllers\SEOController;

Route::get('sitemap.xml', [SEOController::class, 'sitemap']);





use App\Http\Controllers\SitemapController;

// Sitemap route
Route::get('/sitemap.xml', [SitemapController::class, 'index']);

// IndexNow endpoint
Route::get('/indexnow', function () {
    return response()->json([
        'key' => env('INDEXNOW_KEY', 'your-api-key-here')
    ]);
})->name('indexnow.key');

Route::get('/admin/seo-settings', function () {
    return view('admin.seo-instructions');
})->middleware('auth')->name('seo.settings');