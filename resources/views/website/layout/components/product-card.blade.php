@php
    $product = $product ?? [];
    $cityId = $cityId ?? 0;
    
    // Ensure colors and sizes are arrays
    $colors = $product['colors'] ?? [];
    if (is_string($colors)) {
        $colors = json_decode($colors, true) ?? [];
    }
    if (!is_array($colors)) {
        $colors = [];
    }
    
    $sizes = $product['sizes'] ?? [];
    if (is_string($sizes)) {
        $sizes = json_decode($sizes, true) ?? [];
    }
    if (!is_array($sizes)) {
        $sizes = [];
    }
@endphp

<div class="product-card">
    <div class="relative">
        @if($product['isPackage'] ?? false)
            <div class="package-badge">
                <span data-ar="بكج كامل 📦" data-en="Full Package 📦">بكج كامل 📦</span>
            </div>
        @endif
        <img src="{{ $product['image'] ?? '' }}" alt="{{ $product['name']['ar'] ?? $product['name'] ?? '' }}" class="product-image">
    </div>
    <div class="product-info">
        <h3 class="product-name">{{ $product['name']['ar'] ?? $product['name'] ?? '' }}</h3>
        <p class="product-desc">{{ $product['description']['ar'] ?? $product['description'] ?? '' }}</p>
        
        <div class="flex gap-2 mb-3">
            @foreach($colors as $index => $color)
                <button class="color-btn {{ $index === 0 ? 'active' : '' }}" 
                        style="background: {{ $color }}"
                        onclick="selectColor({{ $product['id'] ?? 0 }}, '{{ $color }}', this)"
                        data-color="{{ $color }}">
                </button>
            @endforeach
        </div>
        
        <div class="flex flex-wrap gap-2 mb-3" id="sizes-{{ $product['id'] ?? 0 }}">
            @foreach($sizes as $index => $size)
                <button class="size-btn {{ $index === 0 ? 'active' : '' }}" 
                        onclick="selectSize({{ $product['id'] ?? 0 }}, '{{ $size }}', this)">
                    {{ $size }}
                </button>
            @endforeach
        </div>
        
        <div class="text-xl font-bold mb-3" id="price-{{ $product['id'] ?? 0 }}">
            ${{ number_format($product['price'] ?? 0, 2) }}
        </div>
        <button onclick="addToCart({{ $cityId }}, {{ $product['id'] ?? 0 }})" class="btn-yellow">
            <span data-ar="أضف للسلة" data-en="Add to Cart">أضف للسلة</span>
        </button>
    </div>
</div>

