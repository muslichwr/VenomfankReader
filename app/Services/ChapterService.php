<?php 

namespace App\Services;

use App\Models\Chapter;
use App\Repository\ChapterRepositoryInterface;
use Illuminate\Support\Facades\Auth;

class ChapterService
{
    private ChapterRepositoryInterface $chapterRepo;

    public function __construct(ChapterRepositoryInterface $chapterRepo)
    {
        $this->chapterRepo = $chapterRepo;
    }

    public function getChapterData(string $chapterSlug)
    {
        // Get the chapter with its relationship to series included
        $chapter = Chapter::with('series')
            ->where('slug', $chapterSlug)
            ->firstOrFail();
        
        // Record this view
        $userId = Auth::id();
        $this->chapterRepo->recordView($chapter->id, $userId);
        
        // Get the next and previous chapters
        $nextChapter = $this->chapterRepo->getNextChapter(
            $chapter->series_id, 
            $chapter->chapter_number
        );
        
        $prevChapter = $this->chapterRepo->getPrevChapter(
            $chapter->series_id, 
            $chapter->chapter_number
        );
        
        return [
            'chapter' => $chapter,
            'series' => $chapter->series,
            'next' => $nextChapter,
            'prev' => $prevChapter,
            'contentType' => $chapter->series->type, // Pass the content type to the view
        ];
    }
}