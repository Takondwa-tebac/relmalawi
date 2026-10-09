<?php

use App\Models\Feature;
use App\Models\TeamMember;
use Database\Seeders\Content\LeadershipPartnershipsSeeder;
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
                ->hasAll(['id', 'name', 'role', 'summary', 'bio', 'photo_url'])));
});

it('seeds the real leaders idempotently with photos', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    $this->seed(PeoplePartnershipsSeeder::class);

    expect(TeamMember::count())->toBe(3);
    expect(TeamMember::query()->ordered()->pluck('name')->all())->toBe(['Kobbina Awuah', 'Ike Kyei', 'Michael Kampani']);
    expect(TeamMember::where('name', 'Kobbina Awuah')->first()->role)->toBe('Co-Founder');
    expect(TeamMember::where('name', 'Ike Kyei')->first()->role)->toBe('Leadership team');
    expect(TeamMember::query()->get()->every(fn ($m) => $m->hasMedia('photo') && $m->summary !== null))->toBeTrue();
    expect(TeamMember::where('name', 'Michael Kampani')->first()->bio)->toContain("\n");
});

it('removes only the placeholder people and keeps editor additions', function () {
    foreach (['Martha Chirwa', 'Lloyd Banda', 'Thoko Mbewe', 'Patrick Manda', 'Editor Added'] as $name) {
        TeamMember::factory()->create(['name' => $name]);
    }

    $this->seed(PeoplePartnershipsSeeder::class);

    expect(TeamMember::whereIn('name', ['Martha Chirwa', 'Lloyd Banda', 'Thoko Mbewe', 'Patrick Manda'])->exists())->toBeFalse();
    expect(TeamMember::where('name', 'Editor Added')->exists())->toBeTrue();
});

it('does not overwrite edits to a leader on re-seed', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    TeamMember::where('name', 'Ike Kyei')->update(['role' => 'Chief Operating Officer']);

    $this->seed(PeoplePartnershipsSeeder::class);

    expect(TeamMember::where('name', 'Ike Kyei')->first()->role)->toBe('Chief Operating Officer');
});

it('seeds the people page copy and feature groups', function () {
    $this->seed(PeoplePartnershipsSeeder::class);
    $this->seed(LeadershipPartnershipsSeeder::class);
    $this->seed(LeadershipPartnershipsSeeder::class);

    expect(Feature::where('page_slug', 'people')->where('group', 'people.principles')->count())->toBe(3);

    $this->get('/people')->assertInertia(fn (Assert $page) => $page
        ->where('page.eyebrow', 'Leadership')
        ->has('team', 3)
        ->has('features.principles', 3)
        ->has('features.brings', 4)
        ->has('features.join', 1));
});
