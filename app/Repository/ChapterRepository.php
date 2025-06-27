<?php 

namespace App\Repository;

use App\Models\Chapter;
use App\Models\ReadingHistory;

class ChapterRepository implements ChapterRepositoryInterface
{
    public function getBySeries(string $seriesSlug, array $filters = [])
    {
        return Chapter::whereHas('series', fn($q) => $q->where('slug', $seriesSlug))
            ->when(isset($filters['search']), fn($q) => $q->where('title', 'LIKE', "%{$filters['search']}%"))
            ->orderBy('number', 'desc')
            ->paginate(config('app.per_page'));
    }

    public function getChapter(string $seriesSlug, string $chapterSlug)
    {
        return Chapter::whereHas('series', fn($q) => $q->where('slug', $seriesSlug))
            ->where('slug', $chapterSlug)
            ->firstOrFail();
    }
    
    public function recordView(string $chapterId, string $userId = null)
    {
        // Increment chapter view count
        $chapter = Chapter::findOrFail($chapterId);
        $chapter->incrementViewCount();
        
        // If logged in, record in reading history
        if ($userId) {
            ReadingHistory::getOrCreate(
                $userId, 
                $chapter->series_id, 
                $chapter->id
            )->markAsRead();
        }
        
        return $chapter;
    }
    
    public function getNextChapter(string $seriesId, int $currentChapterNumber)
    {
        return Chapter::where('series_id', $seriesId)
            ->where('chapter_number', '>', $currentChapterNumber)
            ->orderBy('chapter_number')
            ->first();
    }
    
    public function getPrevChapter(string $seriesId, int $currentChapterNumber)
    {
        return Chapter::where('series_id', $seriesId)
            ->where('chapter_number', '<', $currentChapterNumber)
            ->orderBy('chapter_number', 'desc')
            ->first();
    }
}