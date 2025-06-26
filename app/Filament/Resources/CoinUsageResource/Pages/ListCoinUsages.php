<?php

namespace App\Filament\Resources\CoinUsageResource\Pages;

use App\Filament\Resources\CoinUsageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoinUsages extends ListRecords
{
    protected static string $resource = CoinUsageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
} 