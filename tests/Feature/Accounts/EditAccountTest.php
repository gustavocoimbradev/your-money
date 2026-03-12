<?php

use App\Models\Account;
use App\Models\User;

test('account editing page can be displayed', function() {
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $response = $this->actingAs($account->user)
        ->get(route('accounts.edit', $account));
    $response->assertOk();
});

test('account editing page cannot be displayed if user is not its owner', function(){
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $unauthorized_user = User::factory()->create();
    $response = $this->actingAs($unauthorized_user)
        ->get(route('accounts.edit', $account));
    $response->assertStatus(403);
});

test('account can be edited', function() {
    $account = Account::factory()->create([
        'title' => 'old title',
        'user_id' => User::factory()
    ]);
    $payload = [
        'title' => 'new title'
    ];
    $response = $this->actingAs($account->user)
        ->put(route('accounts.update', $account), $payload);
    $response->assertRedirect(route('accounts.show', $account));
    $response->assertSessionHasNoErrors();
    $this->assertDatabaseHas('accounts', [
        'title' => 'new title'
    ]);  
}); 

test('account cannot be edited if user is not its owner', function() {
    $account = Account::factory()->create([
        'title' => 'old title',
        'user_id' => User::factory()
    ]);
    $unauthorized_user = User::factory()->create();
    $payload = [
        'title' => 'new title'
    ];
    $response = $this->actingAs($unauthorized_user)
        ->put(route('accounts.update', $account), $payload);
    $response->assertStatus(403);
    $this->assertDatabaseMissing('accounts', [
        'title' => 'new title'
    ]);  
});