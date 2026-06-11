@extends('layouts.admin')

@section('title', 'Manage Users')
@section('page-title', 'Manage Users')

@section('content')
<div class="space-y-5">

    <div class="flex items-center justify-between">
        <p class="text-sm text-gray-500">Total: <strong>{{ $users->total() }}</strong> users</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-100">
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Phone</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Posts</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Role</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Joined</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 text-gray-500 font-mono text-xs">#{{ $user->id }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0
                                        {{ $user->is_admin ? 'bg-purple-600 text-white' : 'bg-gray-100 text-gray-600' }}">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                                        @if($user->location)
                                            <p class="text-xs text-gray-400"><i class="fas fa-map-marker-alt mr-1"></i>{{ $user->location }}</p>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->phone ?? '—' }}</td>
                            <td class="px-4 py-3">
                                <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2.5 py-1 rounded-full">
                                    {{ $user->ads_count }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($user->is_admin)
                                    <span class="bg-purple-100 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                        <i class="fas fa-shield-alt mr-1"></i>Admin
                                    </span>
                                @else
                                    <span class="bg-gray-100 text-gray-600 text-xs px-2.5 py-1 rounded-full">User</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('admin.users.toggle', $user->id) }}"
                                    class="inline-flex items-center gap-1 px-2.5 py-1 text-xs rounded-full font-medium transition hover:opacity-80
                                    {{ $user->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                    <i class="fas fa-circle text-[8px]"></i>
                                    {{ $user->is_active ? 'Active' : 'Blocked' }}
                                </a>
                            </td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $user->created_at->format('M d, Y') }}</td>
                            <td class="px-4 py-3">
                                @if(auth()->id() !== $user->id)
                                    <button onclick="deleteUser({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                        class="w-8 h-8 flex items-center justify-center bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition" title="Delete">
                                        <i class="fas fa-trash text-xs"></i>
                                    </button>
                                @else
                                    <span class="text-xs text-gray-400 italic">You</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-12 text-center text-gray-400">
                                <i class="fas fa-users text-4xl mb-3 block"></i>
                                No users found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-100">
            {{ $users->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function deleteUser(id, name) {
        if (confirm('Delete user "' + name + '"? All their posts will also be deleted.')) {
            window.location.href = '/admin/users/' + id + '/delete';
        }
    }
</script>
@endpush
