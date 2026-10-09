<?php

use App\Filament\Admin\Resources\Pages\Pages\EditPage;
use App\Filament\Admin\Resources\Pages\Pages\ListPages;
use App\Models\Page;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Inertia\Testing\AssertableInertia as Assert;
use Livewire\Livewire;

const SWITCHABLE = ['about', 'how-it-works', 'raffles', 'partnerships', 'technology', 'people', 'regulation', 'faq', 'contact'];

function switchOff(string $slug, array $extra = []): Page
{
    return Page::query()->updateOrCreate(['slug' => $slug], ['title' => ucfirst($slug), 'is_active' => false, ...$extra]);
}

describe('public visibility', function () {
    it('serves every page when nothing has been configured', function (string $slug) {
        $this->get(Page::pathFor($slug))->assertOk();
    })->with(SWITCHABLE);

    it('answers 404 to visitors for a page that is switched off', function (string $slug) {
        switchOff($slug);

        $this->get(Page::pathFor($slug))->assertNotFound();
    })->with(SWITCHABLE);

    it('shows a branded 404 page', function () {
        switchOff('about');

        $this->get('/about')->assertNotFound()->assertSee('Page', false)->assertSee('Back to home');
    });

    it('never switches the home page off', function () {
        $home = Page::factory()->create(['slug' => 'home', 'is_active' => false]);

        expect($home->fresh()->is_active)->toBeTrue();
        $this->get('/')->assertOk();
    });

    it('lets a signed-in staff member preview a switched-off page', function (string $role) {
        $this->seed(RolesSeeder::class);
        switchOff('about');

        $this->actingAs(User::factory()->create()->assignRole($role))
            ->get('/about')
            ->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('About')->where('previewingInactive', true));
    })->with(['super-admin', 'editor']);

    it('hides a switched-off page from signed-in users who are not staff', function () {
        switchOff('about');

        $this->actingAs(User::factory()->create())->get('/about')->assertNotFound();
    });

    it('does not flag a preview on pages that are on', function () {
        $this->seed(RolesSeeder::class);

        $this->actingAs(User::factory()->create()->assignRole('editor'))
            ->get('/about')
            ->assertInertia(fn (Assert $page) => $page->where('previewingInactive', false));
    });

    it('stops contact form submissions when the contact page is off, even for staff', function () {
        $this->seed(RolesSeeder::class);
        switchOff('contact');

        $payload = ['name' => 'A', 'email' => 'a@example.com', 'message' => 'Hello there'];

        $this->post('/contact', $payload)->assertNotFound();
        $this->actingAs(User::factory()->create()->assignRole('super-admin'))->post('/contact', $payload)->assertNotFound();
    });
});

describe('shared navigation props', function () {
    it('lists the default tabs in the default order', function () {
        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->where('nav.0', ['slug' => 'about', 'label' => 'About', 'href' => '/about'])
            ->where('nav.7.slug', 'faq')
            ->count('nav', 8)
            ->where('contactEnabled', true));
    });

    it('leaves out pages that are switched off', function () {
        switchOff('technology');

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->count('nav', 7)
            ->where('nav', fn ($nav) => collect($nav)->doesntContain('slug', 'technology'))
            ->where('activePaths', fn ($paths) => ! collect($paths)->contains('/technology') && collect($paths)->contains('/about')));
    });

    it('leaves out an active page that is set not to show in the navbar but keeps it reachable', function () {
        Page::factory()->create(['slug' => 'people', 'show_in_nav' => false]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->where('nav', fn ($nav) => collect($nav)->doesntContain('slug', 'people'))
            ->where('activePaths', fn ($paths) => collect($paths)->contains('/people')));

        $this->get('/people')->assertOk();
    });

    it('uses a custom label and a custom order', function () {
        Page::factory()->create(['slug' => 'faq', 'nav_label' => 'Questions', 'nav_sort' => 1]);
        Page::factory()->create(['slug' => 'regulation', 'nav_sort' => 2]);

        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->where('nav.0.slug', 'faq')
            ->where('nav.0.label', 'Questions')
            ->where('nav.1.slug', 'regulation')
            ->where('nav.2.slug', 'about'));
    });

    it('never lists home or contact as navbar tabs, and reports when contact is off', function () {
        $this->get('/')->assertInertia(fn (Assert $page) => $page
            ->where('nav', fn ($nav) => collect($nav)->pluck('slug')->intersect(['home', 'contact'])->isEmpty()));

        switchOff('contact');

        $this->get('/')->assertInertia(fn (Assert $page) => $page->where('contactEnabled', false));
    });

    it('refreshes when a page is switched on or off', function () {
        $this->get('/')->assertInertia(fn (Assert $page) => $page->count('nav', 8));

        $about = switchOff('about');
        $this->get('/')->assertInertia(fn (Assert $page) => $page->count('nav', 7));

        $about->update(['is_active' => true]);
        $this->get('/')->assertInertia(fn (Assert $page) => $page->count('nav', 8));
    });
});

