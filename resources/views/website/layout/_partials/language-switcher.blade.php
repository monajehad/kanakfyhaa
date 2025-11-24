@php
    $locale = app()->getLocale();
    $nextLocale = $locale === 'ar' ? 'en' : 'ar';
    $buttonLabel = $locale === 'ar' ? 'EN' : 'ع';
@endphp
<button type="button" onclick="toggleLanguage('{{ $nextLocale }}')" class="switcher-btn" id="langBtn">
    {{ $buttonLabel }}
</button>

