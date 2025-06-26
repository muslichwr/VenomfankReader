<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use App\Models\Transaction;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditTransaction extends EditRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function afterSave(): void
    {
        parent::afterSave();
        
        // Get the original and current payment status
        $originalStatus = $this->record->getOriginal('payment_status');
        $currentStatus = $this->record->payment_status;
        
        // If status changed to 'completed' from something else, handle coins
        if ($currentStatus === Transaction::STATUS_COMPLETED && $originalStatus !== Transaction::STATUS_COMPLETED) {
            $this->record->user->addCoins($this->record->coins_received);
            
            Notification::make()
                ->title("{$this->record->coins_received} coins added to {$this->record->user->name}'s account")
                ->success()
                ->send();
        }
        
        // If status changed from 'completed' to 'refunded', handle coin removal
        if ($currentStatus === Transaction::STATUS_REFUNDED && $originalStatus === Transaction::STATUS_COMPLETED) {
            $coinRemoved = $this->record->user->subtractCoins($this->record->coins_received);
            
            $message = $coinRemoved 
                ? "{$this->record->coins_received} coins removed from {$this->record->user->name}'s account" 
                : "Could not remove coins from {$this->record->user->name}'s account (insufficient balance)";
                
            Notification::make()
                ->title($message)
                ->color($coinRemoved ? 'success' : 'warning')
                ->send();
        }
    }
}
