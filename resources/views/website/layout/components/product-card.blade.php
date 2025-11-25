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

    $emptyColorsAndSizes = empty($colors) && empty($sizes);
    $onlyColors = !empty($colors) && empty($sizes);
    $onlySizes = empty($colors) && !empty($sizes);
@endphp

<div class="group relative flex flex-col h-full overflow-hidden transition-all duration-500 hover:shadow-2xl"
     style="border-radius: 16px; background: var(--md-surface-bright); box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
    
    <!-- Image Container with Overlay -->
    <div class="relative overflow-hidden bg-linear-to-br from-slate-100 to-slate-50 h-72">
        <!-- Image -->
        <img src="{{ $product['image'] ?? 'https://placehold.co/400x400/f8f9fa/cccccc?text=Product' }}" 
             alt="{{ $product['name']['ar'] ?? $product['name'] ?? 'Product' }}" 
             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
             style="cursor: pointer;">
        
        <!-- Gradient Overlay (appears on hover) -->
        <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

        <!-- Badge Container -->
        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2">
            @if($product['isPackage'] ?? false)
                <div class="px-3 py-2 rounded-full text-xs font-bold flex items-center gap-1.5 backdrop-blur-md transition-all duration-300"
                     style="background: rgba(200, 212, 0, 0.95); color: #1F2612;">
                    <span class="material-icons-outlined" style="font-size: 18px;">card_giftcard</span>
                    <span data-ar="باقة" data-en="Package">باقة</span>
                </div>
            @endif
            
            <!-- Wishlist Button -->
            <button class="w-10 h-10 rounded-full flex items-center justify-center backdrop-blur-md transition-all duration-300 hover:scale-110 active:scale-95"
                    style="background: rgba(255, 255, 255, 0.9);"
                    title="Add to Wishlist">
                <span class="material-icons-outlined text-red-500" style="font-size: 22px;">favorite_border</span>
            </button>
        </div>
    </div>

    <!-- Content Section -->
    <div class="flex flex-col flex-1 p-5 gap-4">
        
        <!-- Product Name & City Info -->
        <div class="flex flex-col gap-2">
            <h3 class="md-title-medium font-bold line-clamp-2 transition-colors duration-300 group-hover:text-primary"
                style="color: var(--md-on-surface);">
                {{ $product['name']['ar'] ?? $product['name'] ?? '' }}
            </h3>
            
            <!-- City Name as Small Chip -->
            @if(isset($product['cityName']))
                <div class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold w-fit"
                     style="background: var(--md-primary-container); color: var(--md-on-primary-container);">
                    <span class="material-icons-outlined" style="font-size: 14px;">location_on</span>
                    <span>{{ $product['cityName']['ar'] ?? $product['cityName'] ?? '' }}</span>
                </div>
            @endif
        </div>

        <!-- Divider -->
        <div style="height: 1px; background: var(--md-outline-variant);"></div>

        <!-- Variants Section -->
        @if(!empty($colors) || !empty($sizes))
            <div class="flex flex-col gap-3">
                {{-- Colors --}}
                @if(!empty($colors))
                    <div class="flex flex-col gap-2">
                        <p class="md-label-small font-medium"
                           style="color: var(--md-on-surface);">
                            <span data-ar="الألوان" data-en="Colors">الألوان</span>
                        </p>
                        <div class="flex gap-2.5 flex-wrap items-center">
                            @foreach($colors as $index => $color)
                                <button class="color-btn rounded-full transition-all duration-300 shrink-0 hover:scale-125 active:scale-95 group/color"
                                        style="width: 32px; height: 32px; background: {{ $color }}; border: 2px solid {{ $index === 0 ? 'var(--md-primary)' : 'var(--md-outline)' }}; box-shadow: {{ $index === 0 ? '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)' : '0 1px 3px rgba(0,0,0,0.1)' }};"
                                        onclick="selectColor({{ $product['id'] ?? 0 }}, '{{ $color }}', this)"
                                        title="{{ $color }}"
                                        data-color="{{ $color }}"
                                        aria-label="Color: {{ $color }}">
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Sizes --}}
                @if(!empty($sizes))
                    <div class="flex flex-col gap-2">
                        <p class="md-label-small font-medium"
                           style="color: var(--md-on-surface);">
                            <span data-ar="الأحجام" data-en="Sizes">الأحجام</span>
                        </p>
                        <div class="flex flex-wrap gap-2"
                             id="sizes-{{ $product['id'] ?? 0 }}">
                            @foreach($sizes as $index => $size)
                                <button class="size-btn md-label-small px-3 py-1.5 rounded-lg border-2 font-medium transition-all duration-300 hover:scale-105 active:scale-95"
                                        style="border-color: {{ $index === 0 ? 'var(--md-primary)' : 'var(--md-outline)' }}; background: {{ $index === 0 ? 'var(--md-primary-container)' : 'transparent' }}; color: {{ $index === 0 ? 'var(--md-on-primary-container)' : 'var(--md-on-surface)' }}; font-size: 0.875rem;"
                                        onclick="selectSize({{ $product['id'] ?? 0 }}, '{{ $size }}', this)"
                                        aria-label="Size: {{ $size }}">
                                    {{ $size }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <!-- Divider -->
        <div style="height: 1px; background: var(--md-outline-variant);"></div>

        <!-- Price & Action -->
        <div class="flex items-center justify-between gap-3 mt-auto">
            <!-- Price Badge -->
            <div class="flex items-baseline gap-1">
                <span class="md-headline-small font-bold transition-colors duration-300"
                      id="price-{{ $product['id'] ?? 0 }}"
                      style="color: var(--md-primary);">
                    ${{ number_format($product['price'] ?? 0, 2) }}
                </span>
                @if(($product['original_price'] ?? 0) > ($product['price'] ?? 0))
                    <span class="md-label-small line-through"
                          style="color: var(--md-on-surface-variant);">
                        ${{ number_format($product['original_price'] ?? 0, 2) }}
                    </span>
                @endif
            </div>

            <!-- Quick Add Button -->
            <button onclick="addToCart({{ $cityId }}, {{ $product['id'] ?? 0 }})"
                    class="rounded-full w-12 h-12 flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 shadow-md hover:shadow-lg"
                    style="background: var(--md-primary); color: var(--md-on-primary);"
                    title="Add to Cart"
                    aria-label="Add to Cart">
                <span class="material-icons-outlined" style="font-size: 24px;">add_shopping_cart</span>
            </button>
        </div>

        <!-- Full Width CTA Button (Optional - visible on hover) -->
        <button onclick="addToCart({{ $cityId }}, {{ $product['id'] ?? 0 }})"
                class="btn-md btn-filled w-full font-semibold transition-all duration-300 gap-2 opacity-0 group-hover:opacity-100 group-hover:translate-y-0 translate-y-2 pointer-events-none group-hover:pointer-events-auto"
                style="background: var(--md-primary); color: var(--md-on-primary);">
            <span class="material-icons-outlined">shopping_cart</span>
            <span data-ar="أضف للسلة" data-en="Add to Cart">أضف للسلة</span>
        </button>
    </div>
</div>
