<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="series-id" content="{{ $series->id }}">
    <meta name="chapter-id" content="{{ $chapter->id }}">
    <meta name="content-type" content="{{ $contentType }}">
    <title>{{ $chapter->title }} - {{ $series->title }} | Venomfank Readers</title>
    <link href="{{ asset('src/output.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="reader-layout">
    <!-- Reader Navigation -->
    <nav class="reader-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <div class="flex justify-between items-center h-16">
                <!-- Left Side: Series Info -->
                <div class="flex items-center space-x-4">
                    <a href="{{ route('front.detail', $series->slug) }}" class="text-accent-500 hover:text-accent-400 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-semibold series-title">{{ $series->title }}</h1>
                        <p class="text-sm text-dark-400 chapter-info">Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}</p>
                    </div>
                </div>
                
                <!-- Right Side: Controls -->
                <div class="flex items-center space-x-4">
                    <!-- Reading Settings -->
                    <button id="settingsBtn" class="p-2 text-dark-400 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </button>

                    <!-- Bookmark -->
                    <button class="p-2 text-dark-400 hover:text-accent-500 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                        </svg>
                    </button>

                    <!-- Reading Progress (Mobile Hidden) -->
                    <div class="hidden md:flex items-center space-x-2 text-sm text-dark-400">
                        <span>Progress:</span>
                        <div class="w-32 bg-dark-700 rounded-full h-2">
                            <div class="reading-progress-bar bg-accent-500 h-2 rounded-full" style="width: 0%"></div>
                        </div>
                        <span class="reading-progress-text">0%</span>
                    </div>

                    <!-- Chapter Navigation -->
                    <div class="flex items-center space-x-2">
                        @if ($prev)
                        <a href="{{ route('front.chapter', $prev->slug) }}" class="p-2 text-dark-400 hover:text-white transition-colors prev-chapter-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </a>
                        @else
                        <span class="p-2 text-dark-700 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </span>
                        @endif
                        
                        <select class="input-field text-sm py-2 chapter-select" onchange="if(this.value) window.location.href=this.value">
                            @foreach($series->chapters as $seriesChapter)
                                <option value="{{ route('front.chapter', $seriesChapter->slug) }}" 
                                    {{ $chapter->id === $seriesChapter->id ? 'selected' : '' }}>
                                    Chapter {{ $seriesChapter->chapter_number }}: {{ $seriesChapter->title }}
                                </option>
                            @endforeach
                        </select>
                        
                        @if ($next)
                        <a href="{{ route('front.chapter', $next->slug) }}" class="p-2 text-dark-400 hover:text-white transition-colors next-chapter-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                        @else
                        <span class="p-2 text-dark-700 cursor-not-allowed">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Settings Panel -->
    <div id="settingsPanel" class="hidden fixed top-16 right-4 settings-panel p-6 z-40 w-80">
        <h3 class="text-lg font-semibold mb-4">Reading Settings</h3>
        
        <!-- Novel Settings (hidden for webtoon) -->
        <div id="novelSettings" class="space-y-4">
            <!-- Font Size -->
            <div>
                <label class="block text-sm font-medium mb-2">Font Size</label>
                <input type="range" id="fontSizeSlider" min="14" max="24" value="18" class="slider-control w-full">
                <div class="flex justify-between text-xs text-dark-400 mt-1">
                    <span>Small</span>
                    <span>Large</span>
                </div>
            </div>

            <!-- Line Height -->
            <div>
                <label class="block text-sm font-medium mb-2">Line Height</label>
                <input type="range" id="lineHeightSlider" min="1.4" max="2.0" step="0.1" value="1.7" class="slider-control w-full">
                <div class="flex justify-between text-xs text-dark-400 mt-1">
                    <span>Tight</span>
                    <span>Loose</span>
                </div>
            </div>

            <!-- Font Family -->
            <div>
                <label class="block text-sm font-medium mb-2">Font Family</label>
                <select id="fontFamilySelect" class="input-field w-full">
                    <option value="Poppins">Poppins (Default)</option>
                    <option value="Georgia">Georgia</option>
                    <option value="Times New Roman">Times New Roman</option>
                    <option value="Arial">Arial</option>
                </select>
            </div>
            
            <!-- Text Alignment -->
            <div>
                <label class="block text-sm font-medium mb-2">Text Alignment</label>
                <select id="textAlignSelect" class="input-field w-full">
                    <option value="left">Left</option>
                    <option value="justify">Justify</option>
                    <option value="center">Center</option>
                </select>
            </div>

            <!-- Reading Width -->
            <div>
                <label class="block text-sm font-medium mb-2">Reading Width</label>
                <select id="readingWidthSelect" class="input-field w-full">
                    <option value="narrow">Narrow</option>
                    <option value="medium" selected>Medium</option>
                    <option value="wide">Wide</option>
                </select>
            </div>
        </div>
        
        <!-- Webtoon Settings (hidden for novel) -->
        <div id="webtoonSettings" class="space-y-4 hidden">
            <!-- Reading Mode - Limited options for webtoon -->
            <div>
                <label class="block text-sm font-medium mb-2">Reading Mode</label>
                <select id="readingModeSelect" class="input-field w-full">
                    <option value="vertical" selected>Vertical Scrolling</option>
                </select>
                <p class="text-xs text-dark-400 mt-1">Webtoons are optimized for vertical scrolling</p>
            </div>

            <!-- Image Quality -->
            <div>
                <label class="block text-sm font-medium mb-2">Image Quality</label>
                <select id="imageQualitySelect" class="input-field w-full">
                    <option value="high">High Quality</option>
                    <option value="medium">Medium Quality</option>
                    <option value="low">Low Quality (Fast)</option>
                </select>
            </div>

            <!-- Width Control - Specific for webtoon -->
            <div>
                <label class="block text-sm font-medium mb-2">Content Width</label>
                <select id="contentWidthSelect" class="input-field w-full">
                    <option value="narrow">Narrow</option>
                    <option value="medium" selected>Medium</option>
                    <option value="wide">Wide</option>
                    <option value="full">Full Width</option>
                </select>
            </div>
        </div>
        
        <!-- Common Settings for both novel and webtoon -->
        <div class="space-y-4 mt-4">
            <hr class="border-dark-600 my-4">
            
            <!-- Auto Scroll -->
            <div class="flex items-center justify-between mb-2">
                <label class="text-sm font-medium">Auto Scroll</label>
                <input type="checkbox" id="autoScrollCheck" class="rounded">
            </div>
            
            <div>
                <label class="block text-sm font-medium mb-2">Scroll Speed</label>
                <input type="range" id="autoScrollSpeedSlider" min="1" max="10" step="0.5" value="2" class="slider-control w-full">
                <div class="flex justify-between text-xs text-dark-400 mt-1">
                    <span>Slow</span>
                    <span>Fast</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Reading Progress Bar at bottom -->
    <div class="reading-progress-container">
        <div class="reading-progress-bar"></div>
    </div>

    <!-- Dynamic Content Area - This will switch between novel and webtoon content -->
    <div id="contentContainer" class="py-8 chapter-content">
        <!-- Novel Content Container -->
        <div id="novelContent" class="max-w-4xl mx-auto px-4">
            <!-- Chapter Header -->
            <div class="chapter-title">
                <h1 id="novelTitle">{{ $series->title }}</h1>
                <h2 id="novelSubtitle">Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}</h2>
                <div class="meta">Published {{ $chapter->created_at->diffForHumans() }} • {{ round(str_word_count($chapter->content) / 250) }} min read</div>
            </div>

            <!-- Novel Text Content -->
            <div class="novel-container">
                <div id="novelTextContent" class="novel-content">
                    @if($contentType === 'novel')
                        {!! $chapter->content !!}
                    @else
                        <p class="text-center text-dark-400 italic">This content is not a novel type.</p>
                    @endif
                    
                    <div class="text-center py-8 border-t border-dark-700 mt-8">
                        <p class="text-dark-400 italic">End of Chapter {{ $chapter->chapter_number }}</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Webtoon Content Container -->
        <div id="webtoonContent" class="py-4 hidden">
            <!-- Webtoon Container -->
            <div id="webtoonContainer" class="webtoon-container width-medium">
                @if(in_array($contentType, ['manga', 'manhwa', 'manhua']))
                    @php
                        $images = $chapter->getProcessedContent();
                    @endphp
                    
                    @if(is_array($images) && count($images) > 0)
                        @foreach($images as $index => $image)
                            <img src="{{ Storage::url($image) }}" alt="Page {{ $index + 1 }}" class="webtoon-segment" 
                                data-high-quality-src="{{ Storage::url($image) }}" 
                                data-medium-quality-src="{{ Storage::url($image) }}" 
                                data-low-quality-src="{{ Storage::url($image) }}">
                        @endforeach
                    @else
                        <div class="text-center py-12 px-4">
                            <div class="bg-dark-800 rounded-lg p-8 border border-dark-700 max-w-2xl mx-auto">
                                <h3 class="text-xl font-semibold mb-4">No Images Available</h3>
                                <p class="text-dark-300 mb-6">Sorry, no images are available for this chapter.</p>
                            </div>
                        </div>
                    @endif
                @else
                    <div class="text-center py-12 px-4">
                        <div class="bg-dark-800 rounded-lg p-8 border border-dark-700 max-w-2xl mx-auto">
                            <h3 class="text-xl font-semibold mb-4">Incorrect Content Type</h3>
                            <p class="text-dark-300 mb-6">This chapter is not available in manga/manhwa/manhua format.</p>
                        </div>
                    </div>
                @endif

                <!-- End of Chapter -->
                <div class="text-center py-12 px-4">
                    <div class="bg-dark-800 rounded-lg p-8 border border-dark-700 max-w-2xl mx-auto">
                        <h3 class="text-xl font-semibold mb-4">End of Chapter</h3>
                        <p class="text-dark-300 mb-6">Thank you for reading! Don't forget to bookmark this series.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Coin system locked chapter notice - Show only if next chapter requires payment -->
    @if($next && !$next->is_free && $next->coin_cost > 0)
    <div class="max-w-4xl mx-auto px-4 mb-8">
        <div class="coin-notification">
            <div class="coin-notification-icon">
                <i class="fas fa-coins"></i>
            </div>
            <div class="coin-notification-content">
                <h4 class="font-semibold mb-1">Continue Reading with Early Access</h4>
                <p>Next chapter is already available with early access. Unlock it now to continue reading!</p>
                <button class="btn-primary">
                    <i class="fas fa-unlock mr-2"></i>
                    Unlock Next Chapter for {{ $next->coin_cost }} Coins
                </button>
            </div>
        </div>
    </div>
    @endif

    <!-- Chapter Navigation -->
    <div class="flex justify-center space-x-4 my-8">
        @if($prev)
        <a href="{{ route('front.chapter', $prev->slug) }}" class="chapter-nav-btn prev-chapter-btn">
            <svg class="w-5 h-5 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Previous Chapter
        </a>
        @endif
        
        <a href="{{ route('front.detail', $series->slug) }}" class="btn-secondary">Back to Series</a>
        
        @if($next)
        <a href="{{ route('front.chapter', $next->slug) }}" class="chapter-nav-btn next-chapter-btn">
            Next Chapter
            <svg class="w-5 h-5 ml-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
        @endif
    </div>

    {{-- <!-- Comments Section -->
    <div class="max-w-4xl mx-auto px-4 pb-12">
        <div class="comments-section">
            <h3 class="text-xl font-semibold mb-6">Chapter Discussion</h3>
            
            <!-- Comment Form -->
            <div class="mb-6">
                <textarea class="input-field w-full h-24 mb-4" placeholder="Share your thoughts on this chapter..."></textarea>
                <button class="btn-primary">Post Comment</button>
            </div>

            <!-- Comments -->
            <div class="space-y-4">
                <div class="comment-item">
                    <div class="flex items-start space-x-3">
                        <div class="comment-avatar bg-warning">
                            <span>N</span>
                        </div>
                        <div class="flex-1">
                            <div class="comment-header">
                                <span class="font-medium">novel_enthusiast</span>
                                <span class="text-dark-400 text-sm">2 hours ago</span>
                            </div>
                            <p class="text-dark-200">Another excellent chapter! The way the author builds suspense is masterful. Can't wait to see what this ancient seal contains.</p>
                        </div>
                    </div>
                </div>

                <div class="comment-item">
                    <div class="flex items-start space-x-3">
                        <div class="comment-avatar bg-accent-500">
                            <span>O</span>
                        </div>
                        <div class="flex-1">
                            <div class="comment-header">
                                <span class="font-medium">overlord_fan</span>
                                <span class="text-dark-400 text-sm">3 hours ago</span>
                            </div>
                            <p class="text-dark-200">Ainz's internal monologue about his guildmates always hits me in the feels. Such great character development!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Auto-scroll indicator (hidden by default) -->
    <div id="autoScrollIndicator" class="auto-scroll-indicator hidden">
        <i class="fas fa-play"></i>
        <span>Auto-scrolling</span>
    </div>

    <!-- JavaScript -->
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/readers.js') }}"></script>
    <script src="{{ asset('js/webtoon.js') }}"></script>
    <script>
        // Initialize the reader based on content type
        document.addEventListener('DOMContentLoaded', function() {
            const contentType = document.querySelector('meta[name="content-type"]').content;
            const novelContent = document.getElementById('novelContent');
            const webtoonContent = document.getElementById('webtoonContent');
            const novelSettings = document.getElementById('novelSettings');
            const webtoonSettings = document.getElementById('webtoonSettings');
            
            // Set body class based on content type
            if (contentType === 'manga' || contentType === 'manhwa' || contentType === 'manhua') {
                document.body.classList.add('bg-black');
                novelContent.classList.add('hidden');
                webtoonContent.classList.remove('hidden');
                novelSettings.classList.add('hidden');
                webtoonSettings.classList.remove('hidden');
            } else {
                // Default to novel
                document.body.classList.add('bg-dark-900');
                webtoonContent.classList.add('hidden');
                webtoonSettings.classList.add('hidden');
            }
            
            // Add keyboard shortcuts for chapter navigation
            document.addEventListener('keydown', function(e) {
                // Page up/down for scrolling within chapter
                if (e.key === 'PageUp') {
                    window.scrollBy({
                        top: -window.innerHeight * 0.9,
                        behavior: 'smooth'
                    });
                } else if (e.key === 'PageDown') {
                    window.scrollBy({
                        top: window.innerHeight * 0.9,
                        behavior: 'smooth'
                    });
                }
                
                // Home/End for top/bottom of chapter
                else if (e.key === 'Home') {
                    window.scrollTo({
                        top: 0,
                        behavior: 'smooth'
                    });
                } else if (e.key === 'End') {
                    window.scrollTo({
                        top: document.body.scrollHeight,
                        behavior: 'smooth'
                    });
                }
                
                // Left/right arrow for prev/next chapter
                else if (e.key === 'ArrowLeft') {
                    const prevBtn = document.querySelector('.prev-chapter-btn');
                    if (prevBtn && !prevBtn.classList.contains('cursor-not-allowed')) {
                        prevBtn.click();
                    }
                } else if (e.key === 'ArrowRight') {
                    const nextBtn = document.querySelector('.next-chapter-btn');
                    if (nextBtn && !nextBtn.classList.contains('cursor-not-allowed')) {
                        nextBtn.click();
                    }
                }
            });
            
            // Track reading progress
            const readingProgressBar = document.querySelector('.reading-progress-bar');
            const readingProgressText = document.querySelector('.reading-progress-text');
            
            window.addEventListener('scroll', function() {
                const totalHeight = document.body.scrollHeight - window.innerHeight;
                const progress = (window.pageYOffset / totalHeight) * 100;
                const roundedProgress = Math.round(progress);
                
                if (readingProgressBar) {
                    readingProgressBar.style.width = roundedProgress + '%';
                }
                
                if (readingProgressText) {
                    readingProgressText.textContent = roundedProgress + '%';
                }
            });
            
            // Settings panel toggle
            const settingsBtn = document.getElementById('settingsBtn');
            const settingsPanel = document.getElementById('settingsPanel');
            
            if (settingsBtn && settingsPanel) {
                settingsBtn.addEventListener('click', function() {
                    settingsPanel.classList.toggle('hidden');
                });
                
                // Close settings when clicking outside
                document.addEventListener('click', function(e) {
                    if (!settingsPanel.contains(e.target) && e.target !== settingsBtn) {
                        settingsPanel.classList.add('hidden');
                    }
                });
            }
        });
    </script>
</body>
</html> 