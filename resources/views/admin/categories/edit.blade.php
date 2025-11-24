@extends('layouts/layoutMaster')

@section('title', 'تعديل التصنيف')

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">تعديل التصنيف: {{ $category->name }}</h4>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
    <div class="card-body">
        <form id="categoryForm" autocomplete="off" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم التصنيف <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قمصان رجالية"
                        required value="{{ old('name', $category->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="image">صورة التصنيف</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                    @if($category->mainImage && $category->mainImage->url)
                        <div class="mt-2">
                            <img src="{{ $category->mainImage->url }}" alt="صورة التصنيف" style="width: 100px; height: 100px; object-fit: cover;" class="rounded border" />
                        </div>
                    @endif
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث التصنيف</button>
        </form>
    </div>
</div>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('categoryForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;

        const name = form.name.value.trim();
        const imageInput = form.querySelector('input[name="image"]');

        if (!name) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', text: 'اسم التصنيف مطلوب.' });
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData();
        formData.append('name', name);

        // إرفاق الصورة إن وجدت
        if (imageInput && imageInput.files.length > 0) {
            formData.append('image', imageInput.files[0]);
        }
        formData.append('_method', 'PUT'); // Laravel requires this for PUT/PATCH

        axios({
            method: 'post',
            url: '{{ route('admin.categories.update', $category->id) }}',
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
                    // التوجيه تلقائيًا بعد التحديث
                    if (response.data.redirect) {
                        window.location.href = response.data.redirect;
                    }
                });
            } else {
                let message = 'حدث خطأ.';
                // معالجة رسالة الخطأ المتعلقة بالحقل url
                if (response.data.error && typeof response.data.error === 'string') {
                    if (
                        response.data.error.includes('Field \'url\' doesn\'t have a default value') ||
                        response.data.error.includes("Field 'url' doesn't have a default value")
                    ) {
                        message += '<br>حدث خطأ في الخادم: هناك مشكلة في رفع أو حفظ الصورة، تأكد من رفع صورة جديدة أو من إعدادات قاعدة البيانات الخاصة بجدول media.<br>' +
                            "<span style='direction:ltr;display:block;font-size:12px'>"+response.data.error+"</span>";
                    } else {
                        message += "<br>" + response.data.error;
                    }
                }
                Swal.fire({ icon: 'error', title: 'فشل التحديث', html: response.data.message || message });
            }
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            // إذا كان الخطأ متعلق بالحقل url في media
            if (error.response?.status === 500 && error.response?.data?.error) {
                if (
                    typeof error.response.data.error === 'string' &&
                    (error.response.data.error.includes('Field \'url\' doesn\'t have a default value') ||
                     error.response.data.error.includes("Field 'url' doesn't have a default value"))
                ) {
                    msg = "حدث خطأ في الخادم:<br>هناك مشكلة في رفع أو حفظ الصورة، تأكد من رفع صورة جديدة أو من إعدادات قاعدة البيانات الخاصة بجدول media.<br>" +
                        "<span style='direction:ltr;display:block;font-size:12px'>"+error.response.data.error+"</span>";
                } else {
                    msg = 'حدث خطأ في الخادم: <br>' + error.response.data.error;
                }
            } else if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            } else if (error.message) {
                msg = error.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل التحديث', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'تحديث التصنيف';
        });
    });
});
</script>

@endsection
