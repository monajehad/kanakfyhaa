@extends('layouts/layoutMaster')

@section('title', 'Create Product')

@section('content')
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">إنشاء منتج جديد</h4>
    </div>
    <div class="card-body">
        <form id="productForm" enctype="multipart/form-data" autocomplete="off">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم المنتج <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قميص رجالي" value="{{ old('name') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان</label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان مختصر للمنتج" value="{{ old('title') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="name_ar">اسم المنتج (عربي)</label>
                    <input class="form-control" type="text" id="name_ar" name="name_ar" placeholder="اسم المنتج بالعربية" value="{{ old('name_ar') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="name_en">اسم المنتج (إنجليزي)</label>
                    <input class="form-control" type="text" id="name_en" name="name_en" placeholder="Product Name in English" value="{{ old('name_en') }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" placeholder="نبذة سريعة عن المنتج" value="{{ old('short_description') }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="تفاصيل عن المنتج...">{{ old('description') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description_ar">وصف تفصيلي (عربي)</label>
                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3" placeholder="الوصف بالعربية...">{{ old('description_ar') }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="description_en">وصف تفصيلي (إنجليزي)</label>
                    <textarea class="form-control" id="description_en" name="description_en" rows="3" placeholder="Description in English...">{{ old('description_en') }}</textarea>
                </div>

                {{-- Colors Picker Section, identical to edit --}}
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

                {{-- Sizes Picker Section, identical to edit --}}
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

                <div class="col-md-4">
                    <label class="form-label" for="city_id">المدينة</label>
                    <select class="form-select" id="city_id" name="city_id">
                        <option value="">-- اختر المدينة --</option>
                        @foreach($cities ?? [] as $city)
                            <option value="{{ $city->id }}" @if(old('city_id') == $city->id) selected @endif>{{ $city->name_ar ?? $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="categories">الفئات</label>
                    <select class="form-select select2" id="categories" name="categories[]" multiple data-placeholder="اختر الفئات">
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" @if(in_array($category->id, old('categories', []))) selected @endif>{{ $category->name_ar ?? $category->name }}</option>
                        @endforeach
                    </select>
                    <small class="text-muted d-block mt-1">اختر فئة أو أكثر للمنتج</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price_cost">سعر التكلفة</label>
                    <input class="form-control" type="number" id="price_cost" name="price_cost" min="0" step="0.01" value="{{ old('price_cost', 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price_sell">سعر البيع</label>
                    <input class="form-control" type="number" id="price_sell" name="price_sell" min="0" step="0.01" value="{{ old('price_sell', 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price">السعر</label>
                    <input class="form-control" type="number" id="price" name="price" min="0" step="0.01" value="{{ old('price', 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="discount">الخصم (%)</label>
                    <input class="form-control" type="number" id="discount" name="discount" min="0" max="100" step="0.01" value="{{ old('discount', 0) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="image">رابط الصورة (URL)</label>
                    <input class="form-control" type="url" id="image" name="image" placeholder="https://example.com/image.jpg" value="{{ old('image') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="main_image">الصورة الرئيسية <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="main_image" name="main_image" accept="image/*" @if(!old('main_image')) required @endif>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="sub_images">صور إضافية <small>(يمكن تحديد أكثر من صورة)</small></label>
                    <input class="form-control" type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                </div>

                <div class="col-md-3 mt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="published" name="published" value="1" @if(old('published', true)) checked @endif>
                        <label class="form-check-label" for="published">نشر المنتج</label>
                    </div>
                </div>

                <div class="col-md-3 mt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="is_package" name="is_package" value="1" @if(old('is_package')) checked @endif>
                        <label class="form-check-label" for="is_package">بكج كامل</label>
                    </div>
                </div>

                <!-- Package Fields (shown when is_package is checked) -->
                <div id="packageFields" style="display: none; width: 100%;">
                    <div class="col-md-12 mt-4">
                        <h5 class="card-title mb-3">معلومات الباقة</h5>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_name">اسم الباقة</label>
                        <input class="form-control" type="text" id="package_name" name="package_name" placeholder="مثل: باقة فاخرة" value="{{ old('package_name') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_name_ar">اسم الباقة (عربي)</label>
                        <input class="form-control" type="text" id="package_name_ar" name="package_name_ar" placeholder="اسم الباقة بالعربية" value="{{ old('package_name_ar') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_name_en">اسم الباقة (إنجليزي)</label>
                        <input class="form-control" type="text" id="package_name_en" name="package_name_en" placeholder="Package Name in English" value="{{ old('package_name_en') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_description">وصف الباقة</label>
                        <textarea class="form-control" id="package_description" name="package_description" rows="2" placeholder="وصف الباقة...">{{ old('package_description') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_description_ar">وصف الباقة (عربي)</label>
                        <textarea class="form-control" id="package_description_ar" name="package_description_ar" rows="2" placeholder="الوصف بالعربية...">{{ old('package_description_ar') }}</textarea>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label" for="package_description_en">وصف الباقة (إنجليزي)</label>
                        <textarea class="form-control" id="package_description_en" name="package_description_en" rows="2" placeholder="Description in English...">{{ old('package_description_en') }}</textarea>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="package_price">سعر الباقة</label>
                        <input class="form-control" type="number" id="package_price" name="package_price" min="0" step="0.01" value="{{ old('package_price', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="package_original_price">السعر الأصلي (اختياري)</label>
                        <input class="form-control" type="number" id="package_original_price" name="package_original_price" min="0" step="0.01" value="{{ old('package_original_price') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="package_discount">خصم الباقة (%)</label>
                        <input class="form-control" type="number" id="package_discount" name="package_discount" min="0" max="100" step="0.01" value="{{ old('package_discount', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="package_shipping">تكلفة الشحن</label>
                        <input class="form-control" type="number" id="package_shipping" name="package_shipping" min="0" step="0.01" value="{{ old('package_shipping', 0) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label" for="package_quantity">الكمية المتاحة</label>
                        <input class="form-control" type="number" id="package_quantity" name="package_quantity" min="1" value="{{ old('package_quantity', 1) }}">
                    </div>

                    <div class="col-md-4 mt-2">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="package_is_active" name="package_is_active" value="1" @if(old('package_is_active', true)) checked @endif>
                            <label class="form-check-label" for="package_is_active">تفعيل الباقة</label>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label" for="package_items">محتويات الباقة (أصناف) <small>(أضف أسطر جديدة)</small></label>
                        <div id="packageItemsContainer">
                        @php
                            $packageItems = old('package_items', []);
                        @endphp
                        @if(count($packageItems))
                            @foreach($packageItems as $i => $item)
                                <div class="package-item row g-2 mb-2">
                                    <div class="col-md-4">
                                        <input class="form-control" type="text" placeholder="اسم الصنف" name="package_items[{{ $i }}][name]" value="{{ $item['name'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="الاسم عربي" name="package_items[{{ $i }}][name_ar]" value="{{ $item['name_ar'] ?? '' }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input class="form-control" type="text" placeholder="English Name" name="package_items[{{ $i }}][name_en]" value="{{ $item['name_en'] ?? '' }}">
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

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">حفظ المنتج</button>
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
    // --- SIZES ---
    const sizeInput = document.getElementById('sizeInput');
    const addSizeBtn = document.getElementById('addSizeBtn');
    const sizesList = document.getElementById('sizesList');
    const sizesField = document.getElementById('sizes');

    let selectedColors = [];
    let colorsFromOld = "{{ old('colors') }}";
    if(colorsFromOld) {
        selectedColors = colorsFromOld.split(',').map(clr => clr.trim()).filter(Boolean);
    }

    // --- SIZES LOGIC ---
    let selectedSizes = [];
    let sizesOldValue = @json(old('sizes', []));
    if (Array.isArray(sizesOldValue)) {
        selectedSizes = sizesOldValue.filter(sz => !!sz && typeof sz === 'string');
    } else if (typeof sizesOldValue === 'string' && sizesOldValue.trim() !== '') {
        try {
            let attemptArray = JSON.parse(sizesOldValue);
            if (Array.isArray(attemptArray)) {
                selectedSizes = attemptArray.filter(sz => !!sz && typeof sz === 'string');
            } else {
                selectedSizes = sizesOldValue.split(',').map(s => s.trim()).filter(Boolean);
            }
        } catch {
            selectedSizes = sizesOldValue.split(',').map(s => s.trim()).filter(Boolean);
        }
    }
    function renderSizes() {
        sizesList.innerHTML = '';
        selectedSizes.forEach(function(sz, idx) {
            const szDiv = document.createElement('div');
            szDiv.className = 'badge bg-secondary d-flex align-items-center';
            szDiv.style.gap = '5px';
            szDiv.style.padding = '9px 12px';
            szDiv.style.fontSize = '1em';
            szDiv.innerHTML = `
                <span>${sz}</span>
                <button type="button" class="btn btn-sm btn-link text-danger px-1 py-0 remove-size" title="إزالة" data-size="${encodeURIComponent(sz)}">&times;</button>
            `;
            sizesList.appendChild(szDiv);
        });
        sizesField.value = selectedSizes.join(',');
    }

    addSizeBtn.addEventListener('click', function() {
        let sizeVal = (sizeInput.value || '').trim();
        if (!sizeVal) return;
        // Optionally add more validation here (e.g. regex)
        if (selectedSizes.includes(sizeVal)) {
            Swal.fire({ icon: 'warning', text: 'تمت إضافة هذا المقاس مسبقاً.' });
            return;
        }
        selectedSizes.push(sizeVal);
        sizeInput.value = "";
        renderSizes();
    });

    sizeInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            addSizeBtn.click();
        }
    });

    sizesList.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-size')) {
            const _sz = decodeURIComponent(e.target.getAttribute('data-size'));
            selectedSizes = selectedSizes.filter(sz => sz !== _sz);
            renderSizes();
        }
    });

    renderSizes();

    // Show/hide package fields on loaded (to sync with old)
    function togglePackageFields() {
        packageFields.style.display = isPackageCheckbox.checked ? 'block' : 'none';
    }
    togglePackageFields();
    isPackageCheckbox.addEventListener('change', togglePackageFields);

    // --- Colors logic ---
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
        updateColorsField();
    });

    function removeColor(color) {
        selectedColors = selectedColors.filter(c => c !== color);
        renderColors();
        updateColorsField();
    }

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
    }
    renderColors();

    colorsList.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-color')) {
            const _clr = e.target.getAttribute('data-color');
            removeColor(_clr);
        }
    });

    function updateColorsField() {
        colorsField.value = selectedColors.join(',');
    }
    updateColorsField();

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

    // Add package item
    addItemBtn.addEventListener('click', function() {
        // Find next free index for incremental naming (in case items have been deleted)
        let itemCount = 0;
        let indexes = [];
        packageItemsContainer.querySelectorAll('.package-item').forEach(div => {
            let input = div.querySelector('input[name^="package_items["]');
            if(input) {
                const match = input.name.match(/\[(\d+)\]/);
                if(match) indexes.push(parseInt(match[1]));
            }
        });
        if (indexes.length) {
            itemCount = Math.max(...indexes) + 1;
        }
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
        if (e.target.classList.contains('remove-item')) {
            e.preventDefault();
            e.target.closest('.package-item').remove();
        }
    });

    function validateForm() {
        let errors = [];
        if (!form.name.value.trim()) {
            errors.push('اسم المنتج مطلوب.');
        }

        if (!form.main_image.files.length) {
            errors.push('يجب رفع صورة رئيسية.');
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

        // Validate at least one color if colors used/required
        if (selectedColors.length === 0) {
            errors.push('يرجى إضافة لون واحد على الأقل للمنتج.');
        }

        // Validate sizes
        // At least one size for validity if sizes required (optional)
        if (selectedSizes && selectedSizes.some(sz => !sz.match(/^[a-zA-Z0-9\s\-_.]+$/))) {
            errors.push('صيغة المقاسات غير صحيحة (استخدم حروف, أرقام, شرطة، نقاط فقط).');
        }

        // Validate package fields if is_package is checked
        if (form.is_package.checked) {
            if (!form.package_name.value.trim()) {
                errors.push('اسم الباقة مطلوب عند تفعيل بكج كامل.');
            }
            if (form.package_price.value === "" || isNaN(form.package_price.value) || Number(form.package_price.value) < 0) {
                errors.push('سعر الباقة غير صحيح.');
            }
            const items = packageItemsContainer.querySelectorAll('.package-item');
            // At least one item with at least one nonempty name
            let hasItem = false;
            items.forEach((div) => {
                let iName = div.querySelector('input[name$="[name]"]');
                if(iName && iName.value.trim()) hasItem = true;
            });
            if (!hasItem) {
                errors.push('يجب إضافة صنف واحد على الأقل للباقة.');
            }
        }

        for (const file of form.main_image.files) {
            if (!file.type.startsWith('image/')) {
                errors.push('الصورة الرئيسية يجب أن تكون صورة.');
                break;
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
            formData.append('sizes', '');
        }

        // Handle colors - send empty string if no colors
        formData.delete('colors');
        if (selectedColors.length > 0) {
            selectedColors.forEach((clr, i) => formData.append('colors[' + i + ']', clr));
        } else {
            formData.append('colors', '');
        }

        if (form.published) {
            formData.set('published', form.published.checked ? 1 : 0);
        }

        if (form.is_package) {
            formData.set('is_package', form.is_package.checked ? 1 : 0);
        }

        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.products.store') }}', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(function(response) {
            Swal.fire({ icon: 'success', title: 'نجاح', text: 'تم إنشاء المنتج بنجاح.' });
            form.reset();
            selectedColors = [];
            selectedSizes = [];
            renderColors();
            renderSizes();
            updateColorsField();
            // Optionally: Reset dynamic package items
            packageItemsContainer.innerHTML = `
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
            `;
        })
        .catch(function(error) {
            let msg = 'حدث خطأ غير متوقع.';
            if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: msg });
        })
        .finally(function() {
            submitBtn.disabled = false;
            submitBtn.innerText = 'حفظ المنتج';
        });
    });
});
</script>

</script>

@include('admin.products.select2-init')
@endsection
