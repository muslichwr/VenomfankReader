/**
 * Venomfank - Filters Module
 * Handles category filtering and sorting functionality
 */

// Initialize category filters
function initCategoryFilters() {
    const categoryPills = document.querySelectorAll('.category-pill');
    
    categoryPills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all pills
            categoryPills.forEach(p => {
                p.classList.remove('bg-accent-500');
                p.classList.remove('text-white');
            });
            
            // Add active class to clicked pill
            this.classList.add('bg-accent-500');
            this.classList.add('text-white');
            
            // Get the selected category
            const category = this.textContent.trim();
            
            // Filter content based on category
            filterContentByCategory(category);
        });
    });
}

// Filter content based on selected category
function filterContentByCategory(category) {
    // This function would be implemented to filter the actual content
    // For now, just log the selected category
    console.log('Filtering by category:', category);
    
    // Example implementation:
    // const allItems = document.querySelectorAll('.manga-item');
    // 
    // allItems.forEach(item => {
    //     const itemCategory = item.getAttribute('data-category');
    //     if (category === 'All' || itemCategory === category) {
    //         item.style.display = 'block';
    //     } else {
    //         item.style.display = 'none';
    //     }
    // });
}

// Export functions
window.VenomfankFilters = {
    init: initFilters
};

document.addEventListener('DOMContentLoaded', function() {
    initFilters();
    
    // Set initial sort option based on URL parameter
    const urlParams = new URLSearchParams(window.location.search);
    const sortParam = urlParams.get('sort');
    
    if (sortParam) {
        const sortSelect = document.querySelector('select[name="sort"]');
        if (sortSelect && sortSelect.querySelector(`option[value="${sortParam}"]`)) {
            sortSelect.value = sortParam;
            applySorting(sortParam);
        }
    }
});

/**
 * Initialize all filter functionality
 */
function initFilters() {
    // Initialize type/genre/sort filters
    const typeSelect = document.querySelector('select[name="type"]');
    const genreSelect = document.querySelector('select[name="genre"]');
    const sortSelect = document.querySelector('select[name="sort"]');
    
    // Initialize category pill filters
    initCategoryPills();
    
    // Add event listeners to select dropdowns
    if (typeSelect) {
        typeSelect.addEventListener('change', applyFilters);
    }
    
    if (genreSelect) {
        genreSelect.addEventListener('change', applyFilters);
    }
    
    if (sortSelect) {
        sortSelect.addEventListener('change', applyFilters);
    }
}

/**
 * Initialize category pill functionality
 */
function initCategoryPills() {
    const categoryPills = document.querySelectorAll('.category-pill');
    
    categoryPills.forEach(pill => {
        pill.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Remove active class from all pills
            categoryPills.forEach(p => p.classList.remove('active', 'bg-accent-500', 'text-white'));
            
            // Add active class to clicked pill
            this.classList.add('active', 'bg-accent-500', 'text-white');
            
            // Apply category filter
            const category = this.textContent.trim();
            applyCategoryFilter(category);
        });
    });
}

/**
 * Apply filtering based on selected options
 */
function applyFilters() {
    // Get filter values
    const type = document.querySelector('select[name="type"]')?.value || 'all';
    const genre = document.querySelector('select[name="genre"]')?.value || 'all';
    const sort = document.querySelector('select[name="sort"]')?.value || 'latest';
    
    // Get all series cards
    const seriesCards = document.querySelectorAll('.card');
    
    // Filter cards based on type and genre
    seriesCards.forEach(card => {
        const cardType = card.getAttribute('data-type');
        const cardGenres = card.getAttribute('data-genre')?.split(' ') || [];
        
        let showCard = true;
        
        // Filter by type
        if (type !== 'all' && cardType !== type) {
            showCard = false;
        }
        
        // Filter by genre
        if (genre !== 'all' && !cardGenres.includes(genre)) {
            showCard = false;
        }
        
        // Show or hide the card
        card.style.display = showCard ? '' : 'none';
    });
    
    // Update URL with filter parameters
    const url = new URL(window.location);
    if (type !== 'all') url.searchParams.set('type', type);
    else url.searchParams.delete('type');
    
    if (genre !== 'all') url.searchParams.set('genre', genre);
    else url.searchParams.delete('genre');
    
    if (sort !== 'latest') url.searchParams.set('sort', sort);
    else url.searchParams.delete('sort');
    
    // Update browser history without reloading the page
    window.history.pushState({}, '', url);
    
    // Apply sorting if needed
    applySorting(sort);
}

/**
 * Apply category filtering
 */
