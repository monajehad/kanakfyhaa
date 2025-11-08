@extends('layouts/layoutMaster')

@section('title', 'تعديل التصنيف')

@section('content')
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">تعديل التصنيف: {{ $category->name }}</h4>
    </div>
    <div class="card-body">
        <form id="categoryForm" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم التصنيف <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قمصان رجالية"
                        required value="{{ old('name', $category->name) }}">
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
        if (!name) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', text: 'اسم التصنيف مطلوب.' });
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData();
        formData.append('name', name);
        formData.append('_method', 'PUT'); // Laravel requires this for PUT/PATCH

        axios.post('{{ route('admin.categories.update', $category->id) }}', formData, {
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
                    // التوجيه تلقائيًا بعد التحديث
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
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
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
