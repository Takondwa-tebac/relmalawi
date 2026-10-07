<?php

use App\Filament\Admin\Resources\Roles\Pages\CreateRole;
use App\Filament\Admin\Resources\Roles\Pages\EditRole;
use App\Filament\Admin\Resources\Roles\Pages\ListRoles;
use App\Filament\Admin\Resources\Users\Pages\EditUser;
use App\Filament\Admin\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->admin = User::factory()->create()->assignRole('super-admin');
    $this->actingAs($this->admin);
});

it('creates a role with grouped permissions', function () {
    Livewire::test(CreateRole::class)
        ->fillForm(['name' => 'moderator', 'permission_groups' => [0 => ['view campaigns', 'update campaigns']]])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Role::findByName('moderator')->permissions->pluck('name')->sort()->values()->all())
        ->toBe(['update campaigns', 'view campaigns']);
});

it('loads and updates a roles permissions', function () {
    $role = Role::findByName('editor');

    Livewire::test(EditRole::class, ['record' => $role->getRouteKey()])
        ->assertOk()
        ->fillForm(['permission_groups' => [0 => ['view campaigns']]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($role->fresh()->permissions->pluck('name')->all())->toBe(['view campaigns']);
});

it('does not allow deleting built-in roles', function () {
    $role = Role::findByName('editor');

    Livewire::test(EditRole::class, ['record' => $role->getRouteKey()])
        ->assertActionHidden(DeleteAction::class);

    $custom = Role::create(['name' => 'temp', 'guard_name' => 'web']);
    Livewire::test(ListRoles::class)->callTableAction('delete', $custom);

    expect(Role::where('name', 'temp')->exists())->toBeFalse();
});

it('lets a super-admin assign roles to another user', function () {
    $other = User::factory()->create();

    Livewire::test(EditUser::class, ['record' => $other->getRouteKey()])
        ->fillForm(['roles' => [Role::findByName('editor')->getKey()]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($other->fresh()->hasRole('editor'))->toBeTrue();
});

it('stops a super-admin editing their own roles', function () {
    Livewire::test(EditUser::class, ['record' => $this->admin->getRouteKey()])
        ->assertFormFieldDisabled('roles');

    expect($this->admin->fresh()->hasRole('super-admin'))->toBeTrue();
});

it('stops a super-admin deleting themselves but not others', function () {
    $other = User::factory()->create();

    expect($this->admin->can('delete', $this->admin))->toBeFalse()
        ->and($this->admin->can('delete', $other))->toBeTrue();

    Livewire::test(ListUsers::class)->assertTableActionHidden('delete', $this->admin);
});

it('hides the roles field from users without manage users', function () {
    $role = Role::create(['name' => 'user-editor', 'guard_name' => 'web']);
    $role->givePermissionTo(['view users', 'update users']);
    $this->actingAs(User::factory()->create()->assignRole($role));

    Livewire::test(EditUser::class, ['record' => User::factory()->create()->getRouteKey()])
        ->assertFormFieldHidden('roles');
});
