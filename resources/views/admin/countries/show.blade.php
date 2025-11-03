@extends('layouts/layoutMaster')

@section('title', 'عرض تفاصيل الدولة')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تفاصيل الدولة</h4>
    <a href="{{ route('admin.countries.index') }}" class="btn btn-secondary">الرجوع إلى القائمة</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="row g-4">

            <div class="col-md-4 text-center">
                <img src="{{ $country->flag_url ?? asset('images/placeholder-flag.png') }}" 
                     alt="علم {{ $country->name }}" 
                     class="rounded shadow-sm mb-3" width="200">
                <h5 class="fw-bold">{{ $country->name }}</h5>
                <p class="text-muted">{{ $country->native_name }}</p>
            </div>

            <div class="col-md-8">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>رمز ISO2</th>
                            <td>{{ $country->iso2 }}</td>
                        </tr>
                        <tr>
                            <th>رمز ISO3</th>
                            <td>{{ $country->iso3 }}</td>
                        </tr>
                        <tr>
                            <th>الكود الرقمي</th>
                            <td>{{ $country->numeric_code }}</td>
                        </tr>
                        <tr>
                            <th>كود الهاتف</th>
                            <td>{{ $country->phone_code }}</td>
                        </tr>
                        <tr>
                            <th>العاصمة</th>
                            <td>{{ $country->capital }}</td>
                        </tr>
                        <tr>
                            <th>رمز العملة</th>
                            <td>{{ $country->currency_symbol }}</td>
                        </tr>
                        <tr>
                            <th>اسم العملة</th>
                            <td>{{ $country->currency_name }}</td>
                        </tr>
                        <tr>
                            <th>المنطقة</th>
                            <td>{{ $country->region }}</td>
                        </tr>
                        <tr>
                            <th>المنطقة الفرعية</th>
                            <td>{{ $country->subregion }}</td>
                        </tr>
                        <tr>
                            <th>عدد المدن</th>
                            <td>{{ $country->cities_count }}</td>
                        </tr>
                        <tr>
                            <th>المنطقة الزمنية</th>
                            <td>{{ $country->timezone }}</td>
                        </tr>
                        <tr>
                            <th>عدد السكان</th>
                            <td>{{ number_format($country->population) }}</td>
                        </tr>
                        <tr>
                            <th>المساحة</th>
                            <td>{{ number_format($country->area) }} كم²</td>
                        </tr>
                        <tr>
                            <th>الإحداثيات</th>
                            <td>
                                خط العرض: {{ $country->latitude }}<br>
                                خط الطول: {{ $country->longitude }}
                            </td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $country->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث</th>
                            <td>{{ $country->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
@endsection
