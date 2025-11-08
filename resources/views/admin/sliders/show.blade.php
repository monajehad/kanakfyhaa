@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'تفاصيل التصنيف')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
        <h4 class="mb-0">تفاصيل التصنيف</h4>
        <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-warning" title="تعديل التصنيف">
            <i class="ri ri-pencil-line me-1"></i> تعديل
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <dl class="row mb-0 gy-3">
                <dt class="col-sm-4">ID التصنيف</dt>
                <dd class="col-sm-8">{{ $category->id }}</dd>

                <dt class="col-sm-4">اسم التصنيف</dt>
                <dd class="col-sm-8 fw-bold">{{ $category->name }}</dd>

                <dt class="col-sm-4">Slug</dt>
                <dd class="col-sm-8">{{ $category->slug }}</dd>

                <dt class="col-sm-4">عدد المنتجات</dt>
                <dd class="col-sm-8">{{ $category->products_count ?? ($category->products ? $category->products->count() : 0) }}</dd>

                <dt class="col-sm-4">تاريخ الإنشاء</dt>
                <dd class="col-sm-8">{{ $category->created_at?->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-4">آخر تحديث</dt>
                <dd class="col-sm-8">{{ $category->updated_at?->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
@endsection
