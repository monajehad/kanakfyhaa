<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>@yield('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')</title>
<script src="https://cdn.tailwindcss.com"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800;900&display=swap" rel="stylesheet">
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Cairo', sans-serif;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    :root {
        --primary-black: #000000;
        --primary-yellow: #C8D400;
        --primary-white: #FFFFFF;
        --gray-bg: #F5F5F5;
        --gray-text: #666666;
        --border-color: #E5E5E5;
    }

    [data-theme="dark"] {
        --primary-black: #FFFFFF;
        --primary-yellow: #C8D400;
        --primary-white: #1A1A1A;
        --gray-bg: #0A0A0A;
        --gray-text: #AAAAAA;
        --border-color: #333333;
    }

    body {
        background: var(--gray-bg);
        color: var(--primary-black);
        min-height: 100vh;
    }

    /* Header */
    .header-nav {
        background: var(--primary-white);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    [data-theme="dark"] .header-nav {
        background: #1A1A1A;
        box-shadow: 0 2px 10px rgba(255,255,255,0.05);
    }

    .logo-img {
        height: 50px;
        width: auto;
        filter: brightness(1);
    }

    [data-theme="dark"] .logo-img {
    }

    /* City Section */
    .city-section {
        background: var(--primary-white);
        border-radius: 15px;
        padding: 30px;
        margin-bottom: 40px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    }

    [data-theme="dark"] .city-section {
        background: #1A1A1A;
        box-shadow: 0 2px 15px rgba(255,255,255,0.05);
    }

    .city-title {
        font-size: 1.8rem;
        font-weight: 800;
        color: var(--primary-black);
        border-bottom: 4px solid var(--primary-yellow);
        display: inline-block;
        padding-bottom: 8px;
        margin-bottom: 25px;
    }

    /* Product Card */
    .product-card {
        background: var(--primary-white);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        border: 2px solid var(--border-color);
        height: 100%;
    }

    [data-theme="dark"] .product-card {
        background: #1A1A1A;
        border-color: #333;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-color: var(--primary-yellow);
    }

    [data-theme="dark"] .product-card:hover {
        box-shadow: 0 10px 25px rgba(255,255,255,0.1);
    }

    .product-image {
        width: 100%;
        height: 280px;
        object-fit: cover;
    }

    .product-info {
        padding: 15px;
    }

    .product-name {
        font-size: 1rem;
        font-weight: 700;
        color: var(--primary-black);
        margin-bottom: 8px;
    }

    .product-desc {
        font-size: 0.85rem;
        color: var(--gray-text);
        margin-bottom: 12px;
    }

    /* Buttons */
    .btn-yellow {
        background: var(--primary-yellow);
        color: #000;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
        width: 100%;
    }

    .btn-yellow:hover {
        background: #b3bf00;
        transform: translateY(-2px);
    }

    .btn-black {
        background: var(--primary-black);
        color: var(--primary-white);
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 700;
        border: none;
        cursor: pointer;
        transition: all 0.3s;
    }

    .btn-black:hover {
        opacity: 0.8;
    }

    /* Color Options */
    .color-btn {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        border: 2px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s;
    }

    .color-btn:hover,
    .color-btn.active {
        transform: scale(1.15);
        border-color: var(--primary-yellow);
        border-width: 3px;
    }

    /* Size Selector */
    .size-btn {
        padding: 6px 12px;
        border: 2px solid var(--border-color);
        border-radius: 6px;
        background: var(--primary-white);
        color: var(--primary-black);
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    [data-theme="dark"] .size-btn {
        background: #0A0A0A;
    }

    .size-btn:hover,
    .size-btn.active {
        background: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: #000;
    }

    /* Package Badge */
    .package-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: var(--primary-yellow);
        color: #000;
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        z-index: 10;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.7);
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
        background: var(--primary-white);
        border-radius: 15px;
        max-width: 800px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
    }

    /* Search Bar */
    .search-bar {
        background: var(--primary-white);
        border: 2px solid var(--border-color);
        border-radius: 25px;
        padding: 10px 20px;
        width: 100%;
        max-width: 400px;
        font-size: 0.9rem;
        color: var(--primary-black);
    }

    [data-theme="dark"] .search-bar {
        background: #0A0A0A;
    }

    .search-bar:focus {
        outline: none;
        border-color: var(--primary-yellow);
    }

    /* Cart Badge */
    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: var(--primary-yellow);
        color: #000;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Hero Section */
    .hero-section {
        background: var(--primary-black);
        color: var(--primary-white);
        padding: 80px 20px;
        text-align: center;
    }

    [data-theme="dark"] .hero-section {
        background: #0A0A0A;
    }

    /* Language & Theme Switcher */
    .switcher-btn {
        background: var(--primary-white);
        color: var(--primary-black);
        border: 2px solid var(--border-color);
        padding: 8px 16px;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        font-size: 0.85rem;
        transition: all 0.3s;
    }

    [data-theme="dark"] .switcher-btn {
        background: #1A1A1A;
    }

    .switcher-btn:hover {
        border-color: var(--primary-yellow);
        background: var(--primary-yellow);
        color: #000;
    }

    /* Nav Links */
    .nav-link {
        color: var(--primary-black);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        padding: 8px 16px;
        border-radius: 8px;
        transition: all 0.3s;
    }

    .nav-link:hover {
        background: var(--primary-yellow);
        color: #000;
    }

    /* Filter Styles */
    .filter-select,
    .filter-input {
        width: 100%;
        padding: 10px 15px;
        background: var(--primary-white);
        border: 2px solid var(--border-color);
        border-radius: 8px;
        color: var(--primary-black);
        font-size: 0.9rem;
        font-family: 'Cairo', sans-serif;
        transition: all 0.3s ease;
    }

    [data-theme="dark"] .filter-select,
    [data-theme="dark"] .filter-input {
        background: #0A0A0A;
        border-color: #333333;
        color: var(--primary-white);
    }

    .filter-select:focus,
    .filter-input:focus {
        outline: none;
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 3px rgba(200, 212, 0, 0.1);
    }

    .filter-select option {
        background: var(--primary-white);
        color: var(--primary-black);
    }

    [data-theme="dark"] .filter-select option {
        background: #1A1A1A;
        color: var(--primary-white);
    }

    /* Filter Color Buttons */
    .filter-color-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 3px solid var(--border-color);
        cursor: pointer;
        transition: all 0.2s;
    }

    .filter-color-btn.active {
        transform: scale(1.2);
        border-color: var(--primary-yellow);
        box-shadow: 0 0 0 2px var(--primary-white), 0 0 0 4px var(--primary-yellow);
    }

    /* Filter Size Buttons */
    .filter-size-btn {
        padding: 8px 14px;
        border: 2px solid var(--border-color);
        border-radius: 6px;
        background: var(--primary-white);
        color: var(--primary-black);
        cursor: pointer;
        font-size: 0.85rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    [data-theme="dark"] .filter-size-btn {
        background: #0A0A0A;
        border-color: #333;
    }

    .filter-size-btn:hover,
    .filter-size-btn.active {
        background: var(--primary-yellow);
        border-color: var(--primary-yellow);
        color: #000;
        transform: translateY(-2px);
    }

    /* Checkbox Styling */
    input[type="checkbox"] {
        width: 20px;
        height: 20px;
        cursor: pointer;
        accent-color: var(--primary-yellow);
    }

    /* Results Section */
    #searchResults {
        min-height: 400px;
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .city-section.sticky {
            position: relative;
            top: 0;
        }
    }
</style>

