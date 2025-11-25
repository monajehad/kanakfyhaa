    <!-- Categories Grid Section (centered) -->
    <section id="categories" class="w-full px-4 py-10 mr-[5%]" style="direction: rtl;">

        @php
            $categories = \App\Models\Category::with('mainImage')->get();
            $showCount = 12;
            $hasMore = $categories->count() > $showCount;
        @endphp

        @if ($categories->count())
            <div class="mb-6 w-full flex justify-center">
                <div id="categoriesGrid"
                    class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-x-8 gap-y-8"
                    style="width: 100%; max-width: 900px; place-items: center;">
                    @foreach ($categories as $idx => $category)
                        @php
                            $imageUrl =
                                $category->mainImage && isset($category->mainImage->url)
                                    ? $category->mainImage->url
                                    : asset('assets/img/illustrations/category_placeholder.svg');
                        @endphp
                        <button onclick="goToSearchWithCategory({{ $category->id }}, '{{ addslashes($category->name) }}')"
                            class="flex flex-col items-center group w-full max-w-[150px] hover:bg-[#f3f3f3] rounded-lg px-2 py-4 transition category-card"
                            style="text-decoration:none; color:#23262f; border:none; background:transparent; cursor:pointer;{{ $idx >= $showCount ? ' display:none;' : '' }}"
                            title="{{ $category->name }}">
                            <div
                                class="flex-shrink-0 h-14 w-14 bg-gray-100 rounded-lg border flex items-center justify-center overflow-hidden mb-2">
                                <img src="{{ $imageUrl }}" alt="{{ $category->name }}"
                                    class="object-cover h-10 w-10" loading="lazy"
                                    onerror="this.onerror=null;this.src='{{ asset('assets/img/illustrations/category_placeholder.svg') }}';">
                            </div>
                            <span
                                class="font-medium text-base text-gray-700 group-hover:text-[#C8D400] truncate text-center transition-colors"
                                style="font-size:1.08rem;max-width:125px;">{{ $category->name }}</span>
                        </button>
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
                function goToSearchWithCategory(categoryId, categoryName) {
                    // Navigate to search page with category filter
                    window.location.href = `/search?category=${categoryId}`;
                }

                document.addEventListener('DOMContentLoaded', function() {
                    const showMoreBtn = document.getElementById('showMoreCategoriesBtn');
                    const showLessBtn = document.getElementById('showLessCategoriesBtn');
                    const showCount = {{ $showCount }};
                    if (showMoreBtn && showLessBtn) {
                        showMoreBtn.addEventListener('click', function() {
                            const cards = document.querySelectorAll('#categoriesGrid .category-card');
                            cards.forEach(function(el, idx) {
                                if (el.style.display === 'none' || getComputedStyle(el).display ===
                                    'none') {
                                    el.style.display = '';
                                }
                            });
                            showMoreBtn.style.display = 'none';
                            showLessBtn.style.display = '';
                        });
                        showLessBtn.addEventListener('click', function() {
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
                                grid.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'start'
                                });
                            }
                        });
                    }
                });
            </script>
        @else
            <div class="text-center text-muted py-5" data-ar="لا يوجد تصنيفات لعرضها"
                data-en="No categories to display">
                لا يوجد تصنيفات لعرضها
            </div>
        @endif
    </section>
