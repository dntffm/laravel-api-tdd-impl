<?php

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class);

$token = "2|Nwayc7DFCi08gbPsMqcxAOvO9PvgJKonRP6JPvYL";

test('Get all articles data', function () use($token){
    getJson('api/articles', [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'data',
    ])
    ->assertStatus(200);
});
