<?php 

namespace App\Repository;

interface SeriesRepositoryInterface
{
    public function getAllWithLatestChapter(array $filters = [], int $perPage = 10);
    public function getBySlugWithChapters(string $slug, string $sort = 'newest');
    public function incrementViewCount(string $slug);
}