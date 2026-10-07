<?php

use Laravel\Fortify\Features;

test('public registration is disabled', function () {
    expect(Features::enabled(Features::registration()))->toBeFalse();

    $this->get('/register')->assertNotFound();

    $this->post('/register', [
        'name' => 'Intruder',
        'email' => 'intruder@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ])->assertNotFound();

    $this->assertGuest();
});
