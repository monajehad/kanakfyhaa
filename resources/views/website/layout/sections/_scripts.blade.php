<script>
    // Language & Theme Management
    let currentLang = localStorage.getItem('language') || 'ar';
    let currentTheme = localStorage.getItem('theme') || 'light';

    // Apply saved preferences
    document.documentElement.lang = currentLang;
    document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
    document.documentElement.setAttribute('data-theme', currentTheme);

    // Toggle Language
    function toggleLanguage() {
        currentLang = currentLang === 'ar' ? 'en' : 'ar';
        document.documentElement.lang = currentLang;
        document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
        localStorage.setItem('language', currentLang);
        
        // Update all translatable elements
        document.querySelectorAll('[data-ar][data-en]').forEach(el => {
            el.textContent = currentLang === 'ar' ? el.dataset.ar : el.dataset.en;
        });
        
        // Update placeholders
        document.querySelectorAll('.search-bar').forEach(input => {
            input.placeholder = currentLang === 'ar' ? 
                'ابحث عن المدينة أو المنتج...' : 
                'Search for city or product...';
        });
        
        // Update language button
        document.getElementById('langBtn').textContent = currentLang === 'ar' ? 'EN' : 'ع';
        
        renderCities();
    }

    // Toggle Theme
    function toggleTheme() {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme);
        
        document.getElementById('themeBtn').textContent = currentTheme === 'light' ? '🌙' : '☀️';
    }

    // Initialize theme button
    if (document.getElementById('themeBtn')) {
        document.getElementById('themeBtn').textContent = currentTheme === 'light' ? '🌙' : '☀️';
    }

    // Data Structure with translations - Loaded from database
    let cities = @json($cities ?? []);
    
    // Pagination state for each city
    let cityPagination = {};
    cities.forEach(city => {
        cityPagination[city.id] = {
            currentPage: 1,
            hasMore: city.hasMore || false,
            loading: false
        };
    });
    
    // Save to localStorage for persistence (optional - can be removed if you want fresh data on each page load)
    if (cities && cities.length > 0) {
        localStorage.setItem('cities', JSON.stringify(cities));
    } else {
        // Fallback: Load from localStorage if database is empty
        const savedCities = localStorage.getItem('cities');
        if (savedCities) {
            cities = JSON.parse(savedCities);
        }
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let currency = JSON.parse(localStorage.getItem('currency')) || { symbol: '$', rate: 1 };

    // Render product card HTML
    function renderProductCard(product, cityId) {
        const pName = product.name[currentLang] || product.name.ar || product.name;
        const pDesc = product.description[currentLang] || product.description.ar || product.description;
        const packageText = currentLang === 'ar' ? 'بكج كامل 📦' : 'Full Package 📦';
        const addToCartText = currentLang === 'ar' ? 'أضف للسلة' : 'Add to Cart';
        
        return `
            <div class="product-card">
                <div class="relative">
                    ${product.isPackage ? `<div class="package-badge">${packageText}</div>` : ''}
                    <img src="${product.image || ''}" alt="${pName}" class="product-image" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect fill=\'%23ddd\' width=\'400\' height=\'400\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%23999\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                </div>
                <div class="product-info">
                    <h3 class="product-name">${pName}</h3>
                    <p class="product-desc">${pDesc}</p>
                    
                    <div class="flex gap-2 mb-3">
                        ${product.colors && product.colors.length > 0 ? product.colors.map((color, index) => `
                            <button class="color-btn ${index === 0 ? 'active' : ''}" 
                                    style="background: ${color}"
                                    onclick="selectColor(${product.id}, '${color}', this)"
                                    data-color="${color}">
                            </button>
                        `).join('') : ''}
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mb-3" id="sizes-${product.id}">
                        ${product.sizes && product.sizes.length > 0 ? product.sizes.map((size, index) => `
                            <button class="size-btn ${index === 0 ? 'active' : ''}" 
                                    onclick="selectSize(${product.id}, '${size}', this)">
                                ${size}
                            </button>
                        `).join('') : ''}
                    </div>
                    
                    <div class="text-xl font-bold mb-3">${currency.symbol}${(product.price * currency.rate).toFixed(2)}</div>
                    <button onclick="addToCart(${cityId}, ${product.id})" class="btn-yellow">
                        ${addToCartText}
                    </button>
                </div>
            </div>
        `;
    }

    // Render Cities and Products
    function renderCities() {
        const container = document.getElementById('citiesContainer');
        if (!container) return;
        
        if (cities.length === 0) {
            container.innerHTML = `<p class="text-center py-12" style="color: var(--gray-text)">
                ${currentLang === 'ar' ? 'لا توجد مدن متاحة حالياً' : 'No cities available currently'}
            </p>`;
            return;
        }

        container.innerHTML = cities.map(city => {
            const productsHtml = city.products.map(product => renderProductCard(product, city.id)).join('');
            const loadingHtml = cityPagination[city.id]?.hasMore ? `
                <div class="load-more-container text-center mt-8" data-city-id="${city.id}">
                    <div class="loading-spinner" style="display: none;">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                        <p class="mt-2" style="color: var(--gray-text)">${currentLang === 'ar' ? 'جاري التحميل...' : 'Loading...'}</p>
                    </div>
                </div>
            ` : '';
            
            return `
                <div class="city-section" data-city-id="${city.id}">
                    <h2 class="city-title">${city.name[currentLang] || city.name.ar}</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid-${city.id}">
                        ${productsHtml}
                    </div>
                    ${loadingHtml}
                </div>
            `;
        }).join('');
    }

    // Load more products for a specific city
    async function loadMoreProducts(cityId) {
        const pagination = cityPagination[cityId];
        if (!pagination || !pagination.hasMore || pagination.loading) {
            return;
        }

        pagination.loading = true;
        const nextPage = pagination.currentPage + 1;
        
        // Show or create loading indicator
        let loadMoreContainer = document.querySelector(`.load-more-container[data-city-id="${cityId}"]`);
        if (!loadMoreContainer) {
            // Create load more container if it doesn't exist
            const citySection = document.querySelector(`.city-section[data-city-id="${cityId}"]`);
            if (citySection) {
                const container = document.createElement('div');
                container.className = 'load-more-container text-center mt-8';
                container.setAttribute('data-city-id', cityId);
                container.innerHTML = `
                    <div class="loading-spinner">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                        <p class="mt-2" style="color: var(--gray-text)">${currentLang === 'ar' ? 'جاري التحميل...' : 'Loading...'}</p>
                    </div>
                `;
                citySection.appendChild(container);
                loadMoreContainer = container;
            }
        }
        
        if (loadMoreContainer) {
            const loadingSpinner = loadMoreContainer.querySelector('.loading-spinner');
            if (loadingSpinner) {
                loadingSpinner.style.display = 'block';
            }
        }

        try {
            const response = await fetch(`/api/products/load-more?city_id=${cityId}&page=${nextPage}`);
            const data = await response.json();

            if (data.success && data.products && data.products.length > 0) {
                // Find the city in the cities array and append products
                const city = cities.find(c => c.id === cityId);
                if (city) {
                    city.products = [...city.products, ...data.products];
                }

                // Append products to the grid
                const productsGrid = document.getElementById(`products-grid-${cityId}`);
                if (productsGrid) {
                    const productsHtml = data.products.map(product => renderProductCard(product, cityId)).join('');
                    productsGrid.insertAdjacentHTML('beforeend', productsHtml);
                }

                // Update pagination state
                pagination.currentPage = nextPage;
                pagination.hasMore = data.hasMore;

                // Hide loading indicator
                if (loadMoreContainer) {
                    const loadingSpinner = loadMoreContainer.querySelector('.loading-spinner');
                    if (loadingSpinner) {
                        loadingSpinner.style.display = 'none';
                    }
                    
                    // Remove load more container if no more products
                    if (!data.hasMore) {
                        loadMoreContainer.remove();
                    }
                }
            } else {
                pagination.hasMore = false;
                if (loadMoreContainer) {
                    loadMoreContainer.remove();
                }
            }
        } catch (error) {
            console.error('Error loading more products:', error);
            if (loadMoreContainer) {
                const loadingSpinner = loadMoreContainer.querySelector('.loading-spinner');
                if (loadingSpinner) {
                    loadingSpinner.style.display = 'none';
                }
            }
        } finally {
            pagination.loading = false;
        }
    }

    // Infinite scroll handler
    function handleScroll() {
        const threshold = 400; // Load when 400px from bottom
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // Check if we're near the bottom of the page
        const distanceFromBottom = documentHeight - (scrollTop + windowHeight);
        
        if (distanceFromBottom <= threshold) {
            // Find the city section that's closest to the bottom of the viewport
            let bottomCitySection = null;
            let maxBottom = -Infinity;
            
            document.querySelectorAll('.city-section').forEach(citySection => {
                const rect = citySection.getBoundingClientRect();
                // Check if this section is visible and its bottom is in viewport
                if (rect.bottom > 0 && rect.top < windowHeight) {
                    // Find the section with the bottommost visible part
                    const visibleBottom = Math.min(rect.bottom, windowHeight);
                    if (visibleBottom > maxBottom) {
                        maxBottom = visibleBottom;
                        bottomCitySection = citySection;
                    }
                }
            });
            
            // Load more products for the bottommost visible city section
            if (bottomCitySection) {
                const cityId = parseInt(bottomCitySection.dataset.cityId);
                if (cityId) {
                    const pagination = cityPagination[cityId];
                    if (pagination && pagination.hasMore && !pagination.loading) {
                        loadMoreProducts(cityId);
                    }
                }
            }
        }
    }

    function selectColor(productId, color, btn) {
        const colorButtons = btn.parentElement.querySelectorAll('.color-btn');
        colorButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function selectSize(productId, size, btn) {
        const sizeButtons = btn.parentElement.querySelectorAll('.size-btn');
        sizeButtons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
    }

    function addToCart(cityId, productId) {
        const city = cities.find(c => c.id === cityId);
        const product = city.products.find(p => p.id === productId);
        
        const colorBtn = document.querySelector(`[onclick*="selectColor(${productId}"]`).parentElement.querySelector('.color-btn.active');
        const sizeBtn = document.querySelector(`#sizes-${productId} .size-btn.active`);
        
        const selectedColor = colorBtn.dataset.color;
        const selectedSize = sizeBtn.textContent.trim();
        
        const cartItem = {
            ...product,
            cityName: city.name,
            selectedColor,
            selectedSize,
            quantity: 1
        };
        
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
        updateCartDisplay();
        alert(currentLang === 'ar' ? '✅ تمت الإضافة إلى السلة!' : '✅ Added to cart!');
    }

    function updateCartDisplay() {
        const cartCountEl = document.getElementById('cartCount');
        if (cartCountEl) {
            const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCountEl.textContent = cartCount;
        }
        
        const cartItems = document.getElementById('cartItems');
        if (!cartItems) return;
        
        const emptyText = currentLang === 'ar' ? 'السلة فارغة' : 'Cart is empty';
        
        if (cart.length === 0) {
            cartItems.innerHTML = `<p class="text-center py-8" style="color: var(--gray-text)">${emptyText}</p>`;
        } else {
            cartItems.innerHTML = cart.map(item => {
                const cityName = item.cityName[currentLang] || item.cityName.ar || item.cityName;
                const productName = item.name[currentLang] || item.name.ar || item.name;
                
                return `
                <div class="flex gap-4 border-b pb-4" style="border-color: var(--border-color)">
                    <img src="${item.image}" alt="${productName}" class="w-20 h-20 rounded object-cover">
                    <div class="flex-1">
                        <h4 class="font-bold text-sm">${productName}</h4>
                        <p class="text-xs" style="color: var(--gray-text)">${cityName}</p>
                        <p class="text-xs">
                            ${currentLang === 'ar' ? 'اللون:' : 'Color:'} 
                            <span class="inline-block w-4 h-4 rounded-full" style="background: ${item.selectedColor}"></span>
                        </p>
                        <p class="text-xs">${currentLang === 'ar' ? 'المقاس:' : 'Size:'} ${item.selectedSize}</p>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="changeQuantity(${item.id}, '${item.selectedColor}', '${item.selectedSize}', -1)" 
                                    class="w-6 h-6 rounded font-bold text-sm" style="background: var(--gray-bg)">-</button>
                            <span class="text-sm font-bold">${item.quantity}</span>
                            <button onclick="changeQuantity(${item.id}, '${item.selectedColor}', '${item.selectedSize}', 1)" 
                                    class="w-6 h-6 rounded font-bold text-sm" style="background: var(--gray-bg)">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm">${currency.symbol}${(item.price * item.quantity * currency.rate).toFixed(2)}</p>
                        <button onclick="removeFromCart(${item.id}, '${item.selectedColor}', '${item.selectedSize}')" 
                                class="text-red-500 text-xs mt-2">${currentLang === 'ar' ? 'حذف' : 'Delete'}</button>
                    </div>
                </div>
            `}).join('');
        }
        
        const cartTotal = document.getElementById('cartTotal');
        if (cartTotal) {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotal.textContent = `${currency.symbol}${(total * currency.rate).toFixed(2)}`;
        }
    }

    function changeQuantity(productId, color, size, change) {
        const item = cart.find(i => i.id === productId && i.selectedColor === color && i.selectedSize === size);
        if (item) {
            item.quantity += change;
            if (item.quantity <= 0) {
                removeFromCart(productId, color, size);
            } else {
                localStorage.setItem('cart', JSON.stringify(cart));
                updateCartDisplay();
            }
        }
    }

    function removeFromCart(productId, color, size) {
        cart = cart.filter(item => !(item.id === productId && item.selectedColor === color && item.selectedSize === size));
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
    }

    function openCart() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.add('active');
        }
    }

    function closeCart() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.remove('active');
        }
    }

    function proceedToCheckout() {
        if (cart.length === 0) {
            alert(currentLang === 'ar' ? 'السلة فارغة!' : 'Cart is empty!');
            return;
        }
        window.location.href = '/checkout';
    }

    // Search Functionality
    function handleSearch(e) {
        const searchTerm = e.target.value.toLowerCase();
        const cityElements = document.querySelectorAll('.city-section');
        
        cityElements.forEach(cityEl => {
            const cityName = cityEl.querySelector('.city-title').textContent.toLowerCase();
            const products = cityEl.querySelectorAll('.product-name');
            let hasMatch = cityName.includes(searchTerm);
            
            products.forEach(product => {
                if (product.textContent.toLowerCase().includes(searchTerm)) {
                    hasMatch = true;
                }
            });
            
            cityEl.style.display = hasMatch ? 'block' : 'none';
        });
    }

    // Throttle scroll events for better performance
    let scrollTimeout;
    function throttledScrollHandler() {
        if (scrollTimeout) {
            return;
        }
        scrollTimeout = setTimeout(() => {
            handleScroll();
            scrollTimeout = null;
        }, 200);
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchInputMobile = document.getElementById('searchInputMobile');
        
        if (searchInput) {
            searchInput.addEventListener('input', handleSearch);
        }
        
        if (searchInputMobile) {
            searchInputMobile.addEventListener('input', handleSearch);
        }

        // Initialize
        renderCities();
        updateCartDisplay();

        // Add scroll event listener for infinite scroll
        window.addEventListener('scroll', throttledScrollHandler);
        
        // Also check on initial load if we need to load more (in case page is already scrolled)
        setTimeout(() => {
            handleScroll();
        }, 500);
    });
</script>

