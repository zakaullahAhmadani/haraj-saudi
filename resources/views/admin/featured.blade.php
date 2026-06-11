@extends('layouts.admin')

@section('title', 'Featured Posts')
@section('page-title', 'Featured / Pinned Posts')

@section('content')
<div class="space-y-6">

    {{-- Info Banner --}}
    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4">
        <div class="w-10 h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-crown text-white"></i>
        </div>
        <div>
            <h3 class="font-bold text-gray-800">How Featured Posts Work</h3>
            <p class="text-sm text-gray-600 mt-1">
                Pin up to <strong>5 posts</strong> to the top of the home page. Each pinned post stays for the duration you set (max 168 hours / 7 days).
                After the time expires, the slot is automatically freed and the home page reverts to showing posts by views.
            </p>
        </div>
    </div>

    {{-- Current Slots Grid --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        @for($slot = 1; $slot <= 5; $slot++)
            @php $pinned = $slots[$slot] ?? null; @endphp
            <div class="bg-white rounded-2xl border-2 {{ $pinned ? 'border-amber-400 shadow-md' : 'border-dashed border-gray-200' }} overflow-hidden">
                {{-- Slot header --}}
                <div class="px-4 py-3 {{ $pinned ? 'bg-amber-500' : 'bg-gray-50' }} flex items-center justify-between">
                    <span class="font-bold {{ $pinned ? 'text-white' : 'text-gray-400' }} flex items-center gap-1.5">
                        <i class="fas fa-crown text-sm"></i> Slot #{{ $slot }}
                    </span>
                    @if($pinned)
                        <span class="text-xs text-amber-100 bg-amber-600 px-2 py-0.5 rounded-full">Active</span>
                    @else
                        <span class="text-xs text-gray-400">Empty</span>
                    @endif
                </div>

                @if($pinned)
                    {{-- Filled slot --}}
                    <div class="p-4">
                        @if($pinned->images->first())
                            <img src="{{ asset('storage/' . $pinned->images->first()->image_path) }}"
                                alt="{{ $pinned->title }}"
                                class="w-full h-24 object-cover rounded-xl mb-3">
                        @else
                            <div class="w-full h-24 bg-gray-100 rounded-xl mb-3 flex items-center justify-center text-gray-400">
                                <i class="fas fa-image text-2xl"></i>
                            </div>
                        @endif
                        <p class="font-semibold text-gray-800 text-sm line-clamp-2">{{ $pinned->title }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            <i class="fas fa-map-marker-alt mr-1"></i>{{ $pinned->location }}
                        </p>
                        @php $hoursLeft = max(0, now()->diffInHours($pinned->featured_until, false)); @endphp
                        <div class="mt-2 flex items-center gap-1 text-xs {{ $hoursLeft < 3 ? 'text-red-600' : 'text-amber-600' }}">
                            <i class="fas fa-clock"></i>
                            @if($hoursLeft > 0)
                                {{ $hoursLeft }}h remaining
                            @else
                                Expiring soon
                            @endif
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('ads.show', $pinned->slug) }}" target="_blank"
                                class="flex-1 text-center text-xs bg-blue-100 text-blue-700 py-1.5 rounded-lg hover:bg-blue-200 transition">
                                <i class="fas fa-eye"></i> View
                            </a>
                            <a href="{{ route('admin.featured.unpin', $pinned->id) }}"
                                onclick="return confirm('Unpin this post from slot #{{ $slot }}?')"
                                class="flex-1 text-center text-xs bg-red-100 text-red-700 py-1.5 rounded-lg hover:bg-red-200 transition">
                                <i class="fas fa-times"></i> Unpin
                            </a>
                        </div>
                    </div>
                @else
                    {{-- Empty slot --}}
                    <div class="p-4 text-center py-8">
                        <i class="fas fa-plus-circle text-3xl text-gray-300 mb-2"></i>
                        <p class="text-xs text-gray-400">Empty slot</p>
                        <p class="text-xs text-gray-400">Pin a post below</p>
                    </div>
                @endif
            </div>
        @endfor
    </div>

    {{-- Pin a Post Form --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-5 flex items-center gap-2">
            <i class="fas fa-thumbtack text-amber-500"></i> Pin a Post to Top
        </h3>

        <form action="{{ route('admin.featured.pin') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                {{-- Search & Select Post --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Position (1 = top) *
                    </label>
                    <select name="position" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        @for($i = 1; $i <= 5; $i++)
                            <option value="{{ $i }}" {{ old('position') == $i ? 'selected' : '' }}>
                                Slot #{{ $i }} {{ isset($slots[$i]) ? '(occupied — will replace)' : '(empty)' }}
                            </option>
                        @endfor
                    </select>
                </div>

                <div class="md:col-span-1">
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Duration (hours) *
                    </label>
                    <select name="hours" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100">
                        <option value="24" selected>24 hours (1 day)</option>
                        <option value="48">48 hours (2 days)</option>
                        <option value="72">72 hours (3 days)</option>
                        <option value="96">96 hours (4 days)</option>
                        <option value="120">120 hours (5 days)</option>
                        <option value="168">168 hours (7 days)</option>
                        <option value="6">6 hours</option>
                        <option value="12">12 hours</option>
                        <option value="1">1 hour (test)</option>
                    </select>
                </div>

                <div class="md:col-span-1 flex items-end">
                    <button type="submit"
                        class="w-full bg-amber-500 text-white px-6 py-2.5 rounded-xl hover:bg-amber-600 transition font-semibold">
                        <i class="fas fa-thumbtack mr-2"></i> Pin Post
                    </button>
                </div>
            </div>

            {{-- Post selector with live search --}}
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Select Post *
                    <span class="text-gray-400 font-normal ml-1">(search by title)</span>
                </label>
                <input type="text" id="postSearch" placeholder="Type to search posts..."
                    class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-amber-500 focus:ring-2 focus:ring-amber-100 mb-3">

                <div id="postList" class="border border-gray-200 rounded-xl overflow-hidden max-h-72 overflow-y-auto">
                    @foreach($allPosts as $post)
                        <label class="post-item flex items-center gap-3 px-4 py-3 hover:bg-amber-50 cursor-pointer border-b border-gray-50 last:border-0 transition"
                            data-title="{{ strtolower($post->title) }}">
                            <input type="radio" name="ad_id" value="{{ $post->id }}" class="text-amber-500" required>
                            <div class="flex-1 min-w-0">
                                <p class="font-medium text-gray-800 text-sm truncate">{{ $post->title }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">
                                    <span class="bg-purple-100 text-purple-700 px-1.5 py-0.5 rounded text-[10px]">{{ $post->category->name }}</span>
                                    <span class="ml-2"><i class="fas fa-map-marker-alt"></i> {{ $post->location }}</span>
                                    <span class="ml-2"><i class="fas fa-eye"></i> {{ number_format($post->views) }}</span>
                                    @if($post->isPinnedActive())
                                        <span class="ml-2 text-amber-600 font-semibold"><i class="fas fa-crown"></i> Pinned #{{ $post->featured_position }}</span>
                                    @endif
                                </p>
                            </div>
                            @if($post->price)
                                <span class="text-xs font-bold text-purple-600 whitespace-nowrap">{{ number_format($post->price) }} SAR</span>
                            @endif
                        </label>
                    @endforeach
                </div>
                @error('ad_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </form>
    </div>

    {{-- All currently pinned table --}}
    @if($pinnedPosts->count())
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h3 class="font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-list text-amber-500"></i> Currently Pinned Posts
                </h3>
                <span class="text-xs text-gray-500">{{ $pinnedPosts->count() }} / 5 slots used</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Slot</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Post</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Category</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Views</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Expires</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Remaining</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($pinnedPosts as $post)
                            @php $hoursLeft = max(0, now()->diffInHours($post->featured_until, false)); @endphp
                            <tr class="hover:bg-amber-50/30 transition">
                                <td class="px-4 py-3">
                                    <span class="inline-flex items-center gap-1 bg-amber-500 text-white text-xs font-bold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-crown text-[10px]"></i> #{{ $post->featured_position }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <p class="font-semibold text-gray-800 text-sm">{{ Str::limit($post->title, 45) }}</p>
                                    <p class="text-xs text-gray-400">{{ $post->user->name ?? 'Unknown' }}</p>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">
                                        {{ $post->category->name }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">
                                    <i class="fas fa-eye text-gray-400 mr-1"></i>{{ number_format($post->views) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 text-xs">
                                    {{ $post->featured_until->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <span class="text-xs font-semibold {{ $hoursLeft < 3 ? 'text-red-600' : 'text-amber-600' }}">
                                        <i class="fas fa-clock mr-1"></i>
                                        @if($hoursLeft > 0) {{ $hoursLeft }}h @else Expiring @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex gap-2">
                                        <a href="{{ route('ads.show', $post->slug) }}" target="_blank"
                                            class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="View">
                                            <i class="fas fa-eye text-xs"></i>
                                        </a>
                                        <a href="{{ route('admin.featured.unpin', $post->id) }}"
                                            onclick="return confirm('Unpin this post?')"
                                            class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Unpin">
                                            <i class="fas fa-times text-xs"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    // Live search filter for post list
    document.getElementById('postSearch').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        document.querySelectorAll('.post-item').forEach(item => {
            item.style.display = item.dataset.title.includes(q) ? '' : 'none';
        });
    });
</script>
@endpush
