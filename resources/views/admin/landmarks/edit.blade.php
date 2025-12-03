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
                <!-- Names Section -->
                <div class="col-12">
                    <h5 class="card-title text-primary"><i class="bi bi-pencil-square me-2"></i>الأسماء</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="name">اسم المعلم (عام) <span class="text-danger">*</span></label>
                    <input class="form-control" type="text" id="name" name="name" required value="{{ old('name', $landmark->name) }}">
                </div>
                
                <div class="col-md-4">
                    <label class="form-label" for="name_ar">الاسم بالعربية</label>
                    <input class="form-control" type="text" id="name_ar" name="name_ar" value="{{ old('name_ar', $landmark->name_ar) }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="name_en">الاسم بالإنجليزية</label>
                    <input class="form-control" type="text" id="name_en" name="name_en" value="{{ old('name_en', $landmark->name_en) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="slug">الاسم المختصر (Slug)</label>
                    <input class="form-control" type="text" id="slug" name="slug" value="{{ old('slug', $landmark->slug) }}">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="type">نوع المعلم</label>
                    <input class="form-control" type="text" id="type" name="type" value="{{ old('type', $landmark->type) }}" placeholder="مثال: مسجد، سوق، متحف">
                </div>

                <!-- Location Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-geo-alt-fill me-2"></i>الموقع</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-6">
                    <label class="form-label" for="city_id">المدينة <span class="text-danger">*</span></label>
                    <select class="form-select" id="city_id" name="city_id" required>
                        <option value="">-- اختر المدينة --</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}" {{ $landmark->city_id == $city->id ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Short Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-card-text me-2"></i>الوصف القصير</h5>
                    <hr class="border-primary">
                </div>

                <!-- Short Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-card-text me-2"></i>الوصف القصير</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description">وصف قصير (عام)</label>
                    <input class="form-control" type="text" id="short_description" name="short_description" maxlength="255" value="{{ old('short_description', $landmark->short_description) }}" placeholder="وصف مختصر للمعلم">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description_ar">وصف قصير (عربي)</label>
                    <input class="form-control" type="text" id="short_description_ar" name="short_description_ar" maxlength="255" value="{{ old('short_description_ar', $landmark->short_description_ar) }}" placeholder="وصف مختصر بالعربية">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="short_description_en">وصف قصير (إنجليزي)</label>
                    <input class="form-control" type="text" id="short_description_en" name="short_description_en" maxlength="255" value="{{ old('short_description_en', $landmark->short_description_en) }}" placeholder="Short description in English">
                </div>

                <!-- Full Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-file-text me-2"></i>الوصف التفصيلي</h5>
                    <hr class="border-primary">
                </div>

                <!-- Full Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-file-text me-2"></i>الوصف التفصيلي</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description">وصف تفصيلي (عام)</label>
                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="وصف تفصيلي للمعلم">{{ old('description', $landmark->description) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description_ar">وصف تفصيلي (عربي)</label>
                    <textarea class="form-control" id="description_ar" name="description_ar" rows="3" placeholder="وصف تفصيلي بالعربية">{{ old('description_ar', $landmark->description_ar) }}</textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label" for="description_en">وصف تفصيلي (إنجليزي)</label>
                    <textarea class="form-control" id="description_en" name="description_en" rows="3" placeholder="Detailed description in English">{{ old('description_en', $landmark->description_en) }}</textarea>
                </div>

                <!-- Ambient Description -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-stars me-2"></i>وصف التجربة الحسية (Ambient)</h5>
                    <hr class="border-primary">
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
                    <h5 class="card-title text-primary"><i class="bi bi-clock-history me-2"></i>خط الزمن (Timeline)</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-12">
                    <div class="card shadow-sm border-0 timeline-gradient-card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label mb-0 timeline-label fw-bold"><i class="bi bi-calendar-event me-2"></i>الأحداث الزمنية</label>
                                <button type="button" class="btn btn-light btn-sm shadow-sm" id="addTimelineBtn">
                                    <i class="bi bi-plus-circle me-1"></i>إضافة حدث
                                </button>
                            </div>
                            
                            <div id="timelineContainer">
                                <!-- Timeline items will be added here -->
                            </div>
                            
                            <div id="emptyTimelineMessage" class="text-center timeline-empty py-4" style="display: none;">
                                <i class="bi bi-hourglass-split fs-2"></i>
                                <p class="mb-0 mt-2">لا توجد أحداث زمنية. انقر على "إضافة حدث" لبدء الإضافة.</p>
                            </div>
                        </div>
                    </div>
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
    
    // Timeline management
    const timelineContainer = document.getElementById('timelineContainer');
    const emptyTimelineMessage = document.getElementById('emptyTimelineMessage');
    const addTimelineBtn = document.getElementById('addTimelineBtn');
    let timelineIndex = 0;

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Load existing timeline data
    const existingTimeline = @json($landmark->timeline ?? []);
    if (existingTimeline && existingTimeline.length > 0) {
        existingTimeline.forEach(item => {
            // Parse the timeline item (format: "period: description")
            const parts = item.split(':');
            const period = parts[0]?.trim() || '';
            const description = parts.slice(1).join(':').trim() || '';
            addTimelineItem(period, description);
        });
    } else {
        emptyTimelineMessage.style.display = 'block';
    }

    // Add timeline event function
    function addTimelineItem(period = '', description = '') {
        const timelineItem = document.createElement('div');
        timelineItem.className = 'timeline-item mb-3 p-3 rounded-3 shadow-sm';
        timelineItem.style.animation = 'slideIn 0.3s ease-out';
        timelineItem.innerHTML = `
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="mb-0 fw-bold timeline-event-title"><i class="bi bi-calendar-check me-2"></i>حدث زمني ${timelineIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger shadow-sm remove-timeline-btn" style="min-width: 32px; height: 32px;">
                    <i class="bi bi-trash text-white"></i>
                </button>
            </div>
            <div class="row g-2">
                <div class="col-md-3">
                    <label class="form-label small fw-semibold"><i class="bi bi-calendar3 me-1"></i>الفترة/التاريخ</label>
                    <input type="text" name="timeline[${timelineIndex}][period]" class="form-control form-control-sm shadow-sm" placeholder="مثال: 1920-1950" value="${period}">
                </div>
                <div class="col-md-9">
                    <label class="form-label small fw-semibold"><i class="bi bi-textarea-t me-1"></i>الوصف</label>
                    <textarea name="timeline[${timelineIndex}][description]" class="form-control form-control-sm shadow-sm" rows="2" placeholder="اكتب وصف الحدث...">${description}</textarea>
                </div>
            </div>
        `;
        
        timelineContainer.appendChild(timelineItem);
        emptyTimelineMessage.style.display = 'none';
        timelineIndex++;
        
        // Add remove functionality
        timelineItem.querySelector('.remove-timeline-btn').addEventListener('click', function() {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "سيتم حذف هذا الحدث الزمني",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'نعم، احذف',
                cancelButtonText: 'إلغاء'
            }).then((result) => {
                if (result.isConfirmed) {
                    timelineItem.style.animation = 'slideOut 0.3s ease-out';
                    setTimeout(() => {
                        timelineItem.remove();
                        updateTimelineNumbers();
                        if (timelineContainer.children.length === 0) {
                            emptyTimelineMessage.style.display = 'block';
                        }
                    }, 300);
                }
            });
        });
    }

    // Add timeline button click
    addTimelineBtn.addEventListener('click', () => {
        addTimelineItem();
    });

    // Update timeline item numbers
    function updateTimelineNumbers() {
        const items = timelineContainer.querySelectorAll('.timeline-item');
        items.forEach((item, index) => {
            item.querySelector('h6').innerHTML = `<i class="bi bi-calendar-check me-2"></i>حدث زمني ${index + 1}`;
        });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;
        submitBtn.innerText = 'يتم التحديث...';

        let formData = new FormData(form);
        if (form.published) formData.set('published', form.published.checked ? 1 : 0);

        // Convert timeline to JSON array
        const timelineItems = [];
        const timelineInputs = form.querySelectorAll('[name^="timeline["]');
        const timelineData = {};
        
        timelineInputs.forEach(input => {
            const match = input.name.match(/timeline\[(\d+)\]\[(\w+)\]/);
            if (match) {
                const index = match[1];
                const field = match[2];
                if (!timelineData[index]) {
                    timelineData[index] = {};
                }
                timelineData[index][field] = input.value;
            }
        });
        
        Object.values(timelineData).forEach(item => {
            if (item.period || item.description) {
                timelineItems.push(`${item.period || ''}: ${item.description || ''}`);
            }
        });
        
        if (timelineItems.length > 0) {
            formData.set('timeline', JSON.stringify(timelineItems));
        } else {
            formData.set('timeline', JSON.stringify([]));
        }

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

<style>
@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-10px);
    }
}

