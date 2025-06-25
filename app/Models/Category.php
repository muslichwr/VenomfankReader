<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
    ];

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    /**
     * Get the series for this category.
     */
    public function series(): BelongsToMany
    {
        return $this->belongsToMany(Series::class, 'category_series');
    }
    
    /**
     * Get the count of series in this category.
     */
    public function getSeriesCountAttribute(): int
    {
        return $this->series()->count();
    }
    
    /**
     * Get only active series in this category (that have chapters).
     */
    public function activeSeries()
    {
        return $this->series()->whereHas('chapters')->orderBy('title');
    }
    
    /**
     * Get popular series in this category.
     */
    public function popularSeries($limit = 10)
    {
        return $this->series()
            ->whereHas('chapters')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get the featured series in this category.
     */
    public function featuredSeries($limit = 5)
    {
        return $this->series()
            ->where('is_featured', true)
            ->whereHas('chapters')
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get series with recent chapters in this category.
     */
    public function recentlySeries($limit = 10)
    {
        return $this->series()
            ->whereHas('chapters', function($query) {
                $query->orderBy('created_at', 'desc');
            })
            ->orderBy('updated_at', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Static method to get popular categories (those with most series).
     */
    public static function getPopularCategories($limit = 10)
    {
        return static::withCount('series')
            ->orderBy('series_count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Check if the slug exists and generate a unique one if needed.
     */
    public static function generateUniqueSlug(string $name, ?int $exceptId = null): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $count = 1;
        
        $query = static::where('slug', $slug);
        if ($exceptId !== null) {
            $query->where('id', '!=', $exceptId);
        }
        
        while ($query->exists()) {
            $slug = $originalSlug . '-' . $count++;
            $query = static::where('slug', $slug);
            if ($exceptId !== null) {
                $query->where('id', '!=', $exceptId);
            }
        }
        
        return $slug;
    }
}
