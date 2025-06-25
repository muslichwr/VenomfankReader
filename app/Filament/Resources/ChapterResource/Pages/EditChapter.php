<?php

namespace App\Filament\Resources\ChapterResource\Pages;

use App\Filament\Resources\ChapterResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditChapter extends EditRecord
{
    protected static string $resource = ChapterResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
    
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $record = $this->getRecord();
        
        if ($record->series->type !== 'novel' && !empty($record->content)) {
            try {
                $images = json_decode($record->content, true);
                if (is_array($images)) {
                    $data['images'] = $images;
                }
            } catch (\Exception $e) {
                // If parsing fails, leave as is
            }
        }
        
        return $data;
    }
    
    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = $this->getRecord();
        
        if ($record->series->type !== 'novel') {
            // For manga/manhwa/manhua, encode the image array as JSON
            if (isset($data['images']) && is_array($data['images'])) {
                $data['content'] = json_encode($data['images']);
            } else if (!isset($data['images']) && !empty($record->content)) {
                // If no new images were uploaded, keep the existing ones
                $data['content'] = $record->content;
            } else {
                $data['content'] = json_encode([]);
            }
        }
        
        return $data;
    }
} 