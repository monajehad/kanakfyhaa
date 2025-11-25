@extends('website.layout.main')

@section('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')

@section('content')
    <!-- Sliders Section -->
    @include('website.layout.components.sliders')

    @include('website.layout.components.categories')
    <!-- Cities & Products Section -->
    <section id="cities" class="container mx-auto px-4 py-12">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold mb-3" data-ar="تصفح المدن" data-en="Browse Cities">تصفح المدن</h2>
            <p class="text-base" style="color: var(--gray-text)">
                <span data-ar="اختر المدينة لرؤية المنتجات" data-en="Select a city to view products">اختر المدينة لرؤية
                    المنتجات</span>
            </p>
        </div>
        <div id="citiesContainer">
            <!-- Cities and products will be loaded dynamically via JavaScript -->
            <div class="text-center py-12" style="color: var(--gray-text)">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2"
                    style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                <p class="mt-2">
                    <span data-ar="جاري التحميل..." data-en="Loading...">جاري التحميل...</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    @include('website.layout.components.cart-modal')
@endsection
