@php
    $city = $city ?? [];
@endphp

<div class="city-section">
    <h2 class="city-title">{{ $city['name']['ar'] ?? $city['name'] ?? '' }}</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($city['products'] ?? [] as $product)
            @include('website.layout.components.product-card', ['product' => $product, 'cityId' => $city['id'] ?? 0])
        @endforeach
    </div>
</div>

