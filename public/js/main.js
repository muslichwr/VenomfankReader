/**
 * Venomfank - Main JavaScript
 * Entry point for all JavaScript functionality
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    initVenomfank();
});

function initVenomfank() {
    // Initialize utilities first (or provide fallbacks)
    if (window.VenomfankUtils) {
        window.VenomfankUtils.init();
    } else {
        // Fallback for animations if module not loaded
        if (typeof AOS !== 'undefined') {
            AOS.init({
                duration: 800,
                once: true,
            });
        }
        
        // Fallback for cookie consent
        initCookieConsentFallback();
    }
    
    // Initialize navigation components
    if (window.VenomfankNavigation) {
        window.VenomfankNavigation.init();
    } else {
        // Fallback for sticky header
        initStickyHeaderFallback();
        
        // Fallback for mobile menu
        initMobileMenuFallback();
    }
    
    // Initialize sliders
    if (window.VenomfankSliders) {
        window.VenomfankSliders.init();
    } else {
        // Fallback for sliders
        initSwipers();
    }
    
    // Initialize filters
    if (window.VenomfankFilters) {
        window.VenomfankFilters.init();
    } else {
        // Fallback for category filters
        initCategoryFiltersFallback();
    }
    
    // Initialize any other modules
    // if (window.VenomfankOtherModule) {
    //     window.VenomfankOtherModule.init();
    // }
    
    console.log('Venomfank initialized successfully');
}

// Fallback functions in case modules aren't loaded

function initCookieConsentFallback() {
    document.getElementById('accept-cookies')?.addEventListener('click', function() {
        document.getElementById('cookie-banner').style.display = 'none';
        localStorage.setItem('cookieConsent', 'true');
    });

    if (localStorage.getItem('cookieConsent') === 'true') {
        const cookieBanner = document.getElementById('cookie-banner');
        if (cookieBanner) {
            cookieBanner.style.display = 'none';
        }
    }
}

function initStickyHeaderFallback() {
    const header = document.getElementById('main-header');
    const categoryFilter = document.getElementById('category-filter');
    
    if (!header) return;
    
    let lastScrollTop = 0;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 10) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            header.classList.add('header-hidden');
        } else {
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
    });
}

function initMobileMenuFallback() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton?.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
}

function initSwipers() {
    // Initialize only if Swiper is available
    if (typeof Swiper === 'undefined') return;
    
    // Featured Series Slider
    if (document.querySelector('.featured-swiper')) {
        const featuredSwiper = new Swiper('.featured-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.featured-next',
                prevEl: '.featured-prev',
            },
            breakpoints: {
                // when window width is >= 640px
                640: {
                    slidesPerView: 2,
                },
                // when window width is >= 768px
                768: {
                    slidesPerView: 3,
                },
                // when window width is >= 1024px
                1024: {
                    slidesPerView: 4,
                },
                // when window width is >= 1280px
                1280: {
                    slidesPerView: 5,
                }
            }
        });
    }
    
    // Trending Series Slider
    if (document.querySelector('.trending-swiper')) {
        const trendingSwiper = new Swiper('.trending-swiper', {
            slidesPerView: 1,
            spaceBetween: 20,
            loop: true,
            autoplay: {
                delay: 6000,
                disableOnInteraction: false,
            },
            navigation: {
                nextEl: '.trending-next',
                prevEl: '.trending-prev',
            },
            breakpoints: {
                640: {
                    slidesPerView: 2,
                },
                768: {
                    slidesPerView: 3,
                },
                1024: {
                    slidesPerView: 4,
                },
                1280: {
                    slidesPerView: 5,
                }
            }
        });
    }
}

function initCategoryFiltersFallback() {
    document.querySelectorAll('.category-pill').forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.category-pill').forEach(p => {
                p.classList.remove('bg-accent-500');
                p.classList.remove('text-white');
            });
            this.classList.add('bg-accent-500');
            this.classList.add('text-white');
        });
    });
}

/**
 * Initialize any additional components
 */
function initComponents() {
    // Lazy load images
    initLazyLoading();
    
    // Initialize coin system tooltips
    initCoinSystem();
    
    // Enable dark mode toggle if applicable
    initDarkMode();
}

/**
 * Initialize lazy loading for images
 */
function initLazyLoading() {
    // Use native lazy loading if available
    document.querySelectorAll('img[loading="lazy"]').forEach(img => {
        // Optionally add a fade in effect when loaded
        img.addEventListener('load', function() {
            this.classList.add('fade-in');
        });
    });
}

/**
 * Initialize coin system tooltips and interactions
 */
function initCoinSystem() {
    // Add tooltip functionality to chapter locks
    document.querySelectorAll('.chapter-lock').forEach(lock => {
        lock.setAttribute('title', `This chapter costs ${lock.textContent} coins to unlock`);
    });
}

/**
 * Initialize dark mode toggle functionality
 */
function initDarkMode() {
    // Venomfank is dark theme by default, but could add a light mode option here
    const darkModeToggle = document.getElementById('dark-mode-toggle');
    if (!darkModeToggle) return;

    darkModeToggle.addEventListener('click', function() {
        document.body.classList.toggle('light-mode');
        localStorage.setItem('theme', document.body.classList.contains('light-mode') ? 'light' : 'dark');
    });
} 