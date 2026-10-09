<?php

namespace Database\Seeders\Content;

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

        // Only seed defaults that do not exist yet, so CMS edits survive re-seeding.
        $defaults = [
            'banner_text' => "REL is building the infrastructure behind Malawi's next media games.",
            'contact_email' => 'hello@relmalawi.com',
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
