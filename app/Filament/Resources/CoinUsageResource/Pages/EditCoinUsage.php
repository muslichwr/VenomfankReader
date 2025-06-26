<?php

namespace App\Filament\Resources\CoinUsageResource\Pages;

use App\Filament\Resources\CoinUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCoinUsage extends EditRecord
{
    protected static string $resource = CoinUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\RestoreAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
} 