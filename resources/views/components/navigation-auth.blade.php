<header class="main-header" id="main-header">
    <nav class="bg-transparent">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center header-content">
                <div class="flex items-center space-x-8">
                    <a href="{{ route('front.homepage') }}" class="text-2xl font-bold text-accent-500">
                        <i class="fas fa-book-open mr-2"></i>Venomfank
                    </a>
                    <div class="hidden md:flex space-x-6">
                        <a href="{{ route('front.homepage') }}" class="nav-link {{ request()->routeIs('front.homepage') && !request()->query('type') ? 'active' : '' }}"><i class="fas fa-compass mr-1"></i> Browse</a>
                        <a href="{{ route('front.homepage', ['type' => 'popular']) }}" class="nav-link {{ request()->query('type') === 'popular' ? 'active' : '' }}"><i class="fas fa-fire mr-1"></i> Popular</a>
                        <a href="{{ route('front.homepage', ['type' => 'latest']) }}" class="nav-link {{ request()->query('type') === 'latest' ? 'active' : '' }}"><i class="fas fa-clock mr-1"></i> Latest</a>
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
                        @auth
                            <span class="coin-badge">{{ Auth::user()->coin_balance }}</span>
                        @else
                            <span class="coin-badge">0</span>
                        @endauth
                    </div>
                    @auth
                        <!-- Authenticated User Profile Dropdown -->
                        <div class="relative">
                            <button class="flex items-center space-x-2" id="profile-button" aria-expanded="false" aria-haspopup="true">
                                <div class="w-9 h-9 rounded-full overflow-hidden border-2 border-accent-500">
                                    <img src="{{ Auth::user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . Auth::user()->name }}" alt="{{ Auth::user()->name }}'s Avatar" class="w-full h-full object-cover">
                                </div>
                                <span class="hidden md:inline-block text-sm font-medium">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-dark-400"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-dark-800 rounded-lg shadow-lg py-2 hidden z-50" id="profile-dropdown" aria-labelledby="profile-button">
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm hover:bg-dark-700">
                                    <i class="fas fa-user mr-2"></i>Dashboard
                                </a>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm hover:bg-dark-700">
                                    <i class="fas fa-cog mr-2"></i>Edit Profile
                                </a>
                                <a href="{{ route('front.homepage') }}" class="block px-4 py-2 text-sm hover:bg-dark-700">
                                    <i class="fas fa-bookmark mr-2"></i>Bookmarks
                                </a>
                                <a href="{{ route('front.homepage') }}" class="block px-4 py-2 text-sm hover:bg-dark-700">
                                    <i class="fas fa-history mr-2"></i>Reading History
                                </a>
                                <a href="{{ route('front.homepage') }}" class="block px-4 py-2 text-sm hover:bg-dark-700">
                                    <i class="fas fa-shopping-cart mr-2"></i>Purchases
                                </a>
                                <div class="border-t border-dark-700 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}" class="block">
                                    @csrf
                                    <button type="submit" class="w-full text-left px-4 py-2 text-sm text-error hover:bg-dark-700">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Sign Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <!-- Guest Navigation -->
                        <a href="{{ route('login') }}" class="nav-link hidden sm:inline-block"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                        <a href="{{ route('register') }}" class="btn-primary hidden sm:inline-block"><i class="fas fa-user-plus mr-1"></i> Register</a>
                    @endauth
                    
                    <button class="md:hidden p-2 rounded-md bg-dark-700 hover:bg-dark-600 transition-colors" id="mobile-menu-button" aria-expanded="false" aria-label="Toggle navigation menu">
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
                        <a href="{{ route('front.homepage') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-bookmark mr-1"></i> Bookmarks</a>
                        <a href="{{ route('front.homepage') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-history mr-1"></i> Reading History</a>
                        <a href="{{ route('front.homepage') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-shopping-cart mr-1"></i> Purchases</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block px-3 py-2 rounded-md text-base font-medium text-error nav-link">
                                <i class="fas fa-sign-out-alt mr-1"></i> Sign Out
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-sign-in-alt mr-1"></i> Login</a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium nav-link"><i class="fas fa-user-plus mr-1"></i> Register</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>

<div class="category-filter" id="category-filter">
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
</div>