<?php

namespace Database\Seeders\Content;

use App\Models\Feature;
use App\Models\HowItWorksStep;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Copy for the How it works page: intro, numbered steps and the page's feature blocks.
 * Idempotent: steps added by editors are never touched.
 */
class HowItWorksPageSeeder extends Seeder
{
    /** Titles of the original placeholder steps, replaced by the real ones below. */
    private const OLD_TITLES = [
        'Dial the shortcode',
        'Pay by mobile money',
        'Entry confirmed by SMS',
        'The draw',
        'Winners announced',
        'Payout',
    ];

    public function run(): void
    {
        Page::query()->updateOrCreate(['slug' => 'how-it-works'], [
            'eyebrow' => 'How it works',
            'title' => 'Dial. Play.',
            'title_accent' => 'Win on air.',
            'description' => 'Enter a draw from any mobile phone, watch it happen live on your favourite station, and get paid straight to your mobile-money account if your ticket is picked. No app, no sign-up, no waiting.',
            'meta_title' => 'How it works',
        ]);

        $this->seedSteps();
        $this->seedFeatures();
    }

    private function seedSteps(): void
    {
        $old = HowItWorksStep::query()
            ->whereIn('title', self::OLD_TITLES)
            ->orderBy('sort_order')
            ->get();

        foreach ($this->steps() as $step) {
            $attributes = $step + ['is_published' => true];

            $existing = HowItWorksStep::query()->where('title', $step['title'])->first();

            if (! $existing && $old->isNotEmpty()) {
                $existing = $old->shift();
            }

            if ($existing) {
                $existing->update($attributes);

                continue;
            }

            HowItWorksStep::query()->create($attributes + [
                'sort_order' => (int) HowItWorksStep::query()->max('sort_order') + 1,
            ]);
        }

        // Any old placeholder left over (fewer new steps than old ones is not the case, but be safe).
        HowItWorksStep::query()->whereIn('id', $old->pluck('id'))->delete();
    }

    /**
     * @return list<array<string, string>>
     */
    private function steps(): array
    {
        return [
            ['title' => "Dial your station's shortcode", 'icon' => 'smartphone', 'body' => 'Every partner station has its own USSD shortcode. Dial it from any mobile phone. You do not need internet or an app.', 'detail' => 'Hear the shortcode on air, or find it in the station adverts and jingles.'],
            ['title' => 'Choose your station', 'icon' => 'radio', 'body' => 'The menu lets you pick the station you are listening to or watching, so your ticket goes into that station\'s draw.'],
            ['title' => 'Choose how many entries', 'icon' => 'wallet', 'body' => 'One entry costs MWK 800 and you can buy up to 10 in one go. The more entries you hold, the better your chance of being picked.', 'detail' => 'Every entry is a fresh chance. No entry is ever a guaranteed win.'],
            ['title' => 'Confirm and pay with your PIN', 'icon' => 'wallet', 'body' => 'Check the summary, confirm, then enter your mobile-money PIN. Payment is taken from your mobile-money account.'],
            ['title' => 'Your entry is confirmed', 'icon' => 'message-square', 'body' => 'Once payment goes through, your tickets are entered into the draw and you receive a confirmation.'],
            ['title' => 'The live draw in studio', 'icon' => 'shuffle', 'body' => 'The presenter runs the draw live in the studio. REL\'s software picks the winning ticket at random, so nobody can steer the result.'],
            ['title' => 'Winners are called on air', 'icon' => 'trophy', 'body' => 'The presenter calls the winner live on air, and the station often interviews winners to hear their reaction.'],
            ['title' => 'Paid instantly to mobile money', 'icon' => 'banknote', 'body' => 'The prize is credited to the winner\'s mobile-money account straight away, with a congratulatory SMS.', 'detail' => 'Payouts go through Airtel Money and TNM Mpamba.'],
        ];
    }

