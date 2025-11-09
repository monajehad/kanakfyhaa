@extends('layouts/layoutMaster')

@section('title', 'Edit Product')

@section('content')
@php
    // Helper functions to replace Str::startsWith
    function str_starts_with_any($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if (strpos($haystack, $needle) === 0) return true;
        }
        return false;
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
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم المنتج <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قميص رجالي" required value="{{ old('name', $product->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان</label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان مختصر للمنتج" value="{{ old('title', $product->title) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="name_ar">اسم المنتج (عربي)</label>
                    <input class="form-control" type="text" id="name_ar" name="name_ar" placeholder="اسم المنتج بالعربية" value="{{ old('name_ar', $product->name_ar) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="name_en">اسم المنتج (إنجليزي)</label>
                    <input class="form-control" type="text" id="name_en" name="name_en" placeholder="Product Name in English" value="{{ old('name_en', $product->name_en) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" placeholder="نبذة سريعة عن المنتج" value="{{ old('short_description', $product->short_description) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="تفاصيل عن المنتج...">{{ old('description', $product->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description_ar">وصف تفصيلي (عربي)</label>
                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3" placeholder="الوصف بالعربية...">{{ old('description_ar', $product->description_ar) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description_en">وصف تفصيلي (إنجليزي)</label>
                    <textarea class="form-control" id="description_en" name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en', $product->description_en) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="color">اللون</label>
                    <input class="form-control" type="text" id="color" name="color" placeholder="مثال: أبيض/أحمر" value="{{ old('color', $product->color) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="colors">الألوان <small>(أكواد hex مثل: #000000,#FFFFFF)</small></label>
                    <input class="form-control" type="text" id="colors" name="colors" placeholder="#000000, #FFFFFF, #C8D400"
                        value="{{ old('colors', is_array($product->colors) ? implode(', ', $product->colors) : ($product->colors ?? '')) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sizes">المقاسات <small>(افصل القيم بفاصلة)</small></label>
                    <input class="form-control" type="text" id="sizes" name="sizes"
                        placeholder="S, M, L, XL"
                        value="{{ old('sizes', is_array($product->sizes) ? implode(', ', $product->sizes) : $product->sizes) }}">
                </div>

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

                <div class="col-md-4">
                    <label class="form-label" for="price_cost">سعر التكلفة</label>
                    <input class="form-control" type="number" id="price_cost" name="price_cost" min="0" step="0.01"
                        value="{{ old('price_cost', $product->price_cost ?? 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price_sell">سعر البيع</label>
                    <input class="form-control" type="number" id="price_sell" name="price_sell" min="0" step="0.01"
                        value="{{ old('price_sell', $product->price_sell ?? 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price">السعر</label>
                    <input class="form-control" type="number" id="price" name="price" min="0" step="0.01"
                        value="{{ old('price', $product->price ?? 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="discount">الخصم (%)</label>
                    <input class="form-control" type="number" id="discount" name="discount" min="0" max="100" step="0.01"
                        value="{{ old('discount', $product->discount ?? 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="image">رابط الصورة (URL)</label>
                    <input class="form-control" type="url" id="image" name="image" placeholder="https://example.com/image.jpg"
                        value="{{ old('image', $product->image) }}">
                </div>
            </div>


            {{-- Main Image Section --}}
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
                            <a href="{{ $main->url ? (str_starts_with_any($main->url, ['http://', 'https://']) ? $main->url : asset($main->url)) : '#' }}" target="_blank">
                                <img src="{{ $main->thumbnail_url
                                    ? (str_starts_with_any($main->thumbnail_url, ['http://', 'https://']) ? $main->thumbnail_url : asset($main->thumbnail_url))
                                    : (str_starts_with_any($main->url, ['http://', 'https://']) ? $main->url : asset($main->url)) }}"
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

            {{-- Sub Images Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>صور إضافية</strong> <small class="text-muted">(يمكن تحديد أكثر من صورة)</small>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                    @php
                        $subImages = $product->media->where('role', 'sub');
                    @endphp
                    @if($subImages->count())
                        <div class="mt-2 d-flex flex-wrap align-items-center">
                            @foreach($subImages as $image)
                                <div class="me-2 mb-2 position-relative" style="display:inline-block;">
                                    <a href="{{ $image->url ? (str_starts_with_any($image->url, ['http://', 'https://']) ? $image->url : asset($image->url)) : '#' }}" target="_blank" class="d-inline-block" 
                                        title="{{ $image->alt_text }}">
                                        <img 
                                            src="{{ $image->thumbnail_url
                                                ? (str_starts_with_any($image->thumbnail_url, ['http://', 'https://']) ? $image->thumbnail_url : asset($image->thumbnail_url))
                                                : (str_starts_with_any($image->url, ['http://', 'https://']) ? $image->url : asset($image->url)) }}"
                                            alt="{{ $image->alt_text ?? 'Sub Image' }}" 
                                            style="max-width:60px;max-height:60px;object-fit:cover;border-radius:4px;border:1px solid #dfdfdf;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">رفع الصور الإضافية يحل محل جميع الصور الإضافية السابقة.</small>
                    @else
                        <span class="text-muted">لا توجد صور إضافية حالياً.</span>
                    @endif
                </div>
            </div>

            <div class="row">
                <div class="col-md-3 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="published" name="published" value="1"
                            {{ old('published', $product->published) ? 'checked' : '' }}>
                        <label class="form-check-label" for="published">نشر المنتج</label>
                    </div>
                </div>

                <div class="col-md-3 mt-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_package" name="is_package" value="1"
                            {{ old('is_package', $product->is_package) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_package">بكج كامل</label>
                    </div>
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث المنتج</button>
        </form>
    </div>
</div>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('productForm');
    const submitBtn = document.getElementById('submitBtn');

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

        let sizes = form.sizes.value.trim();
        if (sizes) {
            if (!/^[a-zA-Z0-9_,\s]+$/.test(sizes)) {
                errors.push('صيغة المقاسات غير صحيحة (استخدم حروف, أرقام, وفواصل فقط).');
            }
            let sizesArray = sizes.split(',').map(s => s.trim()).filter(Boolean);
            if (!Array.isArray(sizesArray)) {
                errors.push('صيغة المقاسات غير صحيحة.');
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

        let sizesRaw = form.sizes.value.trim();
        if (sizesRaw) {
            let arrSizes = sizesRaw.split(',').map(s => s.trim()).filter(Boolean);
            formData.delete('sizes');
            arrSizes.forEach((sz, i) => formData.append('sizes[' + i + ']', sz));
        } else {
            formData.delete('sizes');
        }

        let colorsRaw = form.colors?.value.trim();
        if (colorsRaw) {
            let arrColors = colorsRaw.split(',').map(c => c.trim()).filter(Boolean);
            formData.delete('colors');
            arrColors.forEach((clr, i) => formData.append('colors[' + i + ']', clr));
        } else {
            formData.delete('colors');
        }

        if (form.published) {
            formData.set('published', form.published.checked ? 1 : 0);
        }

        if (form.is_package) {
            formData.set('is_package', form.is_package.checked ? 1 : 0);
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
            console.log(error.response);
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
