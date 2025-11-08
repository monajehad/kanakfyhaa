@extends('layouts/layoutMaster')

@section('title', __('Sliders'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
     <h4>{{ __('Sliders') }}</h4>
    <a href="{{ route('admin.content.sliders.create') }}" class="btn btn-primary">إضافة سلايدر</a>
</div>
   

    {{-- Search Form --}}
    <form method="GET" name="csrf-token" content="{{ csrf_token() }}" action="{{ route('admin.content.sliders.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
        <div class="col-md-4">
            <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Slider Name') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Slug') }}</th>
                    <th>{{ __('Number of Products') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th>{{ __('Updated At') }}</th>
                    <th class="rounded-end-bottom">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sliders as $slider)
                    <tr>
                        <td>{{ $slider->id }}</td>
                        <td>{{ $slider->name }}</td>
                        <td>{{ $slider->slug }}</td>
                        <td>{{ $slider->products_count ?? ($slider->products ? $slider->products->count() : 0) }}</td>
                        <td>{{ $slider->created_at }}</td>
                        <td>{{ $slider->updated_at }}</td>
                        <td>
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.content.sliders.edit', $slider->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <a class="dropdown-item" href="javascript:void(0);" onclick="deleteSlider({{ $slider->id }})">
                                        <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>{{ __('Delete') }}
                                    </a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
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
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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
            axios.delete(`/admin/sliders/${id}`)
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
        // function deleteCategory(id) {
        //     Swal.fire({
        //         title: 'هل أنت متأكد؟',
        //         text: "لن تتمكن من التراجع عن هذا!",
        //         icon: 'warning',
        //         showCancelButton: true,
        //         confirmButtonColor: '#3085d6',
        //         cancelButtonColor: '#d33',
        //         confirmButtonText: 'نعم، احذف!',
        //         cancelButtonText: 'إلغاء'
        //     }).then((result) => {
        //         if (result.isConfirmed) {
        //             axios.delete(`/Sliders/${id}`)
        //                 .then(() => {
        //                     Swal.fire('تم الحذف!', 'تم حذف التصنيف بنجاح.', 'success').then(() => {
        //                         location.reload();
        //                     });
        //                 })
        //                 .catch(error => {
        //                     Swal.fire('خطأ', 'حدث خطأ أثناء الحذف.', 'error');
        //                 });
        //         }
        //     });
        // }
    </script>
@endsection
