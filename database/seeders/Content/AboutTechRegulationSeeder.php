<?php

namespace Database\Seeders\Content;

use App\Models\Feature;
use Illuminate\Database\Seeder;

/**
 * Extra rich-page sections for About, Technology and Regulation.
 *
 * Idempotent: rows are matched by (page_slug, group, sort_order) and updated in
 * place; rows added by editors in the CMS are never deleted.
 *
 * Body convention: paragraphs first, then lines starting with "- " render as tick bullets.
 */
class AboutTechRegulationSeeder extends Seeder
{
    public function run(): void
    {
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
     * @return array<string, list<array<string, mixed>>>
     */
    private function features(): array
    {
        return [
            'about.story' => [[
                'eyebrow' => 'Our story',
                'title' => 'Started in Ghana. Built for Malawi.',
                'icon' => 'radio',
                'body' => "Radio Entertainment Limited is a licensed digital gaming operator. The idea has its roots in Ghana, where the team set out to use the reach of radio and television networks to bring gaming to an entirely new group of people.\n\nWe chose radio and TV because they reach people across income, gender and age. REL does not own stations. We partner with them.\n- Licensed digital gaming operator\n- Roots in Ghana, built for Malawi\n- Partners with stations, never owns them\n- Established in 2024",
            ]],
            'about.servicesIntro' => [[
                'eyebrow' => 'What we do',
                'title' => 'Three things, done end to end.',
                'body' => 'We use technology to widen the market for gaming in Malawi, and we run the operation behind it.',
            ]],
            'about.services' => [
                ['title' => 'Raffle draw management', 'icon' => 'ticket', 'body' => 'Standard presenter draws, bonus draws and jackpot draws, set up and managed on one platform.'],
                ['title' => 'Media games', 'icon' => 'gamepad', 'body' => 'Raffles that fit the shows stations already run. Partners brand the campaign and shape the teasers and adverts.'],
                ['title' => 'Technology and operations', 'icon' => 'cog', 'body' => 'USSD, payments, dashboards, IT support and customer care. We build it, run it and support it.'],
            ],
            'about.stepsIntro' => [[
                'eyebrow' => 'How a partnership works',
                'title' => 'Three steps to go live.',
            ]],
            'about.steps' => [
                ['title' => 'Agree the campaign', 'icon' => 'handshake', 'body' => 'We agree the shape of the campaign with the station, with a dedicated relationship manager for day-to-day work.'],
                ['title' => 'REL builds and runs the platform', 'icon' => 'cog', 'body' => 'We provide the technology, train station staff and carry the operating costs of the platform.'],
                ['title' => 'The station promotes and draws live', 'icon' => 'mic', 'body' => 'Presenters promote the raffle, run the draw live in studio and call the winners on air.'],
            ],
            'about.team' => [[
                'eyebrow' => 'Leadership',
                'title' => 'The people behind REL.',
                'body' => 'A small leadership team with experience in strategy, operations and finance across Africa.',
                'meta' => ['button_label' => 'Meet the team', 'button_url' => '/people'],
            ]],

            'technology.rows' => [
                [
                    'eyebrow' => 'Randomised, testable draws',
                    'title' => 'No bias in who wins.',
                    'icon' => 'shuffle',
                    'body' => "Winners are picked by REL's proprietary software using random, computer-generated selection. The regulator is given access to the system to test and vet the algorithm.\n- Random selection of winning tickets\n- Built to remove bias\n- Regulator can test and vet the algorithm\n- Presenters run the draw live in studio",
                ],
                [
                    'eyebrow' => 'Payments',
                    'title' => 'Collected and paid by mobile money.',
                    'icon' => 'wallet',
                    'body' => "REL is integrated by API with Airtel Money and TNM Mpamba. Company business accounts with both networks handle collections and disbursements.\n- Collections from players (C2B)\n- Prizes paid to winners (B2C)\n- Instant payout once the software selects a winner\n- Instant SMS to the winner",
                ],
            ],
            'technology.dashboardsIntro' => [[
                'eyebrow' => 'Dashboards for every role',
                'title' => 'Each person sees what they need.',
                'body' => 'Access is role-based, so presenters, station managers and owners each work from the view that suits their job.',
            ]],
            'technology.dashboards' => [
                ['title' => 'Presenters', 'icon' => 'mic', 'body' => "A presenter dashboard for running the programme's draws.\n- Their own programme's draws\n- Their own programme's sales"],
                ['title' => 'Station managers', 'icon' => 'building', 'body' => "A view across the whole station.\n- Station and shows\n- Jackpot draws\n- Bonus draws\n- Admin draws\n- Revenue reports\n- Payouts"],
                ['title' => 'Directors and owners', 'icon' => 'users', 'body' => "The numbers that matter to the business.\n- Revenue reports\n- Hourly performance\n- Show summary\n- Daily awarding report"],
            ],
            'technology.reportsIntro' => [[
                'eyebrow' => 'Reports you can see in real time',
                'title' => 'Everyone works from the same numbers.',
                'body' => 'Strategic partners and media houses get dashboards with real-time reports, and more on request.',
            ]],
            'technology.reports' => [
                ['title' => 'Revenue reports'],
                ['title' => 'Hourly performance'],
                ['title' => 'Show summary'],
                ['title' => 'Daily awards report'],
                ['title' => 'Growth trends'],
                ['title' => 'Transactions report'],
                ['title' => 'Winners report'],
            ],
            'technology.ussd' => [[
                'eyebrow' => 'USSD, built for every phone',
                'title' => 'Dial a short code and play.',
                'icon' => 'smartphone',
                'body' => "Players enter by dialling a short code, one for each media station. USSD works on the phone people already have.\n- No app to download\n- No mobile data needed\n- Pick the station, choose your tickets, pay with your mobile-money PIN",
            ]],
            'technology.runsIntro' => [[
                'eyebrow' => 'What REL runs for you',
                'title' => 'We carry the technical load.',
                'body' => 'These are REL responsibilities in every media partnership.',
            ]],
            'technology.runs' => [
                ['title' => 'USSD application design and implementation', 'icon' => 'smartphone'],
                ['title' => 'Ongoing IT support', 'icon' => 'cog'],
                ['title' => 'Customer care', 'icon' => 'headset'],
                ['title' => 'SMS and mobile-money transaction fees', 'icon' => 'wallet'],
            ],
            'technology.cta' => [[
                'eyebrow' => 'See it in action',
                'title' => 'Want the platform behind your station?',
                'meta' => ['button_label' => 'Talk to REL', 'button_url' => '/contact', 'secondary_label' => 'Partner with us', 'secondary_url' => '/partnerships'],
            ]],

            'regulation.rows' => [
                [
                    'eyebrow' => 'Fair by design',
                    'title' => 'Chosen at random. Open to testing.',
                    'icon' => 'scale',
                    'body' => "Winners are selected by random, computer-generated selection, and the software is built to remove bias. The gaming regulator is given access to the system to test and vet the winner-selection algorithm.\n- Random selection\n- No bias by design\n- Regulator access to test the algorithm",
                ],
                [
                    'eyebrow' => 'Accountable payouts',
                    'title' => 'Paid straight to the winner.',
                    'icon' => 'wallet',
                    'body' => "Prizes are paid from funds in the company's merchant account directly into winners' mobile-money wallets, with an instant SMS.\n- Winners are called live on air\n- Stations interview winners live\n- Results are public, not private",
                ],
                [
                    'eyebrow' => 'Transparent partners',
                    'title' => 'Partners see the numbers too.',
                    'icon' => 'eye',
                    'body' => "Strategic partners and media houses have dashboards with real-time reports, so nobody has to wait or guess.\n- Revenue and hourly performance\n- Daily awards and winners reports\n- Transactions report",
                ],
            ],
            'regulation.promisesIntro' => [[
                'eyebrow' => 'What REL commits to',
                'title' => 'Our side of the bargain.',
                'body' => 'These are REL responsibilities, in every partnership.',
            ]],
            'regulation.promises' => [
                ['title' => 'Keep the operating licence renewed on time'],
                ['title' => 'Build and provide the technology and platform the raffle runs on'],
                ['title' => 'Train station staff and give permanent support on running the raffle end to end'],
                ['title' => 'Provide a dedicated relationship manager for day-to-day activity'],
                ['title' => 'Research and develop new product features'],
                ['title' => 'Cover operating costs, including USSD, IT support, customer care, and SMS and mobile-money fees'],
            ],
            'regulation.cta' => [[
                'eyebrow' => 'Questions about how we operate?',
                'title' => 'Talk to us.',
                'meta' => ['button_label' => 'Contact REL', 'button_url' => '/contact'],
            ]],
        ];
    }
}
