@extends('layouts/layoutMaster')

@section('title', 'إضافة معلم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">إضافة معلم جديد</h4>
    <a href="{{ route('admin.landmarks.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="landmarkForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">اسم المعلم *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم المختصر (slug)</label>
                    <input type="text" name="slug" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">النوع</label>
                    <input type="text" name="type" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">المدينة *</label>
                    <select name="city_id" class="form-select" required>
                        <option value="">-- اختر المدينة --</option>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الوصف المختصر</label>
                    <input type="text" name="short_description" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الوصف الكامل</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>

                

                <div class="col-md-6">
                    <label class="form-label">الصورة الرئيسية *</label>
                    <input type="file" name="main_image" class="form-control" >
                </div>

                <div class="col-md-12">
                    <label class="form-label">الصور الفرعية</label>
                    <input type="file" name="sub_images[]" class="form-control" multiple>
                </div>

            </div>

            <div class="mt-4">
                <button type="submit" id="submitBtn" class="btn btn-primary px-4">حفظ المعلم</button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert + Axios --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('landmarkForm');
    const submitBtn = document.getElementById('submitBtn');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    form.addEventListener('submit', e => {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.landmarks.store') }}', new FormData(form))
            .then(res => {
                if (res.data.success) {
                    Swal.fire('نجاح', res.data.message, 'success').then(() => {
                        window.location.href = res.data.redirect;
                    });
                } else {
                    Swal.fire('خطأ', res.data.message, 'error');
                }
            })
            .catch(err => {
                let msg = 'حدث خطأ غير متوقع.';
                if (err.response?.status === 422) {
                    msg = Object.values(err.response.data.errors).flat().join('<br>');
                }
                Swal.fire('فشل', msg, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'حفظ المعلم';
            });
    });
});
</script>
@endsection
