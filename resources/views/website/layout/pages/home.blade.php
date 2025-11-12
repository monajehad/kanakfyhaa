@extends('website.layout.main')

@section('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')

@section('content')
    <!-- Hero Section -->
    @include('website.layout.components.hero-section')

    <!-- Cities & Products Section -->
    <section id="cities" class="container mx-auto px-4 py-12">
        <div id="citiesContainer">
            <!-- Cities and products will be loaded dynamically via JavaScript -->
            <div class="text-center py-12" style="color: var(--gray-text)">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                <p class="mt-2">
                    <span data-ar="جاري التحميل..." data-en="Loading...">جاري التحميل...</span>
                </p>
            </div>
        </div>
    </section>


    
    <!-- Shopping Cart Modal -->
    @include('website.layout.components.cart-modal')
@endsection

