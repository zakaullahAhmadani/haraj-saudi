@extends('layouts.app')

@section('title', 'نتائج البحث')

@section('content')
<div class="grid grid-cols-1 xl:grid-cols-4 gap-6">

    <!-- Filter Panel -->
    <aside class="xl:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">تصفية البحث</h2>
            <form action="{{ route('search') }}" method="GET" class="space-y-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة البحث</label>
                    <input type="text" name="keyword" value="{{ request('keyword') }}"
                        placeholder="مثال: تكييف، كهربائي..."
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">المدينة</label>
                    <select name="location"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100">
                        <option value="">جميع المدن</option>
                        @foreach($locations as $location)
                            <option value="{{ $location }}" {{ request('location') == $location ? 'selected' : '' }}>{{ $location }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">التصنيف</label>
                    <select name="category"
                        class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100">
                        <option value="">جميع التصنيفات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->parent ? $category->parent->name . ' > ' : '' }}{{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex gap-3">
                    <button type="submit"
                        class="flex-1 rounded-xl bg-green-600 text-white px-4 py-3 font-semibold hover:bg-green-700 transition">
                        بحث
                    </button>
                    <a href="{{ route('search') }}"
                        class="flex-1 rounded-xl border border-gray-200 px-4 py-3 text-gray-700 text-center hover:bg-gray-50 transition">
                        إعادة تعيين
                    </a>
                </div>
            </form>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">بحث شائع</h2>
            <div class="grid grid-cols-1 gap-3">
                @foreach(['تكييف', 'كهربائي', 'سباكة', 'تنظيف منازل', 'نقل أثاث'] as $query)
                    <a href="{{ route('search', ['keyword' => $query]) }}"
                        class="block rounded-xl border border-gray-200 px-4 py-3 text-gray-700 hover:bg-gray-50 transition">
                        {{ $query }}
                    </a>
                @endforeach
            </div>
        </div>
    </aside>

    <!-- Results -->
    <main class="xl:col-span-3 space-y-6">
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-900">نتائج البحث</h1>
                    <p class="mt-2 text-gray-600">{{ $ads->total() }} إعلان مطابق لبحثك</p>
                </div>
                <div class="text-sm text-gray-500 flex flex-wrap gap-2">
                    @if(request('keyword'))
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full">
                            {{ request('keyword') }}
                            <a href="{{ route('search', array_merge(request()->except('keyword'))) }}" class="ml-1 hover:text-red-500">&times;</a>
                        </span>
                    @endif
                    @if(request('location'))
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full">
                            {{ request('location') }}
                            <a href="{{ route('search', array_merge(request()->except('location'))) }}" class="ml-1 hover:text-red-500">&times;</a>
                        </span>
                    @endif
                </div>
            </div>
        </div>

        @if($ads->count())
            <div class="space-y-4">
                @foreach($ads as $ad)
                    <article class="bg-white rounded-2xl border border-gray-100 p-4 hover:shadow-lg transition">
                        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-4 items-center">
                            <div>
                                <a href="{{ route('ads.show', $ad->slug) }}"
                                    class="text-xl font-semibold text-gray-900 hover:text-green-600 transition">
                                    {{ $ad->title }}
                                </a>
                                <div class="flex flex-wrap items-center gap-2 mt-2 text-sm text-gray-500">
                                    <span class="bg-purple-100 text-purple-700 px-2 py-0.5 rounded-full text-xs">{{ $ad->category->name }}</span>
                                    <span><i class="fas fa-map-marker-alt ml-1"></i>{{ $ad->location }}</span>
                                    <span><i class="far fa-clock ml-1"></i>{{ $ad->created_at->diffForHumans() }}</span>
                                    <span><i class="fas fa-eye ml-1"></i>{{ number_format($ad->views) }}</span>
                                </div>
                                @if($ad->keywords)
                                    <div class="mt-2 flex flex-wrap gap-2">
                                        @foreach(explode(',', $ad->keywords) as $keyword)
                                            <a href="{{ route('search', ['keyword' => trim($keyword)]) }}"
                                                class="text-xs bg-gray-100 text-gray-700 px-2 py-1 rounded-full hover:bg-gray-200 transition">
                                                #{{ trim($keyword) }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                                <p class="mt-3 text-sm text-gray-600 leading-relaxed">{{ Str::limit($ad->description, 140) }}</p>
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
                            <div class="flex flex-col items-end gap-3">
                                @if($ad->images->first())
                                    <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                        alt="{{ $ad->title }}"
                                        class="w-full max-w-[160px] h-36 object-cover rounded-2xl">
                                @else
                                    <div class="w-full max-w-[160px] h-36 bg-gray-100 rounded-2xl flex items-center justify-center text-gray-400">
                                        <i class="fas fa-image text-3xl"></i>
                                    </div>
                                @endif
                                @if($ad->price)
                                    <div class="rounded-2xl bg-purple-600 px-4 py-2 text-white font-bold">
                                        {{ number_format($ad->price) }} ر.س
                                    </div>
                                @endif
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $ads->appends(request()->query())->links() }}
            </div>
        @else
            <div class="bg-white rounded-2xl shadow-lg p-10 text-center">
                <i class="fas fa-search text-5xl text-gray-300 mb-4"></i>
                <h2 class="text-2xl font-semibold text-gray-900">لا توجد نتائج</h2>
                <p class="mt-3 text-gray-600">جرّب كلمة بحث مختلفة أو غيّر المدينة والتصنيف</p>
                <a href="{{ route('home') }}"
                    class="inline-flex items-center gap-2 mt-6 rounded-xl bg-green-600 px-6 py-3 text-white font-semibold hover:bg-green-700 transition">
                    العودة للرئيسية
                </a>
            </div>
        @endif
    </main>
</div>
@endsection
