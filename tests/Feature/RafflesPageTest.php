<?php

use App\Models\Feature;
use Database\Seeders\Content\PagesFeaturesSeeder;
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
    $this->seed(PagesFeaturesSeeder::class);
    $this->seed(PagesFeaturesSeeder::class);

    $this->get('/raffles')->assertInertia(fn (Assert $page) => $page
        ->has('page.title')
        ->has('features.formats', 3)
        ->has('features.band', 1)
        ->has('features.cards', 2));

    expect(Feature::where('page_slug', 'raffles')->count())->toBe(6);
});
