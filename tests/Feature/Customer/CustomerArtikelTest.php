<?php

use App\Models\Article;
use App\Models\User;

use function Pest\Laravel\actingAs;

test('customer dapat melihat listing artikel', function(){
    //4 failed assertion
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);

    $admin = User::factory()->create([
        'role' => '1'
    ]);

    Article::factory()->create([
        'created_by' => $admin->id
    ]);

    $result = actingAs($user)
    ->getJson(route('customer.article.index'))
    ->getContent();
    
    expect($result)
    ->toBeJson()
    ->json()
    ->success->toBe(true)
    ->data->toBeArray()
    ->each()->toBeArray();
});

test('customer dapat melihat detail artikel', function(){
    //4 Failed assertion
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);

    $admin = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $admin->id
    ]);

    $result = actingAs($user)
    ->getJson(route('customer.article.show', $article->id))
    ->getContent();

    expect($result)
    ->toBeJson()
    ->json()
    ->success->toBe(true)
    ->data->toBeArray();
});
