@extends('layouts/layoutMaster')

@section('title', __('Sliders'))

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-row-reverse">
    <h4>{{ __('Sliders') }}</h4>
    <a href="{{ route('admin.content.sliders.create') }}" class="btn btn-primary">إضافة سلايدر</a>
</div>

@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

<div class="table-responsive">
    <table class="table mb-0">
        <thead>
            <tr>
                <th>ID</th>
                <th>{{ __('Title') }}</th>
                <th>{{ __('Subtitle') }}</th>
                <th>{{ __('Image') }}</th>
                <th>{{ __('Button Text') }}</th>
                <th>{{ __('Order') }}</th>
                <th>{{ __('Active') }}</th>
                <th>{{ __('Created At') }}</th>
                <th>{{ __('Updated At') }}</th>
                <th class="rounded-end-bottom">{{ __('Actions') }}</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($sliders as $slider)
            <tr>
                <td>{{ $slider->id }}</td>
                <td>{{ $slider->title }}</td>
                <td>{{ $slider->subtitle }}</td>
                <td>
                    @if($slider->image)
                        <img src="{{ $slider->image_url ?? asset('storage/' . $slider->image) }}" alt="slider" style="width:70px;height:40px;object-fit:cover;" />
                    @else
                        <span class="text-muted">{{ __('No image') }}</span>
                    @endif
                </td>
                <td>{{ $slider->button_text }}</td>
                <td>{{ $slider->order }}</td>
                <td>
                    @if($slider->active)
                        <span class="badge bg-success">{{ __('Yes') }}</span>
                    @else
                        <span class="badge bg-danger">{{ __('No') }}</span>
                    @endif
                </td>
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
        @empty
            <tr>
                <td colspan="10" class="text-center">{{ __('No sliders found.') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <div class="mt-2">
        {{ $sliders->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // You should have a meta[name="csrf-token"] in your layout file.
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
</script>
@endsection
