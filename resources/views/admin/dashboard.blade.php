@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', __('Dashboard'))

@section('content')
@php
  use Carbon\Carbon;
@endphp

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Dashboard Header -->
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h4 class="mb-1">👋 {{ __('Welcome back') }}!</h4>
            <p class="text-muted mb-0">{{ __('Here\'s what\'s happening with your business today.') }}</p>
        </div>
        <a href="{{ route('admin.settings.index') }}" class="btn btn-outline-primary">
            <i class="bx bx-cog me-2"></i>{{ __('Settings') }}
        </a>
    </div>

    <!-- Revenue Overview -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 fw-medium">{{ __('Total Revenue') }}</p>
                            <h3 class="mb-0">${{ number_format($totalRevenue, 2) }}</h3>
                            <small class="text-success"><i class="bx bx-trending-up"></i> +5.2% from last month</small>
                        </div>
                        <div class="badge bg-success-light text-success fs-2">💰</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 fw-medium">{{ __('This Month') }}</p>
                            <h3 class="mb-0">${{ number_format($monthlyRevenue, 2) }}</h3>
                            <small class="text-info"><i class="bx bx-calendar"></i> {{ now()->format('F') }}</small>
                        </div>
                        <div class="badge bg-info-light text-info fs-2">📊</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 fw-medium">{{ __('This Week') }}</p>
                            <h3 class="mb-0">${{ number_format($weeklyRevenue, 2) }}</h3>
                            <small class="text-primary"><i class="bx bx-week"></i> 7 days</small>
                        </div>
                        <div class="badge bg-primary-light text-primary fs-2">📈</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <p class="text-muted mb-1 fw-medium">{{ __('Today') }}</p>
                            <h3 class="mb-0">${{ number_format($todayRevenue, 2) }}</h3>
                            <small class="text-warning"><i class="bx bx-sun"></i> Just now</small>
                        </div>
                        <div class="badge bg-warning-light text-warning fs-2">⚡</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-medium">{{ __('Total Orders') }}</span>
                            <span class="fs-3">📦</span>
                        </div>
                        <h3 class="mb-2">{{ $totalOrders }}</h3>
                        <div class="progress" style="height: 4px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ min($processingOrders / ($totalOrders ?: 1) * 100, 100) }}%"></div>
                        </div>
                        <small class="text-muted">{{ $processingOrders }} processing</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-medium">{{ __('Products') }}</span>
                            <span class="fs-3">🛍️</span>
                        </div>
                        <h3 class="mb-2">{{ $totalProducts }}</h3>
                        <span class="badge bg-label-info">{{ $packageProducts }} packages</span>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.landmarks.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-medium">{{ __('Landmarks') }}</span>
                            <span class="fs-3">🏛️</span>
                        </div>
                        <h3 class="mb-2">{{ $totalLandmarks }}</h3>
                        <small class="text-muted">across {{ $totalCities }} cities</small>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3 mb-3">
            <a href="{{ route('admin.cities.index') }}" class="text-decoration-none">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="text-muted fw-medium">{{ __('Locations') }}</span>
                            <span class="fs-3">🌍</span>
                        </div>
                        <h3 class="mb-2">{{ $totalCountries }}</h3>
                        <small class="text-muted">{{ $totalCities }} cities</small>
                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Charts and Analytics -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <span class="fw-bold">{{ __('Sales Analytics') }} (14 {{ __('days') }})</span>
                    <small class="text-muted">{{ now()->format('Y-m-d') }}</small>
                </div>
                <div class="card-body">
                    <canvas id="salesChart" height="90"></canvas>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <span class="fw-bold">{{ __('Order Status') }}</span>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="140"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Status Breakdown -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">{{ __('Processing') }}</p>
                            <h4 class="mb-0 text-primary">{{ $processingOrders }}</h4>
                        </div>
                        <div class="fs-3">⏳</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">{{ __('Delivered') }}</p>
                            <h4 class="mb-0 text-success">{{ $deliveredOrders }}</h4>
                        </div>
                        <div class="fs-3">✅</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">{{ __('Cancelled') }}</p>
                            <h4 class="mb-0 text-danger">{{ $cancelledOrders }}</h4>
                        </div>
                        <div class="fs-3">❌</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">{{ __('Users') }}</p>
                            <h4 class="mb-0 text-info">{{ $totalUsers }}</h4>
                        </div>
                        <div class="fs-3">👥</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Content Section -->
    <div class="row mb-5">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center bg-light">
                    <span class="fw-bold">{{ __('Recent Orders') }}</span>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">{{ __('View All') }}</a>
                </div>
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ __('Order Number') }}</th>
                                <th>{{ __('Customer') }}</th>
                                <th>{{ __('Amount') }}</th>
                                <th>{{ __('Payment Status') }}</th>
                                <th>{{ __('Date') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recent as $o)
                            <tr>
                                <td>{{ $o->id }}</td>
                                <td><strong>{{ $o->order_number }}</strong></td>
                                <td>{{ $o->customer_name }}</td>
                                <td><strong>${{ number_format($o->total, 2) }}</strong></td>
                                <td><span class="badge bg-{{ $o->payment_status==='paid'?'success':($o->payment_status==='pending'?'warning':'danger') }}">{{ ucfirst($o->payment_status) }}</span></td>
                                <td><small>{{ optional($o->order_date)->format('Y-m-d H:i') }}</small></td>
                            </tr>
                            @empty
                            <tr><td colspan="6" class="text-center text-muted py-4">{{ __('No orders yet') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Content Stats -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-light">
                    <span class="fw-bold">{{ __('Content Stats') }}</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span>{{ __('Categories') }}</span>
                        <strong>{{ $totalCategories }}</strong>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span>{{ __('Products') }}</span>
                        <strong>{{ $totalProducts }}</strong>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span>{{ __('Packages') }}</span>
                        <strong>{{ $totalPackages }} <span class="badge bg-label-success ms-2">{{ $activePackages }} active</span></strong>
                    </div>
                    <hr>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span>{{ __('Countries') }}</span>
                        <strong>{{ $totalCountries }}</strong>
                    </div>
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <span>{{ __('Cities') }}</span>
                        <strong>{{ $totalCities }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Landmarks') }}</span>
                        <strong>{{ $totalLandmarks }}</strong>
                    </div>
                </div>
            </div>

            <!-- Top Countries -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <span class="fw-bold">{{ __('Top Countries') }}</span>
                </div>
                <div class="card-body">
                    @forelse($topCountries as $row)
                        <div class="mb-3 d-flex justify-content-between align-items-center">
                            <span>{{ $row->country }}</span>
                            <span class="badge bg-label-primary">{{ $row->cnt }}</span>
                        </div>
                    @empty
                        <p class="text-muted">{{ __('No data available') }}</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Packages -->
    @if($recentPackages->count() > 0)
    <div class="card border-0 shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-light">
            <span class="fw-bold">{{ __('Recent Packages') }}</span>
            <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">{{ __('Manage Packages') }}</a>
        </div>
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
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
                    @foreach($recentPackages as $pkg)
                    <tr>
                        <td>
                            <strong>{{ $pkg->name_ar ?? $pkg->name }}</strong>
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
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
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

    const stCtx = document.getElementById('statusChart').getContext('2d');
    new Chart(stCtx, {
      type: 'doughnut',
      data: { 
        labels: ['{{ __("Processing") }}','{{ __("Shipped") }}','{{ __("Delivered") }}','{{ __("Cancelled") }}'],
        datasets:[{ 
          data:[{{ $statusSplit['processing'] }},0,{{ $statusSplit['delivered'] }},{{ $statusSplit['cancelled'] }}],
          backgroundColor:['#0ea5e9','#f59e0b','#22c55e','#ef4444'] 
        }] 
      },
      options:{ plugins:{ legend:{ position:'bottom' } } }
    });
  })();
</script>
@endpush
@endsection
