@extends('layouts/layoutMaster')

@section('title', 'إنشاء سلايدر جديد')

@section('content')
<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4 class="mb-0">إنشاء سلايدر جديد</h4>
        <a href="{{ route('admin.content.sliders.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
    <div class="card-body">
        <form id="sliderForm" autocomplete="off" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="title">عنوان السلايدر</label>
                    <input class="form-control" type="text" id="title" name="title" maxlength="255"
                           placeholder="عنوان السلايدر...">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="subtitle">العنوان الفرعي</label>
                    <input class="form-control" type="text" id="subtitle" name="subtitle" maxlength="255"
                           placeholder="العنوان الفرعي...">
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="description">الوصف</label>
                    <textarea class="form-control" id="description" name="description" rows="2" placeholder="وصف السلايدر..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="image">صورة السلايدر</label>
                    <input class="form-control" type="file" id="image" name="image" accept="image/*">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_text">نص الزر</label>
                    <input class="form-control" type="text" id="button_text" name="button_text" maxlength="255"
                           placeholder="مثلاً: تسوق الآن">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="button_url">رابط الزر</label>
                    <input class="form-control" type="text" id="button_url" name="button_url" maxlength="255"
                           placeholder="/products">
                </div>
                <div class="col-md-3">
                    <label class="form-label" for="order">الترتيب</label>
                    <input class="form-control" type="number" id="order" name="order" min="0" placeholder="0">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="active" name="active" checked>
                        <label class="form-check-label" for="active">نشط (مرئي)</label>
                    </div>
                </div>
            </div>
            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">حفظ السلايدر</button>
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

        // إعداد بيانات النموذج
        const formData = new FormData(form);

        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.content.sliders.store') }}', formData, {
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
                    window.location.href = "{{ route('admin.content.sliders.index') }}";
                });
            } else {
                Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: response.data.message || 'حدث خطأ.' });
            }
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'حفظ السلايدر';
        });
    });
});
</script>
@endsection
