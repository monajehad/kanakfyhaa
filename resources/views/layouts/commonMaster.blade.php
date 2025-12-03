<!DOCTYPE html>
@php
use Illuminate\Support\Str;
use App\Helpers\Helpers;

$menuFixed =
$configData['layout'] === 'vertical'
? $menuFixed ?? ''
: ($configData['layout'] === 'front'
? ''
: $configData['headerType']);
$navbarType =
$configData['layout'] === 'vertical'
? $configData['navbarType']
: ($configData['layout'] === 'front'
? 'layout-navbar-fixed'
: '');
$isFront = ($isFront ?? '') == true ? 'Front' : '';
$contentLayout = isset($container) ? ($container === 'container-xxl' ? 'layout-compact' : 'layout-wide') : '';

// Get skin name from configData - only applies to admin layouts
$isAdminLayout = !Str::contains($configData['layout'] ?? '', 'front');
$skinName = $isAdminLayout ? $configData['skinName'] ?? 'default' : 'default';

// Get semiDark value from configData - only applies to admin layouts
$semiDarkEnabled = $isAdminLayout && filter_var($configData['semiDark'] ?? false, FILTER_VALIDATE_BOOLEAN);

// Generate primary color CSS if color is set
$primaryColorCSS = '';
if (isset($configData['color']) && $configData['color']) {
$primaryColorCSS = Helpers::generatePrimaryColorCSS($configData['color']);
}

@endphp


<html lang="{{ session()->get('locale') ?? app()->getLocale() }}"
class="{{ $navbarType ?? '' }} {{ $contentLayout ?? '' }} {{ $menuFixed ?? '' }} {{ $menuCollapsed ?? '' }} {{ $footerFixed ?? '' }} {{ $customizerHidden ?? '' }}"
dir="rtl" data-skin="{{ $skinName }}" data-assets-path="{{ asset('/assets') . '/' }}"
data-base-url="{{ url('/') }}" data-framework="laravel" data-template="{{ $configData['layout'] }}-menu-template"
  data-bs-theme="{{ $configData['theme'] }}" @if ($isAdminLayout && $semiDarkEnabled) data-semidark-menu="true" @endif>

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>
    @yield('title') | {{ $appName ?? config('variables.templateName', 'TemplateName') }}
    @if(config('variables.templateSuffix'))
      - {{ config('variables.templateSuffix') }}
    @endif
</title>
  <meta name="description" content="@yield('meta_description', $seoConfig['description'] ?? config('variables.templateDescription', ''))"/>
  <meta name="keywords" content="@yield('meta_keywords', $seoConfig['keywords'] ?? config('variables.templateKeyword', ''))"/>
  <meta name="author" content="{{ $appName ?? config('variables.templateName', '') }}" />
  <meta property="og:title" content="@yield('og_title', ($seoConfig['title'] ?? $appName ?? config('variables.ogTitle', '')))" />
  <meta property="og:type" content="@yield('og_type', config('variables.ogType', 'website'))" />
  <meta property="og:url" content="@yield('og_url', url()->current())" />
  <meta property="og:image" content="@yield('og_image', ($appLogo ?? config('variables.ogImage', '')))" />
  <meta property="og:description" content="@yield('og_description', ($seoConfig['description'] ?? config('variables.templateDescription', '')))" />
  <meta property="og:site_name" content="{{ $appName ?? config('variables.creatorName', '') }}" />
  <meta name="robots" content="noindex, nofollow" />
  <!-- laravel CRUD token -->
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <!-- Canonical SEO -->
  <link rel="canonical" href="{{ config('variables.productPage') ? config('variables.productPage') : '' }}" />
  <!-- Favicon: use logo for admin (dashboard), favicon for website -->
  <!-- Use unified logo as favicon across dashboard and website -->
  <link rel="icon" type="image/svg+xml" href="{{ asset('logo.svg') }}" />

  <!-- Include Styles -->
  <!-- $isFront is used to append the front layout styles only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/styles' . $isFront)

  @if (
      $primaryColorCSS &&
          (config('custom.custom.primaryColor') ||
              isset($_COOKIE['admin-primaryColor']) ||
              isset($_COOKIE['front-primaryColor'])))
    <!-- Primary Color Style -->
    <style id="primary-color-style">
      {!! $primaryColorCSS !!}
    </style>
  @endif

  <!-- Include Scripts for customizer, helper, analytics, config -->
  <!-- $isFront is used to append the front layout scriptsIncludes only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scriptsIncludes' . $isFront)

  @if(!empty($seoConfig['google_analytics_id'] ?? null))
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $seoConfig['google_analytics_id'] }}"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      gtag('config', '{{ $seoConfig['google_analytics_id'] }}');
    </script>
  @endif
</head>

<body>
  <!-- Layout Content -->
  @yield('layoutContent')
  <!--/ Layout Content -->

  <!-- WhatsApp Floating Button -->
  @include('components.whatsapp-fab')

  {{-- remove while creating package --}}
  {{-- remove while creating package end --}}

  <!-- Include Scripts -->
  <!-- $isFront is used to append the front layout scripts only on the front layout otherwise the variable will be blank -->
  @include('layouts/sections/scripts' . $isFront)
</body>

</html>
