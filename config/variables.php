<?php
// Variables - Project: Kaannakfiha
return [
    'creatorName' => 'نص شركة',
    'creatorUrl' => 'https://kaannakfiha.ps',
    'templateName' => 'كأنك فيها',
    'templateSuffix' => 'نظام إدارة المنتجات والتجارب الثقافية',
    'templateVersion' => '1.0.0',
    'templateFree' => false,
    'templateDescription' => 'نظام متكامل لإدارة المنتجات التفاعلية المرتبطة بالمدن الفلسطينية مع تجربة QR فريدة',
    'templateKeyword' => 'كأنك فيها, qr products, فلسطين, تجربة تفاعلية, مدن فلسطينية, laravel dashboard',
    'licenseUrl' => 'https://kaannakfiha.ps/license',
    'livePreview' => 'https://kaannakfiha.ps',
    'productPage' => 'https://kaannakfiha.ps/products',
    'support' => 'https://kaannakfiha.ps/support',
    'moreThemes' => 'https://kaannakfiha.ps',
    'ogTitle' => 'كأنك فيها - تجربة تفاعلية لاستكشاف المدن الفلسطينية',
    'ogImage' => 'https://kaannakfiha.ps/assets/images/og-kaannakfiha.png',
    'ogType' => 'product',
    'documentation' => 'https://kaannakfiha.ps/documentation',
    'generator' => 'Laravel 12 + Next.js',
    'changelog' => 'https://kaannakfiha.ps/changelog',
    'repository' => 'https://github.com/nass-company/kaannakfiha',
    'gitRepo' => 'kaannakfiha',
    'gitRepoAccess' => 'https://github.com/nass-company/kaannakfiha/access',
    'githubFreeUrl' => 'https://github.com/nass-company',
    'facebookUrl' => 'https://www.facebook.com/kaannakfiha',
    'twitterUrl' => 'https://x.com/kaannakfiha',
    'githubUrl' => 'https://github.com/nass-company',
    'dribbbleUrl' => 'https://dribbble.com/kaannakfiha',
    'instagramUrl' => 'https://www.instagram.com/kaannakfiha/',
    
    // معلومات خاصة بالمشروع
    'projectCreationDate' => '30-10-2025',
    'projectOwner' => 'نص شركة',
    'defaultLanguage' => 'ar',
    'supportedLanguages' => ['ar'], // قابل للتوسع مستقبلاً
    'defaultFont' => 'Cairo', // أو FF Shamel
    'qrCodeType' => 'UUID', // UUID أو Hash
    'publicDomain' => 'https://kaannakfiha.ps',
    'qrPagePattern' => 'https://kaannakfiha.ps/v/{uuid}',
    
    // إعدادات النظام
    'adminDashboard' => 'Materialize Admin v13.x',
    'backend' => 'Laravel 12',
    'frontend' => 'React.js / Next.js',
    'database' => 'MySQL / SQLite',
    'storage' => 'Cloudinary / Local Storage',
    'comments' => 'Internal System / Disqus API',
    
    // بوابات الدفع (اختياري)
    'paymentGateways' => [
        'stripe' => true,
        'paypal' => true,
        'payoneer' => true,
    ],
    
    // الميزات
    'features' => [
        'qrCodeGeneration' => true,
        'cityPages' => true,
        'productManagement' => true,
        'contentManagement' => true,
        'comments' => true,
        'darkMode' => true,
        'responsiveDesign' => true,
        'advancedSearch' => true,
        'toastNotifications' => true,
        'onboarding' => true,
    ],
];
