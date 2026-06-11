@extends('layouts.app')

@section('title', 'تسجيل الدخول')

@section('content')
<div class="max-w-md mx-auto">
    <div class="bg-white rounded-3xl shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-6">تسجيل الدخول</h1>

        <form method="POST" action="{{ url('/login') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" required
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                @error('email')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">كلمة المرور</label>
                <input type="password" name="password" required
                    class="w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-green-500 focus:ring-2 focus:ring-green-100" />
                @error('password')<p class="text-sm text-red-500 mt-2">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                class="w-full rounded-2xl bg-green-600 text-white px-4 py-3 font-semibold hover:bg-green-700 transition">
                دخول
            </button>
        </form>

        <p class="mt-6 text-center text-gray-600">
            ليس لديك حساب؟
            <a href="{{ route('register') }}" class="text-green-600 font-semibold hover:text-green-700">سجّل الآن</a>
        </p>
    </div>
</div>
@endsection
