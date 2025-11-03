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
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قميص رجالي" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان</label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان مختصر للمنتج">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" placeholder="نبذة سريعة عن المنتج">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="تفاصيل عن المنتج..."></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="color">اللون</label>
                    <input class="form-control" type="text" id="color" name="color" placeholder="مثال: أبيض/أحمر">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="sizes">المقاسات <small>(افصل القيم بفاصلة)</small></label>
                    <input class="form-control" type="text" id="sizes" name="sizes" placeholder="S, M, L, XL">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price_cost">سعر التكلفة</label>
                    <input class="form-control" type="number" id="price_cost" name="price_cost" min="0" step="0.01" value="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="price_sell">سعر البيع</label>
                    <input class="form-control" type="number" id="price_sell" name="price_sell" min="0" step="0.01" value="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="discount">الخصم (%)</label>
                    <input class="form-control" type="number" id="discount" name="discount" min="0" max="100" step="0.01" value="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="main_image">الصورة الرئيسية <span class="text-danger">*</span></label>
                    <input class="form-control" type="file" id="main_image" name="main_image" accept="image/*" required>
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="sub_images">صور إضافية <small>(يمكن تحديد أكثر من صورة)</small></label>
                    <input class="form-control" type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                </div>

                <div class="col-md-2 mt-2">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="published" name="published" value="1">
                        <label class="form-check-label" for="published">نشر المنتج</label>
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

        if (form.published) {
            formData.set('published', form.published.checked ? 1 : 0);
        }

        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.products.store') }}', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(() => {
            Swal.fire({ icon: 'success', title: 'نجاح', text: 'تم إنشاء المنتج بنجاح.' });
            form.reset();
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            console.log(error.response);
            // Always handle backend 422/validation.required
            if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'حفظ المنتج';
        });
    });
});
</script>
@endsection
