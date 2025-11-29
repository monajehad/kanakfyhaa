@php
    $sliders = \App\Models\Slider::active()->get();
@endphp

@if($sliders->count() > 0)
<section class="hero-section-wrapper" data-slides-count="{{ $sliders->count() }}">
    <div class="swiper hero-slider-main">
        <div class="swiper-wrapper">
            @foreach($sliders as $slider)
                <div class="swiper-slide hero-slide-item"
                    @if($slider->mainImage && $slider->mainImage->url)
                        style="background-image: url('{{ $slider->mainImage->url }}');"
                    @else
                        style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);"
                    @endif
                >
                    <div class="hero-overlay"></div>
                    <div class="hero-content-wrapper">
                        <div class="hero-text">
                            <h1 class="hero-title">{{ $slider->title }}</h1>
                            @if($slider->description)
                                <p class="hero-description">{{ $slider->description }}</p>
                            @endif
                            @if($slider->link)
                                <a href="{{ $slider->link }}" class="hero-btn">
                                    اكتشف المزيد
                                </a>
                            @else
                                <button onclick="document.getElementById('cities') ? document.getElementById('cities').scrollIntoView({behavior: 'smooth'}) : null" class="hero-btn">
                                    اكتشف المجموعة
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Navigation -->
        <button class="hero-nav-btn hero-nav-prev" aria-label="Previous">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <button class="hero-nav-btn hero-nav-next" aria-label="Next">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <polyline points="9 18 15 12 9 6"></polyline>
            </svg>
        </button>

        <!-- Pagination -->
        <div class="hero-pagination"></div>
    </div>
</section>

