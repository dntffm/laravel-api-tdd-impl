<?php

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class);

$token = "26|NngRzSiWWm2zKNJauveqntI9uBsg3zJVKXfeZSIv";
test("Add new webinar with valid data", function() use($token){
    postJson("api/admin/webinars",
    [
        "webinar_name" => "Programming 101 using python",
        "description" => "first step for someone who wants to be python programmer.",
        "price" => 0,
        "link" => "https://unej-id.zoom.us/j/97172817933?pwd=OVVsUkhxdnFHQ1kwYlR6UUJpNmNhdz09",
        "started_at" => "2022-09-14 13:01:00",
        "register_end_at" => "2022-09-14 13:01:00"
    ],
    [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(200);
});

/* test("Add new webinar with invalid data", function() use($token){
    postJson("api/webinars",
    [
        "webinar_name" => "Programming 101 using python",
        "description" => "first step for someone who wants to be python programmer.",
        "price" => 0,
        //"link" => "https://unej-id.zoom.us/j/97172817933?pwd=OVVsUkhxdnFHQ1kwYlR6UUJpNmNhdz09",
        "started_at" => "2022-09-14 13:01:00",
        "register_end_at" => "2022-09-14 13:01:00"
    ],
    [
        'Authorization' => 'Bearer '.$token
    ])
    ->assertHeader('Content-Type', 'application/json')
    ->assertJsonStructure([
        'success',
        'message',
    ])
    ->assertStatus(422);
}); */