function applyCategoryFilter(category) {
    // In a real application, this would filter the series based on category
    console.log(`Filtering by category: ${category}`);
    
    // Update URL
    const url = new URL(window.location);
    
    if (category !== 'All') {
        url.searchParams.set('category', category);
    } else {
        url.searchParams.delete('category');
    }
    
    // Update browser history without reloading the page
    window.history.pushState({}, '', url);
    
    // Apply visual filtering effect (for demonstration)
    applyVisualCategoryEffect(category);
}

/**
 * Apply a visual effect to show filtering in action
 * In a real application, this would be replaced with actual filtering
 */
function applyVisualFilterEffect(type, genre, sort) {
    // Get all series cards
    const seriesCards = document.querySelectorAll('.card');
    
    // Apply a subtle animation to show filtering happened
    seriesCards.forEach(card => {
        // Add a fade effect
        card.style.opacity = '0.6';
        
        // After a short delay, restore the opacity
        setTimeout(() => {
            card.style.opacity = '1';
        }, 300);
    });
}

/**
 * Apply a visual effect for category filtering
 */
function applyVisualCategoryEffect(category) {
    // Get all series cards
    const seriesCards = document.querySelectorAll('.card');
    
    // If "All" is selected, show all cards
    if (category === 'All') {
        seriesCards.forEach(card => {
            card.style.display = '';
            setTimeout(() => {
                card.style.opacity = '1';
            }, 50);
        });
        return;
    }
    
    // Otherwise, apply a filter effect
    seriesCards.forEach(card => {
        // Get the card's categories from the badges
        const cardBadges = card.querySelectorAll('.badge');
        let matchesCategory = false;
        
        cardBadges.forEach(badge => {
            if (badge.textContent.trim() === category) {
                matchesCategory = true;
            }
        });
        
        if (matchesCategory) {
            card.style.opacity = '1';
            card.style.display = '';
        } else {
            // Hide non-matching cards
            card.style.display = 'none';
        }
    });
}

/**
 * Apply sorting to the series cards
 */
function applySorting(sortOption) {
    const seriesContainer = document.querySelector('.grid');
    const seriesCards = Array.from(document.querySelectorAll('.card'));
    
    if (!seriesContainer || seriesCards.length === 0) return;
    
    // Sort the cards based on the selected option
    switch(sortOption) {
        case 'latest':
            seriesCards.sort((a, b) => {
                const aTime = extractUpdateTime(a);
                const bTime = extractUpdateTime(b);
                return aTime - bTime;  // Most recent first
            });
            break;
            
        case 'popular':
            seriesCards.sort((a, b) => {
                const aRating = extractRating(a);
                const bRating = extractRating(b);
                return bRating - aRating;  // Highest rating first
            });
            break;
            
        case 'rating':
            seriesCards.sort((a, b) => {
                const aRating = extractRating(a);
                const bRating = extractRating(b);
                return bRating - aRating;  // Highest rating first
            });
            break;
            
        case 'recent':
            // For demo purposes, we'll use a random ordering since we don't have actual "added date"
            seriesCards.sort(() => Math.random() - 0.5);
            break;
    }
    
    // Remove all cards from container
    seriesCards.forEach(card => card.remove());
    
    // Re-append the sorted cards
    seriesCards.forEach(card => seriesContainer.appendChild(card));
    
    // Apply a visual effect to show that sorting occurred
    seriesCards.forEach(card => {
        card.style.opacity = '0.7';
        setTimeout(() => {
            card.style.opacity = '1';
        }, 300);
    });
}

/**
 * Extract the update time from a card (in milliseconds for sorting)
 */
function extractUpdateTime(card) {
    const updateText = card.querySelector('.text-xs.text-dark-400')?.textContent || '';
    
    // Parse the update text to get a relative time value
    if (updateText.includes('h ago')) {
        const hours = parseInt(updateText) || 0;
        return Date.now() - (hours * 60 * 60 * 1000);
    } else if (updateText.includes('d ago')) {
        const days = parseInt(updateText) || 0;
        return Date.now() - (days * 24 * 60 * 60 * 1000);
    } else if (updateText.includes('m ago')) {
        const minutes = parseInt(updateText) || 0;
        return Date.now() - (minutes * 60 * 1000);
    } else if (updateText.includes('Completed')) {
        // Completed series are considered older
        return 0;
    }
    
    // Default - return current time (will be sorted last)
    return Date.now();
}

/**
 * Extract the rating from a card
 */
function extractRating(card) {
    const ratingText = card.querySelector('.text-xs.text-dark-300')?.textContent || '0';
    return parseFloat(ratingText) || 0;
} 