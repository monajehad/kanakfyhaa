@extends('layouts/layoutMaster')

@section('title', 'تعديل شريط العرض')

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">تعديل شريط العرض: {{ $slider->title }}</h4>
        <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
    <div class="card-body">
        <form id="sliderForm" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="title">العنوان <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="title" name="title" placeholder="عنوان شريط العرض"
                        required value="{{ old('title', $slider->title) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="order">الرتبة</label>
                    <input class="form-control" type="number" id="order" name="order" placeholder="ترتيب العرض" min="0" value="{{ old('order', $slider->order) }}">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="description">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="وصف شريط العرض">{{ old('description', $slider->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="link">الرابط (قديم)</label>
                    <input class="form-control" type="url" id="link" name="link" placeholder="https://example.com" value="{{ old('link', $slider->link) }}">
                    <small class="form-text text-muted">يُستخدم للتوافق مع الإصدارات القديمة</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_text">نص الزر</label>
                    <input class="form-control" type="text" id="button_text" name="button_text" placeholder="اكتشف المزيد" value="{{ old('button_text', $slider->button_text) }}">
                    <small class="form-text text-muted">النص الذي سيظهر على الزر</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_url">رابط الزر</label>
                    <input class="form-control" type="url" id="button_url" name="button_url" placeholder="https://example.com" value="{{ old('button_url', $slider->button_url) }}">
                    <small class="form-text text-muted">الرابط الذي سينتقل إليه المستخدم عند الضغط</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="media">صورة أو فيديو</label>
                    <input class="form-control" type="file" id="media" name="media" accept="image/*,video/*">
                    <small class="form-text text-muted">الحد الأقصى: 100 ميجابايت. اترك فارغاً إن لم ترغب بالتغيير</small>
                    @if($slider->mainImage && $slider->mainImage->url)
                        <div class="mt-2">
                            @if($slider->mainImage->type === 'video')
                                <video width="200" height="150" controls style="border-radius: 7px; border: 1px solid #eee;">
                                    <source src="{{ $slider->mainImage->url }}" type="video/mp4">
                                    المتصفح لا يدعم تشغيل الفيديو
                                </video>
                            @else
                                <img src="{{ $slider->mainImage->url }}" alt="صورة شريط العرض" style="width: 200px; height: 150px; object-fit: cover;" class="rounded border" />
                            @endif
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="active" name="active" {{ $slider->active ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">تفعيل</label>
                    </div>
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث الشريط</button>
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
    const imageInput = form.querySelector('input[name="media"]');

    // File size validation
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const maxSize = 100 * 1024 * 1024; // 100MB
                if (file.size > maxSize) {
                    Swal.fire({ 
                        icon: 'warning', 
                        title: 'حجم الملف كبير جداً', 
                        text: 'يجب ألا يتجاوز حجم الملف 100 ميجابايت. الحجم الحالي: ' + (file.size / 1024 / 1024).toFixed(2) + ' ميجابايت'
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
        const mediaInput = form.querySelector('input[name="media"]');

        if (!title) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', text: 'العنوان مطلوب.' });
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData();
        formData.append('title', title);
        formData.append('description', form.description.value || '');
        formData.append('link', form.link.value || '');
        formData.append('button_text', form.button_text.value || '');
        formData.append('button_url', form.button_url.value || '');
        formData.append('order', form.order.value || 0);
        formData.append('active', form.active.checked ? 1 : 0);

        if (mediaInput && mediaInput.files.length > 0) {
            formData.append('media', mediaInput.files[0]);
        }
        formData.append('_method', 'PUT');

        axios({
            method: 'post',
            url: '{{ route('admin.sliders.update', $slider->id) }}',
            data: formData,
            headers: {
                'Content-Type': 'multipart/form-data'
            }
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
                Swal.fire({ icon: 'error', title: 'فشل التحديث', html: response.data.message || 'حدث خطأ.' });
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
            Swal.fire({ icon: 'error', title: 'فشل التحديث', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'تحديث الشريط';
        });
    });
});
</script>

@endsection
