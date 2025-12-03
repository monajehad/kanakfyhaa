@extends('layouts/layoutMaster')

@section('title', 'إضافة دولة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">إضافة دولة جديدة</h4>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="countryForm" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <!-- Names Section -->
                <div class="col-12">
                    <h5 class="card-title">الأسماء</h5>
                    <hr>
                </div>

                <div class="col-md-4">
                    <label class="form-label">اسم الدولة (عام) *</label>
                    <input type="text" name="name" class="form-control" required>
                </div>

                <div class="col-md-4">
                    <label class="form-label">الاسم بالعربية</label>
                    <input type="text" name="name_ar" class="form-control">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الاسم بالإنجليزية</label>
                    <input type="text" name="name_en" class="form-control">
                </div>

                <div class="col-md-4">
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

                <!-- Descriptions Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الوصف</h5>
                    <hr>
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف قصير (عام)</label>
                    <input type="text" name="short_description" class="form-control" placeholder="وصف مختصر للدولة">
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف قصير (عربي)</label>
                    <input type="text" name="short_description_ar" class="form-control" placeholder="وصف مختصر بالعربية">
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف قصير (إنجليزي)</label>
                    <input type="text" name="short_description_en" class="form-control" placeholder="Short description in English">
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف تفصيلي (عام)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="وصف تفصيلي للدولة"></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف تفصيلي (عربي)</label>
                    <textarea name="description_ar" class="form-control" rows="3" placeholder="وصف تفصيلي بالعربية"></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف تفصيلي (إنجليزي)</label>
                    <textarea name="description_en" class="form-control" rows="3" placeholder="Detailed description in English"></textarea>
                </div>

                <!-- Main Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الصورة الرئيسية (أو الفيديو)</h5>
                    <hr>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الملف الرئيسي (صورة أو فيديو)</label>
                    <input type="file" name="main_media" class="form-control" accept="image/*,video/*" id="mainMediaInput">
                    <small class="text-muted">الصيغ المدعومة: JPEG, PNG, GIF, MP4, WebM, AVI (حد أقصى: 100MB)</small>
                </div>

                <div class="col-md-6" id="mainMediaPreview"></div>

                <!-- Sub Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الملفات الإضافية (صور وفيديوهات)</h5>
                    <hr>
                </div>

                <div class="col-md-12">
                    <label class="form-label">ملفات إضافية</label>
                    <input type="file" name="sub_media[]" class="form-control" multiple accept="image/*,video/*" id="subMediaInput">
                    <small class="text-muted">يمكنك تحديد عدة ملفات: صور أو فيديوهات</small>
                </div>

                <div class="col-12" id="subMediaPreview"></div>
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
    const mainMediaInput = document.getElementById('mainMediaInput');
    const subMediaInput = document.getElementById('subMediaInput');
    const mainMediaPreview = document.getElementById('mainMediaPreview');
    const subMediaPreview = document.getElementById('subMediaPreview');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Main media preview
    mainMediaInput?.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const isVideo = file.type.startsWith('video');
            const preview = isVideo 
                ? `<video width="150" height="150" style="object-fit: cover; border-radius: 5px;" controls><source src="${URL.createObjectURL(file)}" type="${file.type}"></video>`
                : `<img src="${URL.createObjectURL(file)}" width="150" height="150" style="object-fit: cover; border-radius: 5px;">`;
            mainMediaPreview.innerHTML = preview;
        }
    });

    // Sub media preview
    subMediaInput?.addEventListener('change', (e) => {
        const files = e.target.files;
        subMediaPreview.innerHTML = '';
        
        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const isVideo = file.type.startsWith('video');
            const preview = isVideo 
                ? `<video width="100" height="100" style="object-fit: cover; border-radius: 5px; margin: 5px;" controls><source src="${URL.createObjectURL(file)}" type="${file.type}"></video>`
                : `<img src="${URL.createObjectURL(file)}" width="100" height="100" style="object-fit: cover; border-radius: 5px; margin: 5px;">`;
            
            subMediaPreview.innerHTML += preview;
        }
    });

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
