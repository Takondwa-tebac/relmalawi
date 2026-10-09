<?php

namespace Database\Seeders\Content;

use App\Models\Feature;
use App\Models\Page;
use Illuminate\Database\Seeder;

class PagesFeaturesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $slug => $attributes) {
            Page::query()->updateOrCreate(['slug' => $slug], $attributes);
        }

        foreach ($this->features() as $group => $items) {
            $pageSlug = explode('.', $group)[0];

            foreach (array_values($items) as $index => $item) {
                Feature::query()->updateOrCreate(
                    ['page_slug' => $pageSlug, 'group' => $group, 'sort_order' => $index + 1],
                    $item + ['eyebrow' => null, 'body' => null, 'icon' => null, 'meta' => null, 'is_published' => true],
                );
            }
        }
    }

    /**
     * @return array<string, array<string, string>>
     */
    private function pages(): array
    {
        return [
            'about' => [
                'eyebrow' => 'Who we are',
                'title' => 'Gaming, carried',
                'title_accent' => 'on the airwaves.',
                'description' => 'Radio Entertainment Limited (Malawi) is a licensed digital gaming operator. We partner with radio and TV stations to bring raffle draws to listeners and viewers, and we build and run the technology behind every draw.',
            ],
            'raffles' => [
                'eyebrow' => 'Our products',
                'title' => 'Games made',
                'title_accent' => 'for airtime.',
                'description' => 'Radio Entertainment Limited runs raffle draws on partner radio and TV stations. Listeners and viewers enter by mobile money, presenters draw winners live in studio, and prizes are paid out straight away. Here is how the draws work and how stations make them their own.',
            ],
            'technology' => [
                'eyebrow' => 'The platform',
                'title' => 'Technology you',
                'title_accent' => 'can test and trust.',
                'description' => 'One system takes a player from a USSD code to a mobile-money payout. Randomised draws, role-based dashboards and real-time reports keep stations, partners and the regulator looking at the same numbers.',
            ],
            'regulation' => [
                'eyebrow' => 'Regulation & trust',
                'title' => 'Licensed, open',
                'title_accent' => 'and accountable.',
                'description' => 'REL is a licensed digital gaming operator regulated by the Malawi Gaming and Lotteries Authority (MAGLA). The regulator can test our draw software, winners are paid straight to their wallets, and partners see the numbers in real time.',
            ],
        ];
    }

    /**
     * Rows are keyed by (group, position); position is the sort_order.
     *
     * @return array<string, list<array<string, mixed>>>
     */
    private function features(): array
    {
        return [
            'about.role' => [[
                'eyebrow' => 'Our role',
                'title' => 'We make the media game work.',
                'body' => 'REL does not own radio stations or television channels. We partner with them. We provide the product, the draw management, the technology and the day-to-day support that lets a station run raffle draws with confidence and earn from them.',
            ]],
            'about.stats' => [
                ['title' => '2024', 'body' => 'The year Radio Entertainment Limited was established.'],
                ['title' => 'MAGLA', 'body' => 'Our regulator: the Malawi Gaming and Lotteries Authority.'],
                ['title' => '2', 'body' => 'Mobile-money networks integrated: Airtel Money and TNM Mpamba.'],
            ],
            'about.guides' => [['title' => 'What guides us']],
            'about.pillars' => [
                ['title' => 'Media-led reach', 'icon' => 'radio', 'body' => 'Radio and television reach people of every income, gender and age. We work through partner stations to put our games in front of them.'],
                ['title' => 'Technology with purpose', 'icon' => 'gamepad', 'body' => 'Players enter by dialling a USSD shortcode on the phone they already own, and pay with mobile money.'],
                ['title' => 'Responsible play', 'icon' => 'shield-check', 'body' => 'Random selection, regulator access to the draw software and real-time reporting are part of how the product is built.'],
            ],
            'about.cta' => [[
                'eyebrow' => 'Work with us',
                'title' => "Let's build the next draw together.",
                'meta' => ['button_label' => 'Talk to REL', 'button_url' => '/contact', 'secondary_label' => 'Partner with us', 'secondary_url' => '/partnerships'],
            ]],
            'raffles.formats' => [
                ['title' => 'Standard Presenter Draw', 'icon' => 'radio', 'eyebrow' => 'Presenter-run, live in studio', 'body' => 'The everyday draw. A presenter runs it live in studio during a show, and the winner is called on air. The country manager and station manager set these draws up before a new week begins.', 'meta' => ['best_for' => 'Best for: daily shows with a loyal live audience.', 'bullets' => "Run live by the presenter\nSet up weekly by the country manager and station manager\nWinners called on air"]],
                ['title' => 'Bonus Draw', 'icon' => 'gift', 'eyebrow' => 'Run by station managers', 'body' => 'A draw that rewards players who have played several times and not yet won. Station managers run it from their own dashboard.', 'meta' => ['best_for' => 'Best for: thanking loyal players and keeping them coming back.', 'bullets' => "Rewards repeat players who have not yet won\nRun by the station manager from the dashboard\nSame random selection as every other draw"]],
                ['title' => 'Jackpot Draw', 'icon' => 'crown', 'eyebrow' => 'Station and country management', 'body' => 'Big-ticket, big-payout draws, run by station management and country managers. They give a station a headline moment to build a campaign around.', 'meta' => ['best_for' => 'Best for: special programmes, holidays and station events.', 'bullets' => "Larger prizes and bigger moments on air\nRun by station management and country managers\nClear winner and payout reporting"]],
            ],
            'technology.capabilities' => [
                ['title' => 'Randomised selection', 'icon' => 'shuffle', 'body' => 'Our proprietary software picks winning tickets at random. The regulator has access to test and vet the algorithm.'],
                ['title' => 'Live dashboards', 'icon' => 'bar-chart', 'body' => 'Presenters, station managers and owners each get a dashboard built for their job, with reports that update in real time.'],
                ['title' => 'Digital payments', 'icon' => 'lock', 'body' => 'Airtel Money and TNM Mpamba are integrated by API for ticket payments and prize payouts, with an instant SMS to every winner.'],
                ['title' => 'Operational visibility', 'icon' => 'eye', 'body' => 'Media houses and strategic partners see how each programme and draw is performing, without waiting for a month-end report.'],
            ],
            'technology.accountability' => [[
                'eyebrow' => 'Designed for accountability',
                'title' => 'The right people see the right information.',
                'body' => 'Technology is only useful when it creates confidence. The platform gives media partners, strategic partners and the regulator the visibility they need to see how each programme is performing.',
            ]],
            'regulation.commitments' => [
                ['title' => 'MAGLA-regulated operations', 'icon' => 'file-check', 'body' => 'REL is a licensed digital gaming operator regulated by the Malawi Gaming and Lotteries Authority (MAGLA), and keeps its operating licence renewed on time.'],
                ['title' => 'Fair draws', 'icon' => 'scale', 'body' => 'Winners are chosen by random, computer-generated selection. The software removes bias, and the regulator can test the algorithm.'],
                ['title' => 'Participant care', 'icon' => 'check-circle', 'body' => 'Dedicated customer care and clear information for players, funded and run by REL.'],
                ['title' => 'Data stewardship', 'icon' => 'lock', 'body' => 'Payments run through integrated Airtel Money and TNM Mpamba business accounts. Security and controls are part of the product, not an afterthought.'],
            ],
            'regulation.standard' => [[
                'eyebrow' => 'Our standard',
                'title' => 'Trust is something we operate.',
                'body' => "We work closely with media partners to make sure promotions are represented accurately, participant journeys are clear and draw administration is handled with care.\n\nAs our products and partnerships grow, this page will carry the relevant licences, notices and public-facing policy documents for participants and partners.",
            ]],
        ];
    }
}
