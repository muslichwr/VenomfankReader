<?php 

namespace App\Repository;

use App\Models\Transaction;

class TransactionRepository implements TransactionRepositoryInterface
{
    public function findByTransactionId(string $transactionId)
    {
        return Transaction::where('booking_trx_id', $transactionId)->first();
    }

    public function create(array $data)
    {
        return Transaction::create($data);
    }

    public function getUserTransaction(int $userId)
    {
        return Transaction::with('coinPackage')
        ->where('user_id', $userId)
        ->orderBy('created_at', 'desc')
        ->get();
    }
}
