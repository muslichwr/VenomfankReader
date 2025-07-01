<?php 

namespace App\Services;

use App\Models\Transaction;
use App\Repository\CoinPackageRepositoryInterface;
use App\Repository\TransactionRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class TransactionService
{
    protected $coinpackageRepository;
    protected $transactionRepository;

    public function __construct(CoinPackageRepositoryInterface $coinpackageRepository, TransactionRepositoryInterface $transactionRepository)
    {
        $this->coinpackageRepository = $coinpackageRepository;
        $this->transactionRepository = $transactionRepository;
    }

    public function getRecentCoinPackage()
    {
        $coinpackageId = session()->get('coin_package_id');

        return $this->coinpackageRepository->findById($coinpackageId);
    }

    public function getUserTransaction()
    {
        $user = Auth::user();

        return $this->transactionRepository->getUserTransaction($user->id);
    }
    
}
