/**
 * Venomfank - Chapters Module
 * Handles chapter listing, sorting, and loading functionality
 */

/**
 * Initialize chapter functionality
 */
document.addEventListener('DOMContentLoaded', function() {
    initChapters();
});

/**
 * Initialize all chapter functionality
 */
function initChapters() {
    // Check if we're on the series/show.html page
    const chaptersContainer = document.querySelector('.space-y-2');
    const loadMoreBtn = document.getElementById('load-more-chapters');
    
    if (chaptersContainer && loadMoreBtn) {
        initLoadMoreChapters();
        initChapterSorting();
        
        // Apply initial sorting based on URL parameter or default
        const urlParams = new URLSearchParams(window.location.search);
        const sortParam = urlParams.get('sort');
        const sortSelect = document.querySelector('select[name="sort"]');
        
        if (sortParam && sortSelect) {
            sortSelect.value = sortParam;
            sortChapters(sortParam);
        }
    }
}

/**
 * Initialize chapter sorting functionality
 */
function initChapterSorting() {
    const sortSelect = document.querySelector('select[name="sort"]');
    
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            sortChapters(this.value);
        });
    }
}

/**
 * Sort chapters based on selected option
 */
function sortChapters(sortOption) {
    const chaptersContainer = document.querySelector('.space-y-2');
    const chapters = Array.from(chaptersContainer.querySelectorAll('a'));
    
    // Sort chapters based on the selected option
    switch (sortOption) {
        case 'latest':
            // Sort by chapter number (highest first)
            chapters.sort((a, b) => {
                const aNum = getChapterNumber(a);
                const bNum = getChapterNumber(b);
                return bNum - aNum;
            });
            break;
            
        case 'oldest':
            // Sort by chapter number (lowest first)
            chapters.sort((a, b) => {
                const aNum = getChapterNumber(a);
                const bNum = getChapterNumber(b);
                return aNum - bNum;
            });
            break;
            
        case 'az':
            // Sort alphabetically by chapter title
            chapters.sort((a, b) => {
                const aTitle = a.querySelector('h3').textContent;
                const bTitle = b.querySelector('h3').textContent;
                return aTitle.localeCompare(bTitle);
            });
            break;
            
        case 'za':
            // Sort reverse alphabetically by chapter title
            chapters.sort((a, b) => {
                const aTitle = a.querySelector('h3').textContent;
                const bTitle = b.querySelector('h3').textContent;
                return bTitle.localeCompare(aTitle);
            });
            break;
    }
    
    // Remove all chapters from the container
    chapters.forEach(chapter => chapter.remove());
    
    // Re-append the sorted chapters
    chapters.forEach(chapter => chaptersContainer.appendChild(chapter));
}

/**
 * Get chapter number from chapter element
 */
function getChapterNumber(chapterElement) {
    const chapterTitle = chapterElement.querySelector('h3').textContent;
    const match = chapterTitle.match(/Chapter (\d+)/);
    return match ? parseInt(match[1]) : 0;
}

/**
 * Initialize load more chapters functionality
 */
function initLoadMoreChapters() {
    const loadMoreBtn = document.getElementById('load-more-chapters');
    
    if (loadMoreBtn) {
        loadMoreBtn.addEventListener('click', function() {
            loadMoreChapters();
        });
    }
}

/**
 * Load more chapters
 * In a real app, this would fetch from an API
 * For demo purposes, we'll add some hardcoded chapters
 */
function loadMoreChapters() {
    const chaptersContainer = document.querySelector('.space-y-2');
    const loadMoreBtn = document.getElementById('load-more-chapters');
    
    // Show loading state
    if (loadMoreBtn) {
        loadMoreBtn.disabled = true;
        loadMoreBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Loading...';
    }
    
    // In a real app, this would be an API call
    // Simulate network delay
    setTimeout(() => {
        // Get current sort order
        const sortValue = document.querySelector('select[name="sort"]')?.value || 'latest';
        
        // Generate new chapters
        const lastChapterNum = getLastChapterNumber(chaptersContainer);
        const newChapters = generateMoreChapters(lastChapterNum, sortValue);
        
        // Add new chapters to the DOM
        newChapters.forEach(chapterHTML => {
            chaptersContainer.insertAdjacentHTML('beforeend', chapterHTML);
        });
        
        // Restore button state
        if (loadMoreBtn) {
            loadMoreBtn.disabled = false;
            loadMoreBtn.innerHTML = '<i class="fas fa-spinner mr-2"></i> Load More Chapters';
        }
        
        // If we've loaded "all" chapters, disable the button
        if (getLastChapterNumber(chaptersContainer) <= 1080) {
            if (loadMoreBtn) {
                loadMoreBtn.disabled = true;
                loadMoreBtn.innerHTML = 'No More Chapters';
            }
        }
    }, 1000);
}

/**
 * Get the last (lowest) chapter number
 */
function getLastChapterNumber(container) {
    const chapters = container.querySelectorAll('a');
    let lowestChapterNum = 9999;
    
    chapters.forEach(chapter => {
        const chapterTitle = chapter.querySelector('h3').textContent;
        const match = chapterTitle.match(/Chapter (\d+)/);
        if (match) {
            const chapterNum = parseInt(match[1]);
            if (chapterNum < lowestChapterNum) {
                lowestChapterNum = chapterNum;
            }
        }
    });
    
    return lowestChapterNum;
}

/**
 * Generate more chapter HTML based on the last chapter number
 */
function generateMoreChapters(lastChapterNum, sortOrder) {
    const newChapters = [];
    const startFrom = lastChapterNum - 1;
    const count = 3; // Number of chapters to load each time
    
    for (let i = 0; i < count; i++) {
        const chapterNum = startFrom - i;
        if (chapterNum <= 1080) break; // Stop at chapter 1080
        
        const viewCount = Math.floor(Math.random() * 200000) + 100000;
        const date = new Date();
        date.setDate(date.getDate() - (1085 - chapterNum));
        const formattedDate = `${date.toLocaleString('default', { month: 'long' })} ${date.getDate()}, ${date.getFullYear()}`;
        
        const chapterHTML = `
            <a href="#" class="flex items-center justify-between bg-dark-800 hover:bg-dark-700 rounded-lg p-4 transition duration-300">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-accent-500 rounded-lg flex items-center justify-center mr-4">
                        <i class="fas fa-book text-white"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold">Chapter ${chapterNum}</h3>
                        <p class="text-dark-400 text-sm">Released: ${formattedDate}</p>
                    </div>
                </div>
                <div class="flex items-center">
                    <span class="text-dark-400 mr-4">
                        <i class="fas fa-eye mr-1"></i> ${(viewCount / 1000).toFixed(0)}K
                    </span>
                    <i class="fas fa-chevron-right text-dark-500"></i>
                </div>
            </a>
        `;
        
        newChapters.push(chapterHTML);
    }
    
    // If sort order is oldest or a-z, reverse the array
    if (sortOrder === 'oldest' || sortOrder === 'az') {
        newChapters.reverse();
    }
    
    return newChapters;
}

// Helper function for jQuery-like contains selector
Element.prototype.contains = function(text) {
    return this.textContent.includes(text);
};

// Export the module
window.VenomfankChapters = {
    init: initChapters,
    loadMore: loadMoreChapters,
    sort: sortChapters
}; 