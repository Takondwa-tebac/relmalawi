<?php

use App\Enums\PartnerType;
use App\Models\Campaign;
use App\Models\Faq;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Stat;
use Database\Seeders\Content\FaqSeeder;
use Database\Seeders\Content\HomeSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('passes only published campaigns, ordered by sort order', function () {
    Campaign::factory()->create(['title' => 'Third', 'sort_order' => 3]);
    Campaign::factory()->create(['title' => 'First', 'sort_order' => 1]);
    Campaign::factory()->unpublished()->create(['title' => 'Hidden', 'sort_order' => 0]);
    Campaign::factory()->create(['title' => 'Second', 'sort_order' => 2]);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->component('Welcome')
        ->has('campaigns', 3)
        ->where('campaigns.0.title', 'First')
        ->where('campaigns.1.title', 'Second')
        ->where('campaigns.2.title', 'Third')
        ->has('campaigns.0.alt')
        ->has('campaigns.0.image'));
});

it('passes hero copy and ordered stats', function () {
    Page::factory()->create(['slug' => 'home', 'title' => 'Umoja', 'title_accent' => 'Promo']);
    Stat::factory()->create(['value' => '02', 'label' => 'Second', 'sort_order' => 2]);
    Stat::factory()->create(['value' => '01', 'label' => 'First', 'sort_order' => 1]);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->where('page.title', 'Umoja')
        ->where('page.title_accent', 'Promo')
        ->has('hero.title_tail')
        ->has('stats', 2)
        ->where('stats.0.label', 'First')
        ->where('stats.1.label', 'Second'));
});

it('seeds the home page idempotently with the existing campaign images', function () {
    $this->seed(HomeSeeder::class);
    $this->seed(HomeSeeder::class);

    expect(Page::where('slug', 'home')->count())->toBe(1)
        ->and(Campaign::count())->toBe(4)
        ->and(Stat::count())->toBe(2);

    Campaign::with('media')->get()->each(fn (Campaign $campaign) => expect($campaign->getMedia('image'))->toHaveCount(1));

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->has('campaigns', 4)
        ->where('campaigns.0.title', 'MBC Raffle'));
});

it('passes home sections, faqs and partner logos split by type', function () {
    $this->seed(HomeSeeder::class);
    $this->seed(FaqSeeder::class);

    $media = Partner::factory()->create(['type' => PartnerType::Radio, 'name' => 'Radio One']);
    $media->addMedia(public_path('images/magla-logo.png'))->preservingOriginal()->toMediaCollection('logo');
    $money = Partner::factory()->create(['type' => PartnerType::MobileMoney, 'name' => 'Airtel Money']);
    $money->addMedia(public_path('images/magla-logo.png'))->preservingOriginal()->toMediaCollection('logo');
    Partner::factory()->create(['type' => PartnerType::Television, 'name' => 'No Logo TV']);

    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->has('features.steps', 4)
        ->has('features.audiences', 3)
        ->has('features.stats', 4)
        ->has('features.cta.0.meta.button_url')
        ->has('mediaPartners', 1)
        ->where('mediaPartners.0.name', 'Radio One')
        ->has('paymentPartners', 1)
        ->where('paymentPartners.0.name', 'Airtel Money')
        ->has('faqs', Faq::forHome()->count()));
});

it('passes empty partner lists when no logos exist', function () {
    $this->get('/')->assertInertia(fn (Assert $page) => $page
        ->has('mediaPartners', 0)
        ->has('paymentPartners', 0)
        ->has('faqs', 0));
});
