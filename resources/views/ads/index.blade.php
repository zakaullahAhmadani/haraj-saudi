@extends('layouts.app')

@section('title', 'جميع الإعلانات')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col lg:flex-row gap-6">

        <!-- Main Ads Column -->
        <div class="w-full lg:w-2/3 space-y-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold text-gray-900">جميع الإعلانات</h1>
                <span class="text-sm text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm">
                    {{ $regularAds->total() }} إعلان
                </span>
            </div>

            @forelse($regularAds as $ad)
                <div class="rounded-2xl border border-gray-100 bg-white p-4 hover:shadow-lg transition ad-card">
                    <div class="flex flex-col md:flex-row gap-4">
                        <div class="flex-1">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <a href="{{ route('ads.show', $ad->slug) }}">
                                    <h2 class="text-xl font-semibold text-gray-900 hover:text-purple-600 transition">{{ $ad->title }}</h2>
                                </a>
                                <span class="text-xs bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full">{{ $ad->category->name ?? 'غير مصنف' }}</span>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 mb-3">
                                <span><i class="fas fa-map-marker-alt ml-1"></i> {{ $ad->location }}</span>
                                <span><i class="far fa-clock ml-1"></i> {{ $ad->created_at->diffForHumans() }}</span>
                                <span><i class="fas fa-eye ml-1"></i> {{ number_format($ad->views) }} مشاهدة</span>
                            </div>
                            
                            <p class="text-sm text-gray-600 leading-relaxed">{{ Str::limit($ad->description, 120) }}</p>
                            
                            <div class="mt-4 flex flex-wrap items-center gap-3">
                                <a href="tel:{{ $ad->phone }}"
                                    class="inline-flex items-center gap-2 rounded-full bg-green-600 text-white px-4 py-2 text-sm font-semibold hover:bg-green-700 transition">
                                    <i class="fas fa-phone"></i> اتصل الآن
                                </a>
                                @if($ad->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $ad->whatsapp) }}" target="_blank"
                                        class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50 transition">
                                        <i class="fab fa-whatsapp text-green-600"></i> واتساب
                                    </a>
                                @endif
                            </div>
                        </div>

                        <div class="flex flex-col items-end gap-3 min-w-[160px]">
                            <div class="relative w-full max-w-[160px]">
                                @if($ad->images && $ad->images->count() > 0)
                                    <a href="{{ route('ads.show', $ad->slug) }}">
                                        <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                            alt="{{ $ad->title }}"
                                            class="w-full h-32 object-cover rounded-2xl border border-gray-200 hover:opacity-90 transition">
                                    </a>
                                    @if($ad->images->count() > 1)
                                        <div class="absolute bottom-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded-full">
                                            <i class="fas fa-images"></i> {{ $ad->images->count() }}
                                        </div>
                                    @endif
                                @else
                                    <div class="w-full h-32 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            @if($ad->price)
                                <div class="rounded-2xl bg-purple-600 px-4 py-2 text-white font-bold text-center w-full">
                                    {{ number_format($ad->price) }} ر.س
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-2xl border border-gray-100 p-12 text-center">
                    <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                    <p class="text-gray-500 text-lg">لا توجد إعلانات حتى الآن</p>
                    <a href="{{ route('ads.create') }}"
                        class="inline-block mt-4 bg-purple-600 text-white px-6 py-2 rounded-xl hover:bg-purple-700 transition">
                        أضف أول إعلان
                    </a>
                </div>
            @endforelse

            <div class="bg-white rounded-2xl shadow-sm p-4">
                {{ $regularAds->links() }}
            </div>
        </div>

        <!-- Top Rated Sidebar -->
        <div class="w-full lg:w-1/3">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden sticky top-24">
                <div class="bg-gradient-to-r from-amber-50 to-orange-50 px-5 py-4 border-b border-amber-100">
                    <div class="flex items-center gap-2">
                        <i class="fas fa-star text-yellow-500 text-xl"></i>
                        <h2 class="text-xl font-bold text-gray-800">الأعلى مشاهدة</h2>
                        <span class="ml-auto text-xs bg-amber-200 text-amber-800 rounded-full px-2 py-0.5">الأكثر مشاهدة</span>
                    </div>
                </div>

                <div class="divide-y divide-gray-100 max-h-[600px] overflow-y-auto">
                    @forelse($trendingAds as $index => $topAd)
                        <div class="p-4 hover:bg-gray-50 transition">
                            <a href="{{ route('ads.show', $topAd->slug) }}">
                                <h3 class="font-bold text-gray-800 hover:text-purple-600 transition">{{ Str::limit($topAd->title, 40) }}</h3>
                            </a>
                            <div class="flex flex-wrap gap-x-3 gap-y-1 text-xs text-gray-500 mt-1">
                                <span><i class="fas fa-map-marker-alt"></i> {{ $topAd->location }}</span>
                                <span><i class="fas fa-eye"></i> {{ number_format($topAd->views) }}</span>
                            </div>
                            @if($topAd->images && $topAd->images->first())
                                <a href="{{ route('ads.show', $topAd->slug) }}">
                                    <img src="{{ asset('storage/' . $topAd->images->first()->image_path) }}"
                                        alt="{{ $topAd->title }}"
                                        class="w-full h-28 object-cover rounded-xl mt-2 border border-gray-200">
                                </a>
                            @endif
                            <div class="flex gap-2 mt-3">
                                <a href="tel:{{ $topAd->phone }}"
                                    class="flex-1 text-center inline-flex justify-center items-center gap-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold py-2 rounded-xl transition">
                                    <i class="fas fa-phone-alt"></i> اتصل
                                </a>
                                @if($topAd->whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $topAd->whatsapp) }}" target="_blank"
                                        class="flex-1 text-center inline-flex justify-center items-center gap-1 bg-[#25D366] hover:bg-[#128C7E] text-white text-xs font-semibold py-2 rounded-xl transition">
                                        <i class="fab fa-whatsapp"></i> واتساب
                                    </a>
                                @endif
                            </div>
                            @if($topAd->price)
                                <div class="mt-2 text-right">
                                    <span class="inline-block bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">
                                        {{ number_format($topAd->price) }} ر.س
                                    </span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="p-4 text-center text-gray-500">لا توجد إعلانات</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .ad-card { transition: all 0.2s ease; }
    .ad-card:hover { transform: translateY(-2px); }
</style>
@endpush
@endsection