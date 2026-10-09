<?php

use App\Enums\PartnerType;
use App\Models\Feature;
use App\Models\Partner;
use Database\Seeders\Content\LeadershipPartnershipsSeeder;
use Database\Seeders\Content\PeoplePartnershipsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('lists only published media partners in sort order', function () {
    Partner::factory()->create(['name' => 'B Radio', 'type' => PartnerType::Radio, 'sort_order' => 2]);
    Partner::factory()->create(['name' => 'A TV', 'type' => PartnerType::Television, 'sort_order' => 1]);
    Partner::factory()->unpublished()->create(['name' => 'Hidden', 'sort_order' => 0]);

    $this->get('/partnerships')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Partnerships')
            ->has('partners', 2)
            ->where('partners.0.name', 'A TV')
            ->where('partners.0.type', 'Television')
            ->where('partners.0.logo_url', null)
            ->where('partners.1.name', 'B Radio')
            ->has('payment_partners', 0));
});

it('shows mobile-money partners only in the payments row', function () {
    Partner::factory()->create(['name' => 'A TV', 'type' => PartnerType::Television, 'sort_order' => 1]);
    Partner::factory()->create(['name' => 'Wallet Co', 'type' => PartnerType::MobileMoney, 'sort_order' => 2]);

    $this->get('/partnerships')->assertInertia(fn (Assert $page) => $page
        ->has('partners', 1)
        ->where('partners.0.name', 'A TV')
        ->has('payment_partners', 1)
        ->where('payment_partners.0.name', 'Wallet Co'));
});

it('seeds the partners idempotently and attaches known logos', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    $this->seed(PeoplePartnershipsSeeder::class);

    expect(Partner::count())->toBe(9);
    expect(Partner::where('name', 'MBC TV 1')->first()->hasMedia('logo'))->toBeTrue();
    expect(Partner::where('name', 'Zodiak')->first()->type)->toBe(PartnerType::RadioAndTelevision);
    expect(Partner::where('name', 'Airtel Money')->first()->type)->toBe(PartnerType::MobileMoney);
    expect(Partner::where('name', 'TNM Mpamba')->first()->hasMedia('logo'))->toBeTrue();

    $this->get('/partnerships')->assertInertia(fn (Assert $page) => $page
        ->has('partners', 7)
        ->has('payment_partners', 2)
        ->where('payment_partners.0.name', 'Airtel Money'));
});

it('seeds the partnerships page copy and feature groups', function () {
    $this->seed(LeadershipPartnershipsSeeder::class);
    $this->seed(LeadershipPartnershipsSeeder::class);

    expect(Feature::where('page_slug', 'partnerships')->where('group', 'partnerships.get')->count())->toBe(6);

    $this->get('/partnerships')->assertInertia(fn (Assert $page) => $page
        ->where('page.eyebrow', 'For media houses')
        ->has('features.hero', 2)
        ->has('features.offers', 3)
        ->has('features.get', 6)
        ->has('features.ask', 4)
        ->has('features.earn', 1)
        ->has('features.roles', 3)
        ->has('features.steps', 4)
        ->where('features.cta.0.meta.button_url', '/contact'));
});

it('keeps commission percentages out of the seeded copy', function () {
    $this->seed(LeadershipPartnershipsSeeder::class);

    $text = Feature::where('page_slug', 'partnerships')->get()->map(fn ($f) => $f->title.' '.$f->body)->implode(' ');

    expect($text)->not->toMatch('/\d+\s?%/');
});