.card-title {
    font-weight: 600;
    letter-spacing: 0.3px;
}

/* Timeline gradient card - adapts to light/dark mode */
.timeline-gradient-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none !important;
}

[data-bs-theme="dark"] .timeline-gradient-card {
    background: linear-gradient(135deg, #4c63d2 0%, #5a3a7f 100%);
}

/* Timeline label color */
.timeline-label {
    color: #ffffff !important;
}

/* Timeline empty message */
.timeline-empty {
    color: rgba(255, 255, 255, 0.9);
}

/* Timeline items - light mode */
.timeline-item {
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    transition: all 0.3s ease;
}

/* Timeline items - dark mode */
[data-bs-theme="dark"] .timeline-item {
    background: #2b3544;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.timeline-item:hover {
    transform: translateX(-3px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

[data-bs-theme="dark"] .timeline-item:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.4) !important;
}

/* Timeline event title */
.timeline-event-title {
    color: #667eea;
}

[data-bs-theme="dark"] .timeline-event-title {
    color: #a0aaf7;
}

.btn {
    transition: all 0.2s ease;
}

.btn:hover {
    transform: translateY(-2px);
}

.remove-timeline-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.25rem 0.5rem;
}

.remove-timeline-btn i {
    font-size: 14px;
}

.form-control:focus,
.form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.25rem rgba(102, 126, 234, 0.25);
}

/* Dark mode form controls */
[data-bs-theme="dark"] .form-control,
[data-bs-theme="dark"] .form-select {
    background-color: #1e2530;
    border-color: rgba(255, 255, 255, 0.15);
    color: #e4e6eb;
}

[data-bs-theme="dark"] .form-control:focus,
[data-bs-theme="dark"] .form-select:focus {
    background-color: #1e2530;
    border-color: #a0aaf7;
    color: #e4e6eb;
}
</style>

@endsection
