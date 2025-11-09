@extends('layouts/layoutMaster')

@section('title', 'إضافة مدينة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">إضافة مدينة جديدة</h4>
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="cityForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المدينة *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">اسم المدينة (عربي)</label>
                    <input type="text" name="name_ar" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">اسم المدينة (إنجليزي)</label>
                    <input type="text" name="name_en" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم المحلي</label>
                    <input type="text" name="native_name" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الدولة *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- اختر الدولة --</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}">{{ $country->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">المنطقة الفرعية</label>
                    <input type="text" name="subregion" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">عدد السكان</label>
                    <input type="number" name="population" class="form-control" min="0">
                </div>

                <div class="col-md-6">
                    <label class="form-label">خط العرض (Latitude)</label>
                    <input type="number" name="latitude" class="form-control" step="0.0000001">
                </div>

                <div class="col-md-6">
                    <label class="form-label">خط الطول (Longitude)</label>
                    <input type="number" name="longitude" class="form-control" step="0.0000001">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" id="submitBtn" class="btn btn-primary px-4">حفظ المدينة</button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert + Axios --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('cityForm');
    const submitBtn = document.getElementById('submitBtn');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    form.addEventListener('submit', e => {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.cities.store') }}', new FormData(form))
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
                submitBtn.innerText = 'حفظ المدينة';
            });
    });
});
</script>
@endsection
