<?php

namespace App\Filament\Resources\ChapterResource\Pages;

use App\Filament\Resources\ChapterResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;

class CreateChapter extends CreateRecord
{
    protected static string $resource = ChapterResource::class;
    
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['slug'] = Str::uuid();
        
        // Handle content based on series type
        $seriesId = $data['series_id'];
        $series = \App\Models\Series::find($seriesId);
        
        if ($series && $series->type !== 'novel') {
            // For manga/manhwa/manhua, encode the image array as JSON
            if (isset($data['images']) && is_array($data['images'])) {
                $data['content'] = json_encode($data['images']);
            } else {
                $data['content'] = json_encode([]);
            }
        }
        
        return $data;
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
} 