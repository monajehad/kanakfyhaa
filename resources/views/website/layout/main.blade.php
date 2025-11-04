<!doctype html>
<html lang="ar" dir="rtl">

<head>
    @include('website.layout.sections._head')
</head>

<body>
    {{-- عرض شريط الأخبار. تأكد أن متغير $newsBar معرف بشكل صحيح في الكنترولر ويتم تمريره إلى هذا العرض --}}
    @if(isset($newsBar))
        @include('website.layout.home._news_bar', ['newsBar' => $newsBar])
    @else
        {{-- ملاحظة: لم يتم إرسال متغير $newsBar إلى هذا العرض، لذلك لن يظهر شريط الأخبار --}}
    @endif

    <!-- ==================== Header ============================ -->
    <header class="main-header">
        <div class="container">
            <!-- Logo -->
            <div class="logo">
                <img src="assets/icons/logo.svg" alt="كأنك فيها" />
            </div>

            <!-- Navigation -->
            <nav class="nav-links" id="navLinks">
                <a href="#">خدماتنا</a>
                <a href="#">من نحن؟</a>
                <a href="#">اكتشف</a>
                <a href="#">التصنيفات</a>
                <a href="#">منتجاتنا</a>
            </nav>

            <!-- Actions -->
            <div class="actions">
                <!-- Search Button -->
                <button class="search-btn" id="searchBtn" aria-label="بحث">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8" />
                        <path d="m21 21-4.3-4.3" />
                    </svg>
                </button>

                <!-- Mobile Menu -->
                <button class="menu-toggle" id="menuToggle">☰</button>
            </div>
        </div>

        <!-- Search Overlay -->
        <div class="search-overlay" id="searchOverlay">
            <button class="close-overlay" id="closeSearch" aria-label="إغلاق">✕</button>

            <form class="search-box" id="searchForm">
                <div class="search-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none"
                        stroke="#C7DB09" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="10.5" cy="10.5" r="7.5" />
                        <path d="m21 21-5.2-5.2" />
                    </svg>
                </div>

                <input type="text" id="searchInput" name="q" placeholder="ابحث عن منتج، مدينة أو قصة..."
                    required />

                <button type="submit" class="search-submit" aria-label="بحث">
                    <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" stroke="#111"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10H7" />
                        <path d="M14 3l7 7-7 7" />
                    </svg>
                </button>
            </form>
        </div>
    </header>


    <!-- ============================ =============================== -->
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="swiper hero-slider">
            <div class="swiper-wrapper">
                <!-- Slide 1 -->
                <div class="swiper-slide hero-slide" style="background-image:url('assets/images/slide-01.png');">
                    <div class="hero-content">
                        <h1><span class="highlight">ارتدي</span> العالم</h1>
                        <p>اختر تيشيرتك المفضل بتصميم مستوحى من أجمل المدن وكن بطل القصة!</p>
                        <a href="#" class="btn-primary">اكتشف الآن</a>
                    </div>
                </div>

                <!-- Slide 2 -->
                <div class="swiper-slide hero-slide" style="background-image:url('assets/images/slide-01.png');">
                    <div class="hero-content">
                        <h1><span class="highlight">كأنك</span> فيها</h1>
                        <p>عيش تجربة افتراضية مستوحاة من التراث والمدن العربية بتصاميم فريدة.</p>
                        <a href="#" class="btn-primary">تسوق الآن</a>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </section>


    <!-- ====================================== Categories ==================== -->


    <section class="categories-section">
        <div class="categories-header">
            <h2>تصنيفات</h2>
            <a href="#" class="see-more">عرض المزيد &lt;</a>
        </div>

        <div class="categories-grid">
            <div class="category-card">
                <div class="icon">
                    <img src="assets/images/category-item1.svg" alt="إكسسوارات">
                </div>
                <h3>إكسسوارات</h3>
            </div>

            <div class="category-card">
                <div class="icon">
                    <img src="assets/images/category-item1.svg" alt="حقائب">
                </div>
                <h3>حقائب</h3>
            </div>

            <div class="category-card">
                <div class="icon">
                    <img src="assets/images/category-item1.svg" alt="أحذية">
                </div>
                <h3>أحذية</h3>
            </div>

            <div class="category-card">
                <div class="icon">
                    <img src="assets/images/category-item1.svg" alt="قبعات">
                </div>
                <h3>قبعات</h3>
            </div>

            <div class="category-card">
                <div class="icon">
                    <img src="assets/images/category-item1.svg" alt="ملابس">
                </div>
                <h3>ملابس</h3>
            </div>
        </div>
    </section>

    <!-- ============================ Products ================================= -->


    <section class="products-section">
        <div class="products-header">
            <h2>الأكثر مبيعًا</h2>
            <a href="#" class="see-more">عرض المزيد &lt;</a>
        </div>

        <div class="products-grid">
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>عِش الواقع كأنك فيها مع مسح الـ QR</p>
                    <div class="rating">★★★★★</div>
                    <div class="price">$80.00</div>
                </div>
            </div>

            <!-- يمكنك تكرار البطاقات -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>تصميم فريد مستوحى من المدن العربية</p>
                    <div class="rating">★★★★☆</div>
                    <div class="price">$80.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>عِش الواقع كأنك فيها مع مسح الـ QR</p>
                    <div class="rating">★★★★★</div>
                    <div class="price">$80.00</div>
                </div>
            </div>

            <!-- يمكنك تكرار البطاقات -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>تصميم فريد مستوحى من المدن العربية</p>
                    <div class="rating">★★★★☆</div>
                    <div class="price">$80.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>عِش الواقع كأنك فيها مع مسح الـ QR</p>
                    <div class="rating">★★★★★</div>
                    <div class="price">$80.00</div>
                </div>
            </div>

            <!-- يمكنك تكرار البطاقات -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>تصميم فريد مستوحى من المدن العربية</p>
                    <div class="rating">★★★★☆</div>
                    <div class="price">$80.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>عِش الواقع كأنك فيها مع مسح الـ QR</p>
                    <div class="rating">★★★★★</div>
                    <div class="price">$80.00</div>
                </div>
            </div>

            <!-- يمكنك تكرار البطاقات -->
            <div class="product-card">
                <div class="product-img">
                    <img src="assets/images/product-item1.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>

                <div class="product-body">
                    <h3>هودي قبة الصخرة</h3>
                    <p>تصميم فريد مستوحى من المدن العربية</p>
                    <div class="rating">★★★★☆</div>
                    <div class="price">$80.00</div>
                </div>
            </div>
        </div>
    </section>


    <!-- =============================[ Trindding ]======================  -->

    <section class="trending-section">
        <div class="trending-header">
            <h2>ترند اليوم</h2>
        </div>

        <div class="trending-grid">
            <!-- Card 1 -->
            <div class="trend-card">
                <div class="trend-image">
                    <img src="assets/images/trend1.png" alt="هودي فلسطين" />
                    <div class="overlay">
                        <a href="#" class="btn-view">عرض التفاصيل</a>
                    </div>
                </div>
                <div class="trend-info">
                    <h3>هودي فلسطين</h3>
                    <p>تصميم مميز يجسد الهوية الفلسطينية بخامة مريحة وأسلوب عصري.</p>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="trend-card">
                <div class="trend-image">
                    <img src="assets/images/trend2.png" alt="جبال القدس" />
                    <div class="overlay">
                        <a href="#" class="btn-view">عرض التفاصيل</a>
                    </div>
                </div>
                <div class="trend-info">
                    <h3>جبال القدس</h3>
                    <p>التفاصيل الفنية التي تعكس طبيعة فلسطين في لوحة فنية نابضة بالحياة.</p>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="trend-card">
                <div class="trend-image">
                    <img src="assets/images/trend3.png" alt="غروب غزة" />
                    <div class="overlay">
                        <a href="#" class="btn-view">عرض التفاصيل</a>
                    </div>
                </div>
                <div class="trend-info">
                    <h3>غروب غزة</h3>
                    <p>استمتع بتصميم مستوحى من لحظات الغروب في أفق غزة الساحلي.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ==================================[ Products ] ==================== -->


    <section class="products-section">
        <div class="products-header">
            <h2>المنتجات</h2>
            <a href="#" class="see-more">عرض المزيد &lt;</a>
        </div>

        <div class="products-grid">

            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي قبة الصخرة" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي قبة الصخرة</h3>
                    <p>عيش الواقع كأنك فيها مع مسح QR code</p>
                    <div class="rating">
                        <span class="stars">★★★★★</span>
                        <span class="score">4.8</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>

            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>
            <!-- Product Card -->
            <div class="product-card">
                <div class="product-image">
                    <img src="assets/images/prduct-item2.png" alt="هودي أبيض كأنك فيها" />
                    <div class="overlay">
                        <button class="btn-add">🛒 أضف للسلة</button>
                        <button class="btn-fav">❤️</button>
                    </div>
                </div>
                <div class="product-info">
                    <h3>هودي أبيض كأنك فيها</h3>
                    <p>تفاصيل دقيقة بخامة عالية الجودة</p>
                    <div class="rating">
                        <span class="stars">★★★★☆</span>
                        <span class="score">4.6</span>
                    </div>
                    <div class="price">$75.00</div>
                </div>
            </div>

            <!-- المزيد من المنتجات ... -->

        </div>
    </section>

    <!-- =======================[ Footer ] =================== -->
    @include('website.layout.sections._footer')
    <!-- ============================================================ -->
    @include('website.layout.sections._scripts')
    
    @stack("scripts")
</body>

</html>
