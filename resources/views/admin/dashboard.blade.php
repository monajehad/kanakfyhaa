@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Home')

@section('content')
@php
  use Carbon\Carbon;
  use App\Models\Order;
  use App\Models\Product;
  use App\Models\ProductPackage;
  use Illuminate\Support\Str;
  
  // Build last 14 days series
  $days = collect(range(0,13))->map(fn($i)=>Carbon::today()->subDays(13-$i));
  $ordersByDay = $days->mapWithKeys(function($day){
    $count = Order::whereDate('order_date',$day)->count();
    return [$day->format('Y-m-d') => $count];
  });
  $salesByDay = $days->mapWithKeys(function($day){
    $sum = Order::whereDate('order_date',$day)->sum('total');
    return [$day->format('Y-m-d') => (float)$sum];
  });
  $paymentSplit = [
    'paypal' => Order::where('payment_method','paypal')->count(),
  ];
  $statusSplit = [
    'processing' => Order::where('order_status','processing')->count(),
    'shipped' => Order::where('order_status','shipped')->count(),
    'delivered' => Order::where('order_status','delivered')->count(),
    'cancelled' => Order::where('order_status','cancelled')->count(),
  ];
  $topCountries = Order::selectRaw('country, COUNT(*) as cnt')
    ->whereNotNull('country')->groupBy('country')->orderByDesc('cnt')->limit(5)->get();
  $recent = Order::latest('order_date')->limit(8)->get();
  
  // Package statistics
  $totalPackages = ProductPackage::count();
  $activePackages = ProductPackage::where('is_active', true)->count();
  $packageProducts = Product::where('is_package', true)->count();
  $recentPackages = ProductPackage::with('product')->latest()->limit(5)->get();
@endphp
<h4 class="mb-4">{{ __('Dashboard') }}</h4>

<div class="row mb-4">
  <div class="col-md-3">
    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Total Orders</span>
            <span>📦</span>
          </div>
          <h3 class="mb-0">{{ \App\Models\Order::count() }}</h3>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Processing</span>
            <span>⏳</span>
          </div>
          <h3 class="mb-0">{{ \App\Models\Order::where('order_status','processing')->count() }}</h3>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Delivered</span>
            <span>✅</span>
          </div>
          <h3 class="mb-0">{{ \App\Models\Order::where('order_status','delivered')->count() }}</h3>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-3">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <span>Total Sales</span>
          <span>💰</span>
        </div>
        <h3 class="mb-0">${{ number_format(\App\Models\Order::sum('total'), 2) }}</h3>
      </div>
    </div>
  </div>
</div>

<!-- Product Packages Section -->
<div class="row mb-4">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <div class="d-flex justify-content-between">
          <span>{{ __('Total Packages') }}</span>
          <span>📋</span>
        </div>
        <h3 class="mb-0">{{ $totalPackages }}</h3>
        <small class="text-muted">{{ $activePackages }} {{ __('active') }}</small>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <a href="{{ route('admin.products.index', ['filter' => 'packages']) }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('Package Products') }}</span>
            <span>🎁</span>
          </div>
          <h3 class="mb-0">{{ $packageProducts }}</h3>
          <small class="text-muted">{{ __('Products with packages') }}</small>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>{{ __('All products') }}</span>
            <span>📦</span>
          </div>
          <h3 class="mb-0">{{ Product::count() }}</h3>
          <small class="text-muted">All products</small>
        </div>
      </div>
    </a>
  </div>
</div>

<div class="row">
  <div class="col-lg-8 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ __('Total Sales') }} (14d)</span>
        <small class="text-muted">{{ now()->format('Y-m-d') }}</small>
      </div>
      <div class="card-body">
        <canvas id="salesChart" height="110"></canvas>
      </div>
    </div>
  </div>
  <div class="col-lg-4 mb-4">
    <div class="card h-100 mb-4">
      <div class="card-header">{{ __('Payment') }}</div>
      <div class="card-body">
        <canvas id="paymentChart" height="140"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header">{{ __('Status') }}</div>
      <div class="card-body"><canvas id="statusChart" height="120"></canvas></div>
    </div>
  </div>
  <div class="col-lg-6 mb-4">
    <div class="card h-100">
      <div class="card-header">{{ __('Top Countries') }}</div>
      <div class="card-body">
        <ul class="list-group list-group-flush">
          @forelse($topCountries as $row)
            <li class="list-group-item d-flex justify-content-between align-items-center">
              <span class="badge bg-label-primary me-2">{{ $row->country }}</span>
              <span class="fw-bold">{{ $row->cnt }}</span>
            </li>
          @empty
            <li class="list-group-item text-muted">-</li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
