<?php

namespace App\Http\Controllers;

use App\Services\ChapterService;
use Illuminate\Http\Request;
use App\Services\SeriesService;
use Illuminate\View\View;

class FrontController extends Controller
{
    protected SeriesService $seriesService;
    protected ChapterService $chapterService;

    public function __construct(SeriesService $seriesService, ChapterService $chapterService)
    {
        $this->seriesService = $seriesService;
        $this->chapterService = $chapterService;
    }

    public function homepage(Request $request): View
    {
        $type = $request->query('type');
        
        $filters = match($type) {
            'featured' => ['is_featured' => true],
            'popular' => ['is_popular' => true, 'min_views' => 1000],
            'manga' => ['type' => 'manga'],
            'novel' => ['type' => 'novel'],
            'manhwa' => ['type' => 'manhwa'],
            'manhua' => ['type' => 'manhua'],
            'latest' => ['sort_by' => 'latest'],
            default => []
        };

        $series = $this->seriesService->getPaginatedSeries($filters);
        $featuredSeries = $this->seriesService->getFeaturedSeries(5);
        $popularSeries = $this->seriesService->getPopularSeries(5);
        
        return view('front.homepage', [
            'series' => $series,
            'featuredSeries' => $featuredSeries,
            'popularSeries' => $popularSeries,
            'activeTab' => $type ?? 'all'
        ]);
    }

    public function show(Request $request, string $slug): View
    {
        $sort = $request->query('sort', 'newest');
        
        // Validate sort parameter
        if (!in_array($sort, ['newest', 'oldest'])) {
            $sort = 'newest';
        }
        
        $series = $this->seriesService->getSeriesDetail($slug, $sort);
        
        return view('front.detail', [
            'series' => $series,
            'chapters' => $series->chapters,
            'freeChaptersCount' => $series->getFreeChapterCountAttribute(),
            'paidChaptersCount' => $series->getPaidChapterCountAttribute(),
            'currentSort' => $sort
        ]);
    }

    public function showChapter(string $chapterSlug): View
    {
        $data = $this->chapterService->getChapterData($chapterSlug);
        return view('front.chapter', $data);
    }
}
