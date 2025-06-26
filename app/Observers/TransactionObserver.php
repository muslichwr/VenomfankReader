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
        //
    }

    /**
     * Handle the Transaction "updated" event.
     */
    public function updated(Transaction $transaction): void
    {
        //
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
