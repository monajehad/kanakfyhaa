@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
    $configData = Helper::appClasses();
    $url = route('experience.show', $product->uuid);
@endphp

@extends('layouts/layoutMaster')

@section('title', 'تفاصيل المنتج')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
        <h4 class="mb-0">تفاصيل المنتج</h4>
        <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning" title="تعديل المنتج">
            <i class="ri ri-pencil-line me-1"></i> تعديل
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <div class="row g-4 align-items-center">

                {{-- الصورة الرئيسية --}}
                <div class="col-lg-4 text-center mb-4 mb-lg-0">
                    @php
                        $mainImage = $product->media->where('role', 'main')->first();
                    @endphp

                    @if ($mainImage)
                        <img src="{{ $mainImage->url }}" alt="{{ $mainImage->alt_text ?? $product->name }}"
                            class="rounded border" style="max-width: 95%; max-height: 270px;">
                        @if ($mainImage->alt_text)
                            <small class="d-block mt-1 text-muted">{{ $mainImage->alt_text }}</small>
                        @endif
                    @else
                        <div class="text-center text-muted"
                            style="min-height:180px;display:flex;align-items:center;justify-content:center;">
                            <i class="ri-image-line" style="font-size:60px"></i><br>
                        </div>
                        <span class="text-muted d-block">لا توجد صورة رئيسية</span>
                    @endif

                    {{-- صور إضافية --}}
                    @php
                        $subImages = $product->media->where('role', 'sub');
                    @endphp
                    @if ($subImages->count())
                        <div class="mt-3">
                            <strong>صور إضافية:</strong>
                            <div class="d-flex flex-wrap gap-2 justify-content-center mt-2">
                                @foreach ($subImages as $media)
                                    <div>
                                        <img src="{{ $media->url }}"
                                            alt="{{ $media->alt_text ?? $product->name }}"
                                            style="max-width: 65px; max-height: 65px; border:1px solid #ddd; border-radius:5px; padding:1px;">
                                        @if ($media->alt_text)
                                            <div class="text-xs text-muted pt-1">
                                                {{ \Illuminate\Support\Str::limit($media->alt_text, 13) }}</div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <span class="d-block text-muted mt-2">لا توجد صور إضافية</span>
                    @endif
                </div>

                {{-- بيانات المنتج --}}
                <div class="col-lg-8">
                    <dl class="row mb-0 gy-3">
                        <dt class="col-sm-4">رقم المنتج</dt>
                        <dd class="col-sm-8">{{ $product->id }}</dd>

                        <dt class="col-sm-4">المدينة</dt>
                        <dd class="col-sm-8">{{ optional($product->city)->name ?? '-' }}</dd>

                        <dt class="col-sm-4">اسم المنتج</dt>
                        <dd class="col-sm-8 fw-bold">{{ $product->name }}</dd>

                        <dt class="col-sm-4">العنوان</dt>
                        <dd class="col-sm-8">{{ $product->title ?? '-' }}</dd>

                        <dt class="col-sm-4">وصف قصير</dt>
                        <dd class="col-sm-8">{{ $product->short_description ?? '-' }}</dd>

                        <dt class="col-sm-4">الوصف التفصيلي</dt>
                        <dd class="col-sm-8">{!! $product->description ?? '-' !!}</dd>

                        <dt class="col-sm-4">اللون</dt>
                        <dd class="col-sm-8">{{ $product->color ?? '-' }}</dd>

                        <dt class="col-sm-4">المقاسات</dt>
                        <dd class="col-sm-8">
                         @if (is_array($product->sizes) && count($product->sizes))
                           {!! '<span class="badge bg-light text-dark fw-normal">' .
                             implode('</span> <span class="badge bg-light text-dark fw-normal">', $product->sizes) .
                           '</span>' !!}
                         @else
                             -
                             @endif

                        </dd>

                        <dt class="col-sm-4">سعر التكلفة</dt>
                        <dd class="col-sm-8"><span class="badge bg-secondary fs-6">${{ number_format($product->price_cost, 2) }}</span></dd>

                        <dt class="col-sm-4">سعر البيع</dt>
                        <dd class="col-sm-8"><span class="badge bg-primary fs-6">${{ number_format($product->price_sell, 2) }}</span></dd>

                        <dt class="col-sm-4">الخصم</dt>
                        <dd class="col-sm-8">
                            @if ($product->discount && $product->discount > 0)
                                <span class="badge bg-danger fs-6">{{ $product->discount }}%</span>
                            @else
                                <span class="text-muted">لا يوجد</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">UUID</dt>
                        <dd class="col-sm-8"><span class="text-monospace dir-ltr user-select-all"
                                style="font-size:smaller">{{ $product->uuid }}</span></dd>

                        <dt class="col-sm-4">رمز QR</dt>
                        <dd class="col-sm-8">
                            <div class="text-center my-3">
                                <h6 class="fw-bold mb-2">رمز QR لتجربة المنتج</h6>
                                {!! QrCode::size(180)->generate($url) !!}
                                <p class="mt-2 small text-muted user-select-all">{{ $url }}</p>
                            </div>
                        </dd>

                        <dt class="col-sm-4">الحالة</dt>
                        <dd class="col-sm-8">
                            @if ($product->published)
                                <span class="badge bg-success px-3 py-2 fs-6">منشور</span>
                            @else
                                <span class="badge bg-secondary px-3 py-2 fs-6">مسودة</span>
                            @endif
                        </dd>

                        <dt class="col-sm-4">تاريخ الإنشاء</dt>
                        <dd class="col-sm-8">{{ $product->created_at?->format('Y-m-d H:i') }}</dd>

                        <dt class="col-sm-4">آخر تحديث</dt>
                        <dd class="col-sm-8">{{ $product->updated_at?->format('Y-m-d H:i') }}</dd>
                    </dl>
                </div>
            </div>
        </div>
    </div>
@endsection
