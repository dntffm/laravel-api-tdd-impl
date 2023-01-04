<?php

use Illuminate\Support\Facades\Auth;

use function Pest\Laravel\postJson;

uses(Tests\TestCase::class);

$token = "12|MxSIkYQxCNFNC1sjuA8uafGZlGHxhWhhdD7sPtyE";
test("Add new article with valid data", function() use($token){
    postJson("api/articles",
    [
        "article_title" => "Pomodor: the effective leaerning method",
        "article_content" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia iste fugit maxime temporibus eaque quibusdam eum, dolorum nulla aliquid fuga repellat corporis voluptates a neque architecto pariatur nesciunt laudantium? Tempore.",
        "created_by" => 1,
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

test("Add new article with invalid data", function() use($token){
    postJson("api/articles",
    [
        //"article_title" => "Pomodor: the effective leaerning method",
        "article_content" => "Lorem ipsum dolor sit amet consectetur adipisicing elit. Quia iste fugit maxime temporibus eaque quibusdam eum, dolorum nulla aliquid fuga repellat corporis voluptates a neque architecto pariatur nesciunt laudantium? Tempore.",
        "created_by" => 1,
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
});