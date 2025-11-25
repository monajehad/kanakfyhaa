@extends('layouts/layoutMaster')

@section('title', 'إنشاء شريط عرض جديد')

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">إنشاء شريط عرض جديد</h4>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
   
    <div class="card-body">
        <form id="sliderForm" autocomplete="off" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان شريط العرض" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="order">الرتبة</label>
                    <input class="form-control" type="number" id="order" name="order" placeholder="ترتيب العرض" min="0" value="0">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="description">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="وصف شريط العرض"></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="link">الرابط</label>
                    <input class="form-control" type="url" id="link" name="link" placeholder="https://example.com">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="image">صورة شريط العرض</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    <small class="form-text text-muted">الحد الأقصى للحجم: 5 ميجابايت. صيغ مقبولة: JPG, PNG, GIF, WebP</small>
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" checked>
                        <label class="form-check-label" for="active">تفعيل</label>
                    </div>
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">حفظ الشريط</button>
        </form>
    </div>
</div>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('sliderForm');
    const submitBtn = document.getElementById('submitBtn');
    const imageInput = form.querySelector('input[name="image"]');

    // File size validation
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 5 * 1024 * 1024; // 5MB
                if (file.size > maxSize) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'حجم الملف كبير جداً', 
                        text: 'يجب ألا يتجاوز حجم الصورة 5 ميجابايت. الحجم الحالي: ' + (file.size / 1024 / 1024).toFixed(2) + ' ميجابايت'
                    });
                    this.value = '';
                    return;
                }
            }
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;

        const title = form.title.value.trim();
        const imageInput = form.querySelector('input[name="image"]');
        
        if (!title) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', text: 'العنوان مطلوب.' });
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم الحفظ...';

        let formData = new FormData();
        formData.append('title', title);
        formData.append('description', form.description.value);
        formData.append('link', form.link.value);
        formData.append('order', form.order.value || 0);
        formData.append('active', form.active.checked ? 1 : 0);
        
        if (imageInput && imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }

        axios.post('{{ route('admin.sliders.store') }}', formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => {
            if (response.data.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'نجاح', 
                    text: response.data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: response.data.message || 'حدث خطأ.' });
            }
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            if (error.response?.status === 422 && error.response?.data?.errors) {
                const errors = error.response.data.errors;
                msg = Object.keys(errors).map(field => {
                    const fieldErrors = errors[field];
                    return Array.isArray(fieldErrors) ? fieldErrors.join('<br>') : fieldErrors;
                }).join('<br>');
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'حفظ الشريط';
        });
    });
});
</script>

@endsection
