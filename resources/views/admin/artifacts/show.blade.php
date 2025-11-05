@extends('layouts/layoutMaster')

@section('title', 'عرض تفاصيل الأثر')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
    <h4 class="mb-0">تفاصيل الأثر</h4>
    <a href="{{ route('admin.artifacts.index') }}" class="btn btn-secondary">الرجوع إلى قائمة الآثار</a>
</div>

<div class="card shadow border-0">
    <div class="card-body">
        <div class="row g-4">

            {{-- صورة الأثر الرئيسية --}}
            <div class="col-md-4 text-center">
                @php
                    $mainMedia = $artifact->media->where('role', 'main')->first();
                @endphp
                <img src="{{ $mainMedia ? asset($mainMedia->url) : asset('images/placeholder.png') }}" 
                     alt="{{ $artifact->name }}" 
                     class="rounded shadow-sm mb-3" width="200">
                <h5 class="fw-bold">{{ $artifact->title }}</h5>
               
                <hr>
                <h6 class="fw-bold">المعلم:</h6>
                <p>
                    @if($artifact->landmark)
                        <a href="{{ route('admin.landmarks.show', $artifact->landmark->id) }}" class="text-decoration-none">
                            {{ $artifact->landmark->name }}
                        </a>
                    @else
                        -
                    @endif
                </p>
                <h6 class="fw-bold">المدينة:</h6>
                <p>
                    @if($artifact->landmark && $artifact->landmark->city)
                        <a href="{{ route('admin.cities.show', $artifact->landmark->city->id) }}" class="text-decoration-none">
                            {{ $artifact->landmark->city->name }}
                        </a>
                    @else
                        -
                    @endif
                </p>
            </div>

            {{-- تفاصيل الأثر --}}
            <div class="col-md-8">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>الوصف المختصر</th>
                            <td>{{ $artifact->short_description ?? '-' }}</td>
                        </tr>
                        <tr>
                            <th>الوصف التفصيلي</th>
                            <td>{!! $artifact->description ?? '-' !!}</td>
                        </tr>
                        <tr>
                            <th>تاريخ الإنشاء</th>
                            <td>{{ $artifact->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                        <tr>
                            <th>آخر تحديث</th>
                            <td>{{ $artifact->updated_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    </tbody>
                </table>

                {{-- الصور الإضافية --}}
                @php
                    $subImages = $artifact->media->where('role', 'sub');
                @endphp
                @if($subImages->count())
                    <h6 class="mt-4">صور إضافية</h6>
                    <div class="d-flex flex-wrap align-items-center mt-2">
                        @foreach($subImages as $image)
                            <div class="me-2 mb-2">
                                <a href="{{ asset($image->url) }}" target="_blank">
                                    <img src="{{ asset($image->url) }}" 
                                         alt="{{ $image->alt_text ?? $artifact->name }}" 
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
