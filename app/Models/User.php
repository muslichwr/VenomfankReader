<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'coin_balance',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'coin_balance' => 'integer',
        ];
    }

    /**
     * Get the transactions for this user.
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the coin usages for this user.
     */
    public function coinUsages(): HasMany
    {
        return $this->hasMany(CoinUsage::class);
    }

    /**
     * Get the reading histories for this user.
     */
    public function readingHistories(): HasMany
    {
        return $this->hasMany(ReadingHistory::class);
    }

    /**
     * Add coins to the user's balance.
     */
    public function addCoins(int $amount): void
    {
        $this->increment('coin_balance', $amount);
    }

    /**
     * Subtract coins from the user's balance.
     */
    public function subtractCoins(int $amount): bool
    {
        if ($this->coin_balance < $amount) {
            return false;
        }

        $this->decrement('coin_balance', $amount);
        return true;
    }

    /**
     * Check if the user has purchased access to a chapter.
     */
    public function hasAccessToChapter(Chapter $chapter): bool
    {
        if ($chapter->is_free) {
            return true;
        }

        return $this->coinUsages()
            ->where('chapter_id', $chapter->id)
            ->exists();
    }

    /**
     * Check if the user can afford to purchase a chapter.
     */
    public function canAffordChapter(Chapter $chapter): bool
    {
        if ($chapter->is_free) {
            return true;
        }
        
        return $this->coin_balance >= $chapter->coin_cost;
    }

    /**
     * Purchase permanent access to a chapter.
     * 
     * @param Chapter $chapter The chapter to purchase
     * @return bool Whether the purchase was successful
     */
    public function purchaseChapter(Chapter $chapter): bool
    {
        // If the chapter is free or already purchased, no need to purchase
        if ($chapter->is_free || $this->hasAccessToChapter($chapter)) {
            return true;
        }
        
        // Check if user has enough coins
        if (!$this->canAffordChapter($chapter)) {
            return false;
        }
        
        // Subtract coins
        if (!$this->subtractCoins($chapter->coin_cost)) {
            return false;
        }
        
        // Create coin usage record with permanent access
        $this->coinUsages()->create([
            'chapter_id' => $chapter->id,
            'coins_spent' => $chapter->coin_cost,
        ]);
        
        return true;
    }
}
