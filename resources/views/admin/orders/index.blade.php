@extends('layouts/layoutMaster')

@section('title', __('Orders'))

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
  <h4 class="mb-4">{{ __('Orders') }}</h4>

  <div class="card mb-4">
    <div class="card-body">
      <form class="row g-3 align-items-end">
        <div class="col-md-3">
          <label class="form-label">{{ __('Search') }}</label>
          <input type="text" class="form-control" name="q" value="{{ $q }}" placeholder="{{ __('Order Number') }}, {{ __('name') }}, {{ __('Email') }}, {{ __('Phone') }}">
        </div>
        <div class="col-md-2">
          <label class="form-label">{{ __('Status') }}</label>
          <select class="form-select" name="status">
            <option value="">{{ __('All') }}</option>
            @foreach(['processing','shipped','delivered','cancelled'] as $st)
              <option value="{{ $st }}" @selected($status===$st)>{{ __(ucfirst($st)) }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label">{{ __('From') }}</label>
          <input type="date" class="form-control" name="from" value="{{ $from }}">
        </div>
        <div class="col-md-2">
          <label class="form-label">{{ __('To') }}</label>
          <input type="date" class="form-control" name="to" value="{{ $to }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button class="btn btn-primary flex-grow-1" type="submit">{{ __('Filter') }}</button>
          <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">{{ __('Reset') }}</a>
          <a class="btn btn-success" href="{{ request()->fullUrlWithQuery(['export'=>'csv']) }}">{{ __('Export CSV') }}</a>
        </div>
      </form>
    </div>
  </div>

  <div class="row mb-4">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('Total Orders') }}</span>
            <span>📦</span>
          </div>
          <h3 class="mb-0">{{ $stats['total'] }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('Processing') }}</span>
            <span>⏳</span>
          </div>
          <h3 class="mb-0">{{ $stats['processing'] }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('Delivered') }}</span>
            <span>✅</span>
          </div>
          <h3 class="mb-0">{{ $stats['delivered'] }}</h3>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('Total Sales') }}</span>
            <span>💰</span>
          </div>
          <h3 class="mb-0">${{ number_format($stats['sales'], 2) }}</h3>
        </div>
      </div>
    </div>
  </div>

  <div class="card">
    <div class="table-responsive text-nowrap">
      <table class="table">
        <thead>
          <tr>
            <th>#</th>
            <th>{{ __('Order') }}</th>
            <th>{{ __('Customer') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Amount') }}</th>
            <th>{{ __('Payment') }}</th>
            <th>{{ __('Status') }}</th>
            <th>{{ __('Date') }}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
        @forelse($orders as $order)
          <tr>
            <td>{{ $order->id }}</td>
            <td>{{ $order->order_number }}</td>
            <td>{{ $order->customer_name }}</td>
            <td>{{ $order->email }}</td>
            <td><strong>{{ $order->currency_symbol }}{{ number_format($order->total * $order->currency_rate, 2) }}</strong></td>
            <td>
              <span class="badge bg-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                {{ __(ucfirst($order->payment_status)) }}
              </span>
            </td>
            <td>
              <select class="form-select form-select-sm order-status-select" data-order-id="{{ $order->id }}">
                @foreach(['processing','shipped','delivered','cancelled'] as $st)
                  <option value="{{ $st }}" @selected($order->order_status===$st)>{{ __(ucfirst($st)) }}</option>
                @endforeach
              </select>
            </td>
            <td>{{ optional($order->order_date)->format('Y-m-d') }}</td>
            <td>
              <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="9" class="text-center py-4">{{ __('No orders found') }}</td>
          </tr>
        @endforelse
        </tbody>
      </table>
    </div>
    <div class="card-footer">
      {{ $orders->links() }}
    </div>
  </div>
</div>

@push('scripts')
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
  <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
  <script>
    (function(){
      toastr.options = { closeButton:true, progressBar:true, positionClass:'toast-top-center', timeOut:'2000' };
      const token = '{{ csrf_token() }}';
      $('.order-status-select').on('change', function(){
        const id = $(this).data('order-id');
        const value = $(this).val();
        const url = '{{ url('/admin/orders') }}/' + id;
        const prev = $(this).data('prev') || this.defaultValue;
        axios({
          method: 'post',
          url,
          data: { _method:'PUT', _token: token, order_status: value }
        }).then(function(){
          toastr.success('{{ __("Status updated") }}');
        }).catch(function(){
          toastr.error('{{ __("Failed to update") }}');
          $(this).val(prev);
        }.bind(this));
      });
    })();
  </script>
@endpush
@endsection


