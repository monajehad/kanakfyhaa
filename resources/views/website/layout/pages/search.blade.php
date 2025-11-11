@extends('website.layout.main')

@section('title', 'البحث - كأنك فيها')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4" style="color: var(--primary-black); border-bottom: 4px solid var(--primary-yellow); display: inline-block; padding-bottom: 8px;" data-ar="البحث المتقدم" data-en="Advanced Search">البحث المتقدم</h1>
        
        <!-- Search Input -->
        <div class="flex gap-2 mb-6 max-w-2xl">
            <input 
                type="text" 
                id="searchQuery" 
                class="search-bar flex-1" 
                placeholder="ابحث عن المنتجات..." 
                data-ar-placeholder="ابحث عن المنتجات..." 
                data-en-placeholder="Search for products..."
                value="{{ $filters['q'] ?? '' }}"
            >
            <button onclick="performSearch()" class="btn-yellow px-8" style="white-space: nowrap;">
                <span data-ar="بحث" data-en="Search">بحث</span>
            </button>
        </div>
    </div>

    <div class="flex flex-col lg:flex-row gap-6">
        <!-- Filters Sidebar -->
        <aside class="lg:w-80 flex-shrink-0">
            <div class="city-section sticky top-24">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-xl font-bold" style="color: var(--primary-black);" data-ar="الفلاتر" data-en="Filters">الفلاتر</h2>
                    <button onclick="clearFilters()" class="text-sm font-semibold px-3 py-1 rounded" style="color: #dc2626; background: rgba(220, 38, 38, 0.1); transition: all 0.3s;" onmouseover="this.style.background='rgba(220, 38, 38, 0.2)'" onmouseout="this.style.background='rgba(220, 38, 38, 0.1)'" data-ar="مسح الكل" data-en="Clear All">مسح الكل</button>
                </div>

                <!-- City Filter -->
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="المدينة" data-en="City">المدينة</h3>
                    <select id="filterCity" class="filter-select">
                        <option value="" data-ar="جميع المدن" data-en="All Cities">جميع المدن</option>
                        @foreach($cities as $city)
                            <option value="{{ $city['id'] }}" {{ (isset($filters['city']) && $filters['city'] == $city['id']) ? 'selected' : '' }}>
                                {{ $city['name']['ar'] ?? $city['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Category Filter -->
                @if(count($categories) > 0)
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="الفئة" data-en="Category">الفئة</h3>
                    <select id="filterCategory" class="filter-select">
                        <option value="" data-ar="جميع الفئات" data-en="All Categories">جميع الفئات</option>
                        @foreach($categories as $category)
                            <option value="{{ $category['id'] }}" {{ (isset($filters['category']) && $filters['category'] == $category['id']) ? 'selected' : '' }}>
                                {{ $category['name']['ar'] ?? $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif

                <!-- Price Range -->
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="السعر" data-en="Price">السعر</h3>
                    <div class="flex gap-2 items-center mb-2">
                        <input 
                            type="number" 
                            id="filterMinPrice" 
                            class="filter-input flex-1" 
                            placeholder="{{ $minPrice }}"
                            min="{{ $minPrice }}"
                            max="{{ $maxPrice }}"
                            value="{{ $filters['min_price'] ?? '' }}"
                        >
                        <span class="text-sm" style="color: var(--gray-text);" data-ar="إلى" data-en="to">إلى</span>
                        <input 
                            type="number" 
                            id="filterMaxPrice" 
                            class="filter-input flex-1" 
                            placeholder="{{ $maxPrice }}"
                            min="{{ $minPrice }}"
                            max="{{ $maxPrice }}"
                            value="{{ $filters['max_price'] ?? '' }}"
                        >
                    </div>
                    <div class="text-xs" style="color: var(--gray-text);">
                        <span data-ar="من" data-en="From">من</span> 
                        <span id="priceRangeDisplay">{{ $minPrice }}</span> 
                        <span data-ar="إلى" data-en="to">إلى</span> 
                        <span id="priceRangeDisplayMax">{{ $maxPrice }}</span>
                    </div>
                </div>

                <!-- Colors Filter -->
                @if(count($colors) > 0)
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="الألوان" data-en="Colors">الألوان</h3>
                    <div class="flex flex-wrap gap-2" id="colorFilters">
                        @foreach($colors as $color)
                            <button 
                                type="button"
                                class="filter-color-btn color-btn {{ (isset($filters['colors']) && in_array($color, is_array($filters['colors']) ? $filters['colors'] : [$filters['colors']])) ? 'active' : '' }}"
                                style="background: {{ $color }}"
                                data-color="{{ $color }}"
                                onclick="toggleColorFilter('{{ $color }}')"
                            ></button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Sizes Filter -->
                @if(count($sizes) > 0)
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="المقاسات" data-en="Sizes">المقاسات</h3>
                    <div class="flex flex-wrap gap-2" id="sizeFilters">
                        @foreach($sizes as $size)
                            <button 
                                type="button"
                                class="filter-size-btn size-btn {{ (isset($filters['sizes']) && in_array($size, is_array($filters['sizes']) ? $filters['sizes'] : [$filters['sizes']])) ? 'active' : '' }}"
                                data-size="{{ $size }}"
                                onclick="toggleSizeFilter('{{ $size }}')"
                            >
                                {{ $size }}
                            </button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Package Filter -->
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="النوع" data-en="Type">النوع</h3>
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input 
                            type="checkbox" 
                            id="filterPackage" 
                            class="w-5 h-5"
                            {{ (isset($filters['is_package']) && $filters['is_package']) ? 'checked' : '' }}
                            onchange="performSearch()"
                        >
                        <span data-ar="بكج كامل فقط" data-en="Full Package Only">بكج كامل فقط</span>
                    </label>
                </div>

                <!-- Sort -->
                <div class="mb-6">
                    <h3 class="font-bold mb-3" style="color: var(--primary-black);" data-ar="الترتيب" data-en="Sort By">الترتيب</h3>
                    <select id="filterSort" class="filter-select" onchange="performSearch()">
                        <option value="relevance" {{ (isset($filters['sort']) && $filters['sort'] == 'relevance') ? 'selected' : '' }} data-ar="الأكثر صلة" data-en="Most Relevant">الأكثر صلة</option>
                        <option value="price_low" {{ (isset($filters['sort']) && $filters['sort'] == 'price_low') ? 'selected' : '' }} data-ar="السعر: من الأقل للأعلى" data-en="Price: Low to High">السعر: من الأقل للأعلى</option>
                        <option value="price_high" {{ (isset($filters['sort']) && $filters['sort'] == 'price_high') ? 'selected' : '' }} data-ar="السعر: من الأعلى للأقل" data-en="Price: High to Low">السعر: من الأعلى للأقل</option>
                        <option value="name" {{ (isset($filters['sort']) && $filters['sort'] == 'name') ? 'selected' : '' }} data-ar="الاسم" data-en="Name">الاسم</option>
                        <option value="newest" {{ (isset($filters['sort']) && $filters['sort'] == 'newest') ? 'selected' : '' }} data-ar="الأحدث" data-en="Newest">الأحدث</option>
                    </select>
                </div>
            </div>
        </aside>

        <!-- Results Section -->
        <main class="flex-1">
            <!-- Results Header -->
            <div class="flex justify-between items-center mb-6">
                <div>
                    <p class="text-sm font-semibold" style="color: var(--gray-text);">
                        <span id="resultsCount" data-ar="جاري البحث..." data-en="Searching...">جاري البحث...</span>
                    </p>
                </div>
            </div>

            <!-- Products Grid -->
            <div id="searchResults" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <!-- Results will be loaded here -->
            </div>

            <!-- Loading Indicator -->
            <div id="loadingIndicator" class="text-center py-16" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 mb-4" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                <p class="text-lg font-semibold" style="color: var(--gray-text);" data-ar="جاري التحميل..." data-en="Loading...">جاري التحميل...</p>
            </div>

            <!-- No Results -->
            <div id="noResults" class="text-center py-16" style="display: none;">
                <div class="mb-4">
                    <svg class="mx-auto h-16 w-16" style="color: var(--gray-text);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <p class="text-xl font-bold mb-2" style="color: var(--primary-black);" data-ar="لا توجد نتائج" data-en="No results found">لا توجد نتائج</p>
                <p class="text-sm" style="color: var(--gray-text);" data-ar="جرب تغيير الفلاتر أو البحث" data-en="Try changing filters or search">جرب تغيير الفلاتر أو البحث</p>
            </div>

            <!-- Load More Button (fallback if auto-load fails) -->
            <div id="loadMoreContainer" class="text-center mt-8" style="display: none;">
                <button onclick="window.loadMoreResults()" class="btn-yellow px-8">
                    <span data-ar="تحميل المزيد" data-en="Load More">تحميل المزيد</span>
                </button>
            </div>
            
            <!-- Auto-load indicator (shown when scrolling near bottom) -->
            <div id="autoLoadIndicator" class="text-center py-6" style="display: none;">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 mb-2" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                <p class="text-sm font-semibold" style="color: var(--gray-text);" data-ar="جاري تحميل المزيد..." data-en="Loading more...">جاري تحميل المزيد...</p>
            </div>
        </main>
    </div>
</div>

<!-- Shopping Cart Modal -->
@include('website.layout.components.cart-modal')

@push('scripts')
<script>
    // Search state
    let searchState = {
        currentPage: 1,
        hasMore: false,
        loading: false,
        selectedColors: @json(isset($filters['colors']) ? (is_array($filters['colors']) ? $filters['colors'] : [$filters['colors']]) : []),
        selectedSizes: @json(isset($filters['sizes']) ? (is_array($filters['sizes']) ? $filters['sizes'] : [$filters['sizes']]) : []),
        products: [] // Store all loaded products
    };

    // Use currentLang from main scripts (don't redeclare)
    // currentLang is already declared in _scripts.blade.php

    // Perform search - make it globally accessible
    window.performSearch = async function(page = 1) {
        if (searchState.loading) return;
        
        searchState.loading = true;
        searchState.currentPage = page;
        
        // Show loading
        document.getElementById('loadingIndicator').style.display = 'block';
        document.getElementById('noResults').style.display = 'none';
        document.getElementById('loadMoreContainer').style.display = 'none';
        
        if (page === 1) {
            document.getElementById('searchResults').innerHTML = '';
        }

        // Build query params
        const params = new URLSearchParams();
        
        const query = document.getElementById('searchQuery').value.trim();
        if (query) params.append('q', query);
        
        const city = document.getElementById('filterCity').value;
        if (city) params.append('city', city);
        
        const category = document.getElementById('filterCategory')?.value;
        if (category) params.append('category', category);
        
        const minPrice = document.getElementById('filterMinPrice').value;
        if (minPrice) params.append('min_price', minPrice);
        
        const maxPrice = document.getElementById('filterMaxPrice').value;
        if (maxPrice) params.append('max_price', maxPrice);
        
        if (searchState.selectedColors.length > 0) {
            searchState.selectedColors.forEach(color => params.append('colors[]', color));
        }
        
        if (searchState.selectedSizes.length > 0) {
            searchState.selectedSizes.forEach(size => params.append('sizes[]', size));
        }
        
        const isPackage = document.getElementById('filterPackage').checked;
        if (isPackage) params.append('is_package', '1');
        
        const sort = document.getElementById('filterSort').value;
        params.append('sort', sort);
        
        params.append('page', page);

        try {
            const response = await fetch(`/api/search?${params.toString()}`);
            const data = await response.json();

            if (data.success) {
                if (data.products && data.products.length > 0) {
                    // Store products in state
                    if (page === 1) {
                        searchState.products = data.products;
                    } else {
                        searchState.products = [...searchState.products, ...data.products];
                    }
                    
                    renderProducts(data.products, page === 1);
                    const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
                    document.getElementById('resultsCount').textContent = 
                        lang === 'ar' 
                            ? `تم العثور على ${data.total} منتج`
                            : `Found ${data.total} products`;
                    
                    searchState.hasMore = data.hasMore;
                    // Hide load more button (we use auto-load instead)
                    // But keep it as fallback - you can show it if needed
                    if (data.hasMore) {
                        // Optionally show button as fallback
                        // document.getElementById('loadMoreContainer').style.display = 'block';
                    } else {
                        // No more results - hide auto-load indicator
                        document.getElementById('autoLoadIndicator').style.display = 'none';
                    }
                } else {
                    document.getElementById('noResults').style.display = 'block';
                    const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
                    document.getElementById('resultsCount').textContent = 
                        lang === 'ar' 
                            ? 'لا توجد نتائج'
                            : 'No results found';
                }
            }
        } catch (error) {
            console.error('Search error:', error);
        } finally {
            searchState.loading = false;
            document.getElementById('loadingIndicator').style.display = 'none';
            document.getElementById('autoLoadIndicator').style.display = 'none';
        }
    }

    // Render products
    function renderProducts(products, clear = false) {
        const container = document.getElementById('searchResults');
        if (clear) {
            container.innerHTML = '';
        }

        const currency = JSON.parse(localStorage.getItem('currency')) || { symbol: '$', rate: 1 };
        const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
        const packageText = lang === 'ar' ? 'بكج كامل 📦' : 'Full Package 📦';
        const addToCartText = lang === 'ar' ? 'أضف للسلة' : 'Add to Cart';

        products.forEach(product => {
            // Escape all strings to prevent syntax errors
            const escapeHtml = (str) => {
                if (!str) return '';
                return String(str)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#39;');
            };
            
            const escapeJs = (str) => {
                if (!str) return '';
                return String(str)
                    .replace(/\\/g, '\\\\')
                    .replace(/`/g, '\\`')
                    .replace(/\${/g, '\\${')
                    .replace(/'/g, "\\'")
                    .replace(/"/g, '\\"');
            };
            
            const pName = product.name[lang] || product.name.ar || product.name;
            const pDesc = product.description[lang] || product.description.ar || product.description;
            const cityName = product.city ? (product.city.name[lang] || product.city.name.ar) : '';
            
            // Escape image URL to prevent syntax errors
            const imageUrl = escapeJs(product.image || '');
            const fallbackImage = 'data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'400\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3ENo Image%3C/text%3E%3C/svg%3E';

            const productHtml = `
                <div class="product-card">
                    <div class="relative">
                        ${product.isPackage ? `<div class="package-badge">${packageText}</div>` : ''}
                        <img src="${imageUrl}" alt="${escapeHtml(pName)}" class="product-image" onerror="this.onerror=null; this.src='${fallbackImage}'">
                    </div>
                    <div class="product-info">
                        <h3 class="product-name">${escapeHtml(pName)}</h3>
                        ${cityName ? `<p class="text-xs mb-2" style="color: var(--gray-text);">${escapeHtml(cityName)}</p>` : ''}
                        <p class="product-desc">${escapeHtml(pDesc)}</p>
                        
                        <div class="flex gap-2 mb-3">
                            ${product.colors && product.colors.length > 0 ? product.colors.map((color, index) => {
                                const escapedColor = escapeJs(color || '');
                                return `
                                <button class="color-btn ${index === 0 ? 'active' : ''}" 
                                        style="background: ${escapeJs(color || '')}"
                                        onclick="window.selectColor(${product.id}, '${escapedColor}', this)"
                                        data-color="${escapeJs(color || '')}">
                                </button>
                            `;
                            }).join('') : ''}
                        </div>
                        
                        <div class="flex flex-wrap gap-2 mb-3" id="sizes-${product.id}">
                            ${product.sizes && product.sizes.length > 0 ? product.sizes.map((size, index) => {
                                const escapedSize = escapeJs(size || '');
                                return `
                                <button class="size-btn ${index === 0 ? 'active' : ''}" 
                                        onclick="window.selectSize(${product.id}, '${escapedSize}', this)">
                                    ${escapeHtml(size || '')}
                                </button>
                            `;
                            }).join('') : ''}
                        </div>
                        
                        <div class="text-xl font-bold mb-3">${currency.symbol}${(product.price * currency.rate).toFixed(2)}</div>
                        <button onclick="window.addToCartSearch(${product.city?.id || 0}, ${product.id})" class="btn-yellow">
                            ${addToCartText}
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', productHtml);
        });
    }

    // Load more results
    window.loadMoreResults = function() {
        if (!searchState.hasMore || searchState.loading) return;
        window.performSearch(searchState.currentPage + 1);
    };

    // Infinite scroll handler for search page
    function handleSearchScroll() {
        // Don't check if already loading
        if (searchState.loading || !searchState.hasMore) {
            return;
        }

        const threshold = 400; // Load when 400px from bottom
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // Check if we're near the bottom of the page
        const distanceFromBottom = documentHeight - (scrollTop + windowHeight);
        
        if (distanceFromBottom <= threshold) {
            // Show auto-load indicator
            const autoLoadIndicator = document.getElementById('autoLoadIndicator');
            if (autoLoadIndicator) {
                autoLoadIndicator.style.display = 'block';
            }
            
            // Load more results
            window.loadMoreResults();
        } else {
            // Hide indicator if we're not near bottom
            const autoLoadIndicator = document.getElementById('autoLoadIndicator');
            if (autoLoadIndicator && !searchState.loading) {
                autoLoadIndicator.style.display = 'none';
            }
        }
    }

    // Throttle scroll events for better performance
    let searchScrollTimeout;
    function throttledSearchScrollHandler() {
        if (searchScrollTimeout) {
            return;
        }
        searchScrollTimeout = setTimeout(() => {
            handleSearchScroll();
            searchScrollTimeout = null;
        }, 200);
    }

    // Toggle color filter
    window.toggleColorFilter = function(color) {
        const index = searchState.selectedColors.indexOf(color);
        if (index > -1) {
            searchState.selectedColors.splice(index, 1);
        } else {
            searchState.selectedColors.push(color);
        }
        
        // Update UI
        const btn = document.querySelector(`[data-color="${color}"]`);
        if (btn) {
            btn.classList.toggle('active');
        }
        
        window.performSearch(1);
    };

    // Toggle size filter
    window.toggleSizeFilter = function(size) {
        const index = searchState.selectedSizes.indexOf(size);
        if (index > -1) {
            searchState.selectedSizes.splice(index, 1);
        } else {
            searchState.selectedSizes.push(size);
        }
        
        // Update UI
        const btn = document.querySelector(`[data-size="${size}"]`);
        if (btn) {
            btn.classList.toggle('active');
        }
        
        window.performSearch(1);
    };

    // Clear all filters
    window.clearFilters = function() {
        document.getElementById('searchQuery').value = '';
        document.getElementById('filterCity').value = '';
        if (document.getElementById('filterCategory')) {
            document.getElementById('filterCategory').value = '';
        }
        document.getElementById('filterMinPrice').value = '';
        document.getElementById('filterMaxPrice').value = '';
        document.getElementById('filterPackage').checked = false;
        document.getElementById('filterSort').value = 'relevance';
        
        searchState.selectedColors = [];
        searchState.selectedSizes = [];
        
        // Update UI
        document.querySelectorAll('.filter-color-btn').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.filter-size-btn').forEach(btn => btn.classList.remove('active'));
        
        window.performSearch(1);
    };

    // Add to cart from search
    window.addToCartSearch = function(cityId, productId) {
        // Find product in stored products
        const product = searchState.products.find(p => p.id === productId);
        if (!product) {
            const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
            alert(lang === 'ar' ? '❌ المنتج غير موجود' : '❌ Product not found');
            return;
        }

        // Find product element to get selected color and size
        const productElement = document.querySelector(`[onclick*="addToCartSearch(${cityId}, ${productId}"]`)?.closest('.product-card');
        const colorBtn = productElement?.querySelector('.color-btn.active');
        const sizeBtn = productElement?.querySelector('.size-btn.active');
        const selectedColor = colorBtn?.dataset.color || (product.colors && product.colors.length > 0 ? product.colors[0] : '');
        const selectedSize = sizeBtn?.textContent?.trim() || (product.sizes && product.sizes.length > 0 ? product.sizes[0] : '');

        // Get cart from localStorage
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        
        // Create cart item
        const cartItem = {
            ...product,
            cityName: product.city ? product.city.name : { ar: '', en: '' },
            selectedColor: selectedColor,
            selectedSize: selectedSize,
            quantity: 1
        };

        // Check if item already exists
        const existingItem = cart.find(item => 
            item.id === productId && 
            item.selectedColor === selectedColor && 
            item.selectedSize === selectedSize
        );

        if (existingItem) {
            existingItem.quantity++;
        } else {
            cart.push(cartItem);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart display if function exists
        if (typeof updateCartDisplay === 'function') {
            updateCartDisplay();
        }
        
        const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
        alert(lang === 'ar' ? '✅ تمت الإضافة إلى السلة!' : '✅ Added to cart!');
    };

    // Select color and size (for product cards)
    window.selectColor = function(productId, color, btn) {
        const colorButtons = btn.parentElement.querySelectorAll('.color-btn');
        colorButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    };

    window.selectSize = function(productId, size, btn) {
        const sizeButtons = btn.parentElement.querySelectorAll('.size-btn');
        sizeButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    };

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Update placeholders based on language
        const searchInput = document.getElementById('searchQuery');
        if (searchInput) {
            const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
            const placeholder = lang === 'ar' 
                ? searchInput.dataset.arPlaceholder 
                : searchInput.dataset.enPlaceholder;
            searchInput.placeholder = placeholder;
        }

        // Auto-search on filter change
        document.getElementById('filterCity').addEventListener('change', () => window.performSearch(1));
        if (document.getElementById('filterCategory')) {
            document.getElementById('filterCategory').addEventListener('change', () => window.performSearch(1));
        }
        document.getElementById('filterMinPrice').addEventListener('change', () => window.performSearch(1));
        document.getElementById('filterMaxPrice').addEventListener('change', () => window.performSearch(1));

        // Search on Enter key
        document.getElementById('searchQuery').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                window.performSearch(1);
            }
        });

        // Initial search if filters are set
        const hasFilters = @json(!empty($filters));
        if (hasFilters) {
            window.performSearch(1);
        }

        // Add scroll event listener for infinite scroll
        window.addEventListener('scroll', throttledSearchScrollHandler);
        
        // Also check on initial load if we need to load more (in case page is already scrolled)
        setTimeout(() => {
            handleSearchScroll();
        }, 500);
    });
</script>
@endpush
@endsection

