@extends('layouts.admin')

@section('title', 'Create New Post')
@section('page-title', 'Create New Post')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">

        <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-5">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Post Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                    @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Category *</label>
                        <select name="category_id" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Location *</label>
                        <select name="location" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                            <option value="">Select City</option>
                            @foreach($locations as $city)
                                <option value="{{ $city }}" {{ old('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('location')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Description *</label>
                    <textarea name="description" rows="6" required
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">{{ old('description') }}</textarea>
                    @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Phone *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" required
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                        @error('phone')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">WhatsApp</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Price (SAR)</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 text-sm font-semibold">SAR</span>
                            <input type="number" name="price" value="{{ old('price') }}" step="0.01" min="0"
                                class="w-full pl-12 pr-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1.5">Keywords (comma separated)</label>
                        <input type="text" name="keywords" value="{{ old('keywords') }}" placeholder="keyword1, keyword2"
                            class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500 focus:ring-2 focus:ring-purple-100">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">Images (optional, max 10)</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:outline-none focus:border-purple-500">
                    <p class="text-xs text-gray-400 mt-1">Max 2MB per image. JPEG or PNG.</p>
                    @error('images')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="mb-4">
    <label class="flex items-center gap-2">
        <input type="checkbox" name="is_pinned" value="1" class="w-4 h-4 text-purple-600">
        <span class="text-sm font-medium text-gray-700">📌 تثبيت الإعلان في الصفحة الرئيسية</span>
    </label>
</div>

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                        class="flex-1 bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition font-semibold">
                        <i class="fas fa-plus mr-2"></i> Create Post
                    </button>
                    <a href="{{ route('admin.posts') }}"
                        class="flex-1 bg-gray-100 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-200 transition text-center font-semibold">
                        Cancel
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
