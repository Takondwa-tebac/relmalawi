<?php

namespace App\Filament\Admin\Resources\Features\Tables;

use App\Enums\FeatureGroup;
use App\Models\Page;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class FeaturesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('page_slug')
                    ->label('Page')
                    ->badge()
                    ->sortable(),
                TextColumn::make('group')
                    ->formatStateUsing(fn (string $state) => FeatureGroup::tryFrom($state)?->label() ?? $state)
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable()
                    ->limit(50),
                TextColumn::make('icon')
                    ->formatStateUsing(fn ($state) => $state?->label())
                    ->toggleable(),
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                ToggleColumn::make('is_published')
                    ->label('Published'),
            ])
            ->filters([
                SelectFilter::make('page_slug')
                    ->label('Page')
                    ->options(array_combine(Page::SLUGS, Page::SLUGS)),
                SelectFilter::make('group')
                    ->options(FeatureGroup::options()),
            ])
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
