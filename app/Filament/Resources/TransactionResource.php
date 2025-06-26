<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Filament\Resources\TransactionResource\RelationManagers;
use App\Models\Transaction;
use App\Helpers\TransactionHelper;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
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
                        ->relationship('user', 'email')
                        ->searchable()
                        ->preload()
                        ->required()
                        ->live()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $user = User::find($state);
                            if ($user) {
                                $set('name', $user->name);
                                $set('email', $user->email);
                            }
                        })
                        ->afterStateHydrated(function (callable $set, $state) {
                            $userId = $state;
                            if ($userId) {
                                $user = User::find($userId);
                                if ($user) {
                                    $set('name', $user->name);
                                    $set('email', $user->email);
                                }
                            }
                        }),

                        Forms\Components\TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->readOnly(),

                        Forms\Components\TextInput::make('email')
                        ->required()
                        ->maxLength(255)
                        ->readOnly(),

                        Forms\Components\Select::make('coin_package_id')
                            ->relationship('coinPackage', 'name')
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $coinPackage = \App\Models\CoinPackage::find($state);
                                    if ($coinPackage) {
                                        $set('coins_received', $coinPackage->coin_amount);
                                        $set('amount_paid', $coinPackage->price);
                                    }
                                }
                            })
                            ->afterStateHydrated(function (callable $set, $state) {
                                if ($state) {
                                    $coinPackage = \App\Models\CoinPackage::find($state);
                                    if ($coinPackage) {
                                        $set('coins_received', $coinPackage->coin_amount);
                                        $set('amount_paid', $coinPackage->price);
                                    }
                                }
                            }),
                        Forms\Components\TextInput::make('coins_received')
                            ->required()
                            ->numeric()
                            ->readOnly(),
                        Forms\Components\TextInput::make('amount_paid')
                            ->required()
                            ->numeric()
                            ->prefix('Rp')
                            ->readOnly(),
                    ])->columns(2),
                    
                Forms\Components\Section::make('Payment Status')
                    ->schema([
                        Forms\Components\Select::make('payment_method')
                            ->options([
                                Transaction::METHOD_MIDTRANS => 'Midtrans',
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
                        Forms\Components\TextInput::make('payment_code')
                            ->label('Payment Code')
                            ->helperText('A unique code for transaction identification')
                            ->placeholder('Auto-generated')
                            ->disabled()
                            ->dehydrated(false)
                            ->afterStateHydrated(function (Forms\Components\TextInput $component, $state) {
                                if ($state) {
                                    $component->state(TransactionHelper::formatPaymentCode($state));
                                }
                            }),
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
                Tables\Columns\TextColumn::make('payment_code')
                    ->label('Payment Code')
                    ->formatStateUsing(fn ($state) => $state ? TransactionHelper::formatPaymentCode($state) : '-')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Payment code copied')
                    ->copyable('Copy payment code'),
                Tables\Columns\TextColumn::make('user.name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coinPackage.name')
                    ->label('Package')
                    ->sortable(),
                Tables\Columns\TextColumn::make('coins_received')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount_paid')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('payment_method')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        Transaction::METHOD_MIDTRANS => 'Midtrans',
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
                        Transaction::METHOD_MIDTRANS => 'Midtrans',
                        Transaction::METHOD_ADMIN_ADJUSTMENT => 'Admin Adjustment',
                    ]),
                Tables\Filters\Filter::make('payment_code')
                    ->form([
                        Forms\Components\TextInput::make('payment_code')
                            ->label('Payment Code')
                            ->placeholder('Enter payment code'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['payment_code'],
                            fn (Builder $query, $code): Builder => $query->where('payment_code', 'like', '%' . $code . '%')
                        );
                    }),
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
                Tables\Actions\Action::make('viewPaymentCode')
                    ->label('View Code')
                    ->icon('heroicon-o-qr-code')
                    ->color('gray')
                    ->visible(fn (Transaction $record) => !empty($record->payment_code))
                    ->modalHeading('Payment Code')
                    ->modalDescription('Use this code to reference this transaction.')
                    ->modalContent(fn (Transaction $record): string => view(
                        'filament.resources.transaction-resource.payment-code-modal',
                        ['code' => TransactionHelper::formatPaymentCode($record->payment_code)]
                    )->render()),
                Tables\Actions\Action::make('regeneratePaymentCode')
                    ->label('Regenerate Code')
                    ->icon('heroicon-o-arrow-path')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalHeading('Regenerate Payment Code')
                    ->modalDescription('Are you sure you want to generate a new payment code? The old code will no longer be valid.')
                    ->modalSubmitActionLabel('Yes, Regenerate')
                    ->action(function (Transaction $record) {
                        $record->generatePaymentCode();
                        
                        Notification::make()
                            ->title('Payment code regenerated')
                            ->success()
                            ->send();
                    }),
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
