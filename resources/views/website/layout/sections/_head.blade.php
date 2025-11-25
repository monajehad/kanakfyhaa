<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')</title>

<!-- Tailwind CSS -->
<script src="https://cdn.tailwindcss.com"></script>

<!-- Swiper JS and CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Google Fonts: Roboto (Material Design 3) + Cairo (Arabic) -->
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">

<!-- Material Design Icons -->
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">

<!-- Remixicon (Additional modern icons) -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/3.5.0/remixicon.min.css" rel="stylesheet">

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', 'Roboto', sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    /* Material Design 3 Color System */
    :root {
        /* Primary Colors - Brand Yellow/Lime Green */
        --md-primary: #C8D400;
        --md-on-primary: #000000;
        --md-primary-container: #DADE1A;
        --md-on-primary-container: #3D4107;
        
        /* Secondary Colors */
        --md-secondary: #5A6741;
        --md-on-secondary: #FFFFFF;
        --md-secondary-container: #7D805B;
        --md-on-secondary-container: #FFFFFF;
        
        /* Tertiary Colors */
        --md-tertiary: #376761;
        --md-on-tertiary: #FFFFFF;
        --md-tertiary-container: #52A399;
        --md-on-tertiary-container: #FFFFFF;
        
        /* Neutral Colors */
        --md-background: #FFFBFE;
        --md-on-background: #1C1B1F;
        --md-surface: #FFFBFE;
        --md-on-surface: #1C1B1F;
        --md-surface-variant: #E8E0EC;
        --md-on-surface-variant: #4A4458;
        --md-surface-bright: #FFFBFE;
        --md-surface-container-highest: #E8E0EC;
        --md-outline: #79747E;
        --md-outline-variant: #CAC7D0;
        
        /* Error Colors */
        --md-error: #B3261E;
        --md-on-error: #FFFFFF;
        --md-error-container: #F9DEDC;
        --md-on-error-container: #410E0B;
        
        /* Semantic Colors */
        --md-success: #52A399;
        --md-warning: #FFB200;
        --md-info: #0087F0;
        
        /* Legacy compatibility */
        --primary-black: #1C1B1F;
        --primary-yellow: #C8D400;
        --primary-white: #FFFFFF;
        --gray-bg: #F5F5F5;
        --gray-text: #4A4458;
        --border-color: #E8E0EC;
    }

    /* Dark Mode */
    [data-theme="dark"] {
        --md-primary: #DADE1A;
        --md-on-primary: #252C00;
        --md-primary-container: #3D4107;
        --md-on-primary-container: #DADE1A;
        
        --md-secondary: #C2CCB0;
        --md-on-secondary: #252E1B;
        --md-secondary-container: #3D4730;
        --md-on-secondary-container: #DFE8CC;
        
        --md-tertiary: #96D4C8;
        --md-on-tertiary: #0B3C35;
        --md-tertiary-container: #1F544C;
        --md-on-tertiary-container: #A5E0D6;
        
        --md-background: #1C1B1F;
        --md-on-background: #E8E0EC;
        --md-surface: #1C1B1F;
        --md-on-surface: #E8E0EC;
        --md-surface-variant: #4A4458;
        --md-on-surface-variant: #CBC4D0;
        --md-surface-bright: #3B383F;
        --md-surface-container-highest: #4A4458;
        --md-outline: #9E9DA7;
        --md-outline-variant: #49454F;
        
        --md-error: #F2B8B5;
        --md-on-error: #601410;
        --md-error-container: #8C1D18;
        --md-on-error-container: #F9DEDC;
        
        --md-success: #96D4C8;
        --md-warning: #FFD700;
        --md-info: #64B5F6;
        
        /* Legacy compatibility */
        --primary-black: #E8E0EC;
        --primary-yellow: #DADE1A;
        --primary-white: #1C1B1F;
        --gray-bg: #0A0A0A;
        --gray-text: #CBC4D0;
        --border-color: #4A4458;
    }

    html, body {
        background: var(--md-background);
        color: var(--md-on-background);
        min-height: 100vh;
    }

    /* Material Design 3 Elevation Shadow System */
    .elevation-1 {
        box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 1px 3px 1px rgba(0,0,0,0.15);
    }
    
    .elevation-2 {
        box-shadow: 0 1px 2px rgba(0,0,0,0.3), 0 2px 6px 2px rgba(0,0,0,0.15);
    }
    
    .elevation-3 {
        box-shadow: 0 1px 3px rgba(0,0,0,0.3), 0 3px 6px 3px rgba(0,0,0,0.15);
    }
    
    .elevation-4 {
        box-shadow: 0 2px 3px rgba(0,0,0,0.3), 0 6px 10px 4px rgba(0,0,0,0.15);
    }
    
    .elevation-5 {
        box-shadow: 0 4px 4px rgba(0,0,0,0.3), 0 8px 12px 6px rgba(0,0,0,0.15);
    }

    /* Material Design 3 Typography */
    .md-display-large {
        font-size: 57px;
        line-height: 64px;
        font-weight: 400;
        letter-spacing: -0.25px;
    }

    .md-display-medium {
        font-size: 45px;
        line-height: 52px;
        font-weight: 400;
        letter-spacing: 0px;
    }

    .md-display-small {
        font-size: 36px;
        line-height: 44px;
        font-weight: 400;
        letter-spacing: 0px;
    }

    .md-headline-large {
        font-size: 32px;
        line-height: 40px;
        font-weight: 700;
        letter-spacing: 0px;
    }

    .md-headline-medium {
        font-size: 28px;
        line-height: 36px;
        font-weight: 700;
        letter-spacing: 0px;
    }

    .md-headline-small {
        font-size: 24px;
        line-height: 32px;
        font-weight: 700;
        letter-spacing: 0px;
    }

    .md-title-large {
        font-size: 22px;
        line-height: 28px;
        font-weight: 700;
        letter-spacing: 0px;
    }

    .md-title-medium {
        font-size: 16px;
        line-height: 24px;
        font-weight: 700;
        letter-spacing: 0.15px;
    }

    .md-title-small {
        font-size: 14px;
        line-height: 20px;
        font-weight: 700;
        letter-spacing: 0.1px;
    }

    .md-body-large {
        font-size: 16px;
        line-height: 24px;
        font-weight: 500;
        letter-spacing: 0.15px;
    }

    .md-body-medium {
        font-size: 14px;
        line-height: 20px;
        font-weight: 500;
        letter-spacing: 0.25px;
    }

    .md-body-small {
        font-size: 12px;
        line-height: 16px;
        font-weight: 500;
        letter-spacing: 0.4px;
    }

    .md-label-large {
        font-size: 14px;
        line-height: 20px;
        font-weight: 700;
        letter-spacing: 0.1px;
    }

    .md-label-medium {
        font-size: 12px;
        line-height: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .md-label-small {
        font-size: 11px;
        line-height: 16px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* Material Icons */
    .material-icons, .material-icons-sharp, .material-icons-outlined, .material-icons-round {
        font-family: 'Material Icons';
        font-weight: normal;
        font-style: normal;
        font-size: 24px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        user-select: none;
    }

    .material-icons {
        font-family: 'Material Icons';
    }

    .material-icons-outlined {
        font-family: 'Material Icons Outlined';
    }

    .material-icons-round {
        font-family: 'Material Icons Round';
    }

    .material-icons-sharp {
        font-family: 'Material Icons Sharp';
    }

    /* Icon sizes */
    .icon-sm { font-size: 18px; }
    .icon-md { font-size: 24px; }
    .icon-lg { font-size: 32px; }
    .icon-xl { font-size: 48px; }

    /* Header */
    .header-nav {
        background: var(--md-surface);
        border-bottom: 1px solid var(--md-surface-variant);
        elevation-1;
    }

    [data-theme="dark"] .header-nav {
        background: var(--md-surface);
        border-bottom-color: var(--md-surface-variant);
    }

    /* Logo */
    .logo-img {
        height: 50px;
        width: auto;
        filter: brightness(1);
    }

    /* Material Design 3 Button Styles */
    .btn-md {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 24px;
        border: none;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
    }

    /* Filled Button */
    .btn-filled {
        background: var(--md-primary);
        color: var(--md-on-primary);
    }

    .btn-filled:hover {
        background: var(--md-primary-container);
        elevation-3;
    }

    .btn-filled:active {
        elevation-1;
    }

    /* Filled Tonal Button */
    .btn-filled-tonal {
        background: var(--md-secondary-container);
        color: var(--md-on-secondary-container);
    }

    .btn-filled-tonal:hover {
        background: var(--md-secondary);
        elevation-3;
    }

    /* Outlined Button */
    .btn-outlined {
        background: transparent;
        color: var(--md-primary);
        border: 2px solid var(--md-primary);
    }

    .btn-outlined:hover {
        background: rgba(200, 212, 0, 0.08);
        elevation-1;
    }

    /* Text Button */
    .btn-text {
        background: transparent;
        color: var(--md-primary);
        padding: 8px 12px;
    }

    .btn-text:hover {
        background: rgba(200, 212, 0, 0.08);
    }

    /* FAB (Floating Action Button) */
    .fab {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: var(--md-primary);
        color: var(--md-on-primary);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        elevation-4;
        transition: all 0.3s;
        position: fixed;
        bottom: 16px;
        right: 16px;
    }

    .fab:hover {
        elevation-5;
        transform: scale(1.1);
    }

    /* Card */
    .md-card {
        background: var(--md-surface);
        border-radius: 12px;
        border: 1px solid var(--md-surface-variant);
        elevation-1;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .md-card:hover {
        elevation-3;
        transform: translateY(-4px);
        border-color: var(--md-primary);
    }

    /* Text Field */
    .md-text-field {
        background: var(--md-surface-variant);
        border: 2px solid var(--md-surface-variant);
        border-radius: 8px;
        padding: 12px 16px;
        font-size: 14px;
        color: var(--md-on-surface);
        font-family: 'Cairo', 'Roboto', sans-serif;
        transition: all 0.3s;
    }

    .md-text-field:focus {
        outline: none;
        border-color: var(--md-primary);
        background: var(--md-surface);
        elevation-2;
    }

    /* Search Bar */
    .search-bar {
        background: var(--md-surface-variant);
        border: 2px solid var(--md-surface-variant);
        border-radius: 24px;
        padding: 10px 16px;
        width: 100%;
        max-width: 400px;
        font-size: 14px;
        color: var(--md-on-surface);
        font-family: 'Cairo', 'Roboto', sans-serif;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .search-bar:focus {
        outline: none;
        border-color: var(--md-primary);
        background: var(--md-surface);
        elevation-2;
    }

    /* Chip */
    .chip {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: var(--md-surface-variant);
        color: var(--md-on-surface-variant);
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        border: 1px solid var(--md-surface-variant);
        cursor: pointer;
        transition: all 0.3s;
    }

    .chip:hover, .chip.active {
        background: var(--md-primary);
        color: var(--md-on-primary);
        border-color: var(--md-primary);
    }

    /* Product Card */
    .product-card {
        background: var(--md-surface);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--md-surface-variant);
        elevation-1;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        elevation-4;
        transform: translateY(-8px);
        border-color: var(--md-primary);
    }

    .product-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
        background: var(--md-surface-variant);
    }

    .product-info {
        padding: 16px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        font-size: 16px;
        font-weight: 700;
        color: var(--md-on-surface);
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .product-desc {
        font-size: 12px;
        color: var(--md-on-surface-variant);
        margin-bottom: 12px;
        flex-grow: 1;
    }

    .product-price {
        font-size: 16px;
        font-weight: 700;
        color: var(--md-primary);
        margin-bottom: 12px;
    }

    .product-actions {
        display: flex;
        gap: 8px;
    }

    /* Color Selector */
    .color-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        border: 2px solid var(--md-surface-variant);
        cursor: pointer;
        transition: all 0.3s;
        position: relative;
    }

    .color-btn:hover {
        transform: scale(1.1);
        border-color: var(--md-primary);
    }

    .color-btn.active {
        border: 3px solid var(--md-primary);
        box-shadow: 0 0 0 2px var(--md-surface), 0 0 0 5px var(--md-primary);
        transform: scale(1.15);
    }

    /* Size Selector */
    .size-btn {
        padding: 8px 16px;
        border: 2px solid var(--md-surface-variant);
        border-radius: 8px;
        background: var(--md-surface);
        color: var(--md-on-surface);
        cursor: pointer;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .size-btn:hover {
        border-color: var(--md-primary);
        background: rgba(200, 212, 0, 0.08);
    }

    .size-btn.active {
        background: var(--md-primary);
        border-color: var(--md-primary);
        color: var(--md-on-primary);
        elevation-2;
    }

    /* Badge */
    .badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 16px;
        height: 16px;
        padding: 0 4px;
        background: var(--md-error);
        color: white;
        border-radius: 50%;
        font-size: 11px;
        font-weight: 700;
        position: absolute;
        top: -8px;
        right: -8px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        overflow-y: auto;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .modal-content {
        background: var(--md-surface);
        border-radius: 28px;
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        elevation-5;
    }

    /* Cart Badge */
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--md-error);
        color: white;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        z-index: 10;
    }

    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, var(--md-primary) 0%, var(--md-primary-container) 100%);
        color: var(--md-on-primary);
        padding: 80px 20px;
        text-align: center;
        border-radius: 0;
    }

    /* Header Navigation (Material Design 3) */
    .header-nav {
        background: var(--md-surface);
        border-bottom: 1px solid var(--md-outline-variant);
        box-shadow: var(--elevation-1);
    }

    .header-nav .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* Navigation Links */
    .nav-link {
        color: var(--md-on-surface);
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .nav-link:hover {
        background: var(--md-primary);
        color: var(--md-on-primary);
    }

    /* Active Nav Link */
    .nav-link.active {
        background: var(--md-primary-container);
        color: var(--md-on-primary-container);
    }

    /* Logo Image */
    .logo-img {
        height: 48px;
        width: auto;
        object-fit: contain;
        transition: transform 0.3s;
    }

    .logo-img:hover {
        transform: scale(1.05);
    }

    /* Cart Badge */
    .cart-badge {
        position: absolute;
        top: -6px;
        right: -6px;
        min-width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 11px;
        font-weight: bold;
        background: var(--md-error);
        color: white;
        animation: badgePulse 0.3s ease-out;
    }

    @keyframes badgePulse {
        0% {
            transform: scale(0);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    /* Header Navigation (Material Design 3) */
    .header-nav {
        background: var(--md-surface);
        box-shadow: var(--elevation-1);
        color: var(--md-on-surface);
        border-bottom: 1px solid var(--md-surface-variant);
    }

    .header-nav .logo-img {
        height: 40px;
        width: auto;
    }

    /* Search Bar (Material Design 3) */
    .md-search-bar {
        transition: all 0.3s;
    }

    .md-search-bar:focus-within {
        background: var(--md-surface);
        box-shadow: var(--elevation-1);
    }

    .md-search-bar input::placeholder {
        color: var(--md-on-surface-variant);
    }

    /* Modal Styling (Material Design 3) */
    .modal {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        justify-content: center;
        align-items: center;
        z-index: 1000;
        animation: modalFadeIn 0.3s ease-out;
    }

    .modal.active {
        display: flex;
    }

    @keyframes modalFadeIn {
        from {
            background: rgba(0, 0, 0, 0);
        }
        to {
            background: rgba(0, 0, 0, 0.5);
        }
    }

    .modal-content {
        width: 90%;
        max-width: 500px;
        max-height: 90vh;
        margin: auto;
        animation: slideUp 0.3s ease-out;
        background: var(--md-surface);
        border-radius: 28px;
        box-shadow: var(--elevation-5);
    }

    @keyframes slideUp {
        from {
            transform: translateY(50px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    @keyframes slideInUp {
        from {
            transform: translateY(10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-in {
        animation: slideUp 0.3s ease-out;
    }

    @media (max-width: 640px) {
        .modal-content {
            width: 100%;
            max-width: 100%;
            max-height: 85vh;
            border-radius: 24px 24px 0 0;
            margin-top: auto;
        }
    }

    /* Divider */
    .divider {
        height: 1px;
        background: var(--md-surface-variant);
    }

    /* List Item */
    .list-item {
        padding: 16px;
        border-bottom: 1px solid var(--md-surface-variant);
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.3s;
    }

    .list-item:hover {
        background: var(--md-surface-variant);
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .city-section.sticky {
            position: relative;
            top: 0;
        }
    }
</style>

