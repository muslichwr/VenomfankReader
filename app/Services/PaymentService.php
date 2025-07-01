<?php

namespace App\Services;

use App\Helpers\TransactionHelper;
use App\Models\CoinPackage;
use App\Models\Transaction;
use App\Repository\CoinPackageRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    protected $midtransService;
    protected $coinPackageRepository;
    protected $transactionRepository;

    public function __construct(
        MidtransService $midtransService,
        CoinPackageRepositoryInterface $coinPackageRepository,
        TransactionRepositoryInterface $transactionRepository
    ) {
        $this->midtransService = $midtransService;
        $this->coinPackageRepository = $coinPackageRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function createPayment(int $coinPackageId)
    {
        $user = Auth::user();
        $coinPackage = $this->coinPackageRepository->findById($coinPackageId);

        if (!$coinPackage) {
            Log::error('Coin package not found', ['coinPackageId' => $coinPackageId]);
            return null;
        }

        $tax = 0.12;
        $totalTax = $coinPackage->price * $tax;
        $grandTotal = $coinPackage->price + $totalTax;

        $params = [
            'transaction_details' => [
                'order_id' => TransactionHelper::generatePaymentCode(),
                'gross_amount' => (int) $grandTotal,
            ],
            'costumer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
                'phone' => '081234567890',
            ],
            'item_details' => [
                [
                    'id' => $coinPackage->id,
                    'price' => (int) $coinPackage->price,
                    'quantity' => 1,
                    'name' => $coinPackage->name,
                ],
                [
                    'id' => 'tax',
                    'price' => (int) $totalTax,
                    'quantity' => 1,
                    'name' => 'PPN 12%',
                ],
            ],
            'custom_field1' => $user->id,
            'custom_field2' => $coinPackageId,
        ];

        return $this->midtransService->createSnapToken($params);
    }

    public function handlePaymentNotification()
    {
        $notification = $this->midtransService->handleNotification();

        if (in_array($notification['transaction_status'], ['capture', 'settlement'])) {
            $coinPackage = CoinPackage::findOrFail($notification['custom_field2']);
            $this->createTransaction($notification, $coinPackage);
        }
        return $notification['transaction_status'];
    }

    protected function createTransaction(array $notification, CoinPackage $coinPackage)
    {
        $transactionData = [
            'user_id' => $notification['custom_field1'],
            'coin_package_id' => $notification['custom_field2'],
            'coins_received' => $coinPackage->coin_amount,
            'sub_total_amount' => $coinPackage->price,
            'total_tax_amount' => $coinPackage->price * 0.12,
            'grand_total_amount' => $notification['gross_amount'],
            'payment_type' => 'Midtrans',
            'payment_status' => 'completed',
            'is_paid' => true,
            'is_coins_added' => false, // Coins will be added on success page visit
            'booking_trx_id' => $notification['order_id'],
        ];

        $transaction = $this->transactionRepository->create($transactionData);
        Log::info('Transaction created successfully', ['order_id' => $notification['order_id']]);
        
        return $transaction;
    }
}