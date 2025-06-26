<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CoinPackageResource\Pages;
use App\Filament\Resources\CoinPackageResource\RelationManagers;
use App\Models\CoinPackage;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CoinPackageResource extends Resource
{
    protected static ?string $model = CoinPackage::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    
    protected static ?string $navigationGroup = 'Payment Management';
    
    protected static ?int $navigationSort = 1;
    
    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Package Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('coin_amount')
                            ->label('Coin Amount')
                            ->required()
                            ->numeric()
                            ->step(1),
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->prefix('Rp'),
                    ])->columns(3),
                
                Forms\Components\Section::make('Package Settings')
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label('Active')
                            ->helperText('Only active packages will be shown to users')
                            ->default(true),
                        Forms\Components\Toggle::make('is_featured')
                            ->label('Featured')
                            ->helperText('Featured packages will be highlighted to users')
                            ->default(false),
                    ])->columns(2),
                
                Forms\Components\Section::make('Package Description')
                    ->schema([
                        Forms\Components\Textarea::make('description')
                            ->nullable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('coin_amount')
                    ->badge()
                    ->color('warning')
                    ->icon('heroicon-o-currency-dollar')
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->money('IDR')
                    ->sortable(),
                Tables\Columns\TextColumn::make('value_ratio')
                    ->label('Value (Coins/$)')
                    ->state(fn (CoinPackage $record): float => $record->getCoinsPerCurrencyRatio())
                    ->numeric()
                    ->sortable(query: fn (Builder $query, string $direction): Builder => $query->orderByRaw('coin_amount / price ' . $direction)),
                Tables\Columns\IconColumn::make('is_best_value')
                    ->label('Best Value')
                    ->boolean()
                    ->state(fn (CoinPackage $record): bool => $record->isBestValue()),
                Tables\Columns\IconColumn::make('is_active')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_featured')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('transactions_count')
                    ->counts('transactions')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\Filter::make('is_active')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_active', true))
                    ->label('Active Only'),
                Tables\Filters\Filter::make('is_featured')
                    ->toggle()
                    ->query(fn (Builder $query): Builder => $query->where('is_featured', true))
                    ->label('Featured Only'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('duplicate')
                    ->icon('heroicon-o-document-duplicate')
                    ->action(function (CoinPackage $record) {
                        $newPackage = $record->replicate();
                        $newPackage->name = $record->name . ' (Copy)';
                        $newPackage->is_featured = false;
                        $newPackage->save();
                    }),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleActive')
                        ->label('Toggle Active Status')
                        ->icon('heroicon-o-power')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_active' => !$record->is_active]);
                            }
                        }),
                ]),
            ]);
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
            'index' => Pages\ListCoinPackages::route('/'),
            'create' => Pages\CreateCoinPackage::route('/create'),
            'edit' => Pages\EditCoinPackage::route('/{record}/edit'),
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
