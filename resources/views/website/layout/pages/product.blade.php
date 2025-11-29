@extends('website.layout.main')

@section('title', $product->localized_name ?? ($product->name_ar ?? $product->name))

@section('content')
    <section class="container mx-auto px-4 py-8">
        <!-- Breadcrumb Navigation (Material Design 3) -->
        <nav class="flex items-center gap-2 mb-12 text-sm" style="color: var(--md-on-surface-variant);">
            <a href="{{ route('pages-home') }}" class="flex items-center gap-1 hover:opacity-80 transition" style="color: var(--md-primary);">
                <span class="material-icons-outlined icon-sm">home</span>
                <span class="font-medium">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</span>
            </a>
            <span class="material-icons-outlined icon-sm">chevron_right</span>
            <a href="#city" class="hover:opacity-80 transition" style="color: var(--md-primary);">{{ $product->city?->localized_name }}</a>
            <span class="material-icons-outlined icon-sm">chevron_right</span>
            <span class="font-semibold" style="color: var(--md-on-surface);">{{ $product->localized_name ?? ($product->name_ar ?? $product->name) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <!-- Gallery Section - Larger -->
            <div class="lg:col-span-3">
                @php
                    $mainMedia = null;
                    $mainUrl = null;
                    $mainType = 'image';
                    $mediaItems = $product->media ?? collect();

                    if ($mediaItems->count() > 0) {
                        $mainMedia = $mediaItems->first();
                        $mainUrl = $mainMedia->url ?? null;
                        $mainType = $mainMedia->type ?? 'image';
                    }

                    if (!$mainUrl && !empty($gallery) && !empty($gallery[0])) {
                        $mainUrl = $gallery[0];
                        $mainType = 'image';
                    }

                    if (!$mainUrl && $product->image) {
                        $mainUrl = str_starts_with($product->image,'http') ? $product->image : asset($product->image);
                        $mainType = 'image';
                    }

                    if (!$mainUrl) {
                        $mainUrl = 'https://placehold.co/800x800/jpg?text=No+Image';
                        $mainType = 'image';
                    }
                @endphp
                
                <!-- Main Image - Square -->
                <div class="mb-6 rounded-2xl overflow-hidden" style="background: var(--md-surface-bright); box-shadow: 0 8px 24px rgba(0,0,0,0.12); border: 1px solid var(--md-outline-variant);">
                    <div class="relative overflow-hidden group" style="aspect-ratio: 1;">
                        <img id="mainImage" src="{{ $mainType === 'image' ? $mainUrl : 'https://placehold.co/800x800/jpg?text=Video' }}" 
                             alt="{{ $product->localized_name }}" 
                             class="w-full h-full object-cover transition-all duration-500 group-hover:scale-110" 
                             style="display: {{ $mainType === 'image' ? 'block' : 'none' }};">

                        <video id="mainVideo" controls class="w-full h-full object-cover transition-all duration-300 bg-black" 
                               style="display: {{ $mainType === 'video' ? 'block' : 'none' }};">
                            @if($mainType === 'video')
                                <source src="{{ $mainUrl }}" type="video/mp4">
                            @endif
                        </video>

                        @if($mainType === 'video')
                            <div class="absolute top-4 right-4 px-4 py-2 rounded-full text-xs font-bold flex items-center gap-1 transition-all duration-300" style="background: var(--md-primary); color: var(--md-on-primary); box-shadow: 0 4px 12px rgba(0,0,0,0.2);">
                                <span class="material-icons-outlined icon-sm">play_circle</span>
                                {{ app()->getLocale() === 'ar' ? 'فيديو' : 'Video' }}
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Thumbnails -->
                @if($mediaItems->count() > 1)
                <div class="flex gap-3 overflow-x-auto pb-2">
                    @foreach($mediaItems as $i => $m)
                        @php
                            $thumb = $m->thumbnail_url ?? $m->url;
                            $url = $m->url;
                            $type = $m->type ?? 'image';
                        @endphp
                        <button type="button" class="thumb-btn shrink-0 rounded-xl overflow-hidden border-3 transition-all hover:shadow-lg hover:scale-105" 
                                style="border-color: {{ $i === 0 ? 'var(--md-primary)' : 'var(--md-outline-variant)' }}; width:80px; height:80px; box-shadow: {{ $i === 0 ? '0 4px 12px rgba(0,0,0,0.12)' : 'none' }};" 
                                data-img="{{ $url }}" data-type="{{ $type }}">
                            @if($type === 'video')
                                <div class="w-full h-full relative flex items-center justify-center" style="background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);">
                                    <img src="{{ $thumb ?? 'https://placehold.co/200x200/png?text=Video' }}" class="w-full h-full object-cover" alt="">
                                    <div class="absolute inset-0 flex items-center justify-center">
                                        <span class="material-icons text-white" style="font-size:24px; text-shadow: 0 2px 4px rgba(0,0,0,0.3);">play_circle</span>
                                    </div>
                                </div>
                            @else
                                <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="">
                            @endif
                        </button>
                    @endforeach
                </div>
                @endif

                <!-- Product Description -->
                <div class="mt-8 p-8 rounded-2xl" style="background: var(--md-surface-container-highest); border: 1px solid var(--md-outline-variant); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">description</span>
                        <h3 class="text-xl font-bold" style="color: var(--md-on-surface);">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</h3>
                    </div>
                    <p class="md-body-medium leading-relaxed" style="color: var(--md-on-surface-variant); line-height: 1.8;">
                        {{ $product->localized_description ?? ($product->description_ar ?? $product->description) }}
                    </p>
                </div>
            </div>

            <!-- Product Details Section - Right Side -->
            <div class="lg:col-span-2">
                <!-- Product Header Card -->
                <div class="rounded-2xl p-8 mb-6" style="background: var(--md-surface-bright); border: 1px solid var(--md-outline-variant); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                    <div class="mb-6 pb-6" style="border-bottom: 1px solid var(--md-outline-variant);">
                        <h1 class="text-3xl font-bold mb-4" style="color: var(--md-on-surface);">{{ $product->localized_name ?? ($product->name_ar ?? $product->name) }}</h1>
                        
                        @if($product->city)
                        <div class="flex items-center gap-2 mb-3">
                            <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">location_on</span>
                            <span class="text-sm font-medium" style="color: var(--md-on-surface);">{{ $product->city->localized_name ?? $product->city->name }}</span>
                        </div>
                        @endif

                        <!-- Product Short Description -->
                        @php
                            $shortDesc = $product->short_description ?? substr($product->localized_description ?? '', 0, 100);
                        @endphp
                        @if($shortDesc)
                        <p class="text-sm leading-relaxed" style="color: var(--md-on-surface-variant);">{{ $shortDesc }}{{ strlen($product->localized_description ?? '') > 100 ? '...' : '' }}</p>
                        @endif
                    </div>

                    <!-- Price Section - Solid surface, better UX -->
                    <div class="rounded-2xl p-6 mb-6" style="background: var(--md-surface-container-highest); color: var(--md-on-surface); border: 1px solid var(--md-outline-variant); box-shadow: 0 4px 12px rgba(0,0,0,0.08);">
                        <div class="mb-4 flex items-end justify-between gap-4">
                            <p class="text-sm font-medium" style="color: var(--md-on-surface-variant);">{{ app()->getLocale() === 'ar' ? 'السعر الحالي' : 'Current Price' }}</p>
                            <p class="text-4xl font-extrabold tracking-tight" style="color: var(--md-on-surface);">${{ number_format($product->final_price, 2) }}</p>
                        </div>
                        
                        @php
                            $originalPrice = $product->price ?? $product->price_sell ?? 0;
                            $hasDiscount = $product->discount > 0;
                        @endphp

                        @if($hasDiscount)
                        <div class="flex items-center gap-3 pt-4" style="border-top: 1px solid var(--md-outline-variant);">
                            <span class="line-through text-sm" style="color: var(--md-on-surface-variant);">${{ number_format($originalPrice, 2) }}</span>
                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold transition-all duration-300" style="background: var(--md-error-container); color: var(--md-on-error-container);">
                                <span class="material-icons-outlined icon-sm">flash_on</span>
                                -{{ $product->discount }}%
                            </span>
                        </div>
                        @endif

                        @if($product->shipping_price && $product->shipping_price > 0)
                        <div class="flex items-center gap-2 text-sm mt-4 pt-4 font-medium" style="border-top: 1px solid var(--md-outline-variant); color: var(--md-on-surface-variant);">
                            <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">local_shipping</span>
                            <span>{{ app()->getLocale() === 'ar' ? 'الشحن:' : 'Shipping:' }} ${{ number_format($product->shipping_price, 2) }}</span>
                        </div>
                        @endif
                    </div>

                    @php
                        // Ensure colors and sizes are arrays - same logic as product card
                        $colors = $product->colors;
                        if (is_string($colors)) {
                            $colors = json_decode($colors, true) ?? [];
                        }
                        if (!is_array($colors)) {
                            $colors = [];
                        }

                        $sizes = $product->sizes;
                        if (is_string($sizes)) {
                            $sizes = json_decode($sizes, true) ?? [];
                        }
                        if (!is_array($sizes)) {
                            $sizes = [];
                        }
                    @endphp

                    <!-- Variants Section - Same style as product card -->
                    @if(!empty($colors) || !empty($sizes))
                        <div class="flex flex-col gap-3 mb-8 p-6 rounded-2xl" style="background: var(--md-surface-container-high); border: 1px solid var(--md-outline-variant); box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                            {{-- Colors --}}
                            @if(!empty($colors))
                                <div class="flex flex-col gap-2">
                                    <p class="md-label-medium font-bold flex items-center gap-2" style="color: var(--md-on-surface);">
                                        <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">palette</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'اختر اللون' : 'Choose Color' }}</span>
                                    </p>
                                    <div class="flex gap-2.5 flex-wrap items-center">
                                        @foreach($colors as $index => $color)
                                            <button class="color-btn rounded-full transition-all duration-300 shrink-0 hover:scale-125 active:scale-95 group/color"
                                                    style="width: 40px; height: 40px; background: {{ $color }}; border: 2px solid {{ $index === 0 ? 'var(--md-primary)' : 'var(--md-outline)' }}; box-shadow: {{ $index === 0 ? '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)' : '0 1px 3px rgba(0,0,0,0.1)' }};"
                                                    onclick="this.parentElement.querySelectorAll('.color-btn').forEach(b => b.style.border = '2px solid var(--md-outline)'); this.style.border = '2px solid var(--md-primary)'; updateButtonStates();"
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
                                <div class="flex flex-col gap-2 {{ !empty($colors) ? 'pt-3 border-t' : '' }}" style="{{ !empty($colors) ? 'border-color: var(--md-outline-variant)' : '' }}">
                                    <p class="md-label-medium font-bold flex items-center gap-2" style="color: var(--md-on-surface);">
                                        <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">straighten</span>
                                        <span>{{ app()->getLocale() === 'ar' ? 'المقاسات' : 'Sizes' }}</span>
                                    </p>
                                    <div class="flex flex-wrap gap-2" id="sizes-group">
                                        @foreach($sizes as $index => $size)
                                            <button type="button" class="size-btn md-label-small px-3 py-1.5 rounded-lg border-2 font-medium transition-all duration-300 hover:scale-105 active:scale-95"
                                                    style="border-color: {{ $index === 0 ? 'var(--md-primary)' : 'var(--md-outline)' }}; background: {{ $index === 0 ? 'var(--md-primary-container)' : 'transparent' }}; color: {{ $index === 0 ? 'var(--md-on-primary-container)' : 'var(--md-on-surface)' }}; font-size: 0.875rem; cursor: pointer;"
                                                    onclick="document.querySelectorAll('.size-btn').forEach(b => { b.style.borderColor = 'var(--md-outline)'; b.style.background = 'transparent'; b.style.color = 'var(--md-on-surface)'; }); this.style.borderColor = 'var(--md-primary)'; this.style.background = 'var(--md-primary-container)'; this.style.color = 'var(--md-on-primary-container)'; updateButtonStates();"
                                                    aria-label="Size: {{ $size }}">
                                                {{ $size }}
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3 mb-6">
                    <button id="addToCartBtn" class="w-full py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-lg hover:brightness-110 active:scale-95 flex items-center justify-center gap-2" 
                            style="background: var(--md-primary); color: var(--md-on-primary); box-shadow: 0 6px 16px rgba(0,0,0,0.18);">
                        <span class="material-icons-outlined">shopping_cart</span>
                        {{ app()->getLocale() === 'ar' ? 'أضف للسلة' : 'Add to Cart' }}
                    </button>
                    <button id="continueShoppingBtn" class="w-full py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:shadow-lg hover:brightness-95 active:scale-95 flex items-center justify-center gap-2 border-3" 
                            style="color: var(--md-on-surface); border-color: var(--md-primary); background: transparent;">
                        <span class="material-icons-outlined">shopping_bag</span>
                        {{ app()->getLocale() === 'ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                    </button>
                </div>

                <!-- Info Chips - Material Design 3 -->
                <div class="flex flex-wrap gap-2">
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300" style="background: var(--md-surface-container-highest); color: var(--md-on-surface-variant); border: 1px solid var(--md-outline-variant);">
                        <span class="material-icons-outlined icon-sm">inventory_2</span>
                        <span class="text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'متاح الآن' : 'In Stock' }}</span>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-full transition-all duration-300" style="background: var(--md-surface-container-highest); color: var(--md-on-surface-variant); border: 1px solid var(--md-outline-variant);">
                        <span class="material-icons-outlined icon-sm" style="color: var(--md-primary);">verified</span>
                        <span class="text-sm font-medium">{{ app()->getLocale() === 'ar' ? 'موثوق' : 'Verified' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- City Section -->
        <div id="city-posts" class="mt-16 pt-12 border-t" style="border-color: var(--md-outline-variant)">
            <!-- Section Header -->
            <div class="mb-12">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-icons-outlined" style="font-size: 32px; color: var(--md-primary);">location_city</span>
                    <h2 class="text-4xl font-bold" style="color: var(--md-on-surface);">
                        {{ app()->getLocale() === 'ar' ? 'استكشف المدينة' : 'Discover the City' }}
                    </h2>
                </div>
                <p class="md-body-large mt-2" style="color: var(--md-on-surface-variant); max-width: 600px;">
                    {{ app()->getLocale() === 'ar' ? 'تعرّف على المعلومات والمعالم السياحية للمدينة التي تشتري منها' : 'Learn more about the city where this product originates from' }}
                </p>
            </div>
            
            @if($product->city)
            @php
                $media = $cityMedia ?? '';
                $mediaType = $cityMediaType ?? 'image';
                $media = $media ? (str_starts_with($media, 'http') ? $media : asset($media)) : '';
                $cityName = $product->city->localized_name ?? $product->city->name;
                $cityDesc = $product->city->description_ar ?? $product->city->description;
            @endphp
            
            <!-- City Card with Professional Modern Design -->
            <div class="mb-12" style="border-radius: 24px; overflow: hidden; background: var(--md-surface-bright); box-shadow: 0 8px 24px rgba(0,0,0,0.12); border: 1px solid var(--md-outline-variant);">
                <!-- Media Section - Full Width Top -->
                @if($media)
                <div class="relative overflow-hidden" style="aspect-ratio: 21/9; background: linear-gradient(135deg, #f5f5f5 0%, #e8e8e8 100%);">
                    @if($mediaType === 'video')
                        <video style="width: 100%; height: 100%; object-fit: cover;" controls>
                            <source src="{{ $media }}" type="video/mp4">
                        </video>
                        <div class="absolute top-6 right-6 flex items-center gap-2 px-4 py-2 bg-white/90 text-black rounded-full text-sm font-bold backdrop-filter backdrop-blur-md" style="box-shadow: 0 4px 12px rgba(0,0,0,0.15);">
                            <span class="material-icons-outlined" style="font-size: 18px">play_circle</span>
                            {{ app()->getLocale() === 'ar' ? 'فيديو' : 'Video' }}
                        </div>
                    @else
                        <img src="{{ $media }}" alt="{{ $cityName }}" class="w-full h-full object-cover transition-transform duration-700 hover:scale-110" style="cursor: pointer;">
                    @endif
                </div>
                @endif

                <!-- Info Section - Bottom -->
                <div class="p-8 md:p-12">
                    <div class="max-w-4xl">
                        <!-- City Header -->
                        <div class="mb-8">
                            <div class="flex items-end gap-4 mb-4">
                                <div>
                                    <h3 class="text-5xl font-bold mb-3" style="color: var(--md-on-surface);">{{ $cityName }}</h3>
                                    <div class="flex items-center gap-3">
                                        <div style="width: 60px; height: 4px; background: linear-gradient(90deg, var(--md-primary) 0%, var(--md-secondary) 100%); border-radius: 2px;"></div>
                                        <span class="md-label-large font-semibold" style="color: var(--md-primary);">{{ app()->getLocale() === 'ar' ? 'وجهة فريدة' : 'Unique Destination' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brief Description -->
                        @if($cityDesc)
                            <div class="mb-8">
                                <p class="md-body-large leading-relaxed" style="color: var(--md-on-surface-variant); line-height: 1.8; font-size: 16px;">
                                    {{ substr($cityDesc, 0, 200) }}{{ strlen($cityDesc) > 200 ? '...' : '' }}
                                </p>
                            </div>
                        @endif

                        <!-- City Features Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-8" style="border-top: 2px solid var(--md-outline-variant);">
                            <!-- Feature 1 -->
                            <div class="flex gap-4">
                                <div class="shrink-0">
                                    <div class="flex items-center justify-center w-14 h-14 rounded-lg" style="background: var(--md-primary); color: var(--md-on-primary);">
                                        <span class="material-icons-outlined">location_on</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg mb-1" style="color: var(--md-on-surface);">{{ app()->getLocale() === 'ar' ? 'موقع استراتيجي' : 'Strategic Location' }}</h4>
                                    <p class="md-body-small" style="color: var(--md-on-surface-variant);">{{ app()->getLocale() === 'ar' ? 'تقع في موقع جغرافي مهم وحيوي' : 'Located in a vital geographic position' }}</p>
                                </div>
                            </div>

                            <!-- Feature 2 -->
                            <div class="flex gap-4">
                                <div class="shrink-0">
                                    <div class="flex items-center justify-center w-14 h-14 rounded-lg" style="background: var(--md-secondary); color: var(--md-on-secondary);">
                                        <span class="material-icons-outlined">shopping_bag</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg mb-1" style="color: var(--md-on-surface);">{{ app()->getLocale() === 'ar' ? 'منتجات أصلية' : 'Authentic Products' }}</h4>
                                    <p class="md-body-small" style="color: var(--md-on-surface-variant);">{{ app()->getLocale() === 'ar' ? 'جودة عالية وأصالة مضمونة' : 'Premium quality guaranteed' }}</p>
                                </div>
                            </div>

                            <!-- Feature 3 -->
                            <div class="flex gap-4">
                                <div class="shrink-0">
                                    <div class="flex items-center justify-center w-14 h-14 rounded-lg" style="background: var(--md-tertiary); color: var(--md-on-tertiary);">
                                        <span class="material-icons-outlined">history</span>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-lg mb-1" style="color: var(--md-on-surface);">{{ app()->getLocale() === 'ar' ? 'تراث غني' : 'Rich Heritage' }}</h4>
                                    <p class="md-body-small" style="color: var(--md-on-surface-variant);">{{ app()->getLocale() === 'ar' ? 'ثقافة وتاريخ عريق' : 'Deep culture & history' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About the City Section -->
            <div style="background: var(--md-surface-container-highest); border-radius: 16px; padding: 3rem; border: 1px solid var(--md-outline-variant);">
                <div class="flex items-center gap-3 mb-6">
                    <span class="material-icons-outlined" style="font-size: 28px; color: var(--md-primary);">info</span>
                    <h3 class="text-2xl font-bold" style="color: var(--md-on-surface);">
                        {{ app()->getLocale() === 'ar' ? 'عن المدينة' : 'About the City' }}
                    </h3>
                </div>

                @if($cityDesc)
                    <div class="space-y-4">
                        <p class="md-body-large leading-relaxed" style="color: var(--md-on-surface); line-height: 1.8;">
                            {{ $cityDesc }}
                        </p>
                    </div>
                @else
                    <p class="md-body-large" style="color: var(--md-on-surface-variant);">
                        {{ app()->getLocale() === 'ar' ? 'لا توجد معلومات متاحة عن هذه المدينة حالياً' : 'No information available about this city at the moment' }}
                    </p>
                @endif
            </div>
            @endif
        </div>


    </section>
@endsection

@push('scripts')
@include('website.layout.components.cart-modal')
<script>
    // Minimal product-page cart handling using existing cart format
    (function(){
        @php
            // Check if product has a valid active package
            $hasValidPackage = $product->is_package && $product->package && $product->package->is_active;
            $packages = $hasValidPackage ? [[
                'id' => $product->package->id,
                'name' => [
                    'ar' => $product->package->name_ar ?? $product->package->name,
                    'en' => $product->package->name_en ?? $product->package->name,
                ],
                'description' => [
                    'ar' => $product->package->description_ar ?? $product->package->description ?? '',
                    'en' => $product->package->description_en ?? $product->package->description ?? '',
                ],
                'items' => $product->package->items ?? [],
                'price' => (float)$product->package->price,
                'original_price' => (float)$product->package->original_price,
                'discount' => (int)$product->package->discount,
                'final_price' => (float)$product->package->final_price,
                'shipping_price' => (float)$product->package->shipping_price,
                'quantity' => (int)$product->package->quantity,
            ]] : [];

            $productPayload = [
                'id' => $product->id,
                'name' => [
                    'ar' => $product->name_ar ?? $product->name,
                    'en' => $product->name_en ?? $product->name,
                ],
                'description' => [
                    'ar' => $product->description_ar ?? $product->description,
                    'en' => $product->description_en ?? $product->description,
                ],
                'price' => $product->final_price,
                'shipping_price' => $product->shipping_price ?? 0,
                'image' => $gallery[0] ?? '',
                'colors' => is_array($product->colors) ? $product->colors : [],
                'sizes' => is_array($product->sizes) ? $product->sizes : [],
                'isPackage' => $hasValidPackage,
                'packages' => $packages,
            ];
            $cityNamePayload = [
                'ar' => $product->city?->name_ar ?? $product->city?->name,
                'en' => $product->city?->name_en ?? $product->city?->name,
            ];
        @endphp
        const product = @json($productPayload);
        const cityName = @json($cityNamePayload);
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');

        function getSelectedColor(){
            // Get the currently selected color from the first color button with primary border
            const colorBtns = document.querySelectorAll('.color-btn');
            if (colorBtns.length === 0) return null;
            
            // Find button with primary border (selected state)
            for (let btn of colorBtns) {
                if (btn.style.border && btn.style.border.includes('var(--md-primary)')) {
                    return btn.getAttribute('data-color');
                }
            }
            
            // Fallback to first color if no selection found
            return colorBtns[0]?.getAttribute('data-color') || (product.colors?.[0] || null);
        }

        function getSelectedSize(){
            // Get the currently selected size from the size button with primary background
            const sizeBtns = document.querySelectorAll('.size-btn');
            if (sizeBtns.length === 0) return null;
            
            // Find button with primary background (selected state)
            for (let btn of sizeBtns) {
                if (btn.style.background && btn.style.background.includes('var(--md-primary-container)')) {
                    return btn.textContent.trim();
                }
            }
            
            // Fallback to first size if no selection found
            return sizeBtns[0]?.textContent.trim() || (product.sizes?.[0] || null);
        }

        document.querySelectorAll('.color-btn').forEach(btn => btn.addEventListener('click', function(){
            updateButtonStates(); // Update button when color changes
        }));
        
        document.querySelectorAll('.size-btn').forEach(btn => btn.addEventListener('click', function(){
            updateButtonStates(); // Update button when size changes
        }));

        // Gallery thumbnails UX (support images and videos)
        const mainImage = document.getElementById('mainImage');
        const mainVideo = document.getElementById('mainVideo');
        function clearThumbActive() {
            document.querySelectorAll('.thumb-btn').forEach(b => {
                b.classList.remove('active-thumb');
                b.classList.remove('ring-2', 'ring-yellow-400', 'border-yellow-400');
            });
        }

        function showImage(url){
            if (!mainImage) return;
            // hide video
            if (mainVideo) {
                mainVideo.pause();
                // remove sources to avoid preloading old video
                Array.from(mainVideo.querySelectorAll('source')).forEach(s => s.src = '');
                mainVideo.style.display = 'none';
            }
            mainImage.src = url;
            mainImage.style.display = 'block';
        }

        function showVideo(url){
            if (!mainVideo) return;
            // hide image
            if (mainImage) {
                mainImage.src = '';
                mainImage.style.display = 'none';
            }
            // set or update source
            let srcEl = mainVideo.querySelector('source');
            if (!srcEl) {
                srcEl = document.createElement('source');
                mainVideo.appendChild(srcEl);
            }
            srcEl.src = url;
            mainVideo.style.display = 'block';
            mainVideo.load();
        }

        document.querySelectorAll('.thumb-btn').forEach(btn => btn.addEventListener('click', function(){
            clearThumbActive();
            this.classList.add('active-thumb');
            this.classList.add('ring-2', 'ring-yellow-400', 'border-yellow-400');
            const img = this.getAttribute('data-img');
            const type = this.getAttribute('data-type') || 'image';
            if (type === 'video') {
                showVideo(img);
            } else {
                showImage(img);
            }
        }));

        // On load: highlight thumbnail that matches the main preview (image or video)
        (function highlightMatchingThumb(){
            let src = '';
            let type = 'image';
            if (mainVideo && mainVideo.style.display !== 'none'){
                const s = mainVideo.querySelector('source');
                src = s ? s.src : '';
                type = 'video';
            } else if (mainImage){
                src = mainImage.src || '';
                type = 'image';
            }
            if (!src) {
                const first = document.querySelector('.thumb-btn');
                if (first) {
                    clearThumbActive();
                    first.classList.add('active-thumb');
                    first.classList.add('ring-2', 'ring-yellow-400', 'border-yellow-400');
                }
                return;
            }
            let matched = false;
            document.querySelectorAll('.thumb-btn').forEach(btn => {
                const img = btn.getAttribute('data-img');
                const t = btn.getAttribute('data-type') || 'image';
                if (!img) return;
                // require same type and match url or path ending
                if (t === type && (img === src || src.endsWith(img) || img.endsWith(src))) {
                    clearThumbActive();
                    btn.classList.add('active-thumb');
                    btn.classList.add('ring-2', 'ring-yellow-400', 'border-yellow-400');
                    matched = true;
                }
            });
            if (!matched) {
                const first = document.querySelector('.thumb-btn');
                if (first) {
                    clearThumbActive();
                    first.classList.add('active-thumb');
                    first.classList.add('ring-2', 'ring-yellow-400', 'border-yellow-400');
                }
            }
        })();

        // Helper: check if product with CURRENT variants is in cart
        function isProductInCart(){
            const selectedColor = getSelectedColor();
            const selectedSize = getSelectedSize();
            return cart.some(i => i.id === product.id && i.selectedColor === selectedColor && i.selectedSize === selectedSize);
        }

        // Helper: update button states based on cart status
        function updateButtonStates(){
            const addBtn = document.getElementById('addToCartBtn');
            const continueBtn = document.getElementById('continueShoppingBtn');
            if (!addBtn || !continueBtn) return;

            if (isProductInCart()) {
                // Product in cart: show Checkout + Continue Shopping
                addBtn.textContent = document.documentElement.lang === 'ar' ? 'إلى الدفع' : 'Checkout';
                addBtn.classList.add('btn-yellow');
                addBtn.onclick = function(){ proceedToCheckout && proceedToCheckout(); };
            } else {
                // Product not in cart: show Add to Cart + Continue Shopping
                addBtn.textContent = document.documentElement.lang === 'ar' ? 'أضف للسلة' : 'Add to Cart';
                addBtn.classList.add('btn-yellow');
                addBtn.onclick = function(){ addProductToCart(); };
            }
        }

        // Add product to cart logic
        function addProductToCart(){
            // Check if this is a package product
            if (product.isPackage) {
                // For packages, handle package selection
                addPackageToCart();
                return;
            }

            // Check if product has variants (colors or sizes)
            const hasColors = product.colors && product.colors.length > 0;
            const hasSizes = product.sizes && product.sizes.length > 0;
            
            // Get selected variants (or null if no variants)
            const selectedColor = hasColors ? getSelectedColor() : null;
            const selectedSize = hasSizes ? getSelectedSize() : null;
            
            const existing = cart.find(i => i.id === product.id && i.selectedColor === selectedColor && i.selectedSize === selectedSize);
            if (existing) {
                existing.quantity += 1;
            } else {
                // Get product image for cart display
                let productImage = '{{ $mainUrl }}';
                if (!productImage || productImage.includes('placehold')) {
                    productImage = '{{ asset("storage/placeholder.jpg") }}';
                }
                
                cart.push({
                    ...product,
                    image: productImage,
                    cityName,
                    selectedColor,
                    selectedSize,
                    quantity: 1
                });
            }
            localStorage.setItem('cart', JSON.stringify(cart));
            if (typeof updateCartDisplay === 'function') {
                updateCartDisplay();
            }
            // Dispatch custom event for real-time updates
            window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
            updateButtonStates();
        }

        // Add package to cart logic
        function addPackageToCart(){
            // Since it's one-to-one, just use the single package
            if (!product.packages || product.packages.length === 0) {
                alert(document.documentElement.lang === 'ar' ? 'لا توجد باقة متاحة' : 'No package available');
                return;
            }
            
            const pkg = product.packages[0]; // First (and only) package
            
            // Create unique identifier for package: product_id-package_id
            const cartItemId = `${product.id}-pkg-${pkg.id}`;
            
            const existing = cart.find(i => i.cartItemId === cartItemId);
            if (existing) {
                if (existing.quantity < pkg.quantity) {
                    existing.quantity += 1;
                } else {
                    alert(document.documentElement.lang === 'ar' ? 'لا توجد كمية كافية من هذه الباقة' : 'Not enough quantity available');
                    return;
                }
            } else {
                let productImage = '{{ $mainUrl }}';
                if (!productImage || productImage.includes('placehold')) {
                    productImage = '{{ asset("storage/placeholder.jpg") }}';
                }
                
                cart.push({
                    id: product.id,
                    cartItemId: cartItemId,
                    name: product.name,
                    image: productImage,
                    cityName: cityName,
                    price: pkg.final_price,
                    shipping_price: pkg.shipping_price,
                    quantity: 1,
                    packageId: pkg.id,
                    packageName: pkg.name,
                    packageItems: pkg.items,
                    isPackageItem: true,
                });
            }
            
            localStorage.setItem('cart', JSON.stringify(cart));
            if (typeof updateCartDisplay === 'function') {
                updateCartDisplay();
            }
            window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
            updateButtonStates();
        }

        // Continue Shopping logic
        function continueShopping(){
            // Go back to home or products page
            window.location.href = '{{ route("pages-home") }}';
        }

        // Attach event listeners - button onclick handlers are set by updateButtonStates()
        const continueBtn = document.getElementById('continueShoppingBtn');

        if (continueBtn) {
            continueBtn.addEventListener('click', continueShopping);
        }

        // On load: set initial button states
        (function initButtonState(){
            updateButtonStates();
        })();

        // Listen for custom cart updates (same-tab changes from cart modal)
        window.addEventListener('cartUpdated', function(e) {
            cart = e.detail || [];
            updateButtonStates();
        });

        // Also listen for storage events (cross-tab changes)
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue || '[]');
                updateButtonStates();
            }
        });
    })();
</script>
@endpush
