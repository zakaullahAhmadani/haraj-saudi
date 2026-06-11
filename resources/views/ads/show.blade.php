@extends('layouts.app')

@section('title', $ad->title)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <div class="flex flex-col lg:flex-row gap-8">

        <!-- LEFT: Seller Info & Contact -->
        <div class="lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-purple-50 to-indigo-50 px-6 py-6 text-center border-b border-gray-100">
                    <div class="w-20 h-20 mx-auto bg-purple-100 rounded-full flex items-center justify-center mb-3">
                        <i class="fas fa-user-circle text-5xl text-purple-600"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800">{{ $ad->user->name ?? 'مستخدم' }}</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        <i class="fas fa-calendar-alt ml-1"></i> عضو منذ {{ $ad->user->created_at->format('Y') ?? '' }}
                    </p>
                    <div class="flex justify-center gap-2 mt-3">
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">
                            <i class="fas fa-check-circle"></i> موثّق
                        </span>
                        <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full">
                            {{ $ad->user->ads->count() ?? 0 }} إعلان
                        </span>
                    </div>
                </div>

                <div class="px-5 py-4 border-b border-gray-100">
                    <h4 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                        <i class="fas fa-address-card text-purple-500"></i> معلومات التواصل
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-phone-alt text-green-600 mt-0.5"></i>
                            <div>
                                <p class="text-xs text-gray-500">رقم الهاتف</p>
                                <p class="text-gray-800 font-medium">{{ $ad->phone }}</p>
                            </div>
                        </div>
                        @if($ad->whatsapp)
                            <div class="flex items-start gap-2">
                                <i class="fab fa-whatsapp text-green-500 mt-0.5"></i>
                                <div>
                                    <p class="text-xs text-gray-500">واتساب</p>
                                    <p class="text-gray-800 font-medium">{{ $ad->whatsapp }}</p>
                                </div>
                            </div>
                        @endif
                        <div class="flex items-start gap-2">
                            <i class="fas fa-map-marker-alt text-red-500 mt-0.5"></i>
                            <div>
                                <p class="text-xs text-gray-500">الموقع</p>
                                <p class="text-gray-800 font-medium">{{ $ad->location }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-calendar text-blue-500 mt-0.5"></i>
                            <div>
                                <p class="text-xs text-gray-500">تاريخ النشر</p>
                                <p class="text-gray-800 font-medium">{{ $ad->created_at->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="px-5 py-4 border-b border-gray-100 space-y-3">
                    <a href="tel:{{ $ad->phone }}"
                        class="flex items-center justify-center gap-2 w-full bg-green-600 text-white px-4 py-3 rounded-xl hover:bg-green-700 transition font-semibold">
                        <i class="fas fa-phone-alt"></i> اتصل الآن
                    </a>
                    @if($ad->whatsapp)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ad->whatsapp) }}" target="_blank"
                            class="flex items-center justify-center gap-2 w-full bg-[#25D366] text-white px-4 py-3 rounded-xl hover:bg-[#128C7E] transition font-semibold">
                            <i class="fab fa-whatsapp"></i> واتساب
                        </a>
                    @endif
                </div>

                <div class="px-5 py-4">
                    <button onclick="navigator.clipboard.writeText(window.location.href); alert('تم نسخ الرابط!')"
                        class="flex items-center justify-center gap-2 w-full border border-gray-300 text-gray-600 px-4 py-2 rounded-xl hover:bg-gray-50 transition text-sm">
                        <i class="fas fa-share-alt"></i> مشاركة الإعلان
                    </button>
                </div>
            </div>
        </div>

        <!-- RIGHT: Ad Details -->
        <div class="lg:w-2/3">
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden mb-6">
                <!-- Image Gallery -->
                @if($ad->images && $ad->images->count() > 0)
                    <div class="swiper-container main-swiper">
                        <div class="swiper-wrapper">
                            @foreach($ad->images as $image)
                                <div class="swiper-slide">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                        alt="{{ $ad->title }}"
                                        class="w-full h-80 object-cover">
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-pagination"></div>
                    </div>
                @else
                    <div class="w-full h-80 bg-gray-100 flex items-center justify-center">
                        <i class="fas fa-image text-6xl text-gray-300"></i>
                    </div>
                @endif

                <div class="p-6">
                    <div class="flex justify-between items-start mb-4 gap-4">
                        <div class="flex-1">
                            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">{{ $ad->title }}</h1>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500">
                                <span class="bg-purple-100 text-purple-700 px-3 py-1 rounded-full text-xs">
                                    {{ $ad->category->name }}
                                </span>
                                <span><i class="far fa-eye ml-1"></i> {{ number_format($ad->views) }} مشاهدة</span>
                                <span><i class="far fa-clock ml-1"></i> {{ $ad->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                        @if($ad->price)
                            <div class="bg-purple-600 text-white text-2xl font-bold px-5 py-3 rounded-2xl whitespace-nowrap">
                                {{ number_format($ad->price) }} ر.س
                            </div>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-3 flex items-center gap-2">
                            <i class="fas fa-file-alt text-purple-500"></i> الوصف
                        </h2>
                        <div class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $ad->description }}</div>
                    </div>

                    @if($ad->keywords)
                        <div class="mb-6">
                            <h3 class="text-sm font-semibold text-gray-700 mb-2 flex items-center gap-2">
                                <i class="fas fa-tags text-purple-500"></i> الكلمات المفتاحية
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach(explode(',', $ad->keywords) as $keyword)
                                    <a href="{{ route('search', ['keyword' => trim($keyword)]) }}"
                                        class="text-xs bg-gray-100 text-gray-700 px-3 py-1 rounded-full hover:bg-purple-100 hover:text-purple-700 transition">
                                        #{{ trim($keyword) }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    @auth
                        @if(Auth::id() === $ad->user_id)
                            <div class="mt-6 pt-4 border-t border-gray-200 flex gap-3">
                                <a href="{{ route('ads.edit', $ad) }}"
                                    class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2 rounded-xl hover:bg-blue-700 transition">
                                    <i class="fas fa-edit"></i> تعديل
                                </a>
                                <form action="{{ route('ads.destroy', $ad) }}" method="POST"
                                    onsubmit="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center gap-2 bg-red-600 text-white px-5 py-2 rounded-xl hover:bg-red-700 transition">
                                        <i class="fas fa-trash"></i> حذف
                                    </button>
                                </form>
                            </div>
                        @endif
                    @endauth
                </div>
            </div>

            <!-- Related Ads -->
            @if($relatedAds->count() > 0)
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <i class="fas fa-tags text-purple-500"></i> إعلانات مشابهة
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($relatedAds as $relatedAd)
                            <a href="{{ route('ads.show', $relatedAd->slug) }}" class="group">
                                <div class="bg-gray-50 rounded-2xl overflow-hidden hover:shadow-md transition">
                                    <div class="relative h-36 overflow-hidden">
                                        @if($relatedAd->images && $relatedAd->images->first())
                                            <img src="{{ asset('storage/' . $relatedAd->images->first()->image_path) }}"
                                                alt="{{ $relatedAd->title }}"
                                                class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                        @else
                                            <div class="w-full h-full bg-gray-200 flex items-center justify-center">
                                                <i class="fas fa-image text-3xl text-gray-400"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="p-3">
                                        <h3 class="font-semibold text-gray-800 line-clamp-1 text-sm">{{ $relatedAd->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-1">
                                            <i class="fas fa-map-marker-alt ml-1"></i>{{ $relatedAd->location }}
                                        </p>
                                        @if($relatedAd->price)
                                            <p class="text-purple-600 font-bold mt-1 text-sm">{{ number_format($relatedAd->price) }} ر.س</p>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<style>
    .swiper-button-next, .swiper-button-prev {
        color: white;
        background: rgba(0,0,0,0.5);
        width: 40px; height: 40px;
        border-radius: 50%;
    }
    .swiper-button-next:after, .swiper-button-prev:after { font-size: 18px; }
    .swiper-pagination-bullet-active { background: white; }
    .line-clamp-1 { display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    new Swiper('.main-swiper', {
        loop: true,
        pagination: { el: '.swiper-pagination', clickable: true },
        navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
    });
</script>
@endpush
@endsection
