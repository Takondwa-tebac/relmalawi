<?php

namespace Database\Seeders\Content;

use App\Models\Feature;
use Illuminate\Database\Seeder;

/**
 * New Raffles page blocks. The page intro and the three formats live in PagesFeaturesSeeder.
 */
class RafflesPageSeeder extends Seeder
{
    public function run(): void
    {
        // Retire the two legacy cards that the new layout replaces (matched by their old titles).
        Feature::query()
            ->where('page_slug', 'raffles')
            ->where('group', 'raffles.cards')
            ->whereIn('title', ['Managed end to end', 'Made for partners'])
            ->delete();

        foreach ($this->features() as $group => $items) {
            foreach (array_values($items) as $index => $item) {
                Feature::query()->updateOrCreate(
                    ['page_slug' => 'raffles', 'group' => $group, 'sort_order' => $index + 1],
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
            'raffles.campaign' => [[
                'eyebrow' => 'Branded campaigns',
                'title' => 'Your station, your campaign',
                'icon' => 'gift',
                'body' => 'Media partners are encouraged to build a branded campaign around the raffle that suits their audience. You have creative freedom to brand the product and shape the campaign name, the teasers and the adverts, then draw in more listeners and viewers with the prizes.',
                'meta' => ['bullets' => "Name the campaign your own way\nWrite your own teasers and adverts\nUse in-house jingles and hype scripts\nGive presenters real ownership of the product"]],
            ],
            'raffles.channelsIntro' => [[
                'eyebrow' => 'How stations promote it',
                'title' => 'Five ways to get listeners playing',
                'body' => 'Stations promote the raffle on their own channels, at a frequency agreed up front. These are the five channels.',
            ]],
            'raffles.channels' => [
                ['title' => 'Live presenter mentions', 'icon' => 'mic', 'body' => 'Presenters talk about the raffle and the shortcode during their shows.'],
                ['title' => 'Adverts, jingles and hypes', 'icon' => 'radio', 'body' => 'Short, catchy content produced in-house keeps the raffle on air all day.'],
                ['title' => 'Live announcement of winners', 'icon' => 'crown', 'body' => 'Winners are announced and called live, so listeners hear the draw happen.'],
                ['title' => 'Live interviews with winners', 'icon' => 'users', 'body' => 'Hearing a real winner react is the best invitation for new players.'],
                ['title' => 'Replays of winner testimonials', 'icon' => 'headset', 'body' => 'Recorded winner stories are replayed across the schedule to keep interest up.'],
            ],
            'raffles.band' => [[
                'eyebrow' => 'Managed end to end',
                'title' => 'One operating layer, from entry to payout',
                'body' => 'Stations should not have to run the machinery. REL provides the platform and supports the people who use it, so a draw can go from ticket to winner without extra work on your side.',
                'meta' => ['bullets' => "Entries\nDraw configuration\nWinner reporting\nPayout workflows"],
            ]],
            'raffles.cta' => [[
                'eyebrow' => 'Run a raffle on your station',
                'title' => 'Let us build it with you.',
                'meta' => ['button_label' => 'Become a partner', 'button_url' => '/partnerships', 'secondary_label' => 'Contact us', 'secondary_url' => '/contact'],
            ]],
        ];
    }
}
