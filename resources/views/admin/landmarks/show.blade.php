@extends('layouts/layoutMaster')

@section('title', 'عرض تفاصيل المعلم')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تفاصيل المعلم</h4>
    <a href="{{ route('admin.landmarks.index') }}" class="btn btn-secondary">الرجوع إلى القائمة</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="row g-4">

            {{-- صورة المعلم الرئيسية --}}
            <div class="col-md-4 text-center">
                @php
                    $mainMedia = $landmark->media->where('role', 'main')->first();
                @endphp
                <img src="{{ $mainMedia ? asset($mainMedia->url) : asset('images/placeholder.png') }}" 
                     alt="{{ $landmark->name }}" 
                     class="rounded shadow-sm mb-3" width="200">
                <h5 class="fw-bold">{{ $landmark->name }}</h5>
                <p class="text-muted">{{ $landmark->type ?? '-' }}</p>
                <hr>
                <h6 class="fw-bold">المدينة:</h6>
                <p>
                    @if($landmark->city)
                        <a href="{{ route('admin.cities.show', $landmark->city->id) }}" class="text-decoration-none">
                            {{ $landmark->city->name }}
                        </a>
                    @else
                        -
                    @endif
                </p>
               
            </div>

            {{-- تفاصيل المعلم --}}
            <div class="col-md-8">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>الوصف المختصر</th>
                            <td>{{ $landmark->short_description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الوصف التفصيلي</th>
                            <td>{!! $landmark->description ?? '-' !!}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $landmark->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث</th>
                            <td>{{ $landmark->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- الصور الإضافية --}}
                @php
                    $subImages = $landmark->media->where('role', 'sub');
                @endphp
                @if($subImages->count())
                    <h6 class="mt-4">صور إضافية</h6>
                    <div class="d-flex flex-wrap align-items-center mt-2">
                        @foreach($subImages as $image)
                            <div class="me-2 mb-2">
                                <a href="{{ asset($image->url) }}" target="_blank">
                                    <img src="{{ asset($image->url) }}" 
                                         alt="{{ $image->alt_text ?? $landmark->name }}" 
                                         style="max-width:100px; max-height:100px; object-fit:cover; border-radius:4px; border:1px solid #dfdfdf;">
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection
