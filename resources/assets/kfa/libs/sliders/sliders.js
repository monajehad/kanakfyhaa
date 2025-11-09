// ============================
// SWIPER SLIDER INITIALIZATION - FIXED VERSION
// ============================

// انتظار تحميل Swiper بالكامل
function initializeSliders() {
  if (typeof Swiper === 'undefined') {
    console.error('Swiper library not loaded. Retrying...');
    setTimeout(initializeSliders, 100);
    return;
  }

  // ✅ Initialize Hero Slider
  const heroSliderElement = document.querySelector(".hero-slider");

  if (heroSliderElement) {
    const heroSlider = new Swiper(".hero-slider", {
      // Basic Settings
      loop: true,
      speed: 800,
      spaceBetween: 0,
      effect: "fade",

      // Fade Effect
      fadeEffect: {
        crossFade: true
      },

      // Autoplay
      autoplay: {
        delay: 5000,
        disableOnInteraction: false,
        pauseOnMouseEnter: true
      },

      // Navigation Arrows
      navigation: {
        nextEl: ".hero-section .swiper-button-next",
        prevEl: ".hero-section .swiper-button-prev",
      },

      // Pagination
      pagination: {
        el: ".hero-section .swiper-pagination",
        clickable: true,
        dynamicBullets: true,
      },

      // Keyboard Control
      keyboard: {
        enabled: true,
        onlyInViewport: true,
      },

      // Grab Cursor for drag
      grabCursor: true,

      // Touch Settings for better mobile experience
      touchRatio: 1,
      touchAngle: 45,
      simulateTouch: true,

      // Accessibility
      a11y: {
        prevSlideMessage: 'الشريحة السابقة',
        nextSlideMessage: 'الشريحة التالية',
      },

      on: {
        init: function () {
          console.log('Hero Slider initialized successfully');
        },
        slideChange: function () {
          // Fix: SKIP animating when active slide is a duplicated "empty" slide (loop fix)
          // Swiper clones slides for looping, which causes empty/blank slides
          // We'll do nothing if there's no .hero-content in the active slide
          const activeSlide = this.slides[this.activeIndex];
          const content = activeSlide?.querySelector('.hero-content');
          if (!content) return; // Do nothing if empty slide!
          content.style.animation = 'none';
          setTimeout(() => {
            content.style.animation = 'fadeUp 1s ease forwards';
          }, 10);
        }
      }
    });

    // ✅ Pause autoplay on hover
    const heroSection = document.querySelector('.hero-section');
    if (heroSection) {
      heroSection.addEventListener('mouseenter', () => {
        heroSlider.autoplay.stop();
      });

      heroSection.addEventListener('mouseleave', () => {
        heroSlider.autoplay.start();
      });
    }
  }

  // ✅ Initialize Gallery Thumbnails Slider (if exists)
  const thumbsSliderElement = document.querySelector(".thumbs-slider");
  if (thumbsSliderElement) {
    const thumbsSlider = new Swiper(".thumbs-slider", {
      slidesPerView: "auto",
      spaceBetween: 10,
      freeMode: true,
      loop: true,
      speed: 3000,
      autoplay: {
        delay: 0,
        disableOnInteraction: false,
      },
      grabCursor: true,
      breakpoints: {
        320: {
          slidesPerView: 3,
          spaceBetween: 8
        },
        640: {
          slidesPerView: 4,
          spaceBetween: 10
        },
        1024: {
          slidesPerView: 6,
          spaceBetween: 12
        }
      },
      on: {
        init: function () {
          console.log('Thumbs Slider initialized successfully');
        }
      }
    });
  }
}

// ✅ تهيئة السلايدر بعد تحميل DOM و Swiper
if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initializeSliders);
} else {
  initializeSliders();
}

// ✅ Fallback: تحميل إضافي بعد window.load
window.addEventListener('load', function () {
  if (typeof Swiper === 'undefined') {
    console.error('Swiper still not loaded after window load');
  }
});