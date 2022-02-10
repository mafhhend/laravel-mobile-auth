@extends("laravel-mobile-auth::layouts.master")
@section('title', 'ورود با رمز یکبار مصرف')

@section('content')

    <x-laravel-mobile-auth::logo />
    <section class="p-8 mt-4 bg-white rounded-lg shadow-sm">
        {{-- Cart Header --}}

        <div>
            <h1 class="text-xl font-bold text-center text-sky-500">ورود با رمز یکبار مصرف</h1>
            <p class="mt-2 text-sm text-gray-500">
                کد تایید ارسال شده به شماره زیر را وارد نمایید.
            </p>
        </div>

        {{-- Cart Body --}}
        <div class="mt-4">
            <form action="{{ route('laravel_mobile_auth.otp.check') }}" method="post" class="flex flex-col">
                @csrf
                <label for="otp" class="flex items-center justify-between">
                    <div>
                        <span class="text-sm text-gray-700">شماره موبایل</span>
                        <span class="text-rose-500">*</span>
                    </div>
                    <div class="flex item-center group">
                        <a href="{{ route('laravel_mobile_auth.login') }}"
                            class="text-rose-500 group-hover:text-rose-600">ویرایش شماره</a>
                        <span class="h-4 mr-2 text-rose-500 group-hover:stroke-rose-600"> &LeftArrowBar; </span>
                    </div>

                </label>

                <input class="px-2 py-2 mt-4 text-sm text-gray-700 border rounded bg-slate-50 border-slate-100"
                    value="{{ session()->get('phone', old('phone')) }}" placeholder="09123456789" readonly dir="ltr"
                    type="tel" name="phone" id="phone">
                @error('phone')
                    <span class="mt-2 text-sm text-rose-500">{{ $message }}</span>
                @enderror
                <label for="otp">
                    <span class="text-sm text-gray-700">رمز یکبار مصرف</span>
                    <span class="text-rose-500">*</span>
                </label>
                <input class="px-2 py-2 text-sm text-gray-700 border rounded bg-slate-50 border-slate-100" dir="ltr"
                    type="otp" placeholder="********" name="otp" id="otp">
                @error('otp')
                    <span class="mt-2 text-sm text-rose-500">{{ $message }}</span>
                @enderror

                <button class="w-3/6 px-2 py-2 text-sm text-white bg-blue-500 rounded" type="submit">ورود به
                    حساب من</button>
        </div>
        </form>
        </div>
    </section>

@endsection
