<?php

namespace App\Filament\Resources\SeriesResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChaptersRelationManager extends RelationManager
{
    protected static string $relationship = 'chapters';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('chapter_number')
                    ->numeric()
                    ->required()
                    ->default(fn ($livewire) => $livewire->ownerRecord->chapters()->count() + 1)
                    ->minValue(0)
                    ->step(0.1)
                    ->helperText('Can use decimal (e.g., 1.5 for special chapters)'),
                Forms\Components\Toggle::make('is_free')
                    ->label('Free Chapter')
                    ->default(function ($livewire) {
                        $freeChaptersAllowed = $livewire->ownerRecord->free_chapters;
                        $currentFreeCount = $livewire->ownerRecord->freeChapters()->count();
                        return $currentFreeCount < $freeChaptersAllowed;
                    })
                    ->helperText(fn ($livewire) => 'Free chapters: ' . $livewire->ownerRecord->freeChapters()->count() . '/' . $livewire->ownerRecord->free_chapters),
                Forms\Components\TextInput::make('coin_cost')
                    ->numeric()
                    ->default(5)
                    ->minValue(0)
                    ->visible(fn (Forms\Get $get) => !$get('is_free'))
                    ->helperText('Cost in coins to unlock this chapter'),
                Forms\Components\Section::make('Chapter Content')
                    ->schema(function ($livewire) {
                        $seriesType = $livewire->ownerRecord->type;

                        if ($seriesType === 'novel') {
                            return [
                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText('Enter the novel chapter text content here.')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('series/' . $livewire->ownerRecord->id . '/chapters/attachments'),
                            ];
                        } else {
                            return [
                                Forms\Components\FileUpload::make('images')
                                    ->disk('public')
                                    ->multiple()
                                    ->reorderable()
                                    ->directory(fn ($livewire) => 'series/' . $livewire->ownerRecord->id . '/chapters')
                                    ->columnSpanFull()
                                    ->required(fn ($livewire) => $livewire->getOperation() === 'create')
                                    ->helperText('Upload manga/manhwa/manhua images here. Images will be displayed in the order they are arranged.')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->imagePreviewHeight('200')
                                    ->panelLayout('grid'),
                            ];
                        }
                    }),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('title')
            ->columns([
                Tables\Columns\TextColumn::make('chapter_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_free')
                    ->boolean()
                    ->label('Free'),
                Tables\Columns\TextColumn::make('coin_cost')
                    ->numeric()
                    ->sortable()
                    ->state(fn ($record) => $record->is_free ? 0 : $record->coin_cost),
                Tables\Columns\TextColumn::make('views_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('content_type')
                    ->getStateUsing(function ($record) {
                        $seriesType = $record->series->type;
                        return ucfirst($seriesType);
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Novel' => 'success',
                        default => 'primary',
                    }),
                Tables\Columns\TextColumn::make('image_count')
                    ->getStateUsing(function ($record) {
                        if ($record->series->type !== 'novel' && !empty($record->content)) {
                            try {
                                $images = json_decode($record->content, true);
                                return is_array($images) ? count($images) : 0;
                            } catch (\Exception $e) {
                                return 0;
                            }
                        }
                        return '-';
                    })
                    ->visible(fn ($livewire) => $livewire->ownerRecord->type !== 'novel')
                    ->label('Images'),
            ])
            ->defaultSort('chapter_number')
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('is_free')
                    ->options([
                        true => 'Free Chapters',
                        false => 'Paid Chapters',
                    ])
                    ->label('Access Type'),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->mutateFormDataUsing(function (array $data, $livewire): array {
                        // Generate UUID for slug
                        $data['slug'] = Str::uuid();
                        
                        // Handle content based on series type
                        $seriesType = $livewire->ownerRecord->type;
                        
                        if ($seriesType === 'novel') {
                            // For novels, content is already stored as HTML text
                            // No additional processing needed
                        } else {
                            // For manga/manhwa/manhua, encode the image array as JSON
                            if (isset($data['images']) && is_array($data['images'])) {
                                $data['content'] = json_encode($data['images']);
                            } else {
                                $data['content'] = json_encode([]);
                            }
                        }
                        
                        return $data;
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('edit')
                    ->url(fn ($record) => route('filament.admin.resources.chapters.edit', ['record' => $record->id]))
                    ->icon('heroicon-o-pencil-square'),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\Action::make('preview')
                    ->label('Preview')
                    ->icon('heroicon-o-eye')
                    ->modalHeading(fn ($record) => "Preview: {$record->title}")
                    ->modalContent(function ($record) {
                        if ($record->series->type === 'novel') {
                            return view('filament.resources.series-resource.relation-managers.preview-novel', [
                                'content' => $record->content
                            ]);
                        } else {
                            $images = [];
                            try {
                                $images = json_decode($record->content, true) ?? [];
                            } catch (\Exception $e) {
                                // If parsing fails, use empty array
                            }
                            
                            return view('filament.resources.series-resource.relation-managers.preview-manga', [
                                'images' => $images
                            ]);
                        }
                    })
                    ->modalWidth('7xl'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                    Tables\Actions\BulkAction::make('toggleFree')
                        ->label('Toggle Free/Paid')
                        ->icon('heroicon-o-currency-dollar')
                        ->action(function ($records) {
                            foreach ($records as $record) {
                                $record->update(['is_free' => !$record->is_free]);
                            }
                        }),
                ]),
            ]);
    }
}