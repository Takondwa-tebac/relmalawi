<x-mail::message>
# Thanks for contacting us, {{ $contactMessage->name }}

We have received your message and a member of the Radio Entertainment Limited team will get back to you soon. Here is a copy of what you sent:

<x-mail::panel>
{{ $contactMessage->message }}
</x-mail::panel>

If you need to add anything, simply reply to this email.

Regards,<br>
Radio Entertainment Limited
</x-mail::message>
