@props(['productId', 'sizes'])

<div class="flex flex-wrap gap-2 mb-3" id="sizes-{{ $productId }}">
    @foreach($sizes as $index => $size)
        <button class="size-btn {{ $index === 0 ? 'active' : '' }}" 
                onclick="selectSize({{ $productId }}, '{{ $size }}', this)">
            {{ $size }}
        </button>
    @endforeach
</div>

