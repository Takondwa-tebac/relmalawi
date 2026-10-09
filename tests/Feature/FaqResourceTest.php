<?php

use App\Filament\Admin\Resources\Faqs\Pages\CreateFaq;
use App\Filament\Admin\Resources\Faqs\Pages\EditFaq;
use App\Filament\Admin\Resources\Faqs\Pages\ListFaqs;
use App\Models\Faq;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('editor'));
});

it('lets an editor list faqs', function () {
    $faqs = Faq::factory()->count(3)->create();

    Livewire::test(ListFaqs::class)->loadTable()->assertCanSeeTableRecords($faqs);
});

it('creates a faq', function () {
    Livewire::test(CreateFaq::class)
        ->fillForm([
            'category' => Faq::CATEGORIES[0],
            'question' => 'Can I play twice?',
            'answer' => 'Yes.',
            'sort_order' => 1,
            'show_on_home' => true,
            'is_published' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Faq::where('question', 'Can I play twice?')->first())->show_on_home->toBeTrue();
});

it('validates required fields and category', function () {
    Livewire::test(CreateFaq::class)
        ->fillForm(['category' => 'Nonsense', 'question' => '', 'answer' => ''])
        ->call('create')
        ->assertHasFormErrors(['category', 'question' => 'required', 'answer' => 'required']);
});

it('edits a faq', function () {
    $faq = Faq::factory()->create();

    Livewire::test(EditFaq::class, ['record' => $faq->getRouteKey()])
        ->fillForm(['question' => 'Renamed?'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($faq->refresh()->question)->toBe('Renamed?');
});
