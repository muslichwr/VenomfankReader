<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ReadingHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'readingHistories';

    protected static ?string $recordTitleAttribute = 'id';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('series_id')
                    ->relationship('series', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('chapter_id')
                    ->relationship('chapter', 'title')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\DateTimePicker::make('last_read_at')
                    ->required(),
                Forms\Components\TextInput::make('progress_percentage')
                    ->numeric()
                    ->minValue(0)
                    ->maxValue(100)
                    ->default(0)
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series.title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('chapter.title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('progress_percentage')
                    ->numeric()
                    ->sortable()
                    ->suffix('%'),
                Tables\Columns\TextColumn::make('last_read_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('completed')
                    ->query(fn ($query) => $query->where('progress_percentage', 100))
                    ->toggle(),
                Tables\Filters\Filter::make('recent')
                    ->query(fn ($query) => $query->where('last_read_at', '>=', now()->subDays(30)))
                    ->toggle(),
            ])
            ->defaultSort('last_read_at', 'desc')
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
} 