<?php

namespace App\Filament\Resources\CommentResource\Widgets;

use App\Filament\Resources\CommentResource;
use App\Models\Comment;
use Filament\Actions\EditAction;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestCommentsWidget extends BaseWidget
{
    protected static ?string $heading = 'Bình luận mới nhất';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Comment::whereDate('created_at', '>', now()->subDays(70)->startOfDay())
            )
            ->columns([
                TextColumn::make('user.name')->label(__('User')),
                TextColumn::make('post.title')->label(__('Post')),
                TextColumn::make('comment')->label(__('Comment')),
                TextColumn::make('created_at')->label(__('Created at'))
                    ->date()
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return \Carbon\Carbon::parse($state)->format('d/m/Y');
                    }),
            ])
            ->actions([
                Action::make(__('View'))
                    ->url(fn (Comment $record): string => CommentResource::getUrl('edit', ['record' => $record]))
                    ->openUrlInNewTab()
            ]);
    }
}
