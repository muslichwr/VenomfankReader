<?php 

namespace App\Repository;

use App\Models\CoinPackage;
use Illuminate\Support\Collection;

interface CoinPackageRepositoryInterface
{
    public function findById(int $id): ?CoinPackage;

    public function getAll(): Collection;
}