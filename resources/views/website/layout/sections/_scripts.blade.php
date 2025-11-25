<script>
    // Language & Theme Management
    let currentLang = localStorage.getItem('language') || '{{ app()->getLocale() }}';
    let currentTheme = localStorage.getItem('theme') || 'light';
    const langSwitchEndpoint = @json(url('/lang'));

    // Apply saved preferences
    document.documentElement.lang = currentLang;
    document.documentElement.dir = currentLang === 'ar' ? 'rtl' : 'ltr';
    localStorage.setItem('language', currentLang);
    document.documentElement.setAttribute('data-theme', currentTheme);

    const langBtnEl = document.getElementById('langBtn');
    if (langBtnEl) {
        langBtnEl.textContent = currentLang === 'ar' ? 'EN' : 'ع';
    }

    // Toggle Language
    function toggleLanguage(targetLocale = null) {
        currentLang = targetLocale || (currentLang === 'ar' ? 'en' : 'ar');
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
        if (langBtnEl) {
            langBtnEl.textContent = currentLang === 'ar' ? 'EN' : 'ع';
        }
        
        if (typeof renderCities === 'function') {
            renderCities();
        }

        fetch(`${langSwitchEndpoint}/${currentLang}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        }).finally(() => window.location.reload());
    }

    // Toggle Theme
    function toggleTheme() {
        currentTheme = currentTheme === 'light' ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', currentTheme);
        localStorage.setItem('theme', currentTheme);
        
        const themeBtnIcon = document.getElementById('themeBtnIcon');
        if (themeBtnIcon) {
            themeBtnIcon.textContent = currentTheme === 'light' ? 'dark_mode' : 'light_mode';
        }
    }

    // Initialize theme button
    if (document.getElementById('themeBtnIcon')) {
        document.getElementById('themeBtnIcon').textContent = currentTheme === 'light' ? 'dark_mode' : 'light_mode';
    }

    // Data Structure with translations - Loaded from database
    let cities = @json($cities ?? []);
    const citiesMeta = @json($citiesMeta ?? null);
    let citiesPage = citiesMeta ? citiesMeta.currentPage : 1;
    let citiesLastPage = citiesMeta ? citiesMeta.lastPage : 1;
    let loadingCities = false;
    
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
        const packageText = currentLang === 'ar' ? 'باقة' : 'Package';
        
        // Check if product has variants
        const hasColors = product.colors && product.colors.length > 0;
        const hasSizes = product.sizes && product.sizes.length > 0;
        
        // Use null for no variants
        const defaultColor = hasColors ? product.colors[0] : null;
        const defaultSize = hasSizes ? product.sizes[0] : null;
        const inCart = cart.some(i => i.id === product.id && i.selectedColor === defaultColor && i.selectedSize === defaultSize);
        const addToCartText = inCart ? (currentLang === 'ar' ? 'إلى الدفع' : 'Checkout') : (currentLang === 'ar' ? 'أضف للسلة' : 'Add to Cart');
        
        return `
            <div class="product-card" data-product-id="${product.id}" data-city-id="${cityId}" style="display: flex; flex-direction: column; height: 100%; border-radius: 16px; background: var(--md-surface-bright); box-shadow: 0 1px 2px rgba(0,0,0,0.04); overflow: hidden; transition: all 0.5s ease;">
                <div class="relative" style="position: relative; overflow: hidden; background: linear-gradient(to bottom right, #f1f5e8, #f8faf4); height: 288px;">
                    ${product.isPackage ? `
                        <div class="package-badge-trigger" data-product-id="${product.id}" style="position: absolute; top: 1rem; right: 1rem; z-index: 20; padding: 0.5rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: bold; display: flex; align-items: center; gap: 0.375rem; backdrop-filter: blur(12px); background: rgba(200, 212, 0, 0.95); color: #1F2612; cursor: pointer; transition: all 0.3s ease;">
                            <span class="material-icons-outlined" style="font-size: 18px;">card_giftcard</span>
                            <span>${packageText}</span>
                        </div>
                    ` : ''}
                    <a href="/product/${product.uuid || product.id}" style="display: block; width: 100%; height: 100%;">
                        <img src="${product.image || ''}" alt="${pName}" class="product-image" style="width: 100%; height: 100%; object-fit: cover; display: block; transition: transform 0.5s ease;" onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'400\'%3E%3Crect fill=\'%23f1f5e8\' width=\'400\' height=\'400\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' font-size=\'16\' fill=\'%23ccc\'%3ENo Image%3C/text%3E%3C/svg%3E'">
                    </a>
                </div>
                <div style="display: flex; flex-direction: column; flex: 1;">
                    <div style="padding: 1.25rem; flex: 1; display: flex; flex-direction: column; gap: 1rem;">
                        <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                            <h3 class="product-name" style="font-weight: bold; font-size: 1.125rem; line-height: 1.5; margin: 0; font-family: inherit; color: var(--md-on-surface);"><a href="/product/${product.uuid || product.id}" style="text-decoration: none; color: inherit;">${pName}</a></h3>
                            <p class="product-desc" style="color: var(--md-on-surface-variant); font-size: 0.875rem; margin: 0; line-height: 1.4;">
                                ${pDesc}
                            </p>
                        </div>

                        ${hasColors || hasSizes ? `
                        <div style="height: 1px; background: var(--md-outline-variant);"></div>
                        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                            ${hasColors ? `
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <p style="margin: 0; font-size: 0.875rem; font-weight: 500; color: var(--md-on-surface);">${currentLang === 'ar' ? 'الألوان' : 'Colors'}</p>
                                <div style="display: flex; gap: 0.625rem; flex-wrap: wrap; align-items: center;">
                                    ${product.colors.map((color, index) => `
                                        <button class="color-btn ${index === 0 ? 'active' : ''}" 
                                                style="width: 32px; height: 32px; background: ${color}; border: 2px solid ${index === 0 ? 'var(--md-primary)' : 'var(--md-outline)'}; border-radius: 9999px; flex-shrink: 0; cursor: pointer; transition: all 0.3s ease; box-shadow: ${index === 0 ? '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)' : '0 1px 3px rgba(0,0,0,0.1)'}; hover:scale-125;"
                                                onclick="selectColor(${product.id}, '${color}', this)"
                                                data-color="${color}"
                                                title="${color}"
                                                aria-label="Color: ${color}">
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                            
                            ${hasSizes ? `
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <p style="margin: 0; font-size: 0.875rem; font-weight: 500; color: var(--md-on-surface);">${currentLang === 'ar' ? 'الأحجام' : 'Sizes'}</p>
                                <div style="display: flex; flex-wrap: wrap; gap: 0.5rem; align-items: center;" id="sizes-${product.id}">
                                    ${product.sizes.map((size, index) => `
                                        <button class="size-btn ${index === 0 ? 'active' : ''}" 
                                                style="border: 2px solid ${index === 0 ? 'var(--md-primary)' : 'var(--md-outline)'}; padding: 0.375rem 0.75rem; border-radius: 0.5rem; font-size: 0.875rem; font-weight: 500; cursor: pointer; transition: all 0.3s ease; background: ${index === 0 ? 'var(--md-primary-container)' : 'transparent'}; color: ${index === 0 ? 'var(--md-on-primary-container)' : 'var(--md-on-surface)'};"
                                                onclick="selectSize(${product.id}, '${size}', this)"
                                                aria-label="Size: ${size}">
                                            ${size}
                                        </button>
                                    `).join('')}
                                </div>
                            </div>
                            ` : ''}
                        </div>
                        ` : ''}

                        <div style="height: 1px; background: var(--md-outline-variant); margin-top: auto;"></div>

                        <div style="display: flex; align-items: center; justify-content: space-between; gap: 0.75rem;">
                            <div style="display: flex; align-items: baseline; gap: 0.25rem;">
                                <span style="font-size: 1.25rem; font-weight: bold; color: var(--md-primary);" id="price-${product.id}">
                                    ${currency.symbol}${(product.price * currency.rate).toFixed(2)}
                                </span>
                                ${product.original_price && product.original_price > product.price ? `
                                <span style="font-size: 0.875rem; text-decoration: line-through; color: var(--md-on-surface-variant);">
                                    ${currency.symbol}${(product.original_price * currency.rate).toFixed(2)}
                                </span>
                                ` : ''}
                            </div>
                            <button class="add-to-cart-btn" 
                                    data-product-id="${product.id}"
                                    data-city-id="${cityId}"
                                    style="width: 48px; height: 48px; border-radius: 9999px; display: flex; align-items: center; justify-content: center; transition: all 0.3s ease; background: var(--md-primary); color: var(--md-on-primary); border: none; cursor: pointer; box-shadow: 0 2px 8px rgba(0,0,0,0.1);"
                                    title="${addToCartText}"
                                    aria-label="${addToCartText}">
                                <span class="material-icons-outlined" style="font-size: 24px;">add_shopping_cart</span>
                            </button>
                        </div>
                    </div>
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
                    <div class="mb-8 mt-12 flex items-center gap-4">
                        <h2 class="city-title md-headline-large font-bold flex-1" style="color: var(--md-on-surface);">${city.name[currentLang] || city.name.ar}</h2>
                        <div style="height: 3px; background: linear-gradient(90deg, var(--md-primary) 0%, var(--md-secondary) 100%); border-radius: 2px; width: 40px;"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid-${city.id}">
                        ${productsHtml}
                    </div>
                    ${loadingHtml}
                </div>
            `;
        }).join('');
        
        // Attach event listeners after rendering
        attachProductCardListeners();
    }

    // Append newly loaded cities to DOM
    function appendCities(newCities) {
        const container = document.getElementById('citiesContainer');
        if (!container || !newCities || newCities.length === 0) return;

        const html = newCities.map(city => {
            const productsHtml = city.products.map(product => renderProductCard(product, city.id)).join('');
            const loadingHtml = city.hasMore ? `
                <div class="load-more-container text-center mt-8" data-city-id="${city.id}">
                    <div class="loading-spinner" style="display: none;">
                        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2" style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                        <p class="mt-2" style="color: var(--gray-text)">${currentLang === 'ar' ? 'جاري التحميل...' : 'Loading...'}</p>
                    </div>
                </div>
            ` : '';

            return `
                <div class="city-section" data-city-id="${city.id}">
                    <div class="mb-8 mt-12 flex items-center gap-4">
                        <h2 class="city-title md-headline-large font-bold flex-1" style="color: var(--md-on-surface);">${city.name[currentLang] || city.name.ar}</h2>
                        <div style="height: 3px; background: linear-gradient(90deg, var(--md-primary) 0%, var(--md-secondary) 100%); border-radius: 2px; width: 40px;"></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid-${city.id}">
                        ${productsHtml}
                    </div>
                    ${loadingHtml}
                </div>
            `;
        }).join('');

        container.insertAdjacentHTML('beforeend', html);
        
        // Attach event listeners to newly added product cards
        attachProductCardListeners();
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

    // Load next page of cities
    async function loadMoreCities() {
        if (loadingCities) return;
        if (citiesPage >= citiesLastPage) return;
        loadingCities = true;
        const nextPage = citiesPage + 1;

        try {
            const response = await fetch(`/api/cities?page=${nextPage}`);
            const data = await response.json();

            if (data.success && data.cities && data.cities.length > 0) {
                // Update local state
                data.cities.forEach(city => {
                    cityPagination[city.id] = {
                        currentPage: 1,
                        hasMore: city.hasMore || false,
                        loading: false
                    };
                });
                cities = [...cities, ...data.cities];
                appendCities(data.cities);

                citiesPage = data.currentPage;
                citiesLastPage = data.lastPage;
            }
        } catch (e) {
            console.error('Error loading more cities', e);
        } finally {
            loadingCities = false;
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
                        return;
                    }
                }
            }

            // If no city needs more products, try loading more cities
            if (citiesPage < citiesLastPage && !loadingCities) {
                loadMoreCities();
            }
        }
    }

    function selectColor(productId, color, btn) {
        const colorButtons = btn.parentElement.querySelectorAll('.color-btn');
        colorButtons.forEach(b => {
            b.style.borderColor = 'var(--md-outline)';
            b.style.boxShadow = '0 1px 3px rgba(0,0,0,0.1)';
            b.classList.remove('active');
        });
        btn.classList.add('active');
        btn.style.borderColor = 'var(--md-primary)';
        btn.style.boxShadow = '0 0 0 2px var(--md-surface-bright), 0 0 8px rgba(200, 212, 0, 0.3)';
        // Update button state when color changes
        updateProductButtonState(productId);
    }

    function selectSize(productId, size, btn) {
        const sizeButtons = btn.parentElement.querySelectorAll('.size-btn');
        sizeButtons.forEach(b => {
            b.style.borderColor = 'var(--md-outline)';
            b.style.background = 'transparent';
            b.style.color = 'var(--md-on-surface)';
            b.classList.remove('active');
        });
        btn.classList.add('active');
        btn.style.borderColor = 'var(--md-primary)';
        btn.style.background = 'var(--md-primary-container)';
        btn.style.color = 'var(--md-on-primary-container)';
        // Update button state when size changes
        updateProductButtonState(productId);
    }

    function updateProductButtonState(productId) {
        // Find the product card
        const productCard = document.querySelector(`button[data-product-id="${productId}"]`)?.closest('.product-card');
        if (!productCard) return;
        
        // Get selected variants (or null if no variants)
        const colorBtn = productCard.querySelector('.color-btn.active');
        const sizeBtn = productCard.querySelector('.size-btn.active');
        
        const selectedColor = colorBtn ? colorBtn.dataset.color : null;
        const selectedSize = sizeBtn ? sizeBtn.textContent.trim() : null;
        
        // Check if this exact variant is in cart
        const isInCart = cart.some(item => 
            item.id === productId && 
            item.selectedColor === selectedColor && 
            item.selectedSize === selectedSize
        );
        
        // Update button with visual feedback and icon change
        const btn = document.querySelector(`.add-to-cart-btn[data-product-id="${productId}"]`);
        if (btn) {
            const icon = btn.querySelector('.material-icons-outlined');
            if (isInCart) {
                // Change to checkout icon
                if (icon) {
                    icon.textContent = 'done_all';
                    icon.style.color = '#4CAF50';
                }
                btn.style.opacity = '0.85';
                btn.title = currentLang === 'ar' ? 'في السلة' : 'In Cart';
                btn.style.pointerEvents = 'auto';
            } else {
                // Change back to shopping cart icon
                if (icon) {
                    icon.textContent = 'add_shopping_cart';
                    icon.style.color = 'var(--md-on-primary)';
                }
                btn.style.opacity = '1';
                btn.title = currentLang === 'ar' ? 'أضف للسلة' : 'Add to Cart';
                btn.style.pointerEvents = 'auto';
            }
        }
    }

    function addToCart(cityId, productId) {
        // Parse as integers to handle both string and number inputs
        const parsedCityId = parseInt(cityId);
        const parsedProductId = parseInt(productId);
        
        const city = cities.find(c => c.id === parsedCityId);
        const product = city?.products.find(p => p.id === parsedProductId);
        
        if (!city || !product) {
            console.error('City or product not found', { cityId: parsedCityId, productId: parsedProductId, cities });
            if (window.Swal) {
                Swal.fire({
                    title: currentLang === 'ar' ? 'خطأ' : 'Error',
                    text: currentLang === 'ar' ? 'المنتج أو المدينة غير موجودة' : 'Product or city not found',
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
        
        // Find the product card
        const productCard = document.querySelector(`.product-card[data-product-id="${parsedProductId}"]`);
        if (!productCard) {
            console.error('Product card not found');
            return;
        }
        
        // Get selected color from the card
        if (hasColors) {
            const colorBtn = productCard.querySelector('.color-btn.active');
            selectedColor = colorBtn ? colorBtn.getAttribute('data-color') : product.colors[0];
        }
        
        // Get selected size from the card
        if (hasSizes) {
            const sizeBtn = productCard.querySelector('.size-btn.active');
            selectedSize = sizeBtn ? sizeBtn.textContent.trim() : product.sizes[0];
        }
        
        // Check if this item already exists in cart
        const existingItem = cart.find(item => 
            item.id === parsedProductId && 
            item.selectedColor === selectedColor && 
            item.selectedSize === selectedSize
        );
        
        if (existingItem) {
            existingItem.quantity++;
        } else {
            const cartItem = {
                ...product,
                cityName: city.name,
                selectedColor,
                selectedSize,
                quantity: 1,
                isPackage: product.isPackage || false
            };
            cart.push(cartItem);
        }
        
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        
        // Find and update the button with animation
        const btn = productCard.querySelector('.add-to-cart-btn');
        if (btn) {
            btn.style.transform = 'scale(0.85)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 200);
        }
        
        // Show success feedback
        if (window.Swal) {
            Swal.fire({
                title: currentLang === 'ar' ? 'تم الإضافة' : 'Added',
                text: currentLang === 'ar' ? 'تمت إضافة المنتج للسلة' : 'Product added to cart',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }
        
        // Trigger custom event for product pages to listen
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
    }
    
    // Attach event listeners to product cards
    function attachProductCardListeners() {
        // Add to cart button listeners
        document.querySelectorAll('.add-to-cart-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const cityId = this.getAttribute('data-city-id');
                const productId = this.getAttribute('data-product-id');
                addToCart(cityId, productId);
            });
        });
        
        // Package badge hover and long-tap listeners
        document.querySelectorAll('.package-badge-trigger').forEach(badge => {
            const productId = badge.getAttribute('data-product-id');
            const productCard = badge.closest('.product-card');
            let touchStartTime = 0;
            let touchStartX = 0;
            let touchStartY = 0;
            
            // Hover event for desktop
            badge.addEventListener('mouseenter', function() {
                showPackageDetails(productId);
            });
            
            badge.addEventListener('mouseleave', function() {
                hidePackageDetails();
            });
            
            // Long-tap for mobile
            badge.addEventListener('touchstart', function(e) {
                touchStartTime = Date.now();
                touchStartX = e.touches[0].clientX;
                touchStartY = e.touches[0].clientY;
            });
            
            badge.addEventListener('touchend', function(e) {
                const touchEndTime = Date.now();
                const touchDuration = touchEndTime - touchStartTime;
                const touchX = e.changedTouches[0].clientX;
                const touchY = e.changedTouches[0].clientY;
                const touchDistance = Math.sqrt(
                    Math.pow(touchX - touchStartX, 2) + Math.pow(touchY - touchStartY, 2)
                );
                
                // Long tap if held for 500ms and minimal movement
                if (touchDuration >= 500 && touchDistance < 10) {
                    showPackageDetails(productId);
                    e.preventDefault();
                }
            });
        });
    }
    
    // Show package details tooltip
    function showPackageDetails(productId) {
        const product = findProductById(productId);
        if (!product || !product.isPackage) return;
        
        // Remove existing tooltip
        const existingTooltip = document.getElementById('package-tooltip');
        if (existingTooltip) {
            existingTooltip.remove();
        }
        
        // Create tooltip
        const tooltip = document.createElement('div');
        tooltip.id = 'package-tooltip';
        tooltip.style.cssText = `
            position: fixed;
            z-index: 1000;
            background: var(--md-surface-container-highest);
            border: 1px solid var(--md-outline);
            border-radius: 12px;
            padding: 1rem;
            max-width: 300px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.15);
            backdrop-filter: blur(12px);
            animation: slideInUp 0.3s ease;
        `;
        
        const packageInfo = product.package_details || product.name[currentLang] || product.name.ar;
        const title = currentLang === 'ar' ? 'تفاصيل الباقة' : 'Package Details';
        
        tooltip.innerHTML = `
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 1rem;">
                <div>
                    <h4 style="margin: 0 0 0.5rem 0; font-weight: 600; color: var(--md-on-surface);">${title}</h4>
                    <p style="margin: 0; font-size: 0.875rem; color: var(--md-on-surface-variant); line-height: 1.5;">
                        ${packageInfo}
                    </p>
                </div>
                <button onclick="document.getElementById('package-tooltip').remove()" 
                        style="background: none; border: none; cursor: pointer; color: var(--md-on-surface-variant); flex-shrink: 0;">
                    <span class="material-icons-outlined" style="font-size: 20px;">close</span>
                </button>
            </div>
        `;
        
        document.body.appendChild(tooltip);
        
        // Position tooltip near the badge
        const badge = document.querySelector(`.package-badge-trigger[data-product-id="${productId}"]`);
        if (badge) {
            const rect = badge.getBoundingClientRect();
            tooltip.style.top = (rect.bottom + 10) + 'px';
            tooltip.style.left = Math.min(rect.left - 100, window.innerWidth - 320) + 'px';
        }
        
        // Auto-hide after 3 seconds if not hovering
        setTimeout(() => {
            const tooltip = document.getElementById('package-tooltip');
            if (tooltip && !tooltip.matches(':hover')) {
                tooltip.remove();
            }
        }, 3000);
    }
    
    // Hide package details
    function hidePackageDetails() {
        const tooltip = document.getElementById('package-tooltip');
        if (tooltip) {
            tooltip.style.opacity = '0';
            tooltip.style.transition = 'opacity 0.2s ease';
            setTimeout(() => tooltip.remove(), 200);
        }
    }
    
    // Helper function to find product by ID
    function findProductById(productId) {
        const parsedId = parseInt(productId);
        for (let city of cities) {
            const product = city.products.find(p => p.id === parsedId);
            if (product) return product;
        }
        return null;
    }

    function updateCartDisplay() {
        const cartCountEl = document.getElementById('cartCount');
        if (cartCountEl) {
            const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            cartCountEl.textContent = cartCount;
            cartCountEl.style.display = cartCount > 0 ? 'flex' : 'none';
        }
        
        const cartItems = document.getElementById('cartItems');
        const itemsCountEl = document.getElementById('cartItemsCount');
        const checkoutBtn = document.getElementById('checkoutBtn');
        if (!cartItems) return;
        
        const emptyText = currentLang === 'ar' ? 'السلة فارغة' : 'Cart is empty';
        
        if (cart.length === 0) {
            cartItems.innerHTML = `<div class="text-center py-12" style="color: var(--gray-text)">
                <p>${emptyText}</p>
                <button onclick="closeCart()" class="mt-4 px-4 py-2 rounded" style="background: var(--gray-bg)">
                    ${currentLang === 'ar' ? 'تابع التسوق' : 'Continue shopping'}
                </button>
            </div>`;
        } else {
            cartItems.innerHTML = cart.map((item, index) => {
                const cityName = item.cityName[currentLang] || item.cityName.ar || item.cityName;
                const productName = item.name[currentLang] || item.name.ar || item.name;
                const productImage = item.image || 'https://placehold.co/80x80/jpg?text=No+Image';
                const packageText = currentLang === 'ar' ? 'باقة' : 'Package';
                
                return `
                <div class="flex gap-4 border-b pb-4" style="border-color: var(--border-color)" data-cart-index="${index}">
                    <div style="position: relative; flex-shrink: 0;">
                        <img src="${productImage}" alt="${productName}" class="w-20 h-20 rounded object-cover" style="border-radius: 8px;" onerror="this.src='https://placehold.co/80x80/jpg?text=No+Image';">
                        ${item.isPackage ? `
                            <div style="position: absolute; top: -4px; right: -4px; padding: 2px 6px; border-radius: 9999px; font-size: 0.65rem; font-weight: bold; background: var(--md-primary); color: var(--md-on-primary); display: flex; align-items: center; gap: 2px;">
                                <span class="material-icons-outlined" style="font-size: 12px;">card_giftcard</span>
                                <span>${packageText}</span>
                            </div>
                        ` : ''}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-sm" style="color: var(--md-on-surface);">${productName}</h4>
                        <p class="text-xs" style="color: var(--md-on-surface-variant);">${cityName}</p>
                        ${item.selectedColor ? `
                        <p class="text-xs" style="color: var(--md-on-surface-variant); margin-top: 4px;">
                            ${currentLang === 'ar' ? 'اللون:' : 'Color:'} 
                            <span class="inline-block w-4 h-4 rounded-full" style="background: ${item.selectedColor}; border: 2px solid var(--md-outline); vertical-align: middle;"></span>
                        </p>
                        ` : ''}
                        ${item.selectedSize ? `<p class="text-xs" style="color: var(--md-on-surface-variant);">${currentLang === 'ar' ? 'المقاس:' : 'Size:'} ${item.selectedSize}</p>` : ''}
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="changeQuantityByIndex(${index}, -1)" 
                                    class="w-6 h-6 rounded font-bold text-sm" style="background: var(--md-primary-container); color: var(--md-on-primary-container); border: none; cursor: pointer;">−</button>
                            <span class="text-sm font-bold" style="min-width: 24px; text-align: center;">${item.quantity}</span>
                            <button onclick="changeQuantityByIndex(${index}, 1)" 
                                    class="w-6 h-6 rounded font-bold text-sm" style="background: var(--md-primary-container); color: var(--md-on-primary-container); border: none; cursor: pointer;">+</button>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-bold text-sm" style="color: var(--md-primary);">${currency.symbol}${(item.price * item.quantity * currency.rate).toFixed(2)}</p>
                        <button onclick="removeFromCartByIndex(${index})" 
                                class="text-red-500 text-xs mt-2 hover:text-red-700 transition-colors" style="background: none; border: none; cursor: pointer;">${currentLang === 'ar' ? 'حذف' : 'Delete'}</button>
                    </div>
                </div>
            `}).join('');
        }
        
        const cartTotal = document.getElementById('cartTotal');
        if (cartTotal) {
            const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
            cartTotal.textContent = `${currency.symbol}${(total * currency.rate).toFixed(2)}`;
        }
        if (itemsCountEl) {
            const itemsCount = cart.reduce((sum, item) => sum + item.quantity, 0);
            itemsCountEl.textContent = itemsCount;
        }
        if (checkoutBtn) {
            checkoutBtn.disabled = cart.length === 0;
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
                // Trigger custom event for product pages to listen
                window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
            }
        }
    }

    function removeFromCart(productId, color, size) {
        cart = cart.filter(item => !(item.id === productId && item.selectedColor === color && item.selectedSize === size));
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        // Update button on home page
        updateProductButtonState(productId);
        // Trigger custom event for product pages to listen
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
    }

    function clearCart() {
        const productIds = [...new Set(cart.map(item => item.id))];
        cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartDisplay();
        // Update all product buttons on home page
        productIds.forEach(id => updateProductButtonState(id));
        // Trigger custom event for product pages to listen
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: cart }));
    }

    function openCart() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.add('active');
        }
        // Refresh cart display when opening to show latest data
        updateCartDisplay();
        document.body.style.overflow = 'hidden';
        document.addEventListener('keydown', escCloseCartHandler);
    }

    function closeCart() {
        const cartModal = document.getElementById('cartModal');
        if (cartModal) {
            cartModal.classList.remove('active');
        }
        document.body.style.overflow = '';
        document.removeEventListener('keydown', escCloseCartHandler);
    }

    function escCloseCartHandler(e) {
        if (e.key === 'Escape') {
            closeCart();
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

        // Listen for real-time cart updates from other pages or components
        window.addEventListener('cartUpdated', function(e) {
            cart = e.detail || [];
            updateCartDisplay();
        });

        // Listen for localStorage changes (cross-tab updates)
        window.addEventListener('storage', function(e) {
            if (e.key === 'cart') {
                cart = JSON.parse(e.newValue || '[]');
                updateCartDisplay();
            }
        });
    });
</script>

