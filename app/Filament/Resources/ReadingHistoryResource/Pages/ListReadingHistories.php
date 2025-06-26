<?php

namespace App\Filament\Resources\ReadingHistoryResource\Pages;

use App\Filament\Resources\ReadingHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReadingHistories extends ListRecords
{
    protected static string $resource = ReadingHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 