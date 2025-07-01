<?php

namespace App\Http\Controllers;

use App\Models\CoinPackage;
use App\Models\User;
use App\Services\CoinPackageService;
use App\Services\PaymentService;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class CoinController extends Controller
{
    protected $transactionService;
    protected $paymentService;
    protected $coinPackageService;

    public function __construct(
        TransactionService $transactionService,
        PaymentService $paymentService,
        CoinPackageService $coinPackageService
    ) {
        $this->transactionService = $transactionService;
        $this->paymentService = $paymentService;
        $this->coinPackageService = $coinPackageService;
    }

    public function pricing()
    {
        $coin_packages = $this->coinPackageService->getAllPackage();
        $user = Auth::user();

        return view('front.pricing', compact('coin_packages', 'user'));
    }

    public function checkout(CoinPackage $coinPackage)
    {
        // Store the coin package ID in session for later use
        Session::put('coin_package_id', $coinPackage->id);
        
        return view('front.checkout', compact('coinPackage'));
    }

    public function paymentStoreMidtrans(Request $request)
    {
        try {
            $validated = $request->validate([
                'payment_method' => 'required|string',
                'coin_package_id' => 'required|exists:coin_packages,id',
            ]);
            
            // Store the coin package ID in session
            Session::put('coin_package_id', $validated['coin_package_id']);
            
            $snapToken = $this->paymentService->createPayment($validated['coin_package_id']);

            if (!$snapToken) {
                return response()->json(['error' => 'Failed to create Midtrans transaction.'], 500);
            }

            return response()->json(['snap_token' => $snapToken], 200);
        } catch (\Exception $e) {
            Log::error('Payment creation failed: ' . $e->getMessage());
            return response()->json(['error' => 'Payment failed: ' . $e->getMessage()], 500);
        }
    }

    public function paymentMidtransNotification(Request $request)
    {
        try {
            $transactionStatus = $this->paymentService->handlePaymentNotification();

            if (!$transactionStatus) {
                return response()->json(['error' => 'Invalid transaction data.'], 400);
            }

            return response()->json(['status' => $transactionStatus], 200);
        } catch (\Exception $e) {
            Log::error('Failed to handle payment notification:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to process payment notification.'], 500);
        }
    }

    public function checkout_success()
    {
        $coinPackage = $this->transactionService->getRecentCoinPackage();

        if (!$coinPackage) {
            return redirect()->route('front.pricing')->with('error', 'No recent subscription found.');
        }

        // Clear the coin package ID from session after successful checkout
        Session::forget('coin_package_id');
        
        // Get user's latest transaction
        $transaction = $this->transactionService->getUserTransaction()->first();
        
        // If transaction exists and payment is successful but coins not yet added
        if ($transaction && $transaction->payment_status === 'completed' && !$transaction->is_coins_added) {
            // Add coins to user's account
            $user = User::find(Auth::id());
            $user->coin_balance += $coinPackage->coin_amount;
            $user->save();
            
            // Mark transaction as coins added
            $transaction->is_coins_added = true;
            $transaction->save();
        }

        return view('front.checkout_success', compact('coinPackage'));
    }
}
