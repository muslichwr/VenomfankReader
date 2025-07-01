<?php 

namespace App\Repository;

use App\Models\Series;
use Illuminate\Database\Eloquent\Builder;

class SeriesRepository implements SeriesRepositoryInterface
{
    public function getAllWithLatestChapter(array $filters = [], int $perPage = 10)
    {
        $query = Series::with(['chapters' => function($query) {
                $query->orderBy('created_at', 'desc')->limit(5);
            }])
            ->withCount('chapters')
            ->withMax('chapters as latest_chapter_date', 'created_at');
            
        // Apply filters
        $query->when(isset($filters['is_featured']), fn($q) => $q->where('is_featured', true))
             ->when(isset($filters['is_popular']), fn($q) => $q->where('is_popular', true))
             ->when(isset($filters['min_views']), fn($q) => $q->where('views_count', '>=', $filters['min_views']))
             ->when(isset($filters['type']), fn($q) => $q->where('type', $filters['type']));
        
        // Apply sorting
        if (isset($filters['sort_by'])) {
            $query->when($filters['sort_by'] === 'latest', fn($q) => $q->orderBy('latest_chapter_date', 'desc'))
                  ->when($filters['sort_by'] === 'newest', fn($q) => $q->orderBy('created_at', 'desc'))
                  ->when($filters['sort_by'] === 'oldest', fn($q) => $q->orderBy('created_at', 'asc'))
                  ->when($filters['sort_by'] === 'views', fn($q) => $q->orderBy('views_count', 'desc'));
        } else {
            // Default sort by latest chapter update
            $query->orderBy('latest_chapter_date', 'desc');
        }
        
        return $query->paginate($perPage);
    }

    public function getBySlugWithChapters(string $slug, string $sort = 'newest')
    {
        $query = Series::with(['chapters' => function($q) use ($sort) {
            if ($sort === 'oldest') {
                $q->orderBy('chapter_number', 'asc');
            } else {
                $q->orderBy('chapter_number', 'desc');
            }
        }]);
        
        return $query->where('slug', $slug)->firstOrFail();
    }

    public function incrementViewCount(string $slug)
    {
        Series::where('slug', $slug)->increment('views_count');
    }
}
