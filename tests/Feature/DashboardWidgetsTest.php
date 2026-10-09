<?php

use App\Filament\Admin\Pages\Dashboard;
use App\Filament\Admin\Widgets\ContactMessagesChart;
use App\Filament\Admin\Widgets\ContentOverviewWidget;
use App\Filament\Admin\Widgets\LatestMessagesWidget;
use App\Filament\Admin\Widgets\NeedsAttentionWidget;
use App\Filament\Admin\Widgets\QuickActionsWidget;
use App\Models\Campaign;
use App\Models\ContactMessage;
use App\Models\Partner;
use App\Models\TeamMember;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
});

function dashboardUser(string $role): User
{
    return User::factory()->create()->assignRole($role);
}

it('mounts every widget on the dashboard for a super-admin', function () {
    $this->actingAs(dashboardUser('super-admin'))->get('/admin')->assertOk()->assertSee('Welcome back');

    Livewire::test(Dashboard::class)
        ->assertSeeLivewire(QuickActionsWidget::class)
        ->assertSeeLivewire(ContentOverviewWidget::class)
        ->assertSeeLivewire(ContactMessagesChart::class)
        ->assertSeeLivewire(LatestMessagesWidget::class)
        ->assertSeeLivewire(NeedsAttentionWidget::class);
});

it('gives an editor content shortcuts but no site settings link', function () {
    $this->actingAs(dashboardUser('editor'));

    Livewire::test(QuickActionsWidget::class)
        ->assertSee('Add campaign')
        ->assertSee('Open inbox')
        ->assertDontSee('Site settings');
});

it('reports live content and unread messages in the overview', function () {
    Campaign::factory()->count(2)->create(['is_published' => true]);
    Campaign::factory()->create(['is_published' => false]);
    TeamMember::factory()->count(3)->create(['is_published' => true]);
    Partner::factory()->count(4)->create(['is_published' => true]);
    ContactMessage::factory()->count(2)->create(['read_at' => null]);
    ContactMessage::factory()->create(['read_at' => now()]);

    $this->actingAs(dashboardUser('super-admin'));

    Livewire::test(ContentOverviewWidget::class)
        ->assertSee('Campaigns live')
        ->assertSee('1 unpublished')
        ->assertSee('Team members')
        ->assertSee('Media partners')
        ->assertSee('Unread messages')
        ->assertSee('3 received in the last 7 days');
});

it('flags published items that are missing images', function () {
    Campaign::factory()->create(['is_published' => true]);
    Partner::factory()->count(2)->create(['is_published' => true]);
    Partner::factory()->create(['is_published' => false]);

    $this->actingAs(dashboardUser('super-admin'));

    Livewire::test(NeedsAttentionWidget::class)
        ->assertSee('Campaigns without an image')
        ->assertSee('Partners without a logo')
        ->assertSee('Upload the missing image');
});

it('lists the latest contact messages newest first', function () {
    $old = ContactMessage::factory()->create(['name' => 'Older Person', 'created_at' => now()->subDay()]);
    $new = ContactMessage::factory()->create(['name' => 'Newer Person', 'created_at' => now()]);

    $this->actingAs(dashboardUser('editor'));

    Livewire::test(LatestMessagesWidget::class)
        ->assertCanSeeTableRecords([$new, $old], inOrder: true);
});

it('charts messages per day for the chosen range', function () {
    ContactMessage::factory()->count(2)->create(['created_at' => now()]);

    $this->actingAs(dashboardUser('super-admin'));

    $chart = Livewire::test(ContactMessagesChart::class)->assertOk();

    // getData() is protected, so read it from inside the component.
    $series = fn () => Closure::bind(fn () => $this->getData()['datasets'][0]['data'], $chart->instance(), ContactMessagesChart::class)();

    expect($series())->toHaveCount(30)->and(array_sum($series()))->toBe(2);

    $chart->set('filter', '7');
    expect($series())->toHaveCount(7);
});

it('hides message widgets from users who cannot view contact messages', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    expect(ContactMessagesChart::canView())->toBeFalse()
        ->and(LatestMessagesWidget::canView())->toBeFalse()
        ->and(ContentOverviewWidget::canView())->toBeFalse();
});

it('greets the user and lists links for the quick actions widget', function () {
    $user = dashboardUser('super-admin');
    $this->actingAs($user);

    Livewire::test(QuickActionsWidget::class)
        ->assertSee($user->name)
        ->assertSee('Site settings')
        ->assertSee('View public site');
});
