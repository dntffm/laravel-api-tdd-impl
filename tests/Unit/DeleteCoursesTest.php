<?php

use function Pest\Laravel\deleteJson;

uses(Tests\TestCase::class);

$token = "1|oetmFJtTYYCnUArChcinxdazR4lzUsgZ1Kfv0MpD";

test('delete course with valid ID', function () use($token){
    deleteJson('api/courses/1', [], [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(200);
});

test('delete course with invalid ID', function () use($token){
    deleteJson('api/courses/1000', [], [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(404);
});
