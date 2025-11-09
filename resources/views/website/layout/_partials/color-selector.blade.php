@props(['productId', 'colors'])

<div class="flex gap-2 mb-3">
    @foreach($colors as $index => $color)
        <button class="color-btn {{ $index === 0 ? 'active' : '' }}" 
                style="background: {{ $color }}"
                onclick="selectColor({{ $productId }}, '{{ $color }}', this)"
                data-color="{{ $color }}">
        </button>
    @endforeach
</div>

