<?php

use App\Models\Feature;
use Database\Seeders\Content\AboutTechRegulationSeeder;
use Database\Seeders\Content\PagesFeaturesSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('passes published technology features grouped and ordered', function () {
    Feature::factory()->forGroup('technology.capabilities')->create(['title' => 'Second', 'sort_order' => 2]);
    Feature::factory()->forGroup('technology.capabilities')->create(['title' => 'First', 'sort_order' => 1]);
    Feature::factory()->forGroup('technology.capabilities')->unpublished()->create(['title' => 'Hidden']);
    Feature::factory()->forGroup('contact.other')->create(['title' => 'Other page']);

    $this->get('/technology')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Technology')
        ->has('features.capabilities', 2)
        ->where('features.capabilities.0.title', 'First')
        ->where('features.capabilities.1.title', 'Second')
        ->missing('features.other'));
});

it('serves the seeded technology copy', function () {
    $this->seed(PagesFeaturesSeeder::class);
    $this->seed(PagesFeaturesSeeder::class);

    $this->get('/technology')->assertInertia(fn (Assert $page) => $page
        ->has('page.title')
        ->has('features.capabilities', 4)
        ->has('features.accountability', 1));

    expect(Feature::where('page_slug', 'technology')->count())->toBe(5);
});

it('serves the rich technology sections and stays idempotent', function () {
    foreach ([1, 2] as $_) {
        $this->seed(PagesFeaturesSeeder::class);
        $this->seed(AboutTechRegulationSeeder::class);
    }

    $this->get('/technology')->assertInertia(fn (Assert $page) => $page
        ->has('features.rows', 2)
        ->has('features.dashboards', 3)
        ->has('features.reports', 7)
        ->has('features.ussd', 1)
        ->has('features.runs', 4)
        ->has('features.cta', 1));

    expect(Feature::where('page_slug', 'technology')->count())->toBe(26);
});
