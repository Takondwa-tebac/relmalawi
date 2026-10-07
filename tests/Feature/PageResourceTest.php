<?php

use App\Filament\Admin\Resources\Pages\Pages\CreatePage;
use App\Filament\Admin\Resources\Pages\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
});

it('lists pages', function () {
    $pages = Page::factory()->count(2)->create();

    Livewire::test(ListPages::class)
        ->loadTable()
        ->assertOk()
        ->assertCanSeeTableRecords($pages);
});

it('creates a page', function () {
    Livewire::test(CreatePage::class)
        ->fillForm(['slug' => 'about', 'title' => 'A licensed', 'title_accent' => 'operator.'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Page::where('slug', 'about')->first()->title)->toBe('A licensed');
});

it('edits a page but never its slug', function () {
    $page = Page::factory()->create(['slug' => 'about', 'title' => 'Old']);

    Livewire::test(EditPage::class, ['record' => $page->getKey()])
        ->assertOk()
        ->assertFormFieldIsDisabled('slug')
        ->fillForm(['title' => 'New'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($page->refresh()->title)->toBe('New')->and($page->slug)->toBe('about');
});

it('lets editors but not guests into the resource', function () {
    $this->get('/admin/pages')->assertOk();

    auth()->logout();
    $this->actingAs(User::factory()->create());
    $this->get('/admin/pages')->assertForbidden();
});
