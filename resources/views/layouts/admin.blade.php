<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') — {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #f3f4f6 100%);
        }
        
        /* Sidebar Styles */
        .sidebar-link {
            transition: all 0.25s ease-in-out;
            position: relative;
            overflow: hidden;
        }
        
        .sidebar-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 0;
            height: 100%;
            background: rgba(255, 255, 255, 0.05);
            transition: width 0.3s ease;
        }
        
        .sidebar-link:hover::before {
            width: 100%;
        }
        
        .sidebar-link.active {
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.2), rgba(99, 102, 241, 0.1));
            border-left: 3px solid #8b5cf6;
            color: white;
        }
        
        .sidebar-link.active i {
            color: #a78bfa;
        }
        
        /* Mobile sidebar overlay */
        .sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(4px);
            z-index: 40;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
        
        /* Stat Cards */
        .stat-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }
        
        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }
        
        .stat-card:hover::before {
            left: 100%;
        }
        
        .stat-card:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 20px 25px -12px rgba(0, 0, 0, 0.15), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
        }
        
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb {
            background: linear-gradient(135deg, #8b5cf6, #6366f1);
            border-radius: 10px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(135deg, #7c3aed, #4f46e5);
        }
        
        /* Mobile Menu Button Animation */
        .menu-icon {
            transition: all 0.3s ease;
        }
        
        .menu-icon.active {
            transform: rotate(90deg);
        }
        
        /* Smooth transitions */
        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        /* Card hover effects */
        .hover-lift:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.15);
        }
        
        /* Loading animation */
        @keyframes shimmer {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        
        .skeleton {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: shimmer 1.5s infinite;
        }
        
        /* Mobile responsive adjustments */
        @media (max-width: 768px) {
            .sidebar-mobile-hidden {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar-mobile-visible {
                transform: translateX(0);
            }
        }
    </style>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">

{{-- Mobile Sidebar Overlay --}}
<div id="sidebarOverlay" class="sidebar-overlay lg:hidden"></div>

<div class="flex min-h-screen relative">

    <!-- Sidebar - Responsive -->
    <aside id="sidebar" 
        class="fixed lg:relative inset-y-0 left-0 z-50 w-72 lg:w-64 bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 text-white flex-shrink-0 flex flex-col shadow-2xl transform transition-transform duration-300 ease-in-out lg:translate-x-0 sidebar-mobile-hidden lg:block">
        
        <!-- Logo Section -->
        <div class="px-5 py-6 border-b border-slate-700/50 bg-gradient-to-r from-slate-800 to-slate-900">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="text-base font-extrabold text-white tracking-tight">Admin Panel</h2>
                    <p class="text-xs text-slate-400">Marketplace Control</p>
                </div>
            </div>
        </div>

        <!-- User Info -->
        <div class="px-5 py-4 border-b border-slate-700/50 bg-slate-800/30">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-sm font-bold shadow-md">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-5 space-y-1 overflow-y-auto">
            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-3">Main Menu</p>

            <a href="{{ route('admin.dashboard') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'active bg-white/10 text-white' : '' }}">
                <i class="fas fa-chart-line w-5 text-center text-base"></i>
                <span class="text-sm font-medium">Dashboard</span>
                @if(request()->routeIs('admin.dashboard'))
                    <i class="fas fa-arrow-left text-xs ml-auto opacity-50"></i>
                @endif
            </a>

            <a href="{{ route('admin.posts') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.posts') ? 'active bg-white/10 text-white' : '' }}">
                <i class="fas fa-newspaper w-5 text-center text-base"></i>
                <span class="text-sm font-medium">Manage Posts</span>
                @php $pendingCount = \App\Models\Ad::where('is_active', false)->count(); @endphp
                @if($pendingCount > 0)
                    <span class="ml-auto bg-red-500 text-white text-xs px-2 py-0.5 rounded-full animate-pulse">{{ $pendingCount }}</span>
                @endif
            </a>

            <a href="{{ route('admin.featured') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.featured*') ? 'active bg-white/10 text-white' : '' }}">
                <i class="fas fa-crown w-5 text-center text-amber-400 text-base"></i>
                <span class="text-sm font-medium">Featured Posts</span>
                @php $activePins = \App\Models\Ad::pinnedByAdmin()->count(); @endphp
                @if($activePins > 0)
                    <span class="ml-auto bg-amber-500 text-white text-xs px-2 py-0.5 rounded-full">{{ $activePins }}</span>
                @endif
            </a>

            <a href="{{ route('admin.users') }}"
                class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white transition-all duration-200 {{ request()->routeIs('admin.users') ? 'active bg-white/10 text-white' : '' }}">
                <i class="fas fa-users w-5 text-center text-base"></i>
                <span class="text-sm font-medium">Manage Users</span>
            </a>

            <div class="pt-6 mt-4 border-t border-slate-700/50">
                <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider px-3 mb-3">Other</p>
                <a href="{{ route('home') }}" target="_blank"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-white transition-all duration-200">
                    <i class="fas fa-globe w-5 text-center text-base"></i>
                    <span class="text-sm font-medium">View Website</span>
                    <i class="fas fa-external-link-alt text-xs ml-auto text-slate-500"></i>
                </a>
            </div>
        </nav>

        <!-- Logout Button -->
        <div class="px-3 py-4 border-t border-slate-700/50 bg-slate-800/20">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-300 hover:text-red-400 hover:bg-red-500/10 w-full text-left transition-all duration-200">
                    <i class="fas fa-sign-out-alt w-5 text-center text-base"></i>
                    <span class="text-sm font-medium">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 w-full">
        
        <!-- Top Bar - Responsive -->
        <header class="bg-white/80 backdrop-blur-md border-b border-gray-200/80 px-4 sm:px-6 py-4 flex items-center justify-between sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-3">
                <!-- Mobile Menu Toggle Button -->
                <button id="mobileMenuToggle" class="lg:hidden w-10 h-10 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all duration-200 active:scale-95">
                    <i class="fas fa-bars text-gray-600 text-lg"></i>
                </button>
                
                <div>
                    <h1 class="text-lg sm:text-xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h1>
                    <p class="text-xs text-gray-500 mt-0.5 hidden sm:block">{{ now()->format('l, F j, Y') }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <!-- Notification Bell (Optional) -->
                <div class="relative hidden sm:block">
                    <button class="w-9 h-9 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-all">
                        <i class="fas fa-bell text-gray-500 text-sm"></i>
                    </button>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </div>
                
                <div class="flex items-center gap-2 bg-gray-100 rounded-lg px-3 py-1.5">
                    <div class="w-7 h-7 bg-gradient-to-br from-purple-500 to-indigo-600 rounded-full flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <span class="text-sm text-gray-700 hidden sm:inline">{{ auth()->user()->name }}</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-5 animate-slide-down">
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                        <i class="fas fa-check-circle text-green-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('success') }}</span>
                        <button onclick="this.closest('.animate-slide-down').remove()" class="mr-auto text-green-500 hover:text-green-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @if(session('error'))
                <div class="mb-5 animate-slide-down">
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 px-4 py-3 rounded-xl shadow-sm flex items-center gap-3">
                        <i class="fas fa-exclamation-circle text-red-500 text-lg"></i>
                        <span class="text-sm font-medium">{{ session('error') }}</span>
                        <button onclick="this.closest('.animate-slide-down').remove()" class="mr-auto text-red-500 hover:text-red-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            @endif
            
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200 px-4 sm:px-6 py-4 text-center">
            <p class="text-xs text-gray-500">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </footer>
    </div>
</div>

@stack('scripts')

<script>
    // Mobile sidebar toggle functionality
    const mobileMenuToggle = document.getElementById('mobileMenuToggle');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    if (mobileMenuToggle && sidebar && sidebarOverlay) {
        mobileMenuToggle.addEventListener('click', function() {
            sidebar.classList.toggle('sidebar-mobile-hidden');
            sidebar.classList.toggle('sidebar-mobile-visible');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebarOverlay.classList.contains('active') ? 'hidden' : '';
        });
        
        // Close sidebar when clicking overlay
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.add('sidebar-mobile-hidden');
            sidebar.classList.remove('sidebar-mobile-visible');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
        
        // Close sidebar on window resize if open
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                sidebar.classList.remove('sidebar-mobile-hidden', 'sidebar-mobile-visible');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    }
    
    // Auto-hide flash messages after 5 seconds
    const flashMessages = document.querySelectorAll('.animate-slide-down');
    flashMessages.forEach(msg => {
        setTimeout(() => {
            msg.style.opacity = '0';
            setTimeout(() => msg.remove(), 300);
        }, 5000);
    });
    
    // Add active class to current sidebar link
    const currentPath = window.location.pathname;
    document.querySelectorAll('.sidebar-link').forEach(link => {
        const href = link.getAttribute('href');
        if (href && currentPath === href) {
            link.classList.add('active');
        }
    });
    
    // Smooth scroll to top on page load
    window.scrollTo({ top: 0, behavior: 'smooth' });
</script>

<style>
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .animate-slide-down {
        animation: slideDown 0.4s ease-out;
    }
    
    /* Mobile optimizations */
    @media (max-width: 640px) {
        .sidebar-mobile-hidden {
            transform: translateX(-100%);
        }
        
        .sidebar-mobile-visible {
            transform: translateX(0);
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        }
        
        .sidebar-overlay.active {
            opacity: 1;
            visibility: visible;
        }
    }
    
    /* Tablet optimizations */
    @media (min-width: 641px) and (max-width: 1023px) {
        .sidebar-mobile-hidden {
            transform: translateX(-100%);
        }
        
        .sidebar-mobile-visible {
            transform: translateX(0);
        }
    }
    
    /* Active link styling */
    .sidebar-link.active i {
        color: #a78bfa;
    }
    
    /* Hover effects on cards */
    .hover-lift {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    
    /* Loading spinner animation */
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    
    .loading-spinner {
        animation: spin 0.8s linear infinite;
    }
</style>
</body>
</html>