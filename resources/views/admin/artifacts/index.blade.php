@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Landmark artifacts'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Landmark artifacts') }}</h4>
        <a href="{{ route('admin.artifacts.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>{{ __('Add Artifact') }}
        </a>
    </div>

    <form method="GET" action="{{ route('admin.artifacts.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Name or Description') }}">
        </div>
        <div class="col-md-3">
            <label for="landmark" class="form-label mb-0">{{ __('Landmark') }}</label>
            <select name="landmark" id="landmark" class="form-select">
                <option value="">{{ __('All Landmarks') }}</option>
                @foreach($landmarks ?? [] as $landmark)
                    <option value="{{ $landmark->id }}" @if(request('landmark') == $landmark->id) selected @endif>
                        {{ $landmark->name }}
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
                    <th>{{ __('short description') }}</th>
                    <th>{{ __('Landmark') }}</th>
                    <th>{{ __('City') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($artifacts as $artifact)
                    <tr id="artifact-row-{{ $artifact->id }}">
                        <td>{{ $artifact->id }}</td>
                        <td>
                            @php
                                $mainMedia = $artifact->media->where('role', 'main')->first();
                            @endphp
                            @if($mainMedia)
                                <img src="{{ asset($mainMedia->url) }}" alt="{{ $artifact->name }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px; height:50px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $artifact->title }}</td>
                        <td>{{ $artifact->short_description ?? '-' }}</td>
                        <td>{{ $artifact->landmark ? $artifact->landmark->name : '-' }}</td>
                        <td>{{ $artifact->landmark && $artifact->landmark->city ? $artifact->landmark->city->name : '-' }}</td>
                        <td>{{ $artifact->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.artifacts.show', $artifact->id) }}">
                                        <i class="icon-base ri ri-eye-line icon-18px me-1"></i>{{ __('Show') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.artifacts.edit', $artifact->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <button 
                                        class="dropdown-item text-danger btn-delete-artifact" 
                                        type="button"
                                        data-id="{{ $artifact->id }}"
                                        data-name="{{ $artifact->name }}"
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
                        {!! $artifacts->appends(request()->query())->links() !!}
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
        while (target && !target.classList.contains('btn-delete-artifact')) {
            target = target.parentElement;
        }
        if (!target || !target.classList.contains('btn-delete-artifact')) return;

        e.preventDefault();
        e.stopPropagation();

        const artifactId = target.getAttribute('data-id');
        const artifactName = target.getAttribute('data-name');

        if (!artifactId) return;

        const dropdown = target.closest('.dropdown-menu');
        if (dropdown) {
            const bsDropdown = bootstrap.Dropdown.getInstance(target.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]'));
            if (bsDropdown) bsDropdown.hide();
        }

        Swal.fire({
            title: 'هل أنت متأكد؟',
            html: 'هل تريد حذف الموقع الأثري: <br><strong>' + artifactName + '</strong>',
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

                axios.delete('{{ url("admin/artifacts") }}/' + artifactId, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => {
                    Swal.fire({ icon:'success', title:'تم الحذف!', text: response.data.message || 'تم حذف الموقع الأثري بنجاح.', timer:2000, showConfirmButton:false });
                    const row = document.getElementById('artifact-row-' + artifactId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s';
                        row.style.opacity = '0';
                        setTimeout(() => row.remove(), 300);
                    }
                })
                .catch(error => {
                    let msg = 'تعذر حذف الموقع الأثري.';
                    if (error.response) {
                        if (error.response.data?.message) msg = error.response.data.message;
                        else if (error.response.status === 404) msg = 'الموقع الأثري غير موجود.';
                        else if (error.response.status === 403) msg = 'ليس لديك صلاحية لحذف هذا الموقع.';
                    }
                    Swal.fire({ icon:'error', title:'خطأ!', text: msg });
                });
            }
        });
    });
})();
</script>
@endsection
