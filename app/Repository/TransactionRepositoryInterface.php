<?php 

namespace App\Repository;

use App\Models\Transaction;

interface TransactionRepositoryInterface
{
    public function findByTransactionId(string $transactionId);

    public function create(array $data);

    public function getUserTransaction(int $userId);
}
