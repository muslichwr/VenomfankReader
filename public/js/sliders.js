/**
 * Venomfank - Sliders Module
 * Handles all slider/carousel functionality
 */

// Initialize all sliders
function initSliders() {
    initFeaturedSlider();
    initTrendingSlider();
}

// Initialize Featured Series Slider
function initFeaturedSlider() {
    const featuredSwiper = new Swiper('.featured-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 6000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.featured-next',
            prevEl: '.featured-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 30
            },
            1280: {
                slidesPerView: 5,
                spaceBetween: 30
            }
        }
    });
}

// Initialize Trending Manga Slider
function initTrendingSlider() {
    const trendingSwiper = new Swiper('.trending-swiper', {
        slidesPerView: 1,
        spaceBetween: 20,
        loop: true,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.trending-next',
            prevEl: '.trending-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 4,
                spaceBetween: 30
            },
            1280: {
                slidesPerView: 5,
                spaceBetween: 30
            }
        }
    });
}

// Export functions
window.VenomfankSliders = {
    init: initSliders
}; 