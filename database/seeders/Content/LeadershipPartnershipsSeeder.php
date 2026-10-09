<?php

namespace Database\Seeders\Content;

use App\Models\Feature;
use App\Models\Page;
use Illuminate\Database\Seeder;

/**
 * Page intros and Feature rows for /people and /partnerships. Idempotent.
 * Team members and partners themselves are seeded by PeoplePartnershipsSeeder.
 */
class LeadershipPartnershipsSeeder extends Seeder
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
            'people' => [
                'eyebrow' => 'Leadership',
                'title' => 'The people',
                'title_accent' => 'behind REL.',
                'description' => 'REL is led by operators with experience in strategy, telecoms, consulting and finance across Africa. They built the company to bring gaming products to a new audience through radio and television, and to give media houses a new source of revenue.',
                'meta_title' => 'Leadership | Radio Entertainment Limited',
                'meta_description' => 'Meet the leadership team behind Radio Entertainment Limited, a licensed digital gaming operator partnering with radio and television stations in Malawi.',
            ],
            'partnerships' => [
                'eyebrow' => 'For media houses',
                'title' => 'Turn your airtime into',
                'title_accent' => 'a new revenue line.',
                'description' => 'REL does not own stations. We partner with them. You bring the audience and the voices. We bring the licence, the platform, the training and the support, so your listeners and viewers can play raffle draws on the shows they already love.',
                'meta_title' => 'Partner with REL | Radio Entertainment Limited',
                'meta_description' => 'Radio and television stations in Malawi can partner with REL to run live raffle draws and earn commission on net revenue. See what you get and what we ask.',
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
            // ---- People ----
            'people.bringsIntro' => [[
                'eyebrow' => 'What the team brings',
                'title' => 'Strategy, operations and finance experience.',
            ]],
            'people.brings' => [
                ['title' => '2', 'body' => 'Harvard Business School MBAs on the leadership team.'],
                ['title' => '10+', 'body' => 'Years of strategy and management experience in Sub-Saharan African markets, including as Head of Strategy at Tigo Tanzania.'],
                ['title' => 'McKinsey', 'body' => 'Consulting experience in financial services, telecoms and the public sector across Africa.'],
                ['title' => 'CA (Mw)', 'body' => 'Chartered Accountant with finance leadership roles at G4S and GardaWorld across Africa.'],
            ],
            'people.principlesIntro' => [[
                'eyebrow' => 'How we work',
                'title' => "No spectators.\nOnly collaborators.",
            ]],
            'people.principles' => [
                ['title' => '01', 'body' => 'Listen first. Every partner and audience has context.'],
                ['title' => '02', 'body' => 'Build together. We do not parachute in with a script.'],
                ['title' => '03', 'body' => 'Own the outcome. Clear reporting is part of the work.'],
            ],
            'people.join' => [[
                'eyebrow' => 'Join the network',
                'title' => 'Run a station, build media games or want to work with us?',
                'body' => 'Tell us who you are and what you have in mind.',
                'meta' => ['button_label' => 'Introduce yourself'],
            ]],

            // ---- Partnerships ----
            'partnerships.hero' => [
                ['title' => 'Start a conversation', 'meta' => ['button_url' => '/contact']],
                ['title' => 'See how it works', 'meta' => ['button_url' => '/how-it-works']],
            ],
            'partnerships.offers' => [
                [
                    'title' => 'Live raffle draws',
                    'icon' => 'gamepad',
                    'body' => 'Presenter draws, bonus draws and jackpot draws, all run through REL\'s platform and drawn live in your studio.',
                ],
                [
                    'title' => 'Your own campaign',
                    'icon' => 'mic',
                    'body' => 'Name the campaign, write the teasers and make the adverts. You have creative freedom to brand the game for your audience.',
                ],
                [
                    'title' => 'Commission on net revenue',
                    'icon' => 'wallet',
                    'body' => 'Media houses and presenters earn a commission, with regular reports showing what each programme brought in.',
                ],
            ],
            'partnerships.mediaIntro' => [[
                'eyebrow' => 'Trusted by media houses',
                'title' => 'The stations carrying the draws.',
                'body' => 'Each station has its own USSD shortcode and its own shows. These are the media houses already partnering with REL.',
            ]],
            'partnerships.paymentsIntro' => [[
                'eyebrow' => 'Payments partners',
                'title' => 'Paid through mobile money.',
                'body' => 'Players pay for tickets and receive prizes through Airtel Money and TNM Mpamba. REL is integrated with both, so winners are paid out instantly.',
            ]],
            'partnerships.getIntro' => [[
                'eyebrow' => 'What you get with REL',
                'title' => 'We run the heavy lifting.',
                'body' => 'Your team focuses on the shows and the audience. REL takes responsibility for the rest.',
            ]],
            'partnerships.get' => [
                ['title' => 'Licence renewal', 'icon' => 'file-check', 'body' => 'We keep the operating licence renewed on time, so you can partner with confidence.'],
                ['title' => 'Technology platform', 'icon' => 'cog', 'body' => 'We build and provide the technology infrastructure and platform the raffle runs on.'],
                ['title' => 'Training and permanent support', 'icon' => 'users', 'body' => 'We train your staff and give permanent support on the end-to-end running of the raffle.'],
                ['title' => 'A dedicated relationship manager', 'icon' => 'handshake', 'body' => 'One named contact for your day-to-day activity.'],
                ['title' => 'Ongoing product research', 'icon' => 'bar-chart', 'body' => 'We research and develop new product features, so the game keeps improving.'],
                ['title' => 'Operating costs covered', 'icon' => 'wallet', 'body' => 'USSD application design and implementation, IT support, customer care, and SMS and mobile-money transaction fees are on REL.'],
            ],
            'partnerships.askIntro' => [[
                'eyebrow' => 'What we ask of media partners',
                'title' => 'A partnership works when both sides show up.',
                'body' => 'These are the responsibilities that sit with the media house.',
            ]],
            'partnerships.ask' => [
                ['title' => 'Promote the raffle', 'icon' => 'radio', 'body' => 'Promote and advertise the raffle on your channels at the agreed frequency, using content produced in-house, mainly jingles and hype scripts.'],
                ['title' => 'Draw live, call winners live', 'icon' => 'mic', 'body' => 'Run the draws live in studio on at least 4 live shows a day, and call the winners live on air.'],
                ['title' => 'Measure your reach', 'icon' => 'bar-chart', 'body' => 'Evaluate how effective the marketing and reach of the raffle is across your channels.'],
                ['title' => 'Build ownership', 'icon' => 'users', 'body' => 'Create and keep a sense of ownership of the product among your presenters and across the media company.'],
            ],
            'partnerships.earn' => [[
                'eyebrow' => 'How partners earn',
                'title' => 'You earn when the game earns.',
                'body' => "Media houses earn a commission calculated on net revenue, which is revenue minus payouts. It is paid monthly.\n\nPresenters and middle management earn a commission based on their own programme's sales. It is paid weekly.\n\nThe commission rate is agreed with each media house. Management receives regular reports showing each programme's revenue and the commissions due.",
            ]],
            'partnerships.rolesIntro' => [[
                'eyebrow' => 'A dashboard for every role',
                'title' => 'Everyone sees what they need.',
                'body' => 'Each person at the station gets a view built for their job, with real-time reports.',
            ]],
            'partnerships.roles' => [
                ['title' => 'Presenters', 'icon' => 'mic', 'body' => 'A presenter dashboard to run the draws on their shows and follow their programme.'],
                ['title' => 'Station managers', 'icon' => 'radio', 'body' => 'See the station, its shows, jackpot draws, bonus draws and admin draws, plus revenue reports and payouts.'],
                ['title' => 'Directors and owners', 'icon' => 'building', 'body' => 'Revenue reports, hourly performance, show summary and the daily awarding report.'],
            ],
            'partnerships.stepsIntro' => [[
                'eyebrow' => 'Getting started',
                'title' => 'A typical flow from hello to first draw.',
                'body' => 'Every station is different, so the details are agreed with you.',
            ]],
            'partnerships.steps' => [
                ['title' => 'Start a conversation', 'body' => 'Tell us about your station, your shows and your audience. We explain how REL works and answer your questions.'],
                ['title' => 'Agree the campaign and commission', 'body' => 'Agree the promotion plan, your campaign name and the commission arrangement for your media house and presenters.'],
                ['title' => 'Training and go-live', 'body' => 'We train your team, set up your station shortcode and dashboards, and your relationship manager supports your day-to-day activity.'],
                ['title' => 'Live draws and reporting', 'body' => 'Presenters run the draws live and call winners on air. Management receives reports on revenue and commissions due.'],
            ],
            'partnerships.cta' => [[
                'eyebrow' => 'Partner with REL',
                'title' => 'Ready to bring raffles to your airtime?',
                'meta' => ['button_label' => 'Start a conversation', 'button_url' => '/contact'],
            ]],
        ];
    }
}
