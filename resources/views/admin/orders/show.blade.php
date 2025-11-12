@extends('layouts/layoutMaster')

@section('title', 'Order #'.$order->order_number)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="mb-4">Order #{{ $order->order_number }}</h4>

  <div class="row">
    <div class="col-lg-8">
      <div class="card mb-4">
        <div class="card-header">Items</div>
        <div class="card-body">
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Size</th>
                  <th>Qty</th>
                  <th>Price</th>
                  <th>Total</th>
                </tr>
              </thead>
              <tbody>
              @foreach($order->items as $item)
                <tr>
                  <td>{{ is_array($item['name'] ?? null) ? ($item['name']['ar'] ?? $item['name']['en'] ?? '') : ($item['name'] ?? '') }}</td>
                  <td>{{ $item['selectedSize'] ?? '-' }}</td>
                  <td>{{ $item['quantity'] ?? 1 }}</td>
                  <td>{{ $order->currency_symbol }}{{ number_format(($item['price'] ?? 0) * $order->currency_rate, 2) }}</td>
                  <td><strong>{{ $order->currency_symbol }}{{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1) * $order->currency_rate, 2) }}</strong></td>
                </tr>
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="card-footer d-flex justify-content-end gap-3">
          <div>Subtotal: <strong>{{ $order->currency_symbol }}{{ number_format($order->subtotal * $order->currency_rate, 2) }}</strong></div>
          <div>Shipping: <strong>{{ $order->currency_symbol }}{{ number_format($order->shipping * $order->currency_rate, 2) }}</strong></div>
          <div>Total: <strong>{{ $order->currency_symbol }}{{ number_format($order->total * $order->currency_rate, 2) }}</strong></div>
        </div>
      </div>
    </div>

    <div class="col-lg-4">
      <div class="card mb-4">
        <div class="card-header">Customer</div>
        <div class="card-body">
          <div><strong>{{ $order->customer_name }}</strong></div>
          <div>{{ $order->email }}</div>
          <div>{{ $order->phone }}</div>
          <div>{{ $order->country }} - {{ $order->city }}</div>
          <div>{{ $order->address }}</div>
          <div>{{ $order->postal_code }}</div>
        </div>
      </div>

      <div class="card">
        <div class="card-header">Status</div>
        <div class="card-body">
          <div class="mb-2">Payment: <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">{{ ucfirst($order->payment_status) }}</span></div>
          <div class="mb-3">Method: <strong>{{ ucfirst($order->payment_method) }}</strong></div>
          <form method="post" action="{{ route('admin.orders.update', $order) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
              <label class="form-label">Order Status</label>
              <select class="form-select" name="order_status">
                @foreach(['processing','shipped','delivered','cancelled'] as $st)
                  <option value="{{ $st }}" @selected($order->order_status === $st)>{{ ucfirst($st) }}</option>
                @endforeach
              </select>
            </div>
            <button class="btn btn-primary" type="submit">Update</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


