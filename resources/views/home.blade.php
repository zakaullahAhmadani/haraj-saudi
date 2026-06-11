@extends('layouts.app')

@section('title', 'سوق - الرئيسية')
@section('description', 'ابحث عن خدمات وإعلانات في المملكة العربية السعودية بسهولة وسرعة.')

@section('content')
<div class="space-y-8">
<section class="grid grid-cols-1 lg:grid-cols-[280px_minmax(0,1fr)] gap-6">

    {{-- Left Sidebar --}}
    <aside class="hidden lg:block space-y-4 lg:sticky lg:top-24 self-start">
        <div class="bg-white rounded-2xl shadow-lg p-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-star text-yellow-500"></i> الأعلى تقييماً
            </h2>
            <div class="mt-3 space-y-2">
                @foreach($trendingAds as $ad)
                    <a href="{{ route('ads.show', $ad->slug) }}"
                        class="flex items-center gap-3 rounded-xl border border-gray-100 p-3 hover:border-purple-200 hover:shadow-md transition-all duration-200 group">
                        <div class="w-10 h-10 bg-gradient-to-br from-purple-100 to-indigo-100 rounded-xl flex items-center justify-center text-purple-600 flex-shrink-0 group-hover:scale-110 transition-transform">
                            <i class="fas fa-fire text-sm"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate group-hover:text-purple-600 transition">{{ Str::limit($ad->title, 38) }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <i class="fas fa-map-marker-alt text-xs"></i> {{ $ad->location }}
                                <span class="mx-1">•</span>
                                <i class="fas fa-eye text-xs"></i> {{ number_format($ad->views) }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-4">
            <h2 class="text-lg font-semibold text-gray-900 flex items-center gap-2">
                <i class="fas fa-list text-purple-500"></i> التصنيفات
            </h2>
            <div class="mt-3 grid grid-cols-1 gap-1.5">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}"
                        class="flex items-center justify-between rounded-xl border border-gray-100 px-4 py-2.5 text-sm text-gray-700 hover:bg-purple-50 hover:border-purple-200 transition-all duration-200 group">
