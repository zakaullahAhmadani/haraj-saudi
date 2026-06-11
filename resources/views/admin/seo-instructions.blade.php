@extends('layouts.app')

@section('title', 'إعدادات محركات البحث - حراج السعودية')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h1 class="text-3xl font-bold mb-6 text-purple-600">إعدادات محركات البحث</h1>
        
        <div class="space-y-6">
            <!-- Google Search Console -->
            <div class="border rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">🔍 Google Search Console</h2>
                <p class="mb-2"><strong>الخطوات:</strong></p>
                <ol class="list-decimal list-inside space-y-1 mr-4">
                    <li>سجل الدخول إلى <a href="https://search.google.com/search-console" target="_blank" class="text-blue-600">Google Search Console</a></li>
                    <li>أضف موقعك: <code class="bg-gray-100 px-2 py-1 rounded">{{ config('app.url') }}</code></li>
                    <li>اختر طريقة التحقق "HTML tag"</li>
                    <li>انسخ الكود وأضفه في ملف <code class="bg-gray-100 px-2 py-1 rounded">.env</code></li>
                    <li>الكود الحالي: <code class="bg-gray-100 px-2 py-1 rounded">{{ env('GOOGLE_SITE_VERIFICATION') }}</code></li>
                </ol>
            </div>
            
            <!-- Bing Webmaster -->
            <div class="border rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">🔍 Bing Webmaster Tools</h2>
                <p class="mb-2"><strong>الخطوات:</strong></p>
                <ol class="list-decimal list-inside space-y-1 mr-4">
                    <li>سجل الدخول إلى <a href="https://www.bing.com/webmasters" target="_blank" class="text-blue-600">Bing Webmaster Tools</a></li>
                    <li>أضف موقعك: <code class="bg-gray-100 px-2 py-1 rounded">{{ config('app.url') }}</code></li>
                    <li>اختر طريقة التحقق "Meta tag"</li>
                    <li>انسخ الكود وأضفه في ملف <code class="bg-gray-100 px-2 py-1 rounded">.env</code></li>
                    <li>الكود الحالي: <code class="bg-gray-100 px-2 py-1 rounded">{{ env('BING_SITE_VERIFICATION') }}</code></li>
                </ol>
            </div>
            
            <!-- Sitemap -->
            <div class="border rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">🗺️ Sitemap</h2>
                <p class="mb-2"><strong>رابط الخريطة:</strong></p>
                <a href="{{ url('/sitemap.xml') }}" target="_blank" class="text-blue-600 underline">{{ url('/sitemap.xml') }}</a>
                <p class="mt-2"><strong>إرسال لمحركات البحث:</strong></p>
                <ul class="list-disc list-inside mr-4">
                    <li>Google: <code class="bg-gray-100 px-2 py-1 rounded">https://www.google.com/ping?sitemap={{ urlencode(url('/sitemap.xml')) }}</code></li>
                    <li>Bing: <code class="bg-gray-100 px-2 py-1 rounded">https://www.bing.com/ping?sitemap={{ urlencode(url('/sitemap.xml')) }}</code></li>
                </ul>
            </div>
            
            <!-- IndexNow -->
            <div class="border rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">⚡ IndexNow</h2>
                <p class="mb-2"><strong>مفتاح API:</strong></p>
                <code class="bg-gray-100 px-2 py-1 rounded block mb-2">{{ env('INDEXNOW_KEY') }}</code>
                <p class="mb-2"><strong>رابط المفتاح:</strong></p>
                <a href="{{ url('/indexnow') }}" target="_blank" class="text-blue-600 underline">{{ url('/indexnow') }}</a>
                <p class="mt-2"><strong>الأمر لتحديث IndexNow:</strong></p>
                <code class="bg-gray-100 px-2 py-1 rounded">php artisan seo:submit-sitemap</code>
            </div>
            
            <!-- Open Graph -->
            <div class="border rounded-lg p-4">
                <h2 class="text-2xl font-bold mb-4">📱 Open Graph Tags</h2>
                <p class="mb-2">تم إضافة جميع Tags المطلوبة لمشاركة الروابط على وسائل التواصل الاجتماعي:</p>
                <ul class="list-disc list-inside mr-4">
                    <li>Facebook</li>
                    <li>Twitter (X)</li>
                    <li>LinkedIn</li>
                    <li>WhatsApp</li>
                    <li>Telegram</li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 p-4 bg-green-50 rounded-lg">
            <h3 class="font-bold text-green-800 mb-2">✅ الأوامر المفيدة:</h3>
            <pre class="bg-gray-900 text-green-400 p-4 rounded overflow-x-auto">
# إنشاء الخريطة وإرسالها
php artisan seo:submit-sitemap

# مسح الكاش
php artisan optimize:clear

# إعادة إنشاء الخريطة
php artisan cache:clear
            </pre>
        </div>
    </div>
</div>
@endsection