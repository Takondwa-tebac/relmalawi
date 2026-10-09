<?php

namespace Database\Seeders\Content;

use App\Models\Campaign;
use App\Models\Feature;
use App\Models\Page;
use App\Models\Setting;
use App\Models\Stat;
use Illuminate\Database\Seeder;

class HomeSeeder extends Seeder
{
    public function run(): void
    {
        Page::query()->updateOrCreate(['slug' => 'home'], [
            'eyebrow' => 'Who we are',
            'title' => 'Umoja',
            'title_accent' => 'Promo',
            'description' => 'Radio Entertainment Limited is a licensed digital gaming operator using innovative technology to expand the addressable market for gaming in Malawi.',
            'meta_title' => 'REL',
            'meta_description' => 'Radio Entertainment Limited is a licensed digital gaming operator in Malawi.',
        ]);

        foreach ([
            'home_hero_title_tail' => 'Media Games.',
            'home_hero_secondary' => 'We work with radio stations and media partners to bring trusted gaming products to new audiences through the reach and influence of broadcast media.',
        ] as $key => $value) {
            Setting::query()->firstOrCreate(['key' => $key], ['value' => $value]);
        }

        foreach ([
            ['value' => '40', 'label' => 'Dynamic codes'],
            ['value' => '01', 'label' => 'New platform'],
        ] as $order => $stat) {
            Stat::query()->updateOrCreate(['label' => $stat['label']], $stat + ['sort_order' => $order]);
        }

        foreach ($this->features() as $group => $items) {
            foreach (array_values($items) as $index => $item) {
                Feature::query()->updateOrCreate(
                    ['page_slug' => 'home', 'group' => $group, 'sort_order' => $index + 1],
                    $item + ['eyebrow' => null, 'body' => null, 'icon' => null, 'meta' => null, 'is_published' => true],
                );
            }
        }

        $campaigns = [
            ['slug' => 'mbc-raffle', 'title' => 'MBC Raffle', 'alt_text' => 'MBC Jackpot TV Campaign', 'file' => 'mbc_jackpot.jpg'],
            ['slug' => 'mibawa-draws', 'title' => 'Mibawa Draws', 'alt_text' => 'Mibawa Draws Radio Campaign', 'file' => 'mibawa_promo.jpg'],
            ['slug' => 'media-campaigns', 'title' => 'Media Campaigns', 'alt_text' => 'Times TV Campaign Activation', 'file' => 'times_tv.jpg'],
            ['slug' => 'timveni-jackpot', 'title' => 'Timveni Jackpot', 'alt_text' => 'Timveni Jackpot Winner Campaign', 'file' => 'timveni_jackpot_winner.jpg'],
        ];

        foreach ($campaigns as $order => $data) {
            $campaign = Campaign::query()->updateOrCreate(['slug' => $data['slug']], [
                'title' => $data['title'],
                'alt_text' => $data['alt_text'],
                'sort_order' => $order,
                'is_published' => true,
            ]);

            $path = public_path('images/campaigns/'.$data['file']);

            if (! $campaign->hasMedia('image') && is_file($path)) {
                $campaign->addMedia($path)
                    ->preservingOriginal()
                    ->toMediaCollection('image');
            }
        }
    }

