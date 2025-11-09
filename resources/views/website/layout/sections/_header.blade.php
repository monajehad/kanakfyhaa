<!-- Header -->
<header class="header-nav sticky top-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo -->
            @include('website.layout._partials.logo')

            <!-- Search -->
            @include('website.layout.components.search-bar', ['id' => 'searchInput', 'mobile' => false])

            <!-- Navigation -->
            <nav class="flex items-center gap-3">
                @include('website.layout._partials.language-switcher')
                @include('website.layout._partials.theme-switcher')
                @include('website.layout._partials.navigation-links')
                @include('website.layout._partials.cart-button')
            </nav>
        </div>

        <!-- Mobile Search -->
        @include('website.layout.components.search-bar', ['id' => 'searchInputMobile', 'mobile' => true])
    </div>
</header>

