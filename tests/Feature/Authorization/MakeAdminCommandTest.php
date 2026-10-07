<?php

use App\Models\User;

it('creates a new super-admin after prompting', function () {
    $this->artisan('cms:make-admin', ['email' => 'owner@example.com'])
        ->expectsQuestion('Name', 'Owner')
        ->expectsQuestion('Password', 'a-strong-password')
        ->assertSuccessful();

    $user = User::where('email', 'owner@example.com')->firstOrFail();

    expect($user->hasRole('super-admin'))->toBeTrue()
        ->and($user->can('manage roles'))->toBeTrue()
        ->and($user->hasVerifiedEmail())->toBeTrue();
});

it('promotes an existing user without prompting', function () {
    $user = User::factory()->create(['email' => 'staff@example.com']);

    $this->artisan('cms:make-admin', ['email' => 'staff@example.com', '--role' => 'editor'])
        ->assertSuccessful();

    expect($user->fresh()->hasRole('editor'))->toBeTrue();
});

it('rejects unknown roles and invalid emails', function () {
    $this->artisan('cms:make-admin', ['email' => 'nobody@example.com', '--role' => 'wizard'])->assertFailed();
    $this->artisan('cms:make-admin', ['email' => 'not-an-email'])->assertFailed();

    expect(User::where('email', 'nobody@example.com')->exists())->toBeFalse();
});