    /**
     * Home page sections below the hero, keyed by feature group.
     *
     * @return array<string, list<array<string, mixed>>>
     */
    private function features(): array
    {
        return [
            'home.intro' => [[
                'eyebrow' => 'Entertainment. Technology. Opportunity.',
                'title' => 'Digital games, carried by media.',
                'body' => 'We combine digital technology with the reach and influence of radio and television to create engaging gaming experiences, new audience opportunities and potential revenue streams for media partners.',
            ]],
            'home.stats' => [
                ['title' => '4+', 'body' => 'Live draws per partner station, every day'],
                ['title' => '2', 'body' => 'Mobile-money networks: Airtel Money and TNM Mpamba'],
                ['title' => 'Instant', 'body' => 'Payouts to the winner\'s mobile-money account'],
                ['title' => 'Live', 'body' => 'Winners drawn and called on air'],
            ],
            'home.steps_heading' => [[
                'eyebrow' => 'How playing works',
                'title' => 'Four steps from the radio to your wallet.',
                'body' => 'No app, no sign-up. If you can dial a shortcode, you can play.',
                'meta' => ['button_label' => 'See the full process', 'button_url' => '/how-it-works'],
            ]],
            'home.steps' => [
                ['title' => 'Dial your station', 'body' => 'Each station has its own USSD shortcode. Dial it and pick the station you are listening to.', 'icon' => 'smartphone'],
                ['title' => 'Choose your entries', 'body' => 'One entry is MWK 800. Buy up to 10 entries in one session. More tickets mean a better chance.', 'icon' => 'ticket'],
                ['title' => 'Pay by mobile money', 'body' => 'Confirm the amount and enter your mobile-money PIN. Airtel Money and TNM Mpamba are supported.', 'icon' => 'wallet'],
                ['title' => 'Win live on air', 'body' => 'The presenter draws winners live in the studio. Prizes are paid instantly, with an SMS to confirm.', 'icon' => 'mic'],
            ],
            'home.audiences' => [
                ['eyebrow' => 'For players', 'title' => 'Play from any phone', 'body' => 'Join draws on the shows you already listen to and watch. Pay with mobile money and hear your name on air.', 'icon' => 'gamepad', 'meta' => ['button_label' => 'How it works', 'button_url' => '/how-it-works']],
                ['eyebrow' => 'For media partners', 'title' => 'A new revenue stream for your station', 'body' => 'Brand the raffle as your own, run draws live, and earn commission with dashboards and a dedicated relationship manager.', 'icon' => 'radio', 'meta' => ['button_label' => 'Partner with us', 'button_url' => '/partnerships']],
                ['eyebrow' => 'For regulators and stakeholders', 'title' => 'Open to inspection', 'body' => 'The regulator can test and vet the winner-selection algorithm. Stakeholders see real-time reports.', 'icon' => 'shield-check', 'meta' => ['button_label' => 'Our approach to regulation', 'button_url' => '/regulation']],
            ],
            'home.transparency' => [[
                'eyebrow' => 'Built for transparency',
                'title' => 'Every draw can be checked.',
                'body' => 'Winner selection is randomised by software, which removes bias. The gaming regulator is given access to the system to test and vet the algorithm, and partners follow results in real time.',
                'meta' => ['button_label' => 'Explore the technology', 'button_url' => '/technology'],
            ]],
            'home.transparency_items' => [
                ['title' => 'Regulator access to test and vet the algorithm', 'icon' => 'shield-check'],
                ['title' => 'Revenue and transactions reports', 'icon' => 'bar-chart'],
                ['title' => 'Hourly performance and show summary', 'icon' => 'bar-chart'],
                ['title' => 'Daily awards and winners reports', 'icon' => 'file-check'],
                ['title' => 'Growth trends for directors and owners', 'icon' => 'eye'],
            ],
            'home.payments' => [[
                'eyebrow' => 'Payments you already use',
                'title' => 'Pay and get paid on your mobile-money account.',
                'body' => 'Tickets are paid for and prizes are paid out through Airtel Money and TNM Mpamba, connected directly to our system.',
            ]],
            'home.faq' => [[
                'eyebrow' => 'Quick answers',
                'title' => 'Questions, answered.',
                'meta' => ['button_label' => 'See all questions', 'button_url' => '/faq'],
            ]],
            'home.cta' => [[
                'eyebrow' => 'Ready when you are',
                'title' => 'Bring REL to your station.',
                'meta' => ['button_label' => 'Get in touch', 'button_url' => '/contact'],
            ]],
        ];
    }
}
