<?php

use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(fn () => Mail::fake());

function validContact(array $overrides = []): array
{
    return [
        'name' => 'Chikondi Banda',
        'email' => 'chikondi@example.com',
        'message' => 'We would love to partner with REL.',
        ...$overrides,
    ];
}

it('renders the contact page', function () {
    $this->get('/contact')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page->component('Contact')->has('page.slug')->where('recaptchaSiteKey', null));
});

it('stores a valid message and notifies staff', function () {
    Setting::put('contact_email', 'staff@example.com');

    $this->post('/contact', validContact())->assertRedirect();

    $this->assertDatabaseHas('contact_messages', [
        'name' => 'Chikondi Banda',
        'email' => 'chikondi@example.com',
        'read_at' => null,
    ]);
    expect(ContactMessage::first()->ip_address)->not->toBeNull();
    Mail::assertQueued(NewContactMessageMail::class, fn ($mail) => $mail->hasTo('staff@example.com'));
});

it('validates input', function (array $overrides, string $field) {
    $this->post('/contact', validContact($overrides))->assertSessionHasErrors($field);

    expect(ContactMessage::count())->toBe(0);
})->with([
    'missing name' => [['name' => ''], 'name'],
    'bad email' => [['email' => 'nope'], 'email'],
    'missing message' => [['message' => ''], 'message'],
    'too long message' => [['message' => str_repeat('a', 5001)], 'message'],
]);

it('silently drops honeypot submissions', function () {
    $this->post('/contact', validContact(['website' => 'http://spam.test']))
        ->assertRedirect()
        ->assertSessionHasNoErrors();

    expect(ContactMessage::count())->toBe(0);
    Mail::assertNothingQueued();
});
