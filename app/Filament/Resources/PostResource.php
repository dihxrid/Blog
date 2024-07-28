<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Filament\Resources\PostResource\RelationManagers\CommentsRelationManager;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\CheckboxColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(__('Main Content'))->schema(
                    [
                        TextInput::make('title')
                            ->label(__('Title'))
                            ->live()
                            ->required()->minLength(1)->maxLength(150)
                            ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                if ($operation === 'edit') {
                                    return;
                                }

                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')->label(__('Slug'))->required()->minLength(1)->unique(ignoreRecord: true)->maxLength(150),
                        RichEditor::make('body')
                            ->label(__('Body'))
                            ->required()
                            ->fileAttachmentsDirectory('posts/images')->columnSpanFull()
                    ]
                )->columns(2),
                Section::make(__('Meta'))->schema(
                    [
                        FileUpload::make('image')->label(__('Image'))->image()->directory('posts/thumbnails'),
                        DateTimePicker::make('published_at')->label(__('Published at'))->nullable(),
                        Checkbox::make('featured')->label(__('Featured')),
                        Select::make('user_id')
                            ->label(__('Author'))
                            ->relationship('author', 'name')
                            ->searchable()
                            ->required(),
                        Select::make('categories')
                            ->label(__('Categories'))
                            ->multiple()
                            ->relationship('categories', 'title')
                            ->searchable(),
                    ]
                ),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')->label(__('Image')),
                TextColumn::make('title')->label(__('Title'))->sortable()->searchable(),
                TextColumn::make('slug')->label(__('Slug'))->sortable()->searchable(),
                // TextColumn::make('categories')
                //     ->sortable()
                //     ->searchable()
                //     ->formatStateUsing(fn ($state) => collect($state)->pluck('title')->implode(', ')),
                TextColumn::make('author.name')->label(__('Author'))->sortable()->searchable(),
                TextColumn::make('published_at')->label(__('Published at'))->date()->sortable()->searchable()->formatStateUsing(function ($state) {
                    return \Carbon\Carbon::parse($state)->format('d/m/Y');
                }),
                CheckboxColumn::make('featured')->label(__('Featured')),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CommentsRelationManager::class
        ];
    }

    public static function getModelLabel(): string
    {
        return __('Post');
    }

    public static function getPluralModelLabel(): string
    {
        return __('Post');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
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
