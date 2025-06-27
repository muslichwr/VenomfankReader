/**
 * Venomfank - Utils Module
 * Utility functions and general purpose functionality
 */

// Initialize utilities
function initUtils() {
    initAnimations();
    initCookieConsent();
}

// Animation initialization
function initAnimations() {
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
        });
    }
}

// Cookie consent functionality
function initCookieConsent() {
    const cookieBanner = document.getElementById('cookie-banner');
    const acceptButton = document.getElementById('accept-cookies');
    
    if (!cookieBanner || !acceptButton) return;
    
    // Check if user already accepted cookies
    if (localStorage.getItem('cookieConsent') === 'true') {
        cookieBanner.style.display = 'none';
        return;
    }
    
    // Show the cookie banner with animation
    cookieBanner.classList.add('fade-in');
    
    // Handle accept button click
    acceptButton.addEventListener('click', function() {
        // Hide the banner with animation
        cookieBanner.style.opacity = '0';
        
        setTimeout(() => {
            cookieBanner.style.display = 'none';
        }, 300);
        
        // Store consent in localStorage
        localStorage.setItem('cookieConsent', 'true');
        
        // You could also set an actual cookie here if needed
        // document.cookie = "cookieConsent=true; max-age=31536000; path=/";
    });
}

// Get viewport size
function getViewportSize() {
    return {
        width: window.innerWidth || document.documentElement.clientWidth,
        height: window.innerHeight || document.documentElement.clientHeight
    };
}

// Debounce function for performance optimization
function debounce(func, wait) {
    let timeout;
    return function(...args) {
        const context = this;
        clearTimeout(timeout);
        timeout = setTimeout(() => func.apply(context, args), wait);
    };
}

// Export functions
window.VenomfankUtils = {
    init: initUtils,
    getViewportSize: getViewportSize,
    debounce: debounce
};

/**
 * Coin system utilities
 */
// const CoinSystem = {
//     /**
//      * Get user's coin balance
//      * @returns {number} Current coin balance
//      */
//     getBalance() {
//         // In a real app, this would come from a server
//         return parseInt(localStorage.getItem('userCoins') || '250');
//     },
    
//     /**
//      * Update user's coin balance
//      * @param {number} amount - Amount to add (positive) or subtract (negative)
//      * @returns {number} New balance
//      */
//     updateBalance(amount) {
//         const currentBalance = this.getBalance();
//         const newBalance = currentBalance + amount;
        
//         // Don't allow negative balance
//         if (newBalance < 0) return currentBalance;
        
//         // Update storage
//         localStorage.setItem('userCoins', newBalance.toString());
        
//         // Update UI if needed
//         this.updateUI();
        
//         return newBalance;
//     },
    
//     /**
//      * Check if user can afford a purchase
//      * @param {number} cost - Cost in coins
//      * @returns {boolean} True if affordable
//      */
//     canAfford(cost) {
//         return this.getBalance() >= cost;
//     },
    
//     /**
//      * Make a purchase with coins
//      * @param {number} cost - Cost in coins
//      * @returns {boolean} True if purchase successful
//      */
//     purchase(cost) {
//         if (!this.canAfford(cost)) return false;
        
//         this.updateBalance(-cost);
//         return true;
//     },
    
//     /**
//      * Update UI elements showing coin balance
//      */
//     updateUI() {
//         const balance = this.getBalance();
//         document.querySelectorAll('.coin-badge').forEach(badge => {
//             badge.textContent = balance;
//         });
//     },
    
//     /**
//      * Initialize coin system
//      */
//     init() {
//         // Update UI on load
//         this.updateUI();
        
//         // Add event listeners for coin purchases and unlocks
//         document.addEventListener('click', e => {
//             // Check if clicking on a locked chapter
//             if (e.target.closest('.chapter-lock')) {
//                 const chapterElement = e.target.closest('a');
//                 const costElement = chapterElement.querySelector('.chapter-lock');
                
//                 if (costElement) {
//                     const cost = parseInt(costElement.textContent);
//                     this.handleChapterUnlock(chapterElement, cost);
//                 }
//             }
//         });
//     },
    
//     /**
//      * Handle chapter unlock attempt
//      * @param {Element} chapterElement - The chapter element
//      * @param {number} cost - Cost in coins
//      */
//     handleChapterUnlock(chapterElement, cost) {
//         // In a real app, this would show a confirmation dialog
//         if (confirm(`Unlock this chapter for ${cost} coins?`)) {
//             if (this.purchase(cost)) {
//                 // Success - redirect to chapter
//                 window.location.href = chapterElement.href;
//             } else {
//                 // Not enough coins
//                 alert('Not enough coins! Please purchase more coins to unlock this chapter.');
//             }
//         }
//     }
// };

/**
 * Date/time formatting utilities
 */
const DateUtils = {
    /**
     * Format a date as relative time (e.g., "2 hours ago")
     * @param {string|Date} date - Date to format
     * @returns {string} Formatted relative time
     */
    formatRelativeTime(date) {
        const now = new Date();
        const then = new Date(date);
        const diffMs = now - then;
        const diffSecs = Math.floor(diffMs / 1000);
        const diffMins = Math.floor(diffSecs / 60);
        const diffHours = Math.floor(diffMins / 60);
        const diffDays = Math.floor(diffHours / 24);
        const diffMonths = Math.floor(diffDays / 30);
        
        if (diffSecs < 60) return 'just now';
        if (diffMins < 60) return `${diffMins}m ago`;
        if (diffHours < 24) return `${diffHours}h ago`;
        if (diffDays < 30) return `${diffDays}d ago`;
        if (diffMonths < 12) return `${diffMonths}mo ago`;
        
        return `${Math.floor(diffMonths / 12)}y ago`;
    },
    
    /**
     * Format a date in a standard format
     * @param {string|Date} date - Date to format
     * @returns {string} Formatted date
     */
    formatDate(date) {
        return new Date(date).toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }
};

/**
 * General utility functions
 */
const Utils = {
    /**
     * Debounce a function to prevent rapid firing
     * @param {Function} func - Function to debounce
     * @param {number} wait - Wait time in ms
     * @returns {Function} Debounced function
     */
    debounce(func, wait) {
        let timeout;
        return function(...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    },
    
    /**
     * Throttle a function to limit execution rate
     * @param {Function} func - Function to throttle
     * @param {number} limit - Limit in ms
     * @returns {Function} Throttled function
     */
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    /**
     * Get URL parameter value
     * @param {string} name - Parameter name
     * @returns {string|null} Parameter value
     */
    getUrlParam(name) {
        const params = new URLSearchParams(window.location.search);
        return params.get(name);
    }
};

// Initialize modules on DOM content loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize coin system
    // CoinSystem.init();
}); 