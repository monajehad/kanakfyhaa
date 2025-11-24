<!DOCTYPE html>
@php
    $locale = app()->getLocale();
@endphp
<html lang="{{ $locale }}" dir="{{ $locale === 'ar' ? 'rtl' : 'ltr' }}">
<head>
    @include('website.layout.sections._head')
</head>
<body>
    @include('website.layout.sections._header')

    @yield('content')

    @include('website.layout.sections._footer')

    @include('website.layout.sections._scripts')
    
    @stack('scripts')
</body>
</html>

