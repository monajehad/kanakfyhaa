@extends('layouts/layoutMaster')

@section('title', __('Edit Package'))

@section('content')
<div class="row mb-4">
  <div class="col-md-12">
    <h4>{{ __('Edit Package') }}: {{ $package->name_ar ?? $package->name }}</h4>
    <small class="text-muted">{{ __('Update package details for this product') }}</small>
  </div>
</div>

<form action="{{ route('admin.products.packages.update', [$product, $package]) }}" method="POST">
  @csrf
  @method('PUT')

  <div class="row">
    <div class="col-md-8">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('Basic Information') }}</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label" for="name">{{ __('Package Name') }} *</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" 
                value="{{ old('name', $package->name) }}" placeholder="e.g., Basic, Premium, Deluxe" required>
              @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="name_ar">{{ __('Name (Arabic)') }}</label>
              <input type="text" class="form-control @error('name_ar') is-invalid @enderror" id="name_ar" name="name_ar" 
                value="{{ old('name_ar', $package->name_ar) }}" placeholder="الاسم بالعربية">
              @error('name_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="name_en">{{ __('Name (English)') }}</label>
              <input type="text" class="form-control @error('name_en') is-invalid @enderror" id="name_en" name="name_en" 
                value="{{ old('name_en', $package->name_en) }}" placeholder="Name in English">
              @error('name_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-12">
              <label class="form-label" for="description">{{ __('Description') }}</label>
              <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" 
                rows="3" placeholder="Brief description">{{ old('description', $package->description) }}</textarea>
              @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="description_ar">Description (Arabic)</label>
              <textarea class="form-control @error('description_ar') is-invalid @enderror" id="description_ar" name="description_ar" 
                rows="2" placeholder="الوصف بالعربية">{{ old('description_ar', $package->description_ar) }}</textarea>
              @error('description_ar') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="description_en">Description (English)</label>
              <textarea class="form-control @error('description_en') is-invalid @enderror" id="description_en" name="description_en" 
                rows="2" placeholder="Description in English">{{ old('description_en', $package->description_en) }}</textarea>
              @error('description_en') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('Pricing') }}</h5>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="price">{{ __('Price') }} *</label>
              <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price" name="price" 
                value="{{ old('price', $package->price) }}" placeholder="0.00" required>
              @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="original_price">{{ __('Original') }} ({{ __('Original Price (Before Discount)') }})</label>
              <input type="number" step="0.01" class="form-control @error('original_price') is-invalid @enderror" id="original_price" name="original_price" 
                value="{{ old('original_price', $package->original_price) }}" placeholder="Leave blank to use price">
              @error('original_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label" for="discount">{{ __('Discount') }} (%)</label>
              <input type="number" min="0" max="100" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" 
                value="{{ old('discount', $package->discount) }}" placeholder="0">
              @error('discount') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-6">
              <label class="form-label" for="shipping_price">{{ __('Shipping Price') }}</label>
              <input type="number" step="0.01" class="form-control @error('shipping_price') is-invalid @enderror" id="shipping_price" name="shipping_price" 
                value="{{ old('shipping_price', $package->shipping_price) }}" placeholder="0.00">
              @error('shipping_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
          <h5 class="mb-0">{{ __('Included Items') }}</h5>
          <button type="button" class="btn btn-sm btn-outline-primary" id="addItemBtn">
            <i class="bx bx-plus"></i> {{ __('Add Item') }}
          </button>
        </div>
        <div class="card-body">
          <div id="itemsContainer">
            @php
              $items = old('items', $package->items ?? []);
              if (empty($items)) $items = [['name' => '', 'name_ar' => '', 'name_en' => '', 'description' => '']];
            @endphp
            @foreach ($items as $index => $item)
              <div class="item-row mb-3" data-index="{{ $index }}">
                <div class="row">
                  <div class="col-md-12">
                    <label class="form-label">{{ __('Item Name') }} *</label>
                    <input type="text" class="form-control" name="items[{{ $index }}][name]" 
                      value="{{ $item['name'] ?? '' }}" placeholder="Item name" required>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-6">
                    <label class="form-label">{{ __('Name (Arabic)') }}</label>
                    <input type="text" class="form-control" name="items[{{ $index }}][name_ar]" 
                      value="{{ $item['name_ar'] ?? '' }}" placeholder="الاسم بالعربية">
                  </div>
                  <div class="col-md-6">
                    <label class="form-label">{{ __('Name (English)') }}</label>
                    <input type="text" class="form-control" name="items[{{ $index }}][name_en]" 
                      value="{{ $item['name_en'] ?? '' }}" placeholder="Name in English">
                  </div>
                </div>
                <div class="row mb-2">
                  <div class="col-md-12">
                    <label class="form-label">{{ __('Description') }}</label>
                    <textarea class="form-control" name="items[{{ $index }}][description]" rows="2">{{ $item['description'] ?? '' }}</textarea>
                  </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-danger removeItemBtn">
                  <i class="bx bx-trash"></i> {{ __('Remove') }}
                </button>
                <hr>
              </div>
            @endforeach
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-4">
      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('Inventory') }}</h5>
        </div>
        <div class="card-body">
          <div class="mb-3">
            <label class="form-label" for="quantity">{{ __('Available Quantity') }}</label>
            <input type="number" min="1" class="form-control @error('quantity') is-invalid @enderror" id="quantity" name="quantity" 
              value="{{ old('quantity', $package->quantity) }}" placeholder="1">
            @error('quantity') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <label class="form-label" for="order">{{ __('Display Order') }}</label>
            <input type="number" min="0" class="form-control @error('order') is-invalid @enderror" id="order" name="order" 
              value="{{ old('order', $package->order) }}" placeholder="0">
            @error('order') <div class="invalid-feedback">{{ $message }}</div> @enderror
          </div>

          <div class="mb-3">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                {{ old('is_active', $package->is_active) ? 'checked' : '' }}>
              <label class="form-check-label" for="is_active">
                {{ __('Active') }}
              </label>
            </div>
          </div>
        </div>
      </div>

      <div class="card mb-4">
        <div class="card-header">
          <h5 class="mb-0">{{ __('Preview') }}</h5>
        </div>
        <div class="card-body">
          <div class="small">
            <div class="mb-2">
              <strong>{{ __('Price') }}:</strong>
              <span id="previewPrice">$0.00</span>
            </div>
            <div class="mb-2">
              <strong>{{ __('Original') }}:</strong>
              <span id="previewOriginal">-</span>
            </div>
            <div class="mb-2">
              <strong>{{ __('Final Price') }}:</strong>
              <span id="previewFinal">$0.00</span>
            </div>
            <div class="mb-2">
              <strong>{{ __('Shipping') }}:</strong>
              <span id="previewShipping">$0.00</span>
            </div>
            <hr>
            <div>
              <strong>{{ __('Status') }}:</strong>
              <span id="previewStatus" class="badge bg-success">{{ __('Active') }}</span>
            </div>
          </div>
        </div>
      </div>

      <div class="d-grid gap-2">
        <button type="submit" class="btn btn-primary">
          <i class="bx bx-check"></i> {{ __('Package Updated') }}
        </button>
        <a href="{{ route('admin.products.packages.index', $product) }}" class="btn btn-outline-secondary">
          <i class="bx bx-x"></i> {{ __('Cancel') }}
        </a>
      </div>
    </div>
  </div>
