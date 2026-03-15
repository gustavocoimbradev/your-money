<?php

use App\Models\User;

test('account creating page can be displayed', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)
        ->get(route('accounts.index'));
    $response->assertOk();
});

test('account creating page cannot be displayed if user is not authenticated', function(){
    $response = $this->get(route('accounts.index'));
    $response->assertStatus(302);
});

test('account can be created', function() {
    $user = User::factory()->create();
    $payload = [
        'title' => 'Bank'
    ];
    $response = $this->actingAs($user)
        ->post(route('accounts.store'), $payload);
    $response->assertRedirect(route('accounts.index'));
    $response->assertSessionHasNoErrors();
    $payload['user_id'] = $user->id;
    $this->assertDatabaseHas('accounts', [
        'title' => 'Bank',
        'current_balance' => 0,
        'user_id' => $user->id
    ]);
});

test('account cannot be created if user is not authenticated', function() {
    $user = User::factory()->create();
    $payload = [
        'title' => 'Bank',
    ];
    $response = $this->post(route('accounts.store'), $payload);
    $response->assertStatus(302);
    $payload['user_id'] = $user->id;
    $this->assertDatabaseMissing('accounts', [
        'title' => 'Bank',
        'current_balance' => 0,
        'user_id' => $user->id
    ]);
});