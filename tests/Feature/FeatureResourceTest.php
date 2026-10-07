<?php

use App\Enums\FeatureIcon;
use App\Filament\Admin\Resources\Features\Pages\CreateFeature;
use App\Filament\Admin\Resources\Features\Pages\EditFeature;
use App\Filament\Admin\Resources\Features\Pages\ListFeatures;
use App\Models\Feature;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('editor'));
});

it('lists features', function () {
    $features = Feature::factory()->count(2)->create();

    Livewire::test(ListFeatures::class)
        ->loadTable()
        ->assertCanSeeTableRecords($features);
});

it('filters features by page', function () {
    $about = Feature::factory()->forGroup('about.pillars')->create();
    $raffle = Feature::factory()->forGroup('raffles.formats')->create();

    Livewire::test(ListFeatures::class)
        ->loadTable()
        ->filterTable('page_slug', 'about')
        ->assertCanSeeTableRecords([$about])
        ->assertCanNotSeeTableRecords([$raffle]);
});

it('filters features by group', function () {
    $about = Feature::factory()->forGroup('about.pillars')->create();
    $raffle = Feature::factory()->forGroup('raffles.formats')->create();

    Livewire::test(ListFeatures::class)
        ->loadTable()
        ->filterTable('group', 'raffles.formats')
        ->assertCanSeeTableRecords([$raffle])
        ->assertCanNotSeeTableRecords([$about]);
});

it('creates a feature with an icon', function () {
    Livewire::test(CreateFeature::class)
        ->fillForm([
            'page_slug' => 'technology',
            'group' => 'technology.capabilities',
            'title' => 'Live dashboards',
            'icon' => 'bar-chart',
            'sort_order' => 3,
            'is_published' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $feature = Feature::firstWhere('title', 'Live dashboards');
    expect($feature->icon)->toBe(FeatureIcon::BarChart)->and($feature->sort_order)->toBe(3);
});

it('rejects an unknown icon key', function () {
    Livewire::test(CreateFeature::class)
        ->fillForm(['page_slug' => 'about', 'group' => 'about.pillars', 'title' => 'x', 'icon' => 'nope'])
        ->call('create')
        ->assertHasFormErrors(['icon']);
});

it('edits and unpublishes a feature', function () {
    $feature = Feature::factory()->create(['title' => 'Old']);

    Livewire::test(EditFeature::class, ['record' => $feature->getKey()])
        ->assertOk()
        ->fillForm(['title' => 'New', 'is_published' => false])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($feature->refresh()->title)->toBe('New')->and($feature->is_published)->toBeFalse();
});

it('keeps the enum and the Vue icon registry in sync', function () {
    $registry = file_get_contents(resource_path('js/components/guest/FeatureIcon.vue'));

    foreach (FeatureIcon::cases() as $icon) {
        expect($registry)->toMatch('/[\'"]?'.preg_quote($icon->value, '/').'[\'"]?\s*:/');
    }
});
