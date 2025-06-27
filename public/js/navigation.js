/**
 * Venomfank - Navigation Module
 * Handles header, navigation and menu functionality
 */

// Initialize navigation components
function initNavigation() {
    initMobileMenu();
    initStickyHeader();
}

// Mobile menu functionality
function initMobileMenu() {
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    
    mobileMenuButton?.addEventListener('click', function() {
        mobileMenu.classList.toggle('hidden');
    });
}

// Sticky header functionality
function initStickyHeader() {
    const header = document.getElementById('main-header');
    const categoryFilter = document.getElementById('category-filter');
    const headerHeight = header.offsetHeight;
    let lastScrollTop = 0;
    let scrollTimer = null;
    let scrollThreshold = 10;
    let hideThreshold = headerHeight;
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Add shadow and background opacity when scrolling down
        if (scrollTop > scrollThreshold) {
            header.classList.add('header-scrolled');
        } else {
            header.classList.remove('header-scrolled');
        }
        
        // Hide/show header based on scroll direction
        if (scrollTop > lastScrollTop && scrollTop > hideThreshold) {
            // Scrolling down - hide the header
            header.classList.add('header-hidden');
            if (categoryFilter) {
                categoryFilter.classList.add('header-hidden');
            }
        } else if (scrollTop < lastScrollTop) {
            // Scrolling up - show the header
            header.classList.remove('header-hidden');
            if (categoryFilter) {
                categoryFilter.classList.remove('header-hidden');
            }
        }
        
        lastScrollTop = scrollTop;
        
        // Clear previous timer
        if (scrollTimer !== null) {
            clearTimeout(scrollTimer);
        }
        
        // Set a timer to show header after scrolling stops
        scrollTimer = setTimeout(function() {
            // When scrolling stops, show the header again if near the top
            if (scrollTop < hideThreshold) {
                header.classList.remove('header-hidden');
            }
        }, 1500);
    });
}

// Smooth scroll functionality
function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;
            
            e.preventDefault();
            
            const targetElement = document.querySelector(targetId);
            if (targetElement) {
                const headerOffset = document.getElementById('main-header').offsetHeight;
                const elementPosition = targetElement.getBoundingClientRect().top;
                const offsetPosition = elementPosition - headerOffset;
                
                window.scrollBy({
                    top: offsetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });
}

// Export functions
window.VenomfankNavigation = {
    init: function() {
        initNavigation();
        initSmoothScroll();
    }
}; 