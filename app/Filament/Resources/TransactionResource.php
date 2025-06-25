<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransactionResource extends Resource
{
    protected static ?string $model = Transaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    
    protected static ?string $navigationGroup = 'Payment Management';
    
    protected static ?int $navigationSort = 2;
    
    public static function getNavigationBadge(): ?string
    {
        return static::getEloquentQuery()->where('payment_status', Transaction::STATUS_PENDING)->count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Transaction Details')
                    ->schema([
                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->required()
                            ->searchable(),
                        Forms\Components\Select::make('coin_package_id')
                            ->relationship('coinPackage', 'name')
                            ->required(),
                        Forms\Components\TextInput::make('coins_received')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->default(0),
                        Forms\Components\TextInput::make('amount_paid')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->minValue(0)
                            ->default(0),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Payment Status')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                Transaction::METHOD_PAYPAL => 'PayPal',
                                Transaction::METHOD_STRIPE => 'Stripe',
                                Transaction::METHOD_BANK_TRANSFER => 'Bank Transfer',
                                Transaction::METHOD_ADMIN_ADJUSTMENT => 'Admin Adjustment',
                            ])
                            ->default(Transaction::METHOD_ADMIN_ADJUSTMENT)
                            ->required(),
                        Forms\Components\Select::make('payment_status')
                            ->options([
                                Transaction::STATUS_PENDING => 'Pending',
                                Transaction::STATUS_COMPLETED => 'Completed',
                                Transaction::STATUS_FAILED => 'Failed',
                                Transaction::STATUS_REFUNDED => 'Refunded',
                            ])
                            ->default(Transaction::STATUS_PENDING)
                            ->required(),
                    ])->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coinPackage.name')
                    ->label('Package')
                    ->sortable(),
                Tables\Columns\TextColumn::make('coins_received')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-coin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('USD')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Transaction::METHOD_PAYPAL => 'PayPal',
                        Transaction::METHOD_STRIPE => 'Stripe',
                        Transaction::METHOD_BANK_TRANSFER => 'Bank Transfer',
                        Transaction::METHOD_ADMIN_ADJUSTMENT => 'Admin Adjustment',
                        default => $state,
                    }),
                Tables\Columns\TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        Transaction::STATUS_COMPLETED => 'success',
                        Transaction::STATUS_PENDING => 'warning',
                        Transaction::STATUS_FAILED => 'danger',
                        Transaction::STATUS_REFUNDED => 'info',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst($state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('payment_status')
                    ->options([
                        Transaction::STATUS_PENDING => 'Pending',
                        Transaction::STATUS_COMPLETED => 'Completed',
                        Transaction::STATUS_FAILED => 'Failed',
                        Transaction::STATUS_REFUNDED => 'Refunded',
                    ]),
                Tables\Filters\SelectFilter::make('payment_method')
                    ->options([
                        Transaction::METHOD_PAYPAL => 'PayPal',
                        Transaction::METHOD_STRIPE => 'Stripe',
                        Transaction::METHOD_BANK_TRANSFER => 'Bank Transfer',
                        Transaction::METHOD_ADMIN_ADJUSTMENT => 'Admin Adjustment',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('markCompleted')
                    ->label('Mark Completed')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Transaction $record) => $record->payment_status === Transaction::STATUS_PENDING)
                    ->action(fn (Transaction $record) => $record->markAsCompleted()),
                Tables\Actions\Action::make('markFailed')
                    ->label('Mark Failed')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (Transaction $record) => $record->payment_status === Transaction::STATUS_PENDING)
                    ->action(fn (Transaction $record) => $record->markAsFailed()),
                Tables\Actions\Action::make('markRefunded')
                    ->label('Mark Refunded')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->visible(fn (Transaction $record) => $record->payment_status === Transaction::STATUS_COMPLETED)
                    ->action(fn (Transaction $record) => $record->markAsRefunded()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('markCompletedBulk')
                        ->label('Mark Completed')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                if ($record->payment_status === Transaction::STATUS_PENDING) {
                                    $record->markAsCompleted();
                                }
                            }
                        }),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransactions::route('/'),
            'create' => Pages\CreateTransaction::route('/create'),
            'edit' => Pages\EditTransaction::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
