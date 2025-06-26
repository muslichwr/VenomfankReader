<?php

namespace App\Observers;

use App\Models\Transaction;
use App\Models\CoinPackage;
use App\Helpers\TransactionHelper;

class TransactionObserver
{
    /**
     * Handle the Transaction "creating" event.
     * Note: Payment code generation is now handled in the model boot method
     */
    public function creating(Transaction $transaction): void
    {
        // Auto-populate coins_received and amount_paid if not set
        if ($transaction->coin_package_id && 
            (!$transaction->coins_received || !$transaction->amount_paid)) {
            $coinPackage = CoinPackage::find($transaction->coin_package_id);
            if ($coinPackage) {
                $transaction->coins_received = $transaction->coins_received ?: $coinPackage->coin_amount;
                $transaction->amount_paid = $transaction->amount_paid ?: $coinPackage->price;
            }
        }
    }

    /**
     * Handle the Transaction "created" event.
     */
    public function created(Transaction $transaction): void
    {
        // If transaction is created with 'completed' status, add coins to user
        if ($transaction->payment_status === Transaction::STATUS_COMPLETED) {
            $transaction->user->addCoins($transaction->coins_received);
        }
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        // Check if payment_status has changed
        if ($transaction->isDirty('payment_status')) {
            $originalStatus = $transaction->getOriginal('payment_status');
            $currentStatus = $transaction->payment_status;
            
            // If status changed to 'completed' from something else, handle coins
            if ($currentStatus === Transaction::STATUS_COMPLETED && $originalStatus !== Transaction::STATUS_COMPLETED) {
                $transaction->user->addCoins($transaction->coins_received);
            }
            
            // If status changed from 'completed' to 'refunded', handle coin removal
            if ($currentStatus === Transaction::STATUS_REFUNDED && $originalStatus === Transaction::STATUS_COMPLETED) {
                $transaction->user->subtractCoins($transaction->coins_received);
            }
        }
    }

    /**
     * Handle the Transaction "deleted" event.
     */
    public function deleted(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "restored" event.
     */
    public function restored(Transaction $transaction): void
    {
        //
    }

    /**
     * Handle the Transaction "force deleted" event.
     */
    public function forceDeleted(Transaction $transaction): void
    {
        //
    }
}
