<?php

use App\Models\Campaign;
use App\Models\Feature;
use Database\Seeders\Content\PagesFeaturesSeeder;
use Database\Seeders\Content\RafflesPageSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('passes published raffles features grouped and ordered', function () {
    Feature::factory()->forGroup('raffles.formats')->create(['title' => 'Second', 'sort_order' => 2]);
    Feature::factory()->forGroup('raffles.formats')->create(['title' => 'First', 'sort_order' => 1]);
    Feature::factory()->forGroup('raffles.formats')->unpublished()->create(['title' => 'Hidden']);
    Feature::factory()->forGroup('contact.other')->create(['title' => 'Other page']);

    $this->get('/raffles')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Raffles')
        ->has('features.formats', 2)
        ->where('features.formats.0.title', 'First')
        ->where('features.formats.1.title', 'Second')
        ->missing('features.other'));
});

it('serves the seeded raffles copy', function () {
    // Legacy cards from the earlier seed must be retired by the new seeder.
    Feature::factory()->forGroup('raffles.cards')->create(['title' => 'Made for partners']);

    foreach ([1, 2] as $_) {
        $this->seed(PagesFeaturesSeeder::class);
        $this->seed(RafflesPageSeeder::class);
    }

    $this->get('/raffles')->assertInertia(fn (Assert $page) => $page
        ->has('page.title')
        ->has('features.formats', 3)
        ->has('features.campaign', 1)
        ->has('features.channels', 5)
        ->has('features.band', 1)
        ->has('features.cta', 1)
        ->missing('features.cards')
        ->where('features.formats.0.meta.best_for', fn ($value) => filled($value)));

    expect(Feature::where('page_slug', 'raffles')->count())->toBe(12);
});

it('shows up to four published campaigns', function () {
    Campaign::factory()->count(5)->create();
    Campaign::factory()->unpublished()->create();

    $this->get('/raffles')->assertInertia(fn (Assert $page) => $page
        ->has('campaigns', 4)
        ->has('campaigns.0', fn (Assert $c) => $c->hasAll(['id', 'title', 'alt', 'image', 'thumb'])->etc()));
});
