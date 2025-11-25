@extends('website.layout.main')

@section('title', 'البحث - كأنك فيها')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Search Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4" style="color: var(--primary-black); border-bottom: 4px solid var(--primary-yellow); display: inline-block; padding-bottom: 8px;" data-ar="البحث المتقدم" data-en="Advanced Search">البحث المتقدم</h1>
        
        <!-- Search Input -->
        <div class="flex gap-2 mb-6 max-w-2xl" style="display: none">
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
        <!-- Filters Sidebar - Material Design 3 -->
        <aside class="lg:w-80 flex-shrink-0">
            <div class="sticky top-24 p-6 rounded-2xl" style="background: var(--md-surface-container-highest); border: 1px solid var(--md-outline-variant); box-shadow: 0 2px 8px rgba(0,0,0,0.06);">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="md-headline-medium font-bold flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 24px; color: var(--md-primary);">tune</span>
                        <span data-ar="الفلاتر" data-en="Filters">الفلاتر</span>
                    </h2>
                    <button onclick="clearFilters()" class="md-label-small font-semibold px-3 py-1 rounded-full transition-all duration-300 hover:scale-105" style="color: var(--md-error); background: rgba(244, 67, 54, 0.1); border: none; cursor: pointer;" data-ar="مسح الكل" data-en="Clear All">مسح الكل</button>
                </div>

                <!-- Divider -->
                <div style="height: 1px; background: var(--md-outline-variant); margin-bottom: 1.5rem;"></div>

                <!-- City Filter -->
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">location_city</span>
                        <span data-ar="المدينة" data-en="City">المدينة</span>
                    </h3>
                    <select id="filterCity" class="w-full px-3 py-2.5 rounded-lg border-2 md-body-small focus:outline-none transition-colors" style="border-color: var(--md-outline); background: var(--md-surface); color: var(--md-on-surface); cursor: pointer;" onchange="window.performSearch(1)">
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
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">category</span>
                        <span data-ar="الفئة" data-en="Category">الفئة</span>
                    </h3>
                    <select id="filterCategory" class="w-full px-3 py-2.5 rounded-lg border-2 md-body-small focus:outline-none transition-colors" style="border-color: var(--md-outline); background: var(--md-surface); color: var(--md-on-surface); cursor: pointer;" onchange="window.performSearch(1)">
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
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">price_check</span>
                        <span data-ar="السعر" data-en="Price">السعر</span>
                    </h3>
                    <div class="flex gap-2 items-center mb-2">
                        <input 
                            type="number" 
                            id="filterMinPrice" 
                            class="flex-1 px-3 py-2 rounded-lg border-2 md-body-small focus:outline-none transition-colors"
                            style="border-color: var(--md-outline); background: var(--md-surface); color: var(--md-on-surface);"
                            placeholder="{{ $minPrice }}"
                            min="{{ $minPrice }}"
                            max="{{ $maxPrice }}"
                            value="{{ $filters['min_price'] ?? '' }}"
                            onchange="window.performSearch(1)"
                        >
                        <span class="md-label-small" style="color: var(--md-on-surface-variant);" data-ar="إلى" data-en="to">إلى</span>
                        <input 
                            type="number" 
                            id="filterMaxPrice" 
                            class="flex-1 px-3 py-2 rounded-lg border-2 md-body-small focus:outline-none transition-colors"
                            style="border-color: var(--md-outline); background: var(--md-surface); color: var(--md-on-surface);"
                            placeholder="{{ $maxPrice }}"
                            min="{{ $minPrice }}"
                            max="{{ $maxPrice }}"
                            value="{{ $filters['max_price'] ?? '' }}"
                            onchange="window.performSearch(1)"
                        >
                    </div>
                    <div class="md-label-small" style="color: var(--md-on-surface-variant);">
                        <span data-ar="من" data-en="From">من</span> 
                        <span id="priceRangeDisplay" class="font-semibold" style="color: var(--md-primary);">{{ $minPrice }}</span> 
                        <span data-ar="إلى" data-en="to">إلى</span> 
                        <span id="priceRangeDisplayMax" class="font-semibold" style="color: var(--md-primary);">{{ $maxPrice }}</span>
                    </div>
                </div>

                <!-- Colors Filter -->
                @if(count($colors) > 0)
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">palette</span>
                        <span data-ar="الألوان" data-en="Colors">الألوان</span>
                    </h3>
                    <div class="flex flex-wrap gap-2.5" id="colorFilters">
                        @foreach($colors as $color)
                            <button 
                                type="button"
                                class="filter-color-btn rounded-full transition-all duration-300 hover:scale-110 active:scale-95"
                                style="width: 40px; height: 40px; background: {{ $color }}; border: 2px solid {{ (isset($filters['colors']) && in_array($color, is_array($filters['colors']) ? $filters['colors'] : [$filters['colors']])) ? 'var(--md-primary)' : 'var(--md-outline)' }}; box-shadow: {{ (isset($filters['colors']) && in_array($color, is_array($filters['colors']) ? $filters['colors'] : [$filters['colors']])) ? '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)' : '0 1px 3px rgba(0,0,0,0.1)' }};"
                                data-color="{{ $color }}"
                                onclick="toggleColorFilter('{{ $color }}')"
                            ></button>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Sizes Filter -->
                @if(count($sizes) > 0)
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">straighten</span>
                        <span data-ar="المقاسات" data-en="Sizes">المقاسات</span>
                    </h3>
                    <div class="flex flex-wrap gap-2" id="sizeFilters">
                        @foreach($sizes as $size)
                            <button 
                                type="button"
                                class="filter-size-btn md-label-small px-3 py-1.5 rounded-lg border-2 font-medium transition-all duration-300 hover:scale-105 active:scale-95"
                                style="border-color: {{ (isset($filters['sizes']) && in_array($size, is_array($filters['sizes']) ? $filters['sizes'] : [$filters['sizes']])) ? 'var(--md-primary)' : 'var(--md-outline)' }}; background: {{ (isset($filters['sizes']) && in_array($size, is_array($filters['sizes']) ? $filters['sizes'] : [$filters['sizes']])) ? 'var(--md-primary-container)' : 'transparent' }}; color: {{ (isset($filters['sizes']) && in_array($size, is_array($filters['sizes']) ? $filters['sizes'] : [$filters['sizes']])) ? 'var(--md-on-primary-container)' : 'var(--md-on-surface)' }};"
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
                <div class="mb-7">
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">card_giftcard</span>
                        <span data-ar="النوع" data-en="Type">النوع</span>
                    </h3>
                    <label class="flex items-center gap-3 cursor-pointer p-2 rounded-lg transition-colors hover:bg-[rgba(200,212,0,0.05)]" style="user-select: none;">
                        <input 
                            type="checkbox" 
                            id="filterPackage" 
                            class="w-5 h-5 rounded cursor-pointer accent-[var(--md-primary)]"
                            {{ (isset($filters['is_package']) && $filters['is_package']) ? 'checked' : '' }}
                            onchange="window.performSearch(1)"
                        >
                        <span class="md-body-small" style="color: var(--md-on-surface);" data-ar="بكج كامل فقط" data-en="Full Package Only">بكج كامل فقط</span>
                    </label>
                </div>

                <!-- Sort -->
                <div>
                    <h3 class="md-label-large font-bold mb-3 flex items-center gap-2" style="color: var(--md-on-surface);">
                        <span class="material-icons-outlined" style="font-size: 18px; color: var(--md-primary);">sort</span>
                        <span data-ar="الترتيب" data-en="Sort By">الترتيب</span>
                    </h3>
                    <select id="filterSort" class="w-full px-3 py-2.5 rounded-lg border-2 md-body-small focus:outline-none transition-colors" style="border-color: var(--md-outline); background: var(--md-surface); color: var(--md-on-surface); cursor: pointer;" onchange="window.performSearch(1)">
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

    // Render products using Material Design 3 card layout
    function renderProducts(products, clear = false) {
        const container = document.getElementById('searchResults');
        if (clear) {
            container.innerHTML = '';
        }

        const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');

        products.forEach(product => {
            // Helper functions to escape strings safely
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
            
            // Safely get product data
            const pName = product.name && product.name[lang] ? product.name[lang] : (product.name && product.name.ar ? product.name.ar : 'Product');
            const pDesc = product.description && product.description[lang] ? product.description[lang] : (product.description && product.description.ar ? product.description.ar : '');
            const imageUrl = escapeJs(product.image || 'https://placehold.co/400x400/f8f9fa/cccccc?text=Product');
            
            // Ensure variants are arrays
            const colors = Array.isArray(product.colors) ? product.colors : [];
            const sizes = Array.isArray(product.sizes) ? product.sizes : [];
            
            // Build color buttons HTML
            const colorButtonsHtml = colors.length > 0 ? `
                <div class="flex flex-col gap-2">
                    <p class="md-label-small font-medium" style="color: var(--md-on-surface);">
                        <span data-ar="الألوان" data-en="Colors">الألوان</span>
                    </p>
                    <div class="flex gap-2.5 flex-wrap items-center">
                        ${colors.map((color, index) => {
                            const escapedColor = escapeJs(color || '');
                            return `
                                <button class="color-btn rounded-full transition-all duration-300 shrink-0 hover:scale-125 active:scale-95" 
                                        style="width: 32px; height: 32px; background: ${escapeJs(color || '')}; border: 2px solid ${index === 0 ? 'var(--md-primary)' : 'var(--md-outline)'}; box-shadow: ${index === 0 ? '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)' : '0 1px 3px rgba(0,0,0,0.1)'};"
                                        onclick="window.selectColor(${product.id}, '${escapedColor}', this)"
                                        data-color="${escapeJs(color || '')}"
                                        title="${escapeHtml(color)}"
                                        aria-label="Color: ${escapeHtml(color)}">
                                </button>
                            `;
                        }).join('')}
                    </div>
                </div>
            ` : '';

            // Build size buttons HTML
            const sizeButtonsHtml = sizes.length > 0 ? `
                <div class="flex flex-col gap-2 ${colors.length > 0 ? 'pt-3 border-t' : ''}" style="${colors.length > 0 ? 'border-color: var(--md-outline-variant)' : ''}">
                    <p class="md-label-small font-medium" style="color: var(--md-on-surface);">
                        <span data-ar="الأحجام" data-en="Sizes">الأحجام</span>
                    </p>
                    <div class="flex flex-wrap gap-2" id="sizes-${product.id}">
                        ${sizes.map((size, index) => {
                            const escapedSize = escapeJs(size || '');
                            return `
                                <button class="size-btn md-label-small px-3 py-1.5 rounded-lg border-2 font-medium transition-all duration-300 hover:scale-105 active:scale-95" 
                                        style="border-color: ${index === 0 ? 'var(--md-primary)' : 'var(--md-outline)'}; background: ${index === 0 ? 'var(--md-primary-container)' : 'transparent'}; color: ${index === 0 ? 'var(--md-on-primary-container)' : 'var(--md-on-surface)'}; font-size: 0.875rem;"
                                        onclick="window.selectSize(${product.id}, '${escapedSize}', this)"
                                        aria-label="Size: ${escapeHtml(size)}">
                                    ${escapeHtml(size)}
                                </button>
                            `;
                        }).join('')}
                    </div>
                </div>
            ` : '';

            // Build the product card HTML with Material Design 3 styling
            const productHtml = `
                <div class="group relative flex flex-col h-full overflow-hidden transition-all duration-500 hover:shadow-2xl"
                     style="border-radius: 16px; background: var(--md-surface-bright); box-shadow: 0 1px 2px rgba(0,0,0,0.04);">
                    
                    <!-- Image Container with Overlay -->
                    <div class="relative overflow-hidden bg-linear-to-br from-slate-100 to-slate-50 h-72">
                        <!-- Image -->
                        <img src="${imageUrl}" 
                             alt="${escapeHtml(pName)}" 
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110"
                             style="cursor: pointer;"
                             onerror="this.src='https://placehold.co/400x400/f8f9fa/cccccc?text=Product'">
                        
                        <!-- Gradient Overlay (appears on hover) -->
                        <div class="absolute inset-0 bg-linear-to-t from-black/40 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Badge Container -->
                        <div class="absolute top-4 right-4 z-20 flex flex-col gap-2">
                            ${product.isPackage ? `
                                <div class="px-3 py-2 rounded-full text-xs font-bold flex items-center gap-1.5 backdrop-blur-md transition-all duration-300"
                                     style="background: rgba(200, 212, 0, 0.95); color: #1F2612;">
                                    <span class="material-icons-outlined" style="font-size: 18px;">card_giftcard</span>
                                    <span data-ar="باقة" data-en="Package">باقة</span>
                                </div>
                            ` : ''}
                            
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
                        
                        <!-- Product Name -->
                        <div class="flex flex-col gap-2">
                            <h3 class="md-title-medium font-bold line-clamp-2 transition-colors duration-300 group-hover:text-primary"
                                style="color: var(--md-on-surface);">
                                ${escapeHtml(pName)}
                            </h3>
                            
                            <!-- Description -->
                            <p class="md-body-small line-clamp-2"
                               style="color: var(--md-on-surface-variant);">
                                ${escapeHtml(pDesc)}
                            </p>
                        </div>

                        <!-- Divider -->
                        <div style="height: 1px; background: var(--md-outline-variant);"></div>

                        <!-- Variants Section -->
                        ${(colors.length > 0 || sizes.length > 0) ? `
                            <div class="flex flex-col gap-3">
                                ${colorButtonsHtml}
                                ${sizeButtonsHtml}
                            </div>
                        ` : ''}

                        <!-- Divider -->
                        <div style="height: 1px; background: var(--md-outline-variant);"></div>

                        <!-- Price & Action -->
                        <div class="flex items-center justify-between gap-3 mt-auto">
                            <!-- Price Badge -->
                            <div class="flex items-baseline gap-1">
                                <span class="md-headline-small font-bold transition-colors duration-300"
                                      id="price-${product.id}"
                                      style="color: var(--md-primary);">
                                    $${Number(product.price || 0).toFixed(2)}
                                </span>
                                ${(product.original_price || 0) > (product.price || 0) ? `
                                    <span class="md-label-small line-through"
                                          style="color: var(--md-on-surface-variant);">
                                        $${Number(product.original_price || 0).toFixed(2)}
                                    </span>
                                ` : ''}
                            </div>

                            <!-- Quick Add Button -->
                            <button onclick="window.addToCartSearch(${product.city?.id || 0}, ${product.id})"
                                    class="rounded-full w-12 h-12 flex items-center justify-center transition-all duration-300 hover:scale-110 active:scale-95 shadow-md hover:shadow-lg"
                                    style="background: var(--md-primary); color: var(--md-on-primary);"
                                    title="Add to Cart"
                                    aria-label="Add to Cart">
                                <span class="material-icons-outlined" style="font-size: 24px;">add_shopping_cart</span>
                            </button>
                        </div>
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

    // Add to cart from search using same logic as home page
    window.addToCartSearch = function(cityId, productId) {
        // Parse as integers
        const parsedCityId = parseInt(cityId);
        const parsedProductId = parseInt(productId);
        
        // Find product in stored products
        const product = searchState.products.find(p => p.id === parsedProductId);
        if (!product) {
            const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
            if (window.Swal) {
                Swal.fire({
                    title: lang === 'ar' ? 'خطأ' : 'Error',
                    text: lang === 'ar' ? 'المنتج غير موجود' : 'Product not found',
                    icon: 'error',
                    timer: 2000,
                });
            }
            return;
        }

        // Check if product has variants
        const hasColors = product.colors && product.colors.length > 0;
        const hasSizes = product.sizes && product.sizes.length > 0;
        
        // Get selected variants or null if no variants exist
        let selectedColor = null;
        let selectedSize = null;
        
        // Find the product card - look through all product cards for one with matching product id
        let productCard = null;
        const allCards = document.querySelectorAll('#searchResults .group');
        for (let card of allCards) {
            const addBtn = card.querySelector('button[onclick*="addToCartSearch"]');
            if (addBtn && addBtn.getAttribute('onclick').includes(parsedProductId)) {
                productCard = card;
                break;
            }
        }
        
        if (!productCard) {
            console.error('Product card not found for product:', parsedProductId);
            return;
        }
        
        // Get selected color from the card - check for primary border style
        if (hasColors) {
            const colorBtns = productCard.querySelectorAll('.color-btn');
            let colorBtn = null;
            for (let btn of colorBtns) {
                if (btn.style.border && btn.style.border.includes('2px solid var(--md-primary)')) {
                    colorBtn = btn;
                    break;
                }
            }
            selectedColor = colorBtn ? colorBtn.getAttribute('data-color') : product.colors[0];
        }
        
        // Get selected size from the card - check for primary-container background
        if (hasSizes) {
            const sizeBtns = productCard.querySelectorAll('.size-btn');
            let sizeBtn = null;
            for (let btn of sizeBtns) {
                if (btn.style.background && btn.style.background.includes('var(--md-primary-container)')) {
                    sizeBtn = btn;
                    break;
                }
            }
            selectedSize = sizeBtn ? sizeBtn.textContent.trim() : product.sizes[0];
        }
        
        // Get cart from localStorage
        let cart = JSON.parse(localStorage.getItem('cart') || '[]');
        
        // Check if this item already exists in cart
        const existingItem = cart.find(item => 
            item.id === parsedProductId && 
            item.selectedColor === selectedColor && 
            item.selectedSize === selectedSize
        );
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            // Build city name object
            const cityName = product.city ? {
                ar: product.city.name_ar || product.city.name || '',
                en: product.city.name_en || product.city.name || ''
            } : { ar: '', en: '' };

            const cartItem = {
                ...product,
                cityName: cityName,
                selectedColor,
                selectedSize,
                quantity: 1,
                isPackage: product.isPackage || false
            };
            cart.push(cartItem);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        
        // Update cart display if function exists
        if (typeof updateCartDisplay === 'function') {
            updateCartDisplay();
        }
        
        // Dispatch custom event for real-time updates
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
        
        // Show success feedback
        const lang = typeof currentLang !== 'undefined' ? currentLang : (localStorage.getItem('language') || 'ar');
        if (window.Swal) {
            Swal.fire({
                title: lang === 'ar' ? 'تم الإضافة' : 'Added',
                text: lang === 'ar' ? 'تمت إضافة المنتج للسلة' : 'Product added to cart',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
    };

    // Select color and size (for product cards) - update styles
    window.selectColor = function(productId, color, btn) {
        const colorButtons = btn.parentElement.querySelectorAll('.color-btn');
        colorButtons.forEach(b => {
            b.style.border = '2px solid var(--md-outline)';
            b.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
        });
        btn.style.border = '2px solid var(--md-primary)';
        btn.style.boxShadow = '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)';
    };

    window.selectSize = function(productId, size, btn) {
        const sizeButtons = btn.parentElement.querySelectorAll('.size-btn');
        sizeButtons.forEach(b => {
            b.style.borderColor = 'var(--md-outline)';
            b.style.background = 'transparent';
            b.style.color = 'var(--md-on-surface)';
        });
        btn.style.borderColor = 'var(--md-primary)';
        btn.style.background = 'var(--md-primary-container)';
        btn.style.color = 'var(--md-on-primary-container)';
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

