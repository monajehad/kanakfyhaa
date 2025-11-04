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
