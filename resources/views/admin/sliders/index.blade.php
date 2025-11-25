@extends('layouts/layoutMaster')

@section('title', 'إدارة أشرطة العرض')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
     <h4>إدارة أشرطة العرض</h4>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">إضافة شريط عرض</a>
</div>

{{-- Search Form --}}
<form method="GET" action="{{ route('admin.sliders.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
    <div class="col-md-4">
        <label for="search" class="form-label mb-0">بحث</label>
        <input type="text" name="search" id="search" value="{{ request('search') }}" class="form-control" placeholder="العنوان أو الوصف">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">تصفية</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>#</th>
                <th>الصورة</th>
                <th>العنوان</th>
                <th>الرتبة</th>
                <th>الحالة</th>
                <th>تاريخ الإنشاء</th>
                <th class="rounded-end-bottom">الإجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sliders as $slider)
                <tr>
                    <td>{{ $slider->id }}</td>
                    <td>
                        @if($slider->mainImage)
                            <img src="{{ $slider->mainImage->url }}" 
                                 alt="{{ $slider->title }}" 
                                 style="width: 48px; height: 48px; object-fit: cover;" 
                                 class="rounded border" />
                        @else
                            <span class="text-muted small">—</span>
                        @endif
                    </td>
                    <td>{{ $slider->title }}</td>
                    <td>{{ $slider->order }}</td>
                    <td>
                        @if($slider->active)
                            <span class="badge bg-success">مفعّل</span>
                        @else
                            <span class="badge bg-danger">معطّل</span>
                        @endif
                    </td>
                    <td>{{ $slider->created_at ? $slider->created_at->format('Y-m-d H:i') : '' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.sliders.edit', $slider->id) }}">
                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>تعديل
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="deleteSlider({{ $slider->id }})">
                                    <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>حذف
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">لا توجد أشرطة عرض.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="7">
                    {!! $sliders->appends(request()->query())->links() !!}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
    
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    if (document.querySelector('meta[name="csrf-token"]')) {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    }

    function deleteSlider(id) {
        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: "لن تتمكن من التراجع عن هذا!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'نعم، احذف!',
            cancelButtonText: 'إلغاء'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete("{{ url('admin/sliders') }}" + '/' + id)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire('تم الحذف!', response.data.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('خطأ', response.data.message, 'error');
                        }
                    })
                    .catch(error => {
                        let msg = 'حدث خطأ أثناء الحذف.';
                        if (error.response && error.response.data && error.response.data.message)
                            msg = error.response.data.message;
                        Swal.fire('خطأ', msg, 'error');
                    });
            }
        });
    }
</script>
@endsection
