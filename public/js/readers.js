/**
 * Venomfank Readers JavaScript
 * Common functionality for both novel and webtoon readers
 */

document.addEventListener('DOMContentLoaded', function() {
    // Settings panel toggle
    const settingsBtn = document.getElementById('settingsBtn');
    const settingsPanel = document.getElementById('settingsPanel');
    
    if (settingsBtn && settingsPanel) {
        settingsBtn.addEventListener('click', function() {
            settingsPanel.classList.toggle('hidden');
        });
        
        // Close settings when clicking outside
        document.addEventListener('click', function(event) {
            if (!settingsPanel.contains(event.target) && !settingsBtn.contains(event.target)) {
                settingsPanel.classList.add('hidden');
            }
        });
    }
    
    // Reading progress tracking
    const trackReadingProgress = function() {
        const progressBar = document.querySelector('.reading-progress-bar');
        const progressText = document.querySelector('.reading-progress-text');
        
        if (!progressBar) return;
        
        window.addEventListener('scroll', function() {
            const winScroll = document.body.scrollTop || document.documentElement.scrollTop;
            const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (winScroll / height) * 100;
            
            progressBar.style.width = scrolled + '%';
            
            if (progressText) {
                progressText.textContent = Math.round(scrolled) + '%';
            }
            
            // Save reading progress if more than 5%
            if (scrolled > 5) {
                const seriesId = document.querySelector('meta[name="series-id"]')?.content;
                const chapterId = document.querySelector('meta[name="chapter-id"]')?.content;
                
                if (seriesId && chapterId) {
                    localStorage.setItem(`reading-progress-${seriesId}-${chapterId}`, scrolled);
                }
            }
        });
        
        // Restore reading progress
        const seriesId = document.querySelector('meta[name="series-id"]')?.content;
        const chapterId = document.querySelector('meta[name="chapter-id"]')?.content;
        
        if (seriesId && chapterId) {
            const savedProgress = localStorage.getItem(`reading-progress-${seriesId}-${chapterId}`);
            
            if (savedProgress && savedProgress > 10 && savedProgress < 90) {
                // Ask user if they want to resume
                const shouldResume = confirm('Would you like to resume reading from where you left off?');
                
                if (shouldResume) {
                    const height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    const scrollTo = (height * savedProgress) / 100;
                    
                    window.scrollTo({
                        top: scrollTo,
                        behavior: 'smooth'
                    });
                }
            }
        }
    };
    
    // Novel reader specific settings
    const initializeNovelSettings = function() {
        const novelContent = document.getElementById('novelContent');
        
        if (!novelContent) return;
        
        // Font size
        const fontSizeSlider = document.getElementById('fontSizeSlider');
        if (fontSizeSlider) {
            fontSizeSlider.addEventListener('input', function() {
                novelContent.style.fontSize = this.value + 'px';
                localStorage.setItem('novel-font-size', this.value);
            });
            
            // Apply saved font size
            const savedFontSize = localStorage.getItem('novel-font-size') || '18';
            fontSizeSlider.value = savedFontSize;
            novelContent.style.fontSize = savedFontSize + 'px';
        }
        
        // Line height
        const lineHeightSlider = document.getElementById('lineHeightSlider');
        if (lineHeightSlider) {
            lineHeightSlider.addEventListener('input', function() {
                novelContent.style.lineHeight = this.value;
                localStorage.setItem('novel-line-height', this.value);
            });
            
            // Apply saved line height
            const savedLineHeight = localStorage.getItem('novel-line-height') || '1.7';
            lineHeightSlider.value = savedLineHeight;
            novelContent.style.lineHeight = savedLineHeight;
        }
        
        // Font family
        const fontFamilySelect = document.getElementById('fontFamilySelect');
        if (fontFamilySelect) {
            fontFamilySelect.addEventListener('change', function() {
                novelContent.style.fontFamily = this.value;
                localStorage.setItem('novel-font-family', this.value);
            });
            
            // Apply saved font family
            const savedFontFamily = localStorage.getItem('novel-font-family') || 'Poppins';
            fontFamilySelect.value = savedFontFamily;
            novelContent.style.fontFamily = savedFontFamily;
        }
        
        // Text alignment
        const textAlignSelect = document.getElementById('textAlignSelect');
        if (textAlignSelect) {
            textAlignSelect.addEventListener('change', function() {
                novelContent.style.textAlign = this.value;
                localStorage.setItem('novel-text-align', this.value);
            });
            
            // Apply saved text alignment
            const savedTextAlign = localStorage.getItem('novel-text-align') || 'left';
            textAlignSelect.value = savedTextAlign;
            novelContent.style.textAlign = savedTextAlign;
        }
        
        // Reading width
        const readingWidthSelect = document.getElementById('readingWidthSelect');
        const novelContainer = document.querySelector('.novel-container');
        
        if (readingWidthSelect && novelContainer) {
            readingWidthSelect.addEventListener('change', function() {
                // Remove all width classes
                novelContainer.classList.remove('reading-width-narrow', 'reading-width-medium', 'reading-width-wide');
                
                if (this.value !== 'medium') {
                    novelContainer.classList.add('reading-width-' + this.value);
                }
                
                localStorage.setItem('novel-reading-width', this.value);
            });
            
            // Apply saved reading width
            const savedReadingWidth = localStorage.getItem('novel-reading-width') || 'medium';
            readingWidthSelect.value = savedReadingWidth;
            
            if (savedReadingWidth !== 'medium') {
                novelContainer.classList.add('reading-width-' + savedReadingWidth);
            }
        }
        
        // Auto scroll
        const autoScrollCheck = document.getElementById('autoScrollCheck');
        const autoScrollSpeedSlider = document.getElementById('autoScrollSpeedSlider');
        const autoScrollIndicator = document.getElementById('autoScrollIndicator');
        
        let autoScrollInterval = null;
        
        if (autoScrollCheck && autoScrollSpeedSlider) {
            autoScrollCheck.addEventListener('change', function() {
                if (this.checked) {
                    const speed = parseFloat(autoScrollSpeedSlider.value) || 2;
                    
                    autoScrollInterval = setInterval(() => {
                        window.scrollBy({
                            top: speed,
                            behavior: 'auto'
                        });
                        
                        // Check if reached end of content
                        const scrollPosition = window.scrollY + window.innerHeight;
                        const contentHeight = document.body.offsetHeight;
                        
                        if (scrollPosition >= contentHeight) {
                            autoScrollCheck.checked = false;
                            clearInterval(autoScrollInterval);
                            autoScrollInterval = null;
                            
                            if (autoScrollIndicator) {
                                autoScrollIndicator.classList.add('hidden');
                            }
                        }
                    }, 30);
                    
                    if (autoScrollIndicator) {
                        autoScrollIndicator.classList.remove('hidden');
                    }
                } else {
                    clearInterval(autoScrollInterval);
                    autoScrollInterval = null;
                    
                    if (autoScrollIndicator) {
                        autoScrollIndicator.classList.add('hidden');
                    }
                }
            });
            
            // Update speed when slider changes
            autoScrollSpeedSlider.addEventListener('input', function() {
                if (autoScrollCheck.checked && autoScrollInterval) {
                    clearInterval(autoScrollInterval);
                    
                    const speed = parseFloat(this.value) || 2;
                    
                    autoScrollInterval = setInterval(() => {
                        window.scrollBy({
                            top: speed,
                            behavior: 'auto'
                        });
                    }, 30);
                }
                
                localStorage.setItem('auto-scroll-speed', this.value);
            });
            
            // Apply saved auto scroll speed
            const savedAutoScrollSpeed = localStorage.getItem('auto-scroll-speed') || '2';
            autoScrollSpeedSlider.value = savedAutoScrollSpeed;
        }
    };
    
    // Initialize tracking and settings
    trackReadingProgress();
    initializeNovelSettings();
    
    // Chapter navigation
    document.querySelector('.chapter-select')?.addEventListener('change', function() {
        window.location.href = this.value;
    });
}); 