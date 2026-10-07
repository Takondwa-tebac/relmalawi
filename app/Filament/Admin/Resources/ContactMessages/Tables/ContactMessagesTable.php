<?php

namespace App\Filament\Admin\Resources\ContactMessages\Tables;

use App\Models\ContactMessage;
use Filament\Actions\BulkAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                IconColumn::make('read_at')
                    ->label('Read')
                    ->boolean()
                    ->state(fn (ContactMessage $record): bool => $record->read_at !== null),
                TextColumn::make('name')
                    ->searchable()
                    ->weight(fn (ContactMessage $record) => $record->read_at === null ? 'bold' : null),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('message')
                    ->limit(70)
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Received')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('unread')
                    ->label('Status')
                    ->placeholder('All messages')
                    ->trueLabel('Unread only')
                    ->falseLabel('Read only')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNull('read_at'),
                        false: fn (Builder $query) => $query->whereNotNull('read_at'),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    BulkAction::make('markAsRead')
                        ->label('Mark as read')
                        ->icon(Heroicon::OutlinedEnvelopeOpen)
                        ->deselectRecordsAfterCompletion()
                        ->action(fn (Collection $records) => $records->each->markAsRead()),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
