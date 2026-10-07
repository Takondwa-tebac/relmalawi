<?php

namespace Database\Seeders\Content;

use App\Models\Campaign;
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
}
