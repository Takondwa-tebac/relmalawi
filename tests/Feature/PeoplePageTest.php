<?php

use App\Models\TeamMember;
use Database\Seeders\Content\PeoplePartnershipsSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('lists only published team members in sort order', function () {
    TeamMember::factory()->create(['name' => 'Second', 'sort_order' => 2]);
    TeamMember::factory()->create(['name' => 'First', 'sort_order' => 1]);
    TeamMember::factory()->unpublished()->create(['name' => 'Hidden', 'sort_order' => 0]);

    $this->get('/people')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('People')
            ->has('team', 2)
            ->where('team.0.name', 'First')
            ->where('team.1.name', 'Second')
            ->has('team.0', fn (Assert $member) => $member
                ->hasAll(['id', 'name', 'role', 'bio', 'photo_url'])));
});

it('seeds the people page copy and team idempotently', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    $this->seed(PeoplePartnershipsSeeder::class);

    expect(TeamMember::count())->toBe(4);

    $this->get('/people')->assertInertia(fn (Assert $page) => $page
        ->where('page.eyebrow', 'The people behind the work')
        ->where('page.title_accent', 'skin in the game.')
        ->has('team', 4));
});
