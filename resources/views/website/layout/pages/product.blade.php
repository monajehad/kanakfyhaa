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
            <div class="lg:flex lg:items-start gap-4">
                @php
                    // Determine main media (could be image or video) with fallbacks
                    $mainMedia = null; // instance of Media or null
                    $mainUrl = null;
                    $mainType = 'image';
                    $mediaItems = $product->media ?? collect();

                    if ($mediaItems->count() > 0) {
                        $mainMedia = $mediaItems->first();
                        $mainUrl = $mainMedia->url ?? null;
                        $mainType = $mainMedia->type ?? 'image';
                    }

                    // fallback to gallery array (assume images)
                    if (!$mainUrl && !empty($gallery) && !empty($gallery[0])) {
                        $mainUrl = $gallery[0];
                        $mainType = 'image';
                        $mainMedia = null;
                    }

                    // fallback to product->image
                    if (!$mainUrl && $product->image) {
                        $mainUrl = str_starts_with($product->image,'http') ? $product->image : asset($product->image);
                        $mainType = 'image';
                        $mainMedia = null;
                    }

                    // fallback to city landmark media
                    if (!$mainUrl && isset($cityLandmark)) {
                        $cm = optional($cityLandmark->media->first());
                        if ($cm && $cm->url) {
                            $mainUrl = $cm->url;
                            $mainType = $cm->type ?? 'image';
                            $mainMedia = $cm;
                        }
                    }

                    if (!$mainUrl) {
                        $mainUrl = 'https://placehold.co/800x800/jpg?text=No+Image';
                        $mainType = 'image';
                        $mainMedia = null;
                    }
                @endphp

                <div class="lg:flex-1">
                    <div class="rounded overflow-hidden bg-black/20 relative" style="border: 1px solid var(--border-color)">
                        {{-- Image preview --}}
                        <img id="mainImage" src="{{ $mainType === 'image' ? $mainUrl : 'https://placehold.co/800x800/jpg?text=No+Image' }}" alt="{{ $product->localized_name ?? $product->name }}" class="w-full h-96 object-cover transition-all duration-200" style="display: {{ $mainType === 'image' ? 'block' : 'none' }};"
                             onerror="this.onerror=null;this.src='https://placehold.co/800x800/jpg?text=No+Image';">

                        {{-- Video preview --}}
                        <video id="mainVideo" controls class="w-full h-96 object-cover transition-all duration-200" style="display: {{ $mainType === 'video' ? 'block' : 'none' }};">
                            @if($mainType === 'video')
                                <source src="{{ $mainUrl }}" type="video/mp4">
                            @endif
                            Your browser does not support the video tag.
                        </video>
                    </div>
                </div>

                @if($mediaItems->count() > 0 || count($gallery) > 1)
                <div class="mt-3 lg:mt-0 lg:w-20 flex lg:flex-col gap-3">
                    @if($mediaItems->count() > 0)
                        @foreach($mediaItems as $i => $m)
                            @php
                                $thumb = $m->thumbnail_url ?? $m->url;
                                $url = $m->url;
                                $type = $m->type ?? 'image';
                            @endphp
                            <button type="button" class="thumb-btn rounded overflow-hidden border transition-all duration-150 {{ ($i === 0) ? 'active-thumb' : '' }}" style="border-color: var(--border-color); width:64px; height:64px;" data-img="{{ $url }}" data-type="{{ $type }}">
                                @if($type === 'video')
                                    {{-- show thumbnail if available, otherwise show a video placeholder --}}
                                    <img src="{{ $thumb ?? 'https://placehold.co/200x200/png?text=Video' }}" class="w-full h-full object-cover" alt="" onerror="this.onerror=null;this.src='https://placehold.co/200x200/png?text=Video';">
                                @else
                                    <img src="{{ $thumb }}" class="w-full h-full object-cover" alt="" onerror="this.onerror=null;this.src='https://placehold.co/200x200/jpg?text=No+Image';">
                                @endif
                            </button>
                        @endforeach
                    @endif
                    @if($mediaItems->count() === 0 && count($gallery) > 1)
                        @foreach($gallery as $i => $img)
                            @if($i === 0) @continue @endif
                            <button type="button" class="thumb-btn rounded overflow-hidden border transition-all duration-150" style="border-color: var(--border-color); width:64px; height:64px;" data-img="{{ $img }}">
                                <img src="{{ $img }}" class="w-full h-full object-cover" alt="" onerror="this.onerror=null;this.src='https://placehold.co/200x200/jpg?text=No+Image';">
                            </button>
                        @endforeach
                    @endif
                </div>
                @endif
            </div>

            <div>
                <h1 class="text-2xl lg:text-3xl font-extrabold mb-3">{{ $product->localized_name ?? ($product->name_ar ?? $product->name) }}</h1>
                <p class="mb-4" style="color: var(--gray-text)">{{ $product->localized_description ?? ($product->description_ar ?? $product->description) }}</p>

                @php
                    // Ensure colors and sizes are arrays with data
                    $colors = is_array($product->colors) && count($product->colors) > 0 ? $product->colors : ['#000000', '#FFFFFF', '#FF0000'];
                    $sizes = is_array($product->sizes) && count($product->sizes) > 0 ? $product->sizes : ['S', 'M', 'L', 'XL'];
                @endphp

                <div class="mb-4">
                    <div class="text-sm font-semibold mb-2" style="color: var(--gray-text)">{{ app()->getLocale()==='ar' ? 'الألوان' : 'Colors' }}</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($colors as $i => $c)
                            <button class="color-btn {{ $i===0 ? 'active ring-2' : '' }}" data-color="{{ $c }}" title="{{ $c }}" style="width:36px;height:36px;border-radius:9999px;background: {{ $c }};border:2px solid #333;cursor:pointer;transition:all 0.2s;"></button>
                        @endforeach
                    </div>
                </div>

                <div class="mb-6">
                    <div class="text-sm font-semibold mb-2" style="color: var(--gray-text)">{{ app()->getLocale()==='ar' ? 'المقاسات' : 'Sizes' }}</div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($sizes as $i => $s)
                            <button class="size-btn px-4 py-2 rounded border transition-all {{ $i===0 ? 'active ring-2' : '' }}" style="border-color: var(--border-color);cursor:pointer;" data-size="{{ $s }}">{{ $s }}</button>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between mb-6">
                    <div class="text-2xl font-extrabold">{{ $product->final_price }} $</div>
                    @if($product->discount)
                        <div class="text-sm line-through" style="color: var(--gray-text)">{{ ($product->price ?? $product->price_sell) }} $</div>
                    @endif
                </div>

                @if($product->is_package && $product->package)
                    <div class="mb-6 p-4 rounded" style="background: var(--gray-bg); border: 1px solid var(--border-color)">
                        <h3 class="font-semibold mb-3">{{ app()->getLocale() === 'ar' ? 'معلومات الباقة' : 'Package Information' }}</h3>
                        
                        <div class="mb-3">
                            <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'اسم الباقة' : 'Package Name' }}</div>
                            <div>{{ $product->package->name_ar ?? $product->package->name }}</div>
                        </div>

                        @if($product->package->description_ar || $product->package->description)
                        <div class="mb-3">
                            <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'الوصف' : 'Description' }}</div>
                            <div class="text-sm" style="color: var(--gray-text)">{{ $product->package->description_ar ?? $product->package->description }}</div>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-2 mb-3 text-sm">
                            <div>
                                <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'سعر الباقة' : 'Package Price' }}</div>
                                <div>${{ number_format($product->package->price, 2) }}</div>
                            </div>
                            @if($product->package->discount > 0)
                            <div>
                                <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'الخصم' : 'Discount' }}</div>
                                <div>{{ $product->package->discount }}%</div>
                            </div>
                            @endif
                            <div>
                                <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'السعر النهائي' : 'Final Price' }}</div>
                                <div class="font-bold">${{ number_format($product->package->final_price, 2) }}</div>
                            </div>
                            <div>
                                <div class="font-semibold">{{ app()->getLocale() === 'ar' ? 'الشحن' : 'Shipping' }}</div>
                                <div>${{ number_format($product->package->shipping_price, 2) }}</div>
                            </div>
                        </div>

                        @if($product->package->items && count($product->package->items) > 0)
                        <div>
                            <div class="font-semibold mb-2">{{ app()->getLocale() === 'ar' ? 'محتويات الباقة' : 'Included Items' }}</div>
                            <ul class="list-disc list-inside text-sm" style="color: var(--gray-text)">
                                @foreach($product->package->items as $item)
                                    <li>{{ $item['name_ar'] ?? $item['name'] ?? $item }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif
                    </div>
                @endif

                <div class="flex flex-col sm:flex-row gap-3 mb-6">
                    <button id="addToCartBtn" class="btn-yellow px-6 py-3 flex-1 sm:flex-auto">
                        {{ app()->getLocale()==='ar' ? 'أضف للسلة' : 'Add to Cart' }}
                    </button>
                    <button id="continueShoppingBtn" class="px-6 py-3 rounded flex-1 sm:flex-auto font-semibold transition-all hover:shadow-md" style="background: var(--primary-yellow); color: white; border: 2px solid var(--primary-yellow);">
                        {{ app()->getLocale()==='ar' ? 'متابعة التسوق' : 'Continue Shopping' }}
                    </button>
                </div>

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

        {{-- Related products and artifacts/posts sections removed as requested --}}

        <div id="city-posts" class="mt-14">
            <h2 class="text-xl font-bold mb-4">{{ app()->getLocale()==='ar' ? 'المدينة' : 'City' }}</h2>
            <div class="grid grid-cols-1 gap-6">
                @if($product->city)
                @php
                    // use controller-provided cityImage (already normalized) or fallback
                    $img = $cityImage ?? optional($product->city->media->first())->url ?? $product->city->image;
                    $img = $img ? (str_starts_with($img,'http') ? $img : asset($img)) : '';
                    $cityName = $product->city->localized_name ?? $product->city->name;
                    $landmarksCount = $landmarksCount ?? ($product->city->landmarks ? $product->city->landmarks->count() : 0);
                @endphp
                <div class="rounded overflow-hidden" style="background: var(--gray-bg); border: 1px solid var(--border-color)">
                    @if($img)
                        {{-- Center the city image inside a responsive square box to preserve square images UX --}}
                        <div class="city-image-wrapper" style="width:100%;max-width:720px;margin:0 auto;position:relative;padding-top:100%;background:#f7fafc;overflow:hidden;">
                            <img src="{{ $img }}" alt="{{ $cityName }}" style="position:absolute;inset:0;width:100%;height:100%;object-fit:cover;" onerror="this.onerror=null;this.src='https://placehold.co/800x800/jpg?text=No+Image';">
                        </div>
                    @else
                        <div class="w-full" style="max-width:720px;margin:0 auto;">
                            <div class="w-full h-64 bg-gray-200 flex items-center justify-center text-gray-500">{{ app()->getLocale()==='ar' ? 'لا توجد صورة' : 'No image' }}</div>
                        </div>
                    @endif
                    <div class="p-4 text-center lg:text-right">
                        <div class="font-bold text-lg">{{ $cityName }}</div>
                        <div class="text-xs mt-1" style="color: var(--gray-text)">
                            {{ app()->getLocale()==='ar' ? 'عدد المعالم:' : 'Landmarks:' }} {{ $landmarksCount }}
                        </div>
                    </div>
                </div>
                @else
                <p class="text-center col-span-full" style="color: var(--gray-text)">{{ app()->getLocale()==='ar' ? 'لا توجد بيانات' : 'No data' }}</p>
                @endif
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
                'shipping_price' => $product->shipping_price ?? 0,
                'image' => $gallery[0] ?? '',
                'colors' => is_array($product->colors) ? $product->colors : [],
                'sizes' => is_array($product->sizes) ? $product->sizes : [],
                'isPackage' => (bool) $product->is_package,
                'packages' => $product->is_package && $product->package && $product->package->is_active ? [[
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
                ]] : [],
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
            updateButtonStates(); // Update button when color changes
        }));
        document.querySelectorAll('.size-btn').forEach(btn => btn.addEventListener('click', function(){
            document.querySelectorAll('.size-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
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

            const selectedColor = getSelectedColor();
            const selectedSize = getSelectedSize();
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
