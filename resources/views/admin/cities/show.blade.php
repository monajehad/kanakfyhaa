@extends('layouts/layoutMaster')

@section('title', 'عرض تفاصيل المدينة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تفاصيل المدينة</h4>
    <a href="{{ route('admin.cities.index') }}" class="btn btn-secondary">الرجوع إلى القائمة</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="row g-4">

            <div class="col-md-4 text-center">
                <img src="{{ $city->country->flag_url ?? asset('images/placeholder-flag.png') }}" 
                     alt="علم {{ $city->country->name }}" 
                     class="rounded shadow-sm mb-3" width="200">
                <h5 class="fw-bold">{{ $city->name }}</h5>
                <p class="text-muted">{{ $city->native_name }}</p>
                <hr>
                <h6 class="fw-bold">الدولة:</h6>
                <p>
                    <a href="{{ route('admin.countries.show', $city->country->id) }}" class="text-decoration-none">
                        {{ $city->country->name }}
                    </a>
                </p>
            </div>

            <div class="col-md-8">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>المنطقة</th>
                            <td>{{ $city->region ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>المنطقة الفرعية</th>
                            <td>{{ $city->subregion ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>عدد السكان</th>
                            <td>{{ $city->population ? number_format($city->population) : '-' }}</td>
                        </tr>
                        <tr>
                            <th>خط العرض</th>
                            <td>{{ $city->latitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>خط الطول</th>
                            <td>{{ $city->longitude ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $city->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث</th>
                            <td>{{ $city->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
