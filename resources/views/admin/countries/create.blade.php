@extends('layouts/layoutMaster')

@section('title', 'إضافة دولة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">إضافة دولة جديدة</h4>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="countryForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم الدولة *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم المحلي</label>
                    <input type="text" name="native_name" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">ISO2 *</label>
                    <input type="text" name="iso2" class="form-control" maxlength="2" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">ISO3 *</label>
                    <input type="text" name="iso3" class="form-control" maxlength="3" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">كود رقمي *</label>
                    <input type="number" name="numeric_code" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">كود الهاتف *</label>
                    <input type="text" name="phone_code" class="form-control" placeholder="+966" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">العاصمة</label>
                    <input type="text" name="capital" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">رمز العملة</label>
                    <input type="text" name="currency_symbol" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">اسم العملة</label>
                    <input type="text" name="currency_name" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">المنطقة الفرعية</label>
                    <input type="text" name="subregion" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">عدد المدن</label>
                    <input type="number" name="cities_count" class="form-control" min="0">
                </div>

                <div class="col-md-4">
                    <label class="form-label">رابط العلم (URL)</label>
                    <input type="url" name="flag_url" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">المنطقة الزمنية</label>
                    <input type="text" name="timezone" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">عدد السكان</label>
                    <input type="number" name="population" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">المساحة</label>
                    <input type="number" name="area" class="form-control">
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" id="submitBtn" class="btn btn-primary px-4">حفظ الدولة</button>
            </div>
        </form>
    </div>
</div>

{{-- SweetAlert + Axios --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById('countryForm');
    const submitBtn = document.getElementById('submitBtn');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    form.addEventListener('submit', e => {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم الحفظ...';

        axios.post('{{ route('admin.countries.store') }}', new FormData(form))
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
                submitBtn.innerText = 'حفظ الدولة';
            });
    });
});
</script>
@endsection
