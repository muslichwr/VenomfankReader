<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CoinPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'coin_amount',
        'price',
        'description',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'coin_amount' => 'integer',
        'price' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
    ];

    /**
     * Get the transactions for this coin package.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
    
    /**
     * Get the coin to price ratio.
     * 
     * @return float Number of coins per currency unit
     */
    public function getCoinsPerCurrencyRatio(): float
    {
        return $this->price > 0 ? $this->coin_amount / $this->price : 0;
    }
    
    /**
     * Check if this package is the best value (highest coins per currency unit).
     */
    public function isBestValue(): bool
    {
        $ratio = $this->getCoinsPerCurrencyRatio();
        
        $hasBetterValue = static::where('is_active', true)
            ->where(function ($query) {
                $query->whereRaw('coin_amount / price > ?', [$this->getCoinsPerCurrencyRatio()])
                    ->orWhere(function ($q) {
                        $q->whereRaw('coin_amount / price = ?', [$this->getCoinsPerCurrencyRatio()])
                            ->where('price', '<', $this->price);
                    });
            })
            ->exists();
            
        return !$hasBetterValue && $ratio > 0;
    }
    
    /**
     * Get all active packages ordered by price.
     */
    public static function getActive()
    {
        return static::where('is_active', true)
            ->orderBy('price')
            ->get();
    }
    
    /**
     * Get featured packages.
     */
    public static function getFeatured()
    {
        return static::where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('price')
            ->get();
    }
    
    /**
     * Get the recommended package for a given coin amount.
     * Returns the cheapest package that provides at least the specified coin amount.
     * 
     * @param int $requiredCoins
     * @return CoinPackage|null
     */
    public static function getRecommendedPackage(int $requiredCoins)
    {
        return static::where('is_active', true)
            ->where('coin_amount', '>=', $requiredCoins)
            ->orderBy('price')
            ->first();
    }
}
