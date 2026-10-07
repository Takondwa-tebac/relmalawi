<?php

namespace Database\Seeders\Content;

use App\Models\HowItWorksStep;
use App\Models\Page;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class ContactHowItWorksSeeder extends Seeder
{
    public function run(): void
    {
        Page::query()->updateOrCreate(['slug' => 'contact'], [
            'eyebrow' => 'Open channel',
            'title' => "Let's make",
            'title_accent' => 'noise.',
            'description' => 'Have a partnership idea, a story to share or a question about REL? Send a signal and our team will get back to you.',
            'meta_title' => 'Contact',
        ]);

        Page::query()->updateOrCreate(['slug' => 'how-it-works'], [
            'eyebrow' => 'The experience',
            'title' => 'Simple to enter.',
            'title_accent' => 'Built to engage.',
            'description' => 'Our digital gaming experience is designed for radio listeners and television viewers. Media partners bring the energy on air; our platform manages participation, draws and reporting behind the scenes.',
            'meta_title' => 'How it works',
        ]);

        // PLACEHOLDER copy: the owner edits these steps in the CMS.
        $steps = [
            ['Dial the shortcode', 'Dial the unique USSD shortcode of a participating media partner.', 'smartphone'],
            ['Pay by mobile money', 'Confirm the ticket cost and complete payment through your mobile money account.', 'wallet'],
            ['Entry confirmed by SMS', 'You receive an SMS confirming your entry into the draw.', 'message-square'],
            ['The draw', 'Tickets enter the draw and winners are selected through the platform.', 'shuffle'],
            ['Winners announced', 'Winners are announced by the media partner on air.', 'trophy'],
            ['Payout', 'Prizes are paid out digitally to the winner.', 'banknote'],
        ];

        foreach ($steps as $index => [$title, $body, $icon]) {
            HowItWorksStep::query()->updateOrCreate(
                ['sort_order' => $index + 1],
                ['title' => $title, 'body' => $body, 'icon' => $icon, 'is_published' => true],
            );
        }

        // Only seed defaults that do not exist yet, so CMS edits survive re-seeding.
        $defaults = [
            'banner_text' => "REL is building the infrastructure behind Malawi's next media games.",
            'contact_email' => 'hello@relmw.com',
            'footer_name' => 'Radio Entertainment Limited',
            'est_year' => '2024',
            'codes_count' => '40',
            'codes_pattern' => '*4342*{n}#',
        ];

        foreach ($defaults as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }
    }
}
