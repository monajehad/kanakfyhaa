@extends('website.layout.main')

@section('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')

@section('content')
    <!-- Hero Section -->
    @include('website.layout.components.hero-section')

    <!-- Cities & Products Section -->
    <section id="cities" class="container mx-auto px-4 py-12">
        <div id="citiesContainer">
            @if(isset($cities) && count($cities) > 0)
                @foreach($cities as $city)
                    @include('website.layout.components.city-section', ['city' => $city])
                @endforeach
            @else
                <p class="text-center py-12" style="color: var(--gray-text)">
                    <span data-ar="لا توجد مدن متاحة حالياً" data-en="No cities available currently">
                        لا توجد مدن متاحة حالياً
                    </span>
                </p>
            @endif
        </div>
    </section>


    
    <!-- Shopping Cart Modal -->
    @include('website.layout.components.cart-modal')
@endsection

