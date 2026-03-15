<?php

use App\Models\Account;
use App\Models\User;

test('account page can be displayed', function() {
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $response = $this->actingAs($account->user)
        ->get(route('accounts.show', $account));
    $response->assertOk();
    $response->assertSee($account->title);
});

test('account page cannot be displayed if user is not its owner', function() {
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $unauthorized_user = User::factory()->create();
    $response = $this->actingAs($unauthorized_user)
        ->get(route('accounts.show', $account));
    $response->assertStatus(403);
});