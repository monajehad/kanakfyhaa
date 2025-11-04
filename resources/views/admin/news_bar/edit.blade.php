@extends('layouts/layoutMaster')

@section('title', 'تعديل إعدادات شريط الأخبار')

@section('content')
<div class="card mt-3">
    <div class="card-header">
        <h4 class="mb-0">تعديل إعدادات شريط الأخبار</h4>
    </div>
    <div class="card-body">
        <form id="newsBarForm" autocomplete="off">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label" for="speed">السرعة <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" id="speed" name="speed" required value="{{ old('speed', $newsBar->speed) }}">
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="direction">الاتجاه <span class="text-danger">*</span></label>
                    <select class="form-select" id="direction" name="direction" required>
                        <option value="rtl" {{ old('direction', $newsBar->direction) == 'rtl' ? 'selected' : '' }}>من اليمين لليسار</option>
                        <option value="ltr" {{ old('direction', $newsBar->direction) == 'ltr' ? 'selected' : '' }}>من اليسار لليمين</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="pause_on_hover">التوقف عند المرور</label>
                    <select class="form-select" id="pause_on_hover" name="pause_on_hover">
                        <option value="1" {{ old('pause_on_hover', $newsBar->pause_on_hover) ? 'selected' : '' }}>نعم</option>
                        <option value="0" {{ !old('pause_on_hover', $newsBar->pause_on_hover) ? 'selected' : '' }}>لا</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="theme">النُسق (Theme)</label>
                    <select class="form-select" id="theme" name="theme">
                        <option value="dark" {{ old('theme', $newsBar->theme) == 'dark' ? 'selected' : '' }}>داكن</option>
                        <option value="light" {{ old('theme', $newsBar->theme) == 'light' ? 'selected' : '' }}>فاتح</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label" for="item_space">المسافة بين العناصر (بالبكسل) <span class="text-danger">*</span></label>
                    <input class="form-control" type="number" min="0" id="item_space" name="item_space" required value="{{ old('item_space', $newsBar->item_space) }}">
                </div>
            </div>
            <hr class="my-4">

            <div id="newsBarItemsSection">
                <h5 class="mb-3">العناصر في شريط الأخبار</h5>
                <div id="barItemsList">
                    @forelse($newsBar->items->sortBy('order') as $idx => $item)
                        <div class="row g-2 align-items-end bb news-bar-item-row" data-index="{{ $idx }}" data-id="{{ $item->id }}">
                            <input type="hidden" class="item-id" name="items[{{ $idx }}][id]" value="{{ $item->id }}">
                            <div class="col-md-6">
                                <label class="form-label">النص <span class="text-danger">*</span></label>
                                <input type="text" class="form-control item-text" name="items[{{ $idx }}][text]" value="{{ $item->text }}" required>
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">ترتيب العرض</label>
                                <input type="number" class="form-control item-order" name="items[{{ $idx }}][order]" min="0" value="{{ $item->order }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">مفعل؟</label>
                                <select class="form-select item-active" name="items[{{ $idx }}][active]">
                                    <option value="1" {{ $item->active ? 'selected' : '' }}>نعم</option>
                                    <option value="0" {{ !$item->active ? 'selected' : '' }}>لا</option>
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger remove-bar-item" aria-label="حذف العنصر">
                                    حذف
                                </button>
                            </div>
                        </div>
                    @empty
                    @endforelse
                </div>
                <button type="button" class="btn btn-success mt-3" id="addBarItemBtn">إضافة عنصر جديد</button>
            </div>

            <button id="submitBtn" class="btn btn-primary mt-4" type="submit">تحديث شريط الأخبار</button>
        </form>
    </div>
</div>

{{-- Bar item row template (hidden) --}}
<template id="barItemRowTemplate">
    <div class="row g-2 align-items-end bb news-bar-item-row" data-index="__INDEX__">
        <input type="hidden" class="item-id" name="items[__INDEX__][id]" value="">
        <div class="col-md-6">
            <label class="form-label">النص <span class="text-danger">*</span></label>
            <input type="text" class="form-control item-text" name="items[__INDEX__][text]" required>
        </div>
        <div class="col-md-2">
            <label class="form-label">ترتيب العرض</label>
            <input type="number" class="form-control item-order" name="items[__INDEX__][order]" min="0" value="0">
        </div>
        <div class="col-md-2">
            <label class="form-label">مفعل؟</label>
            <select class="form-select item-active" name="items[__INDEX__][active]">
                <option value="1" selected>نعم</option>
                <option value="0">لا</option>
            </select>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="button" class="btn btn-danger remove-bar-item" aria-label="حذف العنصر">
                حذف
            </button>
        </div>
    </div>
