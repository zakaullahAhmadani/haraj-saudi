@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Overview')

@section('content')
<div class="space-y-6">

    {{-- Welcome Banner --}}
    <div class="bg-gradient-to-r from-purple-600 via-purple-700 to-indigo-700 rounded-2xl p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold">Welcome back, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-purple-200 mt-1">Here's what's happening with your marketplace today.</p>
            </div>
            <div class="hidden md:block text-right">
                <p class="text-purple-200 text-sm">{{ now()->format('l') }}</p>
                <p class="text-white font-bold text-lg">{{ now()->format('M d, Y') }}</p>
            </div>
        </div>
    </div>

    {{-- Primary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Posts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalPosts) }}</p>
                    <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i> {{ $postsThisMonth }} this month
                    </p>
                </div>
                <div class="w-14 h-14 bg-blue-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-newspaper text-blue-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Total Users</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalUsers) }}</p>
                    <p class="text-xs text-green-600 mt-1 flex items-center gap-1">
                        <i class="fas fa-arrow-up"></i> {{ $usersThisMonth }} this month
                    </p>
                </div>
                <div class="w-14 h-14 bg-green-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-users text-green-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Active Posts</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($activePosts) }}</p>
                    <p class="text-xs text-orange-500 mt-1 flex items-center gap-1">
                        <i class="fas fa-pause-circle"></i> {{ $pendingPosts }} inactive
                    </p>
                </div>
                <div class="w-14 h-14 bg-orange-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-check-circle text-orange-600 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="stat-card bg-white rounded-2xl p-5 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-500 font-medium">Categories</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ number_format($totalCategories) }}</p>
                    <p class="text-xs text-purple-600 mt-1 flex items-center gap-1">
                        <i class="fas fa-tags"></i> Active categories
                    </p>
                </div>
                <div class="w-14 h-14 bg-purple-100 rounded-2xl flex items-center justify-center">
                    <i class="fas fa-tags text-purple-600 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Secondary Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div class="bg-gradient-to-br from-green-50 to-emerald-50 rounded-2xl p-5 border border-green-100">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-calendar-day text-green-600"></i>
                <p class="text-sm font-semibold text-gray-700">Posts This Week</p>
            </div>
            <p class="text-4xl font-bold text-green-700">{{ number_format($postsThisWeek) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ now()->startOfWeek()->format('M d') }} – {{ now()->endOfWeek()->format('M d') }}</p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-5 border border-blue-100">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-calendar-alt text-blue-600"></i>
                <p class="text-sm font-semibold text-gray-700">Posts This Month</p>
            </div>
            <p class="text-4xl font-bold text-blue-700">{{ number_format($postsThisMonth) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ now()->format('F Y') }}</p>
        </div>
        <div class="bg-gradient-to-br from-purple-50 to-pink-50 rounded-2xl p-5 border border-purple-100">
            <div class="flex items-center gap-3 mb-2">
                <i class="fas fa-calendar text-purple-600"></i>
                <p class="text-sm font-semibold text-gray-700">Posts This Year</p>
            </div>
            <p class="text-4xl font-bold text-purple-700">{{ number_format($postsThisYear) }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ now()->year }}</p>
        </div>
    </div>

    {{-- Pinned Posts Quick View --}}
    <div class="bg-white rounded-2xl shadow-sm border border-amber-100 overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 bg-gradient-to-r from-amber-50 to-yellow-50 border-b border-amber-100">
            <h3 class="font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-crown text-amber-500"></i> Pinned / Featured Posts
                <span class="text-xs bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full ml-1">{{ $pinnedPosts->count() }}/5 slots</span>
            </h3>
            <a href="{{ route('admin.featured') }}"
                class="text-sm bg-amber-500 text-white px-4 py-1.5 rounded-xl hover:bg-amber-600 transition font-medium">
                <i class="fas fa-cog mr-1"></i> Manage
            </a>
        </div>
        @if($pinnedPosts->count())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 divide-x divide-gray-50">
                @foreach($pinnedPosts as $p)
                    @php $h = max(0, now()->diffInHours($p->featured_until, false)); @endphp
                    <div class="p-4 hover:bg-amber-50/30 transition">
                        <div class="flex items-center gap-1.5 mb-2">
                            <span class="bg-amber-500 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                                #{{ $p->featured_position }}
                            </span>
                            <span class="text-xs {{ $h < 3 ? 'text-red-500' : 'text-amber-600' }}">
                                {{ $h }}h left
                            </span>
                        </div>
                        <p class="text-sm font-semibold text-gray-800 line-clamp-2">{{ $p->title }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fas fa-eye mr-1"></i>{{ number_format($p->views) }} views
                        </p>
                    </div>
                @endforeach
                @for($empty = $pinnedPosts->count() + 1; $empty <= 5; $empty++)
                    <div class="p-4 flex items-center justify-center text-gray-300">
                        <div class="text-center">
                            <i class="fas fa-plus-circle text-2xl mb-1"></i>
                            <p class="text-xs">Slot #{{ $empty }}</p>
                        </div>
                    </div>
                @endfor
            </div>
        @else
            <div class="p-8 text-center text-gray-400">
                <i class="fas fa-crown text-4xl mb-3 block text-amber-200"></i>
                <p class="font-medium">No posts pinned yet</p>
                <a href="{{ route('admin.featured') }}"
                    class="inline-block mt-3 text-sm bg-amber-500 text-white px-4 py-2 rounded-xl hover:bg-amber-600 transition">
                    Pin Your First Post
                </a>
            </div>
        @endif
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-line text-purple-500"></i> Posts & Users (Last 12 Months)
            </h3>
            <canvas id="monthlyChart" height="220"></canvas>
        </div>
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-chart-bar text-blue-500"></i> Weekly Posts (Current Month)
            </h3>
            <canvas id="weeklyChart" height="220"></canvas>
        </div>
    </div>

    {{-- Category Distribution & Top Users --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-tags text-orange-500"></i> Top Categories
            </h3>
            @forelse($categoryDistribution as $category)
                <div class="mb-4">
                    <div class="flex justify-between text-sm mb-1.5">
                        <span class="font-medium text-gray-700">{{ $category->name }}</span>
                        <span class="text-gray-500 font-semibold">{{ $category->ads_count }} posts</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5">
                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 h-2.5 rounded-full transition-all duration-500"
                            style="width: {{ ($category->ads_count / max($categoryDistribution->first()->ads_count ?? 1, 1)) * 100 }}%">
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-400 text-sm">No data yet.</p>
            @endforelse
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-base font-bold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fas fa-trophy text-yellow-500"></i> Top Contributors
            </h3>
            <div class="space-y-3">
                @forelse($topUsers as $index => $user)
                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm
                                {{ $index === 0 ? 'bg-yellow-100 text-yellow-700' : ($index === 1 ? 'bg-gray-200 text-gray-700' : 'bg-orange-100 text-orange-700') }}">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800 text-sm">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-purple-600">{{ $user->ads_count }}</p>
                            <p class="text-xs text-gray-400">posts</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 text-sm">No contributors yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Recent Posts & Users --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-clock text-blue-500"></i> Recent Posts
                </h3>
                <a href="{{ route('admin.posts') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
            </div>
            <div class="space-y-2">
                @foreach($recentPosts as $post)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition group">
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-800 truncate text-sm">{{ $post->title }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">
                                {{ $post->user->name ?? 'Unknown' }} • {{ $post->created_at->diffForHumans() }}
                            </p>
                        </div>
                        <div class="flex items-center gap-2 ml-3 flex-shrink-0">
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $post->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $post->is_active ? 'Active' : 'Inactive' }}
                            </span>
                            <a href="{{ route('admin.posts.toggle', $post->id) }}"
                                class="text-gray-400 hover:text-blue-600 transition" title="Toggle Status">
                                <i class="fas fa-toggle-on text-sm"></i>
                            </a>
                            <button onclick="deletePost({{ $post->id }})"
                                class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-base font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-user-plus text-green-500"></i> Recent Users
                </h3>
                <a href="{{ route('admin.users') }}" class="text-sm text-purple-600 hover:text-purple-700 font-medium">View All →</a>
            </div>
            <div class="space-y-2">
                @foreach($recentUsers as $user)
                    <div class="flex items-center justify-between p-3 hover:bg-gray-50 rounded-xl transition">
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 bg-purple-100 rounded-full flex items-center justify-center font-bold text-purple-600 text-sm flex-shrink-0">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-800 text-sm truncate">{{ $user->name }}</p>
                                <p class="text-xs text-gray-400 truncate">{{ $user->email }} • {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 ml-2 flex-shrink-0">
                            <span class="px-2 py-0.5 text-xs rounded-full font-medium {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $user->is_active ? 'Active' : 'Blocked' }}
                            </span>
                            @if(auth()->id() !== $user->id)
                                <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                    class="text-gray-400 hover:text-red-600 transition" title="Delete">
                                    <i class="fas fa-trash text-sm"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Monthly Chart
    const monthlyCtx = document.getElementById('monthlyChart').getContext('2d');
    new Chart(monthlyCtx, {
        type: 'line',
        data: {
            labels: {!! json_encode(array_column($monthlyData, 'month')) !!},
            datasets: [
                {
                    label: 'Posts',
                    data: {!! json_encode(array_column($monthlyData, 'posts')) !!},
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.08)',
                    tension: 0.4, fill: true, pointRadius: 4, pointHoverRadius: 6
                },
                {
                    label: 'Users',
                    data: {!! json_encode(array_column($monthlyData, 'users')) !!},
                    borderColor: 'rgb(168, 85, 247)',
                    backgroundColor: 'rgba(168, 85, 247, 0.08)',
                    tension: 0.4, fill: true, pointRadius: 4, pointHoverRadius: 6
                }
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } } }
        }
    });

    // Weekly Chart
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: {!! json_encode(array_column($weeklyData, 'week')) !!},
            datasets: [{
                label: 'Posts',
                data: {!! json_encode(array_column($weeklyData, 'posts')) !!},
                backgroundColor: 'rgba(99, 102, 241, 0.7)',
                borderColor: 'rgb(99, 102, 241)',
                borderWidth: 1, borderRadius: 8
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } },
            scales: { y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.05)' } } }
        }
    });

    function deletePost(id) {
        if (confirm('Are you sure you want to delete this post? This cannot be undone.')) {
            window.location.href = '/admin/posts/' + id + '/delete';
        }
    }
    function deleteUser(id, name) {
        if (confirm('Delete user "' + name + '"? All their posts will also be deleted.')) {
            window.location.href = '/admin/users/' + id + '/delete';
        }
    }
</script>
@endpush
