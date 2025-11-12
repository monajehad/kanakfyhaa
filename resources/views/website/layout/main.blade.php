<!DOCTYPE html>
<html lang="ar" dir="rtl">
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

