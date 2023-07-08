<?php

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class);

test("Login using valid data and valid credential", function() {
    postJson('api/login', [
        "email" => "test1@example.com",
        "password" => "123123123"
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
        'data'
    ])
    ->assertStatus(200);
});

test("Login using invalid data", function() {
    postJson('api/login', [
        "email" => "test1@example.com",
        //"password" => "123123123"
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(422);
});

test("Login using invalid credential (wrong email)", function() {
    postJson('api/login', [
        "email" => "wrongemail@example.com",
        "password" => "123123123"
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(404);
});

test("Login using invalid credential (wrong password)", function() {
    postJson('api/login', [
        "email" => "test1@example.com",
        "password" => "wrongpassword"
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(403);
});