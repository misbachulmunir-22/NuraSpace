<?php

test('public registration screen is disabled and returns 404', function () {
    $response = $this->get('/register');

    $response->assertStatus(404);
});

test('public registration post endpoint is disabled and returns 404', function () {
    $response = $this->post('/register', [
        'name' => 'Stranger',
        'email' => 'stranger@example.com',
        'password' => 'Password123!',
        'password_confirmation' => 'Password123!',
    ]);

    $response->assertStatus(404);
    $this->assertGuest();
});
