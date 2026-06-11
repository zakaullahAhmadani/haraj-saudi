@extends('layouts.app')

@section('title', 'نشر إعلان جديد')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-2xl shadow-md p-6">
        <h1 class="text-2xl font-bold mb-6 text-gray-900">نشر إعلان جديد</h1>

        <form action="{{ route('ads.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="space-y-5">
                <div>
                    <label class="block text-gray-700 font-semibold mb-2">عنوان الإعلان *</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500"
                        required>
                    @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">التصنيف *</label>
                    <select name="category_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500"
                        required>
                        <option value="">اختر التصنيف</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">الوصف *</label>
                    <textarea name="description" rows="6"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500"
                        required>{{ old('description') }}</textarea>
                    <p class="text-sm text-gray-500 mt-1">الحد الأدنى 50 حرف</p>
                    @error('description')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">المدينة *</label>
                    <select name="location" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500">
                        <option value="">اختر المدينة</option>
                        @foreach($locations as $city)
                            <option value="{{ $city }}" {{ old('location') == $city ? 'selected' : '' }}>{{ $city }}</option>
                        @endforeach
                    </select>
                    @error('location')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">رقم الهاتف *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500"
                            required>
                        @error('phone')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">رقم واتساب</label>
                        <input type="tel" name="whatsapp" value="{{ old('whatsapp') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500">
                        @error('whatsapp')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">السعر (ر.س)</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500 font-semibold">ر.س</span>
                        <input type="number" name="price" value="{{ old('price') }}" placeholder="0.00" step="0.01"
                            class="w-full pl-12 pr-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500">
                    </div>
                    @error('price')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">الكلمات المفتاحية (مفصولة بفاصلة)</label>
                    <input type="text" name="keywords" value="{{ old('keywords') }}"
                        placeholder="كلمة1, كلمة2, كلمة3"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500">
                    @error('keywords')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">الصور * (من 1 إلى 4 صور)</label>
                    <input type="file" name="images[]" multiple accept="image/*"
                        class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:outline-none focus:border-purple-500"
                        required>
                    <p class="text-sm text-gray-500 mt-1">يمكنك رفع حتى 4 صور. الحد الأقصى 2 ميجابايت لكل صورة</p>
                    <div id="image-preview" class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-3"></div>
                    @error('images')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                    @error('images.*')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
                </div>

                <div class="flex gap-4 pt-2">
                    <button type="submit"
                        class="flex-1 bg-purple-600 text-white px-6 py-3 rounded-xl hover:bg-purple-700 transition font-semibold">
                        نشر الإعلان
                    </button>
                    <a href="{{ route('home') }}"
                        class="flex-1 bg-gray-200 text-gray-700 px-6 py-3 rounded-xl hover:bg-gray-300 transition text-center font-semibold">
                        إلغاء
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.querySelector('input[name="images[]"]').addEventListener('change', function(e) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';
        const files = Array.from(e.target.files);
        if (files.length > 4) {
            alert('يمكنك رفع 4 صور كحد أقصى');
            this.value = '';
            return;
        }
        files.forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                const previewDiv = document.createElement('div');
                previewDiv.className = 'relative';
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-full h-24 object-cover rounded-xl border border-gray-200';
                    previewDiv.appendChild(img);
                };
                reader.readAsDataURL(file);
                previewContainer.appendChild(previewDiv);
            }
        });
    });
</script>
@endpush
@endsection
