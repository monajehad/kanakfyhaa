@extends('layouts/layoutMaster')

@section('title', 'تعديل الأثر')

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
        <h4 class="mb-0">تعديل الأثر : {{ $artifact->name }}</h4>
        <a href="{{ route('admin.artifacts.index') }}" class="btn btn-secondary">الرجوع</a>
    </div>
    <div class="card-body">
        <form id="artifactForm" enctype="multipart/form-data" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label" for="name">اسم الأثر <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="title" name="title" required value="{{ old('title', $artifact->title) }}">
                </div>
             

                <div class="col-md-12">
                    <label class="form-label" for="short_description">وصف قصير</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $artifact->short_description) }}">
                </div>

                <div class="col-md-12">
                    <label class="form-label" for="description">وصف تفصيلي</label>
                    <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $artifact->description) }}</textarea>
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="landmark_id">المعلم <span class="text-danger">*</span></label>
                    <select class="form-select" id="landmark_id" name="landmark_id" required>
                        <option value="">-- اختر المعلم --</option>
                        @foreach($landmarks as $landmark)
                            <option value="{{ $landmark->id }}" {{ $artifact->landmark_id == $landmark->id ? 'selected' : '' }}>
                                {{ $landmark->name }} ({{ $landmark->city->name ?? '-' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Main Image Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>الصورة الرئيسية</strong>
                </div>
                <div class="card-body d-flex align-items-center">
                    @php
                        $main = $artifact->media->where('role', 'main')->first();
                    @endphp
                    <div class="me-3">
                        @if($main)
                            <a href="{{ $main->url ? (str_starts_with_any($main->url, ['http://','https://']) ? $main->url : asset($main->url)) : '#' }}" target="_blank">
                                <img src="{{ $main->thumbnail_url ?? asset($main->url) }}" alt="{{ $main->alt_text ?? 'Main Image' }}" style="max-width:85px; max-height:85px; border-radius:7px;border:1px solid #eee;">
                            </a>
                        @else
                            <span class="text-muted">لا توجد صورة رئيسية حالياً.</span>
                        @endif
                    </div>
                    <div class="flex-grow-1">
                        <input class="form-control" type="file" id="main_image" name="main_image" accept="image/*">
                        <small class="text-muted">اترك الحقل فارغاً إذا لم ترغب بتغيير الصورة الرئيسية.</small>
                    </div>
                </div>
            </div>

            {{-- Sub Images Section --}}
            <div class="card mt-4">
                <div class="card-header">
                    <strong>صور إضافية</strong> <small class="text-muted">(يمكن تحديد أكثر من صورة)</small>
                </div>
                <div class="card-body">
                    <input class="form-control mb-2" type="file" id="sub_images" name="sub_images[]" accept="image/*" multiple>
                    @php
                        $subImages = $artifact->media->where('role','sub');
                    @endphp
                    @if($subImages->count())
                        <div class="mt-2 d-flex flex-wrap">
                            @foreach($subImages as $image)
                                <div class="me-2 mb-2 position-relative" style="display:inline-block;">
                                    <a href="{{ $image->url ? (str_starts_with_any($image->url,['http://','https://']) ? $image->url : asset($image->url)) : '#' }}" target="_blank" title="{{ $image->alt_text }}">
                                        <img src="{{ $image->thumbnail_url ?? asset($image->url) }}" alt="{{ $image->alt_text ?? 'Sub Image' }}" style="max-width:60px; max-height:60px; object-fit:cover; border-radius:4px; border:1px solid #dfdfdf;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-2">رفع الصور الإضافية يحل محل جميع الصور الإضافية السابقة.</small>
                    @else
                        <span class="text-muted">لا توجد صور إضافية حالياً.</span>
                    @endif
                </div>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث الأثر</button>
        </form>
    </div>
</div>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('artifactForm');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData(form);

        axios.post('{{ route('admin.artifacts.update', $artifact->id) }}', formData, {
            headers: { 
                'Content-Type': 'multipart/form-data',
                'X-HTTP-Method-Override': 'PUT'
            }
        })
        .then(() => {
            Swal.fire({
                icon: 'success',
                title: 'نجاح',
                text: 'تم تحديث الأثر بنجاح.',
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                // إعادة التوجيه لصفحة قائمة الآثار
                window.location.href = '{{ route("admin.artifacts.index") }}';
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
            submitBtn.innerText = 'تحديث الأثر';
        });
    });
});
</script>


@endsection
