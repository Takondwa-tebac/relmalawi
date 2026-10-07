<?php

namespace App\Mail;

use App\Models\ContactMessage;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Confirmation sent to the person who wrote to us, including a copy of their message.
 */
class ContactMessageReceivedMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public ContactMessage $contactMessage) {}

    public function envelope(): Envelope
    {
        $replyTo = Setting::get('contact_email');

        return new Envelope(
            replyTo: filter_var($replyTo, FILTER_VALIDATE_EMAIL) ? [new Address($replyTo, 'Radio Entertainment Limited')] : [],
            subject: 'We received your message - Radio Entertainment Limited',
        );
    }

    public function content(): Content
    {
        return new Content(markdown: 'mail.contact-received');
    }
}
