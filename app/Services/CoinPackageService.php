<?php 

namespace App\Services;

use App\Models\CoinPackage;
use App\Repository\CoinPackageRepository;

class CoinPackageService
{
    protected $coinpackageRepository;

    public function __construct(CoinPackageRepository $coinpackageRepository)
    {
        $this->coinpackageRepository = $coinpackageRepository;
    }

    public function getAllpackage()
    {
        return $this->coinpackageRepository->getAll();
    }
}
