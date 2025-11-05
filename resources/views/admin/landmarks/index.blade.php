@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Landmarks'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Landmarks') }}</h4>
        <a href="{{ route('admin.landmarks.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>{{ __('Add Landmark') }}
        </a>
    </div>

    <form method="GET" action="{{ route('admin.landmarks.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Name or Description') }}">
        </div>
        <div class="col-md-3">
            <label for="city" class="form-label mb-0">{{ __('City') }}</label>
            <select name="city" id="city" class="form-select">
                <option value="">{{ __('All Cities') }}</option>
                @foreach($cities ?? [] as $city)
                    <option value="{{ $city->id }}" @if(request('city') == $city->id) selected @endif>
                        {{ $city->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">{{ __('Filter') }}</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>#</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Type') }}</th>
                    <th>{{ __('City') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($landmarks as $landmark)
                    <tr id="landmark-row-{{ $landmark->id }}">
                        <td>{{ $landmark->id }}</td>
                        <td>
                            @php
                                $mainMedia = $landmark->media->where('role', 'main')->first();
                            @endphp
                            @if($mainMedia)
                                <img src="{{ asset($mainMedia->url) }}" alt="{{ $landmark->name }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $landmark->name }}</td>
                        <td>{{ $landmark->type ?? '-' }}</td>
                        <td>{{ $landmark->city ? $landmark->city->name : '-' }}</td>
                      
                        <td>{{ $landmark->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.landmarks.show', $landmark->id) }}">
                                        <i class="icon-base ri ri-eye-line icon-18px me-1"></i>{{ __('Show') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.landmarks.edit', $landmark->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <button 
                                        class="dropdown-item text-danger btn-delete-landmark" 
                                        type="button"
                                        data-id="{{ $landmark->id }}"
                                        data-name="{{ $landmark->name }}"
                                    >
                                        <i class="icon-base ri ri-delete-bin-6-line icon-18px me-1"></i>{{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8">
                        {!! $landmarks->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection

@section('page-script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
(function() {
    'use strict';

    document.addEventListener('click', function(e) {
        let target = e.target;
        while (target && !target.classList.contains('btn-delete-landmark')) {
            target = target.parentElement;
        }
        if (!target || !target.classList.contains('btn-delete-landmark')) return;

        e.preventDefault();
        e.stopPropagation();

        const landmarkId = target.getAttribute('data-id');
        const landmarkName = target.getAttribute('data-name');

        if (!landmarkId) return;

        const dropdown = target.closest('.dropdown-menu');
        if (dropdown) {
            const bsDropdown = bootstrap.Dropdown.getInstance(target.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]'));
            if (bsDropdown) bsDropdown.hide();
        }

        Swal.fire({
            title: 'هل أنت متأكد؟',
            html: 'هل تريد حذف المعلم: <br><strong>' + landmarkName + '</strong>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه!',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({ title:'جاري الحذف...', allowOutsideClick:false, allowEscapeKey:false, showConfirmButton:false, willOpen:()=>Swal.showLoading() });

                axios.delete('{{ url("admin/landmarks") }}/' + landmarkId, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    Swal.fire({ icon:'success', title:'تم الحذف!', text: response.data.message || 'تم حذف المعلم بنجاح.', timer:2000, showConfirmButton:false });
                    const row = document.getElementById('landmark-row-' + landmarkId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                })
                .catch(error => {
                    let msg = 'تعذر حذف المعلم.';
                    if (error.response) {
                        if (error.response.data?.message) msg = error.response.data.message;
                        else if (error.response.status === 404) msg = 'المعلم غير موجود.';
                        else if (error.response.status === 403) msg = 'ليس لديك صلاحية لحذف هذا المعلم.';
                    }
                    Swal.fire({ icon:'error', title:'خطأ!', text: msg });
                });
            }
        });
    });
})();
</script>
@endsection
