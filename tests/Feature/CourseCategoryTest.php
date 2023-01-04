<?php

use App\Models\CourseCategory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('admin dapat membuat kategori dengan valid data', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $category = CourseCategory::factory()->make();

    actingAs($user)
    ->postJson(route('admin.course-category.store'), $category->toArray())
    ->assertStatus(200);
});

test('admin tidak dapat membuat kategori dengan invalid data', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $category = [];

    actingAs($user)
    ->postJson(route('admin.course-category.store'), $category)
    ->assertStatus(422);
});

test('admin dapat melihat semua kategori', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $category = CourseCategory::factory()->create();

    actingAs($user)
    ->getJson(route('admin.course-category.index'))
    ->assertStatus(200);
});

test('admin dapat menghapus kategori dengan valid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $category = CourseCategory::factory()->create();

    actingAs($user)
    ->deleteJson(route('admin.course-category.destroy', $category->id))
    ->assertStatus(200);
});

test('admin tidak dapat menghapus kategori dengan invalid ID', function() {
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $category = CourseCategory::factory()->create();

    actingAs($user)
    ->deleteJson(route('admin.course-category.destroy', 9999))
    ->assertStatus(404);
});