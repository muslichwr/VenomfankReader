/**
 * Webtoon Reader JavaScript functionality
 * Handles webtoon-specific reading features
 */

document.addEventListener('DOMContentLoaded', function() {
    // Content width selector for webtoons
    const contentWidthSelect = document.getElementById('contentWidthSelect');
    const webtoonContainer = document.querySelector('.webtoon-container');
    
    if (contentWidthSelect && webtoonContainer) {
        contentWidthSelect.addEventListener('change', function() {
            // Remove all width classes
            webtoonContainer.classList.remove('width-narrow', 'width-medium', 'width-wide', 'width-full');
            
            // Add the selected width class
            webtoonContainer.classList.add('width-' + this.value);
            
            // Save the preference
            localStorage.setItem('webtoon-width', this.value);
        });
        
        // Apply saved width preference
        const savedWidth = localStorage.getItem('webtoon-width') || 'medium';
        contentWidthSelect.value = savedWidth;
        webtoonContainer.classList.add('width-' + savedWidth);
    }
    
    // Image quality selector for webtoons
    const imageQualitySelect = document.getElementById('imageQualitySelect');
    const webtoonSegments = document.querySelectorAll('.webtoon-segment');
    
    if (imageQualitySelect && webtoonSegments.length) {
        imageQualitySelect.addEventListener('change', function() {
            const quality = this.value;
            
            webtoonSegments.forEach(segment => {
                if (quality === 'high') {
                    segment.src = segment.dataset.highQualitySrc;
                } else if (quality === 'medium') {
                    segment.src = segment.dataset.mediumQualitySrc;
                } else if (quality === 'low') {
                    segment.src = segment.dataset.lowQualitySrc;
                }
            });
            
            // Save the preference
            localStorage.setItem('webtoon-image-quality', quality);
        });
        
        // Apply saved quality preference
        const savedQuality = localStorage.getItem('webtoon-image-quality') || 'high';
        imageQualitySelect.value = savedQuality;
        
        // Apply quality to images
        if (savedQuality !== 'high') {
            webtoonSegments.forEach(segment => {
                if (savedQuality === 'medium') {
                    segment.src = segment.dataset.mediumQualitySrc;
                } else if (savedQuality === 'low') {
                    segment.src = segment.dataset.lowQualitySrc;
                }
            });
        }
    }
    
    // Initialize Lazy Loading for webtoon segments
    const lazyLoadWebtoonSegments = function() {
        const options = {
            rootMargin: '500px 0px',
            threshold: 0.1
        };
        
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    const quality = localStorage.getItem('webtoon-image-quality') || 'high';
                    
                    if (quality === 'high') {
                        img.src = img.dataset.highQualitySrc;
                    } else if (quality === 'medium') {
                        img.src = img.dataset.mediumQualitySrc;
                    } else if (quality === 'low') {
                        img.src = img.dataset.lowQualitySrc;
                    }
                    
                    img.classList.remove('loading');
                    observer.unobserve(img);
                }
            });
        }, options);
        
        document.querySelectorAll('.webtoon-segment.loading').forEach(img => {
            observer.observe(img);
        });
    };
    
    // Call lazy load function
    lazyLoadWebtoonSegments();
}); 