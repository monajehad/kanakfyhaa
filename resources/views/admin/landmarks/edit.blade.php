@extends('layouts/layoutMaster')

@section('title', 'تعديل المعلم')

@section('content')
@php
    // Helper functions to replace Str::startsWith
    function str_starts_with_any($haystack, $needles) {
        foreach ((array)$needles as $needle) {
            if (strpos($haystack, $needle) === 0) return true;
        }
        return false;
    }
@endphp

<div class="card mt-3">
    <div class="card-header d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تعديل المعلم : {{ $landmark->name }}</h4>
    <a href="{{ route('admin.landmarks.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
    <div class="card-body">
        <form id="landmarkForm" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم المعلم <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $landmark->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">الاسم المختصر (Slug)</label>
                    <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $landmark->slug) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="type">نوع المعلم</label>
                    <input class="form-control" type="text" id="type" name="type" value="{{ old('type', $landmark->type) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $landmark->short_description) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $landmark->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="city_id">المدينة</label>
                    <select class="form-select" id="city_id" name="city_id" required>
                        <option value="">-- اختر المدينة --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $landmark->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Main Image Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>الملف الرئيسي (صورة أو فيديو)</strong>
                </div>
                <div class="card-body d-flex align-items-center">
                    @php
                        $main = $landmark->media->where('role', 'main')->first();
                    @endphp
                    <div class="me-3">
                        @if($main)
                            @if($main->type === 'video')
                                <video width="85" height="85" style="object-fit: cover; border-radius: 7px; border: 1px solid #eee;" controls>
                                    <source src="{{ $main->url }}" type="video/mp4">
                                </video>
                            @else
                                <img src="{{ $main->url }}" alt="{{ $main->alt_text ?? 'Main Media' }}" style="max-width:85px; max-height:85px; border-radius:7px;border:1px solid #eee;">
                            @endif
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="deleteMedia({{ $main->id }})">حذف</button>
                        @else
                            <span class="text-muted">لا يوجد ملف رئيسي حالياً.</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <input class="form-control" type="file" id="main_image" name="main_image" accept="image/*,video/*">
                        <small class="text-muted">الملفات المدعومة: صور أو فيديوهات (JPEG, PNG, GIF, MP4, WebM, AVI)</small>
                    </div>
                </div>
            </div>

            {{-- Sub Images Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>ملفات إضافية</strong> <small class="text-muted">(صور وفيديوهات)</small>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="file" id="sub_images" name="sub_images[]" accept="image/*,video/*" multiple>
                    @php
                        $subMedias = $landmark->media->where('role','sub');
                    @endphp
                    @if($subMedias->count())
                        <div class="mt-2 d-flex flex-wrap" id="existingSubMediaContainer">
                            @foreach($subMedias as $media)
                                <div class="me-2 mb-2 position-relative" style="display:inline-block;" id="media-{{ $media->id }}">
                                    @if($media->type === 'video')
                                        <video width="60" height="60" style="object-fit: cover; border-radius: 4px; border: 1px solid #dfdfdf;" controls>
                                            <source src="{{ $media->url }}" type="video/mp4">
                                        </video>
                                    @else
                                        <img src="{{ $media->url }}" alt="{{ $media->alt_text ?? 'Sub Media' }}" style="max-width:60px; max-height:60px; object-fit:cover; border-radius:4px; border:1px solid #dfdfdf;">
                                    @endif
                                    <button type="button" class="btn btn-sm btn-danger position-absolute" style="top: 0; right: 0; padding: 2px 6px; font-size: 10px;" onclick="deleteMedia({{ $media->id }})">X</button>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">رفع ملفات جديدة يحل محل جميع الملفات الإضافية السابقة.</small>
                    @else
                        <span class="text-muted">لا توجد ملفات إضافية حالياً.</span>
                    @endif
                </div>
            </div>

           

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث المعلم</button>
        </form>
    </div>
</div>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('landmarkForm');
    const submitBtn = document.getElementById('submitBtn');

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData(form);
        if (form.published) formData.set('published', form.published.checked ? 1 : 0);

        axios.post('{{ route('admin.landmarks.update', $landmark->id) }}', formData, {
            headers: { 
                'Content-Type': 'multipart/form-data',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: 'تم تحديث المعلم بنجاح.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = '{{ route('admin.landmarks.index') }}';
            });
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
            submitBtn.innerText = 'تحديث المعلم';
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
