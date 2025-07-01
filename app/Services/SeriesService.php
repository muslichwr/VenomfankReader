<?php 

namespace App\Services;

use App\Models\Series;
use App\Repository\SeriesRepositoryInterface;

class SeriesService
{
    private SeriesRepositoryInterface $seriesRepo;

    public function __construct(SeriesRepositoryInterface $seriesRepo)
    {
        $this->seriesRepo = $seriesRepo;
    }

    public function getPaginatedSeries(array $filters = [], int $perPage = 10)
    {
        // If no specific sort is requested, default to latest chapter updates
        if (!isset($filters['sort_by'])) {
            $filters['sort_by'] = 'latest';
        }
        
        return $this->seriesRepo->getAllWithLatestChapter($filters, $perPage);
    }

    public function getSeriesDetail(string $slug, string $sort = 'newest')
    {
        $series = $this->seriesRepo->getBySlugWithChapters($slug, $sort);
        $this->seriesRepo->incrementViewCount($slug);
        return $series;
    }

    public function getFeaturedSeries(int $limit = 5)
    {
        return $this->seriesRepo->getAllWithLatestChapter(
            ['is_featured' => true],
            $limit
        )->getCollection();
    }

    public function getPopularSeries(int $limit = 5)
    {
        return $this->seriesRepo->getAllWithLatestChapter(
            ['is_popular' => true, 'min_views' => 500],
            $limit
        )->getCollection();
    }
}