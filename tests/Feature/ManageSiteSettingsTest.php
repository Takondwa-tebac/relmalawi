<?php

use App\Filament\Admin\Pages\ManageSiteSettings;
use App\Models\Setting;
use App\Models\User;
use Database\Seeders\RolesSeeder;
use Filament\Facades\Filament;
use Inertia\Testing\AssertableInertia as Assert;

beforeEach(function () {
    $this->seed(RolesSeeder::class);
    Filament::setCurrentPanel('admin');
    $this->actingAs(User::factory()->create()->assignRole('super-admin'));
});

function validSettings(array $overrides = []): array
{
    return [
        'banner_text' => 'Brand new banner',
        'contact_email' => 'new@example.com',
        'footer_name' => 'New Footer Ltd',
        'est_year' => '2025',
        'codes_count' => 12,
        'codes_pattern' => '*123*{n}#',
        ...$overrides,
    ];
}

it('renders for a super-admin with defaults', function () {
    Livewire::test(ManageSiteSettings::class)
        ->assertOk()
        ->assertFormSet(['contact_email' => 'hello@relmw.com', 'codes_count' => '40']);
});

it('saves settings and shares them on the next request', function () {
    $this->get('/contact')->assertInertia(fn (Assert $page) => $page
        ->where('site.banner_text', fn ($v) => $v !== 'Brand new banner'));

    Livewire::test(ManageSiteSettings::class)
        ->fillForm(validSettings())
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertNotified();

    expect(Setting::get('footer_name'))->toBe('New Footer Ltd');

    $this->get('/contact')->assertInertia(fn (Assert $page) => $page
        ->where('site.banner_text', 'Brand new banner')
        ->where('site.contact_email', 'new@example.com')
        ->where('site.footer_name', 'New Footer Ltd')
        ->where('site.est_year', '2025')
        ->where('site.codes_count', 12)
        ->where('site.codes_pattern', '*123*{n}#'));
});

it('invalidates the cache when a setting changes', function () {
    Setting::put('footer_name', 'Cached');
    expect(Setting::get('footer_name'))->toBe('Cached');

    Setting::put('footer_name', 'Fresh');
    expect(Setting::get('footer_name'))->toBe('Fresh');
});

it('validates settings', function (array $overrides, string $field) {
    Livewire::test(ManageSiteSettings::class)
        ->fillForm(validSettings($overrides))
        ->call('save')
        ->assertHasFormErrors([$field]);
})->with([
    'bad email' => [['contact_email' => 'nope'], 'contact_email'],
    'pattern without {n}' => [['codes_pattern' => '*123#'], 'codes_pattern'],
    'pattern with letters' => [['codes_pattern' => 'abc{n}'], 'codes_pattern'],
    'non numeric count' => [['codes_count' => 'lots'], 'codes_count'],
    'empty banner' => [['banner_text' => ''], 'banner_text'],
]);

it('blocks users without a staff role', function () {
    $this->actingAs(User::factory()->create());

    $this->get('/admin/site-settings')->assertForbidden();
});
