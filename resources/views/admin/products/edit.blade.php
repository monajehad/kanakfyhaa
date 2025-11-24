@extends('layouts.layoutMaster')

@section('title', 'Edit Product')

@section('content')
@php
    function str_starts_with_any_helper($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if (strpos($haystack, $needle) === 0) return true;
        }
        return false;
    }
    function colors_to_array_helper($colors) {
        if (is_array($colors)) return $colors;
        if (is_string($colors)) {
            $arr = array_filter(array_map('trim', explode(',', $colors)));
            return $arr;
        }
        return [];
    }
    function sizes_to_array_helper($sizes) {
        if (is_array($sizes)) return $sizes;
        if (is_string($sizes)) {
            $sizes = trim($sizes);
            if ($sizes !== '') {
                $decoded = json_decode($sizes, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return array_map('trim', array_filter($decoded, function($v) {
                        return !is_null($v) && $v !== '';
                    }));
                }
                return array_map('trim', array_filter(explode(',', $sizes)));
            }
        }
        return [];
    }
@endphp

<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">تعديل منتج: {{ $product->name }}</h4>
    </div>
    <div class="card-body">
        <form id="productForm" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')
            <div class="row g-3">

                <!-- Product Name -->
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم المنتج <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قميص رجالي" required value="{{ old('name', $product->name) }}">
                </div>

                <!-- Product Title -->
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان</label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان مختصر للمنتج" value="{{ old('title', $product->title) }}">
                </div>

                <!-- Arabic Name -->
                <div class="col-md-6">
                    <label class="form-label" for="name_ar">اسم المنتج (عربي)</label>
                    <input class="form-control" type="text" id="name_ar" name="name_ar" placeholder="اسم المنتج بالعربية" value="{{ old('name_ar', $product->name_ar) }}">
                </div>

                <!-- English Name -->
                <div class="col-md-6">
                    <label class="form-label" for="name_en">اسم المنتج (إنجليزي)</label>
                    <input class="form-control" type="text" id="name_en" name="name_en" placeholder="Product Name in English" value="{{ old('name_en', $product->name_en) }}">
                </div>

                <!-- Short Description -->
                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" placeholder="نبذة سريعة عن المنتج" value="{{ old('short_description', $product->short_description) }}">
                </div>

                <!-- Detailed Description -->
                <div class="col-md-6">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="تفاصيل عن المنتج...">{{ old('description', $product->description) }}</textarea>
                </div>

                <!-- Arabic Detailed Description -->
                <div class="col-md-6">
                    <label class="form-label" for="description_ar">وصف تفصيلي (عربي)</label>
                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3" placeholder="الوصف بالعربية...">{{ old('description_ar', $product->description_ar) }}</textarea>
                </div>

                <!-- English Detailed Description -->
                <div class="col-md-6">
                    <label class="form-label" for="description_en">وصف تفصيلي (إنجليزي)</label>
                    <textarea class="form-control" id="description_en" name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en', $product->description_en) }}</textarea>
                </div>

                <!-- Main Color -->
                <div class="col-md-4">
                    <label class="form-label" for="color">اللون</label>
                    <input class="form-control" type="text" id="color" name="color" placeholder="مثال: أبيض/أحمر" value="{{ old('color', $product->color) }}">
                </div>

                <!-- Colors Picker Section -->
                <div class="col-md-4">
                    <label class="form-label" for="product_colors">الألوان 
                        <small>(اختر لون وأضف للقائمة أو التقط لون من الشاشة)</small>
                    </label>
                    <div class="input-group mb-2">
                        <input type="color" class="form-control form-control-color" id="colorPicker" name="colorPicker" value="#000000" title="اختر اللون">
                        <input type="text" class="form-control" id="colorHexInput" placeholder="#000000" style="max-width:150px;">
                        <button type="button" class="btn btn-outline-primary" id="addColorBtn">إضافة اللون</button>
                        <button type="button" class="btn btn-outline-secondary" id="pickScreenColorBtn" title="التقط لون من الشاشة">
                            <svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" fill="currentColor" class="bi bi-color-picker" viewBox="0 0 16 16"><path d="M10.615 3.708a.5.5 0 0 0-.707 0l-6.9 6.9A1.5 1.5 0 0 0 1.5 13a1.5 1.5 0 0 0 2.122 0l6.9-6.9a.5.5 0 1 0-.707-.708l-6.9 6.9a.5.5 0 0 1-.707 0 .5.5 0 0 1 0-.707l6.9-6.9a1.5 1.5 0 0 1 2.122 2.122l-6.9 6.9a2.5 2.5 0 0 1-3.536-3.535l6.9-6.9a.5.5 0 1 0-.707-.708l-6.9 6.9a3.5 3.5 0 1 0 4.95 4.95l6.9-6.9a2.5 2.5 0 1 0-3.535-3.535z"/></svg>
                        </button>
                    </div>
                    <div id="colorsList" class="mb-1" style="display:flex; flex-wrap:wrap; gap:8px;"></div>
                    <input type="hidden" name="colors" id="colors" value="">
                </div>

                <!-- Sizes Picker Section -->
                <div class="col-md-4">
                    <label class="form-label" for="sizes">المقاسات</label>
                    <div id="sizesUXArea">
                        <div class="input-group mb-2">
                            <input type="text" class="form-control" id="sizeInput" placeholder="أدخل مقاس مثل S أو M أو L">
                            <button type="button" class="btn btn-outline-primary" id="addSizeBtn">إضافة مقاس</button>
                        </div>
                        <div id="sizesList" class="mb-1" style="display:flex;flex-wrap:wrap;gap:6px;"></div>
                        <input type="hidden" name="sizes" id="sizes" value="">
                    </div>
                </div>

                <!-- City Selector -->
                <div class="col-md-4">
                    <label class="form-label" for="city_id">المدينة</label>
                    <select class="form-select" id="city_id" name="city_id">
                        <option value="">-- اختر المدينة --</option>
                        @foreach($cities ?? [] as $city)
                            <option value="{{ $city->id }}" {{ old('city_id', $product->city_id) == $city->id ? 'selected' : '' }}>
                                {{ $city->name_ar ?? $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Price Fields -->
                <div class="col-md-4">
                    <label class="form-label" for="price_cost">سعر التكلفة</label>
                    <input class="form-control" type="number" id="price_cost" name="price_cost" min="0" step="0.01" value="{{ old('price_cost', $product->price_cost ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="price_sell">سعر البيع</label>
                    <input class="form-control" type="number" id="price_sell" name="price_sell" min="0" step="0.01" value="{{ old('price_sell', $product->price_sell ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="price">السعر</label>
                    <input class="form-control" type="number" id="price" name="price" min="0" step="0.01" value="{{ old('price', $product->price ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="discount">الخصم (%)</label>
                    <input class="form-control" type="number" id="discount" name="discount" min="0" max="100" step="0.01" value="{{ old('discount', $product->discount ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="shipping_price">سعر الشحن</label>
                    <input class="form-control" type="number" id="shipping_price" name="shipping_price" min="0" step="0.01" value="{{ old('shipping_price', $product->shipping_price ?? 0) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="image">رابط الصورة (URL)</label>
                    <input class="form-control" type="url" id="image" name="image" placeholder="https://example.com/image.jpg" value="{{ old('image', $product->image) }}">
                </div>

            </div>

            <!-- Main Image Section -->
            <div class="card mt-4">
                <div class="card-header d-flex align-items-center">
                    <strong>الصورة الرئيسية</strong>
                </div>
                <div class="card-body d-flex align-items-center">
                    @php
                        $main = $product->media->where('role', 'main')->first();
                    @endphp
                    <div class="me-3">
                        @if($main)
                            <a href="{{ $main->url ? (str_starts_with_any_helper($main->url, ['http://', 'https://']) ? $main->url : asset($main->url)) : '#' }}" target="_blank">
                                <img src="{{ $main->thumbnail_url
                                    ? (str_starts_with_any_helper($main->thumbnail_url, ['http://', 'https://']) ? $main->thumbnail_url : asset($main->thumbnail_url))
                                    : (str_starts_with_any_helper($main->url, ['http://', 'https://']) ? $main->url : asset($main->url)) }}"
                                    alt="{{ $main->alt_text ?? 'Main Image' }}" style="max-width: 85px; max-height: 85px; border-radius: 7px;border:1px solid #eee;">
                            </a>
                        @else
                            <span class="text-muted">لا توجد صورة رئيسية حالياً.</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <input class="form-control" type="file" id="main_image" name="main_image" accept="image/*">
                        <small class="text-muted">اترك الحقل فارغاً إن لم ترغب بتغيير الصورة الرئيسية.</small>
                    </div>
                </div>
            </div>

            <!-- Sub Images Section -->
            <div class="card mt-4">
                <div class="card-header">
                    <strong>صور إضافية</strong> <small class="text-muted">(يمكن تحديد أكثر من صورة)</small>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                    <small class="text-muted d-block">رفع صور جديدة سيحل محل جميع الصور الإضافية السابقة.</small>

                    @php
                        $subImages = $product->media->where('role', 'sub');
                    @endphp

                    @if($subImages->count())
                        <div class="mt-3">
                            <label class="form-label"><strong>الصور الحالية:</strong></label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @foreach($subImages as $image)
                                    <div class="position-relative" style="display:inline-block;">
                                        <a href="{{ $image->url ? (str_starts_with_any_helper($image->url, ['http://', 'https://']) ? $image->url : asset($image->url)) : '#' }}" target="_blank" class="d-inline-block" title="{{ $image->alt_text }}">
                                            <img
                                                src="{{ $image->thumbnail_url
                                                    ? (str_starts_with_any_helper($image->thumbnail_url, ['http://', 'https://']) ? $image->thumbnail_url : asset($image->thumbnail_url))
                                                    : (str_starts_with_any_helper($image->url, ['http://', 'https://']) ? $image->url : asset($image->url)) }}"
                                                alt="{{ $image->alt_text ?? 'Sub Image' }}"
                                                style="max-width:60px;max-height:60px;object-fit:cover;border-radius:4px;border:1px solid #dfdfdf;">
                                        </a>
                                        <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: -10px; right: -10px; padding: 2px 6px; font-size: 11px;" onclick="deleteSubImage({{ $image->id }})">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Switches Section -->
            <div class="row">
                <div class="col-md-3 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="published" name="published" value="1" {{ old('published', $product->published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="published">نشر المنتج</label>
                    </div>
                </div>
                <div class="col-md-3 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_package" name="is_package" value="1" {{ old('is_package', $product->is_package) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_package">بكج كامل</label>
                    </div>
                </div>
            </div>

            <!-- Package Fields -->
            <div id="packageFields" style="display: {{ old('is_package', $product->is_package) ? 'contents' : 'none' }}; width: 100%;">
                <div class="row g-3 mt-2">
                    <div class="col-md-12">
                        <h5 class="card-title mb-3">معلومات الباقة</h5>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_name">اسم الباقة</label>
                        <input class="form-control" type="text" id="package_name" name="package_name" placeholder="مثل: باقة فاخرة" value="{{ old('package_name', $product->package?->name) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_name_ar">اسم الباقة (عربي)</label>
                        <input class="form-control" type="text" id="package_name_ar" name="package_name_ar" placeholder="اسم الباقة بالعربية" value="{{ old('package_name_ar', $product->package?->name_ar) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_name_en">اسم الباقة (إنجليزي)</label>
                        <input class="form-control" type="text" id="package_name_en" name="package_name_en" placeholder="Package Name in English" value="{{ old('package_name_en', $product->package?->name_en) }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_description">وصف الباقة</label>
                        <textarea class="form-control" id="package_description" name="package_description" rows="2" placeholder="وصف الباقة...">{{ old('package_description', $product->package?->description) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_description_ar">وصف الباقة (عربي)</label>
                        <textarea class="form-control" id="package_description_ar" name="package_description_ar" rows="2" placeholder="الوصف بالعربية...">{{ old('package_description_ar', $product->package?->description_ar) }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="package_description_en">وصف الباقة (إنجليزي)</label>
                        <textarea class="form-control" id="package_description_en" name="package_description_en" rows="2" placeholder="Description in English...">{{ old('package_description_en', $product->package?->description_en) }}</textarea>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="package_price">سعر الباقة</label>
                        <input class="form-control" type="number" id="package_price" name="package_price" min="0" step="0.01" value="{{ old('package_price', $product->package?->price ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="package_original_price">السعر الأصلي (اختياري)</label>
                        <input class="form-control" type="number" id="package_original_price" name="package_original_price" min="0" step="0.01" value="{{ old('package_original_price', $product->package?->original_price) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="package_discount">خصم الباقة (%)</label>
                        <input class="form-control" type="number" id="package_discount" name="package_discount" min="0" max="100" step="0.01" value="{{ old('package_discount', $product->package?->discount ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="package_shipping">تكلفة الشحن</label>
                        <input class="form-control" type="number" id="package_shipping" name="package_shipping" min="0" step="0.01" value="{{ old('package_shipping', $product->package?->shipping_price ?? 0) }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label" for="package_quantity">الكمية المتاحة</label>
                        <input class="form-control" type="number" id="package_quantity" name="package_quantity" min="1" value="{{ old('package_quantity', $product->package?->quantity ?? 1) }}">
                    </div>
                    <div class="col-md-4 mt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="package_is_active" name="package_is_active" value="1" {{ old('package_is_active', $product->package?->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="package_is_active">تفعيل الباقة</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label" for="package_items">محتويات الباقة (أصناف) <small>(أضف أسطر جديدة)</small></label>
                        <div id="packageItemsContainer">
                            @if($product->package && $product->package->items && count($product->package->items) > 0)
                                @foreach($product->package->items as $index => $item)
                                <div class="package-item row g-2 mb-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="اسم الصنف" name="package_items[{{ $index }}][name]" value="{{ $item['name'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="الاسم عربي" name="package_items[{{ $index }}][name_ar]" value="{{ $item['name_ar'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="English Name" name="package_items[{{ $index }}][name_en]" value="{{ $item['name_en'] ?? '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">حذف</button>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <div class="package-item row g-2 mb-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="اسم الصنف" name="package_items[0][name]">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="الاسم عربي" name="package_items[0][name_ar]">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="English Name" name="package_items[0][name_en]">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-item">حذف</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <button type="button" id="addItemBtn" class="btn btn-sm btn-outline-primary mt-2">+ إضافة صنف</button>
                    </div>
                </div>
            </div>
            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث المنتج</button>
        </form>
    </div>
</div>

<!-- Required Plugins -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
function deleteSubImage(imageId) {
    Swal.fire({
        title: 'حذف الصورة',
        text: 'هل أنت متأكد من حذف هذه الصورة؟',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'حذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/admin/products/{{ $product->id }}/media/${imageId}`)
                .then(() => {
                    Swal.fire('نجح', 'تم حذف الصورة بنجاح', 'success').then(() => {
                        location.reload();
                    });
                })
                .catch(error => {
                    Swal.fire('خطأ', 'فشل حذف الصورة', 'error');
                });
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    @php
        $colorsArr = [];
        if (old('colors')) {
            $colorsArr = colors_to_array_helper(old('colors'));
        } elseif($product->colors) {
            $colorsArr = colors_to_array_helper($product->colors);
        }
        $jsColorsArr = [];
        foreach ($colorsArr as $c) {
            $c = strtoupper(trim($c));
            if ($c && preg_match('/^#?[0-9A-F]{6}$/i', $c)) {
                $jsColorsArr[] = strpos($c, '#') === 0 ? $c : ('#' . $c);
            }
        }
        $sizesArr = [];
        if (old('sizes')) {
            $sizesArr = sizes_to_array_helper(old('sizes'));
        } elseif($product->sizes) {
            $sizesArr = sizes_to_array_helper($product->sizes);
        }
        $jsSizesArr = [];
        foreach ($sizesArr as $s) {
            $s = trim($s);
            if (!empty($s)) $jsSizesArr[] = $s;
        }
    @endphp

    const initialColors = {!! json_encode($jsColorsArr) !!};
    const initialSizes = {!! json_encode($jsSizesArr) !!};

    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');
    const isPackageCheckbox = document.getElementById('is_package');
    const packageFields = document.getElementById('packageFields');
    const addItemBtn = document.getElementById('addItemBtn');
    const packageItemsContainer = document.getElementById('packageItemsContainer');
    const colorPicker = document.getElementById('colorPicker');
    const colorHexInput = document.getElementById('colorHexInput');
    const addColorBtn = document.getElementById('addColorBtn');
    const colorsList = document.getElementById('colorsList');
    const colorsField = document.getElementById('colors');
    const pickScreenColorBtn = document.getElementById('pickScreenColorBtn');
    let selectedColors = Array.isArray(initialColors) ? initialColors.slice() : [];

    const sizeInput = document.getElementById('sizeInput');
    const addSizeBtn = document.getElementById('addSizeBtn');
    const sizesList = document.getElementById('sizesList');
    const sizesField = document.getElementById('sizes');
    let selectedSizes = Array.isArray(initialSizes) ? initialSizes.slice() : [];

    // --- SIZES UX ---
    function renderSizes() {
        sizesList.innerHTML = '';
        selectedSizes.forEach(function(sz, idx) {
            const badge = document.createElement('div');
            badge.className = 'badge bg-secondary d-flex align-items-center px-2 py-1';
            badge.style.gap = '5px; font-size: 1em; border-radius: 6px; user-select: none;';
            badge.innerHTML =
                `<span style="font-family:monospace;margin-left:3px;">${sz}</span>
                <button type="button" class="btn btn-sm btn-link text-danger px-1 py-0 remove-size" style="font-size:1.1em" title="إزالة" data-size-idx="${idx}">&times;</button>`;
            sizesList.appendChild(badge);
        });
        sizesField.value = selectedSizes.join(',');
    }

    addSizeBtn.addEventListener('click', function() {
        let szVal = sizeInput.value.trim();
        if (!szVal) {
            Swal.fire({ icon: 'error', text: 'يرجى إدخال المقاس أولاً.' });
            return;
        }
        if (!/^[a-zA-Z0-9-_]+$/.test(szVal)) {
            Swal.fire({ icon: 'error', text: 'يرجى إدخال مقاس صالح (حروف أو أرقام فقط).' });
            return;
        }
        if (selectedSizes.includes(szVal)) {
            Swal.fire({ icon: 'warning', text: 'تم إضافة هذا المقاس مسبقًا.' });
            return;
        }
        selectedSizes.push(szVal);
        sizeInput.value = '';
        renderSizes();
    });
    sizesList.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-size')) {
            const idx = e.target.getAttribute('data-size-idx');
            if (idx !== null && typeof idx !== 'undefined') {
                selectedSizes.splice(idx, 1);
                renderSizes();
            }
        }
    });
    sizeInput.addEventListener('keydown', function(e) {
        if (e.key === "Enter") {
            e.preventDefault();
            addSizeBtn.click();
        }
    });
    renderSizes();

    // --- COLORS UX ---
    function renderColors() {
        colorsList.innerHTML = '';
        selectedColors.forEach(function(clr) {
            const cdiv = document.createElement('div');
            cdiv.className = 'd-flex align-items-center';
            cdiv.style.gap = '2px';
            cdiv.innerHTML =
                `<span style="display:inline-block;width:28px;height:28px;border-radius:50%;background-color:${clr};border:1.5px solid #666;margin-left:3px;"></span>
                <span style="font-family:monospace;font-size:0.95em;margin-left:2px">${clr}</span>
                <button type="button" class="btn btn-sm btn-link text-danger px-1 py-0 remove-color" title="إزالة" data-color="${clr}">&times;</button>`;
            colorsList.appendChild(cdiv);
        });
        colorsField.value = selectedColors.join(',');
    }
    colorPicker.addEventListener('input', function() {
        colorHexInput.value = colorPicker.value.toUpperCase();
    });
    colorHexInput.addEventListener('input', function() {
        let val = colorHexInput.value.trim();
        if (!val.startsWith('#')) val = '#' + val;
        if (/^#[0-9A-Fa-f]{6}$/.test(val)) {
            colorPicker.value = val;
        }
    });
    addColorBtn.addEventListener('click', function() {
        let colorVal = colorPicker.value.toUpperCase();
        if (!/^#[0-9A-F]{6}$/.test(colorVal)) {
            Swal.fire({ icon: 'error', text: 'يرجى اختيار لون صحيح' });
            return;
        }
        if (selectedColors.includes(colorVal)) {
            Swal.fire({ icon: 'warning', text: 'تم إضافة هذا اللون مسبقًا.' });
            return;
        }
        selectedColors.push(colorVal);
        renderColors();
    });
    colorsList.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-color')) {
            const _clr = e.target.getAttribute('data-color');
            selectedColors = selectedColors.filter(c => c !== _clr);
            renderColors();
        }
    });
    renderColors();

    if ('EyeDropper' in window) {
        pickScreenColorBtn.addEventListener('click', async function() {
            try {
                const eyeDropper = new window.EyeDropper();
                const result = await eyeDropper.open();
                if (result && result.sRGBHex) {
                    colorPicker.value = result.sRGBHex;
                    colorHexInput.value = result.sRGBHex.toUpperCase();
                }
            } catch(e) {
                Swal.fire({ icon: 'error', text: 'لم يتم التقاط لون. تأكد أن المتصفح يدعم الأداة.' });
            }
        });
        pickScreenColorBtn.disabled = false;
    } else {
        pickScreenColorBtn.disabled = true;
        pickScreenColorBtn.title = 'غير مدعوم في هذا المتصفح';
    }

    // --- PACKAGE ITEMS UX ---
    isPackageCheckbox.addEventListener('change', function() {
        packageFields.style.display = this.checked ? 'contents' : 'none';
    });
    addItemBtn.addEventListener('click', function() {
        const itemCount = packageItemsContainer.children.length;
        const newItem = document.createElement('div');
        newItem.className = 'package-item row g-2 mb-2';
        newItem.innerHTML = `
            <div class="col-md-4">
                <input class="form-control" type="text" placeholder="اسم الصنف" name="package_items[${itemCount}][name]">
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" placeholder="الاسم عربي" name="package_items[${itemCount}][name_ar]">
            </div>
            <div class="col-md-3">
                <input class="form-control" type="text" placeholder="English Name" name="package_items[${itemCount}][name_en]">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-sm btn-outline-danger remove-item">حذف</button>
            </div>
        `;
        packageItemsContainer.appendChild(newItem);
        newItem.querySelector('.remove-item').addEventListener('click', function() {
            newItem.remove();
        });
    });
    packageItemsContainer.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-item')) {
            e.preventDefault();
            e.target.closest('.package-item').remove();
        }
    });

    // --- FORM SUBMIT HANDLING ---
    function validateForm() {
        let errors = [];
        if (!form.name.value.trim()) {
            errors.push('اسم المنتج مطلوب.');
        }
        if (form.short_description.value.length > 255) {
            errors.push('الوصف القصير طويل جدا.');
        }
        if (form.price_cost.value === "" || isNaN(form.price_cost.value) || Number(form.price_cost.value) < 0) {
            errors.push('سعر التكلفة غير صحيح.');
        }
        if (form.price_sell.value === "" || isNaN(form.price_sell.value) || Number(form.price_sell.value) < 0) {
            errors.push('سعر البيع غير صحيح.');
        }
        if (form.discount.value !== "" && (isNaN(form.discount.value) || Number(form.discount.value) < 0 || Number(form.discount.value) > 100)) {
            errors.push('الخصم يجب أن يكون بين 0 و 100.');
        }
        if (form.is_package.checked) {
            if (!form.package_name.value.trim()) {
                errors.push('اسم الباقة مطلوب عند تفعيل بكج كامل.');
            }
            if (form.package_price.value === "" || isNaN(form.package_price.value) || Number(form.package_price.value) < 0) {
                errors.push('سعر الباقة غير صحيح.');
            }
            const items = packageItemsContainer.querySelectorAll('.package-item');
            if (items.length === 0) {
                errors.push('يجب إضافة أصناف واحد على الأقل للباقة.');
            }
        }
        if (form.main_image.files.length > 0) {
            for (const file of form.main_image.files) {
                if (!file.type.startsWith('image/')) {
                    errors.push('الصورة الرئيسية يجب أن تكون صورة.');
                    break;
                }
            }
        }
        const subImagesInput = form.querySelector('[name="sub_images[]"]');
        if (subImagesInput && subImagesInput.files.length > 0) {
            for (const file of subImagesInput.files) {
                if (!file.type.startsWith('image/')) {
                    errors.push('كل الصور الإضافية يجب أن تكون من نوع صورة.');
                    break;
                }
            }
        }
        return errors;
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        let errors = validateForm();

        if (errors.length) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', html: errors.join('<br>') });
            submitBtn.disabled = false;
            return;
        }

        let formData = new FormData(form);

        // Handle sizes - send empty string if no sizes
        formData.delete('sizes');
        if (selectedSizes.length > 0) {
            selectedSizes.forEach((sz, i) => formData.append('sizes[' + i + ']', sz));
        } else {
            // Send empty string to explicitly clear sizes
            formData.append('sizes', '');
        }

        // Handle colors - send empty string if no colors
        formData.delete('colors');
        if (selectedColors.length > 0) {
            selectedColors.forEach((clr, i) => formData.append('colors[' + i + ']', clr));
        } else {
            // Send empty string to explicitly clear colors
            formData.append('colors', '');
        }

        if (form.published) {
            formData.set('published', form.published.checked ? 1 : 0);
        }
        if (form.is_package) {
            formData.set('is_package', form.is_package.checked ? 1 : 0);
        }
        if (form.package_is_active) {
            formData.set('package_is_active', form.package_is_active.checked ? 1 : 0);
        }

        const subImagesInput = form.querySelector('[name="sub_images[]"]');
        if (subImagesInput && subImagesInput.files.length > 0) {
            formData.append('replace_sub_images', '1');
        }

        submitBtn.innerText = 'يتم التحديث...';

        axios.post('{{ route('admin.products.update', $product->id) }}', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(() => {
            Swal.fire({ icon: 'success', title: 'نجاح', text: 'تم تحديث المنتج بنجاح.' });
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل التحديث', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'تحديث المنتج';
        });
    });

});
</script>
@endsection