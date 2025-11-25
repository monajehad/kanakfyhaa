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

                <!-- Main Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الملف الرئيسي (صورة أو فيديو)</h5>
                    <hr>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الملف الرئيسي (صورة أو فيديو)</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*,video/*" id="mainMediaInput">
                    <small class="text-muted">الصيغ المدعومة: JPEG, PNG, GIF, MP4, WebM, AVI (حد أقصى: 5MB)</small>
                </div>

                <div class="col-md-6" id="mainMediaPreview"></div>

                <!-- Sub Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الملفات الإضافية (صور وفيديوهات)</h5>
                    <hr>
                </div>

                <div class="col-md-12">
                    <label class="form-label">ملفات إضافية</label>
                    <input type="file" name="sub_images[]" class="form-control" multiple accept="image/*,video/*" id="subMediaInput">
                    <small class="text-muted">يمكنك تحديد عدة ملفات: صور أو فيديوهات</small>
                </div>

                <div class="col-12" id="subMediaPreview"></div>

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
