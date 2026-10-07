<?php

namespace App\Filament\Admin\Resources\HowItWorksSteps\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class HowItWorksStepsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->reorderable('sort_order')
            ->defaultSort('sort_order')
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Order'),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('body')
                    ->limit(80),
                TextColumn::make('icon')
                    ->badge()
                    ->placeholder('-'),
                IconColumn::make('is_published')
                    ->label('Published')
                    ->boolean(),
            ])
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
