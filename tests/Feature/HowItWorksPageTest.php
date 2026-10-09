<?php

use App\Models\Faq;
use App\Models\Feature;
use App\Models\HowItWorksStep;
use Database\Seeders\Content\HowItWorksPageSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('lists published steps in order with derived numbers', function () {
    HowItWorksStep::factory()->create(['title' => 'Second', 'sort_order' => 2]);
    HowItWorksStep::factory()->create(['title' => 'First', 'sort_order' => 1]);
    HowItWorksStep::factory()->unpublished()->create(['title' => 'Hidden', 'sort_order' => 3]);

    $this->get('/how-it-works')
        ->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('HowItWorks')
            ->has('steps', 2)
            ->where('steps.0.title', 'First')
            ->where('steps.0.number', '01')
            ->where('steps.1.title', 'Second')
            ->where('steps.1.number', '02'));
});

it('renumbers when an earlier step is unpublished', function () {
    $first = HowItWorksStep::factory()->create(['sort_order' => 1]);
    HowItWorksStep::factory()->create(['title' => 'Next', 'sort_order' => 2]);
    $first->update(['is_published' => false]);

    $this->get('/how-it-works')->assertInertia(fn (Assert $page) => $page
        ->has('steps', 1)
        ->where('steps.0.number', '01'));
});

it('passes the detail line, features and playing faqs', function () {
    HowItWorksStep::factory()->create(['sort_order' => 1, 'detail' => 'Extra line']);
    Feature::factory()->forGroup('how-it-works.ussd')->create(['title' => 'Select station', 'sort_order' => 1]);
    Feature::factory()->forGroup('raffles.formats')->create();
    Faq::factory()->create(['category' => 'Playing', 'question' => 'Playing Q?']);
    Faq::factory()->create(['category' => 'Playing', 'question' => 'Hidden Q?', 'is_published' => false]);
    Faq::factory()->create(['category' => 'Payments', 'question' => 'Other Q?']);

    $this->get('/how-it-works')->assertInertia(fn (Assert $page) => $page
        ->where('steps.0.detail', 'Extra line')
        ->has('features.ussd', 1)
        ->missing('features.formats')
        ->has('faqs', 1)
        ->where('faqs.0.question', 'Playing Q?'));
});

it('serves the seeded walkthrough and replaces placeholder steps without touching editor steps', function () {
    HowItWorksStep::factory()->create(['title' => 'Dial the shortcode', 'sort_order' => 1]);
    HowItWorksStep::factory()->create(['title' => 'Payout', 'sort_order' => 2]);
    HowItWorksStep::factory()->create(['title' => 'Editor step', 'sort_order' => 3]);

    $this->seed(HowItWorksPageSeeder::class);
    $this->seed(HowItWorksPageSeeder::class);

    expect(HowItWorksStep::where('title', 'Dial the shortcode')->exists())->toBeFalse()
        ->and(HowItWorksStep::where('title', 'Editor step')->exists())->toBeTrue()
        ->and(HowItWorksStep::count())->toBe(9)
        ->and(Feature::where('page_slug', 'how-it-works')->where('group', 'how-it-works.ussd')->count())->toBe(4);

    $this->get('/how-it-works')->assertInertia(fn (Assert $page) => $page
        ->has('features.ussd', 4)
        ->has('features.behind', 2)
        ->has('features.roles', 3)
        ->has('features.cta', 1));
});
