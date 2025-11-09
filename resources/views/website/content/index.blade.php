@extends('website.layout.main')

@section('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')

@section('content')
    <!-- Hero Section -->
    @include('website.layout.components.hero-section')

    <!-- Cities & Products Section -->
    <section id="cities" class="container mx-auto px-4 py-12">
        <div id="citiesContainer">
            <!-- Cities will be loaded here dynamically -->
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    @include('website.layout.components.cart-modal')
@endsection

