@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'قائمة الدول')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
    <h4>الدول</h4>
    <a href="{{ route('admin.countries.create') }}" class="btn btn-primary">إضافة دولة</a>
</div>


<form method="GET"  name="csrf-token" content="{{ csrf_token() }}" action="{{ route('admin.countries.index') }}"  class="row mb-3 gx-2 gy-1 align-items-end">
    <div class="col-md-4">
            <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder=" باسم ابحث الدولة">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
        </div>
</form>

<div class="table-responsive">
    <table class="table table-bordered align-middle">
        <thead class="table-light text-center">
            <tr>
                <th>#</th>
                <th>الاسم</th>
                <th>الرمز ISO2</th>
                <th>العاصمة</th>
                <th>المنطقة</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($countries as $country)
                <tr>
                    <td>{{ $country->id }}</td>
                    <td>{{ $country->name }}</td>
                    <td>{{ $country->iso2 }}</td>
                    <td>{{ $country->capital ?? '-' }}</td>
                    <td>{{ $country->region ?? '-' }}</td>
                    <td class="text-center">
                        <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.countries.edit', $country->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="deleteCountry({{ $country->id }})">
                                        <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>{{ __('Delete') }}
                                    </a>
                                </div>
                            </div>
                    
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">لا توجد نتائج</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $countries->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

function deleteCountry(id) {
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
            axios.delete(`/admin/countries/${id}`)
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
