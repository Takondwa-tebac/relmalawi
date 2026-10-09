<?php

use App\Models\Faq;
use App\Models\Page;
use Database\Seeders\Content\FaqSeeder;
use Inertia\Testing\AssertableInertia as Assert;

it('shows only published faqs in order', function () {
    Page::factory()->create(['slug' => 'faq', 'title' => 'Everything you']);
    Faq::factory()->create(['question' => 'Second?', 'sort_order' => 2]);
    Faq::factory()->create(['question' => 'First?', 'sort_order' => 1]);
    Faq::factory()->unpublished()->create(['question' => 'Hidden?']);

    $this->get('/faq')->assertOk()->assertInertia(fn (Assert $page) => $page
        ->component('Faq')
        ->where('page.title', 'Everything you')
        ->has('faqs', 2)
        ->where('faqs.0.question', 'First?')
        ->where('faqs.1.question', 'Second?')
        ->has('faqs.0.category')
        ->has('faqs.0.answer'));
});

it('seeds the faq page idempotently with home picks across all categories', function () {
    $this->seed(FaqSeeder::class);
    $this->seed(FaqSeeder::class);

    expect(Faq::count())->toBeBetween(14, 16)
        ->and(Faq::forHome()->count())->toBeBetween(5, 6)
        ->and(Faq::query()->distinct()->pluck('category')->sort()->values()->all())->toEqual(collect(Faq::CATEGORIES)->sort()->values()->all())
        ->and(Page::where('slug', 'faq')->count())->toBe(1);

    $this->get('/faq')->assertInertia(fn (Assert $page) => $page->has('faqs', Faq::count()));
});

it('keeps commercially sensitive figures out of the seeded answers', function () {
    $this->seed(FaqSeeder::class);

    $text = Faq::all()->map(fn (Faq $faq) => $faq->question.' '.$faq->answer)->implode(' ');

    expect($text)->not->toContain('%')->not->toContain('10,000,000')->not->toContain('*555#');
});
