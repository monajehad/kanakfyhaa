<header class="main-header">
    <div class="container">
        <!-- Logo -->
        <div class="logo">
            <img src="{{ asset('logo.svg') }}" alt="كأنك فيها" />
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
