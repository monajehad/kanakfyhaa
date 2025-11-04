@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'قائمة المدن')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
    <h4>المدن</h4>
    <a href="{{ route('admin.cities.create') }}" class="btn btn-primary">إضافة مدينة</a>
</div>

<form method="GET" name="csrf-token" content="{{ csrf_token() }}" action="{{ route('admin.cities.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
    <div class="col-md-4">
        <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
        <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="ابحث باسم المدينة">
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
    </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>المدينة</th>
                <th>الاسم المحلي</th>
                <th>الدولة</th>
                <th>المنطقة</th>
                <th>عدد السكان</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($cities as $city)
                <tr>
                    <td>{{ $city->id }}</td>
                    <td>{{ $city->name }}</td>
                    <td>{{ $city->native_name ?? '-' }}</td>
                    <td>{{ $city->country->name ?? '-' }}</td>
                    <td>{{ $city->region ?? '-' }}</td>
                    <td>{{ $city->population ? number_format($city->population) : '-' }}</td>
                    <td>
                        <div class="dropdown">
                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="{{ route('admin.cities.edit', $city->id) }}">
                                    <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                </a>
                                <a class="dropdown-item" href="javascript:void(0);" onclick="deleteCity({{ $city->id }})">
                                    <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>{{ __('Delete') }}
                                </a>
                            </div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">لا توجد نتائج</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $cities->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function deleteCity(id) {
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
            axios.delete(`/admin/cities/${id}`)
                .then(response => {
                    if (response.data.success) {
                        Swal.fire('تم الحذف!', response.data.message, 'success').then(() => {
                            location.reload();
                        });
                    } else {
                        Swal.fire('خطأ', response.data.message, 'error');
                    }
                })
                .catch(() => Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error'));
        }
    });
}
</script>
@endsection
