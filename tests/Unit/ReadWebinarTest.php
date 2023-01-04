<?php

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class);

$token = "12|MxSIkYQxCNFNC1sjuA8uafGZlGHxhWhhdD7sPtyE";

test('Get all webinar data', function () use($token){
    getJson('api/webinars', [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'data',
    ])
    ->assertStatus(200);
});
