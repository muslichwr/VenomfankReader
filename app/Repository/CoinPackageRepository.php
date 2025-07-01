<?php 

namespace App\Repository;

use App\Models\CoinPackage;
use Illuminate\Support\Collection;
class CoinPackageRepository implements CoinPackageRepositoryInterface
{
    public function findById(int $id): ?CoinPackage
    {
        return CoinPackage::find($id);
    }

    public function getAll(): Collection
    {
        return CoinPackage::all();
    }
}
