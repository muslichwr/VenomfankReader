<?php

namespace App\Filament\Resources\ReadingHistoryResource\Pages;

use App\Filament\Resources\ReadingHistoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReadingHistory extends EditRecord
{
    protected static string $resource = ReadingHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
} 