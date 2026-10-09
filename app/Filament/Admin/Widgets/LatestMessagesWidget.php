<?php

namespace App\Filament\Admin\Widgets;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;

class LatestMessagesWidget extends TableWidget
{
    protected static ?int $sort = 4;

    public static function canView(): bool
    {
        return ContactMessageResource::canViewAny();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Latest messages')
            ->query(fn () => ContactMessage::query()->latest()->limit(5))
            ->paginated(false)
            ->columns([
                IconColumn::make('read_at')
                    ->label('')
                    ->icon(fn (ContactMessage $record) => $record->read_at === null ? Heroicon::OutlinedEnvelope : Heroicon::OutlinedCheckCircle)
                    ->color(fn (ContactMessage $record) => $record->read_at === null ? 'warning' : 'gray'),
                TextColumn::make('name')
                    ->weight(fn (ContactMessage $record) => $record->read_at === null ? 'bold' : null)
                    ->description(fn (ContactMessage $record) => $record->email),
                TextColumn::make('message')->limit(60),
                TextColumn::make('created_at')->label('Received')->since(),
            ])
            ->recordUrl(fn (ContactMessage $record) => ContactMessageResource::getUrl('view', ['record' => $record]))
            ->headerActions([
                Action::make('all')
                    ->label('View all')
                    ->url(ContactMessageResource::getUrl('index'))
                    ->color('gray')
                    ->icon(Heroicon::OutlinedArrowRight),
            ])
            ->emptyStateHeading('No messages yet')
            ->emptyStateDescription('Messages from the public contact form will appear here.')
            ->emptyStateIcon(Heroicon::OutlinedInbox);
    }
}
