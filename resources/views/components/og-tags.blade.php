{{-- Open Graph Meta Tags --}}
<meta property="og:title" content="{{ $title ?? 'حراج السعودية - سوق السيارات والعقارات والإعلانات المبوبة' }}" />
<meta property="og:description" content="{{ $description ?? 'أكبر سوق إعلانات مبوبة في السعودية. بيع وشراء السيارات، العقارات، الأجهزة، والخدمات. إعلانات مجانية وسريعة.' }}" />
<meta property="og:type" content="{{ $type ?? 'website' }}" />
<meta property="og:url" content="{{ url()->current() }}" />
<meta property="og:image" content="{{ $image ?? asset('images/og-image.jpg') }}" />
<meta property="og:site_name" content="حراج السعودية" />
<meta property="og:locale" content="ar_AR" />
<meta property="og:locale:alternate" content="en_US" />

{{-- Twitter Card Tags --}}
<meta name="twitter:card" content="summary_large_image" />
<meta name="twitter:title" content="{{ $title ?? 'حراج السعودية' }}" />
<meta name="twitter:description" content="{{ $description ?? 'أكبر سوق إعلانات مبوبة في السعودية' }}" />
<meta name="twitter:image" content="{{ $image ?? asset('images/og-image.jpg') }}" />

{{-- Facebook Domain Verification --}}
<meta property="fb:app_id" content="{{ env('FACEBOOK_APP_ID') }}" />
<meta property="fb:admins" content="{{ env('FACEBOOK_ADMIN_ID') }}" />