    private function seedFeatures(): void
    {
        foreach ($this->features() as $group => $items) {
            foreach (array_values($items) as $index => $item) {
                Feature::query()->updateOrCreate(
                    ['page_slug' => 'how-it-works', 'group' => $group, 'sort_order' => $index + 1],
                    $item + ['eyebrow' => null, 'body' => null, 'icon' => null, 'meta' => null, 'is_published' => true],
                );
            }
        }
    }

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    private function features(): array
    {
        return [
            'how-it-works.hero' => [[
                'title' => 'Hero buttons',
                'meta' => ['button_label' => 'Read the FAQ', 'button_url' => '/faq', 'secondary_label' => 'Contact us', 'secondary_url' => '/contact'],
            ]],
            'how-it-works.ussdIntro' => [[
                'eyebrow' => 'Illustrative',
                'title' => 'What you see on your phone',
                'body' => 'A simple walk-through of the menus on a station shortcode. Station names and wording here are examples only and may differ on the live service.',
                'meta' => ['bullets' => "Pick your station\nChoose up to 10 entries\nConfirm, then pay with your PIN"],
            ]],
            'how-it-works.ussd' => [
                ['eyebrow' => 'Screen 1', 'title' => 'Select station', 'body' => "Select a station\n1. Station A\n2. Station B\n3. Station C"],
                ['eyebrow' => 'Screen 2', 'title' => 'Number of entries', 'body' => "How many entries?\nEnter a number from 1 to 10.\nEach entry costs MWK 800."],
                ['eyebrow' => 'Screen 3', 'title' => 'Confirm ticket', 'body' => "You are about to buy 1 ticket for MWK 800.\n1. Confirm\n2. Cancel"],
                ['eyebrow' => 'Screen 4', 'title' => 'Enter PIN', 'body' => 'Enter your mobile-money PIN to pay MWK 800.'],
            ],
            'how-it-works.behind' => [
                [
                    'eyebrow' => 'Behind the scenes',
                    'title' => 'A random draw nobody can steer',
                    'icon' => 'shuffle',
                    'body' => 'Winners are chosen by REL\'s own software, which selects tickets at random. That removes bias from the result, whoever is in the studio.',
                    'meta' => [
                        'bullets' => "Random, computer-generated selection\nRun live in studio by the presenter\nThe regulator can test and vet the winner-selection algorithm\nMore entries mean a better chance, never a guarantee",
                        'caption' => 'Random selection',
                    ],
                ],
                [
                    'eyebrow' => 'Behind the scenes',
                    'title' => 'Instant payout to mobile money',
                    'icon' => 'wallet',
                    'body' => 'As soon as the software selects a winner, the prize is paid. REL is connected by API to Airtel Money and TNM Mpamba, so there is no manual step and no waiting.',
                    'meta' => [
                        'bullets' => "Prizes paid from funds in the company's merchant account\nCollections and payouts through REL's Airtel and TNM business accounts\nInstant congratulatory SMS to the winner",
                        'caption' => 'Airtel Money and TNM Mpamba',
                    ],
                ],
            ],
            'how-it-works.rolesIntro' => [[
                'eyebrow' => 'For our media partners',
                'title' => 'Everyone sees what they need',
                'body' => 'Each role on a partner station gets its own dashboard with real-time reports, so everyone can follow the draws that matter to them.',
            ]],
            'how-it-works.roles' => [
                ['title' => 'Presenters', 'icon' => 'mic', 'body' => 'The tools to run the live draw in studio.', 'meta' => ['bullets' => "A presenter dashboard built for the live show\nDraw winners and call them on air\nFollow their own programme's sales"]],
                ['title' => 'Station managers', 'icon' => 'building', 'body' => 'The whole station in one view.', 'meta' => ['bullets' => "Their station and its shows\nJackpot, bonus and admin draws\nRevenue reports and payouts"]],
                ['title' => 'Directors and owners', 'icon' => 'bar-chart', 'body' => 'The business picture, as it happens.', 'meta' => ['bullets' => "Revenue reports\nHourly performance\nShow summary and daily awarding report"]],
            ],
            'how-it-works.faqIntro' => [[
                'eyebrow' => 'Good to know',
                'title' => 'Playing questions, answered',
            ]],
            'how-it-works.cta' => [[
                'eyebrow' => 'Ready to talk?',
                'title' => 'Bring the draw to your station.',
                'meta' => ['button_label' => 'Contact us', 'button_url' => '/contact', 'secondary_label' => 'Partner with REL', 'secondary_url' => '/partnerships'],
            ]],
        ];
    }
}
