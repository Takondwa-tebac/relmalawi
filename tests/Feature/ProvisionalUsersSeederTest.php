<?php

use App\Models\User;
use Database\Seeders\ProvisionalUsersSeeder;
use Filament\Facades\Filament;
use Illuminate\Support\Facades\Hash;

it('creates a verified super-admin and editor who can enter the panel', function () {
    $this->seed(ProvisionalUsersSeeder::class);

    $admin = User::firstWhere('email', 'admin@rel.test');
    $editor = User::firstWhere('email', 'editor@rel.test');

    expect($admin->hasRole('super-admin'))->toBeTrue()
        ->and($editor->hasRole('editor'))->toBeTrue()
        ->and($editor->hasRole('super-admin'))->toBeFalse()
        ->and($admin->email_verified_at)->not->toBeNull();

    $panel = Filament::getPanel('admin');
    expect($admin->canAccessPanel($panel))->toBeTrue()
        ->and($editor->canAccessPanel($panel))->toBeTrue();
});

it('is idempotent', function () {
    $this->seed(ProvisionalUsersSeeder::class);
    $this->seed(ProvisionalUsersSeeder::class);

    expect(User::whereIn('email', ['admin@rel.test', 'editor@rel.test'])->count())->toBe(2);
});

it('uses the password from SEED_USER_PASSWORD', function () {
    config(['cms.seed_user_password' => 'a-specific-seed-password']);

    $this->seed(ProvisionalUsersSeeder::class);

    expect(Hash::check('a-specific-seed-password', User::firstWhere('email', 'admin@rel.test')->password))->toBeTrue();
});

it('refuses to seed without a password', function () {
    config(['cms.seed_user_password' => null]);

    expect(fn () => (new ProvisionalUsersSeeder)->run())
        ->toThrow(RuntimeException::class, 'SEED_USER_PASSWORD');

    expect(User::count())->toBe(0);
});

it('does nothing in production', function () {
    $this->app['env'] = 'production';

    // Called directly: $this->seed() would trigger Laravel's "run in production?" prompt.
    (new ProvisionalUsersSeeder)->run();

    expect(User::count())->toBe(0);
});
