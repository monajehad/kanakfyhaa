<!-- Header -->
<header class="header-nav fixed top-0 left-0 right-0 z-50">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3 gap-3">
            <!-- Logo -->
            @include('website.layout._partials.logo')

            <!-- Search -->
            @include('website.layout.components.search-bar', ['id' => 'searchInput', 'mobile' => false])

            <!-- Navigation -->
            <nav class="flex items-center gap-3">
                <!-- Mobile search icon -->
                <button class="md:hidden flex items-center justify-center" id="mobileSearchToggle" aria-label="Toggle search" title="Search" style="width:36px;height:36px;border-radius:10px;background:var(--md-surface-variant);color:var(--md-on-surface-variant);">
                    <span class="material-icons-outlined" style="font-size:20px;">search</span>
                </button>
                @include('website.layout._partials.language-switcher')
                @include('website.layout._partials.theme-switcher')
                @include('website.layout._partials.navigation-links')
                @include('website.layout._partials.cart-button')
            </nav>
        </div>

        <!-- Mobile Search -->
        <!-- Mobile Search Dialog (overlay) -->
        <div id="mobileSearchDialog" class="hidden" aria-hidden="true">
            <div class="search-backdrop" data-close-search></div>
            <div class="search-dialog" role="dialog" aria-modal="true" aria-labelledby="mobileSearchTitle">
                <div class="search-dialog-header">
                    <h2 id="mobileSearchTitle" class="search-title">بحث</h2>
                    <button id="mobileSearchClose" class="search-close" aria-label="Close search">
                        <span class="material-icons-outlined">close</span>
                    </button>
                </div>
                <div class="search-dialog-body">
                    @include('website.layout.components.search-bar', ['id' => 'searchInputMobile', 'mobile' => true])
                </div>
            </div>
        </div>
    </div>
</header>

<style>
    /* Header responsive improvements */
    .header-nav {
        background: #ffffff;
        box-shadow: 0 6px 16px rgba(0,0,0,0.06);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
    }

    /* Offset body content to avoid being hidden under fixed header */
    body { padding-top: 72px; }

    @media (max-width: 768px) {
        body { padding-top: 64px; }
        .header-nav .container {
            padding-left: 12px;
            padding-right: 12px;
        }

        /* Stack header row and avoid cramped layout */
        .header-nav .flex.items-center.justify-between {
            flex-wrap: wrap;
            row-gap: 8px;
        }

        /* Hide desktop search on mobile, rely on icon + toggled search below */
        .header-nav [data-search-desktop] {
            display: none !important;
        }

        /* Make nav items easier to tap */
        .header-nav nav {
            gap: 10px;
        }
        .header-nav nav a,
        .header-nav nav button {
            min-height: 36px;
            min-width: 36px;
            border-radius: 10px;
            padding: 6px 10px;
        }

        /* Ensure mobile search spans full width and centered */
        /* Mobile search in dialog: the inline mobile search is contained inside dialog */
        .header-nav [data-search-mobile] {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        body { padding-top: 60px; }
        /* Slightly tighter spacing for very small screens */
        .header-nav nav a,
        .header-nav nav button {
            min-height: 34px;
            min-width: 34px;
            padding: 6px 8px;
        }
    }
</style>

<style>
    /* Mobile search dialog styles */
    #mobileSearchDialog {
        position: fixed;
        inset: 0;
        z-index: 1000;
    }
    #mobileSearchDialog .search-backdrop {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,0.45);
        backdrop-filter: blur(2px);
    }
    #mobileSearchDialog .search-dialog {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        background: #fff;
        border-bottom-left-radius: 16px;
        border-bottom-right-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        padding: 12px;
        transform: translateY(-16px);
        opacity: 0;
        transition: transform 0.25s ease, opacity 0.25s ease;
    }
    #mobileSearchDialog.active .search-dialog {
        transform: translateY(0);
        opacity: 1;
    }
    .search-dialog-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
        padding: 4px 4px 8px 4px;
    }
    .search-title {
        margin: 0;
        font-size: 16px;
        color: #111827;
    }
    .search-close {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: none;
        background: var(--md-surface-variant);
        color: var(--md-on-surface-variant);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .search-dialog-body {
        padding: 4px 0 12px 0;
    }
    /* Ensure search bar inside dialog is comfortable */
    #mobileSearchDialog [data-search-mobile] .md-search-bar {
        border-radius: 28px;
    }

    @media (min-width: 769px) {
        /* Hide dialog on desktop */
        #mobileSearchDialog { display: none !important; }
        #mobileSearchToggle { display: none !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var toggleBtn = document.getElementById('mobileSearchToggle');
        var dialogRoot = document.getElementById('mobileSearchDialog');
        var closeBtn = document.getElementById('mobileSearchClose');
        var input = document.getElementById('searchInputMobile');

        function openDialog() {
            if (!dialogRoot) return;
            dialogRoot.classList.remove('hidden');
            requestAnimationFrame(function(){ dialogRoot.classList.add('active'); });
            setTimeout(function(){ input && input.focus(); }, 80);
            document.body.style.overflow = 'hidden';
        }

        function closeDialog() {
            if (!dialogRoot) return;
            dialogRoot.classList.remove('active');
            setTimeout(function(){ dialogRoot.classList.add('hidden'); }, 250);
            document.body.style.overflow = '';
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', openDialog);
        }
        if (closeBtn) {
            closeBtn.addEventListener('click', closeDialog);
        }
        if (dialogRoot) {
            dialogRoot.addEventListener('click', function(e) {
                if (e.target && e.target.hasAttribute('data-close-search')) {
                    closeDialog();
                }
            });
        }
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closeDialog();
        });
    });
</script>

