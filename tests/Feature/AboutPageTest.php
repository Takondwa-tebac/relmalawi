<?php

use App\Models\Feature;
use App\Models\TeamMember;
use Database\Seeders\Content\AboutTechRegulationSeeder;
use Database\Seeders\Content\PagesFeaturesSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('passes published about features grouped and ordered', function () {
    Feature::factory()->forGroup('about.pillars')->create(['title' => 'Second', 'sort_order' => 2]);
    Feature::factory()->forGroup('about.pillars')->create(['title' => 'First', 'sort_order' => 1]);
    Feature::factory()->forGroup('about.pillars')->unpublished()->create(['title' => 'Hidden']);
    Feature::factory()->forGroup('contact.other')->create(['title' => 'Other page']);

    $this->get('/about')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('About')
        ->has('features.pillars', 2)
        ->where('features.pillars.0.title', 'First')
        ->where('features.pillars.1.title', 'Second')
        ->missing('features.other'));
});

it('serves the seeded about copy', function () {
    $this->seed(PagesFeaturesSeeder::class);
    $this->seed(PagesFeaturesSeeder::class);

    $this->get('/about')->assertInertia(fn (Assert $page) => $page
        ->has('page.title')
        ->has('features.pillars', 3)
        ->has('features.role', 1)
        ->has('features.stats', 3)
        ->has('features.cta', 1));

    expect(Feature::where('page_slug', 'about')->count())->toBe(9);
});

it('serves the rich about sections and stays idempotent', function () {
    foreach ([1, 2] as $_) {
        $this->seed(PagesFeaturesSeeder::class);
        $this->seed(AboutTechRegulationSeeder::class);
    }

    $this->get('/about')->assertInertia(fn (Assert $page) => $page
        ->has('features.story', 1)
        ->has('features.services', 3)
        ->has('features.steps', 3)
        ->has('features.team', 1)
        ->where('team', []));

    expect(Feature::where('page_slug', 'about')->count())->toBe(19);
});

it('passes published team members for the leadership strip', function () {
    TeamMember::factory()->create(['name' => 'Shown', 'is_published' => true]);
    TeamMember::factory()->create(['name' => 'Hidden', 'is_published' => false]);

    $this->get('/about')->assertInertia(fn (Assert $page) => $page
        ->has('team', 1)
        ->where('team.0.name', 'Shown'));
});
