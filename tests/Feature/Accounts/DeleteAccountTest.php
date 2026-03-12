<?php

use App\Models\Account;
use App\Models\User;

test('account can be soft deleted', function(){
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $response = $this->actingAs($account->user)
        ->delete(route('accounts.destroy', $account));
    $response->assertRedirect(route('accounts.index'));
    $response->assertSessionHasNoErrors();
    $this->assertSoftDeleted($account);
});

test('account cannot be soft deleted if user is not its owner', function(){
    $account = Account::factory()->create([
        'user_id' => User::factory()
    ]);
    $unauthorized_user = User::factory()->create();
    $response = $this->actingAs($unauthorized_user)
        ->delete(route('accounts.destroy', $account));
    $response->assertStatus(403);
    $this->assertNotSoftDeleted($account);
});