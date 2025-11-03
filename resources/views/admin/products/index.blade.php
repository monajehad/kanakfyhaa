@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Products'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ __('Products') }}</h4>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="ri-add-line me-1"></i>{{ __('Add Product') }}
        </a>
    </div>

    <form method="GET" action="{{ route('admin.products.index') }}" class="row mb-3 gx-2 gy-1 align-items-end">
        <div class="col-md-3">
            <label for="search" class="form-label mb-0">{{ __('Search') }}</label>
            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="{{ __('Name or Description') }}">
        </div>
        <div class="col-md-3">
            <label for="category" class="form-label mb-0">{{ __('Category') }}</label>
            <select name="category" id="category" class="form-select">
                <option value="">{{ __('Categories') }}</option>
                @foreach($categories ?? [] as $cat)
                    <option value="{{ $cat->id }}" @if(request('category') == $cat->id) selected @endif>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label for="price_min" class="form-label mb-0">{{ __('Min Price') }}</label>
            <input type="number" name="price_min" value="{{ request('price_min') }}" class="form-control" placeholder="0">
        </div>
        <div class="col-md-2">
            <label for="price_max" class="form-label mb-0">{{ __('Max Price') }}</label>
            <input type="number" name="price_max" value="{{ request('price_max') }}" class="form-control" placeholder="99999">
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
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Discount') }}</th>
                    <th>{{ __('Categories') }}</th>
                    <th>{{ __('City') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th class="text-center">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr id="product-row-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td>
                            @php
                                $mainMedia = $product->media->where('role', 'main')->first();
                            @endphp
                            @if($mainMedia)
                                <img src="{{ asset($mainMedia->url) }}" alt="{{ $product->name }}" width="50" height="50" class="rounded" style="object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                    <i class="ri-image-line text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div>{{ $product->name }}</div>
                            @if($product->title)
                                <small class="text-muted">{{ strlen($product->title) > 50 ? substr($product->title, 0, 50) . '...' : $product->title }}</small>
                            @endif
                        </td>
                        <td>
                            <div>${{ number_format($product->price_sell, 2) }}</div>
                            @if($product->price_cost > 0)
                                <small class="text-muted">Cost: ${{ number_format($product->price_cost, 2) }}</small>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->discount > 0)
                                <span class="badge bg-label-success">{{ number_format($product->discount, 2) }}%</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">
                            @if($product->categories && $product->categories->count() > 0)
                                <span class="badge bg-label-primary">{{ $product->categories->count() }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($product->city)
                                {{ $product->city->name }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($product->published)
                                <span class="badge bg-label-success">Published</span>
                            @else
                                <span class="badge bg-label-warning">Draft</span>
                            @endif
                        </td>
                        <td>{{ $product->created_at->format('Y-m-d') }}</td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <i class="icon-base ri ri-more-2-fill icon-18px"></i>
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="{{ route('admin.products.show', $product->id) }}">
                                        <i class="icon-base ri ri-eye-line icon-18px me-1"></i>{{ __('Show') }}
                                    </a>
                                    <a class="dropdown-item" href="{{ route('admin.products.edit', $product->id) }}">
                                        <i class="icon-base ri ri-pencil-line icon-18px me-1"></i>{{ __('Edit') }}
                                    </a>
                                    <button 
                                        class="dropdown-item text-danger btn-delete-product" 
                                        type="button"
                                        data-id="{{ $product->id }}"
                                        data-name="{{ $product->name }}"
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
                    <td colspan="10">
                        {!! $products->appends(request()->query())->links() !!}
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

    console.log('Delete script initialized');

    function handleDeleteClick(e) {
        // Find the delete button
        let target = e.target;
        while (target && !target.classList.contains('btn-delete-product')) {
            target = target.parentElement;
        }

        if (!target || !target.classList.contains('btn-delete-product')) {
            return;
        }

        e.preventDefault();
        e.stopPropagation();

        const productId = target.getAttribute('data-id');
        const productName = target.getAttribute('data-name');

        console.log('Delete clicked:', productId, productName);

        if (!productId) {
            console.error('Product ID not found');
            return;
        }

        // Close the dropdown
        const dropdown = target.closest('.dropdown-menu');
        if (dropdown) {
            const bsDropdown = bootstrap.Dropdown.getInstance(target.closest('.dropdown').querySelector('[data-bs-toggle="dropdown"]'));
            if (bsDropdown) {
                bsDropdown.hide();
            }
        }

        // Show confirmation dialog
        Swal.fire({
            title: 'هل أنت متأكد؟',
            html: 'هل تريد حذف المنتج: <br><strong>' + productName + '</strong>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'نعم، احذفه!',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                // Show loading
                Swal.fire({
                    title: 'جاري الحذف...',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Delete request
                axios.delete('{{ url("admin/products") }}/' + productId, {
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(function(response) {
                    console.log('Delete success:', response);
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'تم الحذف!',
                        text: response.data.message || 'تم حذف المنتج بنجاح.',
                        timer: 2000,
                        showConfirmButton: false
                    });

                    // Remove row with animation
                    const row = document.getElementById('product-row-' + productId);
                    if (row) {
                        row.style.transition = 'opacity 0.3s ease';
                        row.style.opacity = '0';
                        setTimeout(() => {
                            row.remove();
                            
                            // Check if table is empty
                            const tbody = document.querySelector('tbody');
                            if (tbody && tbody.children.length === 0) {
                                location.reload();
                            }
                        }, 300);
                    }
                })
                .catch(function(error) {
                    console.error('Delete error:', error);
                    
                    let errorMessage = 'تعذر حذف المنتج.';
                    
                    if (error.response) {
                        if (error.response.data && error.response.data.message) {
                            errorMessage = error.response.data.message;
                        } else if (error.response.status === 404) {
                            errorMessage = 'المنتج غير موجود.';
                        } else if (error.response.status === 403) {
                            errorMessage = 'ليس لديك صلاحية لحذف هذا المنتج.';
                        }
                    }
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'خطأ!',
                        text: errorMessage
                    });
                });
            }
        });
    }

    // Add event listener
    document.addEventListener('click', handleDeleteClick);

})();
</script>
@endsection