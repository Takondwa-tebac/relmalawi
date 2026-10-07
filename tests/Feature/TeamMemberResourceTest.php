<?php

use App\Filament\Admin\Resources\TeamMembers\Pages\CreateTeamMember;
use App\Filament\Admin\Resources\TeamMembers\Pages\EditTeamMember;
use App\Filament\Admin\Resources\TeamMembers\Pages\ListTeamMembers;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
    Filament::setCurrentPanel('admin');
});

it('lists team members', function () {
    $members = TeamMember::factory()->count(3)->create();

    Livewire::test(ListTeamMembers::class)
        ->assertOk()
        ->assertCanSeeTableRecords($members);
});

it('creates a team member', function () {
    Livewire::test(CreateTeamMember::class)
        ->fillForm(['name' => 'Jane Doe', 'role' => 'Producer', 'bio' => 'Bio', 'sort_order' => 5, 'is_published' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TeamMember::where('name', 'Jane Doe')->exists())->toBeTrue();
});

it('validates required fields on create', function () {
    Livewire::test(CreateTeamMember::class)
        ->fillForm(['name' => '', 'role' => ''])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'role' => 'required']);
});

it('edits a team member', function () {
    $member = TeamMember::factory()->create();

    Livewire::test(EditTeamMember::class, ['record' => $member->getRouteKey()])
        ->assertOk()
        ->fillForm(['name' => 'Renamed', 'is_published' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($member->refresh())->name->toBe('Renamed')->is_published->toBeFalse();
});

it('keeps non-staff out of the panel', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/team-members')->assertForbidden();
});
