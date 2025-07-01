<?php

use App\Http\Controllers\CoinController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


// Series
Route::get('/', [FrontController::class, 'homepage'])->name('front.homepage');
Route::get('/series/{slug}', [FrontController::class, 'show'])->name('front.detail');

// Chapter
Route::get('/chapter/{chapterSlug}', [FrontController::class, 'showChapter'])->name('front.chapter');

Route::match(['get', 'post'], '/coin/payment/midtrans/notification',
[CoinController::class, 'paymentMidtransNotification'])
    ->name('front.payment_midtrans_notification');

Route::get('/pricing', [CoinController::class, 'pricing'])->name('front.pricing');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/checkout/success', [CoinController::class, 'checkout_success'])
    ->name('front.checkout.success');

    Route::get('/checkout/{coinPackage}', [CoinController::class, 'checkout'])
    ->name('front.checkout');

    Route::post('/booking/payment/midtrans', [CoinController::class, 'paymentStoreMidtrans'])
    ->name('front.payment_store_midtrans');

});

require __DIR__.'/auth.php';
