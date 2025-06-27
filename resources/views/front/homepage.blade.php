<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venomfank Readers - Home</title>
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
<body>
    <!-- Navigation -->
    <x-navigation-auth />


    <!-- Category Filter Bar -->
    {{-- <div class="category-filter" id="category-filter">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4 overflow-x-auto pb-2 pt-2 scrollbar-hide">
                <span class="text-dark-200 font-medium">Categories:</span>
                <a href="{{ route('front.homepage') }}" class="category-pill {{ !request()->query('type') ? 'bg-accent-500 text-white' : '' }}">All</a>
                <a href="{{ route('front.homepage', ['type' => 'manga']) }}" class="category-pill {{ request()->query('type') === 'manga' ? 'bg-accent-500 text-white' : '' }}">Manga</a>
                <a href="{{ route('front.homepage', ['type' => 'novel']) }}" class="category-pill {{ request()->query('type') === 'novel' ? 'bg-accent-500 text-white' : '' }}">Novel</a>
                <a href="{{ route('front.homepage', ['type' => 'manhwa']) }}" class="category-pill {{ request()->query('type') === 'manhwa' ? 'bg-accent-500 text-white' : '' }}">Manhwa</a>
                <a href="{{ route('front.homepage', ['type' => 'manhua']) }}" class="category-pill {{ request()->query('type') === 'manhua' ? 'bg-accent-500 text-white' : '' }}">Manhua</a>
                <a href="{{ route('front.homepage', ['type' => 'featured']) }}" class="category-pill {{ request()->query('type') === 'featured' ? 'bg-accent-500 text-white' : '' }}">Featured</a>
                <a href="{{ route('front.homepage', ['type' => 'popular']) }}" class="category-pill {{ request()->query('type') === 'popular' ? 'bg-accent-500 text-white' : '' }}">Popular</a>
            </div>
        </div>
    </div> --}}

    <!-- Header Spacer -->
    <div class="header-spacer"></div>

    <!-- Featured Series -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Featured Series</h2>
                <a href="{{ route('front.homepage', ['type' => 'featured']) }}" class="text-accent-500 hover:text-accent-400 transition-colors">View all <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="swiper-container featured-swiper">
                <div class="swiper-wrapper">
                    @forelse($featuredSeries ?? [] as $featured)
                    <div class="swiper-slide">
                        <div class="card h-full">
                            <a href="{{ route('front.detail', $featured->slug) }}" class="block">
                                <div class="aspect-[3/4] rounded-lg mb-4 relative overflow-hidden">
                                    @if($featured->cover_image)
                                        <img src="{{ Storage::url($featured->cover_image) }}" alt="{{ $featured->title }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://picsum.photos/id/{{ 100 + $loop->index }}/300/400" alt="{{ $featured->title }}" class="w-full h-full object-cover">
                                    @endif
                                    @if($featured->is_popular)
                                    <div class="absolute top-2 right-2">
                                        <span class="badge badge-primary">HOT</span>
                                    </div>
                                    @endif
                                    <div class="series-card-overlay">
                                        <h3 class="text-lg font-bold text-white">{{ $featured->title }}</h3>
                                    </div>
                                </div>
                            </a>
                            <div class="flex justify-between items-center">
                                <span class="text-xs bg-accent-500 text-white px-2 py-1 rounded">{{ ucfirst($featured->type) }}</span>
                                <span class="text-xs text-dark-400">{{ $featured->updated_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default Card if no featured series -->
                    <div class="swiper-slide">
                        <div class="card h-full">
                            <div class="aspect-[3/4] rounded-lg mb-4 relative overflow-hidden">
                                <img src="https://picsum.photos/id/237/300/400" alt="One Piece" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span class="badge badge-primary">KOSONG</span>
                                </div>
                                <div class="series-card-overlay">
                                    <h3 class="text-lg font-bold text-white">KOSONG</h3>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs bg-accent-500 text-white px-2 py-1 rounded">KOSONG</span>
                                <span class="text-xs text-dark-400">KOSONG</span>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                <div class="swiper-navigation flex items-center space-x-2 mt-6 justify-end">
                    <button class="featured-prev p-2 rounded-full bg-dark-700 hover:bg-accent-500 transition-colors">
                        <i class="fas fa-chevron-left text-dark-200"></i>
                    </button>
                    <button class="featured-next p-2 rounded-full bg-dark-700 hover:bg-accent-500 transition-colors">
                        <i class="fas fa-chevron-right text-dark-200"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Trending Manga Slider -->
    <section class="py-16 bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Trending Now</h2>
                <a href="{{ route('front.homepage', ['type' => 'popular']) }}" class="text-accent-500 hover:text-accent-400 transition-colors">View all <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            
            <div class="swiper-container trending-swiper">
                <div class="swiper-wrapper">
                    @forelse($popularSeries ?? [] as $index => $trending)
                    <div class="swiper-slide">
                        <div class="card h-full">
                            <a href="{{ route('front.detail', $trending->slug) }}" class="block">
                                <div class="aspect-[3/4] rounded-lg mb-4 relative overflow-hidden">
                                    @if($trending->cover_image)
                                        <img src="{{ Storage::url($trending->cover_image) }}" alt="{{ $trending->title }}" class="w-full h-full object-cover">
                                    @else
                                        <img src="https://picsum.photos/id/{{ 300 + $index }}/300/400" alt="{{ $trending->title }}" class="w-full h-full object-cover">
                                    @endif
                                    <div class="absolute top-2 right-2">
                                        <span class="badge badge-primary">TOP {{ $index + 1 }}</span>
                                    </div>
                                    <div class="series-card-overlay">
                                        <h3 class="text-lg font-bold text-white">{{ $trending->title }}</h3>
                                    </div>
                                </div>
                            </a>
                            <div class="flex justify-between items-center">
                                <span class="text-xs bg-accent-500 text-white px-2 py-1 rounded">{{ ucfirst($trending->type) }}</span>
                                <div class="flex space-x-1">
                                    @if($trending->categories && $trending->categories->isNotEmpty())
                                        <span class="text-xs bg-dark-600 text-dark-300 px-2 py-1 rounded">{{ $trending->categories->first()->name }}</span>
                                    @else
                                        <span class="text-xs bg-dark-600 text-dark-300 px-2 py-1 rounded">{{ ucfirst($trending->type) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <!-- Default Card if no trending series -->
                    <div class="swiper-slide">
                        <div class="card h-full">
                            <div class="aspect-[3/4] rounded-lg mb-4 relative overflow-hidden">
                                <img src="https://picsum.photos/id/433/300/400" alt="Chainsaw Man" class="w-full h-full object-cover">
                                <div class="absolute top-2 right-2">
                                    <span class="badge badge-primary">TOP 1</span>
                                </div>
                                <div class="series-card-overlay">
                                    <h3 class="text-lg font-bold text-white">Chainsaw Man</h3>
                                </div>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs bg-accent-500 text-white px-2 py-1 rounded">Manga</span>
                                <div class="flex space-x-1">
                                    <span class="text-xs bg-dark-600 text-dark-300 px-2 py-1 rounded">Action</span>
                                    <span class="text-xs bg-dark-600 text-dark-300 px-2 py-1 rounded">Horror</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforelse
                </div>
                
                <div class="swiper-navigation flex items-center space-x-2 mt-6 justify-end">
                    <button class="trending-prev p-2 rounded-full bg-dark-700 hover:bg-accent-500 transition-colors">
                        <i class="fas fa-chevron-left text-dark-200"></i>
                    </button>
                    <button class="trending-next p-2 rounded-full bg-dark-700 hover:bg-accent-500 transition-colors">
                        <i class="fas fa-chevron-right text-dark-200"></i>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Latest Updates -->
    <section class="py-16 bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold">Latest Updates</h2>
                <a href="{{ route('front.homepage', ['type' => 'latest']) }}" class="text-accent-500 hover:text-accent-400 transition-colors">View all <i class="fas fa-arrow-right ml-1"></i></a>
            </div>
            <div class="space-y-4">
                @forelse($series as $item)
                <div class="flex items-start space-x-4 p-4 bg-dark-700 rounded-lg hover:bg-dark-600 transition-colors cursor-pointer" onclick="window.location.href='{{ route('front.detail', $item->slug) }}'">
                    <div class="w-16 h-20 rounded overflow-hidden">
                        @if($item->cover_image)
                            <img src="{{ Storage::url($item->cover_image) }}" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @else
                            <img src="https://picsum.photos/id/{{ 200 + $loop->index }}/100/130" alt="{{ $item->title }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h3 class="font-semibold">
                                <a href="{{ route('front.detail', $item->slug) }}" class="hover:text-accent-500 transition-colors">
                                    {{ $item->title }}
                                </a>
                            </h3>
                            <span class="text-xs bg-accent-500 text-white px-2 py-1 rounded">{{ ucfirst($item->type) }}</span>
                        </div>
                        <div class="flex items-center mt-1 mb-2">
                            <span class="text-xs text-dark-400 mr-2"><i class="far fa-clock mr-1"></i>{{ $item->updated_at->diffForHumans() }}</span>
                            <span class="text-xs text-dark-400"><i class="far fa-eye mr-1"></i>{{ number_format($item->views_count) }} views</span>
                        </div>
                        <div class="space-y-1 mt-2 chapter-list">
                            @php
                                // Get the 5 most recent chapters by chapter number
                                $latestChapters = $item->chapters()->orderBy('chapter_number', 'desc')->take(5)->get();
                            @endphp
                            
                            @forelse($latestChapters as $chapter)
                            <div class="flex justify-between items-center">
                                <a href="{{ route('front.chapter', $chapter->slug) }}" class="text-xs text-dark-300 hover:text-accent-500 transition-colors">
                                    Chapter {{ $chapter->chapter_number }} - {{ $chapter->title }}
                                </a>
                                <div class="flex items-center">
                                    @if(!$chapter->is_free)
                                    <span class="chapter-lock mr-1" title="{{ $chapter->coin_cost }} coins required">{{ $chapter->coin_cost }}</span>
                                    @endif
                                    @if($loop->first)
                                    <span class="text-xs text-accent-500">New</span>
                                    @else
                                    <span class="text-xs text-dark-400">{{ $chapter->created_at->diffForHumans() }}</span>
                                    @endif
                                </div>
                            </div>
                            @empty
                            <div class="flex justify-center items-center py-2">
                                <span class="text-xs text-dark-400">No chapters available</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
                @empty
                <div class="flex items-center justify-center p-8 bg-dark-700 rounded-lg">
                    <p class="text-dark-400">No series available at the moment.</p>
                </div>
                @endforelse
            </div>
            
            @if($series->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $series->links() }}
            </div>
            @endif
        </div>
    </section>
    
    <!-- Cookie Consent -->
    <div class="fixed bottom-4 left-4 right-4 md:left-auto md:max-w-md bg-dark-700 rounded-lg p-4 shadow-lg flex flex-col sm:flex-row items-center justify-between z-50 fade-in" id="cookie-banner">
        <div class="flex-1 mr-4">
            <p class="text-sm text-dark-300 mb-2 sm:mb-0">We use cookies to enhance your experience. By continuing to visit this site you agree to our use of cookies.</p>
        </div>
        <div class="flex space-x-2">
            <button class="text-xs px-3 py-2 rounded bg-dark-600 text-dark-300 hover:bg-dark-500 transition-colors">Preferences</button>
            <button class="text-xs px-3 py-2 rounded bg-accent-500 text-white hover:bg-accent-400 transition-colors" id="accept-cookies">Accept All</button>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- Venomfank JS Modules -->
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/sliders.js') }}"></script>
    <script src="{{ asset('js/filters.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script src="{{ asset('js/header-dropdown.js') }}"></script>
</body>
</html>