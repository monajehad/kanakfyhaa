@php
    $id = $id ?? 'searchInput';
    $placeholder = $placeholder ?? 'ابحث عن المدينة أو المنتج...';
    $mobile = $mobile ?? false;
@endphp

<div class="{{ $mobile ? 'md:hidden pb-3' : 'hidden md:block flex-1 mx-8' }}">
    <input type="text" 
           id="{{ $id }}" 
           class="search-bar" 
           placeholder="{{ $placeholder }}"
           data-ar-placeholder="ابحث عن المدينة أو المنتج..."
           data-en-placeholder="Search for city or product...">
</div>