<style>
    /* Layout hardening to avoid horizontal whitespace on mobile */
    html, body {
        width: 100%;
        overflow-x: hidden;
    }

    .hero-section-wrapper {
        position: relative;
        width: 100%;
        height: 90vh;
        max-height: 900px;
        overflow: hidden;
        background: #000;
    }

    .hero-slider-main {
        width: 100%;
        height: 100%;
    }

    .hero-slide-item {
        position: relative;
        width: 100%;
        height: 100%;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: flex-start;
        padding: 0 5%;
    }

    .hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(90deg, rgba(0, 0, 0, 0.6) 0%, rgba(0, 0, 0, 0.3) 50%, transparent 100%);
        z-index: 1;
    }

    .hero-content-wrapper {
        position: relative;
        z-index: 2;
        max-width: 600px;
    }

    .hero-text {
        animation: slideInLeft 0.8s ease-out forwards;
    }

    .hero-title {
        font-size: clamp(2rem, 6vw, 4rem);
        font-weight: 800;
        color: #ffffff;
        margin-bottom: 1.5rem;
        line-height: 1.1;
        font-family: 'Cairo', sans-serif;
        letter-spacing: -1px;
    }

    .hero-description {
        font-size: clamp(1rem, 2.5vw, 1.25rem);
        color: rgba(255, 255, 255, 0.9);
        margin-bottom: 2rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .hero-btn {
        display: inline-block;
        background: #c7db09;
        color: #000;
        padding: 1rem 2.5rem;
        border-radius: 8px;
        font-weight: 700;
        text-decoration: none;
        border: none;
        cursor: pointer;
        font-size: 1.1rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 15px rgba(199, 219, 9, 0.3);
        font-family: 'Cairo', sans-serif;
    }

    .hero-btn:hover {
        background: #b5c90a;
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(199, 219, 9, 0.4);
    }

    .hero-btn:active {
        transform: translateY(-1px);
    }

    /* Navigation Buttons */
    .hero-nav-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 56px;
        height: 56px;
        background: rgba(255, 255, 255, 0.15);
        backdrop-filter: blur(12px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        transition: all 0.3s ease;
        padding: 0;
    }

    .hero-nav-btn:hover {
        background: #c7db09;
        color: #000;
        border-color: #c7db09;
        transform: translateY(-50%) scale(1.1);
    }

    .hero-nav-btn:active {
        transform: translateY(-50%) scale(0.95);
    }

    .hero-nav-prev {
        left: 2rem;
    }

    .hero-nav-next {
        right: 2rem;
    }

    .hero-nav-btn svg {
        stroke-width: 2.5;
    }

    /* Pagination */
    .hero-pagination {
        position: absolute;
        bottom: 2rem;
        left: 50%;
        transform: translateX(-50%);
        display: flex;
        gap: 12px;
        z-index: 10;
    }

    .hero-pagination-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.4);
        cursor: pointer;
        transition: all 0.3s ease;
        border: 2px solid transparent;
    }

    .hero-pagination-dot:hover {
        background: rgba(255, 255, 255, 0.6);
    }

    .hero-pagination-dot.active {
        background: #c7db09;
        width: 32px;
        border-radius: 8px;
    }

    /* Animations */
    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-50px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .hero-section-wrapper {
            height: 70vh;
        }

        .hero-title {
            font-size: clamp(1.8rem, 5vw, 3rem);
        }

        .hero-nav-btn {
            width: 48px;
            height: 48px;
        }
    }

    @media (max-width: 768px) {
        .hero-section-wrapper {
            height: 60vh;
        }

        .hero-slide-item {
            justify-content: center;
            text-align: center;
            /* Remove side padding to prevent white gutters */
            padding: 0;
            margin: 0;
        }

        .hero-content-wrapper {
            max-width: 92%;
            margin: 0 auto;
        }

        .hero-overlay {
            background: linear-gradient(180deg, rgba(0, 0, 0, 0.3) 0%, rgba(0, 0, 0, 0.5) 50%, rgba(0, 0, 0, 0.7) 100%);
        }

        .hero-title {
            font-size: clamp(1.5rem, 4vw, 2.5rem);
        }

        .hero-description {
            font-size: clamp(0.9rem, 2vw, 1.1rem);
        }

        .hero-nav-btn {
            width: 44px;
            height: 44px;
        }

        .hero-nav-prev {
            left: 1rem;
        }

        .hero-nav-next {
            right: 1rem;
        }
    }

    @media (max-width: 480px) {
        .hero-section-wrapper {
            height: 52vh;
        }

        .hero-nav-btn {
            display: none;
        }

        .hero-pagination {
            bottom: 1rem;
            gap: 8px;
        }

        .hero-pagination-dot {
            width: 10px;
            height: 10px;
        }

        .hero-pagination-dot.active {
            width: 28px;
        }

        .hero-btn {
            padding: 0.8rem 2rem;
            font-size: 1rem;
        }

        /* Make pagination dots easier to tap */
        .hero-pagination-dot {
            width: 12px;
            height: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if Swiper is available
        if (typeof Swiper === 'undefined') {
            console.warn('Swiper library not loaded. Slider may not work.');
            return;
        }

        // Ensure elements exist
        const sliderContainer = document.querySelector('.hero-slider-main');
        const navPrev = document.querySelector('.hero-nav-prev');
        const navNext = document.querySelector('.hero-nav-next');
        
        if (!sliderContainer) {
            console.warn('Slider container not found.');
            return;
        }

        const heroSliderConfig = {
            loop: true,
            speed: 600,
            direction: 'horizontal',
            slidesPerView: 1,
            spaceBetween: 0,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: false
            },
            keyboard: {
                enabled: true,
                onlyInViewport: true
            },
            grabCursor: true,
            simulateTouch: true,
            touchRatio: 1,
            allowTouchMove: true,
            on: {
                slideChange: function() {
                    updatePagination(this);
                    animateContent(this);
                },
                init: function() {
                    updatePagination(this);
                    animateContent(this);
                }
            }
        };

        const heroSlider = new Swiper('.hero-slider-main', heroSliderConfig);

        // Custom navigation with null checks
        if (navPrev) {
            navPrev.addEventListener('click', function(e) {
                e.preventDefault();
                heroSlider.slidePrev();
            });
        }
        
        if (navNext) {
            navNext.addEventListener('click', function(e) {
                e.preventDefault();
                heroSlider.slideNext();
            });
        }

        // Update pagination
        function updatePagination(swiper) {
            const paginationEl = document.querySelector('.hero-pagination');
            const sectionEl = document.querySelector('.hero-section-wrapper');
            if (!paginationEl) return;

            paginationEl.innerHTML = '';
            
            // Get the actual number of original slides from data attribute
            const totalSlides = parseInt(sectionEl?.dataset.slidesCount) || swiper.loopedSlides || 6;
            
            for (let i = 0; i < totalSlides; i++) {
                const dot = document.createElement('div');
                dot.className = 'hero-pagination-dot';
                
                // Use realIndex to get the actual slide index (ignoring loop duplicates)
                const realIndex = swiper.realIndex;
                if (i === realIndex) {
                    dot.classList.add('active');
                }
                
                dot.addEventListener('click', function() {
                    // Use slideToLoop to correctly navigate when loop is enabled
                    if (typeof swiper.slideToLoop === 'function') {
                        swiper.slideToLoop(i);
                    } else {
                        swiper.slideTo(i);
                    }
                });
                
                paginationEl.appendChild(dot);
            }
        }

        // Animate content
        function animateContent(swiper) {
            const text = swiper.slides[swiper.activeIndex]?.querySelector('.hero-text');
            if (text) {
                text.style.animation = 'none';
                setTimeout(() => {
                    text.style.animation = 'slideInLeft 0.8s ease-out forwards';
                }, 10);
            }
        }
    });
</script>
@else
<!-- Fallback -->
<section class="hero-section-wrapper">
    <div class="hero-slide-item" style="background: linear-gradient(135deg, #c7db09 0%, #111 100%);">
        <div class="hero-overlay"></div>
        <div class="hero-content-wrapper">
            <div class="hero-text">
                <h1 class="hero-title">كأنك فيها</h1>
                <p class="hero-description">هوديهات فلسطينية أصيلة - عبر عن حبك لمدينتك</p>
                <button onclick="document.getElementById('cities') ? document.getElementById('cities').scrollIntoView({behavior: 'smooth'}) : null" class="hero-btn">
                    اكتشف المجموعة
                </button>
            </div>
        </div>
    </div>
</section>

<style>
    .hero-overlay {
        display: none;
    }
</style>
@endif
