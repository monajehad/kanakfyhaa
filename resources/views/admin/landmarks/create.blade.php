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

                <!-- Names Section -->
                <div class="col-12">
                    <h5 class="card-title text-primary"><i class="bi bi-pencil-square me-2"></i>الأسماء</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label">اسم المعلم (عام) *</label>
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

                <div class="col-md-6">
                    <label class="form-label">الاسم المختصر (slug)</label>
                    <input type="text" name="slug" class="form-control">
                </div>

                <div class="col-md-6">
                    <label class="form-label">النوع</label>
                    <input type="text" name="type" class="form-control" placeholder="مثال: مسجد، سوق، متحف">
                </div>

                <!-- Location -->

                <!-- Location -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-geo-alt-fill me-2"></i>الموقع</h5>
                    <hr class="border-primary">
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

                <!-- Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-card-text me-2"></i>الوصف القصير</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف المختصر (عام)</label>
                    <input type="text" name="short_description" class="form-control" placeholder="وصف مختصر للمعلم">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف المختصر (عربي)</label>
                    <input type="text" name="short_description_ar" class="form-control" placeholder="وصف مختصر بالعربية">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف المختصر (إنجليزي)</label>
                    <input type="text" name="short_description_en" class="form-control" placeholder="Short description in English">
                </div>

                <!-- Full Descriptions -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-file-text me-2"></i>الوصف التفصيلي</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف الكامل (عام)</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="وصف تفصيلي للمعلم"></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف الكامل (عربي)</label>
                    <textarea name="description_ar" class="form-control" rows="3" placeholder="وصف تفصيلي بالعربية"></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">الوصف الكامل (إنجليزي)</label>
                    <textarea name="description_en" class="form-control" rows="3" placeholder="Detailed description in English"></textarea>
                </div>

                <!-- Ambient Description -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-stars me-2"></i>وصف التجربة الحسية (Ambient)</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف التجربة الحسية</label>
                    <textarea name="ambient_description" class="form-control" rows="3" placeholder="اكتب وصفاً حسياً للمكان..."></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف التجربة الحسية (عربي)</label>
                    <textarea name="ambient_description_ar" class="form-control" rows="3" placeholder="اكتب وصفاً حسياً بالعربية..."></textarea>
                </div>

                <div class="col-md-4">
                    <label class="form-label">وصف التجربة الحسية (إنجليزي)</label>
                    <textarea name="ambient_description_en" class="form-control" rows="3" placeholder="Write sensory description in English..."></textarea>
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
                            
                            <div id="emptyTimelineMessage" class="text-center timeline-empty py-4">
                                <i class="bi bi-hourglass-split fs-2"></i>
                                <p class="mb-0 mt-2">لا توجد أحداث زمنية. انقر على "إضافة حدث" لبدء الإضافة.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-image me-2"></i>الملف الرئيسي (صورة أو فيديو)</h5>
                    <hr class="border-primary">
                </div>

                <div class="col-md-6">
                    <label class="form-label">الملف الرئيسي (صورة أو فيديو)</label>
                    <input type="file" name="main_image" class="form-control" accept="image/*,video/*" id="mainMediaInput">
                    <small class="text-muted">الصيغ المدعومة: JPEG, PNG, GIF, MP4, WebM, AVI (حد أقصى: 5MB)</small>
                </div>

                <div class="col-md-6" id="mainMediaPreview"></div>

                <!-- Sub Media Section -->
                <div class="col-12 mt-4">
                    <h5 class="card-title text-primary"><i class="bi bi-images me-2"></i>الملفات الإضافية (صور وفيديوهات)</h5>
                    <hr class="border-primary">
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
    
    // Timeline management
    const timelineContainer = document.getElementById('timelineContainer');
    const emptyTimelineMessage = document.getElementById('emptyTimelineMessage');
    const addTimelineBtn = document.getElementById('addTimelineBtn');
    let timelineIndex = 0;

    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    // Add timeline event
    addTimelineBtn.addEventListener('click', () => {
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
                    <input type="text" name="timeline[${timelineIndex}][period]" class="form-control form-control-sm shadow-sm" placeholder="مثال: 1920-1950">
                </div>
                <div class="col-md-9">
                    <label class="form-label small fw-semibold"><i class="bi bi-textarea-t me-1"></i>الوصف</label>
                    <textarea name="timeline[${timelineIndex}][description]" class="form-control form-control-sm shadow-sm" rows="2" placeholder="اكتب وصف الحدث..."></textarea>
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
    });

    // Update timeline item numbers
    function updateTimelineNumbers() {
        const items = timelineContainer.querySelectorAll('.timeline-item');
        items.forEach((item, index) => {
            item.querySelector('h6').innerHTML = `<i class="bi bi-calendar-check me-2"></i>حدث زمني ${index + 1}`;
        });
    }

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

        const formData = new FormData(form);
        
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
        }

        axios.post('{{ route('admin.landmarks.store') }}', formData)
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
