    @vite([
    'resources/assets/kfa/js/main.js',
    'resources/assets/kfa/libs/news-bar/newsbar.js',
    'resources/assets/kfa/libs/sliders/sliders.js',
])

<script>
    // ✅ Initialize Swipers============================
    document.addEventListener("DOMContentLoaded", function() {

        // ✅ Initialize Swipers
        const heroSlider = new Swiper(".hero-slider", {
            loop: true,
            speed: 800,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            effect: "fade",
            fadeEffect: {
                crossFade: true,
            },
        });

        // Gallery Thumbnails
        const thumbsSlider = new Swiper(".thumbs-slider", {
            slidesPerView: "auto",
            spaceBetween: 10,
            freeMode: true,
            autoplay: {
                delay: 0,
                disableOnInteraction: false,
            },
            speed: 3000,
            loop: true,
        });
    });

    // ✅ Initialize News Bar============================

  
</script>
