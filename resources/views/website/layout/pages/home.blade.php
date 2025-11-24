@extends('website.layout.main')

@section('title', 'كأنك فيها - متجر الهوديهات الفلسطينية')

@section('content')
    <!-- Hero Section -->
    @include('website.layout.components.hero-section')

    <!-- Categories Grid Section (6 columns in desktop) -->
    <section id="categories" class="container mx-auto px-4 py-10" style="direction: rtl;">
        <div class="mb-6 text-center">
            <h2 class="text-3xl font-bold mb-3" data-ar="تصفح المدن" data-en="Browse Cities">تصفح المدن</h2>
            <p class="text-base" style="color: var(--gray-text)">
                <span data-ar="اختر المدينة لرؤية المنتجات" data-en="Select a city to view products">اختر المدينة لرؤية
                    المنتجات</span>
            </p>
        </div>
        @php
            $categories = \App\Models\Category::with('mainImage')->get();
            $showCount = 12;
            $hasMore = $categories->count() > $showCount;
        @endphp

        @if ($categories->count())
            <div class="mb-6">
                <div id="categoriesGrid"
                     class="w-full mx-auto grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-x-8 gap-y-8 justify-items-center"
                     style="max-width:1000px;">
                    @foreach ($categories as $idx => $category)
                        @php
                            $imageUrl =
                                $category->mainImage && isset($category->mainImage->url)
                                    ? $category->mainImage->url
                                    : asset('assets/img/illustrations/category_placeholder.svg');
                        @endphp
                        <a href="{{ route('search', ['category' => $category->name]) }}"
                           class="flex flex-col items-center group w-full max-w-[150px] hover:bg-[#f3f3f3] rounded-lg px-2 py-4 transition category-card"
                           style="text-decoration:none; color:#23262f;{{ $idx >= $showCount ? ' display:none;' : '' }}">
                            <div
                                class="flex-shrink-0 h-14 w-14 bg-gray-100 rounded-lg border flex items-center justify-center overflow-hidden mb-2">
                                <img src="{{ $imageUrl }}" alt="{{ $category->name }}" class="object-cover h-10 w-10"
                                     loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('assets/img/illustrations/category_placeholder.svg') }}';">
                            </div>
                            <span
                                class="font-medium text-base text-gray-700 group-hover:text-[#C8A74E] truncate text-center"
                                style="font-size:1.08rem;max-width:125px;">{{ $category->name }}</span>
                        </a>
                    @endforeach

                    @if ($hasMore)
                        <button id="showMoreCategoriesBtn"
                                class="flex flex-col items-center w-full max-w-[150px] bg-gray-200 hover:bg-gray-300 transition rounded-lg px-2 py-4 font-medium text-base text-gray-700 justify-center"
                                style="border: none; cursor: pointer; min-height:92px;">
                            <span data-ar="عرض المزيد" data-en="Show more">عرض المزيد</span>
                        </button>
                        <button id="showLessCategoriesBtn"
                                class="flex flex-col items-center w-full max-w-[150px] bg-gray-200 hover:bg-gray-300 transition rounded-lg px-2 py-4 font-medium text-base text-gray-700 justify-center"
                                style="border: none; cursor: pointer; min-height:92px; display:none;">
                            <span data-ar="عرض أقل" data-en="Show less">عرض أقل</span>
                        </button>
                    @endif
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const showMoreBtn = document.getElementById('showMoreCategoriesBtn');
                    const showLessBtn = document.getElementById('showLessCategoriesBtn');
                    const showCount = {{ $showCount }};
                    if (showMoreBtn && showLessBtn) {
                        showMoreBtn.addEventListener('click', function () {
                            const cards = document.querySelectorAll('#categoriesGrid .category-card');
                            cards.forEach(function(el, idx) {
                                if (el.style.display === 'none' || getComputedStyle(el).display === 'none') {
                                    el.style.display = '';
                                }
                            });
                            showMoreBtn.style.display = 'none';
                            showLessBtn.style.display = '';
                        });
                        showLessBtn.addEventListener('click', function () {
                            const cards = document.querySelectorAll('#categoriesGrid .category-card');
                            cards.forEach(function(el, idx) {
                                if (idx >= showCount) {
                                    el.style.display = 'none';
                                }
                            });
                            showLessBtn.style.display = 'none';
                            showMoreBtn.style.display = '';
                            // Scroll to top of categories grid
                            const grid = document.getElementById('categoriesGrid');
                            if (grid) {
                                grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                            }
                        });
                    }
                });
            </script>
        @else
            <div class="text-center text-muted py-5" data-ar="لا يوجد تصنيفات لعرضها" data-en="No categories to display">
                لا يوجد تصنيفات لعرضها
            </div>
        @endif
    </section>

    <!-- Cities & Products Section -->
    <section id="cities" class="container mx-auto px-4 py-12">
        <div id="citiesContainer">
            <!-- Cities and products will be loaded dynamically via JavaScript -->
            <div class="text-center py-12" style="color: var(--gray-text)">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2"
                    style="border-color: var(--primary-yellow); border-top-color: transparent;"></div>
                <p class="mt-2">
                    <span data-ar="جاري التحميل..." data-en="Loading...">جاري التحميل...</span>
                </p>
            </div>
        </div>
    </section>

    <!-- Shopping Cart Modal -->
    @include('website.layout.components.cart-modal')
@endsection
