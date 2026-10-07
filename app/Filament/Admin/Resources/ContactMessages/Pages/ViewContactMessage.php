<?php

namespace App\Filament\Admin\Resources\ContactMessages\Pages;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\ViewRecord;
use Filament\Support\Icons\Heroicon;

class ViewContactMessage extends ViewRecord
{
    protected static string $resource = ContactMessageResource::class;

    /**
     * Opening a message marks it as read.
     */
    public function mount(int|string $record): void
    {
        parent::mount($record);

        /** @var ContactMessage $message */
        $message = $this->getRecord();
        $message->markAsRead();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reply')
                ->label('Reply by email')
                ->icon(Heroicon::OutlinedEnvelope)
                ->url(fn (ContactMessage $record): string => 'mailto:'.$record->email)
                ->openUrlInNewTab(),
            Action::make('markUnread')
                ->label('Mark as unread')
                ->icon(Heroicon::OutlinedEnvelopeOpen)
                ->color('gray')
                ->action(function (ContactMessage $record): void {
                    $record->forceFill(['read_at' => null])->save();
                    $this->redirect(ContactMessageResource::getUrl('index'));
                }),
            DeleteAction::make(),
        ];
    }
}
