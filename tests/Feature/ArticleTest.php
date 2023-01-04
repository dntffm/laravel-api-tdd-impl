<?php

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;

test('admin dapat membuat artikel dengan valid data', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->make();
    $article['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');

    actingAs($user)
    ->postJson(route('admin.article.store'), $article->toArray())
    ->assertStatus(200);
});

test('admin tidak dapat membuat artikel dengan invalid data', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->make();
    $article['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');
    $article['article_title'] = null;
    actingAs($user)
    ->postJson(route('admin.article.store'), $article->toArray())
    ->assertStatus(422);
});

test('admin dapat melihat daftar artikel', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    actingAs($user)
    ->getJson(route('admin.article.index'))
    ->assertStatus(200);
});

test('admin dapat melihat detail artikel', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    actingAs($user)
    ->getJson(route('admin.article.show', $article->id))
    ->assertStatus(200);
});

test('admin dapat menghapus artikel dengan valid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    actingAs($user)
    ->deleteJson(route('admin.article.destroy', $article->id))
    ->assertStatus(200);
});

test('admin tidak dapat menghapus artikel dengan invalid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    actingAs($user)
    ->deleteJson(route('admin.article.show', 23123))
    ->assertStatus(404);
});

test('admin dapat mengubah artikel dengan valid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    $article['article_title'] = 'New Title';
    $article['article_content'] = 'New Content';

    actingAs($user)
    ->putJson(route('admin.article.update', $article->id), $article->toArray())
    ->assertStatus(200);
});

test('admin tidak dapat mengubah artikel dengan invalid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $article = Article::factory()->create([
        'created_by' => $user->id
    ]);

    $article['article_title'] = 'New Title';
    $article['article_content'] = 'New Content';

    actingAs($user)
    ->putJson(route('admin.article.update', 9999), $article->toArray())
    ->assertStatus(404);
});