// Initialize all components when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize animations
    initAnimations();
    
    // Initialize sliders
    initSliders();
    
    // Initialize cookie consent
    initCookieConsent();
    
    // Initialize mobile menu
    initMobileMenu();
    
    // Initialize smooth scroll
    initSmoothScroll();
    
    // Initialize category filters
    initCategoryFilters();
    
    // Initialize sticky header
    initStickyHeader();
});

// Animation initialization
function initAnimations() {
    AOS.init({
        duration: 800,
        once: true,
    });
}

// Slider initialization
function initSliders() {
    // Initialize Featured Series Swiper
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

    // Initialize Trending Manga Swiper
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

// Cookie consent initialization
function initCookieConsent() {
    document.getElementById('accept-cookies')?.addEventListener('click', function() {
        document.getElementById('cookie-banner').style.display = 'none';
        // Here you would set a cookie to remember this choice
        localStorage.setItem('cookieConsent', 'true');
    });

    // Check if user already accepted cookies
    if (localStorage.getItem('cookieConsent') === 'true') {
        const cookieBanner = document.getElementById('cookie-banner');
        if (cookieBanner) {
            cookieBanner.style.display = 'none';
        }
    }
}

// Mobile menu initialization
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton?.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
}

// Smooth scroll initialization
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href'))?.scrollIntoView({
                behavior: 'smooth'
            });
        });
    });
}

// Category filters initialization
function initCategoryFilters() {
    document.querySelectorAll('.category-pill').forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            // Remove active class from all pills
            document.querySelectorAll('.category-pill').forEach(p => {
                p.classList.remove('bg-accent-500');
                p.classList.remove('text-white');
            });
            // Add active class to clicked pill
            this.classList.add('bg-accent-500');
            this.classList.add('text-white');
            // Here you would filter content based on category
        });
    });
}

// Sticky header functionality
function initStickyHeader() {
    const header = document.getElementById('main-header');
    const categoryFilter = document.getElementById('category-filter');
    const headerHeight = header.offsetHeight;
    let lastScrollTop = 0;
    let scrollTimer = null;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add shadow and background opacity when scrolling down
        if (scrollTop > 10) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Hide/show header based on scroll direction
        if (scrollTop > lastScrollTop && scrollTop > headerHeight) {
            // Scrolling down - hide the header
            header.classList.add('header-hidden');
        } else {
            // Scrolling up - show the header
            header.classList.remove('header-hidden');
        }
        
        lastScrollTop = scrollTop;
        
        // Clear previous timer
        if (scrollTimer !== null) {
            clearTimeout(scrollTimer);
        }
        
        // Set a timer to show header after scrolling stops
        scrollTimer = setTimeout(function() {
            // When scrolling stops, show the header again
            if (scrollTop > 0) {
                header.classList.remove('header-hidden');
            }
        }, 1500);
    });
} 