</div>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>{{ __('Recent') }} {{ __('Orders') }}</span>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('All Orders') }}</a>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>#</th><th>{{ __('Order Number') }}</th><th>{{ __('Customer') }}</th><th>{{ __('Amount') }}</th><th>{{ __('Payment Status') }}</th><th>{{ __('Date') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recent as $o)
        <tr>
          <td>{{ $o->id }}</td>
          <td>{{ $o->order_number }}</td>
          <td>{{ $o->customer_name }}</td>
          <td><strong>{{ $o->currency_symbol }}{{ number_format($o->total * $o->currency_rate, 2) }}</strong></td>
          <td><span class="badge bg-{{ $o->payment_status==='paid'?'success':($o->payment_status==='pending'?'warning':'danger') }}">{{ ucfirst($o->payment_status) }}</span></td>
          <td>{{ optional($o->order_date)->format('Y-m-d') }}</td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">-</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<!-- Recent Packages Section -->
<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <span>{{ __('Recent') }} {{ __('Packages') }}</span>
    <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Manage Packages') }}</a>
  </div>
  <div class="table-responsive">
    <table class="table mb-0">
      <thead>
        <tr>
          <th>{{ __('Package Name') }}</th>
          <th>{{ __('Product') }}</th>
          <th>{{ __('Price') }}</th>
          <th>{{ __('Discount') }}</th>
          <th>{{ __('Quantity') }}</th>
          <th>{{ __('Status') }}</th>
          <th>{{ __('Actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentPackages as $pkg)
        <tr>
          <td>
            <strong>{{ $pkg->name_ar ?? $pkg->name }}</strong>
            <br>
            <small class="text-muted">{{ Str::limit($pkg->description_ar ?? $pkg->description ?? '-', 50) }}</small>
          </td>
          <td>
            <a href="{{ route('admin.products.edit', $pkg->product) }}" class="text-decoration-none">
              {{ $pkg->product->name_ar ?? $pkg->product->name }}
            </a>
          </td>
          <td><strong>${{ number_format($pkg->price, 2) }}</strong></td>
          <td>{{ $pkg->discount }}%</td>
          <td>
            <span class="badge bg-{{ $pkg->quantity > 0 ? 'success' : 'danger' }}">
              {{ $pkg->quantity }}
            </span>
          </td>
          <td>
            <span class="badge bg-{{ $pkg->is_active ? 'info' : 'secondary' }}">
              {{ $pkg->is_active ? __('Active') : __('Inactive') }}
            </span>
          </td>
          <td>
            <a href="{{ route('admin.products.packages.edit', [$pkg->product, $pkg]) }}" class="btn btn-sm btn-outline-warning">
              <i class="bx bx-edit"></i>
            </a>
            <form action="{{ route('admin.products.packages.destroy', [$pkg->product, $pkg]) }}" method="POST" style="display:inline-block;">
              @csrf
              @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?')">
                <i class="bx bx-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="7" class="text-center text-muted">{{ __('No packages yet') }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
  (function(){
    const labels = {!! json_encode($days->map->format('M d')) !!};
    const sales = {!! json_encode(array_values($salesByDay->toArray())) !!};
    const orders = {!! json_encode(array_values($ordersByDay->toArray())) !!};
    const ctxS = document.getElementById('salesChart').getContext('2d');
    new Chart(ctxS, {
      type: 'line',
      data: {
        labels,
        datasets: [
          { label: '{{ __("Total Sales") }}', data: sales, borderColor: '#16a34a', tension:.3, fill: true, backgroundColor:'rgba(22,163,74,.08)' },
          { label: '{{ __("Orders") }}', data: orders, borderColor: '#0ea5e9', tension:.3, yAxisID:'y1' }
        ]
      },
      options: { responsive: true, interaction:{mode:'index', intersect:false},
        scales:{ y:{ beginAtZero:true }, y1:{ beginAtZero:true, position:'right', grid:{ drawOnChartArea:false } } }
      }
    });

    const payCtx = document.getElementById('paymentChart').getContext('2d');
    new Chart(payCtx, {
      type: 'doughnut',
      data: { labels: ['PayPal'], datasets:[{ data:[{{ $paymentSplit['paypal'] }}], backgroundColor:['#2563eb'] }] },
      options:{ plugins:{ legend:{ position:'bottom' } } }
    });

    const stCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(stCtx, {
      type: 'pie',
      data: { labels: ['{{ __("Processing") }}','{{ __("Shipped") }}','{{ __("Delivered") }}','{{ __("Cancelled") }}'],
        datasets:[{ data:[{{ $statusSplit['processing'] }},{{ $statusSplit['shipped'] }},{{ $statusSplit['delivered'] }},{{ $statusSplit['cancelled'] }}],
          backgroundColor:['#0ea5e9','#f59e0b','#22c55e','#ef4444'] }] },
      options:{ plugins:{ legend:{ position:'bottom' } } }
    });
  })();
</script>
@endpush
@endsection
