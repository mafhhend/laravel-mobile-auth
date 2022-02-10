@extends("laravel-mobile-auth::layouts.master")
@section('title', 'داشبورد کاربر')

@section('content')
<x-laravel-mobile-auth::logo />
<section class="p-8 mt-4 bg-white rounded-lg shadow-sm">
    {{-- Cart Header --}}

    <div>
        <h1 class="text-xl font-bold text-center text-sky-500">داشبورد کاربر</h1>
        <p class="mt-2 text-sm text-gray-500">
            به حساب کاربریتان خوش آمدید!
        </p>
    </div>

    {{-- Cart Body --}}
    <div class="mt-4">
        @if (session()->has("welcome_message"))
        <div class="px-4 py-2 mb-2 text-green-500 rounded bg-green-50">
            ورود شما موفقیت آمیز بود‌!
        </div>
        @endif
        <div class="flex items-center">
            <h1 class="font-medium font-bold text-gray-700">شماره موبایل شما:</h1>
            <p class="mr-2 text-gray-500"> {{ auth()->user()->phone }} </p>
        </div>
        <div class="flex items-center justify-end">
            <a href="{{ route("laravel_mobile_auth.logout") }}" class="w-2/6 px-2 py-2 text-sm text-center text-white rounded bg-rose-500">خروج از حساب</a>
        </div>
    </div>
</section>
@endsection
