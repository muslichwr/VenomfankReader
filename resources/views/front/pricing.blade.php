<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venomfank Readers - Pricing</title>
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

    <!-- Header Spacer -->
    <div class="header-spacer"></div>


    <!-- Pricing Plans -->
    <section id="pricing-plans" class="py-16 bg-dark-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Choose Your Coin Package</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($coin_packages as $package)
                <div class="bg-dark-700 rounded-lg overflow-hidden relative transition-transform hover:transform hover:scale-105 hover:shadow-xl" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    @if($package->is_featured)
                    <div class="absolute top-0 right-0">
                        <div class="bg-accent-500 text-white px-4 py-1 rounded-bl-lg font-medium text-sm">
                            POPULAR
                        </div>
                    </div>
                    @endif
                    
                    @if($package->isBestValue())
                    <div class="absolute top-0 right-0">
                        <div class="bg-warning text-white px-4 py-1 rounded-bl-lg font-medium text-sm">
                            BEST VALUE
                        </div>
                    </div>
                    @endif
                    
                    <div class="p-6 border-b border-dark-600">
                        <h3 class="text-2xl font-semibold mb-2">{{ $package->name }}</h3>
                        <div class="flex items-center">
                            <span class="text-4xl font-bold">Rp {{ number_format($package->price, 0, ',', '.') }}</span>
                            @if($package->getCoinsPerCurrencyRatio() > 100)
                                <span class="text-dark-400 text-sm line-through ml-2">Rp {{ number_format($package->price * 1.2, 0, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center mt-4 space-x-2">
                            <div class="flex items-center">
                                <img src="https://cdn-icons-png.flaticon.com/512/2933/2933116.png" alt="Coin" class="w-6 h-6 mr-2">
                                <span class="text-2xl font-bold text-accent-500">{{ number_format($package->coin_amount, 0, ',', '.') }}</span>
                            </div>
                            <span class="text-dark-400">coins</span>
                            @php
                                $bonusPercent = ($package->getCoinsPerCurrencyRatio() > 100) ? round(($package->getCoinsPerCurrencyRatio() - 100) / 100 * 100) : null;
                            @endphp
                            
                            @if($bonusPercent)
                                <span class="bg-accent-500 text-white text-xs px-2 py-1 rounded ml-2">+{{ $bonusPercent }}% bonus</span>
                            @endif
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="min-h-[100px]">
                            <p class="text-dark-300 mb-4">{{ $package->description }}</p>
                            <ul class="space-y-3">
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Akses ke chapter premium</span>
                                </li>
                                @if($package->coin_amount >= 2500)
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Early access ke rilis baru</span>
                                </li>
                                @endif
                                @if($package->coin_amount >= 6000)
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Pengalaman membaca bebas iklan</span>
                                </li>
                                @endif
                                @if($package->coin_amount >= 15000)
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Priority early access (24h)</span>
                                </li>
                                @endif
                                @if($package->coin_amount >= 35000)
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Akses ke chapter eksklusif</span>
                                </li>
                                @endif
                                @if($package->coin_amount >= 100000)
                                <li class="flex items-start">
                                    <i class="fas fa-check text-accent-500 mt-1 mr-2"></i>
                                    <span>Badge VIP di profil</span>
                                </li>
                                @endif
                            </ul>
                        </div>
                        <a href="{{ route('front.checkout', $package->id) }}" class="{{ $package->is_featured ? 'btn-primary' : 'btn-secondary' }} w-full text-center mt-6 block py-3">
                            <i class="fas fa-shopping-cart mr-2"></i>Buy Now
                        </a>
                    </div>
                </div>
                @empty
                <div class="col-span-full text-center py-12">
                    <p class="text-xl">No coin packages available at this time. Please check back later.</p>
                </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- Coin Usage Info -->
    <section id="how-to-use" class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Cara Menggunakan Koin</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-dark-700 rounded-lg p-6 text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="inline-block p-3 rounded-full bg-dark-600 mb-4">
                        <i class="fas fa-lock text-3xl text-accent-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Buka Konten Premium</h3>
                    <p class="text-dark-300">Gunakan koin untuk membuka chapter premium dan seri eksklusif yang tidak tersedia untuk pengguna gratis.</p>
                </div>
                
                <div class="bg-dark-700 rounded-lg p-6 text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="inline-block p-3 rounded-full bg-dark-600 mb-4">
                        <i class="fas fa-clock text-3xl text-accent-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Akses Lebih Awal</h3>
                    <p class="text-dark-300">Dapatkan akses lebih awal ke rilis chapter baru sebelum tersedia untuk orang lain. VIP mendapatkan akses hingga 72 jam lebih awal!</p>
                </div>
                
                <div class="bg-dark-700 rounded-lg p-6 text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="inline-block p-3 rounded-full bg-dark-600 mb-4">
                        <i class="fas fa-ad text-3xl text-accent-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Pengalaman Bebas Iklan</h3>
                    <p class="text-dark-300">Nikmati pengalaman membaca tanpa iklan saat Anda menggunakan koin untuk membuka fitur premium.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="py-16 bg-dark-800">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-bold text-center mb-12" data-aos="fade-up">Pertanyaan yang Sering Diajukan</h2>
            
            <div class="space-y-4" data-aos="fade-up">
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Berapa lama koin bertahan?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Koin tidak pernah kadaluarsa! Setelah Anda membeli koin, mereka akan tetap ada di akun Anda sampai Anda menggunakannya.</p>
                    </div>
                </div>
                
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Bisakah saya mentransfer koin ke akun lain?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Saat ini, koin tidak dapat ditransfer antar akun. Koin terikat pada akun yang membelinya.</p>
                    </div>
                </div>
                
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Bagaimana cara mendapatkan koin gratis?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Anda dapat memperoleh koin gratis dengan menyelesaikan check-in harian, berpartisipasi dalam event, dan mengajak teman untuk bergabung dengan Venomfank.</p>
                    </div>
                </div>
                
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Metode pembayaran apa yang tersedia?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Kami menerima kartu kredit/debit (Visa, Mastercard, American Express), PayPal, QRIS, GoPay, OVO, Dana, Virtual Account, dan transfer bank.</p>
                    </div>
                </div>
                
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Bisakah saya mendapatkan pengembalian dana untuk koin yang tidak terpakai?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Sayangnya, kami tidak menawarkan pengembalian dana untuk koin yang telah dibeli. Semua pembelian koin bersifat final.</p>
                    </div>
                </div>
                
                <div class="bg-dark-700 rounded-lg">
                    <button class="w-full px-6 py-4 text-left font-semibold flex justify-between items-center focus:outline-none" onclick="toggleFaq(this)">
                        <span>Apakah ada diskon untuk pembelian koin dalam jumlah besar?</span>
                        <i class="fas fa-chevron-down transition-transform"></i>
                    </button>
                    <div class="hidden px-6 pb-4 text-dark-300">
                        <p>Ya! Seperti yang bisa Anda lihat di atas, semakin besar paket yang Anda beli, semakin besar bonus koin yang Anda dapatkan. Paket VIP memberikan bonus 100% koin tambahan!</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-dark-700 rounded-lg p-8 md:p-12" data-aos="fade-up">
                <div class="text-center max-w-3xl mx-auto">
                    <h2 class="text-3xl md:text-4xl font-bold mb-6">Siap meningkatkan pengalaman membaca Anda?</h2>
                    <p class="text-xl text-dark-300 mb-8">Bergabunglah dengan ribuan pembaca yang telah membuka konten premium dengan koin Venomfank</p>
                    @if(count($coin_packages) > 0)
                    <a href="{{ route('front.checkout', $coin_packages->where('is_featured', true)->first() ? $coin_packages->where('is_featured', true)->first()->id : $coin_packages->first()->id) }}" class="btn-primary text-lg px-8 py-4 inline-block">
                        <i class="fas fa-shopping-cart mr-2"></i>Beli Koin Sekarang
                    </a>
                    @endif
                </div>
            </div>
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
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS animation
            AOS.init({
                duration: 800,
                once: true,
                offset: 100
            });
            
            // FAQ toggle function
            window.toggleFaq = function(el) {
                const content = el.nextElementSibling;
                const icon = el.querySelector('i');
                
                if (content.classList.contains('hidden')) {
                    content.classList.remove('hidden');
                    icon.classList.add('transform', 'rotate-180');
                } else {
                    content.classList.add('hidden');
                    icon.classList.remove('transform', 'rotate-180');
                }
            }
        });
    </script>
</body>
</html>
