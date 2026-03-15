<?php

use App\Models\Account;
use App\Models\User;

test('transactions creating page can be displayed', function(){
    $user = User::factory()->create();
    $response = $this->actingAs($user)
        ->get(route('transactions.create'));
    $response->assertOk();
});

test('transaction can be created', function() {
    $user = User::factory()->create();
    $account = Account::factory()->create([
        'user_id' => $user->id
    ]);
    $payload = [
        'title' => 'Transaction title',
        'value' => 1.10,
        'reference' => '2026-03-12',
        'account_id' => $account->id 
    ];
    $response = $this->actingAs($user)
        ->post(route('transactions.store'), $payload);
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('transactions', $payload); 
}); 
