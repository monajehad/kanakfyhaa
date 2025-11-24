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
        $artifactCount = $landmarks->sum(function ($landmark) {
            return $landmark->artifacts?->count() ?? 0;
        });
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

    <section class="relative overflow-hidden" style="background: radial-gradient(circle at top, #0f172a, #020617);">
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
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                        <div class="py-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-2xl font-extrabold">{{ $landmarkCount }}</div>
                            <p class="text-xs text-white/70 mt-1">@lang('qr.stats.linked_landmarks')</p>
                        </div>
                        <div class="py-4 rounded-2xl bg-white/5 border border-white/10">
                            <div class="text-2xl font-extrabold">{{ $artifactCount }}</div>
                            <p class="text-xs text-white/70 mt-1">@lang('qr.stats.artifacts')</p>
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
                        <div class="rounded-2xl p-6 bg-gradient-to-br from-slate-900 to-slate-800">
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

    <section class="container mx-auto px-4 pb-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
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

                <div class="rounded-3xl border p-6 lg:p-8" style="border-color: var(--border-color, #e5e7eb);">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold">@lang('qr.landmarks.section_title')</h2>
                        <span class="text-sm text-gray-500">{{ $landmarkCount }} @lang('qr.landmarks.count_label')</span>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        @forelse($landmarks->take(4) as $landmark)
                            @php
                                $landmarkCover = optional($landmark->media->first())->url ?? $landmark->image ?? '';
                                $artifactsCount = $landmark->artifacts?->count() ?? 0;
                                $immersivePayload = [
                                    'name' => $landmark->name,
                                    'type' => $landmark->type ?? __('qr.landmarks.type_fallback'),
                                    'city' => $cityName,
                                    'image' => $landmarkCover,
                                    'history' => $landmark->description ?? $landmark->short_description,
                                    'short' => \Illuminate\Support\Str::limit($landmark->short_description ?? $landmark->description, 140),
                                    'timeline' => [
                                        __('qr.timeline.phase_one'),
                                        __('qr.timeline.phase_two'),
                                        __('qr.timeline.phase_three'),
                                    ],
                                    'artifacts' => $landmark->artifacts?->take(3)->map(function($artifact){
                                        $artifactImage = optional($artifact->media->first())->url ?? $artifact->image ?? '';
                                        return [
                                            'title' => $artifact->title,
                                            'image' => $artifactImage,
                                            'excerpt' => \Illuminate\Support\Str::limit($artifact->description, 110),
                                            'details' => strip_tags($artifact->description) ?? '',
                                        ];
                                    })->values() ?? [],
                                    'ambient' => __('qr.landmarks.ambient_default'),
                                ];
                            @endphp
                            <button
                                type="button"
                                class="rounded-2xl border overflow-hidden text-start landmark-card focus:outline-none focus:ring-2 focus:ring-offset-2"
                                style="border-color: var(--border-color, #e5e7eb);"
                                data-landmark-trigger
                                data-landmark='@json($immersivePayload)'
                            >
                                <div class="relative">
                                    <img src="{{ $landmarkCover }}" alt="{{ $landmark->name }}" class="w-full h-40 object-cover"
                                         onerror="this.style.display='none'">
                                    <span class="absolute top-4 {{ $isArabic ? 'left-4' : 'right-4' }} bg-white/80 text-xs font-semibold px-3 py-1 rounded-full">
                                        {{ $landmark->type ?? trans('qr.landmarks.type_fallback') }}
                                    </span>
                                </div>
                                <div class="p-4 space-y-2">
                                    <h3 class="text-lg font-semibold">{{ $landmark->name }}</h3>
                                    <p class="text-sm text-gray-500">
                                        {{
                                            \Illuminate\Support\Str::limit(
                                                $landmark->short_description ?? $landmark->description,
                                                90
                                            )
                                        }}
                                    </p>
                                    <div class="flex items-center gap-2 text-xs text-gray-400">
                                        <span>⏳ @lang('qr.landmarks.card_action')</span>
                                        <span>•</span>
                                        <span>{{ $artifactsCount }} @lang('qr.landmarks.pieces_label')</span>
                                    </div>
                                </div>
                            </button>
                        @empty
                            <p class="text-gray-500 text-sm">@lang('qr.landmarks.empty')</p>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="rounded-3xl border p-6 lg:p-8 h-fit" style="border-color: var(--border-color, #e5e7eb); background: var(--gray-bg, #f8fafc);">
                <h2 class="text-xl font-bold mb-4">@lang('qr.why.title')</h2>
                <ul class="space-y-4 text-sm text-gray-700 leading-relaxed">
                    <li>
                        <span class="font-semibold">@lang('qr.why.consistent_title')</span>
                        @lang('qr.why.consistent_body')
                    </li>
                    <li>
                        <span class="font-semibold">@lang('qr.why.performance_title')</span>
                        @lang('qr.why.performance_body')
                    </li>
                    <li>
                        <span class="font-semibold">@lang('qr.why.print_title')</span>
                        @lang('qr.why.print_body')
                    </li>
                    <li>
                        <span class="font-semibold">@lang('qr.why.guided_title')</span>
                        @lang('qr.why.guided_body')
                    </li>
                </ul>
            </div>
        </div>
    </section>

    <div id="landmarkExperienceModal" class="fixed inset-0 hidden z-[1200]">
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
                    <div class="p-6 lg:p-8 space-y-6 overflow-y-auto">
                        <div class="flex items-center justify-between sticky top-0 bg-white pb-4">
                            <p class="text-sm font-semibold text-gray-800">@lang('qr.modal.header_label')</p>
                            <button type="button" class="w-10 h-10 rounded-full border hover:bg-gray-100 text-gray-600" data-close-modal>✕</button>
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
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-semibold tracking-wide text-gray-500">@lang('qr.modal.artifacts_label')</p>
                                <span class="text-xs text-gray-400">@lang('qr.modal.artifacts_hint')</span>
                            </div>
                            <div class="space-y-3" id="landmarkExperienceArtifacts"></div>
                            <div class="rounded-2xl border p-4 mt-4" style="border-color: var(--border-color, #e5e7eb);" id="landmarkArtifactDetail">
                                <p class="text-sm text-gray-500">
                                    @lang('qr.modal.artifact_detail_hint')
                                </p>
                            </div>
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
        const artifactsEl = document.getElementById('landmarkExperienceArtifacts');
        const artifactDetailEl = document.getElementById('landmarkArtifactDetail');

        const artifactDetailEmptyText = @json(__('qr.modal.artifact_detail_hint'));
        const artifactShareText = @json(__('qr.modal.artifact_share_cta'));
        const artifactShareSuccess = @json(__('qr.modal.artifact_share_success'));
        const artifactExploreHint = @json(__('qr.modal.artifact_explore_hint'));

        const renderList = (container, items) => {
            container.innerHTML = '';
            (items || []).forEach(item => {
                const li = document.createElement('li');
                li.className = 'flex items-start gap-3';
                li.innerHTML = `<span>•</span><p class="flex-1">${item}</p>`;
                container.appendChild(li);
            });
        };

        const setArtifactDetail = (artifact = null) => {
            if (!artifactDetailEl) return;
            if (!artifact) {
                artifactDetailEl.innerHTML = `<p class="text-sm text-gray-500">${artifactDetailEmptyText}</p>`;
                return;
            }

            artifactDetailEl.innerHTML = `
                <div class="flex gap-3 mb-4">
                    <img src="${artifact.image || 'https://placehold.co/96'}" alt="${artifact.title || ''}" class="w-20 h-20 rounded-2xl object-cover" onerror="this.src='https://placehold.co/96'">
                    <div>
                        <p class="text-sm font-semibold text-gray-900">${artifact.title || ''}</p>
                        <p class="text-sm text-gray-600 mt-2">${artifact.details || artifact.excerpt || artifact.title || ''}</p>
                    </div>
                </div>
                <button type="button" class="w-full px-4 py-3 rounded-2xl text-sm font-semibold text-white" style="background: var(--primary-yellow, #eab308);" data-artifact-share>
                    ${artifactShareText}
                </button>
            `;

            const shareBtn = artifactDetailEl.querySelector('[data-artifact-share]');
            if (shareBtn) {
                shareBtn.addEventListener('click', () => {
                    const payload = `${artifact.title || ''} - ${artifact.details || artifact.excerpt || ''}`.trim();
                    navigator.clipboard.writeText(payload).then(() => {
                        shareBtn.textContent = artifactShareSuccess;
                        setTimeout(() => shareBtn.textContent = artifactShareText, 2000);
                    });
                });
            }
        };

        const renderArtifacts = (container, items) => {
            container.innerHTML = '';
            let activeButton = null;

            const activate = (button, artifact) => {
                if (activeButton) {
                    activeButton.classList.remove('ring-2', 'ring-amber-300', 'border-transparent', 'shadow-md');
                    activeButton.style.borderColor = 'var(--border-color, #e5e7eb)';
                }
                activeButton = button;
                button.classList.add('ring-2', 'ring-amber-300', 'border-transparent', 'shadow-md');
                button.style.borderColor = 'transparent';
                setArtifactDetail(artifact);
            };

            if (!(items || []).length) {
                setArtifactDetail();
                return;
            }

            (items || []).forEach((artifact, index) => {
                const button = document.createElement('button');
                button.type = 'button';
                button.setAttribute('data-artifact-item', 'true');
                button.className = 'flex w-full text-left gap-3 rounded-2xl border p-3 transition hover:border-amber-300 focus:outline-none';
                button.style.borderColor = 'var(--border-color, #e5e7eb)';
                button.innerHTML = `
                    <img src="${artifact.image || 'https://placehold.co/96'}" alt="${artifact.title || ''}" class="w-16 h-16 rounded-xl object-cover" onerror="this.src='https://placehold.co/96'">
                    <div>
                        <p class="font-semibold text-sm text-gray-900">${artifact.title || ''}</p>
                        <p class="text-xs text-gray-500 mt-1">${artifact.excerpt || ''}</p>
                        <span class="text-[11px] text-amber-600 mt-1 inline-flex items-center gap-1">
                            <span>↗</span> ${artifactExploreHint}
                        </span>
                    </div>
                `;
                button.addEventListener('click', () => activate(button, artifact));
                container.appendChild(button);

                if (index === 0) {
                    activate(button, artifact);
                }
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
            renderArtifacts(artifactsEl, payload.artifacts);

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