@extends('layouts.admin')

@section('title', 'Manage Posts')
@section('page-title', 'Manage Posts')

@section('content')
<div class="space-y-5">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <p class="text-sm text-gray-500">Total: <strong>{{ $posts->total() }}</strong> posts</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.posts.create') }}"
                class="px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition text-sm font-medium">
                <i class="fas fa-plus mr-1"></i> Create Post
            </a>
            <button onclick="selectAll()"
                class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition text-sm font-medium">
                <i class="fas fa-check-square mr-1"></i> Select All
            </button>
            <button onclick="bulkDelete()"
                class="px-4 py-2 bg-red-600 text-white rounded-xl hover:bg-red-700 transition text-sm font-medium">
                <i class="fas fa-trash mr-1"></i> Bulk Delete
            </button>
        </div>
    </div>

    {{-- Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left w-10">
                            <input type="checkbox" id="selectAllCheckbox" class="rounded">
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Title</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Category</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Location</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Price</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Views</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($posts as $post)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3">
                                <input type="checkbox" class="post-checkbox rounded" value="{{ $post->id }}">
                            </td>
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">#{{ $post->id }}</td>
                            <td class="px-4 py-3 max-w-xs">
                                <p class="font-semibold text-gray-800 truncate">{{ $post->title }}</p>
                                @if($post->images->count())
                                    <span class="text-xs text-gray-400"><i class="fas fa-images mr-1"></i>{{ $post->images->count() }} images</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 bg-purple-100 rounded-full flex items-center justify-center text-xs font-bold text-purple-600">
                                        {{ strtoupper(substr($post->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <span class="text-gray-700">{{ $post->user->name ?? 'Unknown' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="bg-purple-100 text-purple-700 text-xs px-2 py-0.5 rounded-full">
                                    {{ $post->category->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <i class="fas fa-map-marker-alt text-red-400 mr-1"></i>{{ $post->location }}
                            </td>
                            <td class="px-4 py-3 font-semibold text-gray-700">
                                {{ $post->price ? number_format($post->price) . ' SAR' : '—' }}
                            </td>
                            <td class="px-4 py-3 text-gray-600">
                                <i class="fas fa-eye text-gray-400 mr-1"></i>{{ number_format($post->views) }}
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.posts.toggle', $post->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full font-medium transition hover:opacity-80
                                    {{ $post->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-circle text-[8px]"></i>
                                    {{ $post->is_active ? 'Active' : 'Inactive' }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $post->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-2">
                                    <a href="{{ route('ads.show', $post->slug) }}" target="_blank"
                                        class="w-8 h-8 flex items-center justify-center bg-blue-100 text-blue-600 rounded-lg hover:bg-blue-200 transition" title="View">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.featured') }}"
                                        class="w-8 h-8 flex items-center justify-center bg-amber-100 text-amber-600 rounded-lg hover:bg-amber-200 transition" title="Pin to Top">
                                        <i class="fas fa-crown text-xs"></i>
                                    </a>
                                    <button onclick="deletePost({{ $post->id }})"
                                        class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Delete">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="11" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-newspaper text-4xl mb-3 block"></i>
                                No posts found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $posts->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deletePost(id) {
        if (confirm('Are you sure you want to delete this post?')) {
            window.location.href = '/admin/posts/' + id + '/delete';
        }
    }

    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const postCheckboxes = document.querySelectorAll('.post-checkbox');

    selectAllCheckbox.addEventListener('change', function() {
        postCheckboxes.forEach(cb => cb.checked = this.checked);
    });

    function selectAll() {
        postCheckboxes.forEach(cb => cb.checked = true);
        selectAllCheckbox.checked = true;
    }

    function bulkDelete() {
        const selectedIds = Array.from(postCheckboxes).filter(cb => cb.checked).map(cb => cb.value);
        if (selectedIds.length === 0) { alert('Please select posts to delete'); return; }
        if (confirm(`Delete ${selectedIds.length} selected post(s)? This cannot be undone.`)) {
            fetch('{{ route("admin.posts.bulk-delete") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ ids: selectedIds })
            }).then(r => r.json()).then(data => {
                if (data.success) location.reload();
                else alert(data.message);
            });
        }
    }
</script>
@endpush
