<?php

namespace App\Filament\Resources\Testimonials\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TestimonialsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label(__('testimonial.user'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('event.title')
                    ->label(__('testimonial.event'))
                    ->searchable()
                    ->sortable()
                    ->limit(30),
                TextColumn::make('content')
                    ->label(__('testimonial.content'))
                    ->limit(50)
                    ->searchable(),
                TextColumn::make('rating')
                    ->label(__('testimonial.rating'))
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn (int $state): string => match (true) {
                        $state >= 4 => 'success',
                        $state >= 3 => 'warning',
                        default => 'danger',
                    }),
                TextColumn::make('status')
                    ->label(__('testimonial.status'))
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'approved' => 'success',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                IconColumn::make('is_published')
                    ->label(__('testimonial.published'))
                    ->boolean()
                    ->sortable(),
                IconColumn::make('is_featured')
                    ->label(__('testimonial.featured'))
                    ->boolean()
                    ->sortable(),
                ImageColumn::make('image_path')
                    ->label(__('testimonial.image'))
                    ->circular()
                    ->size(50),
                TextColumn::make('created_at')
                    ->label(__('testimonial.created_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label(__('testimonial.updated_at'))
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->preload()
                    ->label(__('testimonial.event')),
                SelectFilter::make('status')
                    ->label(__('testimonial.status'))
                    ->options([
                        'pending' => __('testimonial.pending'),
                        'approved' => __('testimonial.approved'),
                    ]),
                TernaryFilter::make('is_published')
                    ->label(__('testimonial.published')),
                TernaryFilter::make('is_featured')
                    ->label(__('testimonial.featured')),
            ])
            ->recordActions([
                Action::make('publish')
                    ->label(__('testimonial.publish_action'))
                    ->icon(fn ($record) => $record->is_published ? 'heroicon-o-check-circle' : 'heroicon-o-x-circle')
                    ->color(fn ($record) => $record->is_published ? 'success' : 'gray')
                    ->action(function ($record) {
                        $record->is_published = !$record->is_published;
                        $record->save();
                    }),
                EditAction::make()->slideOver(),
                ViewAction::make()
                    ->label(__('testimonial.view_action'))
                    ->icon('heroicon-o-eye')
                    ->slideOver(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
