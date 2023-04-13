<?php

use App\Models\Agenda;
use App\Models\User;
use App\Models\Webinar;

use function Pest\Laravel\actingAs;

test('customer dapat melihat daftar agenda', function() {
    $this->withoutExceptionHandling();

    $user = User::factory()->create([
        'role' => '0'
    ]);

    $webinar = Webinar::factory()->create([
        'started_at' => now()
    ]);

    $user->agendas()->attach($webinar);
    
    $response = actingAs($user)
    ->getJson(route('customer.agenda.index', [
        'month' => '12',
        'year' => '2022'
    ]))->getContent();
        
    expect($response)
    ->toBeJson()
    ->json()
    ->status->toBe('success')
    ->data->toBeArray()->each()
    ->toBeArray();
});