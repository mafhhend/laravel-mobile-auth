<!DOCTYPE html>
<html lang="en" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset("packages/mafhhend/laravel-mobile-auth/css/tailwind.css") }}">
</head>
<body class="flex flex-col justify-center items-center bg-slate-50 h-screen">
    @yield("content")
</body>
</html>