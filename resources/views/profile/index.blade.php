@extends('layouts.app')

@section('title', 'ملفي الشخصي')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    {{-- Profile Header --}}
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-20 h-20 bg-purple-100 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-3xl text-purple-600"></i>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->name }}</h1>
                <p class="text-gray-500">{{ $user->email }}</p>
                <p class="text-gray-500 text-sm">عضو منذ {{ $user->created_at->translatedFormat('F Y') }}</p>
            </div>
        </div>

        {{-- Stats --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-blue-600">{{ $stats['total_ads'] }}</div>
                <div class="text-gray-600 text-sm mt-1">إجمالي الإعلانات</div>
            </div>
            <div class="bg-green-50 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-green-600">{{ number_format($stats['total_views']) }}</div>
                <div class="text-gray-600 text-sm mt-1">إجمالي المشاهدات</div>
            </div>
            <div class="bg-yellow-50 p-4 rounded-xl text-center">
                <div class="text-2xl font-bold text-yellow-600">{{ $stats['active_ads'] }}</div>
                <div class="text-gray-600 text-sm mt-1">الإعلانات النشطة</div>
            </div>
        </div>

        {{-- Edit Profile Form --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                    @error('name')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">البريد الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                    @error('email')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">رقم الهاتف</label>
                    <input type="tel" name="phone" value="{{ old('phone', $user->phone) }}"
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                </div>
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">رقم واتساب</label>
                    <input type="tel" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}"
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 font-semibold mb-2">المدينة</label>
                    <input type="text" name="location" value="{{ old('location', $user->location) }}"
                        class="w-full px-4 py-2 border rounded-xl focus:outline-none focus:border-purple-500">
                </div>
            </div>

            <button type="submit"
                class="mt-4 bg-purple-600 text-white px-6 py-2 rounded-xl hover:bg-purple-700 transition font-semibold">
                حفظ التغييرات
            </button>
        </form>
    </div>

    {{-- My Ads --}}
    <div class="bg-white rounded-2xl shadow-md p-6">
        <div class="flex items-center justify-between mb-5">
            <h2 class="text-xl font-bold text-gray-900">إعلاناتي</h2>
            <a href="{{ route('ads.create') }}"
                class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700 transition text-sm font-semibold">
                <i class="fas fa-plus ml-1"></i> إعلان جديد
            </a>
        </div>

        @forelse($ads as $ad)
            <div class="border-b border-gray-100 pb-4 mb-4 last:border-0 last:mb-0 last:pb-0">
                <div class="flex justify-between items-start gap-4">
                    <div class="flex gap-3 flex-1">
                        @if($ad->images->first())
                            <img src="{{ asset('storage/' . $ad->images->first()->image_path) }}"
                                alt="{{ $ad->title }}"
                                class="w-16 h-16 object-cover rounded-xl flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-image text-gray-400"></i>
                            </div>
                        @endif
                        <div>
                            <a href="{{ route('ads.show', $ad->slug) }}"
                                class="font-semibold text-gray-800 hover:text-purple-600 transition">
                                {{ $ad->title }}
                            </a>
                            <p class="text-sm text-gray-500 mt-0.5">
                                <i class="fas fa-map-marker-alt ml-1 text-xs"></i>{{ $ad->location }}
                                <span class="mx-2">•</span>
                                <i class="far fa-clock ml-1 text-xs"></i>{{ $ad->created_at->diffForHumans() }}
                            </p>
                            <p class="text-sm text-gray-500">
                                <i class="fas fa-eye ml-1 text-xs"></i>{{ number_format($ad->views) }} مشاهدة
                                @if($ad->price)
                                    <span class="mx-2">•</span>
                                    <span class="text-purple-600 font-bold">{{ number_format($ad->price) }} ر.س</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <div class="flex gap-2 flex-shrink-0">
                        <a href="{{ route('ads.edit', $ad) }}"
                            class="bg-blue-100 text-blue-700 px-3 py-1.5 rounded-lg hover:bg-blue-200 transition text-sm font-medium">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form method="POST" action="{{ route('ads.destroy', $ad) }}" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg hover:bg-red-200 transition text-sm font-medium"
                                onclick="return confirm('هل أنت متأكد من حذف هذا الإعلان؟')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-10">
                <i class="fas fa-newspaper text-5xl text-gray-300 mb-3"></i>
                <p class="text-gray-500 mb-4">لم تنشر أي إعلانات بعد</p>
                <a href="{{ route('ads.create') }}"
                    class="inline-block bg-purple-600 text-white px-6 py-2 rounded-xl hover:bg-purple-700 transition">
                    أضف أول إعلان
                </a>
            </div>
        @endforelse

        <div class="mt-4">
            {{ $ads->links() }}
        </div>
    </div>
</div>
@endsection
