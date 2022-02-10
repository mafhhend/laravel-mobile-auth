@extends("laravel-mobile-auth::layouts.master")
@section('title', 'ورود با حساب کاربری')

@section('content')
<x-laravel-mobile-auth::logo />
<section class="p-8 mt-4 bg-white rounded-lg shadow-sm">
    {{-- Cart Header --}}

    <div>
        <h1 class="text-xl font-bold text-center text-sky-500">ورود به حساب کاربری</h1>
        <p class="mt-2 text-sm text-gray-500">
            برای ورود به حساب کاربری شماره حساب خود را وارد نمایید.
        </p>
    </div>

    {{-- Cart Body --}}
    <div class="mt-4">


        @if (session()->has("is_logged_out"))
        <div class="px-4 py-2 mb-2 text-sm rounded bg-rose-50 text-rose-500">
            خروج شما از حساب کاربری موفقیت آمیز بود!
        </div>
        @endif


        <form action="{{ route('laravel_mobile_auth.auth') }}" method="post" class="flex flex-col">
            @csrf
            <label for="phone">
                <span class="text-sm text-gray-700">شماره موبایل</span>
                <span class="text-rose-500">*</span>
            </label>
            <input class="px-2 py-2 text-sm border rounded bg-slate-50 border-slate-100" placeholder="09034099325"
                dir="ltr" type="tel" name="phone" id="phone" value="{{ old(" phone") }}">
            @error('phone')
            <span class="mt-2 text-sm text-rose-500">{{ $message }}</span>
            @enderror
            <div class="flex items-end justify-end">
                <button class="w-3/6 px-2 py-2 mt-4 text-sm text-white bg-blue-500 rounded " type="submit">تایید
                    شماره موبایل</button>
            </div>
        </form>
    </div>
</section>
@endsection
