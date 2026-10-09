<?php

namespace App\Filament\Admin\Resources\Pages\Tables;

use App\Models\Page;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\EditAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Collection;

class PagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('slug')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('title')
                    ->searchable(),
                // Quick on/off switches. They need the same permission as editing the page,
                // and the home page can never be switched off.
                ToggleColumn::make('is_active')
                    ->label('Active')
                    ->sortable()
                    ->disabled(fn (Page $record) => $record->slug === 'home' || ! auth()->user()?->can('update', $record)),
                ToggleColumn::make('show_in_nav')
                    ->label('In navbar')
                    ->sortable()
                    ->disabled(fn (Page $record) => ! auth()->user()?->can('update', $record)),
                TextColumn::make('nav_label')
                    ->label('Navbar label')
                    ->placeholder('Default')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('title_accent')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Status')
                    ->placeholder('All pages')
                    ->trueLabel('Active only')
                    ->falseLabel('Switched off only'),
            ])
            ->defaultSort('slug')
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('activate')
                        ->label('Switch on')
                        ->icon(Heroicon::OutlinedEye)
                        ->authorizeIndividualRecords('update')
                        ->action(fn (Collection $records) => $records->each->update(['is_active' => true]))
                        ->deselectRecordsAfterCompletion(),
                    BulkAction::make('deactivate')
                        ->label('Switch off')
                        ->icon(Heroicon::OutlinedEyeSlash)
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalDescription('Visitors will get a "page not found". The home page is skipped because it cannot be switched off.')
                        ->authorizeIndividualRecords('update')
                        ->action(fn (Collection $records) => $records->reject(fn (Page $page) => $page->slug === 'home')->each->update(['is_active' => false]))
                        ->deselectRecordsAfterCompletion(),
                ]),
            ]);
    }
}
