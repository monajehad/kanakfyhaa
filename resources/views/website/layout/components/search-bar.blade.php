@php
    $id = $id ?? 'searchInput';
    $placeholder = $placeholder ?? 'ابحث عن المدينة أو المنتج...';
    $mobile = $mobile ?? false;
@endphp

<div class="{{ $mobile ? 'md:hidden pb-3' : 'hidden md:block flex-1 mx-8' }}">
    <form action="{{ route('search') }}" method="GET" class="relative flex-1">
        <input type="text" 
               name="q"
               id="{{ $id }}" 
               class="search-bar flex-1 pr-12" 
               placeholder="{{ $placeholder }}"
               data-ar-placeholder="ابحث عن المدينة أو المنتج..."
               data-en-placeholder="Search for city or product..."
               value="{{ request('q') }}">
        <button type="submit" class="absolute right-2 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-yellow-500" style="background: none; border: none; padding: 0; outline: none;">
            <!-- SVG search icon -->
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <title data-ar="بحث" data-en="Search">بحث</title>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 104.5 4.5a7.5 7.5 0 0012.15 12.15z" />
            </svg>
        </button>
    </form>
</div>

