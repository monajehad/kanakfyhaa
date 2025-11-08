@extends('layouts/layoutMaster')

@section('title', 'تعديل السلايدر')

@section('content')
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">تعديل السلايدر: {{ $slider->title ?: 'بدون عنوان' }}</h4>
    </div>
    <div class="card-body">
        <form id="sliderForm" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="title">عنوان السلايدر</label>
                    <input class="form-control" type="text" id="title" name="title" maxlength="255"
                           value="{{ old('title', $slider->title) }}" placeholder="عنوان السلايدر...">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="subtitle">العنوان الفرعي</label>
                    <input class="form-control" type="text" id="subtitle" name="subtitle" maxlength="255"
                           value="{{ old('subtitle', $slider->subtitle) }}" placeholder="العنوان الفرعي...">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="description">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="وصف السلايدر...">{{ old('description', $slider->description) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="image">صورة السلايدر</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    @if($slider->image)
                        <div class="mt-2">
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="صورة السلايدر" style="max-width: 150px;"/>
                        </div>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_text">نص الزر</label>
                    <input class="form-control" type="text" id="button_text" name="button_text" maxlength="255"
                           value="{{ old('button_text', $slider->button_text) }}" placeholder="مثلاً: تسوق الآن">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_url">رابط الزر</label>
                    <input class="form-control" type="text" id="button_url" name="button_url" maxlength="255"
                           value="{{ old('button_url', $slider->button_url) }}" placeholder="/products">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="order">الترتيب</label>
                    <input class="form-control" type="number" id="order" name="order" min="0"
                           value="{{ old('order', $slider->order) }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active"
                                {{ old('active', $slider->active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="active">نشط (مرئي)</label>
                    </div>
                </div>
            </div>
            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث السلايدر</button>
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

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;

        let formData = new FormData();
        formData.append('title', form.title.value.trim());
        formData.append('subtitle', form.subtitle.value.trim());
        formData.append('description', form.description.value.trim());
        formData.append('button_text', form.button_text.value.trim());
        formData.append('button_url', form.button_url.value.trim());
        formData.append('order', form.order.value);
        formData.append('active', form.active.checked ? 1 : 0);
        formData.append('_method', 'PUT'); // Laravel requires this for PUT/PATCH

        if(form.image.files[0]) {
            formData.append('image', form.image.files[0]);
        }

        submitBtn.innerText = 'يتم التحديث...';

        axios.post('{{ route('admin.content.sliders.update', $slider->id) }}', formData, {
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
                    // تحديث الصفحة أو إعادة التوجيه إذا لزم الأمر
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    } else {
                        window.location.reload();
                    }
                });
            } else {
                Swal.fire({ icon: 'error', title: 'فشل التحديث', html: response.data.message || 'حدث خطأ.' });
            }
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
            submitBtn.innerText = 'تحديث السلايدر';
        });
    });
});
</script>

@endsection
