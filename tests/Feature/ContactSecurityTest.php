<?php

use App\Mail\ContactMessageReceivedMail;
use App\Mail\NewContactMessageMail;
use App\Models\ContactMessage;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Inertia\Testing\AssertableInertia as Assert;

const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

function contactPayload(array $overrides = []): array
{
    return [
        'name' => 'Chikondi Banda',
        'email' => 'chikondi@example.com',
        'message' => 'We would love to partner with REL.',
        ...$overrides,
    ];
}

function fakeRecaptcha(array $response): void
{
    Http::fake([VERIFY_URL => Http::response(['success' => true, 'action' => 'contact', 'score' => 0.9, ...$response])]);
}

describe('reCAPTCHA v3', function () {
    beforeEach(function () {
        Mail::fake();
        config([
            'services.recaptcha.site_key' => 'site-key',
            'services.recaptcha.secret' => 'secret-key',
            'services.recaptcha.min_score' => 0.5,
        ]);
    });

    it('shares only the site key with the page', function () {
        $this->get('/contact')->assertInertia(fn (Assert $page) => $page
            ->where('recaptchaSiteKey', 'site-key'));

        $this->get('/contact')->assertDontSee('secret-key');
    });

    it('accepts a high-scoring token for the contact action', function () {
        fakeRecaptcha(['score' => 0.9]);

        $this->post('/contact', contactPayload(['recaptcha_token' => 'good']))
            ->assertRedirect()
            ->assertSessionHasNoErrors();

        expect(ContactMessage::count())->toBe(1);
        Http::assertSent(fn (Request $request) => $request->url() === VERIFY_URL
            && $request['secret'] === 'secret-key'
            && $request['response'] === 'good');
    });

    it('rejects suspicious submissions', function (array $response) {
        fakeRecaptcha($response);

        $this->post('/contact', contactPayload(['recaptcha_token' => 'bad']))
            ->assertSessionHasErrors('recaptcha_token');

        expect(ContactMessage::count())->toBe(0);
        Mail::assertNothingQueued();
    })->with([
        'low score' => [['score' => 0.1]],
        'wrong action' => [['action' => 'login']],
        'google says failure' => [['success' => false, 'score' => null, 'action' => null]],
    ]);

    it('rejects a missing token without calling Google', function () {
        Http::fake();

        $this->post('/contact', contactPayload())->assertSessionHasErrors('recaptcha_token');

        Http::assertNothingSent();
    });

    it('fails closed and reports when Google is unreachable', function () {
        Exceptions::fake();
        Http::fake(fn () => throw new ConnectionException('timeout'));

        $this->post('/contact', contactPayload(['recaptcha_token' => 'x']))
            ->assertSessionHasErrors('recaptcha_token');

        expect(ContactMessage::count())->toBe(0);
        Exceptions::assertReported(ConnectionException::class);
    });

    it('fails closed when Google answers with a server error', function () {
        Exceptions::fake();
        Http::fake([VERIFY_URL => Http::response('nope', 500)]);

        $this->post('/contact', contactPayload(['recaptcha_token' => 'x']))
            ->assertSessionHasErrors('recaptcha_token');

        expect(ContactMessage::count())->toBe(0);
    });
});

describe('reCAPTCHA without keys', function () {
    beforeEach(function () {
        Mail::fake();
        config(['services.recaptcha.site_key' => null, 'services.recaptcha.secret' => null]);
    });

    it('is skipped in the testing environment', function () {
        Http::fake();

        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        expect(ContactMessage::count())->toBe(1);
        Http::assertNothingSent();
    });

    it('fails closed and logs a warning in production', function () {
        $this->app['env'] = 'production';
        Log::spy();

        // CSRF verification is only skipped under the testing environment.
        $this->withSession(['_token' => 'csrf'])
            ->post('/contact', contactPayload(['_token' => 'csrf']))
            ->assertSessionHasErrors('recaptcha_token');

        expect(ContactMessage::count())->toBe(0);
        Log::shouldHaveReceived('warning')->once();
    });
});

describe('rate limiting', function () {
    beforeEach(fn () => Mail::fake());

    it('blocks a second submission within ten seconds from the same IP', function () {
        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        $this->post('/contact', contactPayload(['email' => 'other@example.com']))
            ->assertRedirect()
            ->assertSessionHasErrors('throttle');

        expect(ContactMessage::count())->toBe(1);

        $this->travel(11)->seconds();

        $this->post('/contact', contactPayload(['email' => 'other@example.com']))
            ->assertSessionHasNoErrors();
    });

    it('allows only three submissions per hour per IP', function () {
        foreach (range(1, 3) as $i) {
            $this->post('/contact', contactPayload(['email' => "person{$i}@example.com"]))
                ->assertSessionHasNoErrors();
            $this->travel(11)->seconds();
        }

        $this->post('/contact', contactPayload(['email' => 'person4@example.com']))
            ->assertSessionHasErrors('throttle');

        expect(ContactMessage::count())->toBe(3);

        $this->travel(1)->hour();

        $this->post('/contact', contactPayload(['email' => 'person4@example.com']))
            ->assertSessionHasNoErrors();
    });

    it('allows only two submissions per hour per email address, ignoring case', function () {
        $emails = ['same@example.com', 'SAME@example.com', 'Same@Example.com'];

        foreach ($emails as $i => $email) {
            $response = $this->withServerVariables(['REMOTE_ADDR' => '10.0.0.'.($i + 1)])
                ->post('/contact', contactPayload(['email' => $email]));

            $i < 2
                ? $response->assertSessionHasNoErrors()
                : $response->assertSessionHasErrors('throttle');
        }

        expect(ContactMessage::count())->toBe(2);
    });

    it('does not apply the email limit to different addresses', function () {
        foreach (['a@example.com', 'b@example.com'] as $i => $email) {
            $this->withServerVariables(['REMOTE_ADDR' => '10.0.1.'.($i + 1)])
                ->post('/contact', contactPayload(['email' => $email]))
                ->assertSessionHasNoErrors();
        }

        expect(ContactMessage::count())->toBe(2);
    });
});

