@extends('website.layout.main')

@section('title', 'تم استلام طلبك')

@section('content')
<section class="container mx-auto px-4 py-16">
    <div class="checkout-section text-center">
        <div class="text-6xl mb-4">✅</div>
        <h1 class="text-3xl font-extrabold mb-2" style="color: var(--primary-black);">شكراً لك!</h1>
        <p class="mb-6" style="color: var(--gray-text);">تم استلام طلبك بنجاح، وسنقوم بمعالجته قريباً.</p>
        <div id="orderIdBox" class="mb-6" style="color: var(--primary-black);"></div>
        <a href="{{ url('/') }}" class="btn-yellow inline-block px-6 py-3 text-lg">العودة إلى الصفحة الرئيسية</a>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const params = new URLSearchParams(window.location.search);
        const oid = params.get('orderId');
        if (oid) {
            document.getElementById('orderIdBox').innerHTML = `<strong>رقم الطلب:</strong> ${oid}`;
        }
    });
@endsection
*** End Patch***  QPush ক json```} }-->
*** End Patch