</template>

{{-- Plugins --}}
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
{{-- Toastr for notifications --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('newsBarForm');
    const submitBtn = document.getElementById('submitBtn');
    const barItemsList = document.getElementById('barItemsList');
    const addBarItemBtn = document.getElementById('addBarItemBtn');
    const barItemRowTemplate = document.getElementById('barItemRowTemplate').innerHTML;

    function getCurrentBarItemCount() {
        return barItemsList.querySelectorAll('.news-bar-item-row').length;
    }

    // Add new item
    addBarItemBtn.addEventListener('click', function() {
        let idx = getCurrentBarItemCount();
        let html = barItemRowTemplate.replace(/__INDEX__/g, idx);
        let tempDiv = document.createElement('div');
        tempDiv.innerHTML = html.trim();
        barItemsList.appendChild(tempDiv.firstChild);
    });

    // Remove item
    barItemsList.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-bar-item')) {
            let row = e.target.closest('.news-bar-item-row');
            if (row) {
                row.remove();
            }
        }
    });

    // Handle dynamic name attributes & sort by index before submission
    function syncItemRowNames() {
        const rows = barItemsList.querySelectorAll('.news-bar-item-row');
        rows.forEach((row, i) => {
            row.dataset.index = i;
            row.querySelectorAll('input, select').forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/items\[\d+\]/, `items[${i}]`);
                }
            });
        });
    }

    // Setup toastr options
    toastr.options = {
        "closeButton": true,
        "positionClass": "toast-top-left",
        "timeOut": "3500"
    };

    // Before submit, gather bar items to array
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        submitBtn.disabled = true;

        // Main fields
        const speed = form.speed.value.trim();
        const direction = form.direction.value;
        const pause_on_hover = form.pause_on_hover.value;
        const theme = form.theme.value;
        const item_space = form.item_space.value.trim();

        // Validate required fields
        if (!speed || !direction || !item_space) {
            toastr.error('الرجاء تعبئة جميع الحقول المطلوبة.', 'تحقق من البيانات');
            submitBtn.disabled = false;
            return;
        }

        // Validate bar items
        syncItemRowNames();
        const itemRows = barItemsList.querySelectorAll('.news-bar-item-row');
        let items = [];
        let hasItemError = false;
        itemRows.forEach((row, idx) => {
            const id = row.querySelector('.item-id').value || null;
            const text = row.querySelector('.item-text').value.trim();
            const order = row.querySelector('.item-order').value || 0;
            const active = row.querySelector('.item-active').value;

            if (!text) {
                hasItemError = true;
                row.querySelector('.item-text').classList.add('is-invalid');
            } else {
                row.querySelector('.item-text').classList.remove('is-invalid');
            }

            items.push({
                id,
                text,
                order,
                active
            });
        });

        if (hasItemError) {
            toastr.error('يجب إدخال نص لكل عنصر في الشريط.', 'تحقق من بيانات العناصر');
            submitBtn.disabled = false;
            return;
        }

        submitBtn.innerText = 'يتم التحديث...';

        // Use classic Object for posting (FormData not required)
        const payload = {
            speed: speed,
            direction: direction,
            pause_on_hover: pause_on_hover,
            theme: theme,
            item_space: item_space,
            items: items,
            _method: 'PUT'
        };

        axios.post('{{ route('admin.news_bar.update', $newsBar->id) }}', payload)
        .then(response => {
            if (response.data.success) {
                toastr.success(response.data.message || "تم تحديث الإعدادات بنجاح.", "نجاح");
                // Do NOT reload the page. Optionally, update some UI here if you need.
                // Optionally, you could update the UI with latest data here.
            } else {
                toastr.error(response.data.message || "حدث خطأ أثناء عملية التحديث.", "فشل التحديث");
            }
        })
        .catch(error => {
            let msg = 'حدث خطأ غير متوقع.';
            if (error.response?.status === 422 && error.response?.data?.errors) {
                msg = Object.values(error.response.data.errors).flat().join("<br>");
            } else if (error.response?.data?.message) {
                msg = error.response.data.message;
            }
            toastr.error(msg, "فشل التحديث");
        })
        .finally(() => {
            submitBtn.disabled = false;
            submitBtn.innerText = 'تحديث شريط الأخبار';
        });
    });
});
</script>

@endsection
