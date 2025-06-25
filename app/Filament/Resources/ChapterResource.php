<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ChapterResource\Pages;
use App\Models\Chapter;
use App\Models\Series;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ChapterResource extends Resource
{
    protected static ?string $model = Chapter::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    
    protected static ?string $navigationGroup = 'Content Management';

    protected static ?int $navigationSort = 2;

    protected static ?string $recordTitleAttribute = 'title';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('series_id')
                    ->relationship('series', 'title')
                    ->required()
                    ->searchable()
                    ->preload()
                    ->live()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $series = Series::find($state);
                            if ($series) {
                                // Set default chapter number
                                $chapterCount = $series->chapters()->count();
                                $set('chapter_number', $chapterCount + 1);
                                
                                // Set default free status based on free chapters allowed
                                $freeChaptersAllowed = $series->free_chapters;
                                $currentFreeCount = $series->freeChapters()->count();
                                $set('is_free', $currentFreeCount < $freeChaptersAllowed);
                            }
                        }
                    }),
                    
                Forms\Components\TextInput::make('title')
                    ->required()
                    ->maxLength(255),
                    
                Forms\Components\TextInput::make('chapter_number')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.1)
                    ->helperText('Can use decimal (e.g., 1.5 for special chapters)'),
                    
                Forms\Components\Toggle::make('is_free')
                    ->label('Free Chapter')
                    ->reactive()
                    ->afterStateUpdated(function ($state, Forms\Set $set) {
                        if ($state) {
                            $set('coin_cost', 0);
                        } else {
                            $set('coin_cost', 5); // Default cost
                        }
                    }),
                    
                Forms\Components\TextInput::make('coin_cost')
                    ->numeric()
                    ->default(5)
                    ->minValue(0)
                    ->visible(fn (Forms\Get $get) => !$get('is_free'))
                    ->helperText('Cost in coins to unlock this chapter'),
                    
                Forms\Components\TextInput::make('views_count')
                    ->numeric()
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),
                    
                Forms\Components\Section::make('Chapter Content')
                    ->schema(function (Forms\Get $get) {
                        $seriesId = $get('series_id');
                        if (!$seriesId) {
                            return [
                                Forms\Components\Placeholder::make('select_series')
                                    ->content('Please select a series first to display the appropriate content editor.')
                                    ->columnSpanFull(),
                            ];
                        }
                        
                        $series = Series::find($seriesId);
                        $seriesType = $series ? $series->type : null;
                        
                        if ($seriesType === 'novel') {
                            return [
                                Forms\Components\RichEditor::make('content')
                                    ->required()
                                    ->columnSpanFull()
                                    ->helperText('Enter the novel chapter text content here.')
                                    ->fileAttachmentsDisk('public')
                                    ->fileAttachmentsDirectory('series/' . $seriesId . '/chapters/attachments'),
                            ];
                        } else {
                            return [
                                Forms\Components\FileUpload::make('images')
                                    ->disk('public')
                                    ->multiple()
                                    ->reorderable()
                                    ->directory('series/' . $seriesId . '/chapters')
                                    ->columnSpanFull()
                                    ->required(fn (string $operation) => $operation === 'create')
                                    ->helperText('Upload manga/manhwa/manhua images here. Images will be displayed in the order they are arranged.')
                                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                    ->imagePreviewHeight('200')
                                    ->panelLayout('grid'),
                            ];
                        }
                    })
                    ->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('series.title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                Tables\Columns\TextColumn::make('chapter_number')
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable()
                    ->limit(30),
                Tables\Columns\IconColumn::make('is_free')
                    ->boolean()
                    ->label('Free'),
                Tables\Columns\TextColumn::make('coin_cost')
                    ->numeric()
                    ->sortable()
                    ->getStateUsing(fn ($record) => $record->is_free ? 0 : $record->coin_cost),
                Tables\Columns\TextColumn::make('views_count')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('series.type')
                    ->badge()
                    ->label('Type'),
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
                    ->visible(fn ($livewire) => true) // Always show, will display '-' for novels
                    ->label('Images'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\SelectFilter::make('series_id')
                    ->relationship('series', 'title')
                    ->searchable()
                    ->preload()
                    ->label('Series'),
                Tables\Filters\SelectFilter::make('is_free')
                    ->options([
                        true => 'Free Chapters',
                        false => 'Paid Chapters',
                    ])
                    ->label('Access Type'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChapters::route('/'),
            'create' => Pages\CreateChapter::route('/create'),
            'edit' => Pages\EditChapter::route('/{record}/edit'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
    
    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
} 