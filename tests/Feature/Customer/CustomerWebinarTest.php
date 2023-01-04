<?php

use App\Models\User;
use App\Models\Webinar;

use function Pest\Laravel\actingAs;

test('customer dapat mendaftarkan akun ke webinar', function(){
    $this->withoutExceptionHandling();
    
    $user = User::factory()->create([
        'role' => '0'
    ]);
    
    $webinar = Webinar::factory()->create([
        'price' => 0
    ]);

    actingAs($user)
    ->postJson(route('customer.join.webinar'), ['webinar_id' => $webinar->id])
    ->assertStatus(200);
}); 