@extends('layouts/layoutMaster')

@section('title', 'تعديل دولة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تعديل الدولة</h4>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="countryForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم الدولة *</label>
                    <input type="text" name="name" class="form-control" value="{{ $country->name }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">الاسم المحلي</label>
                    <input type="text" name="native_name" class="form-control" value="{{ $country->native_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">ISO2 *</label>
                    <input type="text" name="iso2" class="form-control" maxlength="2" value="{{ $country->iso2 }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">ISO3 *</label>
                    <input type="text" name="iso3" class="form-control" maxlength="3" value="{{ $country->iso3 }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">كود رقمي *</label>
                    <input type="number" name="numeric_code" class="form-control" value="{{ $country->numeric_code }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">كود الهاتف *</label>
                    <input type="text" name="phone_code" class="form-control" value="{{ $country->phone_code }}" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label">العاصمة</label>
                    <input type="text" name="capital" class="form-control" value="{{ $country->capital }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">رمز العملة</label>
                    <input type="text" name="currency_symbol" class="form-control" value="{{ $country->currency_symbol }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">اسم العملة</label>
                    <input type="text" name="currency_name" class="form-control" value="{{ $country->currency_name }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control" value="{{ $country->region }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">المنطقة الفرعية</label>
                    <input type="text" name="subregion" class="form-control" value="{{ $country->subregion }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">عدد المدن</label>
                    <input type="number" name="cities_count" class="form-control" value="{{ $country->cities_count }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">رابط العلم (URL)</label>
                    <input type="url" name="flag_url" class="form-control" value="{{ $country->flag_url }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">المنطقة الزمنية</label>
                    <input type="text" name="timezone" class="form-control" value="{{ $country->timezone }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">عدد السكان</label>
                    <input type="number" name="population" class="form-control" value="{{ $country->population }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label">المساحة</label>
                    <input type="number" name="area" class="form-control" value="{{ $country->area }}">
                </div>

                <!-- Main Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الصورة الرئيسية (أو الفيديو)</h5>
                    <hr>
                </div>

                @php
                    $mainMedia = $country->media()->where('role', 'main')->first();
                @endphp

                @if($mainMedia)
                    <div class="col-md-6">
                        <label class="form-label">الملف الرئيسي الحالي</label>
                        <div class="mt-2 mb-3">
                            @if($mainMedia->type === 'video')
                                <video width="150" height="150" style="object-fit: cover; border-radius: 5px;" controls>
                                    <source src="{{ $mainMedia->url }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ $mainMedia->url }}" width="150" height="150" style="object-fit: cover; border-radius: 5px;">
                            @endif
                        </div>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteMedia({{ $mainMedia->id }})">حذف</button>
                    </div>
                @endif

                <div class="col-md-6">
                    <label class="form-label">تحديث الملف الرئيسي (اختياري)</label>
                    <input type="file" name="main_media" class="form-control" accept="image/*,video/*" id="mainMediaInput">
                    <small class="text-muted">الصيغ المدعومة: JPEG, PNG, GIF, MP4, WebM, AVI (حد أقصى: 100MB)</small>
                </div>

                <div class="col-md-12" id="mainMediaPreview"></div>

                <!-- Sub Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الملفات الإضافية (صور وفيديوهات)</h5>
                    <hr>
                </div>

                @php
                    $subMedias = $country->media()->where('role', 'sub')->get();
                @endphp

                @if($subMedias->count() > 0)
                    <div class="col-12">
                        <label class="form-label">الملفات الحالية</label>
                        <div class="row" id="existingSubMediaContainer">
                            @foreach($subMedias as $media)
                                <div class="col-md-2 mb-3" id="media-{{ $media->id }}">
                                    @if($media->type === 'video')
                                        <video width="100" height="100" style="object-fit: cover; border-radius: 5px;" controls>
                                            <source src="{{ $media->url }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ $media->url }}" width="100" height="100" style="object-fit: cover; border-radius: 5px;">
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger mt-1 w-100" onclick="deleteMedia({{ $media->id }})">حذف</button>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="col-md-12">
                    <label class="form-label">إضافة ملفات جديدة</label>
                    <input type="file" name="sub_media[]" class="form-control" multiple accept="image/*,video/*" id="subMediaInput">
                    <small class="text-muted">يمكنك تحديد عدة ملفات: صور أو فيديوهات</small>
                </div>

                <div class="col-12" id="subMediaPreview"></div>
            </div>

            <div class="mt-4">
                <button type="submit" id="submitBtn" class="btn btn-primary px-4">تحديث الدولة</button>
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
        submitBtn.innerText = 'يتم التحديث...';

        axios.post('{{ route('admin.countries.update', $country->id) }}', new FormData(form))
            .then(res => {
                if (res.data.success) {
                    Swal.fire('تم التحديث', res.data.message, 'success').then(() => {
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
                Swal.fire('فشل التحديث', msg, 'error');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'تحديث الدولة';
            });
    });

    // Delete media function
    window.deleteMedia = function(mediaId) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'سيتم حذف هذا الملف بشكل نهائي.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء'
        }).then(result => {
            if (result.isConfirmed) {
                axios.delete(`/admin/media/${mediaId}`)
                    .then(res => {
                        if (res.data.success) {
                            Swal.fire('تم الحذف', res.data.message, 'success').then(() => {
                                const element = document.getElementById(`media-${mediaId}`);
                                if (element) {
                                    element.remove();
                                }
                            });
                        }
                    })
                    .catch(err => {
                        Swal.fire('خطأ', 'فشل حذف الملف', 'error');
                    });
            }
        });
    };
});
</script>
@endsection
