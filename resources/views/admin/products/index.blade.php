@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Products'))

@section('content')
    <h4>{{ __('Products') }}</h4>

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
                    <th>id</th>
                    <th>{{ __('name') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Category') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Created At') }}</th>
                    <th>{{ __('Updated At') }}</th>
                    <th class="rounded-end-bottom">{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->price_sell }}</td>
                        <td>{{ $product->categories_count ?? ($product->categories ? $product->categories->count() : 0) }}</td>
                        <td>{{ $product->description }}</td>
                        <td>
                            @if ($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                    width="50">
                            @endif
                        </td>
                        <td>{{ $product->created_at }}</td>
                        <td>{{ $product->updated_at }}</td>
                        <td>
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
                                    <a class="dropdown-item" href="javascript:void(0);">
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
                    <td colspan="10">
                        {!! $products->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
@endsection
