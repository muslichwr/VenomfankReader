<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateTransaction extends CreateRecord
{
    protected static string $resource = TransactionResource::class;
    
    // Koin sudah ditambahkan oleh TransactionObserver, jadi kita tidak perlu menambahkannya di sini
    // Kita hanya perlu menampilkan notifikasi jika transaksi selesai
    protected function afterCreate(): void
    {
        // parent::afterCreate();
        
        // Hanya tampilkan notifikasi jika transaksi berstatus 'completed'
        if ($this->record->payment_status === Transaction::STATUS_COMPLETED) {
            Notification::make()
                ->title("{$this->record->coins_received} coins added to {$this->record->user->name}'s account")
                ->success()
                ->send();
        }
    }
}
