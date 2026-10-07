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
                'title' => 'A licensed',
                'title_accent' => 'media game operator.',
                'description' => 'Radio Entertainment Limited (Malawi) is a licensed digital gaming operator using innovative technology to expand the addressable market for gaming in Malawi. We bring together gaming mechanics, media reach and responsible operations.',
            ],
            'raffles' => [
                'eyebrow' => 'Our products',
                'title' => 'Games made',
                'title_accent' => 'for airtime.',
                'description' => 'Our core offering is raffle draw management and media games. Umoja Promo Raffle and Pompo Draws are built to give stations flexible formats for engaging audiences through the programming they already love.',
            ],
            'technology' => [
                'eyebrow' => 'The platform',
                'title' => 'Built for',
                'title_accent' => 'transparency.',
                'description' => 'Our technology connects participation, media operations, randomised draw management, reporting and digital payouts into one accountable system.',
            ],
            'regulation' => [
                'eyebrow' => 'Regulation & trust',
                'title' => 'Good games',
                'title_accent' => 'need good governance.',
                'description' => 'REL is a licensed digital gaming operator regulated by the Malawi Gaming and Lotteries Authority (MAGLA). Our responsibility is to make every game understandable, every draw properly managed and every partnership operated with integrity.',
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
                'body' => 'We do not hold radio stations or television channels. We work with them. REL provides the product, draw management, technology and operating discipline that lets media partners deliver engaging games with confidence.',
            ]],
            'about.stats' => [
                ['title' => '01', 'body' => 'One connected operating layer for partners, participants and promotions.'],
                ['title' => '40', 'body' => 'Dynamic participation codes designed for accessible media games.'],
            ],
            'about.guides' => [['title' => 'What guides us']],
            'about.pillars' => [
                ['title' => 'Media-led reach', 'icon' => 'radio', 'body' => 'We use the reach, trust and immediacy of Malawi radio and television partners to connect products with real audiences.'],
                ['title' => 'Technology with purpose', 'icon' => 'gamepad', 'body' => 'Our digital tools make participation simple, trackable and accessible across the devices people already use.'],
                ['title' => 'Responsible play', 'icon' => 'shield-check', 'body' => 'Clear mechanics, transparent draws and responsible communications are not extras. They are how we earn trust.'],
            ],
            'about.cta' => [[
                'eyebrow' => 'Ready to play a bigger role?',
                'title' => "Let's build the next draw together.",
                'meta' => ['button_label' => 'Talk to REL', 'button_url' => '/contact'],
            ]],
            'raffles.formats' => [
                ['title' => 'Standard Presenter Draw', 'icon' => 'radio', 'body' => 'Presenter-run draws configured by station management and integrated into weekly programming.'],
                ['title' => 'Bonus Draw', 'icon' => 'gift', 'body' => 'Reward players who participate repeatedly but have not yet won, with station managers able to manage the format through their dashboard.'],
                ['title' => 'Jackpot Draw', 'icon' => 'crown', 'body' => 'Larger-ticket, higher-payout draws managed by station and country management with clear reporting.'],
            ],
            'raffles.band' => [[
                'eyebrow' => 'Core formats',
                'title' => "Umoja Promo Raffle\nPompo Draws",
            ]],
            'raffles.cards' => [
                ['title' => 'Managed end to end', 'icon' => 'bar-chart', 'body' => 'Entries, draw configuration, winner reporting and payout workflows in one operating layer.'],
                ['title' => 'Made for partners', 'body' => 'Campaign names, teasers and promotions can be shaped by media houses for their own audiences.'],
            ],
            'technology.capabilities' => [
                ['title' => 'Randomised selection', 'icon' => 'shuffle', 'body' => 'Our winner-selection software is designed to randomly select winning tickets, with the algorithm available for regulator testing and vetting.'],
                ['title' => 'Live dashboards', 'icon' => 'bar-chart', 'body' => 'Partners can access revenue, hourly performance, show summaries, daily awards, transaction and winners reports.'],
                ['title' => 'Digital payments', 'icon' => 'lock', 'body' => 'The intended mobile-money infrastructure supports collections, disbursements, SMS notifications and fast prize payments.'],
                ['title' => 'Operational visibility', 'icon' => 'eye', 'body' => 'A shared reporting layer gives media houses and strategic partners visibility into the performance of their programmes and draws.'],
            ],
            'technology.accountability' => [[
                'eyebrow' => 'Designed for accountability',
                'title' => 'The right people see the right information.',
                'body' => 'Technology is only useful when it creates confidence. Our platform is designed to give media partners, strategic partners and regulators the visibility needed to understand how each programme is performing.',
            ]],
            'regulation.commitments' => [
                ['title' => 'MAGLA-regulated operations', 'icon' => 'file-check', 'body' => 'REL operates under the oversight of the Malawi Gaming and Lotteries Authority (MAGLA), within the applicable licensing and regulatory framework for digital gaming in Malawi.'],
                ['title' => 'Fair draws', 'icon' => 'scale', 'body' => 'Our draw mechanics are designed to be clear, auditable and communicated in language people can understand.'],
                ['title' => 'Participant care', 'icon' => 'check-circle', 'body' => 'We take responsible participation seriously, with straightforward information and clear support pathways.'],
                ['title' => 'Data stewardship', 'icon' => 'lock', 'body' => 'Technology and data controls are treated as part of the product, not an afterthought.'],
            ],
            'regulation.standard' => [[
                'eyebrow' => 'Our standard',
                'title' => 'Trust is something we operate.',
                'body' => "We work closely with media partners to make sure promotions are represented accurately, participant journeys are clear and draw administration is handled with care.\n\nAs our products and partnerships grow, this page will carry the relevant licences, notices and public-facing policy documents for participants and partners.",
            ]],
        ];
    }
}
