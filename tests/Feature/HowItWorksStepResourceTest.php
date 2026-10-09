<?php

use App\Filament\Admin\Resources\HowItWorksSteps\Pages\CreateHowItWorksStep;
use App\Filament\Admin\Resources\HowItWorksSteps\Pages\EditHowItWorksStep;
use App\Filament\Admin\Resources\HowItWorksSteps\Pages\ListHowItWorksSteps;
use App\Models\HowItWorksStep;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
});

it('lists steps', function () {
    $steps = HowItWorksStep::factory()->count(3)->create();

    Livewire::test(ListHowItWorksSteps::class)->assertOk()->assertCanSeeTableRecords($steps);
});

it('creates a step at the end of the list', function () {
    HowItWorksStep::factory()->create(['sort_order' => 4]);

    Livewire::test(CreateHowItWorksStep::class)
        ->fillForm(['title' => 'New step', 'body' => 'Body', 'detail' => 'More detail', 'icon' => 'trophy', 'is_published' => true])
        ->call('create')
        ->assertHasNoFormErrors();

    $step = HowItWorksStep::where('title', 'New step')->first();
    expect($step->sort_order)->toBe(5)->and($step->detail)->toBe('More detail');
});

it('requires a title', function () {
    Livewire::test(CreateHowItWorksStep::class)
        ->fillForm(['title' => ''])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('edits a step', function () {
    $step = HowItWorksStep::factory()->create();

    Livewire::test(EditHowItWorksStep::class, ['record' => $step->getKey()])
        ->fillForm(['title' => 'Edited', 'is_published' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($step->fresh())->title->toBe('Edited')->is_published->toBeFalse();
});

it('is ordered by sort_order', function () {
    $b = HowItWorksStep::factory()->create(['sort_order' => 2]);
    $a = HowItWorksStep::factory()->create(['sort_order' => 1]);

    Livewire::test(ListHowItWorksSteps::class)->assertCanSeeTableRecords([$a, $b], inOrder: true);
});
