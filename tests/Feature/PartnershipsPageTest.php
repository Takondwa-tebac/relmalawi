<?php

use App\Enums\PartnerType;
use App\Models\Partner;
use Database\Seeders\Content\PeoplePartnershipsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('lists only published partners in sort order', function () {
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
            ->where('partners.1.name', 'B Radio'));
});

it('seeds the partners idempotently and attaches known logos', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    $this->seed(PeoplePartnershipsSeeder::class);

    expect(Partner::count())->toBe(7);
    expect(Partner::where('name', 'MBC TV 1')->first()->hasMedia('logo'))->toBeTrue();
    expect(Partner::where('name', 'Zodiak')->first()->type)->toBe(PartnerType::RadioAndTelevision);

    $this->get('/partnerships')->assertInertia(fn (Assert $page) => $page
        ->where('page.eyebrow', 'Our network')
        ->has('partners', 7));
});
