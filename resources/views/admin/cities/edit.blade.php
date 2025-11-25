@extends('layouts/layoutMaster')

@section('title', 'تعديل مدينة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تعديل المدينة</h4>
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">الرجوع</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <form id="cityForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المدينة *</label>
                    <input type="text" name="name" class="form-control" value="{{ $city->name }}" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">اسم المدينة (عربي)</label>
                    <input type="text" name="name_ar" class="form-control" value="{{ $city->name_ar }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">اسم المدينة (إنجليزي)</label>
                    <input type="text" name="name_en" class="form-control" value="{{ $city->name_en }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الاسم المحلي</label>
                    <input type="text" name="native_name" class="form-control" value="{{ $city->native_name }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الدولة *</label>
                    <select name="country_id" class="form-select" required>
                        <option value="">-- اختر الدولة --</option>
                        @foreach ($countries as $country)
                            <option value="{{ $country->id }}" {{ $city->country_id == $country->id ? 'selected' : '' }}>
                                {{ $country->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-6">
                    <label class="form-label">المنطقة</label>
                    <input type="text" name="region" class="form-control" value="{{ $city->region }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">المنطقة الفرعية</label>
                    <input type="text" name="subregion" class="form-control" value="{{ $city->subregion }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">عدد السكان</label>
                    <input type="number" name="population" class="form-control" min="0" value="{{ $city->population }}">
                </div>

                <div class="col-12">
                    <label class="form-label">الوصف (عام)</label>
                    <textarea name="description" class="form-control" rows="4" placeholder="وصف المدينة بشكل عام">{{ $city->description }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الوصف (عربي)</label>
                    <textarea name="description_ar" class="form-control" rows="4" placeholder="وصف المدينة بالعربية">{{ $city->description_ar }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">الوصف (إنجليزي)</label>
                    <textarea name="description_en" class="form-control" rows="4" placeholder="وصف المدينة بالإنجليزية">{{ $city->description_en }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label">خط العرض (Latitude)</label>
                    <input type="number" name="latitude" class="form-control" step="0.0000001" value="{{ $city->latitude }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label">خط الطول (Longitude)</label>
                    <input type="number" name="longitude" class="form-control" step="0.0000001" value="{{ $city->longitude }}">
                </div>
            </div>

            {{-- Main Media Section (Image or Video) --}}
            <div class="card mt-4">
                <div class="card-header d-flex align-items-center">
                    <strong>الصورة/الفيديو الرئيسي</strong>
                </div>
                <div class="card-body">
                    @php
                        $main = $city->media->where('role', 'main')->first();
                    @endphp
                    <div class="row align-items-center">
                        <div class="col-md-3">
                            @if($main)
                                @if($main->type === 'video')
                                    <video width="150" height="150" controls style="border-radius: 7px; border: 1px solid #eee;">
                                        <source src="{{ $main->url }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <a href="{{ $main->url }}" target="_blank">
                                        <img src="{{ $main->thumbnail_url ?? $main->url }}" 
                                            alt="{{ $main->alt_text ?? 'Main Media' }}" 
                                            style="max-width: 150px; max-height: 150px; border-radius: 7px; border: 1px solid #eee;">
                                    </a>
                                @endif
                            @else
                                <span class="text-muted">لا يوجد صورة أو فيديو رئيسي حالياً.</span>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <input class="form-control mb-2" type="file" id="main_media" name="main_media" accept="image/*,video/*">
                            <small class="text-muted d-block">يمكنك رفع صورة أو فيديو. اترك الحقل فارغاً إن لم ترغب بتغيير الملف الرئيسي.</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sub Media Section (Images and Videos) --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>صور وفيديوهات إضافية</strong> <small class="text-muted">(يمكن تحديد أكثر من ملف)</small>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="file" id="sub_media" name="sub_media[]" accept="image/*,video/*" multiple>
                    <small class="text-muted d-block">رفع ملفات جديدة سيحل محل جميع الملفات الإضافية السابقة.</small>

                    @php
                        $subMedia = $city->media->where('role', 'sub');
                    @endphp

                    @if($subMedia->count())
                        <div class="mt-3">
                            <label class="form-label"><strong>الملفات الحالية:</strong></label>
                            <div class="d-flex flex-wrap align-items-center gap-2">
                                @foreach($subMedia as $media)
                                    <div class="position-relative" style="display:inline-block;">
                                        @if($media->type === 'video')
                                            <video width="80" height="80" controls style="border-radius: 4px; border: 1px solid #dfdfdf;">
                                                <source src="{{ $media->url }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <a href="{{ $media->url }}" target="_blank" class="d-inline-block">
                                                <img src="{{ $media->thumbnail_url ?? $media->url }}"
                                                    alt="{{ $media->alt_text ?? 'Sub Media' }}"
                                                    style="max-width:80px; max-height:80px; object-fit:cover; border-radius:4px; border:1px solid #dfdfdf;">
                                            </a>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-danger position-absolute" 
                                                style="top: -10px; right: -10px; padding: 2px 6px; font-size: 11px;" 
                                                onclick="deleteSubMedia({{ $media->id }})">
                                            ✕
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" id="submitBtn" class="btn btn-primary px-4">تحديث المدينة</button>
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
        submitBtn.innerText = 'يتم التحديث...';

        const formData = new FormData(form);

        axios.post('{{ route('admin.cities.update', $city->id) }}', formData)
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
                submitBtn.innerText = 'تحديث المدينة';
            });
    });
});

function deleteSubMedia(mediaId) {
    Swal.fire({
        title: 'هل أنت متأكد؟',
        text: 'لن تتمكن من استرجاع هذا الملف!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'حذف',
        cancelButtonText: 'إلغاء'
    }).then((result) => {
        if (result.isConfirmed) {
            axios.delete(`/admin/media/${mediaId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(res => {
                if (res.data.success) {
                    Swal.fire('تم الحذف', res.data.message, 'success').then(() => {
                        location.reload();
                    });
                }
            })
            .catch(err => {
                Swal.fire('خطأ', 'فشل حذف الملف', 'error');
            });
        }
    });
}
</script>
@endsection
