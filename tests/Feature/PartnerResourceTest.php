<?php

use App\Enums\PartnerType;
use App\Filament\Admin\Resources\Partners\Pages\CreatePartner;
use App\Filament\Admin\Resources\Partners\Pages\EditPartner;
use App\Filament\Admin\Resources\Partners\Pages\ListPartners;
use App\Models\Partner;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
    Filament::setCurrentPanel('admin');
});

it('lists partners', function () {
    $partners = Partner::factory()->count(3)->create();

    Livewire::test(ListPartners::class)
        ->assertOk()
        ->assertCanSeeTableRecords($partners);
});

it('creates a partner', function () {
    Livewire::test(CreatePartner::class)
        ->fillForm(['name' => 'Radio Test', 'type' => PartnerType::Radio->value, 'sort_order' => 1, 'is_published' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Partner::where('name', 'Radio Test')->first()->type)->toBe(PartnerType::Radio);
});

it('creates a mobile-money partner', function () {
    Livewire::test(CreatePartner::class)
        ->fillForm(['name' => 'Wallet Co', 'type' => PartnerType::MobileMoney->value, 'sort_order' => 1, 'is_published' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Partner::where('name', 'Wallet Co')->first()->type)->toBe(PartnerType::MobileMoney);
});

it('requires a name and type', function () {
    Livewire::test(CreatePartner::class)
        ->fillForm(['name' => '', 'type' => null])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required', 'type' => 'required']);
});

it('edits a partner', function () {
    $partner = Partner::factory()->create();

    Livewire::test(EditPartner::class, ['record' => $partner->getRouteKey()])
        ->assertOk()
        ->fillForm(['name' => 'Renamed FM', 'type' => PartnerType::RadioAndTelevision->value])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($partner->refresh())->name->toBe('Renamed FM')->type->toBe(PartnerType::RadioAndTelevision);
});

it('keeps non-staff out of the panel', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/partners')->assertForbidden();
});