</form>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', function() {
    let itemIndex = {{ count(old('items', $package->items ?? [['name' => '']])) }};

    // Add new item
    document.getElementById('addItemBtn').addEventListener('click', function() {
      const container = document.getElementById('itemsContainer');
      const itemRow = document.createElement('div');
      itemRow.className = 'item-row mb-3';
      itemRow.dataset.index = itemIndex;
      itemRow.innerHTML = `
        <div class="row">
          <div class="col-md-12">
            <label class="form-label">{{ __('Item Name') }} *</label>
            <input type="text" class="form-control" name="items[${itemIndex}][name]" placeholder="{{ __('Item Name') }}" required>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <label class="form-label">{{ __('Name (Arabic)') }}</label>
            <input type="text" class="form-control" name="items[${itemIndex}][name_ar]" placeholder="{{ __('Name (Arabic)') }}">
          </div>
          <div class="col-md-6">
            <label class="form-label">{{ __('Name (English)') }}</label>
            <input type="text" class="form-control" name="items[${itemIndex}][name_en]" placeholder="{{ __('Name (English)') }}">
          </div>
        </div>
        <div class="row mb-2">
          <div class="col-md-12">
            <label class="form-label">{{ __('Description') }}</label>
            <textarea class="form-control" name="items[${itemIndex}][description]" rows="2"></textarea>
          </div>
        </div>
        <button type="button" class="btn btn-sm btn-outline-danger removeItemBtn">
          <i class="bx bx-trash"></i> {{ __('Remove') }}
        </button>
        <hr>
      `;
      container.appendChild(itemRow);
      attachRemoveHandler(itemRow.querySelector('.removeItemBtn'));
      itemIndex++;
    });

    // Remove item
    function attachRemoveHandler(btn) {
      btn.addEventListener('click', function(e) {
        e.preventDefault();
        this.closest('.item-row').remove();
      });
    }

    document.querySelectorAll('.removeItemBtn').forEach(attachRemoveHandler);

    // Update preview
    function updatePreview() {
      const price = parseFloat(document.getElementById('price').value) || 0;
      const original = parseFloat(document.getElementById('original_price').value) || price;
      const discount = parseFloat(document.getElementById('discount').value) || 0;
      const shipping = parseFloat(document.getElementById('shipping_price').value) || 0;
      const isActive = document.getElementById('is_active').checked;

      const finalPrice = price - (price * discount / 100);

      document.getElementById('previewPrice').textContent = '$' + price.toFixed(2);
      document.getElementById('previewOriginal').textContent = original !== price ? '$' + original.toFixed(2) : '-';
      document.getElementById('previewFinal').textContent = '$' + finalPrice.toFixed(2);
      document.getElementById('previewShipping').textContent = '$' + shipping.toFixed(2);
      document.getElementById('previewStatus').textContent = isActive ? 'Active' : 'Inactive';
      document.getElementById('previewStatus').className = isActive ? 'badge bg-success' : 'badge bg-secondary';
    }

    // Attach listeners
    ['price', 'original_price', 'discount', 'shipping_price', 'is_active'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('change', updatePreview);
        el.addEventListener('input', updatePreview);
      }
    });

    updatePreview();
  });
</script>
@endpush
@endsection
