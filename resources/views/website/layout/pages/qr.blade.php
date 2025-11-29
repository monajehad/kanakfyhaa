@extends('website.layout.main')

@section('title', ($product->localized_name ?? $product->name) . ' - ' . __('qr.page_title_suffix'))

@section('content')
    @php
        $locale = app()->getLocale();
        $isArabic = $locale === 'ar';
        $productName = $product->localized_name ?? ($product->name_ar ?? $product->name);
        $productDescription = $product->localized_description ?? ($product->description_ar ?? $product->description);
        $cityName = $city?->localized_name ?? ($city?->name_ar ?? $city?->name ?? '');
        $experienceUrl = url('/experience/' . $product->uuid);
        $landmarkCount = $landmarks->count();
        $colors = is_array($product->colors) ? array_values(array_filter($product->colors)) : [];
        $sizes = is_array($product->sizes) ? array_values(array_filter($product->sizes)) : [];
        $steps = [
            [
                'title' => __('qr.steps.scan.title'),
                'body' => __('qr.steps.scan.body'),
            ],
            [
                'title' => __('qr.steps.explore.title'),
                'body' => __('qr.steps.explore.body'),
            ],
            [
                'title' => __('qr.steps.share.title'),
                'body' => __('qr.steps.share.body'),
            ],
        ];
    @endphp

    <!-- Immersive City Experience Section -->
    <section class="relative overflow-hidden bg-white">
        <!-- Hero Background -->
        <div class="absolute inset-0 opacity-10 pointer-events-none">
            <svg class="absolute top-0 right-0 w-96 h-96 text-blue-400" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path d="M50,-50 Q150,0 100,150 Q0,100 50,-50" fill="currentColor"/>
            </svg>
        </div>

        <div class="container mx-auto px-4 py-12 relative z-10">
            <!-- City Header -->
            <div class="mb-12">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-12 h-12 rounded-full bg-blue-600 flex items-center justify-center text-white text-xl shrink-0">
                        📍
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-blue-600 mb-1">@lang('qr.city.label')</p>
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900">{{ $cityName }}</h2>
                        <p class="text-lg text-gray-600 mt-3 max-w-2xl leading-relaxed">
                            {{ $city?->localized_description ?? $city?->description ?? __('qr.city.no_description') }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Landmarks Section -->
            @if($landmarkCount > 0)
                <div class="mb-12">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-full bg-orange-600 flex items-center justify-center text-white text-lg">🏛️</div>
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900">@lang('qr.city.landmarks.title')</h3>
                            <p class="text-sm text-gray-600 mt-1">@lang('qr.city.landmarks.description')</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($landmarks as $landmark)
                            @php
                                $landmarkCover = optional($landmark->media->first())->url ?? $landmark->image ?? '';
                                $timelineData = $landmark->timeline ?? [
                                    __('qr.timeline.phase_one'),
                                    __('qr.timeline.phase_two'),
                                    __('qr.timeline.phase_three'),
                                ];
                                $immersivePayload = [
                                    'name' => $landmark->localized_name ?? $landmark->name,
                                    'type' => $landmark->type ?? __('qr.landmarks.type_fallback'),
                                    'city' => $cityName,
                                    'image' => $landmarkCover,
                                    'history' => $landmark->localized_description ?? $landmark->description ?? $landmark->localized_short_description ?? $landmark->short_description,
                                    'short' => \Illuminate\Support\Str::limit($landmark->localized_short_description ?? $landmark->short_description ?? $landmark->localized_description ?? $landmark->description, 140),
                                    'timeline' => is_array($timelineData) ? $timelineData : json_decode($timelineData ?? '[]', true),
                                    'ambient' => $landmark->localized_ambient_description ?? $landmark->ambient_description ?? __('qr.landmarks.ambient_default'),
                                ];
                            @endphp
                            <button
                                type="button"
                                class="group relative rounded-3xl overflow-hidden shadow-md hover:shadow-2xl transition-all duration-300 text-left focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 bg-white"
                                data-landmark-trigger
                                data-landmark='@json($immersivePayload)'
                            >
                                <!-- Image Container -->
                                <div class="relative h-64 overflow-hidden">
                                    <img src="{{ $landmarkCover }}" alt="{{ $landmark->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" onerror="this.style.display='none'">
                                    <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/0 group-hover:from-black/80"></div>

                                    <!-- Type Badge -->
                                    <div class="absolute top-4 {{ $isArabic ? 'left-4' : 'right-4' }} flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/95 text-xs font-semibold text-gray-900 backdrop-blur-sm">
                                        <span class="text-lg">🏛️</span>
                                        {{ $landmark->type ?? trans('qr.landmarks.type_fallback') }}
                                    </div>

                                    <!-- Bottom Info -->
                                    <div class="absolute bottom-0 left-0 right-0 p-4 text-white">
                                        <h3 class="text-xl font-bold">{{ $landmark->localized_name ?? $landmark->name }}</h3>
                                    </div>
                                </div>

                                <!-- Content -->
                                <div class="p-5">
                                    <p class="text-sm text-gray-600 line-clamp-2">
                                        {{ \Illuminate\Support\Str::limit($landmark->localized_short_description ?? $landmark->short_description ?? $landmark->localized_description ?? $landmark->description, 80) }}
                                    </p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <span class="inline-flex items-center gap-2 text-xs font-semibold text-orange-600">
                                            <span>🔍</span>
                                            @lang('qr.city.explore_landmark')
                                        </span>
                                        <span class="text-lg opacity-0 group-hover:opacity-100 transition-opacity">→</span>
                                    </div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="rounded-2xl border-2 border-dashed border-gray-300 p-12 text-center mb-12">
                    <div class="text-5xl mb-4">🗺️</div>
                    <p class="text-gray-600">@lang('qr.city.no_landmarks')</p>
                </div>
            @endif
        </div>
    </section>

    <!-- City Map Section -->
    <section class="relative bg-white py-12 overflow-hidden">
        <!-- Background decoration -->
        <div class="absolute inset-0 opacity-5 pointer-events-none">
            <svg class="absolute bottom-0 left-0 w-96 h-96" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <circle cx="100" cy="100" r="80" fill="currentColor" class="text-blue-400"/>
            </svg>
        </div>

        <div class="container mx-auto px-4 relative z-10">
            <!-- Map Header -->
            <div class="flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-lg">🗺️</div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900">@lang('qr.city.map.title')</h3>
                    <p class="text-sm text-gray-600 mt-1">@lang('qr.city.map.description')</p>
                </div>
            </div>

            <!-- Map Container - Material 3 Card -->
            <div class="rounded-3xl overflow-hidden shadow-lg border border-blue-200 bg-white">
                @if($city?->latitude && $city?->longitude)
                    <!-- OpenStreetMap Embed -->
                    <div class="relative w-full h-96 md:h-[500px] bg-blue-50">
                        <iframe
                            class="w-full h-full border-0"
                            src="https://www.openstreetmap.org/export/embed.html?bbox={{ $city->longitude - 0.05 }},{{ $city->latitude - 0.05 }},{{ $city->longitude + 0.05 }},{{ $city->latitude + 0.05 }}&layer=mapnik&marker={{ $city->latitude }},{{ $city->longitude }}"
                            style="border: 0;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>

                    <!-- Map Info Footer -->
                    <div class="p-6 bg-blue-50 border-t border-blue-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Coordinates -->
                            <div class="flex items-start gap-3">
                                <div class="text-2xl">📍</div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-700">@lang('qr.city.map.coordinates')</p>
                                    <p class="text-sm text-gray-600 font-mono">{{ number_format($city->latitude, 4) }}, {{ number_format($city->longitude, 4) }}</p>
                                </div>
                            </div>

                            <!-- Region Info -->
                            @if($city?->region)
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">🌍</div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">@lang('qr.city.map.region')</p>
                                        <p class="text-sm text-gray-600">{{ $city->region }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Population Info -->
                            @if($city?->population)
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl">👥</div>
                                    <div>
                                        <p class="text-sm font-semibold text-gray-700">@lang('qr.city.map.population')</p>
                                        <p class="text-sm text-gray-600">{{ number_format($city->population) }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @else
                    <!-- No Coordinates Available -->
                    <div class="w-full h-96 md:h-[500px] bg-blue-50 flex items-center justify-center">
                        <div class="text-center">
                            <div class="text-6xl mb-4">🗺️</div>
                            <p class="text-gray-600 font-medium">@lang('qr.city.map.no_coordinates')</p>
                            <p class="text-sm text-gray-500 mt-2">@lang('qr.city.map.location_info_unavailable')</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
        <div class="grid grid-cols-1 gap-8">
            <div class="space-y-8">
                <div class="rounded-3xl border p-6 lg:p-8" style="border-color: var(--border-color, #e5e7eb);">
                    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">@lang('qr.product.info_label')</p>
                            <h2 class="text-2xl font-extrabold">{{ $productName }}</h2>
                        </div>
                        <span class="text-3xl font-black text-emerald-600">${{ number_format($product->final_price, 2) }}</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-500 mb-3">
                                @lang('qr.product.colors_label')
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                @forelse($colors as $color)
                                    <div class="flex items-center gap-2 px-3 py-2 rounded-xl border" style="border-color: var(--border-color, #e5e7eb);">
                                        <span class="w-6 h-6 rounded-full border" style="background: {{ $color }}"></span>
                                        <span class="text-sm font-semibold">{{ $color }}</span>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500">
                                        @lang('qr.product.no_colors')
                                    </p>
                                @endforelse
                            </div>
                        </div>
                        <div>
                            <h3 class="text-sm font-semibold uppercase tracking-widest text-gray-500 mb-3">
                                @lang('qr.product.sizes_label')
                            </h3>
                            <div class="flex flex-wrap gap-2">
                                @forelse($sizes as $size)
                                    <span class="px-4 py-2 rounded-xl border font-semibold"
                                          style="border-color: var(--border-color, #e5e7eb);">
                                        {{ $size }}
                                    </span>
                                @empty
                                    <p class="text-sm text-gray-500">
                                        @lang('qr.product.no_sizes')
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 text-sm leading-relaxed text-gray-600">
                        {{ $productDescription ?? __('qr.product.fallback_story') }}
                    </div>
                </div>

                <!-- Product Media Gallery - Material 3 -->
                @php
                    $productMedia = $product->media->where('role', 'product_image')->values();
                    $hasProductMedia = $productMedia->count() > 0;
                @endphp
                @if($hasProductMedia)
                    <div class="rounded-3xl overflow-hidden shadow-lg border" style="border-color: var(--border-color, #e5e7eb); background: #fafafa;">
                        <!-- Gallery Header -->
                        <div class="px-6 lg:px-8 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-2">
                                <div class="text-2xl">📸</div>
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">@lang('qr.product.gallery_title', ['count' => $productMedia->count()] ?? 'Product Gallery')</h3>
                                    <p class="text-sm text-gray-600 mt-0.5">{{ $productMedia->count() }} @lang('qr.product.gallery_images', ['count' => $productMedia->count()] ?? 'images')</p>
                                </div>
                            </div>
                        </div>

                        <!-- Gallery Container -->
                        <div class="px-6 lg:px-8 pb-8">
                            <!-- Main Image Viewer -->
                            <div class="relative rounded-2xl overflow-hidden mb-6 bg-gray-100 aspect-square group shadow-md">
                                <img 
                                    id="productMainImage" 
                                    src="{{ $productMedia->first()?->url ?? 'https://placehold.co/600x600?text=' . urlencode($productName) }}" 
                                    alt="{{ $productName }}"
                                    class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
                                >
                                <!-- Image Counter Badge -->
                                <div class="absolute top-4 {{ $isArabic ? 'left-4' : 'right-4' }} px-4 py-2 rounded-full bg-black/50 backdrop-blur-md text-white text-sm font-semibold">
                                    <span id="imageCounter">1</span> / {{ $productMedia->count() }}
                                </div>

                                <!-- Loading State -->
                                <div class="absolute inset-0 bg-black/0 transition-colors duration-300" id="imageLoadingState"></div>
                            </div>

                            <!-- Thumbnail Carousel -->
                            <div class="relative">
                                <div class="overflow-x-auto scrollbar-hide pb-2">
                                    <div class="flex gap-3 min-w-max px-0.5">
                                        @foreach($productMedia as $index => $media)
                                            <button
                                                type="button"
                                                class="group relative flex-shrink-0 w-20 h-20 rounded-xl overflow-hidden border-2 transition-all duration-300 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2"
                                                style="border-color: {{ $index === 0 ? 'var(--primary-yellow, #eab308)' : 'var(--border-color, #e5e7eb)' }};"
                                                data-image-index="{{ $index }}"
                                                data-image-url="{{ $media->url }}"
                                            >
                                                <img 
                                                    src="{{ $media->url }}" 
                                                    alt="Thumbnail {{ $index + 1 }}"
                                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                                >
                                                <!-- Active Indicator -->
                                                <div class="absolute inset-0 bg-white/0 group-hover:bg-white/10 transition-colors"></div>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Scroll Indicators (if needed) -->
                                @if($productMedia->count() > 4)
                                    <div class="absolute right-0 top-0 bottom-0 w-8 pointer-events-none bg-[#fafafa]"></div>
                                @endif
                            </div>

                            <!-- Image Info -->
                            <div class="mt-6 p-4 rounded-xl bg-white border" style="border-color: var(--border-color, #e5e7eb);">
                                <div class="flex items-start gap-3">
                                    <div class="text-2xl flex-shrink-0">ℹ️</div>
                                    <div class="text-sm text-gray-700 leading-relaxed">
                                        <p class="font-semibold mb-1">@lang('qr.product.gallery_tip_title')</p>
                                        <p class="text-gray-600">@lang('qr.product.gallery_tip_body')</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </section>

    <!-- Hero Section and QR Preview at Bottom -->
    <section class="relative overflow-hidden" style="background: #0f172a;">
        <div class="absolute inset-0 opacity-50 pointer-events-none">
            <div class="w-72 h-72 bg-[#e6b800]/40 blur-3xl rounded-full absolute -top-16 {{ $isArabic ? '-right-10' : '-left-10' }}"></div>
            <div class="w-96 h-96 bg-[#00d2ff]/20 blur-3xl rounded-full absolute bottom-0 {{ $isArabic ? 'left-0' : 'right-0' }}"></div>
        </div>
        <div class="container mx-auto px-4 py-14 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10 items-center">
                <div class="text-white space-y-6">
                    <div class="flex flex-wrap gap-3 text-sm">
                        @if($cityName)
                            <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20">{{ $cityName }}</span>
                        @endif
                        <span class="px-3 py-1 rounded-full bg-white/10 border border-white/20">
                            @lang('qr.field_experience_chip')
                        </span>
                    </div>
                    <div>
                        <p class="uppercase tracking-[0.3em] text-xs text-white/60 mb-3">
                            @lang('qr.hero_tagline')
                        </p>
                        <h1 class="text-3xl lg:text-4xl font-extrabold leading-snug">
                            {{ $productName }}
                        </h1>
                        <p class="mt-4 text-base text-white/80 leading-relaxed">
                            {{ $productDescription ?? __('qr.hero_fallback') }}
                        </p>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                        <div class="py-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-2xl font-extrabold">{{ $landmarkCount }}</div>
                            <p class="text-xs text-white/70 mt-1">@lang('qr.stats.linked_landmarks')</p>
                        </div>
                        <div class="py-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-2xl font-extrabold">{{ number_format($product->final_price, 0) }} $</div>
                            <p class="text-xs text-white/70 mt-1">@lang('qr.stats.final_price')</p>
                        </div>
                        <div class="py-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-2xl font-extrabold">{{ $product->is_package ? __('qr.stats.package_yes') : __('qr.stats.package_single') }}</div>
                            <p class="text-xs text-white/70 mt-1">@lang('qr.stats.experience_type')</p>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('product.show', $product->uuid) }}" class="btn-yellow px-6 py-3 font-bold">
                            @lang('qr.cta.view_product')
                        </a>
                        <button
                            type="button"
                            class="px-6 py-3 rounded-xl border border-white/30 text-white/90 hover:bg-white/10 transition"
                            data-copy-link
                            data-link="{{ $experienceUrl }}"
                        >
                            @lang('qr.cta.copy_link')
                        </button>
                    </div>
                </div>

                <div>
                    <div class="rounded-3xl bg-white/90 shadow-2xl p-6 lg:p-8 space-y-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-500">@lang('qr.card.qr_label')</p>
                                <h2 class="text-xl font-bold mt-1">{{ $productName }}</h2>
                            </div>
                            <span class="px-3 py-1 rounded-full text-xs font-semibold" style="background: var(--gray-bg, #f5f5f5); color: var(--primary-yellow, #eab308);">
                                @lang('qr.card.ready_badge')
                            </span>
                        </div>
                        <div class="rounded-2xl p-6 bg-slate-900">
                            <img src="{{ $qrUrl }}" alt="QR {{ $productName }}" class="mx-auto w-56 h-56 object-contain">
                            <p class="text-center text-white/80 text-sm mt-4">
                                @lang('qr.card.scan_tip')
                            </p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ $qrUrl }}" download="qr-{{ $product->uuid }}.svg"
                               class="flex-1 text-center px-4 py-3 rounded-xl font-semibold text-white"
                               style="background: var(--primary-yellow, #eab308);">
                                @lang('qr.card.download_qr')
                            </a>
                            <a href="{{ $experienceUrl }}" target="_blank"
                               class="flex-1 text-center px-4 py-3 rounded-xl font-semibold border"
                               style="border-color: var(--border-color, #dfe3e8);">
                                @lang('qr.card.open_experience')
                            </a>
                        </div>
                        <div class="text-sm text-gray-500 leading-relaxed">
                            @lang('qr.card.dynamic_note')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            @foreach($steps as $index => $step)
                <div class="rounded-2xl border px-6 py-8 bg-white/80" style="border-color: var(--border-color, #e5e7eb);">
                    <div class="w-12 h-12 rounded-full flex items-center justify-center font-bold mb-5
                        {{ in_array($index, [0,2]) ? 'text-white' : '' }}"
                        style="background: {{ $index === 1 ? 'var(--gray-bg, #f3f4f6)' : 'var(--primary-yellow, #eab308)' }};">
                        {{ $index + 1 }}
                    </div>
                    <h3 class="text-xl font-bold mb-3">{{ $step['title'] }}</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $step['body'] }}</p>
                </div>
            @endforeach
        </div>
    </section>

    <div id="landmarkExperienceModal" class="fixed inset-0 hidden z-50" style="z-index: 9999;">
        <div class="absolute inset-0 bg-black/70 backdrop-blur-sm" data-close-modal></div>
        <div class="relative mx-auto my-8 md:my-12 w-full max-w-5xl px-4">
            <div class="rounded-3xl overflow-hidden shadow-2xl bg-white max-h-[90vh] flex flex-col">
                <div class="grid grid-cols-1 lg:grid-cols-2 flex-1 overflow-hidden">
                    <div class="relative">
                        <img id="landmarkExperienceImage" src="" alt="" class="w-full h-64 lg:h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/0"></div>
                        <div class="absolute bottom-4 {{ $isArabic ? 'left-4' : 'right-4' }} text-white">
                            <p class="text-xs uppercase tracking-[0.3em] text-white/70" id="landmarkExperienceType"></p>
                            <h3 class="text-2xl font-bold mt-2" id="landmarkExperienceName"></h3>
                            <p class="text-sm text-white/80 mt-1" id="landmarkExperienceCity"></p>
                        </div>
                    </div>
                    <div class="p-6 lg:p-8 space-y-5 overflow-y-auto">
                        <div class="flex items-center justify-between sticky top-0 bg-white z-10 pb-4 mb-4 border-b">
                            <p class="text-sm font-semibold text-gray-800 flex-1">@lang('qr.modal.header_label')</p>
                            <button type="button" class="w-10 h-10 rounded-full border hover:bg-gray-100 text-gray-600 shrink-0" data-close-modal>✕</button>
                        </div>
                        <p class="text-base text-gray-900 leading-relaxed" id="landmarkExperienceHistory"></p>
                        <div class="rounded-2xl bg-white border p-4" style="border-color: var(--border-color, #e5e7eb);">
                            <p class="text-xs font-semibold tracking-wide text-gray-500 mb-2">@lang('qr.modal.ambient_label')</p>
                            <p class="text-sm text-gray-800" id="landmarkExperienceAmbient"></p>
                        </div>
                        <div>
                            <p class="text-xs font-semibold tracking-wide text-gray-500 mb-2">@lang('qr.modal.timeline_label')</p>
                            <ul class="space-y-3 text-sm text-gray-800" id="landmarkExperienceTimeline"></ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const copySuccessText = @json(__('qr.cta.copy_success'));
    const copyResetText = @json(__('qr.cta.copy_link'));

    document.querySelectorAll('[data-copy-link]').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const link = this.getAttribute('data-link');
            if (!link) return;
            navigator.clipboard.writeText(link).then(() => {
                this.textContent = copySuccessText;
                this.classList.add('bg-white/10');
                setTimeout(() => {
                    this.textContent = copyResetText;
                    this.classList.remove('bg-white/10');
                }, 2500);
            });
        });
    });

    // Product Gallery Functionality
    (function() {
        const mainImage = document.getElementById('productMainImage');
        const imageCounter = document.getElementById('imageCounter');
        const imageLoadingState = document.getElementById('imageLoadingState');

        if (!mainImage) return;

        const thumbnails = document.querySelectorAll('[data-image-index]');
        let currentIndex = 0;

        const updateImage = (index) => {
            const thumbnail = thumbnails[index];
            if (!thumbnail) return;

            const imageUrl = thumbnail.getAttribute('data-image-url');
            currentIndex = index;

            // Loading state
            imageLoadingState.classList.add('bg-black/10');

            // Preload image
            const img = new Image();
            img.onload = () => {
                mainImage.src = imageUrl;
                imageCounter.textContent = index + 1;
                
                // Update border
                thumbnails.forEach((thumb, idx) => {
                    const borderColor = idx === index ? 'var(--primary-yellow, #eab308)' : 'var(--border-color, #e5e7eb)';
                    thumb.style.borderColor = borderColor;
                });

                imageLoadingState.classList.remove('bg-black/10');
            };
            img.src = imageUrl;
        };

        // Thumbnail click handler
        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', () => {
                const index = parseInt(thumbnail.getAttribute('data-image-index'));
                updateImage(index);
            });
        });

        // Keyboard navigation
        document.addEventListener('keydown', (e) => {
            if (e.key === 'ArrowLeft') {
                const newIndex = Math.max(0, currentIndex - 1);
                updateImage(newIndex);
                thumbnails[newIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            } else if (e.key === 'ArrowRight') {
                const newIndex = Math.min(thumbnails.length - 1, currentIndex + 1);
                updateImage(newIndex);
                thumbnails[newIndex].scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }
        });
    })();

    (function(){
        const modal = document.getElementById('landmarkExperienceModal');
        if (!modal) return;

        const imageEl = document.getElementById('landmarkExperienceImage');
        const nameEl = document.getElementById('landmarkExperienceName');
        const typeEl = document.getElementById('landmarkExperienceType');
        const cityEl = document.getElementById('landmarkExperienceCity');
        const historyEl = document.getElementById('landmarkExperienceHistory');
        const ambientEl = document.getElementById('landmarkExperienceAmbient');
        const timelineEl = document.getElementById('landmarkExperienceTimeline');

        const renderList = (container, items) => {
            container.innerHTML = '';
            (items || []).forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-3';
                li.innerHTML = `<span>•</span><p class="flex-1">${item}</p>`;
                container.appendChild(li);
            });
        };

        const openModal = (payload) => {
            imageEl.src = payload.image || 'https://placehold.co/600x600?text=Landmark';
            imageEl.alt = payload.name || 'Landmark';
            nameEl.textContent = payload.name || '';
            typeEl.textContent = payload.type || '';
            cityEl.textContent = payload.city || '';
            historyEl.textContent = payload.history || payload.short || '';
            ambientEl.textContent = payload.ambient || '';

            renderList(timelineEl, payload.timeline);

            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        };

        const closeModal = () => {
            modal.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        };

        document.querySelectorAll('[data-landmark-trigger]').forEach(card => {
            card.addEventListener('click', () => {
                try {
                    const payload = JSON.parse(card.getAttribute('data-landmark'));
                    openModal(payload);
                } catch (error) {
                    console.warn('Unable to open landmark experience', error);
                }
            });
        });

        modal.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', closeModal);
        });

        modal.addEventListener('click', (event) => {
            if (event.target.dataset.closeModal !== undefined || event.target === modal) {
                closeModal();
            }
        });

        document.addEventListener('keydown', (event) => {
            if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    })();
</script>
@endpush