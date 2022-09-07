<?php

use function Pest\Laravel\getJson;

uses(Tests\TestCase::class);

$token = "2|Nwayc7DFCi08gbPsMqcxAOvO9PvgJKonRP6JPvYL";

test('readcourses', function () use($token){
    getJson('api/courses', [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'data',
    ])
    ->assertStatus(200);
});
