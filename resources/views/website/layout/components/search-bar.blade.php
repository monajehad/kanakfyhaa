@php
    $id = $id ?? 'searchInput';
    $placeholder = $placeholder ?? 'ابحث عن المدينة أو المنتج...';
    $mobile = $mobile ?? false;
@endphp

<div class="{{ $mobile ? 'md:hidden pb-3' : 'hidden md:block flex-1 mx-8' }}">
    <form action="{{ route('search') }}" method="GET" class="flex gap-2">
        <input type="text" 
               name="q"
               id="{{ $id }}" 
               class="search-bar flex-1" 
               placeholder="{{ $placeholder }}"
               data-ar-placeholder="ابحث عن المدينة أو المنتج..."
               data-en-placeholder="Search for city or product..."
               value="{{ request('q') }}">
        <button type="submit" class="btn-yellow px-6" style="white-space: nowrap;">
            <span data-ar="بحث" data-en="Search">بحث</span>
        </button>
    </form>
</div>

