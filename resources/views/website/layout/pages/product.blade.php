@extends('website.layout.main')

@section('title', $product->localized_name ?? ($product->name_ar ?? $product->name))

@section('content')
    <section class="container mx-auto px-4 py-10">
        <nav class="text-sm mb-6" style="color: var(--gray-text)">
            <a href="{{ route('pages-home') }}" class="hover:underline" style="color: var(--primary-yellow)">{{ app()->getLocale() === 'ar' ? 'الرئيسية' : 'Home' }}</a>
            <span class="mx-2">/</span>
            <a href="#city" class="hover:underline">{{ $product->city?->localized_name }}</a>
            <span class="mx-2">/</span>
            <span>{{ $product->localized_name ?? ($product->name_ar ?? $product->name) }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
            <div>
                <div class="rounded overflow-hidden bg-black/20" style="border: 1px solid var(--border-color)">
                    <img id="mainImage" src="{{ $gallery[0] ?? '' }}" alt="{{ $product->localized_name ?? $product->name }}" class="w-full h-auto object-cover"
                         onerror="this.onerror=null;this.src='https://placehold.co/800x800/jpg?text=No+Image';">
                </div>
                @if(count($gallery) > 1)
                <div class="grid grid-cols-5 gap-3 mt-3">
                    @foreach($gallery as $img)
                        <button class="rounded overflow-hidden border" style="border-color: var(--border-color)" onclick="document.getElementById('mainImage').src='{{ $img }}'">
                            <img src="{{ $img }}" class="w-full h-20 object-cover" alt=""
                                 onerror="this.onerror=null;this.src='https://placehold.co/200x200/jpg?text=No+Image';">
                        </button>
                    @endforeach
                </div>
                @endif
            </div>

            <div>
                <h1 class="text-2xl lg:text-3xl font-extrabold mb-3">{{ $product->localized_name ?? ($product->name_ar ?? $product->name) }}</h1>
                <p class="mb-4" style="color: var(--gray-text)">{{ $product->localized_description ?? ($product->description_ar ?? $product->description) }}</p>

                <div class="mb-4 flex flex-wrap gap-2">
                    @if(is_array($product->colors) && count($product->colors))
                        @foreach($product->colors as $i => $c)
                            <button class="color-btn {{ $i===0 ? 'active' : '' }}" data-color="{{ $c }}" style="width:28px;height:28px;border-radius:9999px;background: {{ $c }};border:2px solid #222"></button>
                        @endforeach
                    @endif
                </div>

                <div class="mb-6 flex flex-wrap gap-2">
                    @if(is_array($product->sizes) && count($product->sizes))
                        @foreach($product->sizes as $i => $s)
                            <button class="size-btn px-3 py-1 rounded border {{ $i===0 ? 'active' : '' }}" style="border-color: var(--border-color)">{{ $s }}</button>
                        @endforeach
                    @endif
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="text-2xl font-extrabold">{{ $product->final_price }} $</div>
                    @if($product->discount)
                        <div class="text-sm line-through" style="color: var(--gray-text)">{{ ($product->price ?? $product->price_sell) }} $</div>
                    @endif
                </div>

                <button id="addToCartBtn" class="btn-yellow w-full lg:w-auto px-6 py-3">
                    {{ app()->getLocale()==='ar' ? 'أضف للسلة' : 'Add to Cart' }}
                </button>

                <div class="mt-6 flex flex-wrap gap-2">
                    @if($product->city)
                    <a id="city" class="px-3 py-1 rounded-full text-sm" style="background: var(--gray-bg)" href="#city-posts">
                        {{ app()->getLocale()==='ar' ? 'مدينة:' : 'City:' }} {{ $product->city->localized_name }}
                    </a>
                    @endif
                    <a class="px-3 py-1 rounded-full text-sm" style="background: var(--gray-bg)" href="#artifact-posts">
                        {{ app()->getLocale()==='ar' ? 'الآثار' : 'Artifacts' }}
                    </a>
                </div>
            </div>
        </div>

        @if($relatedProducts->count())
        <div class="mt-12">
            <h2 class="text-xl font-bold mb-4">{{ app()->getLocale()==='ar' ? 'منتجات ذات صلة' : 'Related Products' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($relatedProducts as $rp)
                @php
                    $img = optional($rp->media->where('role','main')->first())->url ?? $rp->image;
                    $img = $img ? (str_starts_with($img,'http') ? $img : asset($img)) : '';
                    $name = app()->getLocale()==='ar' ? ($rp->name_ar ?? $rp->name) : ($rp->name_en ?? $rp->name);
                @endphp
                <a href="{{ route('product.show', $rp->uuid) }}" class="product-card block">
                    <img src="{{ $img }}" class="product-image" alt="{{ $name }}">
                    <div class="product-info">
                        <div class="product-name">{{ $name }}</div>
                        <div class="text-sm" style="color: var(--gray-text)">{{ $rp->city?->localized_name }}</div>
                        <div class="mt-2 font-bold">${{ $rp->final_price }}</div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        <div id="city-posts" class="mt-14">
            <h2 class="text-xl font-bold mb-4">{{ app()->getLocale()==='ar' ? 'منشورات المدينة' : 'City Posts' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse($landmarks as $lm)
                @php
                    $img = optional($lm->media->first())->url ?? $lm->image;
                    $img = $img ? (str_starts_with($img,'http') ? $img : asset($img)) : '';
                @endphp
                <div class="rounded overflow-hidden" style="background: var(--gray-bg); border: 1px solid var(--border-color)">
                    <img src="{{ $img }}" alt="{{ $lm->name }}" class="w-full h-44 object-cover">
                    <div class="p-4">
                        <div class="font-bold">{{ $lm->name }}</div>
                        <div class="text-xs mt-1" style="color: var(--gray-text)">
                            {{ app()->getLocale()==='ar' ? 'عدد الآثار:' : 'Artifacts:' }} {{ $lm->artifacts_count }}
                        </div>
                    </div>
                </div>
                @empty
                <p class="text-center col-span-full" style="color: var(--gray-text)">{{ app()->getLocale()==='ar' ? 'لا توجد منشورات' : 'No posts' }}</p>
                @endforelse
            </div>
        </div>

        <div id="artifact-posts" class="mt-14">
            <h2 class="text-xl font-bold mb-4">{{ app()->getLocale()==='ar' ? 'منشورات الآثار' : 'Artifacts Posts' }}</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($artifacts as $af)
                @php
                    $img = optional($af->media->first())->url ?? $af->image;
                    $img = $img ? (str_starts_with($img,'http') ? $img : asset($img)) : '';
                @endphp
                <div class="rounded overflow-hidden" style="background: var(--gray-bg); border: 1px solid var(--border-color)">
                    <img src="{{ $img }}" alt="{{ $af->title }}" class="w-full h-44 object-cover">
                    <div class="p-4">
                        <div class="font-bold">{{ $af->title }}</div>
                        <div class="text-xs mt-1" style="color: var(--gray-text)">{{ $af->landmark?->name }}</div>
                    </div>
                </div>
                @empty
                <p class="text-center col-span-full" style="color: var(--gray-text)">{{ app()->getLocale()==='ar' ? 'لا توجد منشورات' : 'No posts' }}</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection

@push('scripts')
@include('website.layout.components.cart-modal')
<script>
    // Minimal product-page cart handling using existing cart format
    (function(){
        @php
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
                'image' => $gallery[0] ?? '',
                'colors' => is_array($product->colors) ? $product->colors : [],
                'sizes' => is_array($product->sizes) ? $product->sizes : [],
                'isPackage' => (bool) $product->is_package,
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
            const active = document.querySelector('.color-btn.active');
            return active ? active.getAttribute('data-color') : (product.colors[0] || null);
        }
        function getSelectedSize(){
            const active = document.querySelector('.size-btn.active');
            return active ? active.textContent.trim() : (product.sizes[0] || null);
        }

        document.querySelectorAll('.color-btn').forEach(btn => btn.addEventListener('click', function(){
            document.querySelectorAll('.color-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        }));
        document.querySelectorAll('.size-btn').forEach(btn => btn.addEventListener('click', function(){
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
        }));

        const addBtn = document.getElementById('addToCartBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function(){
                const selectedColor = getSelectedColor();
                const selectedSize = getSelectedSize();
                const existing = cart.find(i => i.id === product.id && i.selectedColor === selectedColor && i.selectedSize === selectedSize);
                if (existing) {
                    existing.quantity += 1;
                } else {
                    cart.push({
                        ...product,
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
                // Switch button to checkout
                const btn = document.getElementById('addToCartBtn');
                if (btn) {
                    btn.textContent = document.documentElement.lang === 'ar' ? 'إلى الدفع' : 'Checkout';
                    btn.onclick = function(){ proceedToCheckout && proceedToCheckout(); };
                }
                // First add: do not open cart; only change button to Checkout
            });
        }

        // On load, if product already in cart, set button to Checkout
        (function initButtonState(){
            const inCart = cart.some(i => i.id === product.id);
            const btn = document.getElementById('addToCartBtn');
            if (btn && inCart) {
                btn.textContent = document.documentElement.lang === 'ar' ? 'إلى الدفع' : 'Checkout';
                btn.onclick = function(){ proceedToCheckout && proceedToCheckout(); };
            }
        })();
    })();
</script>
@endpush
