<?php

use App\Filament\Admin\Pages\ManageSiteSettings;
use App\Filament\Admin\Resources\Campaigns\CampaignResource;
use App\Filament\Admin\Resources\ContactMessages\ContactMessageResource;
use App\Filament\Admin\Resources\Faqs\FaqResource;
use App\Filament\Admin\Resources\Features\FeatureResource;
use App\Filament\Admin\Resources\HowItWorksSteps\HowItWorksStepResource;
use App\Filament\Admin\Resources\Pages\PageResource;
use App\Filament\Admin\Resources\Partners\PartnerResource;
use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Filament\Admin\Resources\Stats\StatResource;
use App\Filament\Admin\Resources\TeamMembers\TeamMemberResource;
use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Faq;
use App\Models\Feature;
use App\Models\HowItWorksStep;
use App\Models\Page;
use App\Models\Partner;
use App\Models\Stat;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
});

/**
 * Resource => [model, editor can list, editor can create, editor can edit].
 *
 * @return array<string, array{0: string, 1: string, 2: int, 3: int|null, 4: int}>
 */
dataset('resources', [
    'campaigns' => [CampaignResource::class, Campaign::class, 200, 200, 200],
    'stats' => [StatResource::class, Stat::class, 200, 200, 200],
    'team members' => [TeamMemberResource::class, TeamMember::class, 200, 200, 200],
    'partners' => [PartnerResource::class, Partner::class, 200, 200, 200],
    'faqs' => [FaqResource::class, Faq::class, 200, 200, 200],
    'features' => [FeatureResource::class, Feature::class, 200, 200, 200],
    'how it works steps' => [HowItWorksStepResource::class, HowItWorksStep::class, 200, 200, 200],
    'pages' => [PageResource::class, Page::class, 200, 403, 200],
    'contact messages' => [ContactMessageResource::class, ContactMessage::class, 200, null, 200],
    'users' => [UserResource::class, User::class, 403, 403, 403],
    'roles' => [RoleResource::class, Role::class, 403, 403, 403],
]);

function recordFor(string $model, string $resource): object
{
    return match ($model) {
        Role::class => Role::findByName('editor'),
        ContactMessage::class => ContactMessage::factory()->create(),
        default => $model::factory()->create(),
    };
}

function editUrl(string $resource, object $record): string
{
    // Contact messages are view-only pages in the panel; others have an edit page.
    return $resource::getUrl($resource === ContactMessageResource::class ? 'view' : 'edit', ['record' => $record]);
}

/*
 * The permission matrix is asserted through the same static checks Filament uses to
 * guard its pages (Resource::canViewAny/canCreate/canEdit/canView). That is exactly
 * what decides 200 vs 403, without rendering three full admin pages per case, which
 * made this file the slowest in the suite. The HTTP tests further down confirm the
 * pages really are wired to those checks, for both roles.
 */
it('lets an editor do only what they are permitted', function (string $resource, string $model, int $index, ?int $create, int $edit) {
    $this->actingAs(User::factory()->create()->assignRole('editor'));
    $record = recordFor($model, $resource);

    expect($resource::canViewAny())->toBe($index === 200);

    if ($create !== null) {
        expect($resource::canCreate())->toBe($create === 200);
    }

    // Contact messages are view-only in the panel; every other resource has an edit page.
    $canOpen = $resource === ContactMessageResource::class ? $resource::canView($record) : $resource::canEdit($record);
    expect($canOpen)->toBe($edit === 200);
})->with('resources');

it('lets a super-admin do everything on every resource', function (string $resource, string $model) {
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
    $record = recordFor($model, $resource);

    expect($resource::canViewAny())->toBeTrue();

    if ($resource === ContactMessageResource::class) {
        expect($resource::canView($record))->toBeTrue();
    } else {
        expect($resource::canCreate())->toBeTrue()
            ->and($resource::canEdit($record))->toBeTrue();
    }
})->with('resources');

it('serves and blocks real panel pages according to the role', function () {
    $campaign = Campaign::factory()->create();

    // Editor: allowed content pages load, restricted ones are forbidden.
    $this->actingAs(User::factory()->create()->assignRole('editor'));
    $this->get(CampaignResource::getUrl('index'))->assertOk();
    $this->get(CampaignResource::getUrl('create'))->assertOk();
    $this->get(CampaignResource::getUrl('edit', ['record' => $campaign]))->assertOk();
    $this->get(PageResource::getUrl('create'))->assertForbidden();
    $this->get(UserResource::getUrl('index'))->assertForbidden();
    $this->get(RoleResource::getUrl('index'))->assertForbidden();

    // Super-admin: everything loads.
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
    $this->get(UserResource::getUrl('index'))->assertOk();
    $this->get(RoleResource::getUrl('index'))->assertOk();
    $this->get(PageResource::getUrl('create'))->assertOk();
});

it('restricts site settings to those with the permission', function () {
    $this->actingAs(User::factory()->create()->assignRole('editor'));
    $this->get(ManageSiteSettings::getUrl())->assertForbidden();

    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
    $this->get(ManageSiteSettings::getUrl())->assertOk();
});

it('grants site settings to a custom role with the permission', function () {
    $role = Role::create(['name' => 'settings-manager', 'guard_name' => 'web']);
    $role->givePermissionTo('manage site settings');
    $user = User::factory()->create()->assignRole('editor', $role);

    $this->actingAs($user)->get(ManageSiteSettings::getUrl())->assertOk();
});

it('redirects guests to the panel login', function (string $resource) {
    $this->get($resource::getUrl('index'))->assertRedirect();
    $this->get(ManageSiteSettings::getUrl())->assertRedirect();
})->with([CampaignResource::class, UserResource::class, RoleResource::class]);

it('forbids authenticated users without a staff role from the panel', function () {
    $this->actingAs(User::factory()->create())
        ->get('/admin')
        ->assertForbidden();
});

it('keeps the public contact page available to guests', function () {
    $this->get('/contact')->assertOk();
});

it('seeds idempotently', function () {
    $permissions = Permission::count();

    $this->seed(RolesSeeder::class);

    expect(Permission::count())->toBe($permissions)
        ->and(Role::count())->toBe(2)
        ->and(Role::findByName('editor')->hasPermissionTo('manage users'))->toBeFalse()
        ->and(Role::findByName('editor')->hasPermissionTo('update pages'))->toBeTrue()
        ->and(Role::findByName('editor')->hasPermissionTo('create pages'))->toBeFalse();
});
