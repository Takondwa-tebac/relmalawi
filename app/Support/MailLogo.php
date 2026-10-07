<?php

namespace App\Support;

use Illuminate\Mail\Events\MessageSending;
use Symfony\Component\Mime\Email;

/**
 * Embeds the REL logo as an inline (CID) attachment in every outgoing HTML
 * mail, so it renders on localhost and in clients that block remote images.
 * Falls back to plain brand text when the logo file is unavailable.
 */
class MailLogo
{
    public const CID = 'rel-logo';

    public function handle(MessageSending $event): void
    {
        $email = $event->message;

        if (! $email instanceof Email) {
            return;
        }

        $html = $email->getHtmlBody();

        if (! is_string($html) || ! str_contains($html, 'cid:'.self::CID)) {
            return;
        }

        $path = public_path('images/logo.png');

        if (is_file($path)) {
            $email->embedFromPath($path, self::CID, 'image/png');

            return;
        }

        $email->html(preg_replace(
            '/<img[^>]*cid:'.preg_quote(self::CID, '/').'[^>]*>/i',
            '<span class="brand-text" style="color:#f7edcf;font-weight:bold;font-size:19px;">Radio Entertainment Limited</span>',
            $html,
        ));
    }
}