<span class="group-hover:text-purple-600">{{ $category->arabic_name ?? $category->name }}</span>                        @if($category->ads_count)
                            <span class="text-xs text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full group-hover:bg-purple-100 group-hover:text-purple-600">{{ $category->ads_count }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </aside>

    {{-- Right: Search + Posts --}}
    <div id="main-ads" class="space-y-5">

        {{-- Search --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-5 sm:p-6">
                <form action="{{ route('search') }}" method="GET" class="space-y-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="keyword" value="{{ request('keyword') }}"
                            placeholder="ابحث عن خدمة أو منتج..."
                            class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 pl-11 pr-4 py-3.5 focus:border-purple-500 focus:bg-white focus:ring-4 focus:ring-purple-100 transition-all outline-none" />
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="relative">
                            <i class="fas fa-layer-group absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="category"
                                class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 pl-11 pr-10 py-3.5 appearance-none cursor-pointer focus:border-purple-500 focus:bg-white focus:ring-4 focus:ring-purple-100 transition-all outline-none">
                                <option value="">جميع التصنيفات</option>
                                @foreach($allCategories as $cat)
                                   <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
    {{ $cat->parent ? ($cat->parent->arabic_name ?? $cat->parent->name) . ' > ' : '' }}{{ $cat->arabic_name ?? $cat->name }}
</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                        <div class="relative">
                            <i class="fas fa-location-dot absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                            <select name="location"
                                class="w-full rounded-xl border-2 border-gray-200 bg-gray-50 pl-11 pr-10 py-3.5 appearance-none cursor-pointer focus:border-purple-500 focus:bg-white focus:ring-4 focus:ring-purple-100 transition-all outline-none">
                                <option value="">جميع المدن</option>
                                @foreach($searchCities as $city)
                                    <option value="{{ $city }}" {{ request('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                                @endforeach
                            </select>
                            <i class="fas fa-chevron-down absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xs pointer-events-none"></i>
                        </div>
                    </div>
                    <div class="flex gap-3">
                        <button type="submit"
                            class="flex-1 bg-gradient-to-r from-purple-600 to-indigo-600 text-white px-6 py-3.5 rounded-xl font-bold hover:from-purple-700 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                            <i class="fas fa-search ml-2"></i> بحث
                        </button>
                        <a href="{{ route('home') }}"
                            class="px-5 py-3.5 rounded-xl font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition-all duration-200 hover:scale-105">
                            <i class="fas fa-redo-alt"></i>
                        </a>
                    </div>
                </form>
            </div>
        </div>

        {{-- Auto-refresh notification --}}
        <div class="text-center text-xs text-gray-400 py-1">
            <i class="fas fa-sync-alt ml-1"></i> يتم تحديث الإعلانات تلقائياً كل ساعة
            <span class="mx-1">•</span>
            <span id="nextUpdateTimer"></span>
        </div>

        {{-- ══ PINNED / FEATURED POSTS (Stay at top, don't rotate) ══ --}}
        @if($pinnedAds->count() && request()->get('page', 1) == 1)
            <div class="space-y-4">
                <div class="flex items-center gap-2 mb-2">
                    <div class="w-1 h-6 bg-gradient-to-b from-amber-500 to-orange-500 rounded-full"></div>
                    <h3 class="text-sm font-bold text-gray-700">إعلانات مميزة</h3>
                    <span class="text-xs text-amber-600 bg-amber-50 px-2 py-0.5 rounded-full">ثابتة</span>
                </div>
                @foreach($pinnedAds as $ad)
                    @php $adImages = $ad->images; $imageCount = $adImages->count(); @endphp
                    <article class="bg-white rounded-xl border border-amber-100 overflow-hidden hover:shadow-xl transition-all duration-300 hover:border-amber-200">
                        <div class="p-4 sm:p-5">
                            <div class="flex flex-col md:flex-row gap-4">
                                {{-- Image Gallery --}}
                                <div class="md:w-48 flex-shrink-0">
                                    @if($imageCount > 0)
                                        <a href="{{ route('ads.show', $ad->slug) }}" class="block group overflow-hidden rounded-xl">
                                            <div class="relative h-32 w-full overflow-hidden">
                                                <img src="{{ asset('storage/' . $adImages->first()->image_path) }}"
                                                    alt="{{ $ad->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-all duration-300">
                                                @if($imageCount > 1)
                                                    <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-1.5 py-0.5 rounded">
                                                        +{{ $imageCount - 1 }}
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @else
                                        <div class="h-32 w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-xl flex items-center justify-center">
                                            <i class="fas fa-image text-3xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-2 flex-wrap">
                                        <span class="inline-flex items-center gap-1 bg-gradient-to-r from-amber-500 to-orange-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                            <i class="fas fa-crown text-[8px]"></i> مميز
                                        </span>
<span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs">{{ $ad->category->arabic_name ?? $ad->category->name }}</span>                                    </div>
                                    
                                    <a href="{{ route('ads.show', $ad->slug) }}"
                                        class="text-lg font-bold text-gray-900 hover:text-purple-600 transition line-clamp-1">
                                        {{ $ad->title }}
                                    </a>
                                    
                                    <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-gray-500">
                                        <span><i class="fas fa-map-marker-alt ml-1"></i> {{ $ad->location }}</span>
                                        <span><i class="far fa-clock ml-1"></i> {{ $ad->created_at->diffForHumans() }}</span>
                                        <span><i class="fas fa-eye ml-1"></i> {{ number_format($ad->views) }}</span>
                                    </div>
                                    
                                    <p class="mt-2 text-sm text-gray-600 line-clamp-2">{{ Str::limit($ad->description, 100) }}</p>
                                    
                                    <div class="flex flex-wrap items-center justify-between mt-3 gap-2">
                                        <div class="flex gap-2">
                                            <a href="tel:{{ $ad->phone }}"
                                                class="inline-flex items-center gap-1 rounded-full bg-green-600 text-white px-3 py-1.5 text-xs font-semibold hover:bg-green-700 transition">
                                                <i class="fas fa-phone-alt text-xs"></i> اتصل
                                            </a>
                                            @if($ad->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ad->whatsapp) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs font-semibold text-gray-700 hover:bg-green-50 transition">
                                                    <i class="fab fa-whatsapp text-green-600"></i> واتساب
                                                </a>
                                            @endif
                                        </div>
                                        @if($ad->price)
                                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-3 py-1.5 rounded-lg text-white font-bold text-sm">
                                                {{ number_format($ad->price) }} ر.س
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        {{-- ══ REGULAR POSTS (Auto-rotate every hour) ══ --}}
        <div class="bg-white rounded-2xl shadow-lg p-4 sm:p-6">
            <div class="flex items-center justify-between mb-5">
                <h2 class="text-xl sm:text-2xl font-semibold text-gray-900 flex items-center gap-2">
                    <i class="fas fa-clock text-purple-600"></i> أحدث الإعلانات
                    <span class="text-xs bg-purple-100 text-purple-600 px-2 py-0.5 rounded-full">تتغير كل ساعة</span>
                </h2>
                <span class="text-xs text-gray-400 bg-gray-100 px-3 py-1 rounded-full">
                    {{ $regularAds->total() }} إعلان
                </span>
            </div>

            <div class="space-y-4" id="postsContainer">
                @foreach($regularAds as $index => $ad)
                    @php 
                        $adImages = $ad->images; 
                        $imageCount = $adImages->count();
                        // Add rotation animation class based on position
                        $animationClass = $index < 3 ? 'animate-slide-in' : '';
                    @endphp
                    <article class="bg-white rounded-xl border border-gray-100 overflow-hidden hover:shadow-md transition-all duration-300 {{ $animationClass }}" data-post-id="{{ $ad->id }}">
                        <div class="p-4">
                            <div class="flex flex-col md:flex-row gap-4">
                                {{-- Image Gallery --}}
                                <div class="md:w-40 flex-shrink-0">
                                    @if($imageCount > 0)
                                        <a href="{{ route('ads.show', $ad->slug) }}" class="block group overflow-hidden rounded-lg">
                                            <div class="relative h-28 w-full overflow-hidden">
                                                <img src="{{ asset('storage/' . $adImages->first()->image_path) }}"
                                                    alt="{{ $ad->title }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-all duration-300">
                                                @if($imageCount > 1)
                                                    <div class="absolute bottom-1 right-1 bg-black bg-opacity-60 text-white text-xs px-1 py-0.5 rounded text-[10px]">
                                                        +{{ $imageCount }}
                                                    </div>
                                                @endif
                                            </div>
                                        </a>
                                    @else
                                        <div class="h-28 w-full bg-gradient-to-br from-gray-100 to-gray-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-2xl text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>

                                {{-- Content --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 mb-1">
<span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs">{{ $ad->category->arabic_name ?? $ad->category->name }}</span>                                        @if($index == 0)
                                            <span class="bg-blue-100 text-blue-600 text-xs px-2 py-0.5 rounded-full">
                                                <i class="fas fa-arrow-up"></i> جديد
                                            </span>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ route('ads.show', $ad->slug) }}"
                                        class="text-base font-bold text-gray-900 hover:text-purple-600 transition line-clamp-1">
                                        {{ $ad->title }}
                                    </a>
                                    
                                    <div class="flex flex-wrap items-center gap-3 mt-1 text-xs text-gray-500">
                                        <span><i class="fas fa-map-marker-alt ml-1"></i> {{ $ad->location }}</span>
                                        <span><i class="far fa-clock ml-1"></i> {{ $ad->created_at->diffForHumans() }}</span>
                                        <span><i class="fas fa-eye ml-1"></i> {{ number_format($ad->views) }}</span>
                                    </div>
                                    
                                    <p class="mt-1 text-xs text-gray-600 line-clamp-2">{{ Str::limit($ad->description, 80) }}</p>
                                    
                                    <div class="flex flex-wrap items-center justify-between mt-2 gap-2">
                                        <div class="flex gap-1.5">
                                            <a href="tel:{{ $ad->phone }}"
                                                class="inline-flex items-center gap-1 rounded-full bg-green-600 text-white px-2.5 py-1 text-xs font-semibold hover:bg-green-700 transition">
                                                <i class="fas fa-phone-alt text-xs"></i> اتصل
                                            </a>
                                            @if($ad->whatsapp)
                                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ad->whatsapp) }}" target="_blank"
                                                    class="inline-flex items-center gap-1 rounded-full border border-gray-200 bg-white px-2.5 py-1 text-xs font-semibold text-gray-700 hover:bg-green-50 transition">
                                                    <i class="fab fa-whatsapp text-green-600 text-xs"></i> واتساب
                                                </a>
                                            @endif
                                        </div>
                                        @if($ad->price)
                                            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 px-2.5 py-1 rounded-lg text-white font-bold text-xs">
                                                {{ number_format($ad->price) }} ر.س
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $regularAds->withQueryString()->links() }}
            </div>
        </div>

    </div>{{-- end right column --}}
</section>
</div>

@push('styles')
<style>
    .line-clamp-1 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
        height: 6px;
    }
    ::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    ::-webkit-scrollbar-thumb {
        background: linear-gradient(135deg, #8b5cf6, #6366f1);
        border-radius: 10px;
    }
    
    /* Smooth transitions */
    .transition-all {
        transition-property: all;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 300ms;
    }
    
    /* Slide in animation for new top posts */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-in {
        animation: slideIn 0.5s ease-out;
    }
    
    /* Pulse animation for update indicator */
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.5;
        }
    }
    
    .pulse {
        animation: pulse 2s infinite;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lazy loading for images
        const images = document.querySelectorAll('img');
        const imageObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.style.opacity = '1';
                    imageObserver.unobserve(img);
                }
            });
        });
        
        images.forEach(img => {
            img.style.opacity = '0';
            img.style.transition = 'opacity 0.3s ease';
            imageObserver.observe(img);
        });
        
        // Auto-refresh timer (refresh page every hour to rotate posts)
        let nextUpdateTime = localStorage.getItem('nextUpdateTime');
        const now = new Date().getTime();
        
        if (!nextUpdateTime || nextUpdateTime < now) {
            // Set next update to 1 hour from now
            nextUpdateTime = now + (60 * 60 * 1000);
            localStorage.setItem('nextUpdateTime', nextUpdateTime);
        }
        
        function updateTimer() {
            const nowTime = new Date().getTime();
            const timeLeft = nextUpdateTime - nowTime;
            
            if (timeLeft <= 0) {
                // Refresh the page to get new post order
                localStorage.removeItem('nextUpdateTime');
                location.reload();
                return;
            }
            
            const hours = Math.floor(timeLeft / (60 * 60 * 1000));
            const minutes = Math.floor((timeLeft % (60 * 60 * 1000)) / (60 * 1000));
            const seconds = Math.floor((timeLeft % (60 * 1000)) / 1000);
            
            const timerElement = document.getElementById('nextUpdateTimer');
            if (timerElement) {
                if (hours > 0) {
                    timerElement.innerHTML = `تحديث خلال ${hours} ساعة ${minutes} دقيقة`;
                } else if (minutes > 0) {
                    timerElement.innerHTML = `تحديث خلال ${minutes} دقيقة ${seconds} ثانية`;
                } else {
                    timerElement.innerHTML = `تحديث خلال ${seconds} ثانية`;
                    if (seconds <= 10) {
                        timerElement.classList.add('pulse');
                        timerElement.classList.add('text-amber-600');
                    }
                }
            }
        }
        
        updateTimer();
        setInterval(updateTimer, 1000);
    });
</script>
@endpush
@endsection