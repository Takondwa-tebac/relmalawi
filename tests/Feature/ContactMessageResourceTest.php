<?php

use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Admin\Resources\ContactMessages\Pages\ListContactMessages;
use App\Filament\Admin\Resources\ContactMessages\Pages\ViewContactMessage;
use App\Models\ContactMessage;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Actions\Testing\TestAction;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
});

it('lists messages and filters unread', function () {
    $unread = ContactMessage::factory()->create();
    $read = ContactMessage::factory()->read()->create();

    Livewire::test(ListContactMessages::class)
        ->assertOk()
        ->assertCanSeeTableRecords([$unread, $read])
        ->filterTable('unread', true)
        ->assertCanSeeTableRecords([$unread])
        ->assertCanNotSeeTableRecords([$read]);
});

it('marks messages as read in bulk', function () {
    $messages = ContactMessage::factory()->count(3)->create();

    Livewire::test(ListContactMessages::class)
        ->selectTableRecords($messages)
        ->callAction(TestAction::make('markAsRead')->table()->bulk());

    expect(ContactMessage::unread()->count())->toBe(0);
});

it('shows the unread count badge', function () {
    ContactMessage::factory()->count(2)->create();
    ContactMessage::factory()->read()->create();

    expect(ContactMessageResource::getNavigationBadge())->toBe('2');
});

it('shows the full message and can delete it', function () {
    $message = ContactMessage::factory()->create();

    Livewire::test(ViewContactMessage::class, ['record' => $message->getKey()])
        ->assertOk()
        ->assertSee($message->message)
        ->callAction('delete');

    expect(ContactMessage::count())->toBe(0);
});

it('marks a message read when viewed', function () {
    $message = ContactMessage::factory()->create();

    Livewire::test(ViewContactMessage::class, ['record' => $message->getKey()]);

    expect($message->fresh()->read_at)->not->toBeNull();
});

it('does not allow creating messages', function () {
    expect(ContactMessageResource::canCreate())->toBeFalse();
});
