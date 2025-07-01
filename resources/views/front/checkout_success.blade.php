<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venomfank Readers - Checkout Success</title>
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

    <!-- Success Page -->
    <section class="py-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-dark-700 rounded-lg overflow-hidden shadow-xl p-8" data-aos="fade-up">
                <div class="text-center mb-8">
                    <div class="inline-flex items-center justify-center w-24 h-24 bg-green-500 bg-opacity-20 rounded-full mb-6">
                        <i class="fas fa-check-circle text-5xl text-green-500"></i>
                    </div>
                    <h2 class="text-3xl font-bold mb-2">Payment Successful!</h2>
                    <p class="text-xl text-dark-300">Your coin purchase was completed successfully.</p>
                </div>
                
                <div class="bg-dark-800 rounded-lg p-6 mb-8">
                    <h3 class="text-xl font-semibold mb-4">Order Details</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between border-b border-dark-600 pb-3">
                            <span class="text-dark-300">Package:</span>
                            <span class="font-medium">{{ $coinPackage->name }}</span>
                        </div>
                        
                        <div class="flex justify-between border-b border-dark-600 pb-3">
                            <span class="text-dark-300">Amount:</span>
                            <div class="flex items-center">
                                <img src="https://cdn-icons-png.flaticon.com/512/2933/2933116.png" alt="Coin" class="w-5 h-5 mr-2">
                                <span class="font-medium">{{ number_format($coinPackage->coin_amount, 0, ',', '.') }} coins</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between border-b border-dark-600 pb-3">
                            <span class="text-dark-300">Price:</span>
                            <span class="font-medium">Rp {{ number_format($coinPackage->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between border-b border-dark-600 pb-3">
                            <span class="text-dark-300">Tax (12%):</span>
                            <span class="font-medium">Rp {{ number_format($coinPackage->price * 0.12, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex justify-between font-semibold text-lg">
                            <span>Total:</span>
                            <span>Rp {{ number_format($coinPackage->price * 1.12, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-green-900 bg-opacity-20 border border-green-600 rounded-lg p-6 mb-8">
                    <div class="flex items-center">
                        <i class="fas fa-coins text-3xl text-yellow-500 mr-4"></i>
                        <div>
                            <h3 class="text-xl font-semibold mb-1">Coins Added Successfully</h3>
                            <p class="text-dark-300">
                                {{ number_format($coinPackage->coin_amount, 0, ',', '.') }} coins have been added to your account balance.
                                <br>Current balance: {{ number_format(Auth::user()->coin_balance, 0, ',', '.') }} coins
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-6">
                    <a href="{{ route('front.homepage') }}" class="btn-secondary text-center px-8 py-3">
                        <i class="fas fa-home mr-2"></i>Go to Homepage
                    </a>
                    <a href="{{ route('front.pricing') }}" class="btn-primary text-center px-8 py-3">
                        <i class="fas fa-shopping-cart mr-2"></i>Buy More Coins
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
    
    <!-- Venomfank JS Modules -->
    <script src="{{ asset('js/utils.js') }}"></script>
    <script src="{{ asset('js/navigation.js') }}"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize animations
            AOS.init({
                duration: 800,
                once: true
            });
        });
    </script>
</body>
</html>
