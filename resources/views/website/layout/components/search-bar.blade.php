@php
    $id = $id ?? 'searchInput';
    $placeholder = $placeholder ?? 'ابحث عن المدينة أو المنتج...';
    $mobile = $mobile ?? false;
@endphp

<div class="{{ $mobile ? 'md:hidden pb-3' : 'hidden md:block flex-1 mx-4' }}" {{ $mobile ? 'data-search-mobile' : 'data-search-desktop' }}>
    <form action="{{ route('search') }}" method="GET" class="relative flex-1">
        <div class="relative md-search-bar" style="background: var(--md-surface-variant); border-radius: 28px; overflow: hidden; display: flex; align-items: center; padding: 0 16px;">
            <span class="material-icons-outlined icon-sm" style="color: var(--md-on-surface-variant); margin-right: 8px;">search</span>
            <input type="text" 
                   name="q"
                   id="{{ $id }}" 
                   class="flex-1 bg-transparent px-0 py-2 text-sm md-body-medium focus:outline-none" 
                   placeholder="{{ $placeholder }}"
                   data-ar-placeholder="ابحث عن المدينة أو المنتج..."
                   data-en-placeholder="Search for city or product..."
                   value="{{ request('q') }}"
                   style="color: var(--md-on-surface);">
            <button type="submit" class="text-md-on-surface-variant hover:text-md-on-surface transition-colors" style="background: none; border: none; padding: 0; margin-left: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <span class="material-icons-outlined icon-sm">arrow_forward</span>
            </button>
        </div>
    </form>
</div>

