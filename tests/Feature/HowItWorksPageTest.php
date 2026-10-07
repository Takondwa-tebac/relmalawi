<?php

use App\Models\HowItWorksStep;
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
