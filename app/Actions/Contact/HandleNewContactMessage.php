<?php

namespace App\Actions\Contact;

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Mail\ContactMessageReceivedMail;
use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Throwable;

/**
 * Everything that follows a stored contact message: confirm to the sender,
 * notify super-admins in the panel and by email. Never throws, so a mail or
 * notification outage cannot lose the stored message.
 */
class HandleNewContactMessage
{
    public function __invoke(ContactMessage $message): void
    {
        $this->safely(fn () => Mail::to($message->email)->queue(new ContactMessageReceivedMail($message)));

        $admins = $this->admins();

        $this->safely(fn () => $this->notifyInPanel($admins, $message));
        $this->safely(fn () => $this->emailStaff($admins, $message));
    }

    /**
     * @param  iterable<User>  $admins
     */
    private function notifyInPanel(iterable $admins, ContactMessage $message): void
    {
        foreach ($admins as $admin) {
            $this->safely(fn () => Notification::make()
                ->title('New contact message')
                ->body($message->name.' ('.$message->email.'): '.Str::limit($message->message, 120))
                ->icon('heroicon-o-envelope')
                ->actions([
                    Action::make('view')
                        ->label('View message')
                        ->url(ContactMessageResource::getUrl('view', ['record' => $message], panel: 'admin')),
                ])
                ->sendToDatabase($admin));
        }
    }

    /**
     * @param  iterable<User>  $admins
     */
    private function emailStaff(iterable $admins, ContactMessage $message): void
    {
        $addresses = collect($admins)->pluck('email')->push(Setting::get('contact_email'))
            ->filter(fn ($address) => filter_var($address, FILTER_VALIDATE_EMAIL))
            ->unique(fn (string $address) => Str::lower($address));

        foreach ($addresses as $address) {
            $this->safely(fn () => Mail::to($address)->queue(new NewContactMessageMail($message)));
        }
    }

    /**
     * @return Collection<int, User>
     */
    private function admins(): Collection
    {
        try {
            return User::role('super-admin')->get();
        } catch (Throwable $e) {
            report($e);

            return new Collection;
        }
    }

    private function safely(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable $e) {
            report($e);
        }
    }
}
