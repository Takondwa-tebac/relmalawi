<x-mail::message>
# New contact message

**From:** {{ $contactMessage->name }} ({{ $contactMessage->email }})

{{ $contactMessage->message }}

<x-mail::button :url="url('/admin/contact-messages/'.$contactMessage->id)">
Open in admin
</x-mail::button>
</x-mail::message>
