<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Venomfank Readers - Checkout</title>
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

    <!-- Checkout Page -->
    <section class="py-12 -mt-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-dark-700 rounded-lg overflow-hidden shadow-xl" data-aos="fade-up">
                <!-- Order Summary -->
                <form id="checkout-form" method="POST" action="{{ route('front.payment_store_midtrans') }}" class="w-full flex flex-col gap-6 p-6 lg:p-8">
                    @csrf
                    <input type="hidden" name="payment_method" value="Midtrans">
                    <input type="hidden" name="coin_package_id" value="{{ $coinPackage->id }}">

                    <div class="p-6 border-b border-dark-600">
                        <h2 class="text-2xl font-semibold mb-6">Order Summary</h2>
                        
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-16 h-16 bg-dark-600 rounded-lg flex items-center justify-center">
                                    <img src="https://cdn-icons-png.flaticon.com/512/2933/2933116.png" alt="Coin Package" class="w-10 h-10">
                                </div>
                                <div>
                                    <h3 class="font-semibold text-lg">{{ $coinPackage->name }}</h3>
                                    <div class="flex items-center mt-1">
                                        <span class="text-dark-400 text-sm">Get </span>
                                        <span class="text-lg font-bold text-accent-500 mx-1">{{ number_format($coinPackage->coin_amount, 0, ',', '.') }}</span>
                                        <span class="text-dark-400 text-sm">coins</span>
                                        
                                        @php
                                            $bonusPercent = ($coinPackage->getCoinsPerCurrencyRatio() > 100) ? round(($coinPackage->getCoinsPerCurrencyRatio() - 100) / 100 * 100) : null;
                                        @endphp
                                        
                                        @if($bonusPercent)
                                            <span class="ml-2 bg-accent-500 text-white text-xs px-2 py-0.5 rounded">+{{ $bonusPercent }}% bonus</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <span class="text-2xl font-bold">Rp {{ number_format($coinPackage->price, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="space-y-3 border-t border-dark-600 pt-4">
                            <div class="flex justify-between">
                                <span class="text-dark-300">Subtotal:</span>
                                <span class="font-medium">Rp {{ number_format($coinPackage->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between" id="discount-container">
                                <span class="text-dark-300">Discount:</span>
                                <span class="font-medium text-green-400">-Rp 0</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-dark-300">Tax (12%):</span>
                                @php
                                    $tax = $coinPackage->price * 0.12;
                                    $total = $coinPackage->price + $tax;
                                @endphp
                                <span class="font-medium">Rp {{ number_format($tax, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between border-t border-dark-600 pt-3 mt-3">
                                <span class="text-lg font-semibold">Total:</span>
                                <span class="text-lg font-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Options -->
                    <div class="p-6 bg-dark-800">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-semibold">Payment Method</h3>
                            <div class="flex space-x-2">
                                <img src="https://cdn.midtrans.com/assets/images/logo-midtrans-color.png" alt="Midtrans" class="h-6">
                                <img src="https://cdn-icons-png.flaticon.com/512/196/196578.png" alt="Visa" class="h-6">
                                <img src="https://cdn-icons-png.flaticon.com/512/196/196561.png" alt="Mastercard" class="h-6">
                                <img src="https://cdn-icons-png.flaticon.com/512/196/196565.png" alt="PayPal" class="h-6">
                            </div>
                        </div>
                        
                        <div class="bg-dark-700 p-4 rounded-lg mb-6 flex items-start space-x-3">
                            <i class="fas fa-shield-alt text-accent-500 mt-1"></i>
                            <div>
                                <h4 class="font-medium">Secure Payment</h4>
                                <p class="text-sm text-dark-400">Your payment will be processed securely via Midtrans payment gateway.</p>
                            </div>
                        </div>
                        
                        <button type="button" id="pay-button" class="btn-primary w-full py-4 text-center text-lg">
                            <i class="fas fa-lock mr-2"></i>Pay Now
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Additional Info -->
            <div class="mt-8 text-center" data-aos="fade-up" data-aos-delay="100">
                <p class="text-dark-300 mb-2">Need help? <a href="#" class="text-accent-500 hover:underline">Contact our support team</a></p>
                <div class="flex justify-center space-x-6 mt-4">
                    <a href="{{ route('front.pricing') }}" class="text-dark-400 hover:text-dark-200 transition-colors">
                        <i class="fas fa-arrow-left mr-1"></i> Back to Pricing
                    </a>
                    <a href="{{ route('front.homepage') }}" class="text-dark-400 hover:text-dark-200 transition-colors">
                        <i class="fas fa-home mr-1"></i> Home
                    </a>
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
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.clientKey') }}"></script>

    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        payButton.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Show loading indicator
            payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';
            payButton.disabled = true;
            
            // Get the form data
            const form = document.getElementById('checkout-form');
            const formData = new FormData(form);
            
            // Fetch the Snap token from your backend
            fetch('{{ route('front.payment_store_midtrans') }}', {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value,
                    "Accept": "application/json"
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.snap_token) {
                    // Trigger Midtrans Snap payment popup
                    snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.href = "{{ route('front.checkout.success') }}";
                        },
                        onPending: function(result) {
                            alert('Payment pending! You will receive a notification once payment is confirmed.');
                            window.location.href = "{{ route('front.homepage') }}";
                        },
                        onError: function(result) {
                            alert('Payment failed: ' + result.status_message);
                            // Reset the button
                            payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay Now';
                            payButton.disabled = false;
                        },
                        onClose: function() {
                            alert('Payment popup closed without completing payment.');
                            // Reset the button
                            payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay Now';
                            payButton.disabled = false;
                        }
                    });
                } else {
                    alert('Error: ' + (data.error || 'Failed to create payment token'));
                    // Reset the button
                    payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay Now';
                    payButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Payment processing error: ' + error.message);
                // Reset the button
                payButton.innerHTML = '<i class="fas fa-lock mr-2"></i>Pay Now';
                payButton.disabled = false;
            });
        });
    </script>
</body>
</html>
