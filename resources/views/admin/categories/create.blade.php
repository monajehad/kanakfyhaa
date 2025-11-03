@extends('layouts/layoutMaster')

@section('title', 'إنشاء تصنيف جديد')

@section('content')
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">إنشاء تصنيف جديد</h4>
    </div>
    <div class="card-body">
        <form id="categoryForm" autocomplete="off">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم التصنيف <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" placeholder="مثل: قمصان " required>
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">حفظ التصنيف</button>
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

        // Validation بسيطة
        const name = form.name.value.trim();
        if (!name) {
            Swal.fire({ icon: 'error', title: 'تحقق من البيانات', text: 'اسم التصنيف مطلوب.' });
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.categories.store') }}', { name: name })
        .then(response => {
            if (response.data.success) {
                Swal.fire({ 
                    icon: 'success', 
                    title: 'نجاح', 
                    text: response.data.message,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    // التوجيه تلقائيًا بعد الحفظ
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
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            Swal.fire({ icon: 'error', title: 'فشل الحفظ', html: msg });
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'حفظ التصنيف';
        });
    });
});
</script>

@endsection
