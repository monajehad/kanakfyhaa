@php
    $locale = app()->getLocale();
    $nextLocale = $locale === 'ar' ? 'en' : 'ar';
    $buttonLabel = $locale === 'ar' ? 'EN' : 'العربية';
@endphp
<button type="button" onclick="toggleLanguage('{{ $nextLocale }}')" class="inline-flex items-center justify-center px-3 py-2 rounded-lg transition-all duration-200 hover:bg-black/5 dark:hover:bg-white/10 font-medium text-sm md-label-medium" id="langBtn" style="color: var(--md-on-surface)">
    <span class="material-icons-outlined icon-sm mr-1">language</span>
    {{ $buttonLabel }}
</button>

