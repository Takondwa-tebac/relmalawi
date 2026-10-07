<?php

namespace App\Http\Controllers\Guest;

use App\Actions\Contact\HandleNewContactMessage;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;

class ContactController extends Controller
{
    public function __invoke(): Response
    {
        return inertia('Contact', [
            'page' => Page::intro('contact'),
            // Public by design; the secret never leaves the server.
            'recaptchaSiteKey' => config('services.recaptcha.site_key') ?: null,
        ]);
    }

    public function store(StoreContactMessageRequest $request, HandleNewContactMessage $handle): RedirectResponse
    {
        // Honeypot: pretend success so bots learn nothing, but store nothing.
        if ($request->isHoneypotTripped()) {
            return back()->with('contact_sent', true);
        }

        $message = ContactMessage::create([
            ...$request->safe()->only(['name', 'email', 'message']),
            'ip_address' => $request->ip(),
        ]);

        $handle($message);

        return back()->with('contact_sent', true);
    }
}
