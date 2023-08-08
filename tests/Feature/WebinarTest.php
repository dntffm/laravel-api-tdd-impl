<?php

use App\Models\File;
use App\Models\User;
use App\Models\Webinar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;

use function Pest\Laravel\actingAs;

uses(RefreshDatabase::class);

test('admin dapat menambah webinar baru dengan valid data', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->make();
    $webinar['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');
    actingAs($user)
    ->postJson(route('admin.webinar.store'), $webinar->toArray())
    ->assertJson(['success' => true])
    ->assertStatus(200);
});

test('admin tidak dapat menambah webinar baru dengan invalid data', function() {
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->make();
    $webinar['webinar_name'] = null;
    $webinar['thumbnail'] = UploadedFile::fake()->image('thumbnail.jpg');
    actingAs($user)
    ->postJson(route('admin.webinar.store'), $webinar->toArray())
    ->assertJson(['success' => false])
    ->assertStatus(422);
});

test('admin dapat melihat daftar webinar', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->create();

    actingAs($user)
    ->getJson(route('admin.webinar.index'))
    ->assertJson(['success' => true])
    ->assertStatus(200);
});

test('admin dapat menghapus webinar dengan valid ID', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->create();

    actingAs($user)
    ->deleteJson(route('admin.webinar.destroy', $webinar->id))
    ->assertJson(['success' => true])
    ->assertStatus(200);
});

test('admin tidak dapat menghapus webinar dengan invalid ID', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->create();

    actingAs($user)
    ->deleteJson(route('admin.webinar.destroy', 999))
    ->assertJson(['success' => true])
    ->assertStatus(404);
});

test('admin dapat mengubah webinar dengan valid ID', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->create();
    $webinar['webinar_name'] = 'Webinar Baru';

    actingAs($user)
    ->putJson(route('admin.webinar.update', $webinar->id), $webinar->toArray())
    ->assertJson(['success' => true])
    ->assertStatus(200);
});

test('admin tidak dapat mengubah webinar dengan invalid ID', function() {
    $this->withoutExceptionHandling();
    $user = User::factory()->create([
        'role' => '1'
    ]);

    $webinar = Webinar::factory()->create();
    $webinar['webinar_name'] = 'Webinar Baru';

    actingAs($user)
    ->putJson(route('admin.webinar.update', 19999), $webinar->toArray())
    ->assertJson(['success' => true])
    ->assertStatus(404);
});
