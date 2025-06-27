<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class CoinUsage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'chapter_id',
        'coins_spent',
    ];

    protected $casts = [
        'coins_spent' => 'integer',
    ];

    /**
     * Boot function from Laravel.
     */
    /**
     * Get the user that owns the coin usage.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the chapter for this coin usage.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Static method to find an existing coin usage or null.
     * 
     * @param int|string $userId
     * @param int|string $chapterId
     * @return CoinUsage|null
     */
    public static function findExisting($userId, $chapterId)
    {
        return self::where('user_id', $userId)
            ->where('chapter_id', $chapterId)
            ->first();
    }
}
