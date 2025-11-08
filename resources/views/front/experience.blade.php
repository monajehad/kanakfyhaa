@extends('layouts.layoutMaster')

@section('title', 'تجربة المنتج')

@section('content')
<div class="container text-center py-5">
    <h2 class="mb-4">🌍 تجربة "كأنك فيها"</h2>

    <h4>{{ $product->name }}</h4>
    <p class="text-muted">{{ $product->city->name ?? 'مدينة غير محددة' }}</p>

    <div class="mt-4">
        @if($product->media->count())
            <img src="{{ $product->media->where('role','main')->first()->url ?? '' }}" 
                 alt="{{ $product->name }}" 
                 style="max-width:300px;border-radius:10px;">
        @endif
    </div>

    <div class="mt-3">
        <p>{{ $product->short_description }}</p>
        <p>{!! $product->description !!}</p>
    </div>

    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-primary">العودة إلى الموقع</a>
    </div>
</div>
@endsection
