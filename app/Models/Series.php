<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Series extends Model
{
    /** @use HasFactory<\Database\Factories\SeriesFactory> */
    use HasFactory, SoftDeletes;

    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'cover_image',
        'type',
        'status',
        'author',
        'is_popular',
        'is_featured',
        'views_count',
        'free_chapters',
    ];

    protected $casts = [
        'is_popular' => 'boolean',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'free_chapters' => 'integer',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = (string) Str::uuid();
            }
            
            if (empty($model->slug)) {
                $model->slug = (string) Str::uuid();
            }
        });
    }

    /**
     * Get the categories for this series.
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_series');
    }

    /**
     * Get the chapters for this series.
     */
    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->orderBy('chapter_number');
    }

    /**
     * Get the free chapters for this series.
     */
    public function freeChapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->where('is_free', true)->orderBy('chapter_number');
    }

    /**
     * Get the paid chapters for this series.
     */
    public function paidChapters(): HasMany
    {
        return $this->hasMany(Chapter::class)->where('is_free', false)->orderBy('chapter_number');
    }

    /**
     * Increment the view count.
     */
    public function incrementViewCount(): void
    {
        $this->increment('views_count');
    }
    
    /**
     * Check if the series has any chapters.
     */
    public function hasChapters(): bool
    {
        return $this->chapters()->exists();
    }
    
    /**
     * Get the first chapter of the series.
     */
    public function firstChapter()
    {
        return $this->chapters()->orderBy('chapter_number')->first();
    }
    
    /**
     * Get the latest chapter of the series.
     */
    public function latestChapter()
    {
        return $this->chapters()->orderBy('chapter_number', 'desc')->first();
    }
    
    /**
     * Get the chapter count for this series.
     */
    public function getChapterCountAttribute(): int
    {
        return $this->chapters()->count();
    }
    
    /**
     * Get the free chapter count for this series.
     */
    public function getFreeChapterCountAttribute(): int
    {
        return $this->freeChapters()->count();
    }
    
    /**
     * Get the paid chapter count for this series.
     */
    public function getPaidChapterCountAttribute(): int
    {
        return $this->paidChapters()->count();
    }
    
    /**
     * Get related series based on shared categories.
     * 
     * @param int $limit Number of related series to return
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRelatedSeries(int $limit = 5)
    {
        $categoryIds = $this->categories()->pluck('categories.id');
        
        return self::whereHas('categories', function ($query) use ($categoryIds) {
                $query->whereIn('categories.id', $categoryIds);
            })
            ->where('id', '!=', $this->id)
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
