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

    // Data Structure with translations
    let cities = [
        {
            id: 1,
            name: { ar: 'غزة', en: 'Gaza' },
            products: [
                {
                    id: 101,
                    name: { ar: 'هودي غزة الكلاسيكي', en: 'Gaza Classic Hoodie' },
                    description: { ar: 'تصميم عصري يعبر عن صمود وجمال غزة', en: 'Modern design expressing Gaza\'s resilience and beauty' },
                    price: 49.99,
                    image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop',
                    colors: ['#000000', '#FFFFFF', '#C8D400'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 102,
                    name: { ar: 'هودي غزة العتيقة', en: 'Gaza Heritage Hoodie' },
                    description: { ar: 'يحمل روح التاريخ والأصالة', en: 'Carries the spirit of history and authenticity' },
                    price: 54.99,
                    image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=500&h=500&fit=crop',
                    colors: ['#000000', '#8B4513', '#2F4F4F'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 103,
                    name: { ar: 'هودي غزة المودرن', en: 'Gaza Modern Hoodie' },
                    description: { ar: 'تصميم عصري وجريء', en: 'Contemporary and bold design' },
                    price: 52.99,
                    image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500&h=500&fit=crop',
                    colors: ['#1E3A8A', '#DC2626', '#C8D400'],
                    sizes: ['S', 'M', 'L', 'XL'],
                    isPackage: true
                }
            ]
        },
        {
            id: 2,
            name: { ar: 'القدس', en: 'Jerusalem' },
            products: [
                {
                    id: 201,
                    name: { ar: 'هودي القدس التراثي', en: 'Jerusalem Heritage Hoodie' },
                    description: { ar: 'يحمل عبق التاريخ والقدسية', en: 'Carries the fragrance of history and sanctity' },
                    price: 59.99,
                    image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=500&h=500&fit=crop',
                    colors: ['#DAA520', '#000000', '#FFFFFF'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 202,
                    name: { ar: 'هودي القدس المقدسة', en: 'Jerusalem Sacred Hoodie' },
                    description: { ar: 'تصميم يليق بأولى القبلتين', en: 'Design worthy of the first qibla' },
                    price: 64.99,
                    image: 'https://images.unsplash.com/photo-1556821840-3a63f95609a7?w=500&h=500&fit=crop',
                    colors: ['#000000', '#C8D400', '#8B4513'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 203,
                    name: { ar: 'هودي القدس الذهبية', en: 'Jerusalem Golden Hoodie' },
                    description: { ar: 'كقبة الصخرة في جمالها', en: 'Like the Dome of the Rock in its beauty' },
                    price: 69.99,
                    image: 'https://images.unsplash.com/photo-1620799139652-715e4d5b232d?w=500&h=500&fit=crop',
                    colors: ['#DAA520', '#1E3A8A', '#FFFFFF'],
                    sizes: ['M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 204,
                    name: { ar: 'هودي القدس العريقة', en: 'Jerusalem Ancient Hoodie' },
                    description: { ar: 'عراقة وأصالة لا تنتهي', en: 'Endless heritage and authenticity' },
                    price: 62.99,
                    image: 'https://images.unsplash.com/photo-1578587018452-892bacefd3f2?w=500&h=500&fit=crop',
                    colors: ['#8B4513', '#000000', '#C8D400'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                }
            ]
        },
        {
            id: 3,
            name: { ar: 'الخليل', en: 'Hebron' },
            products: [
                {
                    id: 301,
                    name: { ar: 'هودي الخليل الأصيل', en: 'Hebron Authentic Hoodie' },
                    description: { ar: 'يعكس عراقة مدينة الخليل', en: 'Reflects Hebron\'s ancient heritage' },
                    price: 54.99,
                    image: 'https://images.unsplash.com/photo-1620799140408-edc6dcb6d633?w=500&h=500&fit=crop',
                    colors: ['#000000', '#8B4513', '#FFFFFF'],
                    sizes: ['S', 'M', 'L', 'XL', 'XXL'],
                    isPackage: true
                },
                {
                    id: 302,
                    name: { ar: 'هودي الخليل التراثي', en: 'Hebron Heritage Hoodie' },
                    description: { ar: 'تراث وتاريخ في قطعة واحدة', en: 'Heritage and history in one piece' },
                    price: 57.99,
                    image: 'https://images.unsplash.com/photo-1618354691373-d851c5c3a990?w=500&h=500&fit=crop',
                    colors: ['#2F4F4F', '#C8D400', '#000000'],
                    sizes: ['S', 'M', 'L', 'XL'],
                    isPackage: true
                }
            ]
        }
    ];

    // Load from localStorage if exists
    const savedCities = localStorage.getItem('cities');
    if (savedCities) {
        cities = JSON.parse(savedCities);
    } else {
        localStorage.setItem('cities', JSON.stringify(cities));
    }

    let cart = JSON.parse(localStorage.getItem('cart')) || [];
    let currency = JSON.parse(localStorage.getItem('currency')) || { symbol: '$', rate: 1 };

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

        container.innerHTML = cities.map(city => `
            <div class="city-section">
                <h2 class="city-title">${city.name[currentLang] || city.name.ar}</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    ${city.products.map(product => {
                        const pName = product.name[currentLang] || product.name.ar || product.name;
                        const pDesc = product.description[currentLang] || product.description.ar || product.description;
                        const packageText = currentLang === 'ar' ? 'بكج كامل 📦' : 'Full Package 📦';
                        const addToCartText = currentLang === 'ar' ? 'أضف للسلة' : 'Add to Cart';
                        
                        return `
                        <div class="product-card">
                            <div class="relative">
                                ${product.isPackage ? `<div class="package-badge">${packageText}</div>` : ''}
                                <img src="${product.image}" alt="${pName}" class="product-image">
                            </div>
                            <div class="product-info">
                                <h3 class="product-name">${pName}</h3>
                                <p class="product-desc">${pDesc}</p>
                                
                                <div class="flex gap-2 mb-3">
                                    ${product.colors.map((color, index) => `
                                        <button class="color-btn ${index === 0 ? 'active' : ''}" 
                                                style="background: ${color}"
                                                onclick="selectColor(${product.id}, '${color}', this)"
                                                data-color="${color}">
                                        </button>
                                    `).join('')}
                                </div>
                                
                                <div class="flex flex-wrap gap-2 mb-3" id="sizes-${product.id}">
                                    ${product.sizes.map((size, index) => `
                                        <button class="size-btn ${index === 0 ? 'active' : ''}" 
                                                onclick="selectSize(${product.id}, '${size}', this)">
                                            ${size}
                                        </button>
                                    `).join('')}
                                </div>
                                
                                <div class="text-xl font-bold mb-3">${currency.symbol}${(product.price * currency.rate).toFixed(2)}</div>
                                <button onclick="addToCart(${city.id}, ${product.id})" class="btn-yellow">
                                    ${addToCartText}
                                </button>
                            </div>
                        </div>
                    `}).join('')}
                </div>
            </div>
        `).join('');
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
    });
</script>