describe('confirmation email to the sender', function () {
    it('queues a copy of the message to the sender with the staff address as reply-to', function () {
        Mail::fake();
        Setting::put('contact_email', 'staff@example.com');

        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        Mail::assertQueued(ContactMessageReceivedMail::class, fn (ContactMessageReceivedMail $mail) => $mail->hasTo('chikondi@example.com')
            && $mail->hasReplyTo('staff@example.com')
            && $mail->contactMessage->message === 'We would love to partner with REL.');
    });

    it('renders the message copy and the logo, and embeds the logo inline when sent', function () {
        $message = ContactMessage::factory()->create(['message' => 'Please call me about advertising.']);
        $mailable = new ContactMessageReceivedMail($message);

        $html = $mailable->render();

        expect($html)
            ->toContain('Please call me about advertising.')
            ->toContain('cid:rel-logo')
            ->toContain('Radio Entertainment Limited');

        Mail::mailer('array')->to($message->email)->send($mailable);

        $sent = Mail::mailer('array')->getSymfonyTransport()->messages()->last();
        $raw = $sent->toString();

        expect($raw)
            ->toContain('Content-Type: image/png')
            ->toContain('Content-Disposition: inline')
            ->toContain('Content-ID: <')
            ->not->toContain('cid:rel-logo');
    });

    it('uses the branded layout for the staff email too', function () {
        $html = (new NewContactMessageMail(ContactMessage::factory()->create()))->render();

        expect($html)->toContain('cid:rel-logo')->toContain('Radio Entertainment Limited');
    });

    it('falls back to brand text when the logo file is missing', function () {
        $message = ContactMessage::factory()->create();
        $logo = public_path('images/logo.png');
        $backup = $logo.'.bak';

        rename($logo, $backup);

        try {
            Mail::mailer('array')->to($message->email)->send(new ContactMessageReceivedMail($message));
            $raw = Mail::mailer('array')->getSymfonyTransport()->messages()->last()->toString();
        } finally {
            rename($backup, $logo);
        }

        expect($raw)->not->toContain('Content-ID: <rel-logo>')->toContain('brand-text');
    });
});

describe('admin notifications', function () {
    beforeEach(function () {
        $this->seed(RolesSeeder::class);
        $this->admin = User::factory()->create()->assignRole('super-admin');
        $this->editor = User::factory()->create()->assignRole('editor');
    });

    it('sends a Filament database notification to super-admins only', function () {
        Mail::fake();

        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        $notification = $this->admin->notifications()->sole();
        $message = ContactMessage::sole();

        expect($notification->data['title'])->toBe('New contact message')
            ->and($notification->data['body'])->toContain('Chikondi Banda')->toContain('We would love to partner')
            ->and($notification->data['actions'][0]['url'])->toContain('/admin/contact-messages/'.$message->getKey())
            ->and($this->editor->notifications()->count())->toBe(0);
    });

    it('emails super-admins and the contact address, but not editors', function () {
        Mail::fake();
        Setting::put('contact_email', 'staff@example.com');

        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        Mail::assertQueued(NewContactMessageMail::class, 2);
        Mail::assertQueued(NewContactMessageMail::class, fn ($mail) => $mail->hasTo($this->admin->email));
        Mail::assertQueued(NewContactMessageMail::class, fn ($mail) => $mail->hasTo('staff@example.com'));
        Mail::assertNotQueued(NewContactMessageMail::class, fn ($mail) => $mail->hasTo($this->editor->email));
    });

    it('does not email the same address twice', function () {
        Mail::fake();
        Setting::put('contact_email', strtoupper($this->admin->email));

        $this->post('/contact', contactPayload())->assertSessionHasNoErrors();

        Mail::assertQueued(NewContactMessageMail::class, 1);
    });

    it('keeps the stored message when mail fails', function () {
        Exceptions::fake();
        Mail::shouldReceive('to')->andThrow(new RuntimeException('SMTP down'));

        $this->post('/contact', contactPayload())
            ->assertRedirect()
            ->assertSessionHasNoErrors()
            ->assertSessionHas('contact_sent');

        expect(ContactMessage::count())->toBe(1)
            ->and($this->admin->notifications()->count())->toBe(1);
        Exceptions::assertReported(RuntimeException::class);
    });
});
