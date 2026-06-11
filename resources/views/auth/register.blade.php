@extends('layouts.app')

@section('title', 'إنشاء حساب')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">إنشاء حساب جديد</h1>

        <form method="POST" action="{{ url('/register') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">الاسم الكامل</label>
                <input type="text" name="name" value="{{ old('name') }}" required
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                @error('name')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                @error('email')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
                    <input type="password" name="password" required
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                    @error('password')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">تأكيد كلمة المرور</label>
                    <input type="password" name="password_confirmation" required
                        class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                </div>
            </div>

            <button type="submit"
                class="w-full rounded-2xl bg-green-600 text-white px-4 py-3 font-semibold hover:bg-green-700 transition">
                إنشاء الحساب
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            لديك حساب بالفعل؟
            <a href="{{ route('login') }}" class="text-green-600 font-semibold hover:text-green-700">تسجيل الدخول</a>
        </p>
    </div>
</div>
@endsection
