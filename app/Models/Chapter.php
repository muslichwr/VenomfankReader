<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'series_id',
        'title',
        'slug',
        'chapter_number',
        'content',
        'is_free',
        'coin_cost',
        'views_count',
    ];

    protected $casts = [
        'is_free' => 'boolean',
        'coin_cost' => 'integer',
        'chapter_number' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            
            if (empty($model->slug)) {
                $model->slug = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the series that owns the chapter.
     */
    public function series(): BelongsTo
    {
        return $this->belongsTo(Series::class);
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('views_count');
    }

    /**
     * Check if the chapter requires payment.
     */
    public function requiresPayment(): bool
    {
        return !$this->is_free && $this->coin_cost > 0;
    }
    
    /**
     * Get the next chapter in the series.
     */
    public function nextChapter()
    {
        return static::where('series_id', $this->series_id)
            ->where('chapter_number', '>', $this->chapter_number)
            ->orderBy('chapter_number')
            ->first();
    }
    
    /**
     * Get the previous chapter in the series.
     */
    public function previousChapter()
    {
        return static::where('series_id', $this->series_id)
            ->where('chapter_number', '<', $this->chapter_number)
            ->orderBy('chapter_number', 'desc')
            ->first();
    }
    
    /**
     * Check if this is the first chapter of the series.
     */
    public function isFirstChapter(): bool
    {
        return !static::where('series_id', $this->series_id)
            ->where('chapter_number', '<', $this->chapter_number)
            ->exists();
    }
    
    /**
     * Check if this is the last chapter of the series.
     */
    public function isLastChapter(): bool
    {
        return !static::where('series_id', $this->series_id)
            ->where('chapter_number', '>', $this->chapter_number)
            ->exists();
    }
    
    /**
     * Get chapter content based on type.
     * For manga/manhwa/manhua, the content field stores JSON with image paths.
     * For novels, it stores the actual text content.
     * 
     * @return array|string Content as array of images or text
     */
    public function getProcessedContent()
    {
        $seriesType = $this->series->type;
        
        // If the series is a novel, return content as text
        if ($seriesType === 'novel') {
            return $this->content;
        }
        
        // Otherwise, assume it's manga/manhwa/manhua and parse JSON
        try {
            $images = json_decode($this->content, true);
            return is_array($images) ? $images : [];
        } catch (\Exception $e) {
            // If parsing fails, return empty array
            return [];
        }
    }
}
