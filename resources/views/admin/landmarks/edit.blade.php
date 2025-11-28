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
                <!-- Names -->
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم المعلم <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $landmark->name) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label" for="slug">الاسم المختصر (Slug)</label>
                    <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $landmark->slug) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="name_ar">الاسم بالعربية</label>
                    <input class="form-control" type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $landmark->name_ar) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="name_en">الاسم بالإنجليزية</label>
                    <input class="form-control" type="text" id="name_en" name="name_en" value="{{ old('name_en', $landmark->name_en) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="type">نوع المعلم</label>
                    <input class="form-control" type="text" id="type" name="type" value="{{ old('type', $landmark->type) }}" placeholder="مثال: مسجد، سوق، متحف">
                </div>

                <!-- Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">الوصف</h5>
                    <hr>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $landmark->short_description) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description_ar">وصف قصير (عربي)</label>
                    <input class="form-control" type="text" id="short_description_ar" name="short_description_ar" maxlength="255" value="{{ old('short_description_ar', $landmark->short_description_ar) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description_en">وصف قصير (إنجليزي)</label>
                    <input class="form-control" type="text" id="short_description_en" name="short_description_en" maxlength="255" value="{{ old('short_description_en', $landmark->short_description_en) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $landmark->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description_ar">وصف تفصيلي (عربي)</label>
                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3">{{ old('description_ar', $landmark->description_ar) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description_en">وصف تفصيلي (إنجليزي)</label>
                    <textarea class="form-control" id="description_en" name="description_en" rows="3">{{ old('description_en', $landmark->description_en) }}</textarea>
                </div>

                <!-- Ambient Description -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">وصف التجربة الحسية (Ambient)</h5>
                    <hr>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ambient_description">وصف التجربة الحسية</label>
                    <textarea class="form-control" id="ambient_description" name="ambient_description" rows="3" placeholder="اكتب وصفاً حسياً للمكان...">{{ old('ambient_description', $landmark->ambient_description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ambient_description_ar">وصف التجربة الحسية (عربي)</label>
                    <textarea class="form-control" id="ambient_description_ar" name="ambient_description_ar" rows="3" placeholder="اكتب وصفاً حسياً بالعربية...">{{ old('ambient_description_ar', $landmark->ambient_description_ar) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="ambient_description_en">وصف التجربة الحسية (إنجليزي)</label>
                    <textarea class="form-control" id="ambient_description_en" name="ambient_description_en" rows="3" placeholder="Write sensory description in English...">{{ old('ambient_description_en', $landmark->ambient_description_en) }}</textarea>
                </div>

                <!-- Timeline -->
                <div class="col-12 mt-4">
                    <h5 class="card-title">خط الزمن (Timeline)</h5>
                    <hr>
                </div>

                <div class="col-12">
                    <label class="form-label" for="timeline">خط الزمن (JSON)</label>
                    <textarea class="form-control" id="timeline" name="timeline" rows="4" placeholder="مثال: [&#10;  &quot;الفترة الأولى: وصف المرحلة&quot;,&#10;  &quot;الفترة الثانية: وصف المرحلة&quot;,&#10;  &quot;الفترة الثالثة: وصف المرحلة&quot;&#10;]">{{ old('timeline', json_encode($landmark->timeline ?? [], JSON_UNESCAPED_UNICODE)) }}</textarea>
                    <small class="text-muted">أدخل JSON array</small>
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
