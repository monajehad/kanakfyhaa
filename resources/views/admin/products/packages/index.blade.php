@extends('layouts/layoutMaster')

@section('title', __('Package Management'))

@section('content')
<div class="row mb-4">
  <div class="col-md-8">
    <h4>{{ $product->name_ar ?? $product->name }} - {{ __('Package Management') }}</h4>
    <small class="text-muted">{{ __('Configure the package for this product') }}</small>
  </div>
  <div class="col-md-4 text-end">
    @if (!$package)
      <a href="{{ route('admin.products.packages.create', $product) }}" class="btn btn-primary">
        <i class="bx bx-plus"></i> {{ __('Create Package') }}
      </a>
    @endif
    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-secondary">
      <i class="bx bx-arrow-back"></i> {{ __('Back to Product') }}
    </a>
  </div>
</div>

@if (!$package)
  <div class="alert alert-info">
    <i class="bx bx-info-circle"></i> {{ __('No package configured yet') }}. <a href="{{ route('admin.products.packages.create', $product) }}">{{ __('Create one now') }}</a>
  </div>
@else
  <div class="row">
    <div class="col-md-8">
        <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h5 class="mb-0">{{ $package->name_ar ?? $package->name }}</h5>
            <small class="text-muted">{{ $package->localized_name }}</small>
          </div>
          <span class="badge bg-{{ $package->is_active ? 'success' : 'secondary' }}">
            {{ $package->is_active ? __('Active') : __('Inactive') }}
          </span>
        </div>
          <div class="card-body">
            <p class="card-text text-muted small">
              {{ Str::limit($package->description_ar ?? $package->description ?? __('No description'), 100) }}
            </p>
            
            <div class="row mb-3 small">
              <div class="col-6">
                <strong>{{ __('Price') }}:</strong> ${{ number_format($package->price, 2) }}
              </div>
              <div class="col-6">
                <strong>{{ __('Discount') }}:</strong> {{ $package->discount }}%
              </div>
            </div>

            <div class="row mb-3 small">
              <div class="col-6">
                <strong>{{ __('Final Price') }}:</strong> ${{ number_format($package->final_price, 2) }}
              </div>
              <div class="col-6">
                <strong>{{ __('Shipping') }}:</strong> ${{ number_format($package->shipping_price, 2) }}
              </div>
            </div>

            <div class="row mb-3 small">
              <div class="col-6">
                <strong>{{ __('Stock') }}:</strong> 
                <span class="badge bg-{{ $package->quantity > 0 ? 'info' : 'danger' }}">
                  {{ $package->quantity }}
                </span>
              </div>
              <div class="col-6">
                <strong>{{ __('Display Order') }}:</strong> {{ $package->order }}
              </div>
            </div>

            @if ($package->items && count($package->items) > 0)
              <div class="mb-3">
                <strong class="small">{{ __('Included Items') }}:</strong>
                <ul class="small mb-0">
                  @foreach ($package->items as $item)
                    <li>
                      {{ $item['name_ar'] ?? $item['name'] ?? $item }}
                      @if (is_array($item) && isset($item['description']))
                        <br><small class="text-muted">{{ $item['description'] }}</small>
                      @endif
                    </li>
                  @endforeach
                </ul>
              </div>
            @endif
          </div>
          <div class="card-footer d-flex gap-2">
            <a href="{{ route('admin.products.packages.edit', [$product, $package]) }}" class="btn btn-sm btn-outline-warning flex-grow-1">
              <i class="bx bx-edit"></i> {{ __('Edit') }}
            </a>
            <form action="{{ route('admin.products.packages.destroy', [$product, $package]) }}" method="POST" class="flex-grow-1">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger w-100" onclick="return confirm('{{ __('Are you sure you want to delete this package?') }}')">
                <i class="bx bx-trash"></i> {{ __('Delete') }}
              </button>
            </form>
          </div>
      </div>
    </div>
  </div>
@endif

@push('scripts')
<script>
  // Auto-update package preview on form changes
  document.addEventListener('DOMContentLoaded', function() {
    console.log('Package management page loaded');
  });
</script>
@endpush
@endsection