describe('admin controls', function () {
    beforeEach(function () {
        $this->seed(RolesSeeder::class);
        Filament::setCurrentPanel('admin');
    });

    it('lets an editor switch a page off and set its navbar options from the edit form', function () {
        $this->actingAs(User::factory()->create()->assignRole('editor'));
        $page = Page::factory()->create(['slug' => 'raffles', 'title' => 'Raffles']);

        Livewire::test(EditPage::class, ['record' => $page->getKey()])
            ->fillForm(['is_active' => false, 'show_in_nav' => false, 'nav_label' => 'Games', 'nav_sort' => 4])
            ->call('save')
            ->assertHasNoFormErrors();

        expect($page->fresh())
            ->is_active->toBeFalse()
            ->show_in_nav->toBeFalse()
            ->nav_label->toBe('Games')
            ->nav_sort->toBe(4);
    });

    it('disables the active toggle on the home page', function () {
        $this->actingAs(User::factory()->create()->assignRole('super-admin'));
        $home = Page::factory()->create(['slug' => 'home', 'title' => 'Home']);

        Livewire::test(EditPage::class, ['record' => $home->getKey()])
            ->assertFormFieldIsDisabled('is_active');
    });

    it('switches pages on and off with the quick toggles in the list', function () {
        $this->actingAs(User::factory()->create()->assignRole('editor'));
        $about = Page::factory()->create(['slug' => 'about']);

        Livewire::test(ListPages::class)
            ->loadTable()
            ->call('updateTableColumnState', 'is_active', (string) $about->getKey(), false)
            ->call('updateTableColumnState', 'show_in_nav', (string) $about->getKey(), false);

        expect($about->fresh())->is_active->toBeFalse()->show_in_nav->toBeFalse();

        $this->get('/about')->assertOk(); // an editor can still preview it...

        auth()->logout();
        $this->get('/about')->assertNotFound(); // ...but visitors cannot.
    });

    it('switches several pages off or on at once and skips the home page', function () {
        $this->actingAs(User::factory()->create()->assignRole('super-admin'));
        $about = Page::factory()->create(['slug' => 'about']);
        $faq = Page::factory()->create(['slug' => 'faq']);
        $home = Page::factory()->create(['slug' => 'home']);

        Livewire::test(ListPages::class)
            ->loadTable()
            ->callTableBulkAction('deactivate', [$about, $faq, $home]);

        expect($about->fresh()->is_active)->toBeFalse()
            ->and($faq->fresh()->is_active)->toBeFalse()
            ->and($home->fresh()->is_active)->toBeTrue();

        Livewire::test(ListPages::class)
            ->loadTable()
            ->callTableBulkAction('activate', [$about, $faq]);

        expect($about->fresh()->is_active)->toBeTrue()
            ->and($faq->fresh()->is_active)->toBeTrue();
    });

    it('filters the list to switched-off pages', function () {
        $this->actingAs(User::factory()->create()->assignRole('super-admin'));
        $on = Page::factory()->create(['slug' => 'about']);
        $off = Page::factory()->create(['slug' => 'faq', 'is_active' => false]);

        Livewire::test(ListPages::class)
            ->loadTable()
            ->filterTable('is_active', false)
            ->assertCanSeeTableRecords([$off])
            ->assertCanNotSeeTableRecords([$on]);
    });
});
