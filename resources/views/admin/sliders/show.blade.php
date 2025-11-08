@php
    $configData = Helper::appClasses();
@endphp
@extends('layouts/layoutMaster')

@section('title', 'تفاصيل السلايدر')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4 flex-row-reverse">
        <h4 class="mb-0">تفاصيل السلايدر</h4>
        <a href="{{ route('admin.content.sliders.edit', $slider->id) }}" class="btn btn-warning" title="تعديل السلايدر">
            <i class="ri ri-pencil-line me-1"></i> تعديل
        </a>
    </div>

    <div class="card shadow border-0">
        <div class="card-body">
            <dl class="row mb-0 gy-3">
                <dt class="col-sm-4">ID السلايدر</dt>
                <dd class="col-sm-8">{{ $slider->id }}</dd>

                <dt class="col-sm-4">عنوان السلايدر</dt>
                <dd class="col-sm-8 fw-bold">{{ $slider->title }}</dd>

                <dt class="col-sm-4">العنوان الفرعي</dt>
                <dd class="col-sm-8">{{ $slider->subtitle }}</dd>

                <dt class="col-sm-4">الوصف</dt>
                <dd class="col-sm-8">{{ $slider->description }}</dd>

                <dt class="col-sm-4">صورة السلايدر</dt>
                <dd class="col-sm-8">
                    @if($slider->image)
                        <img src="{{ asset('storage/' . $slider->image) }}" alt="صورة السلايدر" style="max-width: 180px;">
                    @else
                        <span class="text-muted">لا يوجد صورة</span>
                    @endif
                </dd>

                <dt class="col-sm-4">نص الزر</dt>
                <dd class="col-sm-8">{{ $slider->button_text }}</dd>

                <dt class="col-sm-4">رابط الزر</dt>
                <dd class="col-sm-8">{{ $slider->button_url }}</dd>

                <dt class="col-sm-4">الترتيب</dt>
                <dd class="col-sm-8">{{ $slider->order }}</dd>

                <dt class="col-sm-4">الحالة</dt>
                <dd class="col-sm-8">
                    @if($slider->active)
                        <span class="badge bg-success">نشط (مرئي)</span>
                    @else
                        <span class="badge bg-secondary">غير نشط</span>
                    @endif
                </dd>

                <dt class="col-sm-4">تاريخ الإنشاء</dt>
                <dd class="col-sm-8">{{ $slider->created_at?->format('Y-m-d H:i') }}</dd>

                <dt class="col-sm-4">آخر تحديث</dt>
                <dd class="col-sm-8">{{ $slider->updated_at?->format('Y-m-d H:i') }}</dd>
            </dl>
        </div>
    </div>
@endsection
