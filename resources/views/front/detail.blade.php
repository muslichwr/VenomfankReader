<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $series->title }} - Venomfank Readers</title>
    <link href="{{ asset('src/output.css') }}" rel="stylesheet">
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" />
    <!-- SwiperJS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />
</head>
<body class="no-footer-layout">
    <!-- Navigation -->
    <header class="main-header" id="main-header">
        <nav class="bg-transparent">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center header-content">
                    <div class="flex items-center space-x-8">
                        <a href="{{ route('front.homepage') }}" class="text-2xl font-bold text-accent-500">
                            <i class="fas fa-book-open mr-2"></i>Venomfank
                        </a>
                        <div class="hidden md:flex space-x-6">
                            <a href="{{ route('front.homepage') }}" class="nav-link"><i class="fas fa-compass mr-1"></i> Browse</a>
                            <a href="{{ route('front.homepage', ['type' => 'popular']) }}" class="nav-link"><i class="fas fa-fire mr-1"></i> Popular</a>
                            <a href="{{ route('front.homepage', ['type' => 'latest']) }}" class="nav-link"><i class="fas fa-clock mr-1"></i> Latest</a>
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <input type="text" placeholder="Search series..." class="input-field w-64 pr-10">
                            <svg class="absolute right-3 top-3.5 h-5 w-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <div class="hidden sm:flex items-center space-x-2">
                            <span class="coin-badge">250</span>
                        </div>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn-primary hidden sm:inline-block"><i class="fas fa-user mr-1"></i> Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link hidden sm:inline-block"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                            <a href="{{ route('register') }}" class="btn-primary hidden sm:inline-block"><i class="fas fa-user-plus mr-1"></i> Register</a>
                        @endauth
                        <button class="md:hidden p-2 rounded-md bg-dark-700 hover:bg-dark-600 transition-colors" id="mobile-menu-button">
                            <i class="fas fa-bars text-dark-200"></i>
                        </button>
                    </div>
                </div>
                
                <!-- Mobile Menu -->
                <div class="md:hidden hidden" id="mobile-menu">
                    <div class="px-2 pt-2 pb-3 space-y-1 border-t border-dark-700 mt-2">
                        <a href="{{ route('front.homepage') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-compass mr-1"></i> Browse</a>
                        <a href="{{ route('front.homepage', ['type' => 'popular']) }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-fire mr-1"></i> Popular</a>
                        <a href="{{ route('front.homepage', ['type' => 'latest']) }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-clock mr-1"></i> Latest</a>
                        @auth
                            <a href="{{ url('/dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-user mr-1"></i> Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                            <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-user-plus mr-1"></i> Register</a>
                        @endauth
                        <div class="px-3 py-2 flex items-center">
                            <span class="text-dark-300 mr-2">Coins:</span>
                            <span class="coin-badge">250</span>
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Header Spacer -->
    <div class="header-spacer"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Breadcrumb -->
        <nav class="text-sm mb-8">
            <ol class="flex items-center space-x-2 text-dark-400">
                <li><a href="{{ route('front.homepage') }}" class="hover:text-white">Home</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li><a href="{{ route('front.homepage', ['type' => $series->type]) }}" class="hover:text-white">{{ ucfirst($series->type) }}</a></li>
                <li><i class="fas fa-chevron-right text-xs"></i></li>
                <li class="text-white">{{ $series->title }}</li>
            </ol>
        </nav>

        <!-- Series Header -->
        <div class="flex flex-col md:flex-row gap-8 mb-12" data-aos="fade-up">
            <div class="w-full md:w-80 flex-shrink-0">
                <div class="aspect-[3/4] rounded-xl overflow-hidden mb-4 relative">
                    <img src="{{ Storage::url($series->cover_image) }}" class="w-full h-full object-cover">
                    @if($series->is_popular)
                    <div class="absolute top-2 right-2">
                        <span class="badge badge-primary">HOT</span>
                    </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button class="w-full btn-primary flex items-center justify-center">
                        <i class="fas fa-bookmark mr-2"></i>
                        Add to Bookmarks
                    </button>
                    <button class="w-full btn-secondary flex items-center justify-center">
                        <i class="fas fa-heart mr-2"></i>
                        Add to Favorites
                    </button>
                    @if($chapters->isNotEmpty())
                    <a href="{{ route('front.chapter', $chapters->first()->slug) }}" class="w-full btn-primary block text-center flex items-center justify-center">
                        <i class="fas fa-book-open mr-2"></i>
                        Start Reading
                    </a>
                    @else
                    <button disabled class="w-full btn-primary opacity-50 cursor-not-allowed flex items-center justify-center">
                        <i class="fas fa-book-open mr-2"></i>
                        No Chapters
                    </button>
                    @endif
                </div>
            </div>

            <div class="flex-1">
                <div class="mb-6">
                    <h1 class="text-4xl font-bold mb-4">{{ $series->title }}</h1>
                    <div class="flex flex-wrap items-center gap-4 mb-4">
                        <span class="bg-accent-500 text-white px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst($series->type) }}</span>
                        <span class="bg-success text-white px-3 py-1 rounded-full text-sm font-medium">{{ ucfirst($series->status) }}</span>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-eye text-accent-500"></i>
                            <span class="text-lg font-semibold">{{ number_format($series->views_count) }}</span>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-dark-800 rounded-lg">
                            <div class="text-2xl font-bold text-accent-500">{{ $chapters->count() }}</div>
                            <div class="text-sm text-dark-400">Chapters</div>
                        </div>
                        <div class="text-center p-4 bg-dark-800 rounded-lg">
                            <div class="text-2xl font-bold text-success">{{ $series->updated_at->diffForHumans() }}</div>
                            <div class="text-sm text-dark-400">Last Update</div>
                        </div>
                        <div class="text-center p-4 bg-dark-800 rounded-lg">
                            <div class="text-2xl font-bold text-warning">{{ $freeChaptersCount }}</div>
                            <div class="text-sm text-dark-400">Free Chapters</div>
                        </div>
                        <div class="text-center p-4 bg-dark-800 rounded-lg">
                            <div class="text-2xl font-bold text-accent-500">{{ $paidChaptersCount }}</div>
                            <div class="text-sm text-dark-400">Paid Chapters</div>
                        </div>
                    </div>
                </div>

                <!-- Synopsis -->
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Synopsis</h3>
                    <p class="text-dark-200 leading-relaxed">
                      {!! $series->description !!}
                    </p>
                </div>

                <!-- Additional Info -->
                @if($series->author)
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Author</h3>
                    <p class="text-dark-200">{{ $series->author }}</p>
                </div>
                @endif

                <!-- Categories -->
                @if($series->categories->isNotEmpty())
                <div class="mb-8">
                    <h3 class="text-xl font-semibold mb-4">Categories</h3>
                    <div class="flex flex-wrap gap-2">
                        @foreach($series->categories as $category)
                        <span class="bg-dark-700 text-dark-200 px-3 py-1 rounded-full text-sm">{{ $category->name }}</span>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Chapter List -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Chapters</h2>
                <div class="flex items-center">
                    <label for="sort" class="mr-2 text-dark-300">Sort by:</label>
                    <select id="chapter-sort" name="sort" class="input-field py-1 px-3 w-40" onchange="window.location.href='{{ route('front.detail', $series->slug) }}?sort=' + this.value">
                        <option value="newest" {{ $currentSort === 'newest' ? 'selected' : '' }}>Newest First</option>
                        <option value="oldest" {{ $currentSort === 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>
            
            <div class="space-y-2" id="chapter-list">
                @forelse($chapters as $chapter)
                <a href="{{ route('front.chapter', $chapter->slug) }}" id="chapter-{{ $chapter->chapter_number }}" class="flex items-center justify-between bg-dark-800 hover:bg-dark-700 rounded-lg p-4 transition duration-300">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-accent-500 rounded-lg flex items-center justify-center mr-4">
                            <i class="fas fa-book text-white"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold">Chapter {{ $chapter->chapter_number }} - {{ $chapter->title }}</h3>
                            <p class="text-dark-400 text-sm">Released: {{ $chapter->created_at->format('F j, Y, g:i A') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        @if(!$chapter->is_free)
                        <span class="text-warning mr-4">
                            <i class="fas fa-coins mr-1"></i> {{ $chapter->coin_cost }}
                        </span>
                        @endif
                        <span class="text-dark-400 mr-4">
                            <i class="fas fa-eye mr-1"></i> {{ number_format($chapter->views_count) }}
                        </span>
                        <i class="fas fa-chevron-right text-dark-500"></i>
                    </div>
                </a>
                @empty
                <div class="flex justify-center items-center py-12 bg-dark-800 rounded-lg">
                    <p class="text-dark-400">No chapters available yet.</p>
                </div>
                @endforelse
            </div>
            
            @if(count($chapters) > 10)
            <div class="flex justify-center mt-8">
                <button id="load-more-chapters" class="btn-secondary">
                    <i class="fas fa-spinner mr-2"></i>
                    Load More Chapters
                </button>
            </div>
            @endif
        </div>
    </div>
    
    <!-- JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/chapters.js') }}"></script>
    
    <script>
        // Initialize AOS
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init();
            
            // Handle chapter sorting
            const sortSelect = document.getElementById('chapter-sort');
            if (sortSelect) {
                sortSelect.addEventListener('change', function() {
                    const sortValue = this.value;
                    window.location.href = '{{ route('front.detail', $series->slug) }}?sort=' + sortValue;
                });
            }
            
            // Scroll to chapter if hash is present in URL
            if (window.location.hash) {
                const chapterId = window.location.hash;
                const chapterElement = document.querySelector(chapterId);
                if (chapterElement) {
                    setTimeout(() => {
                        chapterElement.scrollIntoView({ behavior: 'smooth' });
                        chapterElement.classList.add('bg-dark-600');
                        setTimeout(() => {
                            chapterElement.classList.remove('bg-dark-600');
                        }, 2000);
                    }, 500);
                }
            }
        });
    </script>
</body>
</html>