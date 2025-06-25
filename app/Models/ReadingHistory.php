<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReadingHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'series_id',
        'chapter_id',
        'last_read_at',
        'progress_percentage',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'progress_percentage' => 'integer',
    ];

    /**
     * Get the user that owns the reading history.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the series for this reading history.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Get the chapter for this reading history.
     */
    public function chapter(): BelongsTo
    {
        return $this->belongsTo(Chapter::class);
    }

    /**
     * Update the last read time to now.
     */
    public function markAsRead(int $progress = 100): void
    {
        $this->update([
            'last_read_at' => now(),
            'progress_percentage' => $progress,
        ]);
    }
    
    /**
     * Check if the chapter has been completely read.
     */
    public function isCompleted(): bool
    {
        return $this->progress_percentage >= 100;
    }
    
    /**
     * Check if the reading history is recent (within the last 30 days).
     */
    public function isRecent(): bool
    {
        return $this->last_read_at->gt(now()->subDays(30));
    }
    
    /**
     * Get the next chapter to read in the series.
     */
    public function getNextChapterToRead()
    {
        $currentChapter = $this->chapter;
        return $currentChapter ? $currentChapter->nextChapter() : null;
    }
    
    /**
     * Static method to get or create a reading history record.
     * 
     * @param int|string $userId
     * @param int|string $seriesId
     * @param int|string $chapterId
     * @return ReadingHistory
     */
    public static function getOrCreate($userId, $seriesId, $chapterId): self
    {
        return self::firstOrCreate(
            [
                'user_id' => $userId,
                'series_id' => $seriesId,
                'chapter_id' => $chapterId,
            ],
            [
                'last_read_at' => now(),
                'progress_percentage' => 0,
            ]
        );
    }
    
    /**
     * Static method to get all series in user reading history, ordered by last read.
     * 
     * @param int|string $userId
     * @param int $limit
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getRecentlyReadSeries($userId, $limit = 10)
    {
        return self::where('user_id', $userId)
            ->select('series_id')
            ->distinct()
            ->orderBy('last_read_at', 'desc')
            ->limit($limit)
            ->with('series')
            ->get()
            ->pluck('series');
    }
}
