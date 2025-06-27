<?php 

namespace App\Repository;

use App\Models\Chapter;

interface ChapterRepositoryInterface
{
    public function getBySeries(string $seriesSlug, array $filters = []);
    public function getChapter(string $seriesSlug, string $chapterSlug);
    public function recordView(string $chapterId, string $userId = null);
    public function getNextChapter(string $seriesId, int $currentChapterNumber);
    public function getPrevChapter(string $seriesId, int $currentChapterNumber);
}