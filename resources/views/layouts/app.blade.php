<!DOCTYPE html>
<html lang="ar" dir="ltr">
<head>
   {{-- Basic Meta Tags --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
<meta name="description" content="@yield('description', 'أكبر سوق إعلانات مبوبة في السعودية. بيع وشراء السيارات، العقارات، الأجهزة، والخدمات. إعلانات مجانية وسريعة.')">
<meta name="keywords" content="حراج, سوق, سيارات, عقارات, إعلانات, السعودية, haraj, saudi, market">
<meta name="author" content="حراج السعودية">
<meta name="robots" content="index, follow">
<meta name="googlebot" content="index, follow">

{{-- Language and Region --}}
<meta name="language" content="ar">
<meta name="geo.region" content="SA">
<meta name="geo.placename" content="Saudi Arabia">
<meta name="geo.position" content="23.885942;45.079162">
<meta name="ICBM" content="23.885942, 45.079162">

{{-- Open Graph Tags --}}
<x-og-tags 
    title="@yield('og_title', 'حراج السعودية')"
    description="@yield('og_description', 'أكبر سوق إعلانات مبوبة في السعودية')"
    type="@yield('og_type', 'website')"
    image="@yield('og_image', asset('images/og-image.jpg'))"
/>

{{-- Google Search Console --}}
<meta name="google-site-verification" content="{{ env('GOOGLE_SITE_VERIFICATION') }}">

{{-- Bing Webmaster Tools --}}
<meta name="msvalidate.01" content="{{ env('BING_SITE_VERIFICATION') }}">

{{-- IndexNow --}}
<meta name="indexnow" content="{{ env('INDEXNOW_KEY') }}">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ url()->current() }}">

{{-- Alternate URLs --}}
@if(app()->getLocale() == 'ar')
    <link rel="alternate" hreflang="en" href="{{ url('/en' . request()->getRequestUri()) }}">
@else
    <link rel="alternate" hreflang="ar" href="{{ url('/ar' . request()->getRequestUri()) }}">
@endif

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
      <!-- Arabic Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body {
           font-family: 'Cairo', 'Almarai', 'Tajawal', 'IBM Plex Sans Arabic', sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }
        
        /* Remove any English fonts */
        h1, h2, h3, h4, h5, h6, p, span, a, div, button, input {
            font-family: 'Cairo', 'Tajawal', 'Almarai', 'IBM Plex Sans Arabic', sans-serif !important;
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gray-50 antialiased">

    <!-- Top Categories Bar -->
    <div class="hidden md:block bg-gradient-to-r from-gray-100 to-gray-50 border-b border-gray-200">
        <div class="container mx-auto px-4">
            <div class="flex items-center justify-start gap-8 py-3 overflow-x-auto">
                @if(isset($headerCategories) && $headerCategories->count())
                    @foreach($headerCategories->take(7) as $catNav)
                        <a href="{{ route('categories.show', $catNav->slug) }}" class="flex items-center gap-2 text-gray-700 hover:text-purple-600 transition whitespace-nowrap text-sm font-medium group">
                            <span class="inline-flex items-center justify-center w-5 h-5 rounded-full bg-purple-100 group-hover:bg-purple-600 text-purple-600 group-hover:text-white transition">
                                <i class="fas fa-tag text-xs"></i>
                            </span>
                            {{ $catNav->name }}
                        </a>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
               <a href="/" class="flex items-center gap-2">
    <i class="fas fa-store text-2xl text-purple-600"></i>
    <span class="text-2xl font-bold text-purple-600">حراج السعودية</span>
</a>

                <div class="hidden md:flex items-center space-x-6 space-x-reverse">
                    <a href="/" class="text-gray-700 hover:text-purple-600 transition">الرئيسية</a>
                    <a href="{{ route('ads.index') }}" class="text-gray-700 hover:text-purple-600 transition">جميع الإعلانات</a>
                    @auth
                        <a href="{{ route('profile.index') }}" class="text-gray-700 hover:text-purple-600 transition">
                            <i class="fas fa-user ml-1"></i>{{ Auth::user()->name }}
                        </a>
                        @if(Auth::user()->is_admin)
                            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-purple-600 transition">
                                <i class="fas fa-cog ml-1"></i>لوحة التحكم
                            </a>
                        @endif
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-red-600 transition">
                                <i class="fas fa-sign-out-alt ml-1"></i>تسجيل الخروج
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-purple-600 transition">تسجيل الدخول</a>
                        <a href="{{ route('register') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition">
                            إنشاء حساب
                        </a>
                    @endauth
                    <a href="{{ route('ads.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        <i class="fas fa-plus ml-1"></i>أضف إعلان
                    </a>
                </div>

                <div class="md:hidden flex items-center gap-2">
                    <a href="{{ route('ads.create') }}" class="inline-flex items-center rounded-full bg-green-600 text-white px-3 py-2 text-sm font-semibold hover:bg-green-700 transition">
                        <i class="fas fa-plus ml-1"></i> إعلان
                    </a>
                    <button id="menu-btn" class="text-gray-600">
                        <i class="fas fa-bars text-2xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden bg-white border-t">
            <div class="container mx-auto px-4 py-3 space-y-3">
                <a href="/" class="block text-gray-700 py-2">الرئيسية</a>
                <a href="{{ route('ads.index') }}" class="block text-gray-700 py-2">جميع الإعلانات</a>
                @auth
                    <a href="{{ route('profile.index') }}" class="block text-gray-700 py-2">{{ Auth::user()->name }}</a>
                    @if(Auth::user()->is_admin)
                        <a href="{{ route('admin.dashboard') }}" class="block text-gray-700 py-2">لوحة التحكم</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block text-red-600 py-2">تسجيل الخروج</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block text-gray-700 py-2">تسجيل الدخول</a>
                    <a href="{{ route('register') }}" class="block bg-purple-600 text-white px-4 py-2 rounded-lg text-center">إنشاء حساب</a>
                @endauth
                <a href="{{ route('ads.create') }}" class="block bg-green-600 text-white px-4 py-2 rounded-lg text-center">أضف إعلان</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
               <div>
    <div class="flex items-center gap-2 mb-4">
        <i class="fas fa-store text-2xl text-purple-400"></i>
        <h3 class="text-2xl font-bold bg-gradient-to-r from-purple-400 to-indigo-400 bg-clip-text text-transparent">
            حراج السعودية
        </h3>
    </div>
    <p class="text-gray-400 leading-relaxed">
        سوقك الإلكتروني الموثوق للبيع والشراء في المملكة العربية السعودية
    </p>
</div>
                <div>
                    <h4 class="font-bold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/" class="hover:text-white">الرئيسية</a></li>
                        <li><a href="{{ route('ads.index') }}" class="hover:text-white">جميع الإعلانات</a></li>
                        <li><a href="{{ route('ads.create') }}" class="hover:text-white">أضف إعلان</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">التصنيفات</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>إلكترونيات</li>
                        <li>أثاث</li>
                        <li>خدمات</li>
                        <li>عقارات</li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-bold mb-4">تواصل معنا</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope ml-2"></i> info@souq.com</li>
                        <li><i class="fas fa-phone ml-2"></i> +92 331 8624880</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-4 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} سوق. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('menu-btn')?.addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
