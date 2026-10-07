<?php

use App\Models\Feature;
use Database\Seeders\Content\PagesFeaturesSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('passes published regulation features grouped and ordered', function () {
    Feature::factory()->forGroup('regulation.commitments')->create(['title' => 'Second', 'sort_order' => 2]);
    Feature::factory()->forGroup('regulation.commitments')->create(['title' => 'First', 'sort_order' => 1]);
    Feature::factory()->forGroup('regulation.commitments')->unpublished()->create(['title' => 'Hidden']);
    Feature::factory()->forGroup('contact.other')->create(['title' => 'Other page']);

    $this->get('/regulation')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Regulation')
        ->has('features.commitments', 2)
        ->where('features.commitments.0.title', 'First')
        ->where('features.commitments.1.title', 'Second')
        ->missing('features.other'));
});

it('serves the seeded regulation copy', function () {
    $this->seed(PagesFeaturesSeeder::class);
    $this->seed(PagesFeaturesSeeder::class);

    $this->get('/regulation')->assertInertia(fn (Assert $page) => $page
        ->has('page.title')
        ->has('features.commitments', 4)
        ->has('features.standard', 1));

    expect(Feature::where('page_slug', 'regulation')->count())->toBe(5);
});
