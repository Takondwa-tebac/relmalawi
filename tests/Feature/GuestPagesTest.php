<?php

use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Inertia\Testing\AssertableInertia as Assert;

it('renders every public page with shared site props', function (string $uri, string $component) {
    $this->get($uri)
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component($component)
            ->has('site.banner_text')
            ->has('page.slug'));
})->with([
    ['/', 'Welcome'],
    ['/about', 'About'],
    ['/how-it-works', 'HowItWorks'],
    ['/raffles', 'Raffles'],
    ['/partnerships', 'Partnerships'],
    ['/technology', 'Technology'],
    ['/people', 'People'],
    ['/regulation', 'Regulation'],
    ['/contact', 'Contact'],
]);

it('serves editable intro copy from the pages table', function () {
    Page::factory()->create(['slug' => 'about', 'title' => 'A licensed', 'title_accent' => 'media game operator.']);

    $this->get('/about')->assertInertia(fn (Assert $page) => $page
        ->where('page.title', 'A licensed')
        ->where('page.title_accent', 'media game operator.'));
});

it('only lets staff roles into the admin panel', function () {
    $this->seed(RolesSeeder::class);
    $panel = Filament::getPanel('admin');

    expect(User::factory()->create()->canAccessPanel($panel))->toBeFalse();
    expect(User::factory()->create()->assignRole('editor')->canAccessPanel($panel))->toBeTrue();
    expect(User::factory()->create()->assignRole('super-admin')->canAccessPanel($panel))->toBeTrue();
});
