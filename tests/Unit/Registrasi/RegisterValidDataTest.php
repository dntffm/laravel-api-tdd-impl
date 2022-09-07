<?php

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class);

it('tes registrasi dengan data yang valid', function () {
    postJson('api/register', [
        'email' => 'test@example.com',
        'password' => '123123123',
        'name' => 'Denta Maulana'
    ])
        ->assertHeader('Content-Type', 'application/json')
        ->assertJsonStructure([
            'status',
            'message',
            'user'
        ])
        ->assertStatus(200);
});
