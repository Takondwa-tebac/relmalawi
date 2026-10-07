<?php

use App\Filament\Admin\Resources\Campaigns\Pages\CreateCampaign;
use App\Filament\Admin\Resources\Campaigns\Pages\EditCampaign;
use App\Filament\Admin\Resources\Campaigns\Pages\ListCampaigns;
use App\Filament\Admin\Resources\Stats\Pages\ListStats;
use App\Models\Campaign;
use App\Models\Stat;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
});

it('lists campaigns', function () {
    $campaigns = Campaign::factory()->count(3)->create();

    Livewire::test(ListCampaigns::class)
        ->assertOk()
        ->assertCanSeeTableRecords($campaigns);
});

it('renders the create page', function () {
    Livewire::test(CreateCampaign::class)->assertOk();
});

it('renders the edit page', function () {
    $campaign = Campaign::factory()->create();

    Livewire::test(EditCampaign::class, ['record' => $campaign->getRouteKey()])
        ->assertOk()
        ->assertSchemaStateSet(['title' => $campaign->title]);
});

it('validates required fields', function () {
    Livewire::test(CreateCampaign::class)
        ->fillForm(['title' => null, 'alt_text' => null])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required', 'alt_text' => 'required']);
});

it('lists stats', function () {
    $stats = Stat::factory()->count(2)->create();

    Livewire::test(ListStats::class)->assertOk()->assertCanSeeTableRecords($stats);
});

it('keeps non-staff out of the resource', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/campaigns')->assertForbidden();
});
