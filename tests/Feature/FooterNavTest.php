<?php

it('shares the footer settings with every guest page', function () {
    $this->get('/faq')->assertInertia(fn ($page) => $page
        ->has('site.contact_email')
        ->has('site.footer_name')
        ->has('site.est_year'));
});

it('links the nav and footer to the faq page', function () {
    expect(file_get_contents(resource_path('js/components/guest/NavBar.vue')))->toContain("'/faq'")
        ->and(file_get_contents(resource_path('js/components/guest/Footer.vue')))->toContain("'/faq'")
        ->not->toContain('/privacy')
        ->not->toContain('/terms');
});
