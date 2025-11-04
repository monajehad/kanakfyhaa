@if (isset($newsBar) && $newsBar)
    @php
        $newsItems = $newsBar->items()->where('active', true)->orderBy('order')->get();
        $itemSpace = (int) ($newsBar->item_space ?? 40);
        $theme = $newsBar->theme ?? 'dark';
        $direction = $newsBar->direction ?? 'rtl';
        $speed = (int) ($newsBar->speed ?? 120);
        $pauseOnHover = $newsBar->pause_on_hover ? 'true' : 'false';
    @endphp
    @if ($newsItems->count())
        <div class="news-bar theme-{{ $theme }}" id="newsBar">
            <div class="news-track" id="newsTrack">
                @foreach ($newsItems as $item)
                    <span style="margin-inline-end: {{ $itemSpace }}px; white-space:pre;" data-original="true">{{ $item->text }}</span>
                @endforeach
            </div>
        </div>
    @endif
@endif

@push('scripts')
    <script>
        // Initialize NewsBar with updated settings
        document.addEventListener("DOMContentLoaded", function() {
            new NewsBar({
                container: "#newsBar",
                track: "#newsTrack",
                speed: {{ $speed }},
                direction: "{{ $direction }}",
                pauseOnHover: {{ $pauseOnHover }},
                theme: "{{ $theme }}",
                itemSpace: {{ $itemSpace }}
            });
        });
    </script>
@endpush

