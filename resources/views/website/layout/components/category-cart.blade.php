@props(['categories'])

<div class="d-flex flex-row gap-2 flex-nowrap overflow-auto pb-2">
    @foreach($categories as $category)
        @php
            $imageUrl = $category->mainImage ? $category->mainImage->url : asset('assets/images/placeholders/category-default.svg');
        @endphp
        <a href="{{ route('search', ['category' => $category->name]) }}" class="d-inline-flex align-items-center text-decoration-none rounded-2 px-2 py-1 bg-white shadow-sm border gap-2 category-sm-link" style="min-width:0; white-space:nowrap; font-size: .93rem; height:32px;">
            <img src="{{ $imageUrl }}" alt="{{ $category->name }}" width="24" height="24" class="rounded-circle border" style="object-fit:cover; background: #f6f6f6;" loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('assets/images/placeholders/category-default.svg') }}';">
            <span class="text-dark text-truncate" style="max-width:86px;">{{ $category->name }}</span>
        </a>
    @endforeach
</div>
<style>
    .category-sm-link:hover, .category-sm-link:focus-visible {
        text-decoration: none;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        background: #fbf8ed;
    }
</style>
